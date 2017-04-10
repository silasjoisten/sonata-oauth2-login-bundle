<?php

namespace Exozet\Oauth2LoginBundle\Tests\Checker;

use Exozet\Oauth2LoginBundle\Checker\Email;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    public function testIsEmailValid()
    {
        $validEmailDomains = array(
            '@hotmail.de',
            '@gmail.com',
            '@exozet.com'
        );

        $emailChecker = new Email($validEmailDomains);

        $this->assertTrue($emailChecker->isEmailValid('test@hotmail.de'));
        $this->assertTrue($emailChecker->isEmailValid('test@gmail.com'));
        $this->assertTrue($emailChecker->isEmailValid('test@exozet.com'));
        $this->assertFalse($emailChecker->isEmailValid('test@hotmail.com'));
        $this->assertFalse($emailChecker->isEmailValid('test@foobar.de'));
        $this->assertFalse($emailChecker->isEmailValid('test@gmail.de'));
        $this->assertFalse($emailChecker->isEmailValid('test@exozet.de'));
        $this->assertFalse($emailChecker->isEmailValid('test@test.de'));
    }
}
