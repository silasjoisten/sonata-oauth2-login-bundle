<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Checker;

class Email
{
    /**
     * @var array
     */
    private $validDomains;

    /**
     * @param array $validDomains
     */
    public function __construct(array $validDomains)
    {
        $this->validDomains = $validDomains;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function isEmailValid($email)
    {
        foreach ($this->validDomains as $validDomain) {
            if (strpos($email, $validDomain) !== false) {
                return true;
            }
        }

        return false;
    }
}
