<?php

namespace GoDaddyAPI\Http;

use GoDaddyAPI\Exceptions\GoDaddyAPIException;
use GoDaddyAPI\Models\GoDaddyResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

/**
 * Class GoDaddyApiClient
 *
 * A client for making HTTP requests to the GoDaddy API using Guzzle.
 */
class GoDaddyApiClient
{
    private $client;

    public function __construct($apiKey, $apiSecret, $isProduction = true, $headers = [])
    {
        // Read the base URL from the configuration
        $config = include(__DIR__ . '/../../config/config.php');
        ;
        $baseUrl = $isProduction ? $config['production_base_url'] : $config['test_base_url'];
        // Create a new Guzzle HTTP client with base_uri and headers
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'headers' => $headers + [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'sso-key ' . $apiKey . ':' . $apiSecret,
            ],
        ]);
    }

    /**
     * Send an HTTP request to the specified endpoint.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $options
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    private function sendRequest($method, $endpoint, $options = [])
    {
        try {
            // Send the HTTP request using Guzzle
            $response = $this->client->request($method, $endpoint, $options);

            // Handle the response and create a GoDaddyResponse object
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $headers = $response->getHeaders();

            return new GoDaddyResponse($statusCode, $body, $headers);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $body = $e->getResponse()->getBody()->getContents();
                $headers = $e->getResponse()->getHeaders();

                throw new GoDaddyAPIException($body, $statusCode, $e);
            } else {
                throw new GoDaddyAPIException($e->getMessage(), $e->getCode(), $e);
            }
        }
    }

    /**
     * Send a GET request to the specified endpoint.
     *
     * @param string $endpoint
     *
     * @param array $query
     * @param array $headers
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function get($endpoint, $query = [], $headers = [])
    {
        $options = ['query' => $query, 'headers' => $headers];
        return $this->sendRequest('GET', $endpoint, $options);
    }

    /**
     * Send a POST request to the specified endpoint.
     *
     * @param string $endpoint
     * @param array $data
     *
     * @param array $query
     * @param array $headers
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function post($endpoint, $data = [], $query = [], $headers = [])
    {
        $options = ['json' => $data, 'headers' => $headers, 'query' => $query];
        return $this->sendRequest('POST', $endpoint, $options);
    }

    /**
     * Send a PUT request to the specified endpoint.
     *
     * @param string $endpoint
     * @param array $data
     *
     * @param array $headers
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function put($endpoint, $data = [], $headers = [])
    {
        $options = ['json' => $data, 'headers' => $headers];
        return $this->sendRequest('PUT', $endpoint, $options);
    }

    /**
     * Send a DELETE request to the specified endpoint.
     *
     * @param string $endpoint
     *
     * @param array $query
     * @param array $headers
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function delete($endpoint, $query = [], $headers = [])
    {
        $options = ['query' => $query, 'headers' => $headers];
        return $this->sendRequest('DELETE', $endpoint, $options);
    }

    /**
     * Send a PATCH request to the specified endpoint.
     *
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function patch($endpoint, $data = [], $headers = [])
    {
        $options = ['json' => $data, 'headers' => $headers];
        return $this->sendRequest('PATCH', $endpoint, $options);
    }
}
