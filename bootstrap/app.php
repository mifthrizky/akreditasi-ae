<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'check.permission' => \App\Http\Middleware\CheckPagePermission::class,
        ]);

        // Apply permission check globally to all authenticated routes
        $middleware->appendToGroup('web', \App\Http\Middleware\CheckPagePermission::class);

        // Redirect authenticated users away from /login page dynamically based on their role
        $middleware->redirectUsersTo(function () {
            $role = auth()->user()->role ?? null;
            return match ($role) {
                'admin' => route('admin.dashboard'),
                'dosen' => route('dosen.dashboard'),
                'validator' => route('validator.dashboard'),
                default => '/',
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
