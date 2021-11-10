<?php

namespace SilasJoisten\Sonata\Oauth2LoginBundle\Google;

final class Authorization
{
    public function __construct(
        private \Google_Client $client
    ) {
    }

    public function getClient(): \Google_Client
    {
        $this->client->setAccessType('offline');

        return $this->client;
    }
}
