<?php

namespace GoDaddyAPI\Requests;

use GoDaddyAPI\Exceptions\GoDaddyAPIException;
use GoDaddyAPI\Http\GoDaddyApiClient;
use GoDaddyAPI\Models\GoDaddyResponse;

class AftermarketRequest
{
    private $client;

    public function __construct(GoDaddyApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Remove listings from GoDaddy Auction.
     *
     * @param array $domains A comma-separated list of domain names.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function removeListings($domains)
    {
        $query = ['domains' => implode(',', $domains)];
        return $this->client->delete('/v1/aftermarket/listings', $query);
    }

    /**
     * Add expiry listings into GoDaddy Auction.
     *
     * @param array $expiryListings An array of expiry listings to be loaded.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function addExpiryListings($expiryListings)
    {
        return $this->client->post('/v1/aftermarket/listings/expiry', $expiryListings);
    }
}
