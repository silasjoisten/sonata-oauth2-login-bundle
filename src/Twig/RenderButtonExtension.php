<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RenderButtonExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'render_login_button',
                [$this, 'renderLoginButton'],
                [
                    'is_safe' => ['html'],
                    'needs_environment' => true,
                ]
            ),
        ];
    }

    public function renderLoginButton(Environment $environment, array $options = []): string
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
