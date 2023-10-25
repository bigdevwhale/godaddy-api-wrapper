<?php

namespace GoDaddyAPI\Exceptions;

use Exception;

/**
 * Class GoDaddyAPIException
 *
 * Exception class for handling GoDaddy API related errors.
 */
class GoDaddyAPIException extends Exception
{
    /**
     * GoDaddyAPIException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
