<?php

namespace Database\Seeders;

use App\Models\ModelProfile;
use App\Models\ModelProfileQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;

class ModelProfileQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            '撮影の服装は指定されますか？それとも自由でよろしいでしょうか。',
            '撮影時間はどのくらいを想定されていますか？',
            '交通費は別途支給いただけますか？',
            '顔出しは必須でしょうか？',
            '撮影データの使用範囲について教えてください。SNSなどへの掲載は可能でしょうか。',
            '経験が少ないのですが、初心者でも大丈夫でしょうか？',
            '複数回の撮影のご依頼でしょうか？それとも1回限りでしょうか。',
            '撮影場所の駐車場や更衣室はございますか？',
            'メイクや髪型の指定はありますか？',
            '作品の完成後、モデル用のデータはいただけるでしょうか。',
        ];

        $answers = [
            '白やベージュなどシンプルな色合いでお願いしております。具体的な衣装は当日お見せします。',
            '2〜3時間程度を想定しております。休憩も挟みますのでご安心ください。',
            'はい、実費をお支払いいたします。',
            '顔出し必須ではありません。お好みに合わせて調整いたします。',
            '個展やポートフォリオでの使用を想定しています。SNS掲載の可否は相談させてください。',
            '大丈夫です。リラックスして自然な雰囲気を出していただける方が嬉しいです。',
            '1回限りを想定しておりますが、よろしければ継続もご相談させてください。',
            'スタジオをご利用予定で、駐車場・更衣室とも完備されております。',
            'ナチュラルメイクでお願いしております。事前にイメージ写真をお送りします。',
            'はい、モデル様用に編集したデータをお渡しします。',
        ];

        $modelProfiles = ModelProfile::where('is_public', true)->get();
        $painters = User::where('role', 'painter')->get();

        if ($modelProfiles->isEmpty() || $painters->isEmpty()) {
            return;
        }

        foreach ($modelProfiles->take(10) as $modelProfile) {
            // 各モデルに2〜5件の質問を作成
            $count = rand(2, 5);
            $availablePainters = $painters->where('id', '!=', $modelProfile->user_id);
            if ($availablePainters->isEmpty()) {
                continue;
            }
            $selectedPainters = $availablePainters->random(min($count, $availablePainters->count()));
            $selectedPainters = collect($selectedPainters); // random(1)は単一オブジェクトを返すため

            foreach ($selectedPainters as $painter) {
                // 自分自身への質問はスキップ
                if ($painter->id === $modelProfile->user_id) {
                    continue;
                }

                $qIndex = array_rand($questions);
                $question = $questions[$qIndex];
                $answer = rand(0, 1) === 1 ? $answers[$qIndex] : null; // 半数は未回答

                ModelProfileQuestion::create([
                    'model_profile_id' => $modelProfile->id,
                    'asker_id' => $painter->id,
                    'question' => $question,
                    'answer' => $answer,
                ]);
            }
        }
    }
}
