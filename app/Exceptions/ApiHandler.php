<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ApiHandler
{

    public function __invoke(Throwable $e, Request $request)
    {
        if ($request->is('api/*') || $request->expectsJson()) {
            if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                return errorResponse(404, 'Resource not found');
            }

            if ($e instanceof MethodNotAllowedHttpException) {
                return errorResponse(405, 'Method not allowed');
            }

            if ($e instanceof AuthenticationException) {
                return errorResponse(401, 'Unauthenticated');
            }

            if ($e instanceof AuthorizationException) {
                return errorResponse(403, 'Forbidden');
            }

            if ($e instanceof ThrottleRequestsException) {
                return errorResponse(429, 'Too many requests');
            }

            if ($e instanceof ValidationException) {
                return errorResponse(
                    422,
                    'Validation failed',
                    $e->errors()
                );
            }

            return errorResponse(500, 'Server error');
        }

        return null;
    }
}
