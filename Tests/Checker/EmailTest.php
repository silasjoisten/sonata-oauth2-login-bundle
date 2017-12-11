<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Tests\Checker;

use PHPUnit\Framework\TestCase;
use SilasJoisten\Sonata\Oauth2LoginBundle\Checker\Email;

class EmailTest extends TestCase
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

    /**
     * @test
     *
     * @dataProvider hasCustomRolesProvider
     */
    public function hasCustomRoles($expected, $email)
    {
        $customEmails = array(
            'bar.foo@goo.de' => 'ROLE_SUPER_ADMIN',
            'test@example.com' => 'ROLE_SONATA_ADMIN'
        );

        $checker = new Email(array(), $customEmails);

        $this->assertEquals($expected, $checker->hasCustomRoles($email));
    }

    /**
     * @return array
     */
    public function hasCustomRolesProvider()
    {
        return array(
            array('ROLE_SUPER_ADMIN', 'bar.foo@goo.de'),
            array(false, 'test@gmail.com'),
            array('ROLE_SONATA_ADMIN', 'test@example.com'),
            array(false, 'test@hotmail.com'),
            array(false, 'test@foobar.de'),
            array(false, 'test@gmail.de'),
            array(false, 'test@example.de'),
            array(false, 'test@test.de'),
        );
    }
}
