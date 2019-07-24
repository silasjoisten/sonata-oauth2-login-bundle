<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Checker;

final class Email
{
    /**
     * @var array
     */
    private $validDomains;

    /**
     * @var array
     */
    private $customEmailRoles;

    public function __construct(array $validDomains, array $customEmailRoles = [])
    {
        $this->validDomains = $validDomains;
        $this->customEmailRoles = $customEmailRoles;
    }

    public function isEmailValid(string $email): bool
    {
        foreach ($this->validDomains as $validDomain) {
            if (false !== strpos($email, $validDomain)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function hasCustomRoles(string $email): bool
    {
        return isset($this->customEmailRoles[$email]) ?? false;
    }

    public function getCustomRoles(string $email): array
    {
        return $this->customEmailRoles[$email] ?? [];
    }
}
