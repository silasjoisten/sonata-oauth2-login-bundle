<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Checker;

class Email
{
    /**
     * @var array
     */
    private $validDomains;

    /**
     * @var array
     */
    private $customEmailRoles;

    /**
     * @param array $validDomains
     * @param array $customEmailRoles
     */
    public function __construct(array $validDomains, array $customEmailRoles = array())
    {
        $this->validDomains = $validDomains;
        $this->customEmailRoles = $customEmailRoles;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function isEmailValid($email)
    {
        foreach ($this->validDomains as $validDomain) {
            if (false !== strpos($email, $validDomain)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $email
     *
     * @return bool|string
     */
    public function hasCustomRoles($email)
    {
        return isset($this->customEmailRoles[$email]) ? $this->customEmailRoles[$email] : false;
    }
}
