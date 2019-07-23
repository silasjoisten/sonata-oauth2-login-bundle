<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Google;

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

    /**
     * @return \Google_Client
     */
    public function getClient(): \Google_Client
    {
        $this->client->setAccessType('offline');

        return $this->client;
    }
}
