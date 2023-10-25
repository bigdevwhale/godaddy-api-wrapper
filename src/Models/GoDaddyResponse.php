<?php

namespace GoDaddyAPI\Models;

/**
 * Class GoDaddyResponse
 *
 * Represents the response received from the GoDaddy API.
 */
class GoDaddyResponse
{
    /**
     * @var int HTTP status code of the response.
     */
    protected $statusCode;

    /**
     * @var string Response data in JSON format.
     */
    protected $data;

    /**
     * @var array Response headers.
     */
    protected $headers;

    /**
     * GoDaddyResponse constructor.
     *
     * @param int $statusCode HTTP status code of the response.
     * @param string $data Response data in JSON format.
     * @param array $headers Response headers.
     */
    public function __construct($statusCode, $data, $headers)
    {
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->headers = $headers;
    }

    /**
     * Get the HTTP status code of the response.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Get the response data in JSON format.
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the response headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Check if the response indicates success (HTTP status code in the 2xx range).
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    /**
     * Get the error message from the response data, if available.
     *
     * @return string|null The error message or null if not found.
     */
    public function getErrorMessage()
    {
        // Extract error message from the response data
        $responseData = json_decode($this->data, true);
        if (isset($responseData['message'])) {
            return $responseData['message'];
        }
        return null;
    }
}
