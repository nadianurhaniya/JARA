<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
        $exceptions->render(function (QueryException $e, Request $request) {
            if (! $request->routeIs('task-lists.store', 'task-lists.update', 'task-lists.destroy')) {
                return null;
            }

            $message = 'Daftar tugas tidak dapat diproses. Silakan coba lagi.';

            return $request->expectsJson()
                ? response()->json(['message' => $message], 500)
                : response($message, 500);
        });
    })->create();
