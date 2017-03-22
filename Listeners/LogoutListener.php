<?php
namespace Exozet\Oauth2LoginBundle\Listeners;

use Exozet\Oauth2LoginBundle\Authorization\Google;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class LogoutListener implements LogoutHandlerInterface
{
    /**
     * @var Google
     */
    protected $authorization;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @param Google $authorization
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(Google $authorization, TokenStorageInterface $tokenStorage)
    {
        $this->authorization = $authorization;
        $this->tokenStorage = $tokenStorage;
    }

    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $client = $this->authorization->getClient();
        $client->revokeToken();

        $this->tokenStorage->setToken(null);
        $request->getSession()->invalidate();
    }
}