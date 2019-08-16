<?php

if (!function_exists("notifyException")) {
    /**
     * Global function for exception notification.
     *
     * @param Exception $exception
     * @return mixed
     */
    function notifyException(Exception $exception)
    {
        return app()->make(\Noisim\Support\Services\ExceptionService::class)->notify($exception);
    }
}

if (!function_exists("reconnectToDb")) {
    /**
     * Set the new database connection and reconnect.
     *
     * @param string $ip
     * @param string $dbName
     */
    function reconnectToDb(string $ip, string $dbName): void
    {
        config([
            'database.connections.mysql.host' => $ip,
            'database.connections.mysql.database' => $dbName,
        ]);

        \Illuminate\Support\Facades\DB::reconnect();
    }
}

if (!function_exists("decodeAuthToken")) {
    /**
     * Decode auth JWT token.
     *
     * @param string $bearerToken
     * @return mixed
     */
    function decodeAuthToken(string $bearerToken)
    {
        return json_decode(base64_decode(strtr(explode('.', $bearerToken)[1], '-_', '+/')));
    }
}