# GoDaddy API PHP Wrapper

A PHP wrapper for interacting with the GoDaddy API. This wrapper simplifies the process of making HTTP requests to the GoDaddy API, allowing you to manage your domains, accounts, and perform various domain-related tasks.

## Getting Started

Before using this wrapper, you'll need to create an API Key and Secret by following the instructions provided in the [GoDaddy API Documentation](https://developer.godaddy.com/doc).

### Installation

You can install this wrapper using Composer. If you don't have Composer installed, [download it here](https://getcomposer.org/download/).

1. Create a new directory for your project and navigate to it in your terminal.

2. Inside your project directory, run the following Composer command to install the GoDaddy API PHP Wrapper:

   ```bash
   composer require your-namespace/godaddy-api-wrapper
   
Replace your-package/your-package-name with the actual package name you want to use.

3. After installation, you can use the wrapper by including the Composer autoloader in your PHP script:
   
   ```php
   require 'vendor/autoload.php';
   
Make sure to require the Composer autoloader at the beginning of your PHP script.

4. Make sure to require the Composer autoloader at the beginning of your PHP script.

    ```php
    // Include the Composer autoloader
    require 'vendor/autoload.php';
 
    // Initialize the GoDaddyAPI with your API Key and Secret for the test environment
    $apiKey = 'your-test-api-key';
    $apiSecret = 'your-test-api-secret';
    
    // Set the isProduction flag to false to use the test environment
    $isProduction = false;
    
    // Create an instance of GoDaddyAPI for the test environment
    $goDaddyAPI = new \GoDaddyAPI\GoDaddyAPI($apiKey, $apiSecret, $isProduction);
    
    // Access various API endpoints using the provided methods
    $abuseRequest = $goDaddyAPI->abuse();
    $aftermarketRequest = $goDaddyAPI->aftermarket();
    $agreementsRequest = $goDaddyAPI->agreements();
    $certificatesRequest = $goDaddyAPI->certificates();
    $countriesRequest = $goDaddyAPI->countries();
    $domainsRequest = $goDaddyAPI->domains();
    $ordersRequest = $goDaddyAPI->orders();
    $parkingRequest = $goDaddyAPI->parking();
    $shoppersRequest = $goDaddyAPI->shoppers();
    $subscriptionsRequest = $goDaddyAPI->subscriptions();
    
    // Use the API requests to perform specific actions
    // For example, list all abuse tickets
    try {
        $abuseTickets = $abuseRequest->getTickets();
        // Process the $abuseTickets response
        print_r($abuseTickets);
    } catch (\GoDaddyAPI\Exceptions\GoDaddyAPIException $e) {
        echo 'Error: ' . $e->getMessage();
    }

For detailed API reference and available endpoints, refer to the official [GoDaddy API documentation](https://developer.godaddy.com/doc).

### Configuration
You can configure the base URL (production or test environment) in the GoDaddyApiClient constructor by setting the $isProduction parameter.

## Contributing
We welcome contributions! Feel free to create issues, submit pull requests, or make improvements to this wrapper.

## License
This GoDaddy API PHP wrapper is licensed under the MIT License - see the LICENSE file for details.
