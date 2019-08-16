<?php

namespace Noisim\Support\Traits;


use Illuminate\Http\Request;
use Noisim\Support\Services\AuditLogService;
use Noisim\Support\Services\ExceptionService;
use Noisim\Support\Services\Http\HttpClientService;
use Noisim\Support\Services\Http\ResponseService\ResponseService;
use Noisim\Support\Services\QueueService;

trait SupportServices
{
    /**
     * Get a HttpClientService instance that we can use to make HTTP requests.
     *
     * @param string|null $bearerToken
     * @param string|null $baseUrl
     * @return HttpClientService
     */
    protected function httpClient(string $bearerToken = null, string $baseUrl = null): HttpClientService
    {
        return app()->makeWith(HttpClientService::class, ['bearerToken' => $bearerToken, 'baseUrl' => $baseUrl]);
    }

    /**
     * Get a ResponseService instance with the specified data.
     *
     * @param bool $success
     * @param string|null $message
     * @param mixed $data
     * @param int $status
     * @return ResponseService
     */
    protected function response(bool $success = true, string $message = null, $data = null, int $status = 200): ResponseService
    {
        return app()->makeWith(ResponseService::class, ['success' => $success, 'message' => $message, 'data' => $data, 'status' => $status]);
    }

    /**
     * Get an ExceptionService instance.
     * @return ExceptionService
     */
    protected function exception(): ExceptionService
    {
        return app()->make(ExceptionService::class);
    }
}