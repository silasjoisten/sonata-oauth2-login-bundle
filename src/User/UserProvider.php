<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use SilasJoisten\Sonata\Oauth2LoginBundle\Checker\Email;
use SilasJoisten\Sonata\Oauth2LoginBundle\Google\Authorization;
use Sonata\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements OAuthAwareUserProviderInterface, UserProviderInterface
{
    public function __construct(
        private UserManagerInterface $userManager,
        private Email $emailChecker,
        private Authorization $authorization,
        private array $defaultUserRoles
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username): UserInterface
    {
        if (($user = $this->userManager->findUserByUsernameOrEmail($username))) {
            return $user;
        }

        throw new UserNotFoundException();
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response): UserInterface
    {
        $token = $response->getOAuthToken()->getRawToken();

        if (!$this->emailChecker->isEmailValid($response->getEmail())
            && !$this->emailChecker->hasCustomRoles($response->getEmail())
        ) {
            $client = $this->authorization->getClient();
            $client->revokeToken($token);

            throw new AuthenticationException('Invalid Google Account');
        }

        try {
            $user = $this->loadUserByUsername($response->getEmail());
        } catch (UserNotFoundException) {
            $user = $this->userManager->create();
            $user->setUsername($response->getEmail());
            $user->setEmail($response->getEmail());
            $user->setFirstname($response->getFirstName());
            $user->setLastname($response->getLastName());
            $user->setPassword('');
            $user->setEnabled(true);
            $user->setRoles($this->defaultUserRoles);
        }

        if ($customRoles = $this->emailChecker->getCustomRoles($response->getEmail())) {
            foreach ($customRoles as $customRole) {
                $user->addRole($customRole);
            }
        }

        $this->userManager->save($user);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Expected an instance of %s, but got "%s".', $this->userManager->getClass(), get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class): bool
    {
        $userClass = $this->userManager->getClass();

        return $userClass === $class || is_subclass_of($class, $userClass);
    }
}
