<?php

namespace Exozet\Oauth2LoginBundle\Twig;

class RenderButtonExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFunction('render_exozet_login_button', [$this, 'renderExozetLoginButton'],
                [
                    'is_safe' => ['html'],
                    'needs_environment' => true
                ]
            ),
        );
    }

    public function renderExozetLoginButton(\Twig_Environment $environment)
    {
        return $environment->render('@ExozetOauth2Login/Default/exozet_login.html.twig');
    }
}