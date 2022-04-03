<?php

namespace App\Http\Middleware;

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful as BaseSanctumMiddleware;

class EnsureFrontendRequestsAreStateful extends BaseSanctumMiddleware
{
    /**
     * Determines if we are running from a phpunit test
     *
     * @return bool
     */
    protected static function isEnvTesting(): bool
    {
        return env("APP_ENV") === 'local';
    }

    /**
     * Override parent method to make it look like request is from front end if we are running from a phpunit test
     *
     * @inheritDoc
     */
    public static function fromFrontend($request): bool
    {
        return static::isEnvTesting() || parent::fromFrontend($request);
    }
}


