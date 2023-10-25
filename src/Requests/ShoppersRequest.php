<?php

namespace GoDaddyAPI\Requests;

use GoDaddyAPI\Exceptions\GoDaddyAPIException;
use GoDaddyAPI\Http\GoDaddyApiClient;
use GoDaddyAPI\Models\GoDaddyResponse;

class ShoppersRequest
{
    private $client;

    public function __construct(GoDaddyApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create a subaccount owned by the authenticated Reseller.
     *
     * @param array $subaccountData Data to create the subaccount.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function createSubaccount($subaccountData)
    {
        $path = "/v1/shoppers/subaccount";
        return $this->client->post($path, $subaccountData);
    }

    /**
     * Get details for the specified Shopper.
     *
     * @param string $shopperId Shopper's ID.
     * @param array $includes Additional properties to include in the response.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getShopper($shopperId, $includes = [])
    {
        $path = "/v1/shoppers/{$shopperId}";
        $queryParameters = ['includes' => implode(',', $includes)];
        return $this->client->get($path, $queryParameters);
    }

    /**
     * Update details for the specified Shopper.
     *
     * @param string $shopperId Shopper's ID.
     * @param array $shopperData Data to update the Shopper.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function updateShopper($shopperId, $shopperData)
    {
        $path = "/v1/shoppers/{$shopperId}";
        return $this->client->post($path, $shopperData);
    }

    /**
     * Request the deletion of a shopper profile.
     *
     * @param string $shopperId Shopper's ID.
     * @param string $auditClientIp The client IP of the user who originated the request.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function deleteShopper($shopperId, $auditClientIp)
    {
        $path = "/v1/shoppers/{$shopperId}";
        $queryParameters = ['auditClientIp' => $auditClientIp];
        return $this->client->delete($path, $queryParameters);
    }

    /**
     * Get details for the specified Shopper's status.
     *
     * @param string $shopperId Shopper's ID.
     * @param string $auditClientIp The client IP of the user who originated the request.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getShopperStatus($shopperId, $auditClientIp)
    {
        $path = "/v1/shoppers/{$shopperId}/status";
        $queryParameters = ['auditClientIp' => $auditClientIp];
        return $this->client->get($path, $queryParameters);
    }

    /**
     * Set subaccount's password.
     *
     * @param string $shopperId Shopper's ID.
     * @param array $passwordData Data to set the subaccount's password.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function setPassword($shopperId, $passwordData)
    {
        $path = "/v1/shoppers/{$shopperId}/factors/password";
        return $this->client->put($path, $passwordData);
    }
}
