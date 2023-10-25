<?php


namespace GoDaddyAPI\Requests;

use GoDaddyAPI\Exceptions\GoDaddyAPIException;
use GoDaddyAPI\Http\GoDaddyApiClient;
use GoDaddyAPI\Models\GoDaddyResponse;

class SubscriptionsRequest
{
    private $client;

    public function __construct(GoDaddyApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieves a list of Subscriptions for the specified Shopper.
     *
     * @param string|null $shopperId Shopper ID to return subscriptions for when not using JWT.
     * @param string $marketId The market that the response should be formatted for. Default is "en-US".
     * @param array $productGroupKeys Only return Subscriptions with the specified product groups.
     * @param array $includes Optional details to be included in the response (addons, relations).
     * @param int $offset Number of Subscriptions to skip before starting to return paged results.
     * @param int $limit Number of Subscriptions to retrieve in this page, starting after offset.
     * @param string $sort Property name that will be used to sort results. "-" indicates descending.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function listSubscriptions(
        $shopperId = null,
        $marketId = 'en-US',
        $productGroupKeys = [],
        $includes = [],
        $offset = 0,
        $limit = 25,
        $sort = '-expiresAt'
    ) {
        $query = [
            'X-Shopper-Id' => $shopperId,
            'X-Market-Id' => $marketId,
            'productGroupKeys' => $productGroupKeys,
            'includes' => $includes,
            'offset' => $offset,
            'limit' => $limit,
            'sort' => $sort,
        ];

        return $this->client->get('/v1/subscriptions', $query);
    }

    /**
     * Retrieves a list of ProductGroups for the specified Shopper.
     *
     * @param string|null $shopperId Shopper ID to return data for when not using JWT.
     * @param string $marketId The market that the response should be formatted for. Default is "en-US".
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getProductGroups($shopperId = null, $marketId = 'en-US')
    {
        $query = [
            'X-Shopper-Id' => $shopperId,
            'X-Market-Id' => $marketId,
        ];

        return $this->client->get('/v1/subscriptions/productGroups', $query);
    }

    /**
     * Cancel the specified Subscription.
     *
     * @param string $subscriptionId Unique identifier of the Subscription to cancel.
     * @param string|null $shopperId Shopper ID to cancel subscriptions for when not using JWT.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function cancelSubscription($subscriptionId, $shopperId = null)
    {
        $headers = ['X-Shopper-Id' => $shopperId];
        $path = '/v1/subscriptions/' . $subscriptionId;

        return $this->client->delete($path, [], $headers);
    }

    /**
     * Retrieve details for the specified Subscription.
     *
     * @param string $subscriptionId Unique identifier of the Subscription to retrieve.
     * @param string|null $shopperId Shopper ID to be operated on, if different from JWT.
     * @param string $marketId Unique identifier of the Market in which the request is happening. Default is "en-US".
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function getSubscription($subscriptionId, $shopperId = null, $marketId = 'en-US')
    {
        $query = [
            'X-Shopper-Id' => $shopperId,
            'X-Market-Id' => $marketId,
        ];
        $path = '/v1/subscriptions/' . $subscriptionId;

        return $this->client->get($path, $query);
    }

    /**
     * Update details for the specified Subscription.
     *
     * @param string $subscriptionId Unique identifier of the Subscription to update.
     * @param array $subscriptionDetails Details of the Subscription to change.
     *
     * @return GoDaddyResponse
     * @throws GoDaddyAPIException
     */
    public function updateSubscription($subscriptionId, $subscriptionDetails)
    {
        $path = '/v1/subscriptions/' . $subscriptionId;

        return $this->client->patch($path, $subscriptionDetails);
    }
}
