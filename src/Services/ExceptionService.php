<?php

namespace Noisim\Support\Services;


use Illuminate\Support\Facades\Log;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;

class ExceptionService
{
    /**
     * Log exception and notify to the specified exception reporters.
     * @param \Exception $exception
     */
    public function notify(\Exception $exception)
    {
        if (env('APP_ENV', 'production') == 'local') {
            Log::error('error', [
                $exception->getMessage(),
                $exception->getLine(),
                $exception->getTraceAsString()
            ]);
        }

        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }

        BugSnag::notifyException($exception);
    }
}