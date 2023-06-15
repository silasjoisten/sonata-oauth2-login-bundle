<?php

declare(strict_types=1);

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Tests\User;

use PHPUnit\Framework\TestCase;
use SilasJoisten\Sonata\Oauth2LoginBundle\Google\Authorization;
use SilasJoisten\Sonata\Oauth2LoginBundle\Checker\Email;
use SilasJoisten\Sonata\Oauth2LoginBundle\User\UserProvider;

class UserProviderTest extends TestCase
{
    public function testLoadUserByUsername(): void
    {
        $user = \Mockery::mock('Sonata\UserBundle\Model\UserInterface');
        $userManager = \Mockery::mock('Sonata\UserBundle\Model\UserManagerInterface');
        $emailChecker = new EMail([]);
        $authorization = new Authorization($this->createMock(\Google_Client::class));

        $userManager->shouldReceive('findUserByUsernameOrEmail')->once()->andReturn($user);

        $provider = new UserProvider($userManager, $emailChecker, $authorization, []);
        $this->assertNotNull($result = $provider->loadUserByUsername('User'));
        $this->assertInstanceOf('Symfony\Component\Security\Core\User\UserInterface', $result);
    }

    public function testLoadUserByUsernameReturnsNullIfNotFound(): void
    {
        $user = \Mockery::mock('Sonata\UserBundle\Model\UserInterface');
        $userManager = \Mockery::mock('Sonata\UserBundle\Model\UserManagerInterface');
        $emailChecker = new EMail([]);
        $authorization = new Authorization($this->createMock(\Google_Client::class));

        $userManager->shouldReceive('findUserByUsernameOrEmail')->once()->andReturn(null);

        $provider = new UserProvider($userManager, $emailChecker, $authorization, []);
        $this->assertNull($provider->loadUserByUsername('User'));
    }
    public function loadUserByOAuthUserResponse(): void
    {
        $user = \Mockery::mock('Sonata\UserBundle\Model\UserInterface');
        $userManager = \Mockery::mock('Sonata\UserBundle\Model\UserManagerInterface');
        $emailChecker = new EMail([]);
        $google = $this->createMock(\Google_Client::class);
        $google->shouldReceive()->once
        $authorization = new Authorization();

        $userManager->shouldReceive('findUserByUsernameOrEmail')->once()->andReturn($user);

        $provider = new UserProvider($userManager, $emailChecker, $authorization, []);
        $this->assertNotNull($result = $provider->loadUserByUsername('User'));
        $this->assertInstanceOf('Symfony\Component\Security\Core\User\UserInterface', $result);
    }

    public function loadUserByOAuthUserResponseReturnsNullIfNotFound(): void
    {
        $user = \Mockery::mock('Sonata\UserBundle\Model\UserInterface');
        $userManager = \Mockery::mock('Sonata\UserBundle\Model\UserManagerInterface');
        $emailChecker = new EMail([]);
        $authorization = new Authorization($this->createMock(\Google_Client::class));

        $userManager->shouldReceive('findUserByUsernameOrEmail')->once()->andReturn(null);

        $provider = new UserProvider($userManager, $emailChecker, $authorization, []);
        $this->assertNull($provider->loadUserByUsername('User'));
    }
}
