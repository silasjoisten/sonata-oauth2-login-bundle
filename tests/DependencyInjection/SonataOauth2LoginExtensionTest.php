<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use SilasJoisten\Sonata\Oauth2LoginBundle\DependencyInjection\SonataOauth2LoginExtension;

/**
 * @group di
 */
class SonataOauth2LoginExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @dataProvider availableSecurityProvider
     * @dataProvider availableServicesProvider
     * @dataProvider availableTwigExtensionsProvider
     */
    public function testAvailableServices($service): void
    {
        $this->load();

        $this->assertContainerBuilderHasService($service);
    }

    /**
     * @return array
     */
    public function availableSecurityProvider(): array
    {
        return [
            ['sonata_oauth2_login.google.authorization'],
        ];
    }

    /**
     * @return array
     */
    public function availableServicesProvider(): array
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
    public function availableTwigExtensionsProvider(): array
    {
        return [
            ['sonata_oauth2_login.twig.render_button_extension'],
        ];
    }

    protected function getContainerExtensions(): array
    {
        return [
            new SonataOauth2LoginExtension(),
        ];
    }
}
