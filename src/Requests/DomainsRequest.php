<?php


use GoDaddyAPI\Exceptions\GoDaddyAPIException;
use GoDaddyAPI\Http\GoDaddyApiClient;

class DomainsRequest
{
    private $client;

    public function __construct(GoDaddyApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * List domains for a specific shopper with optional filters.
     *
     * @param string $shopperId - Shopper ID whose domains are to be retrieved.
     * @param array $statuses - Array of domain statuses to filter by.
     * @param array $statusGroups - Array of domain status groups to filter by.
     * @param int $limit - Maximum number of domains to return.
     * @param null $marker - Marker domain to use as an offset in results.
     * @param array $includes - Optional details to be included in the response.
     * @param null $modifiedDate - Only include results that have been modified since the specified date.
     *
     * @return mixed - Response from the API.
     * @throws GoDaddyAPIException
     */
    public function listDomains($shopperId, $statuses = [], $statusGroups = [], $limit = 100, $marker = null, $includes = [], $modifiedDate = null)
    {
        $query = [
            'X-Shopper-Id' => $shopperId,
            'statuses' => implode(',', $statuses),
            'statusGroups' => implode(',', $statusGroups),
            'limit' => $limit,
            'marker' => $marker,
            'includes' => implode(',', $includes),
            'modifiedDate' => $modifiedDate,
        ];

        return $this->client->get('/v1/domains', $query);
    }

    /**
     * Retrieve legal agreements for specified TLDs and domain add-ons.
     *
     * @param array $tlds - List of TLDs whose legal agreements are to be retrieved.
     * @param bool $privacy - Whether or not privacy has been requested.
     * @param bool $forTransfer - Whether or not domain transfer has been requested (optional).
     *
     * @return mixed - Response from the API.
     * @throws GoDaddyAPIException
     */
    public function getLegalAgreements($tlds, $privacy, $forTransfer = null)
    {
        $query = [
            'tlds' => implode(',', $tlds),
            'privacy' => $privacy,
            'forTransfer' => $forTransfer,
        ];

        return $this->client->get('/v1/domains/agreements', $query);
    }

    /**
     * Check the availability of a single domain.
     *
     * @param string $domain - Domain name to check for availability.
     * @param string $checkType - Optimize for time ('FAST') or accuracy ('FULL') (optional).
     * @param bool $forTransfer - Whether or not to include domains available for transfer (optional).
     *
     * @return mixed - Response from the API.
     * @throws GoDaddyAPIException
     */
    public function checkDomainAvailability($domain, $checkType = 'FAST', $forTransfer = false)
    {
        $query = [
            'domain' => $domain,
            'checkType' => $checkType,
            'forTransfer' => $forTransfer,
        ];

        return $this->client->get('/v1/domains/available', $query);
    }

    /**
     * Check the availability of multiple domains in bulk.
     *
     * @param array $domains - Domain names to check for availability (up to 500 domains).
     * @param string $checkType - Optimize for time ('FAST') or accuracy ('FULL') (optional).
     *
     * @return mixed - Response from the API.
     * @throws GoDaddyAPIException
     */
    public function checkDomainAvailabilityBulk($domains, $checkType = 'FAST')
    {
        $query = ['checkType' => $checkType];
        $data = ['domains' => $domains];

        return $this->client->post('/v1/domains/available', $data, $query);
    }

    /**
     * Validate domain contacts against specified domains.
     *
     * @param int $privateLabelId - PrivateLabelId to operate as, if different from JWT (optional).
     * @param string $marketId - MarketId in which the request is being made (optional).
     * @param array $contacts - Domain contacts to validate.
     *
     * @return mixed - Response from the API.
     * @throws GoDaddyAPIException
     */
    public function validateDomainContacts($contacts, $privateLabelId = 1, $marketId = 'en-US')
    {
        $headers = ['X-Private-Label-Id' => $privateLabelId];
        $query = ['marketId' => $marketId];
        $data = ['body' => $contacts];

        return $this->client->post('/v1/domains/contacts/validate', $data, $query, $headers);
    }

    /**
     * Purchase and register a domain for a specific shopper.
     *
     * @param string $shopperId - Shopper ID for whom the domain should be purchased.
     * @param array $purchaseData - Domain purchase data.
     *
     * @return mixed - Response from the API.
     * @throws GoDaddyAPIException
     */
    public function purchaseDomain($shopperId, $purchaseData)
    {
        $headers = ['X-Shopper-Id' => $shopperId];
        $data = ['body' => $purchaseData];

        return $this->client->post('/v1/domains/purchase', $data, [], $headers);
    }

    /**
     * Get the purchase schema for a specified TLD.
     *
     * @param string $tld The Top-Level Domain whose schema should be retrieved.
     *
     * @return mixed The response from the API.
     * @throws GoDaddyAPIException
     */
    public function getPurchaseSchema($tld)
    {
        return $this->client->get("/v1/domains/purchase/schema/{$tld}");
    }

    /**
     * Validate a domain purchase request using the Domain Purchase Schema for the specified TLD.
     *
     * @param array $purchaseData An instance document expected to match the JSON schema returned by `./schema/{tld}`.
     *
     * @return mixed The response from the API.
     * @throws GoDaddyAPIException
     */
    public function validateDomainPurchase($purchaseData)
    {
        $data = ['body' => $purchaseData];

        return $this->client->post('/v1/domains/purchase/validate', $data);
    }

    /**
     * Suggest alternate domain names based on a seed domain, keywords, or purchase history.
     *
     * @param string|null $shopperId Shopper ID for which the suggestions are being generated.
     * @param string|null $query Domain name or set of keywords for which alternative domain names will be suggested.
     * @param string|null $country Two-letter ISO country code to be used as a hint for the target region.
     * @param string|null $city Name of the city to be used as a hint for the target region.
     * @param array $sources Sources to be queried for suggestions.
     * @param array $tlds Top-level domains to be included in suggestions.
     * @param int|null $lengthMax Maximum length of the second-level domain.
     * @param int|null $lengthMin Minimum length of the second-level domain.
     * @param int|null $limit Maximum number of suggestions to return.
     * @param int $waitMs Maximum amount of time, in milliseconds, to wait for responses.
     *
     * @return mixed The response from the API.
     * @throws GoDaddyAPIException
     */
    public function suggestDomains($shopperId = null, $query = null, $country = null, $city = null, $sources = [], $tlds = [], $lengthMax = null, $lengthMin = null, $limit = null, $waitMs = 1000)
    {
        $headers = [];
        $queryParams = [];

        if ($shopperId !== null) {
            $headers['X-Shopper-Id'] = $shopperId;
        }

        if ($query !== null) {
            $queryParams['query'] = $query;
        }

        // Additional parameters...

        return $this->client->get('/v1/domains/suggest', $queryParams, $headers);
    }

    /**
     * Get a list of supported and enabled TLDs for sale.
     *
     * @return mixed The response from the API.
     * @throws GoDaddyAPIException
     */
    public function getSupportedTlds()
    {
        return $this->client->get('/v1/domains/tlds');
    }

    /**
     * Cancel a purchased domain.
     *
     * @param string $domain Domain to cancel.
     *
     * @return mixed The response from the API.
     * @throws GoDaddyAPIException
     */
    public function cancelDomain($domain)
    {
        return $this->client->delete("/v1/domains/{$domain}");
    }

    /**
     * Retrieve details for the specified domain.
     *
     * @param string $domain Domain name whose details are to be retrieved.
     * @param string|null $shopperId Shopper ID expected to own the specified domain.
     *
     * @return mixed The response from the API.
     * @throws GoDaddyAPIException
     */
    public function getDomainDetails($domain, $shopperId = null)
    {
        $headers = [];

        if ($shopperId !== null) {
            $headers['X-Shopper-Id'] = $shopperId;
        }

        return $this->client->get("/v1/domains/{$domain}", [], $headers);
    }

    /**
     * Update details for the specified domain.
     *
     * @param string $domain Domain whose details are to be updated.
     * @param array $purchaseData Changes to apply to the existing domain.
     * @param string|null $shopperId Shopper for whom the domain is to be updated.
     *
     * @return mixed The response from the API.
     */
    public function updateDomainDetails($domain, $purchaseData, $shopperId = null)
    {
        $headers = [];

        if ($shopperId !== null) {
            $headers['X-Shopper-Id'] = $shopperId;
        }

        $data = ['body' => $purchaseData];

        return $this->client->patch("/v1/domains/{$domain}", $data, $headers);
    }

    /**
     * Update the contacts for a specific domain.
     *
     * @param string $domain The domain to update contacts for.
     * @param array $contacts The changes to apply to existing contacts.
     * @param string|null $shopperId Shopper ID for the domain owner (optional).
     * @return mixed The API response.
     */
    public function updateDomainContacts($domain, $contacts, $shopperId = null)
    {
        $headers = [];
        if ($shopperId !== null) {
            $headers['X-Shopper-Id'] = $shopperId;
        }
        $data = ['body' => $contacts];
        return $this->client->patch("/v1/domains/{$domain}/contacts", $data, $headers);
    }

    /**
     * Submit a request to cancel domain privacy for the given domain.
     *
     * @param string $domain The domain for which to cancel privacy.
     * @param string|null $shopperId Shopper ID of the domain owner (optional).
     * @return mixed The API response.
     */
    public function cancelDomainPrivacy($domain, $shopperId = null)
    {
        $headers = [];
        if ($shopperId !== null) {
            $headers['X-Shopper-Id'] = $shopperId;
        }
        return $this->client->delete("/v1/domains/{$domain}/privacy", [], $headers);
    }

    /**
     * Purchase domain privacy for a specified domain.
     *
     * @param string $domain The domain for which to purchase privacy.
     * @param array $privacyOptions Options for purchasing privacy.
     * @param string|null $shopperId Shopper ID of the domain owner (optional).
     * @return mixed The API response, including the purchase response.
     */
    public function purchaseDomainPrivacy($domain, $privacyOptions, $shopperId = null)
    {
        $headers = [];
        if ($shopperId !== null) {
            $headers['X-Shopper-Id'] = $shopperId;
        }
        $data = ['body' => $privacyOptions];
        return $this->client->post("/v1/domains/{$domain}/privacy/purchase", $data, [], $headers);
    }

    /**
     * Update the contacts for a specific domain.
     *
     * @param string $domain The domain to update contacts for.
     * @param array $contacts Changes to apply to existing contacts.
     * @param string|null $shopperId Shopper ID for the domain owner (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function updateContacts($domain, $contacts, $shopperId = null)
    {
        // Construct the request headers if shopperId is provided
        $headers = [];
        if ($shopperId !== null) {
            $headers['X-Shopper-Id'] = $shopperId;
        }

        // Prepare the request data with contacts
        $data = ['body' => $contacts];

        // Make a PATCH request to update domain contacts
        return $this->client->patch("/v1/domains/{$domain}/contacts", $data, $headers);
    }

    /**
     * Submit a request to cancel domain privacy for the given domain.
     *
     * @param string $domain The domain for which to cancel privacy.
     * @param string|null $shopperId Shopper ID of the domain owner (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function cancelPrivacy($domain, $shopperId = null)
    {
        // Construct the request headers if shopperId is provided
        $headers = [];
        if ($shopperId !== null) {
            $headers['X-Shopper-Id'] = $shopperId;
        }

        // Make a DELETE request to cancel domain privacy
        return $this->client->delete("/v1/domains/{$domain}/privacy", [], $headers);
    }

    /**
     * Purchase domain privacy for a specified domain.
     *
     * @param string $domain The domain for which to purchase privacy.
     * @param array $privacyOptions Options for purchasing privacy.
     * @param string|null $shopperId Shopper ID of the domain owner (optional).
     * @return mixed The API response, including the purchase response.
     * @throws GoDaddyAPIException
     */
    public function purchasePrivacy($domain, $privacyOptions, $shopperId = null)
    {
        // Construct the request headers if shopperId is provided
        $headers = [];
        if ($shopperId !== null) {
            $headers['X-Shopper-Id'] = $shopperId;
        }

        // Prepare the request data with privacyOptions
        $data = ['body' => $privacyOptions];

        // Make a POST request to purchase domain privacy
        return $this->client->post("/v1/domains/{$domain}/privacy/purchase", $data, [], $headers);
    }

    /**
     * Add the specified DNS Records to the specified Domain.
     *
     * @param string $domain The domain whose DNS Records are to be augmented.
     * @param array $records DNS Records to add to the existing records.
     * @param string|null $shopperId Shopper ID for domain ownership (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function addDNSRecords($domain, $records, $shopperId = null)
    {
        // Construct the request headers if shopperId is provided
        $headers = [];
        if ($shopperId !== null) {
            $headers['X-Shopper-Id'] = $shopperId;
        }

        // Prepare the request data with records
        $data = ['body' => $records];

        // Make a PATCH request to add DNS Records
        return $this->client->patch("/v1/domains/{$domain}/records", $data, $headers);
    }

    /**
     * Replace all DNS Records for the specified Domain.
     *
     * @param string $domain The domain whose DNS Records are to be replaced.
     * @param array $records DNS Records to replace the existing records.
     * @param string|null $shopperId Shopper ID for domain ownership (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function replaceDNSRecords($domain, $records, $shopperId = null)
    {
        // Construct the request headers if shopperId is provided
        $headers = [];
        if ($shopperId !== null) {
            $headers['X-Shopper-Id'] = $shopperId;
        }

        // Prepare the request data with records
        $data = ['body' => $records];

        // Make a PUT request to replace DNS Records
        return $this->client->put("/v1/domains/{$domain}/records", $data, $headers);
    }

    /**
     * Retrieve DNS Records for the specified Domain, optionally with the specified Type and/or Name.
     *
     * @param string $domain The domain whose DNS Records are to be retrieved.
     * @param string|null $shopperId Shopper ID for domain ownership (optional).
     * @param string|null $type DNS Record Type for retrieval (optional).
     * @param string|null $name DNS Record Name for retrieval (optional).
     * @param int $offset Number of results to skip for pagination (optional).
     * @param int $limit Maximum number of items to return (optional).
     * @return mixed The API response containing DNS Records.
     * @throws GoDaddyAPIException
     */
    public function getDNSRecords($domain, $shopperId = null, $type = null, $name = null, $offset = 0, $limit = 10)
    {
        // Construct the request headers if shopperId is provided
        $headers = [];
        if ($shopperId !== null) {
            $headers['X-Shopper-Id'] = $shopperId;
        }

        // Construct the query parameters for type, name, offset, and limit
        $queryParams = [
            'type' => $type,
            'name' => $name,
            'offset' => $offset,
            'limit' => $limit,
        ];

        // Make a GET request to retrieve DNS Records
        return $this->client->get("/v1/domains/{$domain}/records", $queryParams, $headers);
    }

    /**
     * Replace all DNS Records for the specified Domain with the specified Type and Name.
     *
     * @param string $domain The domain whose DNS Records are to be replaced.
     * @param string $type DNS Record Type to be replaced.
     * @param string $name DNS Record Name to be replaced.
     * @param array $records DNS Records to replace the existing records.
     * @param string|null $shopperId Shopper ID for domain ownership (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function replaceDNSRecordsByTypeAndName($domain, $type, $name, $records, $shopperId = null)
    {
        // Construct the request headers if shopperId is provided
        $headers = [];
        if ($shopperId !== null) {
            $headers['X-Shopper-Id'] = $shopperId;
        }

        // Prepare the request data with records
        $data = ['body' => $records];

        // Make a PUT request to replace DNS Records by type and name
        return $this->client->put("/v1/domains/{$domain}/records/{$type}/{$name}", $data, $headers);
    }

    /**
     * Delete all DNS Records for the specified Domain with the specified Type and Name.
     *
     * @param string $domain The domain whose DNS Records are to be deleted.
     * @param string $type DNS Record Type to be deleted.
     * @param string $name DNS Record Name to be deleted.
     * @param string|null $shopperId Shopper ID for domain ownership (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function deleteDNSRecordsByTypeAndName($domain, $type, $name, $shopperId = null)
    {
        // Construct the request headers if shopperId is provided
        $headers = [];
        if ($shopperId !== null) {
            $headers['X-Shopper-Id'] = $shopperId;
        }

        // Make a DELETE request to delete DNS Records by type and name
        return $this->client->delete("/v1/domains/{$domain}/records/{$type}/{$name}", [], $headers);
    }

    /**
     * Replace all DNS Records for the specified Domain with the specified Type.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain whose DNS Records are to be replaced.
     * @param string $type DNS Record Type for which DNS Records are to be replaced.
     * @param array $records DNS Records to replace the existing records.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function replaceDNSRecordsByType($customerId, $domain, $type, $records, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Prepare the request data with records
        $data = ['body' => $records];

        // Make a PUT request to replace DNS Records by type
        return $this->client->put("/v2/customers/{$customerId}/domains/{$domain}/records/{$type}", $data, $headers);
    }

    /**
     * Renew the specified Domain for a customer.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain to renew.
     * @param array|null $renewalOptions Options for renewing the domain (optional).
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response, including the renewal response.
     * @throws GoDaddyAPIException
     */
    public function renewDomain($customerId, $domain, $renewalOptions = null, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Prepare the request data with renewalOptions if provided
        $data = ['body' => $renewalOptions];

        // Make a POST request to renew the domain
        return $this->client->post("/v2/customers/{$customerId}/domains/{$domain}/renew", $data, [], $headers);
    }

    /**
     * Purchase and start or restart the transfer process for a domain.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain to transfer in.
     * @param array $transferOptions Details for domain transfer purchase.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response, including the transfer response.
     * @throws GoDaddyAPIException
     */
    public function transferDomainIn($customerId, $domain, $transferOptions, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Prepare the request data with transferOptions
        $data = ['body' => $transferOptions];

        // Make a POST request to purchase and start/restart domain transfer
        return $this->client->post("/v2/customers/{$customerId}/domains/{$domain}/transfer", $data, [], $headers);
    }

    /**
     * Re-send Contact E-mail Verification for the specified Domain.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain whose Contact E-mail should be verified.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function verifyRegistrantEmail($customerId, $domain, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Make a POST request to re-send Contact E-mail Verification
        return $this->client->post("/v1/domains/{$domain}/verifyRegistrantEmail", [], $headers);
    }

    /**
     * Retrieve details for the specified Domain.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain name whose details are to be retrieved.
     * @param array|null $includes Optional details to be included in the response (optional).
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response containing domain details.
     * @throws GoDaddyAPIException
     */
    public function getDomainDetailsV2($customerId, $domain, $includes = null, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Construct the query parameters for 'includes' if provided
        $queryParams = ['includes' => $includes];

        // Make a GET request to retrieve domain details
        return $this->client->get("/v2/customers/{$customerId}/domains/{$domain}", $queryParams, $headers);
    }

    /**
     * Replaces the existing name servers on the domain using API version two.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain whose name servers are to be replaced.
     * @param array $nameServers Name server records to replace on the domain.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function replaceNameServersV2($customerId, $domain, $nameServers, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Prepare the request data with nameServers
        $data = ['body' => $nameServers];

        // Make a PUT request to replace name servers on the domain (API version two)
        return $this->client->put("/v2/customers/{$customerId}/domains/{$domain}/nameServers", $data, $headers);
    }

    /**
     * Retrieve privacy email forwarding settings showing where emails are delivered using API version two.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain name whose details are to be retrieved.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function getPrivacyEmailForwardingSettingsV2($customerId, $domain, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Make a GET request to retrieve privacy email forwarding settings (API version two)
        return $this->client->get("/v2/customers/{$customerId}/domains/{$domain}/privacy/forwarding", [], $headers);
    }

    /**
     * Update privacy email forwarding settings to determine how emails are delivered using API version two.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain name whose details are to be retrieved.
     * @param array $privacyEmailForwardingSettings Update privacy email forwarding settings.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     */
    public function updatePrivacyEmailForwardingSettingsV2($customerId, $domain, $privacyEmailForwardingSettings, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Prepare the request data with privacyEmailForwardingSettings
        $data = ['body' => $privacyEmailForwardingSettings];

        // Make a PATCH request to update privacy email forwarding settings (API version two)
        return $this->client->patch("/v2/customers/{$customerId}/domains/{$domain}/privacy/forwarding", $data, [], $headers);
    }

    /**
     * Purchase a restore for the given domain to bring it out of redemption using API version two.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain to request redeem for.
     * @param array|null $redeemOptions Options for redeeming existing Domain (optional).
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function redeemDomainV2($customerId, $domain, $redeemOptions = null, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Prepare the request data with redeemOptions if provided
        $data = ['body' => $redeemOptions];

        // Make a POST request to purchase a restore for the given domain (API version two)
        return $this->client->post("/v2/customers/{$customerId}/domains/{$domain}/redeem", $data, [], $headers);
    }

    /**
     * Renew the specified Domain using API version two.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain to be renewed.
     * @param array $renewalOptions Options for renewing existing Domain.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function renewDomainV2($customerId, $domain, $renewalOptions, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Prepare the request data with renewalOptions
        $data = ['body' => $renewalOptions];

        // Make a POST request to renew the specified Domain (API version two)
        return $this->client->post("/v2/customers/{$customerId}/domains/{$domain}/renew", $data, [], $headers);
    }

    /**
     * Purchase and start or restart the transfer process for a domain using API version two.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain to transfer in.
     * @param array $transferOptions Details for domain transfer purchase.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function transferDomainInV2($customerId, $domain, $transferOptions, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Prepare the request data with transferOptions
        $data = ['body' => $transferOptions];

        // Make a POST request to purchase and start/restart domain transfer (API version two)
        return $this->client->post("/v2/customers/{$customerId}/domains/{$domain}/transfer", $data, [], $headers);
    }

    /**
     * Accepts the transfer in for a domain.
     *
     * @param string $customerId The Customer identifier
     * @param string $domain Domain to accept the transfer in for
     * @param array $authCode An Authorization code for transferring the Domain
     * @param string|null $requestId A client provided identifier for tracking this request (optional)
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function acceptTransferInV2($customerId, $domain, $authCode, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Prepare the request data with the authCode
        $data = ['body' => $authCode];

        // Make a POST request to accept the transfer in
        return $this->client->post("/v2/customers/{$customerId}/domains/{$domain}/transferInAccept", $data, [], $headers);
    }

    /**
     * Cancels the transfer in for a domain.
     *
     * @param string $customerId The Customer identifier
     * @param string $domain Domain to cancel the transfer in for
     * @param string|null $requestId A client provided identifier for tracking this request (optional)
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function cancelTransferInV2($customerId, $domain, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Make a POST request to cancel the transfer in
        return $this->client->post("/v2/customers/{$customerId}/domains/{$domain}/transferInCancel", [], [], $headers);
    }

    /**
     * Restarts the transfer in request from the beginning for a domain.
     *
     * @param string $customerId The Customer identifier
     * @param string $domain Domain to restart the transfer in for
     * @param string|null $requestId A client provided identifier for tracking this request (optional)
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function restartTransferInV2($customerId, $domain, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Make a POST request to restart the transfer in
        return $this->client->post("/v2/customers/{$customerId}/domains/{$domain}/transferInRestart", [], [], $headers);
    }

    /**
     * Retries the current transfer in request with supplied Authorization code for a domain.
     *
     * @param string $customerId The Customer identifier
     * @param string $domain Domain to retry the transfer in
     * @param array $authCode An Authorization code for transferring the Domain
     * @param string|null $requestId A client provided identifier for tracking this request (optional)
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function retryTransferInV2($customerId, $domain, $authCode, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Prepare the request data with the authCode
        $data = ['body' => $authCode];

        // Make a POST request to retry the transfer in
        return $this->client->post("/v2/customers/{$customerId}/domains/{$domain}/transferInRetry", $data, [], $headers);
    }

    /**
     * Initiates transfer out to another registrar for a .uk domain.
     *
     * @param string $customerId The Customer identifier
     * @param string $domain Domain to initiate the transfer out for
     * @param string $registrar Registrar tag to push transfer to
     * @param string|null $requestId A client provided identifier for tracking this request (optional)
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function initiateTransferOutV2($customerId, $domain, $registrar, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Make a POST request to initiate the transfer out
        return $this->client->post("/v2/customers/{$customerId}/domains/{$domain}/transferOut", [], ['registrar' => $registrar], $headers);
    }

    /**
     * Accepts the transfer out for a domain.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain for which transfer out is accepted.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function acceptTransferOutV2($customerId, $domain, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Make a POST request to accept transfer out
        return $this->client->post("/v2/customers/{$customerId}/domains/{$domain}/transferOutAccept", [], $headers);
    }

    /**
     * Rejects the transfer out for a domain.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain for which transfer out is rejected.
     * @param string|null $reason Reason for rejecting the transfer out (optional).
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function rejectTransferOutV2($customerId, $domain, $reason = null, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Prepare the query parameters for reason if provided
        $queryParams = [];
        if ($reason !== null) {
            $queryParams['reason'] = $reason;
        }

        // Make a POST request to reject transfer out
        return $this->client->post("/v2/customers/{$customerId}/domains/{$domain}/transferOutReject", $queryParams, $headers);
    }

    /**
     * Deletes forwarding details for a fully qualified domain name (FQDN).
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $fqdn Fully qualified domain name to delete forwarding details.
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function deleteForwardingDetailsV2($customerId, $fqdn)
    {
        // Make a DELETE request to delete forwarding details for the FQDN
        return $this->client->delete("/v2/customers/{$customerId}/domains/forwards/{$fqdn}");
    }

    /**
     * Retrieves forwarding information for a fully qualified domain name (FQDN).
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $fqdn Fully qualified domain name to retrieve forwarding details.
     * @param bool $includeSubs Optionally include subdomains if the FQDN is a domain.
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function getForwardingInfoV2($customerId, $fqdn, $includeSubs = false)
    {
        // Prepare the query parameters for includeSubs
        $queryParams = ['includeSubs' => $includeSubs];

        // Make a GET request to retrieve forwarding information for the FQDN
        return $this->client->get("/v2/customers/{$customerId}/domains/forwards/{$fqdn}", $queryParams);
    }

    /**
     * Modifies forwarding details for a fully qualified domain name (FQDN).
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $fqdn Fully qualified domain name to modify forwarding details.
     * @param array $forwardingRule Domain forwarding rule to create or replace.
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function modifyForwardingDetailsV2($customerId, $fqdn, $forwardingRule)
    {
        // Prepare the request data with forwardingRule
        $data = ['body' => $forwardingRule];

        // Make a PUT request to modify forwarding details for the FQDN
        return $this->client->put("/v2/customers/{$customerId}/domains/forwards/{$fqdn}", $data);
    }

    /**
     * Creates new forwarding details for a fully qualified domain name (FQDN).
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $fqdn Fully qualified domain name to create forwarding details.
     * @param array $forwardingRule Domain forwarding rule to create.
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function createForwardingDetailsV2($customerId, $fqdn, $forwardingRule)
    {
        // Prepare the request data with forwardingRule
        $data = ['body' => $forwardingRule];

        // Make a POST request to create forwarding details for the FQDN
        return $this->client->post("/v2/customers/{$customerId}/domains/forwards/{$fqdn}", $data);
    }

    /**
     * Retrieves a list of the most recent actions for the specified domain.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain for which actions are to be retrieved.
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function getRecentActionsV2($customerId, $domain)
    {
        // Make a GET request to retrieve recent actions for the domain
        return $this->client->get("/v2/customers/{$customerId}/domains/{$domain}/actions");
    }

    /**
     * Cancels the most recent user action for the specified domain.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain for which the action is to be canceled.
     * @param string $type The type of action to cancel.
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function cancelRecentActionV2($customerId, $domain, $type)
    {
        // Make a DELETE request to cancel the most recent user action for the domain
        return $this->client->delete("/v2/customers/{$customerId}/domains/{$domain}/actions/{$type}");
    }

    /**
     * Retrieves the most recent action for the specified domain.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $domain Domain for which the action is to be retrieved.
     * @param string $type The type of action to retrieve.
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function getRecentActionV2($customerId, $domain, $type)
    {
        // Make a GET request to retrieve the most recent action for the domain
        return $this->client->get("/v2/customers/{$customerId}/domains/{$domain}/actions/{$type}");
    }

    /**
     * Retrieve the next domain notification.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function getNextDomainNotificationV2($customerId, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Make a GET request to retrieve the next domain notification
        return $this->client->get("/v2/customers/{$customerId}/domains/notifications", [], $headers);
    }

    /**
     * Retrieve a list of notification types that are opted in.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function getOptedInNotificationTypesV2($customerId, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Make a GET request to retrieve a list of notification types that are opted in
        return $this->client->get("/v2/customers/{$customerId}/domains/notifications/optIn", [], $headers);
    }

    /**
     * Opt in to receive notifications for the specified notification types.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param array $notificationTypes Array of notification types to opt in.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function optInForNotificationsV2($customerId, $notificationTypes, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Construct the query parameters with notification types
        $queryParams = ['types' => $notificationTypes];

        // Make a PUT request to opt in for notifications
        return $this->client->put("/v2/customers/{$customerId}/domains/notifications/optIn", $queryParams, $headers);
    }

    /**
     * Retrieve the schema for the notification data for the specified notification type.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $notificationType Notification type for which schema should be retrieved.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function getNotificationSchemaV2($customerId, $notificationType, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Make a GET request to retrieve the schema for the notification data
        return $this->client->get("/v2/customers/{$customerId}/domains/notifications/schemas/{$notificationType}", [], $headers);
    }

    /**
     * Acknowledge a domain notification.
     *
     * @param string $customerId Customer ID or Subaccount ID.
     * @param string $notificationId Notification ID to acknowledge.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function acknowledgeDomainNotificationV2($customerId, $notificationId, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Make a POST request to acknowledge a domain notification
        return $this->client->post("/v2/customers/{$customerId}/domains/notifications/{$notificationId}/acknowledge", [], $headers);
    }

    /**
     * Retrieve a list of upcoming system Maintenances.
     *
     * @param string|null $status Filter by maintenance status (optional).
     * @param string|null $modifiedAtAfter Filter by modifiedAt date (optional).
     * @param string|null $startsAtAfter Filter by startsAt date (optional).
     * @param int|null $limit Maximum number of results to return (optional).
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function getUpcomingSystemMaintenancesV2($status = null, $modifiedAtAfter = null, $startsAtAfter = null, $limit = null, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Construct the query parameters for status, modifiedAtAfter, startsAtAfter, and limit
        $queryParams = [];
        if ($status !== null) {
            $queryParams['status'] = $status;
        }
        if ($modifiedAtAfter !== null) {
            $queryParams['modifiedAtAfter'] = $modifiedAtAfter;
        }
        if ($startsAtAfter !== null) {
            $queryParams['startsAtAfter'] = $startsAtAfter;
        }
        if ($limit !== null) {
            $queryParams['limit'] = $limit;
        }

        // Make a GET request to retrieve a list of upcoming system Maintenances
        return $this->client->get("/v2/domains/maintenances", $queryParams, $headers);
    }

    /**
     * Retrieve the details for an upcoming system Maintenance.
     *
     * @param string $maintenanceId Identifier for the system maintenance.
     * @param string|null $requestId A client provided identifier for tracking this request (optional).
     * @return mixed The API response.
     * @throws GoDaddyAPIException
     */
    public function getMaintenanceDetailsV2($maintenanceId, $requestId = null)
    {
        // Construct the request headers with X-Request-Id if provided
        $headers = [];
        if ($requestId !== null) {
            $headers['X-Request-Id'] = $requestId;
        }

        // Make a GET request to retrieve the details for an upcoming system Maintenance
        return $this->client->get("/v2/domains/maintenances/{$maintenanceId}", [], $headers);
    }
}
