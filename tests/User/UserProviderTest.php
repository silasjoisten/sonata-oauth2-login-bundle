<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Tests\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use PHPUnit\Framework\TestCase;
use SilasJoisten\Sonata\Oauth2LoginBundle\Checker\Email;
use SilasJoisten\Sonata\Oauth2LoginBundle\Google\Authorization;
use SilasJoisten\Sonata\Oauth2LoginBundle\Test\Fixtures\User;
use SilasJoisten\Sonata\Oauth2LoginBundle\User\UserProvider;
use Sonata\UserBundle\Entity\UserManager;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProviderTest extends TestCase
{
    protected $userManager;
    protected $authorization;
    protected $response;
    protected $token;
    protected $client;
    protected $user;

    public function setUp(): void
    {
        $this->userManager = $this->createMock(UserManager::class);
        $this->authorization = $this->createMock(Authorization::class);

        $this->response = $this->createMock(UserResponseInterface::class);
        $this->client = $this->createMock(\Google_Client::class);
        $this->user = new User();
    }

    public function testLoadUserByOAuthUserResponseValidEmail(): void
    {
        $this->response
            ->expects($this->once())
            ->method('getOAuthToken')
            ->willReturn(new OAuthToken('thisIsaTestSecret'));

        $this->authorization
            ->expects($this->never())
            ->method('getClient')
            ->willReturn($this->client);

        $this->client
            ->expects($this->never())
            ->method('revokeToken')
            ->with('thisIsaTestSecret')
            ->willReturn(true);

        $email = new Email(['@email.com']);

        $this->response
            ->expects($this->atLeast(3))
            ->method('getEmail')
            ->willReturn('test@email.com');

        $this->response
            ->expects($this->never())
            ->method('getFirstName')
            ->willReturn('Test');

        $this->response
            ->expects($this->never())
            ->method('getLastName')
            ->willReturn('Test-Lastname');

        $this->userManager
            ->expects($this->once())
            ->method('findUserByUsernameOrEmail')
            ->with('test@email.com')
            ->willReturn($this->user);

        $this->userManager
            ->expects($this->once())
            ->method('save')
            ->with($this->user);

        $this->userManager
            ->expects($this->never())
            ->method('create')
            ->with($this->user);

        $userProvider = new UserProvider(
            $this->userManager,
            $email,
            $this->authorization,
            ['ROLE_SONATA_ADMIN']);

        $user = $userProvider->loadUserByOAuthUserResponse($this->response);
        $this->assertInstanceOf(UserInterface::class, $user);
    }

    public function testLoadUserByOAuthUserResponseValidEmailAndValidCustomEmails(): void
    {
        $this->response
            ->expects($this->once())
            ->method('getOAuthToken')
            ->willReturn(new OAuthToken('thisIsaTestSecret'));

        $this->authorization
            ->expects($this->never())
            ->method('getClient')
            ->willReturn($this->client);

        $this->client
            ->expects($this->never())
            ->method('revokeToken')
            ->with('thisIsaTestSecret')
            ->willReturn(true);

        $email = new Email(['@foo.com'], ['fooo@email.com' => ['ROLE_SUPER_ADMIN']]);

        $this->response
            ->expects($this->atLeast(3))
            ->method('getEmail')
            ->willReturn('fooo@email.com');

        $this->userManager
            ->expects($this->once())
            ->method('findUserByUsernameOrEmail')
            ->with('fooo@email.com')
            ->willReturn($this->user);

        $this->userManager
            ->expects($this->once())
            ->method('save')
            ->with($this->user);

        $this->userManager
            ->expects($this->never())
            ->method('create')
            ->with($this->user);

        $userProvider = new UserProvider(
            $this->userManager,
            $email,
            $this->authorization,
            ['ROLE_SONATA_ADMIN']
        );

        $user = $userProvider->loadUserByOAuthUserResponse($this->response);
        $this->assertInstanceOf(UserInterface::class, $user);
    }

    public function testLoadUserByOAuthUserResponseCreateUserByEmail(): void
    {
        $this->response
            ->expects($this->once())
            ->method('getOAuthToken')
            ->willReturn(new OAuthToken('thisIsaTestSecret'));

        $this->authorization
            ->expects($this->never())
            ->method('getClient')
            ->willReturn($this->client);

        $this->client
            ->expects($this->never())
            ->method('revokeToken')
            ->with('thisIsaTestSecret')
            ->willReturn(true);

        $email = new Email(['@email.com']);

        $this->response
            ->expects($this->atLeast(3))
            ->method('getEmail')
            ->willReturn('fooo@email.com');

        $this->response
            ->expects($this->once())
            ->method('getFirstName')
            ->willReturn('Test');

        $this->response
            ->expects($this->once())
            ->method('getLastName')
            ->willReturn('Test-Lastname');

        $this->userManager
            ->expects($this->once())
            ->method('findUserByUsernameOrEmail')
            ->with('fooo@email.com')
            ->willReturn(null);

        $this->userManager
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->user);

        $this->userManager
            ->expects($this->once())
            ->method('save')
            ->with($this->user);

        $userProvider = new UserProvider(
            $this->userManager,
            $email,
            $this->authorization,
            ['ROLE_SONATA_ADMIN']);

        $user = $userProvider->loadUserByOAuthUserResponse($this->response);
        $this->assertInstanceOf(UserInterface::class, $user);
        $this->assertEquals('fooo@email.com', $user->getEmail());
        $this->assertEquals('fooo@email.com', $user->getUsername());
        $this->assertEquals('Test', $user->getFirstname());
        $this->assertEquals('Test-Lastname', $user->getLastname());
        $this->assertEquals(['ROLE_SONATA_ADMIN', 'ROLE_USER'], $user->getRoles());
    }

    public function testLoadUserByOAuthUserResponseException(): void
    {
        $this->expectException(AuthenticationException::class);

        $this->response
            ->expects($this->once())
            ->method('getOAuthToken')
            ->willReturn(new OAuthToken('thisIsaTestSecret'));

        $this->response
            ->expects($this->exactly(2))
            ->method('getEmail')
            ->willReturn('fooo@gmail.com');

        $this->client
            ->expects($this->once())
            ->method('revokeToken')
            ->willReturn(true);

        $this->authorization
            ->expects($this->once())
            ->method('getClient')
            ->willReturn($this->client);

        $userProvider = new UserProvider(
            $this->userManager,
            new Email(['test@email.com']),
            $this->authorization,
            ['ROLE_SONATA_ADMIN']
        );

        $userProvider->loadUserByOAuthUserResponse($this->response);
    }
}
