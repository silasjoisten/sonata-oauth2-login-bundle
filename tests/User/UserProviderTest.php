<?php

declare(strict_types=1);

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Tests\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Mockery;
use PHPUnit\Framework\TestCase;
use SilasJoisten\Sonata\Oauth2LoginBundle\Google\Authorization;
use SilasJoisten\Sonata\Oauth2LoginBundle\Checker\Email;
use SilasJoisten\Sonata\Oauth2LoginBundle\User\UserProvider;
use Sonata\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserProviderTest extends TestCase
{
    public function testLoadUserByUsername(): void
    {
        $user = Mockery::mock('Sonata\UserBundle\Model\UserInterface');
        $userManager = Mockery::mock('Sonata\UserBundle\Model\UserManagerInterface');
        $emailChecker = new EMail([]);
        $authorization = new Authorization($this->createMock(\Google_Client::class));

        $userManager->shouldReceive('findUserByUsernameOrEmail')->once()->andReturn($user);

        $provider = new UserProvider($userManager, $emailChecker, $authorization, []);
        $this->assertNotNull($result = $provider->loadUserByUsername('User'));
        $this->assertInstanceOf('Symfony\Component\Security\Core\User\UserInterface', $result);
    }

    public function testLoadUserByIdentifier(): void
    {
        $user = Mockery::mock('Sonata\UserBundle\Model\UserInterface');
        $userManager = Mockery::mock('Sonata\UserBundle\Model\UserManagerInterface');
        $emailChecker = new EMail([]);
        $authorization = new Authorization($this->createMock(\Google_Client::class));

        $userManager->shouldReceive('findUserByUsernameOrEmail')->once()->andReturn($user);

        $provider = new UserProvider($userManager, $emailChecker, $authorization, []);
        $this->assertNotNull($result = $provider->loadUserByIdentifier('User'));
        $this->assertInstanceOf('Symfony\Component\Security\Core\User\UserInterface', $result);
    }

    public function testLoadUserByUsernameThrowsExceptionIfNotFound(): void
    {
        $userManager = Mockery::mock('Sonata\UserBundle\Model\UserManagerInterface');
        $emailChecker = new EMail([]);
        $authorization = new Authorization($this->createMock(\Google_Client::class));

        $userManager->shouldReceive('findUserByUsernameOrEmail')->once()->andReturn(null);

        $this->expectException(UserNotFoundException::class);
        $provider = new UserProvider($userManager, $emailChecker, $authorization, []);
        $provider->loadUserByUsername('User');
    }

    public function testLoadUserByIdentifierThrowsExceptionIfNotFound(): void
    {
        $userManager = Mockery::mock('Sonata\UserBundle\Model\UserManagerInterface');
        $emailChecker = new EMail([]);
        $authorization = new Authorization($this->createMock(\Google_Client::class));

        $userManager->shouldReceive('findUserByUsernameOrEmail')->once()->andReturn(null);

        $this->expectException(UserNotFoundException::class);
        $provider = new UserProvider($userManager, $emailChecker, $authorization, []);
        $provider->loadUserByIdentifier('User');
    }

    public function testLoadUserByOAuthUserResponse(): void
    {
        $user = Mockery::mock('Sonata\UserBundle\Model\UserInterface');
        $user->shouldReceive('addRole')->once();

        $userManager = Mockery::mock('Sonata\UserBundle\Model\UserManagerInterface');
        $userManager->shouldReceive('save')->once();

        $emailChecker = new EMail(['example.org'],['test@example.org' => ['MY_ROLE']]);
        $google = $this->createMock(\Google_Client::class);
        $authorization = new Authorization($google);

        $oauthToken = Mockery::mock(OAuthToken::class);
        $oauthToken->shouldReceive('getRawToken')->once()->andReturn('123456');

        $userResponse = Mockery::mock(UserResponseInterface::class);
        $userResponse->shouldReceive('getOAuthToken')->once()->andReturn($oauthToken);
        $userResponse->shouldReceive('getEmail')->times(4)->andReturn('test@example.org');

        $userManager->shouldReceive('findUserByUsernameOrEmail')->once()->andReturn($user);

        $provider = new UserProvider($userManager, $emailChecker, $authorization, []);
        $this->assertNotNull($result = $provider->loadUserByOAuthUserResponse($userResponse));
        $this->assertInstanceOf('Symfony\Component\Security\Core\User\UserInterface', $result);
    }

    public function testLoadUserByOAuthUserResponseWillCreateUserIfNotFound(): void
    {
        $user = Mockery::mock('Sonata\UserBundle\Model\UserInterface');
        $user->shouldReceive('addRole')->once();
        $user->shouldReceive('setUsername')->once();
        $user->shouldReceive('setEmail')->once();
        $user->shouldReceive('setFirstname')->once();
        $user->shouldReceive('setLastname')->once();
        $user->shouldReceive('setPassword')->once();
        $user->shouldReceive('setEnabled')->once();
        $user->shouldReceive('setRoles')->once();

        $userManager = Mockery::mock('Sonata\UserBundle\Model\UserManagerInterface');
        $userManager->shouldReceive('save')->once();
        $userManager->shouldReceive('create')->andReturn($user);

        $emailChecker = new EMail(['example.org'],['test@example.org' => ['MY_ROLE']]);
        $google = $this->createMock(\Google_Client::class);
        $authorization = new Authorization($google);

        $oauthToken = Mockery::mock(OAuthToken::class);
        $oauthToken->shouldReceive('getRawToken')->once()->andReturn('123456');

        $userResponse = Mockery::mock(UserResponseInterface::class);
        $userResponse->shouldReceive('getOAuthToken')->once()->andReturn($oauthToken);
        $userResponse->shouldReceive('getFirstName')->once();
        $userResponse->shouldReceive('getLastName')->once();
        $userResponse->shouldReceive('getEmail')->times(6)->andReturn('test@example.org');

        $userManager->shouldReceive('findUserByUsernameOrEmail')->once()->andReturn(null);

        $provider = new UserProvider($userManager, $emailChecker, $authorization, []);
        $this->assertNotNull($result = $provider->loadUserByOAuthUserResponse($userResponse));
        $this->assertInstanceOf('Symfony\Component\Security\Core\User\UserInterface', $result);
    }

    public function testLoadUserByOAuthUserResponseWillThrowException(): void
    {
        $user = Mockery::mock('Sonata\UserBundle\Model\UserInterface');
        $user->shouldReceive('addRole')->never();
        $user->shouldReceive('setUsername')->never();
        $user->shouldReceive('setEmail')->never();
        $user->shouldReceive('setFirstname')->never();
        $user->shouldReceive('setLastname')->never();
        $user->shouldReceive('setPassword')->never();
        $user->shouldReceive('setEnabled')->never();
        $user->shouldReceive('setRoles')->never();

        $userManager = Mockery::mock('Sonata\UserBundle\Model\UserManagerInterface');
        $userManager->shouldReceive('save')->never();

        $emailChecker = new EMail(['example.org']);
        $google = Mockery::mock(\Google_Client::class);
        $google->shouldReceive('setAccessType')->once();
        $google->shouldReceive('revokeToken')->once()->andReturn(true);
        $authorization = new Authorization($google);

        $oauthToken = Mockery::mock(OAuthToken::class);
        $oauthToken->shouldReceive('getRawToken')->once()->andReturn('123456');

        $userResponse = Mockery::mock(UserResponseInterface::class);
        $userResponse->shouldReceive('getOAuthToken')->once()->andReturn($oauthToken);
        $userResponse->shouldReceive('getFirstName')->never();
        $userResponse->shouldReceive('getLastName')->never();
        $userResponse->shouldReceive('getEmail')->times(2)->andReturn('test@example.co.uk');

        $userManager->shouldReceive('findUserByUsernameOrEmail')->never();
        $provider = new UserProvider($userManager, $emailChecker, $authorization, []);
        $this->expectException(AuthenticationException::class);
        $provider->loadUserByOAuthUserResponse($userResponse);
    }

    public function testRefreshUser(): void
    {
        $user = Mockery::mock(UserInterface::class);
        $user->shouldReceive('getUsername')->once()->andReturn('test@example');
        $userManager = Mockery::mock('Sonata\UserBundle\Model\UserManagerInterface');
        $userManager->shouldReceive('getClass')->once()->andReturn(UserInterface::class);
        $emailChecker = new EMail([]);
        $authorization = new Authorization($this->createMock(\Google_Client::class));

        $userManager->shouldReceive('findUserByUsernameOrEmail')->once()->andReturn($user);

        $provider = new UserProvider($userManager, $emailChecker, $authorization, []);
        $this->assertNotNull($result = $provider->refreshUser($user));
        $this->assertInstanceOf('Symfony\Component\Security\Core\User\UserInterface', $result);
    }

    public function testRefreshUserWillThrowException(): void
    {
        $user = Mockery::mock(UserInterface::class);
        $user->shouldReceive('getUsername')->once()->andReturn('test@example');
        $userManager = Mockery::mock('Sonata\UserBundle\Model\UserManagerInterface');
        $userManager->shouldReceive('getClass')->once()->andReturn(UserInterface::class);
        $emailChecker = new EMail([]);
        $authorization = new Authorization($this->createMock(\Google_Client::class));

        $userManager->shouldReceive('findUserByUsernameOrEmail')->once()->andReturn(null);
        $this->expectException(UserNotFoundException::class);
        $provider = new UserProvider($userManager, $emailChecker, $authorization, []);
        $provider->refreshUser($user);
    }

    public function testRefreshUserThrowsException(): void
    {
        $user = Mockery::mock(UserInterface::class);
        $user->shouldReceive('getUsername')->once()->andReturn('test@example');
        $userManager = Mockery::mock('Sonata\UserBundle\Model\UserManagerInterface');
        $userManager->shouldReceive('getClass')->once()->andReturn('Custom\User\Class');
        $emailChecker = new EMail([]);
        $authorization = new Authorization($this->createMock(\Google_Client::class));

        $userManager->shouldReceive('findUserByUsernameOrEmail')->once()->andReturn($user);

        $provider = new UserProvider($userManager, $emailChecker, $authorization, []);
        $this->expectException(UnsupportedUserException::class);
        $provider->refreshUser($user);
    }
}
