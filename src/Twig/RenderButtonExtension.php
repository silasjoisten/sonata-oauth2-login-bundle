<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RenderButtonExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction(
                'render_login_button',
                [$this, 'renderLoginButton'],
                [
                    'is_safe' => ['html'],
                    'needs_environment' => true,
                ]
            ),
        );
    }

    /**
     * @param Environment $environment
     * @param array       $options
     *
     * @return string
     */
    public function renderLoginButton(Environment $environment, array $options = [])
    {
        $defaults = [
            'class' => 'btn btn-danger btn-block btn-flat',
            'value' => 'Google Login',
        ];
        $options = array_merge($defaults, $options);

        return $environment->render(
            '@SonataOauth2Login/Default/button_login.html.twig',
            [
                'options' => $options,
            ]
        );
    }
}
