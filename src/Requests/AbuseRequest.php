<?php

namespace GoDaddyAPI\Requests;

use GoDaddyAPI\Exceptions\GoDaddyAPIException;
use GoDaddyAPI\Http\GoDaddyApiClient;
use GoDaddyAPI\Models\GoDaddyResponse;

class AbuseRequest
{
    private $client;

    public function __construct(GoDaddyApiClient $client)
    {
        $this->client = $client;
    }

    // Methods for API version 1

    /**
     * List abuse tickets that match user-provided filters (API version 1).
     *
     * @param string|null $type The type of abuse.
     * @param bool $closed Is this abuse ticket closed?
     * @param string|null $sourceDomainOrIp The domain name or IP address the abuse originated from.
     * @param string|null $target The brand/company the abuse is targeting.
     * @param string|null $createdStart The earliest abuse ticket creation date.
     * @param string|null $createdEnd The latest abuse ticket creation date.
     * @param int $limit Number of abuse ticket numbers to return.
     * @param int $offset The earliest result set record number to pull abuse tickets for.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function listAbuseTickets($type = null, $closed = false, $sourceDomainOrIp = null, $target = null, $createdStart = null, $createdEnd = null, $limit = 100, $offset = 0)
    {
        $filters = compact('type', 'closed', 'sourceDomainOrIp', 'target', 'createdStart', 'createdEnd', 'limit', 'offset');
        return $this->client->get('/v1/abuse/tickets', $filters);
    }

    /**
     * Create a new abuse ticket (API version 1).
     *
     * @param array $abuseTicketData Data for creating the abuse ticket.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function createAbuseTicket($abuseTicketData)
    {
        return $this->client->post('/v1/abuse/tickets', $abuseTicketData);
    }

    /**
     * Get abuse ticket information for a given ticket ID (API version 1).
     *
     * @param string $ticketId A unique abuse ticket identifier.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getAbuseTicketInfo($ticketId)
    {
        return $this->client->get("/v1/abuse/tickets/{$ticketId}");
    }

    // Methods for API version 2 (with V2 postfix)

    /**
     * List abuse tickets that match user-provided filters (API version 2).
     *
     * @param string|null $type The type of abuse.
     * @param bool $closed Is this abuse ticket closed?
     * @param string|null $sourceDomainOrIp The domain name or IP address the abuse originated from.
     * @param string|null $target The brand/company the abuse is targeting.
     * @param string|null $createdStart The earliest abuse ticket creation date.
     * @param string|null $createdEnd The latest abuse ticket creation date.
     * @param int $limit Number of abuse ticket numbers to return.
     * @param int $offset The earliest result set record number to pull abuse tickets for.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function listAbuseTicketsV2($type = null, $closed = false, $sourceDomainOrIp = null, $target = null, $createdStart = null, $createdEnd = null, $limit = 100, $offset = 0)
    {
        $filters = compact('type', 'closed', 'sourceDomainOrIp', 'target', 'createdStart', 'createdEnd', 'limit', 'offset');
        return $this->client->get('/v2/abuse/tickets', $filters);
    }

    /**
     * Create a new abuse ticket (API version 2).
     *
     * @param array $abuseTicketData Data for creating the abuse ticket.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function createAbuseTicketV2($abuseTicketData)
    {
        return $this->client->post('/v2/abuse/tickets', $abuseTicketData);
    }

    /**
     * Get abuse ticket information for a given ticket ID (API version 2).
     *
     * @param string $ticketId A unique abuse ticket identifier.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getAbuseTicketInfoV2($ticketId)
    {
        return $this->client->get("/v2/abuse/tickets/{$ticketId}");
    }
}
