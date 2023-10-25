<?php

namespace GoDaddyAPI\Requests;

use GoDaddyAPI\Exceptions\GoDaddyAPIException;
use GoDaddyAPI\Http\GoDaddyApiClient;
use GoDaddyAPI\Models\GoDaddyResponse;

class CertificatesRequest
{
    private $client;

    public function __construct(GoDaddyApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create a pending order for a certificate.
     *
     * @param array $certificateCreate
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function createCertificateOrder($certificateCreate)
    {
        return $this->client->post("/v1/certificates", $certificateCreate);
    }

    /**
     * Validate a pending order for a certificate.
     *
     * @param array $certificateCreate
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function validateCertificateOrder($certificateCreate)
    {
        return $this->client->post("/v1/certificates/validate", $certificateCreate);
    }

    /**
     * Retrieve certificate details.
     *
     * @param string $certificateId
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getCertificateDetails($certificateId)
    {
        return $this->client->get("/v1/certificates/{$certificateId}");
    }

    /**
     * Retrieve all stateful actions relating to a certificate lifecycle.
     *
     * @param string $certificateId
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getCertificateActions($certificateId)
    {
        return $this->client->get("/v1/certificates/{$certificateId}/actions");
    }

    /**
     * Resend an email for a certificate.
     *
     * @param string $certificateId
     * @param string $emailId
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function resendCertificateEmail($certificateId, $emailId)
    {
        return $this->client->post("/v1/certificates/{$certificateId}/email/{$emailId}/resend");
    }

    /**
     * Add an alternate email address to a certificate order and resend all existing request emails to that address.
     *
     * @param string $certificateId
     * @param string $emailAddress
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function addAlternateEmailAddress($certificateId, $emailAddress)
    {
        return $this->client->post("/v1/certificates/{$certificateId}/email/resend/{$emailAddress}");
    }

    /**
     * Resend an email to a specific email address for a certificate.
     *
     * @param string $certificateId
     * @param string $emailId
     * @param string $emailAddress
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function resendEmailToEmailAddress($certificateId, $emailId, $emailAddress)
    {
        return $this->client->post("/v1/certificates/{$certificateId}/email/{$emailId}/resend/{$emailAddress}");
    }

    /**
     * Retrieve email history for a certificate.
     *
     * @param string $certificateId
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getCertificateEmailHistory($certificateId)
    {
        return $this->client->get("/v1/certificates/{$certificateId}/email/history");
    }

    /**
     * Unregister the callback for a particular certificate.
     *
     * @param string $certificateId
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function unregisterCallback($certificateId)
    {
        return $this->client->delete("/v1/certificates/{$certificateId}/callback");
    }

    /**
     * Retrieve the registered callback URL for a certificate.
     *
     * @param string $certificateId
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getCallbackUrl($certificateId)
    {
        return $this->client->get("/v1/certificates/{$certificateId}/callback");
    }

    /**
     * Register or replace a URL for callbacks for stateful actions relating to a certificate lifecycle.
     *
     * @param string $certificateId
     * @param string $callbackUrl
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function registerCallback($certificateId, $callbackUrl)
    {
        return $this->client->put("/v1/certificates/{$certificateId}/callback", ['callbackUrl' => $callbackUrl]);
    }

    /**
     * Cancel a pending certificate order.
     *
     * @param string $certificateId
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function cancelCertificateOrder($certificateId)
    {
        return $this->client->post("/v1/certificates/{$certificateId}/cancel");
    }

    /**
     * Download a certificate.
     *
     * @param string $certificateId
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function downloadCertificate($certificateId)
    {
        return $this->client->get("/v1/certificates/{$certificateId}/download");
    }

    /**
     * Reissue a certificate.
     *
     * @param string $certificateId
     * @param array $reissueCreate
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function reissueCertificate($certificateId, $reissueCreate)
    {
        return $this->client->post("/v1/certificates/{$certificateId}/reissue", $reissueCreate);
    }

    /**
     * Renew a certificate.
     *
     * @param string $certificateId
     * @param array $renewCreate
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function renewCertificate($certificateId, $renewCreate)
    {
        return $this->client->post("/v1/certificates/{$certificateId}/renew", $renewCreate);
    }

    /**
     * Revoke a certificate.
     *
     * @param string $certificateId
     * @param array $certificateRevoke
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function revokeCertificate($certificateId, $certificateRevoke)
    {
        return $this->client->post("/v1/certificates/{$certificateId}/revoke", $certificateRevoke);
    }

    /**
     * Get site seal information for a certificate.
     *
     * @param string $certificateId
     * @param string $theme
     * @param string $locale
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getSiteSeal($certificateId, $theme = "LIGHT", $locale = "en")
    {
        $queryParams = [
            'theme' => $theme,
            'locale' => $locale,
        ];

        return $this->client->get("/v1/certificates/{$certificateId}/siteSeal", $queryParams);
    }

    /**
     * Verify domain control for a certificate.
     *
     * @param string $certificateId
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function verifyDomainControl($certificateId)
    {
        return $this->client->post("/v1/certificates/{$certificateId}/verifyDomainControl");
    }

    /**
     * Get certificate details by entitlement.
     *
     * @param string $entitlementId
     * @param bool $latest
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getCertificatesByEntitlement($entitlementId, $latest = true)
    {
        $queryParams = [
            'entitlementId' => $entitlementId,
            'latest' => $latest,
        ];

        return $this->client->get("/v2/certificates", $queryParams);
    }

    /**
     * Download a certificate by entitlement.
     *
     * @param string $entitlementId
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function downloadCertificateByEntitlement($entitlementId)
    {
        $queryParams = [
            'entitlementId' => $entitlementId,
        ];

        return $this->client->get("/v2/certificates/download", $queryParams);
    }

    /**
     * Get certificates for a specific customer by customer ID.
     *
     * @param string $customerId
     * @param int $offset
     * @param int $limit
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getCustomerCertificatesByCustomerId($customerId, $offset, $limit)
    {
        $queryParams = [
            'customerId' => $customerId,
            'offset' => $offset,
            'limit' => $limit,
        ];

        return $this->client->get("/v2/customers/{$customerId}/certificates", $queryParams);
    }

    /**
     * Get individual certificate details by customer ID and certificate ID.
     *
     * @param string $customerId
     * @param string $certificateId
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getCertificateDetailByCertIdentifier($customerId, $certificateId)
    {
        return $this->client->get("/v2/customers/{$customerId}/certificates/{$certificateId}");
    }

    /**
     * Get domain verification status for a specific certificate and customer.
     *
     * @param string $customerId
     * @param string $certificateId
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getDomainVerificationStatus($customerId, $certificateId)
    {
        return $this->client->get("/v2/customers/{$customerId}/certificates/{$certificateId}/domainVerifications");
    }

    /**
     * Get detailed domain information for a specific certificate, customer, and domain.
     *
     * @param string $customerId
     * @param string $certificateId
     * @param string $domain
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getDomainDetails($customerId, $certificateId, $domain)
    {
        return $this->client->get("/v2/customers/{$customerId}/certificates/{$certificateId}/domainVerifications/{$domain}");
    }

    /**
     * Retrieve ACME External Account Binding information for a customer.
     *
     * @param string $customerId
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getAcmeExternalAccountBinding($customerId)
    {
        return $this->client->get("/v2/customers/{$customerId}/certificates/acme/externalAccountBinding");
    }
}
