<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Tests\Checker;

use SilasJoisten\Sonata\Oauth2LoginBundle\Checker\Email;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     *
     * @dataProvider isEmailValidProvider
     */
    public function isEmailValid($expected, $email)
    {
        $validEmailDomains = array(
            '@hotmail.de',
            '@gmail.com',
            '@example.com'
        );

        $checker = new Email($validEmailDomains);

        $this->assertEquals($expected, $checker->isEmailValid($email));
    }

    /**
     * @return array
     */
    public function isEmailValidProvider()
    {
        return array(
            array(true, 'test@hotmail.de'),
            array(true, 'test@gmail.com'),
            array(true, 'test@example.com'),
            array(false, 'test@hotmail.com'),
            array(false, 'test@foobar.de'),
            array(false, 'test@gmail.de'),
            array(false, 'test@example.de'),
            array(false, 'test@test.de'),
        );
    }
}
