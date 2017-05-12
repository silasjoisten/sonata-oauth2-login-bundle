<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Tests\Checker;

use SilasJoisten\Sonata\Oauth2LoginBundle\Checker\Email;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider isEmailValidProvider
     */
    public function testIsEmailValid($expected, $email)
    {
        $validEmailDomains = array(
            '@hotmail.de',
            '@gmail.com',
            '@exozet.com'
        );

        $emailChecker = new Email($validEmailDomains);

        $this->assertEquals($expected, $emailChecker->isEmailValid($email));
    }

    /**
     * @return array
     */
    public function isEmailValidProvider()
    {
        return array(
            array(true, 'test@hotmail.de'),
            array(true, 'test@gmail.com'),
            array(true, 'test@exozet.com'),
            array(false, 'test@hotmail.com'),
            array(false, 'test@foobar.de'),
            array(false, 'test@gmail.de'),
            array(false, 'test@exozet.de'),
            array(false, 'test@test.de'),
        );
    }
}
