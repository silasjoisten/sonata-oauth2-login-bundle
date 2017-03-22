<?php

namespace Exozet\Oauth2LoginBundle\Authorization;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Google
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var \Google_Client
     */
    private $client;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param \Google_Client $client
     */
    public function __construct(TokenStorageInterface $tokenStorage, \Google_Client $client)
    {
        $this->tokenStorage = $tokenStorage;
        $token = $this->tokenStorage->getToken()->getRawToken();

        $this->client = $client;
        $this->client->setAccessToken($token);
        $this->client->setAccessType('offline');
    }

    public function getClient()
    {
        return $this->client;
    }
}
