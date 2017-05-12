<?php

namespace SilasJoisten\SonataOauth2LoginBundle\Tests\Google;

use SilasJoisten\SonataOauth2LoginBundle\Google\Authorization;

class AuthorizationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetClient()
    {
        $client = $this->createMock(\Google_Client::class);
        $client->expects($this->once())
            ->method('setAccessType')
        ;

        $authorization = new Authorization($client);
        $googleClient = $authorization->getClient();

        $this->assertInstanceOf(\Google_Client::class, $googleClient);
    }
}
