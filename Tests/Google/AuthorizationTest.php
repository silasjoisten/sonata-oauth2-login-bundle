<?php

namespace Exozet\Oauth2LoginBundle\Tests\Google;

use Exozet\Oauth2LoginBundle\Google\Authorization;

class AuthorizationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetClient()
    {
        $mockedClient = $this->getMockBuilder(\Google_Client::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $mockedClient->expects($this->once())
            ->method('setAccessType')
        ;

        $authorization = new Authorization($mockedClient);
        $googleClient = $authorization->getClient();

        $this->assertInstanceOf(\Google_Client::class, $googleClient);
    }
}
