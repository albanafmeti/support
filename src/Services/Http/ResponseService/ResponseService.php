<?php

namespace Noisim\Support\Services\Http\ResponseService;

use Illuminate\Http\JsonResponse;

class ResponseService
{
    /**
     * A flag which tells if the action was successfully done or not.
     * @var bool
     */
    private $success = true;

    /**
     * A general message returned to the user.
     * @var string
     */
    private $message;

    /**
     * Contains the data that the user is looking for.
     * @var array
     */
    private $data;

    /**
     * Custom status code necessary for our front-end apps.
     * @var int
     */
    private $code;

    /**
     * A list of error messages. Used in some cases like e.g. ValidationException which can contain a list of errors.
     * @var array
     */
    private $errors = [];

    /**
     * The HTTP code which will be sent with the response.
     * @var int
     */
    private $status = 200;

    /**
     * The headers which will be sent with the response.
     * @var array
     */
    private $headers = [];

    public function __construct(bool $success = true, string $message = null, $data = null, int $status = 200)
    {
        $this->setBodyFields($success, $message, $data);
        $this->setStatus($status);
    }

    /**
     * Return a JsonResponse instance with `success` => true and the specified body fields.
     *
     * @param string|null $message
     * @param mixed $data
     * @param int|null $status
     * @return JsonResponse
     */
    public function withSuccess(string $message = null, $data = null, int $status = null): JsonResponse
    {
        $message = $message ?? $this->message;
        $data = $data ?? $this->data;
        $status = $status ?? $this->status;

        $this->setBodyFields(true, $message, $data);
        $body = $this->getFormattedResponseBody();

        return $this->json($body, $status, $this->headers);
    }

    /**
     * Return a JsonResponse instance with `success` => false and the specified body fields.
     *
     * @param string|null $message
     * @param mixed $data
     * @param int|null $status
     * @param array $errors
     * @return JsonResponse
     */
    public function withError(string $message = null, $data = null, int $status = null, array $errors = []): JsonResponse
    {
        $message = $message ?? $this->message;
        $data = $data ?? $this->data;
        $status = $status ?? $this->status;
        $errors = !empty($errors) ? $errors : $this->errors;

        $this->setBodyFields(false, $message, $data, $this->code, $errors);
        $body = $this->getFormattedResponseBody();

        return $this->json($body, $status, $this->headers);
    }

    /**
     * Return a JsonResponse instance with the specified body, status and headers.
     *
     * @param array $body
     * @param int|null $status
     * @param array $headers
     * @return JsonResponse
     */
    public function withBody(array $body, int $status = null, array $headers = [])
    {
        $status = $status ?? $this->status;
        $headers = !empty($headers) ? $headers : $this->headers;

        return $this->json($body, $status, $headers);
    }

    /**
     * Set the response body fields all together.
     *
     * @param bool $success
     * @param string $message
     * @param mixed $data
     * @param int $code
     * @param array $errors
     * @return ResponseService
     */
    public function setBodyFields(bool $success = true, string $message = null, $data = null, int $code = null, array $errors = []): ResponseService
    {
        $this->setSuccess($success);
        $this->setMessage($message);
        $this->setData($data);
        $this->setCode($code);
        $this->setErrors($errors);

        return $this;
    }

    /**
     * Set response body `success` key.
     *
     * @param bool $success
     * @return ResponseService
     */
    public function setSuccess(bool $success): ResponseService
    {
        $this->success = $success;
        return $this;
    }

    /**
     * Set response body `message` key.
     *
     * @param string|null $message
     * @return ResponseService
     */
    public function setMessage(string $message = null): ResponseService
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Set response body `data` key.
     *
     * @param mixed $data
     * @return ResponseService
     */
    public function setData($data): ResponseService
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set response body `status` key.
     *
     * @param int|null $code
     * @return ResponseService
     */
    public function setCode(int $code = null): ResponseService
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Set request body `errors` key.ss
     *
     * @param array $errors
     * @return ResponseService
     */
    public function setErrors(array $errors): ResponseService
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * Build a predefined array with the keys and data that will be returned to front-end.
     *
     * @return array
     */
    private function getFormattedResponseBody(): array
    {
        $body = [];
        $body['success'] = $this->success;
        $body['data'] = $this->data ?? [];

        if (!empty($this->message)) {
            $body['message'] = $this->message;
        }
        
        if (!empty($this->code)) {
            // At the moment we need this with `status` key. Later we have to change it to `code` or `errorCode`.
            $body['status'] = $this->code;
        }

        if (!empty($this->errors)) {
            $body['errors'] = $this->errors;
        }

        return $body;
    }

    /**
     * Set HTTP status code for the response.
     *
     * @param int $status
     * @return ResponseService
     */
    public function setStatus(int $status): ResponseService
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Set the headers of the response.
     *
     * @param array $headers
     * @return ResponseService
     */
    public function setHeaders(array $headers): ResponseService
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Get the JsonResponse object that will be returned to front-end (after serialization).
     * Fill the response with the specified data, status and headers.
     *
     * @param array $body
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function json(array $body = [], int $status = 200, array $headers = []): JsonResponse
    {
        return response()->json($body, $status, $headers, JSON_NUMERIC_CHECK);
    }
}