<?php

namespace App\Actions\Bitcoin;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

abstract class BaseAction
{
    protected Client $client;
    protected string $apiUrl;
    protected string $token;
    protected string $zpub;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => Config::get('bitcoin.api_base_url', 'https://api.blockcypher.com/v1/btc/main/'),
            'timeout'  => 10.0,
        ]);
        $this->apiUrl = Config::get('bitcoin.api_base_url', 'https://api.blockcypher.com/v1/btc/main/');
        $this->token = Config::get('bitcoin.blockcypher_token');
        $this->zpub = Config::get('bitcoin.zpub');
    }

    protected function request(string $method, string $endpoint, array $options = []): array
    {
        try {
            $response = $this->client->request($method, $endpoint, array_merge([
                'query' => ['token' => $this->token],
            ], $options));

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('BlockCypher API Error: ' . $e->getMessage());
            throw new \Exception('Error communicating with BlockCypher API: ' . $e->getMessage());
        }
    }

    public function get(string $endpoint, array $params = []): array
    {
        return $this->request('GET', $endpoint, ['query' => $params]);
    }

    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, ['json' => $data]);
    }
}