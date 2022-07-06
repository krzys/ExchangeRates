<?php

namespace App\Http;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class NBPApiClient 
{
    const API_ENDPOINT = 'https://api.nbp.pl/api/exchangerates/tables/A/?format=json';

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetch()
    {
        $response = $this->client->request(
            'GET',
            self::API_ENDPOINT
        );

        $content = $response->getContent();
        $content = $response->toArray();

        return $content[0];
    }

    public function fetchOnlyRates()
    {
        return $this->fetch()['rates'];
    }
}