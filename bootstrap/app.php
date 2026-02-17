<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['force.password.change' => \App\Http\Middleware\ForcePasswordChange::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Authentication
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }
            return redirect()
                ->route('login')
                ->with('error', 'دسترسی محدود || لطفا ابتدا وارد شوید.');
        });

        // Authorization
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Forbidden'
                ], 403);
            }

            return redirect()
                ->back()
                ->with('error', 'شما دسترسی لازم را ندارید.');
        });
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() !== 403) {
                return null;
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            return redirect()
                ->back()
                ->with('error', 'شما دسترسی لازم را ندارید.');
        });
    })->create();
