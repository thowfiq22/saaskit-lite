<?php

use App\Http\Middleware\EnsureUserHasRole;
use App\Support\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
        $middleware->throttleApi();
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request, Throwable $throwable) => $request->is('api/*') || $request->expectsJson()
        );

        $exceptions->render(function (ValidationException $exception, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return ApiResponse::error(
                'The given data was invalid.',
                $exception->errors(),
                422
            );
        });

        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return ApiResponse::error(
                'Authentication is required to access this resource.',
                [],
                401
            );
        });

        $exceptions->render(function (AuthorizationException $exception, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return ApiResponse::error(
                $exception->getMessage() ?: 'You are not authorized to perform this action.',
                [],
                403
            );
        });

        $exceptions->render(function (AccessDeniedHttpException $exception, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return ApiResponse::error(
                $exception->getMessage() ?: 'You do not have permission to perform this action.',
                [],
                403
            );
        });

        $exceptions->render(function (ModelNotFoundException $exception, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return ApiResponse::error(
                'The requested resource could not be found.',
                [],
                404
            );
        });

        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return ApiResponse::error(
                'The requested endpoint could not be found.',
                [],
                404
            );
        });

        $exceptions->render(function (Throwable $exception, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            $message = config('app.debug')
                ? $exception->getMessage()
                : 'Something went wrong while processing the request.';

            return ApiResponse::error($message, [], 500);
        });
    })->create();
