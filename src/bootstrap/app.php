<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // 全Webリクエストでログイン中ユーザーのアクティビティを記録
        $middleware->web(append: [
            \App\Http\Middleware\TrackUserActivity::class,
        ]);

        // 未認証ユーザーのリダイレクト先を /admin/* と通常で分ける
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('admin/*') || $request->is('admin')) {
                return route('admin.login');
            }
            return route('login');
        });

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 本番環境のみ Sentry に例外を送信
        \Sentry\Laravel\Integration::handles($exceptions);
    })->create();
