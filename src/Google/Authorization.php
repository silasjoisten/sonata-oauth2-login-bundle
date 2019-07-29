<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Google;

class Authorization
{
    /**
     * @var \Google_Client
     */
    private $client;

    public function __construct(\Google_Client $client)
    {
        $this->client = $client;
    }

    public function getClient(): \Google_Client
    {
        $this->client->setAccessType('offline');

        return $this->client;
    }
}
