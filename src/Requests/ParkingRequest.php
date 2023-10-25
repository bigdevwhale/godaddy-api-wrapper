<?php

namespace GoDaddyAPI\Requests;

use GoDaddyAPI\Exceptions\GoDaddyAPIException;
use GoDaddyAPI\Http\GoDaddyApiClient;
use GoDaddyAPI\Models\GoDaddyResponse;

class ParkingRequest
{
    private $client;

    public function __construct(GoDaddyApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieve a list of parking metrics for the specified customer.
     *
     * @param string $customerId Identifier for the customer.
     * @param array $filters Filters for parking metrics.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getParkingMetrics($customerId, $filters)
    {
        $path = "/v1/customers/{$customerId}/parking/metrics";
        return $this->client->get($path, $filters);
    }

    /**
     * Retrieve a list of domain metrics for the specified customer and portfolio.
     *
     * @param string $customerId Identifier for the customer.
     * @param array $filters Filters for domain metrics.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getMetricsByDomain($customerId, $filters)
    {
        $path = "/v1/customers/{$customerId}/parking/metricsByDomain";
        return $this->client->get($path, $filters);
    }
}
