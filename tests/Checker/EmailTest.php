<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Tests\Checker;

use PHPUnit\Framework\TestCase;
use SilasJoisten\Sonata\Oauth2LoginBundle\Checker\Email;

class EmailTest extends TestCase
{
    /**
     * @dataProvider isEmailValidProvider
     */
    public function testIsEmailValid($expected, $email): void
    {
        $validEmailDomains = array(
            '@hotmail.de',
            '@gmail.com',
            '@example.com',
        );

        $checker = new Email($validEmailDomains);

        $this->assertEquals($expected, $checker->isEmailValid($email));
    }

    /**
     * @return array
     */
    public function isEmailValidProvider(): array
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
     * @dataProvider hasCustomRolesProvider
     */
    public function testHasCustomRoles($expected, $email): void
    {
        $customEmails = array(
            'bar.foo@goo.de' => 'ROLE_SUPER_ADMIN',
            'test@example.com' => 'ROLE_SONATA_ADMIN',
        );

        $checker = new Email(array(), $customEmails);

        $this->assertEquals($expected, $checker->hasCustomRoles($email));
    }

    /**
     * @return array
     */
    public function hasCustomRolesProvider(): array
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
