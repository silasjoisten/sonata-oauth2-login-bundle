<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use SilasJoisten\Sonata\Oauth2LoginBundle\DependencyInjection\SonataOauth2LoginExtension;

/**
 * @group di
 */
class AppExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     *
     * @dataProvider availableSecurityProvider
     * @dataProvider availableServicesProvider
     * @dataProvider availableTwigExtensionsProvider
     */
    public function availableServices($service)
    {
        $this->load();

        $this->assertContainerBuilderHasService($service);
    }

    /**
     * @return array
     */
    public function availableSecurityProvider()
    {
        return [
            ['sonata_oauth2_login.google.authorization'],
        ];
    }

    /**
     * @return array
     */
    public function availableServicesProvider()
    {
        return [
            ['sonata_oauth2_login.checker.email'],
            ['sonata_oauth2_login.user.provider'],
            ['sonata_oauth2_login.google.client'],
        ];
    }

    /**
     * @return array
     */
    public function availableTwigExtensionsProvider()
    {
        return [
            ['sonata_oauth2_login.twig.render_button_extension'],
        ];
    }

    protected function getContainerExtensions()
    {
        return array(
            new SonataOauth2LoginExtension(),
        );
    }
}
