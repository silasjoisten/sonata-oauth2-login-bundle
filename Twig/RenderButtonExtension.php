<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Twig;

class RenderButtonExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'render_login_button',
                array($this, 'renderLoginButton'),
                array(
                    'is_safe' => array('html'),
                    'needs_environment' => true
                )
            ),
        );
    }

    /**
     * @param \Twig_Environment $environment
     * @param array             $options
     *
     * @return string
     */
    public function renderExozetLoginButton(\Twig_Environment $environment, array $options = array())
    {
        $defaults = array(
            'class' => 'btn btn-danger btn-block btn-flat',
            'value' => 'Google Login'
        );
        $options = array_merge($defaults, $options);

        return $environment->render(
            '@SilasJoistenSonataOauth2Login/Default/button_login.html.twig',
            array(
                'options' => $options
            )
        );
    }
}