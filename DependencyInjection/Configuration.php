<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sonata_oauth2_login');
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
            ->end()
        ;

        return $treeBuilder;
    }
}
