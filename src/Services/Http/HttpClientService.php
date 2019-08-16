<?php

namespace Noisim\Support\Services\Http;


use Noisim\Support\Services\Http\ResponseService\SimpleResponse;
use Zjango\Curl\Curl;

class HttpClientService
{
    /**
     * A Curl object that we need to make HTTP requests.
     * @var Curl
     */
    private $client;

    /**
     * The base of the URL which we are going to make requests.
     * @var string
     */
    private $baseUrl;

    /**
     * The bearer token necessary for the authentication.
     * @var string
     */
    private $bearerToken;

    /**
     * The headers of the request.
     * @var array
     */
    private $headers = [];

    /**
     * A flag which shows if the response body need to be JSON decoded or not.
     * @var bool
     */
    private $needsJsonDecode = true;

    public function __construct(string $bearerToken = null, string $baseUrl = null)
    {
        $this->client = new Curl();
        $this->setBaseUrl($baseUrl);
        $this->setBearerToken($bearerToken);
    }

    /**
     * Set the base URL.
     *
     * @param string|null $baseUrl
     * @return HttpClientService
     */
    public function setBaseUrl(string $baseUrl = null): HttpClientService
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * Set bearer token.
     *
     * @param string|null $bearerToken
     * @return HttpClientService
     */
    public function setBearerToken(string $bearerToken = null): HttpClientService
    {
        $this->bearerToken = $bearerToken;

        if ($bearerToken) {
            $this->client->setHeader("Authorization", "Bearer {$bearerToken}");
        }

        return $this;
    }

    /**
     * Set the flag which shows if the response body need to be JSON decoded or not.
     *
     * @param bool $needsJsonDecode
     * @return HttpClientService
     */
    public function needsJsonDecode(bool $needsJsonDecode = true): HttpClientService
    {
        $this->needsJsonDecode = $needsJsonDecode;
        return $this;
    }

    /**
     * Set basic authentication.
     *
     * @param string $username
     * @param string $password
     * @return HttpClientService
     */
    public function setBasicAuthentication(string $username, string $password): HttpClientService
    {
        $this->client->setBasicAuthentication($username, $password);
        return $this;
    }


    /**
     * Set a CURL option.
     *
     * @param $option
     * @param $value
     * @return HttpClientService
     */
    public function setOption($option, $value): HttpClientService
    {
        $this->client->setOpt($option, $value);
        return $this;
    }

    /**
     * Add request header.
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setHeader(string $key, string $value): HttpClientService
    {
        $this->headers[$key] = $value;
        $this->client->setHeader($key, $value);

        return $this;
    }

    /**
     * Make a GET request to the specified URL.
     * Pass optional specified data as query params.
     *
     * @param string $url
     * @param array $data
     * @return SimpleResponse
     */
    public function get(string $url, array $data = []): SimpleResponse
    {
        $fullUrl = $this->baseUrl . $url;
        $curlResponse = $this->client->get($fullUrl, $data);

        return $this->getSimpleResponse($curlResponse);
    }

    /**
     * Make a POST request to the specified URL, with the specified data.
     *
     * @param string $url
     * @param mixed $data
     * @return SimpleResponse
     */
    public function post(string $url, $data = []): SimpleResponse
    {
        $fullUrl = $this->baseUrl . $url;
        $curlResponse = $this->client->post($fullUrl, $data);

        return $this->getSimpleResponse($curlResponse);
    }

    /**
     * Make a PUT request to the specified URL, with the specified data.
     *
     * @param string $url
     * @param mixed $data
     * @return SimpleResponse
     */
    public function put(string $url, $data = []): SimpleResponse
    {
        $fullUrl = $this->baseUrl . $url;
        $curlResponse = $this->client->put($fullUrl, $data, true);

        return $this->getSimpleResponse($curlResponse);
    }

    /**
     * Make a DELETE request to the specified URL.
     *
     * @param string $url
     * @param array $data
     * @return SimpleResponse
     */
    public function delete(string $url, array $data = []): SimpleResponse
    {
        $fullUrl = $this->baseUrl . $url;
        $curlResponse = $this->client->delete($fullUrl, $data);

        return $this->getSimpleResponse($curlResponse);
    }

    /**
     * Build a more simple response object getting data from the response of our Curl client.
     * Decode the JSON body of curlResponse.
     *
     * @param Curl $curlResponse
     * @return SimpleResponse
     */
    public function getSimpleResponse(Curl $curlResponse): SimpleResponse
    {
        $response = new SimpleResponse();
        $response->setInfo($curlResponse->info);

        if (is_array($curlResponse->response_headers) && !empty($curlResponse->response_headers)) {
            $response->setHeaders($curlResponse->response_headers);
        }

        $response->setError($curlResponse->error);

        if ($this->needsJsonDecode) {
            $body = json_decode($curlResponse->body, true);

            if (!is_array($body)) {
                $body = $curlResponse->body;
                $this->needsJsonDecode = false;
            }

        } else {
            $body = $curlResponse->body;
        }

        $response->setBody($body, $this->needsJsonDecode);

        return $response;
    }
}