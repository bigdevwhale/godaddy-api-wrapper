<?php

namespace GoDaddyAPI\Requests;

use GoDaddyAPI\Exceptions\GoDaddyAPIException;
use GoDaddyAPI\Http\GoDaddyApiClient;
use GoDaddyAPI\Models\GoDaddyResponse;

class OrdersRequest
{
    private $client;

    public function __construct(GoDaddyApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieve a list of orders for the authenticated shopper.
     *
     * @param array $filters Filters for the orders.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getOrders($filters)
    {
        return $this->client->get('/v1/orders', $filters);
    }

    /**
     * Retrieve details for a specified order.
     *
     * @param string $orderId Order ID for which details are to be retrieved.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getOrderDetails($orderId)
    {
        $path = '/v1/orders/' . $orderId;
        return $this->client->get($path);
    }
}
