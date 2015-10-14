<?php

/**
 * ApiClient
 *
 * PHP version 5
 *
 * @category Services
 * @package  RestfulClient
 * @author   Jamie Cressey <jamiecressey89@gmail.com>
 * @license  http://creativecommons.org/licenses/MIT/ MIT
 * @link     http://github.com/JamieCressey/php-restful-client
 */

/**
 * RestfulClient API interface.
 *
 * @category Services
 * @package  RestfulClient
 * @author   Jamie Cressey <jamiecressey89@gmail.com>
 * @license  http://creativecommons.org/licenses/MIT/ MIT
 * @link     http://github.com/JamieCressey/php-restful-client
 */

namespace RestfulClient;

class ApiClient
{
    const VERSION = '0.9.0';

    protected $rest;
    protected $api_key;
    protected $api_secret;
    protected $api_url;

    /**
     * Constructor.
     *
     * @param string $api_key Api Key
     * @param string $api_secret Api Secret
     * @param string $api_url Api URL
     */
    public function __construct($api_key, $api_secret, $api_url)
    {
        $this->rest = new \GuzzleHttp\Client(array(
            'base_url' => "{$api_url}",
            'defaults' => array(
                'headers'       => array(
                    'User-Agent' => $this->__getUserAgent()
                ),
                'exceptions'    => false
            )
        ));

        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        $this->api_url = $api_url;
    }

    /**
     * Send a POST request.
     *
     * @param string $path	API endpoint to send request to
     * @param array  $data	Optional array of options, for example: array("Title" => "Twilight")
     *
     * @return ApiResponse	The server response
     */
    public function post($path, $data = array())
    {
        $headers = array(
            'Content-Type' => 'application/json'
        );	
        return new ApiResponse($this->__request("POST", $path, $data, $headers));
    }

    /**
     * Send a GET request.
     *
     * @param string $path	API endpoint to send request to
     * @param array  $data	Optional array of options, for example: array("Title" => "Twilight")
     *
     * @return ApiResponse	The server response
     */
    public function get($path, $data = array())
    {
        return new ApiResponse($this->__request("GET", $path, $data));
    }

    /**
     * Send a PUT request.
     *
     * @param string $path	API endpoint to send request to
     * @param array  $data	Optional array of options, for example: array("Title" => "Twilight")
     *
     * @return ApiResponse	The server response
     */
    public function put($path, $data = array())
    {
        $headers = array(
            'Content-Type' => 'application/json'
        );	
        return new ApiResponse($this->__request("PUT", $path, $data, $headers));
    }

    /**
     * Send a DELETE request.
     *
     * @param string $path	API endpoint to send request to
     * @param array  $data	Optional array of options, for example: array("Title" => "Twilight")
     *
     * @return ApiResponse	The server response
     */
    public function delete($path, $data = array())
    {
        return new ApiResponse($this->__request("DELETE", $path, $data));
    }

    private function __getUserAgent()
    {
        return sprintf(
            'PHPClient/%s (%s-%s-%s; PHP %s)', 
            ApiClient::VERSION, 
            php_uname('s'), 
            php_uname('r'), 
            php_uname('m'), 
            phpversion()
        );
    }

    private function __request($method, $path, $data = array(), $headers = array())
    {
	$nonce = round(microtime(true) * 1000);
        $method = strtolower($method);
	$_headers = array(
                'X-Authentication-Key' => $this->api_key,
                'X-Authentication-Nonce' => $nonce,
                'X-Authentication-Signature' => base64_encode(hash_hmac('sha256', $nonce, $this->api_secret, true))
        );
        return $this->rest->$method($path, array(
            'json' => $data,
            'headers' => array_merge(
                $headers,
                $_headers
            )
        ));
    }

}
