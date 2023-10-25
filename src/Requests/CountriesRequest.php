<?php

namespace GoDaddyAPI\Requests;

use GoDaddyAPI\Exceptions\GoDaddyAPIException;
use GoDaddyAPI\Http\GoDaddyApiClient;
use GoDaddyAPI\Models\GoDaddyResponse;

class CountriesRequest
{
    private $client;

    public function __construct(GoDaddyApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieves summary country information for the provided marketId and filters.
     *
     * @param string $marketId MarketId in which the request is being made, and for which responses should be localized.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getCountries($marketId)
    {
        $query = ['marketId' => $marketId];
        return $this->client->get('/v1/countries', $query);
    }

    /**
     * Retrieves country and summary state information for the provided countryKey.
     *
     * @param string $countryKey The country key.
     * @param string $marketId MarketId in which the request is being made, and for which responses should be localized.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getCountry($countryKey, $marketId)
    {
        $query = ['marketId' => $marketId];
        $path = '/v1/countries/' . $countryKey;
        return $this->client->get($path, $query);
    }
}
