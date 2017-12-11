<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Tests\Google;

use PHPUnit\Framework\TestCase;
use SilasJoisten\Sonata\Oauth2LoginBundle\Google\Authorization;

class AuthorizationTest extends TestCase
{
    public function testGetClient()
    {
        $client = $this->createMock(\Google_Client::class);
        $client->expects($this->once())->method('setAccessType');

        $authorization = new Authorization($client);
        $googleClient = $authorization->getClient();

        $this->assertInstanceOf(\Google_Client::class, $googleClient);
    }
}
