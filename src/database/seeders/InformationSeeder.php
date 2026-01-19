<?php

namespace Database\Seeders;

use App\Models\Information;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // お知らせ
        Information::create([
            'title' => '年末年始休業のお知らせ',
            'content' => '誠に勝手ながら、年末年始の期間中は休業とさせていただきます。\n\n休業期間：2025年12月29日（月）〜2026年1月5日（月）\n\nご不便をおかけいたしますが、何卒ご理解のほどお願い申し上げます。',
            'type' => 'information',
            'published_at' => Carbon::parse('2025-12-22'),
            'is_published' => true,
        ]);

        Information::create([
            'title' => '夏季休業のお知らせ',
            'content' => '誠に勝手ながら、夏季休業期間中は休業とさせていただきます。\n\n休業期間：2025年8月11日（月）〜2025年8月17日（日）\n\nご不便をおかけいたしますが、何卒ご理解のほどお願い申し上げます。',
            'type' => 'information',
            'published_at' => Carbon::parse('2025-08-04'),
            'is_published' => true,
        ]);

        Information::create([
            'title' => 'GW期間中のサポートについて',
            'content' => 'ゴールデンウィーク期間中も通常通りサポートを実施しております。\n\nただし、回答までにお時間をいただく場合がございます。\n\nご理解のほどお願い申し上げます。',
            'type' => 'information',
            'published_at' => Carbon::parse('2025-04-30'),
            'is_published' => true,
        ]);

        Information::create([
            'title' => '年末年始休業のお知らせ',
            'content' => '誠に勝手ながら、年末年始の期間中は休業とさせていただきます。\n\n休業期間：2024年12月29日（日）〜2025年1月5日（日）\n\nご不便をおかけいたしますが、何卒ご理解のほどお願い申し上げます。',
            'type' => 'information',
            'published_at' => Carbon::parse('2024-12-12'),
            'is_published' => true,
        ]);

        // プレスリリース
        Information::create([
            'title' => 'Q-pot CAFE. 期間限定クリスマスメニュー 「Happy Melty Christmas」 10月12日12:00',
            'content' => 'Q-pot CAFE.にて、期間限定のクリスマスメニュー「Happy Melty Christmas」が2023年11月1日（水）より提供開始されます。\n\n詳細は公式サイトをご確認ください。',
            'type' => 'press_release',
            'published_at' => Carbon::parse('2023-10-12'),
            'is_published' => true,
        ]);

        Information::create([
            'title' => 'とろ~りチョコレートソールの「レースアップブーツ」がQ-pot. SHOESからデビュー!! 10月10日14:00',
            'content' => 'Q-pot. SHOESから、とろ~りチョコレートソールの「レースアップブーツ」が新発売されました。\n\n詳細は公式サイトをご確認ください。',
            'type' => 'press_release',
            'published_at' => Carbon::parse('2023-10-10'),
            'is_published' => true,
        ]);

        Information::create([
            'title' => 'デザインも機能も美味しい! Q-pot. 「チョコレート」 財布&パスケースが10月7日(土)発売。10月4日18:00',
            'content' => 'Q-pot.より、「チョコレート」をモチーフにした財布&パスケースが2023年10月7日（土）より発売されます。\n\n詳細は公式サイトをご確認ください。',
            'type' => 'press_release',
            'published_at' => Carbon::parse('2023-10-04'),
            'is_published' => true,
        ]);
    }
}
