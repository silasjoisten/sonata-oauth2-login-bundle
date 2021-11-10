<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Checker;

final class Email
{
    public function __construct(
        private array $validDomains,
        private array $customEmailRoles = []
    ) {
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

    public function hasCustomRoles(string $email): bool
    {
        return isset($this->customEmailRoles[$email]) ?? false;
    }

    public function getCustomRoles(string $email): array
    {
        return $this->customEmailRoles[$email] ?? [];
    }
}
