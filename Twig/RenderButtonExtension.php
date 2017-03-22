<?php

namespace Exozet\Oauth2LoginBundle\Twig;

class RenderButtonExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'render_exozet_login_button' => new \Twig_SimpleFunction(
                $this,
                'renderExozetLoginButton',
                array('needs_environment' => true)
            ),
        );
    }

    public function renderExozetLoginButton(\Twig_Environment $environment)
    {
        return $environment->render('@ExozetOauth2Login/Default/exozet_login.html.twig');
    }
}