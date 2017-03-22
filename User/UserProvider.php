<?php

namespace Exozet\Oauth2LoginBundle\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements OAuthAwareUserProviderInterface, UserProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        return new User(
            $username,
            null,
            array(),
            true,
            null,
            null,
            true,
            true,
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        return new User(
            $response->getEmail(),
            $response->getTokenSecret(),
            ['ROLE_USER'],
            true,
            $response->getProfilePicture(),
            $response->getRealName()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return new User(
            $user->getUsername(),
            $user->getPassword(),
            $user->getRoles(),
            $user->isEnabled(),
            $user->getProfilePicture(),
            $user->getFullName(),
            $user->isAccountNonExpired(),
            $user->isCredentialsNonExpired(),
            $user->isAccountNonLocked()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === User::class;
    }
}