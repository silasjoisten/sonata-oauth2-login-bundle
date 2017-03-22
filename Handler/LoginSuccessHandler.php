<?php

namespace Exozet\Oauth2LoginBundle\Handler;

use Exozet\Oauth2LoginBundle\Authorization\Google;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LoginSuccessHandler
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Google
     */
    private $authorization;

    /**
     * @param Google $authorization
     */
    public function __construct(Google $authorization)
    {
        $this->authorization = $authorization;
    }

    public function onLoginSuccess(Request $request)
    {
        $this->authorization->getClient();
    }
}