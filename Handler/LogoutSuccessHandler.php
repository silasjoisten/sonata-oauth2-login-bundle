<?php

namespace Exozet\Oauth2LoginBundle\Handler;

use Exozet\Oauth2LoginBundle\Authorization\Google;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Google
     */
    private $authorization;

    /**
     * @param TokenStorage $tokenStorage
     * @param Router $router
     */
    public function __construct(TokenStorage $tokenStorage, Router $router, Google $authorization)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->authorization = $authorization;
    }

    public function onLogoutSuccess(Request $request)
    {
        $client = $this->authorization->getClient();
        $client->revokeToken();

        $this->tokenStorage->setToken(null);
        $request->getSession()->invalidate();

        return new RedirectResponse($this->router->generate('exozet_oauth2_login_homepage'));
    }
}