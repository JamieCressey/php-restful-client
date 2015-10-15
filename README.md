[![Build Status](https://img.shields.io/travis/JamieCressey/php-restful-client.svg)](https://travis-ci.org/JamieCressey/php-restful-client)
[![Package Version](https://img.shields.io/packagist/v/jamiecressey/restful-client.svg)](https://packagist.org/packages/jamiecressey/restful-client)
[![Package Downloads](https://img.shields.io/packagist/dm/jamiecressey/restful-client.svg)](https://packagist.org/packages/jamiecressey/restful-client)
[![Package License](https://img.shields.io/github/license/jamiecressey/php-restful-client.svg)]

# Generic RESTful PHP client

A generic RESTful PHP client for interacting with JSON APIs.

## Usage

To use this client you just need to import ApiClient and initialize it with an API Key, Secret and URL endpoint

    $api = new RestfulClient\ApiClient('#your_api_key', '#your_api_secret', '#your_api_endpoint');

Now that you have a RESTful API object you can start sending requests.

## Request Authentication

All requests include the following headers by default:
- 'X-Authentication-Key' - The API Key provided when creating the ApiClient object.
- 'X-Authentication-Nonce' - An incremental number to prevent request replays. By default this is the current epoch time in milliseconds.
- 'X-Authentication-Signature' - A SHA512 HMAC signature of the nonce, signed using the API Secret provided when creating the ApiClient object.

## Making a request

The framework supports GET, PUT, POST and DELETE requests:

    $api->get('/books/');
    $api->post('/books/', array('title' => 'Twilight', 'author' => 'Stephenie Meyer'));
    $api->put('/book/Twilight/', array('release_date' => '06/09/2006'));
    $api->delete('/book/Twilight/');

## Verifying Requests

Two helpers are built in to verify the success of requests made. `ok()` checks for a 20x status code and returns a boolean, `errors()` returns the body content as an associative array if the status code is not 20x:

    $req = $api->get('/books/');

    if( $req->ok() ) {
        echo 'Success!';
    } else {
        echo $req->errors();
    }

## Extending the client

The client can be extended to be more application specific, e.g. `$api->create_book('Twilight', 'Stephenie Meyer');`:

    class YourAPI extends \RestfulClient\ApiClient 
    {
        public function __construct($api_key, $api_secret)
        {
            $api_url = 'https://api.yourdomain.com'
            parent::__construct($api_key, $api_secret, $api_url);
        }

        public function create_book($title, $author)
        {
            $data = array(
                'title' => $title,
                'author' => $author,
            );

            return $this->post('/books/', $data);
        }
    }

## Contributing

All contributions are welcome, either for new\improved functionality or in response to any open bugs.
