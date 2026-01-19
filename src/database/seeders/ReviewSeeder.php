<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Job;
use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ratings = ['very_good', 'good', 'bad'];
        $ratingWeights = ['very_good' => 0.7, 'good' => 0.25, 'bad' => 0.05]; // 良い評価が多いように

        $comments = [
            'very_good' => [
                'この度はご協力いただき誠にありがとうございました。キャリアも長く信頼できるプロフェッショナルなモデルさんです。事前から丁寧にやり取りをしていただき、撮影もスムーズでした。',
                '非常に良いモデルさんでした。ポージングも自然で、こちらの要望をしっかりと理解してくださり、素晴らしい作品ができました。また機会があればお願いしたいです。',
                'プロフェッショナルな対応で、時間も守っていただき、撮影もスムーズに進みました。表現力も豊かで、こちらのイメージ通りの作品ができました。',
                'とても良い経験ができました。モデルさんの協力のおかげで、思い通りの作品を制作することができました。また機会があればぜひお願いしたいです。',
                '事前の打ち合わせから丁寧に対応していただき、当日もスムーズに撮影が進みました。表現力が豊かで、こちらの要望をしっかりと理解してくださり、素晴らしい作品ができました。',
            ],
            'good' => [
                '良いモデルさんでした。基本的な対応は問題なく、撮影も無事に終わりました。',
                '時間を守っていただき、基本的なポージングもできていました。また機会があればお願いしたいです。',
                '対応も丁寧で、撮影もスムーズに進みました。',
            ],
            'bad' => [
                '時間に遅れてきて、撮影がスムーズに進みませんでした。',
                '事前の打ち合わせが不十分で、当日の撮影で少し混乱がありました。',
            ],
        ];

        // 完了したジョブ（statusが'completed'または'accepted'の応募があるジョブ）を取得
        $completedJobs = Job::whereHas('applications', function ($query) {
            $query->where('status', 'accepted');
        })->get();

        if ($completedJobs->isEmpty()) {
            // 完了したジョブがない場合は、いくつかの応募をacceptedに変更
            $applications = JobApplication::take(20)->get();
            foreach ($applications as $application) {
                $application->update(['status' => 'accepted']);
            }
            $completedJobs = Job::whereHas('applications', function ($query) {
                $query->where('status', 'accepted');
            })->get();
        }

        $reviewCount = 0;
        $maxReviews = 30; // 最大30件のレビューを作成

        foreach ($completedJobs as $job) {
            if ($reviewCount >= $maxReviews) {
                break;
            }

            // このジョブの承認された応募を取得
            $acceptedApplications = $job->applications()->where('status', 'accepted')->get();

            foreach ($acceptedApplications as $application) {
                if ($reviewCount >= $maxReviews) {
                    break;
                }

                $model = $application->model;
                $painter = $job->painter;

                // 画家からモデルへのレビュー
                $rating = $this->weightedRandom($ratingWeights);
                $comment = $comments[$rating][array_rand($comments[$rating])];

                // 既存のレビューがないか確認
                $existingReview = Review::where('job_id', $job->id)
                    ->where('reviewer_id', $painter->id)
                    ->where('reviewed_user_id', $model->id)
                    ->first();

                if (!$existingReview) {
                    Review::create([
                        'job_id' => $job->id,
                        'reviewer_id' => $painter->id,
                        'reviewed_user_id' => $model->id,
                        'rating' => $rating,
                        'comment' => $comment,
                        'created_at' => now()->subDays(rand(1, 30)), // 過去30日以内のランダムな日付
                    ]);
                    $reviewCount++;
                }

                // モデルから画家へのレビュー（50%の確率で作成）
                if (rand(0, 1) === 1 && $reviewCount < $maxReviews) {
                    $rating = $this->weightedRandom($ratingWeights);
                    $comment = $comments[$rating][array_rand($comments[$rating])];

                    $existingReview = Review::where('job_id', $job->id)
                        ->where('reviewer_id', $model->id)
                        ->where('reviewed_user_id', $painter->id)
                        ->first();

                    if (!$existingReview) {
                        Review::create([
                            'job_id' => $job->id,
                            'reviewer_id' => $model->id,
                            'reviewed_user_id' => $painter->id,
                            'rating' => $rating,
                            'comment' => $comment,
                            'created_at' => now()->subDays(rand(1, 30)),
                        ]);
                        $reviewCount++;
                    }
                }
            }
        }

        $this->command->info("Created {$reviewCount} reviews.");
    }

    /**
     * 重み付きランダム選択
     */
    private function weightedRandom(array $weights): string
    {
        $total = array_sum($weights);
        $random = mt_rand(1, (int)($total * 100)) / 100;

        $cumulative = 0;
        foreach ($weights as $key => $weight) {
            $cumulative += $weight;
            if ($random <= $cumulative) {
                return $key;
            }
        }

        return array_key_first($weights);
    }
}
