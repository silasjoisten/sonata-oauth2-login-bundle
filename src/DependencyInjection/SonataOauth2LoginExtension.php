<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class SonataOauth2LoginExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('sonata_oauth2_login.valid_email_domains', $config['valid_email_domains']);
        $container->setParameter('sonata_oauth2_login.default_user_roles', $config['default_user_roles']);
        $container->setParameter('sonata_oauth2_login.custom_emails', $config['custom_emails']);
    }
}
