<?php

namespace Exozet\Oauth2LoginBundle\Google;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Authorization
{
    /**
     * @var \Google_Client
     */
    private $client;

    /**
     * @param \Google_Client $client
     */
    public function __construct(\Google_Client $client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        $this->client->setAccessType('offline');

        return $this->client;
    }
}
