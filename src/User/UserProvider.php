<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\User;

use SilasJoisten\Sonata\Oauth2LoginBundle\Checker\Email;
use SilasJoisten\Sonata\Oauth2LoginBundle\Google\Authorization;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Sonata\UserBundle\Entity\UserManager;
use Sonata\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements OAuthAwareUserProviderInterface, UserProviderInterface
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var Authorization
     */
    private $authorization;

    /**
     * @var Email
     */
    private $emailChecker;

    /**
     * @var array
     */
    private $defaultUserRoles;

    /**
     * @param UserManagerInterface $userManager
     * @param Email                $emailChecker
     * @param Authorization        $authorization
     * @param array                $defaultUserRoles
     */
    public function __construct(
        UserManagerInterface $userManager,
        Email $emailChecker,
        Authorization $authorization,
        array $defaultUserRoles
    ) {
        $this->userManager = $userManager;
        $this->emailChecker = $emailChecker;
        $this->authorization = $authorization;
        $this->defaultUserRoles = $defaultUserRoles;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        return $this->userManager->findUserByUsernameOrEmail($username);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $token = $response->getOAuthToken()->getRawToken();

        if (!$this->emailChecker->isEmailValid($response->getEmail())
            && !$this->emailChecker->hasCustomRoles($response->getEmail())
        ) {
            $client = $this->authorization->getClient();
            $client->revokeToken($token);

            throw new AuthenticationException('Invalid Google Account');
        }

        $user = $this->loadUserByUsername($response->getEmail());

        if (!$user) {
            $user = $this->userManager->create();
            $user->setUsername($response->getEmail());
            $user->setEmail($response->getEmail());
            $user->setFirstname($response->getFirstName());
            $user->setLastname($response->getLastName());
            $user->setPassword('');
            $user->setEnabled(true);
            $user->setRoles($this->defaultUserRoles);
        }

        if ($customRoles = $this->emailChecker->hasCustomRoles($response->getEmail())) {
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
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf(
                'Expected an instance of %s, but got "%s".',
                $this->userManager->getClass(),
                get_class($user)
            ));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        $userClass = $this->userManager->getClass();

        return $userClass === $class || is_subclass_of($class, $userClass);
    }
}
