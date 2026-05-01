<?php

namespace App\Console\Commands;

use App\Services\AccountDeletionService;
use Illuminate\Console\Command;

class AnonymizeDeletedUsersCommand extends Command
{
    protected $signature = 'users:anonymize-deleted';

    protected $description = '猶予期間（' . AccountDeletionService::GRACE_PERIOD_DAYS . '日）が経過した退会済みユーザーを匿名化する';

    public function handle(AccountDeletionService $service): int
    {
        $this->info('匿名化処理を開始します...');

        $count = $service->anonymizeExpiredUsers();

        $this->info("匿名化完了: {$count}件");

        return self::SUCCESS;
    }
}
