<?php

namespace Exozet\Oauth2LoginBundle\Twig;

class RenderButtonExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'render_exozet_login_button',
                array($this, 'renderExozetLoginButton'),
                array('is_safe' => array('html'), 'needs_environment' => true)),
        );
    }

    public function renderExozetLoginButton(\Twig_Environment $environment)
    {
        return $environment->render('@ExozetOauth2Login/Default/exozet_login.html.twig');
    }
}