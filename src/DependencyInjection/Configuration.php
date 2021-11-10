<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('sonata_oauth2_login');

        // Keep compatibility with symfony/config < 4.2
        if (!method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->root('sonata_admin');
        } else {
            $rootNode = $treeBuilder->getRootNode();
        }

        $rootNode
            ->children()
                ->arrayNode('default_user_roles')
                    ->info('Used for user creation after success validation')
                    ->example('ROLE_SONATA_ADMIN')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('valid_email_domains')
                    ->info('Needed for email domain validation')
                    ->example('@example.com')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('custom_emails')
                    ->defaultValue([])
                    ->info('Customize Emails with specific role')
                    ->prototype('array')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
