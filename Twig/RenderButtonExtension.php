<?php

namespace Exozet\Oauth2LoginBundle\Twig;

class RenderButtonExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'render_exozet_login_button',
                array($this, 'renderExozetLoginButton'),
                array('is_safe' => array('html'), 'needs_environment' => true)),
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
            'value' => 'Exozet Login'
        );
        $options = array_merge($defaults, $options);

        return $environment->render('@ExozetOauth2Login/Default/exozet_login.html.twig', array('options' => $options));
    }
}