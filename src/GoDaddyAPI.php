<?php
namespace GoDaddyAPI;

use DomainsRequest;
use GoDaddyAPI\Http\GoDaddyApiClient;
use GoDaddyAPI\Requests\AbuseRequest;
use GoDaddyAPI\Requests\AftermarketRequest;
use GoDaddyAPI\Requests\AgreementsRequest;
use GoDaddyAPI\Requests\CertificatesRequest;
use GoDaddyAPI\Requests\CountriesRequest;
use GoDaddyAPI\Requests\OrdersRequest;
use GoDaddyAPI\Requests\ParkingRequest;
use GoDaddyAPI\Requests\ShoppersRequest;
use GoDaddyAPI\Requests\SubscriptionsRequest;

class GoDaddyAPI
{
    private $httpClient;
    private $abuseRequest;
    private $aftermarketRequest;
    private $agreementsRequest;
    private $certificatesRequest;
    private $countriesRequest;
    private $domainsRequest;
    private $ordersRequest;
    private $parkingRequest;
    private $shoppersRequest;
    private $subscriptionsRequest;

    public function __construct($apiKey, $apiSecret, $isProduction = true)
    {
        $this->httpClient = new GoDaddyApiClient($isProduction, $apiKey, $apiSecret);
        $this->abuseRequest = new AbuseRequest($this->httpClient);
        $this->aftermarketRequest = new AftermarketRequest($this->httpClient);
        $this->agreementsRequest = new AgreementsRequest($this->httpClient);
        $this->certificatesRequest = new CertificatesRequest($this->httpClient);
        $this->countriesRequest = new CountriesRequest($this->httpClient);
        $this->domainsRequest = new DomainsRequest($this->httpClient);
        $this->ordersRequest = new OrdersRequest($this->httpClient);
        $this->parkingRequest = new ParkingRequest($this->httpClient); 
        $this->shoppersRequest = new ShoppersRequest($this->httpClient);
        $this->subscriptionsRequest = new SubscriptionsRequest($this->httpClient);
    }

    public function abuse()
    {
        return $this->abuseRequest;
    }

    public function aftermarket()
    {
        return $this->aftermarketRequest;
    }

    public function agreements()
    {
        return $this->agreementsRequest;
    }

    public function certificates()
    {
        return $this->certificatesRequest;
    }

    public function countries()
    {
        return $this->countriesRequest;
    }

    public function domains()
    {
        return $this->domainsRequest;
    }

    public function orders()
    {
        return $this->ordersRequest;
    }

    public function parking()
    {
        return $this->parkingRequest;
    }

    public function shoppers()
    {
        return $this->shoppersRequest;
    }

    public function subscriptions()
    {
        return $this->subscriptionsRequest;
    }
}
