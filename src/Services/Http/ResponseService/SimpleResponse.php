<?php

namespace Noisim\Support\Services\Http\ResponseService;


class SimpleResponse
{
    /**
     * Instance of SimpleResponseBody containing the response body content.
     * @var SimpleResponseBody
     */
    public $body;

    /**
     * Contains different info of the response like HTTP status code etc.
     * @var array
     */
    public $info = [];

    /**
     * Contains the headers of the response.
     * @var array
     */
    public $headers = [];

    /**
     * Contains the error if the response failed.
     */
    public $error;

    /**
     * Flag that tells if the body is a string or not.
     * @var bool
     */
    private $isString = false;

    public function __construct(array $body = [], array $info = [], array $headers = [])
    {
        $this->setBody($body);
        $this->setInfo($info);
        $this->setHeaders($headers);
    }

    /**
     * Set the body property which can be an instance of SimpleResponseBody or a string.
     *
     * @param mixed $body
     * @param bool $needsObject
     * @return SimpleResponse
     */
    public function setBody($body, bool $needsObject = true): SimpleResponse
    {
        if ($needsObject) {
            $simpleResponseBody = new SimpleResponseBody();
            $simpleResponseBody->setContent($body);
            $this->body = $simpleResponseBody;
            $this->isString = false;
        } else {
            $this->body = $body;
            $this->isString = true;
        }

        return $this;
    }

    /**
     * Set info property.
     *
     * @param array $info
     * @return SimpleResponse
     */
    public function setInfo(array $info): SimpleResponse
    {
        $this->info = $info;
        return $this;
    }

    /**
     * Set headers property.
     *
     * @param array $headers
     * @return SimpleResponse
     */
    public function setHeaders(array $headers): SimpleResponse
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Set error property.
     *
     * @param mixed $error
     * @return SimpleResponse
     */
    public function setError($error): SimpleResponse
    {
        $this->error = $error;
        return $this;
    }

    /**
     * Tells if the response body is a string or not.
     *
     * @return bool
     */
    public function isString(): bool
    {
        return $this->isString;
    }
}