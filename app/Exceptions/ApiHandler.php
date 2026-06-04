<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\QueryException;
use App\Exceptions\EmailNotVerifiedException;


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

            if ($e instanceof AccessDeniedHttpException || $e instanceof AuthorizationException) {
                return errorResponse(403, __('auth.forbidden'));
            }
            if ($e instanceof EmailNotVerifiedException) {
                return errorResponse(401, 'Email not verified');
            }
            if ($e instanceof ThrottleRequestsException) {
                return errorResponse(429, 'Too many requests');
            }
            // if ($e instanceof QueryException) {
            //     return errorResponse(500, 'Database error');
            // }
            if ($e instanceof EmailAlreadyVerifiedException) {
                return errorResponse(422, 'Email already verified.');
            }
            if ($e instanceof InvalidOtpException) {
                return errorResponse(422, 'Invalid or expired OTP');
            }
            if ($e instanceof ValidationException) {
                return errorResponse(
                    422,
                    'Validation failed',
                    $e->errors()
                );
            }
            // return errorResponse(500, 'Server error');
        }

        return null;
    }
}
