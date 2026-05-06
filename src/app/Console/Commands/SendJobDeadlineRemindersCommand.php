<?php

namespace App\Console\Commands;

use App\Mail\JobDeadlineReminderMail;
use App\Models\EmailPreference;
use App\Models\Job;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * 応募締切が近い依頼の画家にリマインドメールを送る。
 * 条件:
 *   - status = open
 *   - apply_deadline が今から3日以内（過去日除く）
 *   - deadline_reminder_sent_at が NULL（重複送信防止）
 *   - 画家のオプトアウト設定で reminder_emails が true
 */
class SendJobDeadlineRemindersCommand extends Command
{
    protected $signature = 'jobs:send-deadline-reminders {--days=3 : 締切何日前から通知するか}';

    protected $description = '応募締切が近い依頼の画家にリマインドメールを送信する';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $now  = now();

        $jobs = Job::query()
            ->where('status', 'open')
            ->whereNotNull('apply_deadline')
            ->whereNull('deadline_reminder_sent_at')
            ->whereDate('apply_deadline', '>=', $now->toDateString())
            ->whereDate('apply_deadline', '<=', $now->copy()->addDays($days)->toDateString())
            ->with('painter')
            ->get();

        $sent = 0;
        foreach ($jobs as $job) {
            $painter = $job->painter;
            if (!$painter) {
                continue;
            }

            if (!EmailPreference::allows($painter, 'reminder')) {
                // オプトアウト中。送らないが reminder_sent_at は付ける（次回も判定回避）
                $job->update(['deadline_reminder_sent_at' => $now]);
                continue;
            }

            Mail::to($painter->email)->queue(new JobDeadlineReminderMail($job));
            $job->update(['deadline_reminder_sent_at' => $now]);
            $sent++;
        }

        $this->info("締切リマインダー送信完了: {$sent}件 / 対象 {$jobs->count()}件");

        return self::SUCCESS;
    }
}
