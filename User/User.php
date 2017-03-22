<?php

namespace Exozet\Oauth2LoginBundle\User;


use Symfony\Component\Security\Core\User\AdvancedUserInterface;

class User implements AdvancedUserInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var string
     */
    private $password;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var boolean
     */
    private $accountNonExpired;

    /**
     * @var boolean
     */
    private $credentialsNonExpired;

    /**
     * @var boolean
     */
    private $accountNonLocked;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var string
     */
    private $profilePicture;

    /**
     * User constructor.
     *
     * @param string $username
     * @param string $password
     * @param array $roles
     * @param bool $enabled
     * @param string $profilePicture
     * @param string $fullName
     * @param bool $userNonExpired
     * @param bool $credentialsNonExpired
     * @param bool $userNonLocked
     */
    public function __construct(
        $username,
        $password,
        array $roles = array(),
        $enabled = true,
        $profilePicture = null,
        $fullName = null,
        $userNonExpired = true,
        $credentialsNonExpired = true,
        $userNonLocked = true
    ) {
        if ('' === $username || null === $username) {
            throw new \InvalidArgumentException('The username cannot be empty.');
        }

        $this->username = $username;
        $this->password = $password;
        $this->enabled = $enabled;
        $this->profilePicture = $profilePicture;
        $this->accountNonExpired = $userNonExpired;
        $this->credentialsNonExpired = $credentialsNonExpired;
        $this->accountNonLocked = $userNonLocked;
        $this->roles = $roles;
        $this->fullName = $fullName;
    }

    /**
     * Get id.
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return $this->accountNonExpired;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return $this->accountNonLocked;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return $this->credentialsNonExpired;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}