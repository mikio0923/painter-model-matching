<?php

return [
    App\Providers\AppServiceProvider::class,
    // Telescope は dev 専用なので本番 (--no-dev) では除外
    // ローカル開発時のみ AppServiceProvider 内で条件付き登録する想定
    App\Providers\ViewServiceProvider::class,
];
