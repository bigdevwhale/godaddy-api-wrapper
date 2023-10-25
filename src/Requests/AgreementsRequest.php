<?php

namespace GoDaddyAPI\Requests;

use GoDaddyAPI\Exceptions\GoDaddyAPIException;
use GoDaddyAPI\Http\GoDaddyApiClient;
use GoDaddyAPI\Models\GoDaddyResponse;

class AgreementsRequest
{
    private $client;

    public function __construct(GoDaddyApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieve Legal Agreements for provided agreements keys.
     *
     * @param array $keys Keys for Agreements whose details are to be retrieved.
     * @param int|null $privateLabelId PrivateLabelId to operate as, if different from JWT.
     * @param string|null $marketId Unique identifier of the Market used to retrieve/translate Legal Agreements.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException|GoDaddyAPIException
     */
    public function getAgreements($keys, $privateLabelId = null, $marketId = null)
    {
        $headers = [];

        if ($privateLabelId !== null) {
            $headers['X-Private-Label-Id'] = $privateLabelId;
        }

        if ($marketId !== null) {
            $headers['X-Market-Id'] = $marketId;
        }

        $query = ['keys' => $keys];
        return $this->client->get('/v1/agreements', $query, $headers);
    }
}
