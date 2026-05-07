@extends('layouts.app')

@section('title', 'モデルを探すガイド')
@section('description', '画家がモデルを探し、依頼を出すための具体的な流れと書き方のポイントをご紹介します。')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <div class="art-bg-stage">
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/starry-night.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/great-wave.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/pearl-earring.jpg') }}');"></div>
        <div class="art-bg-veil"></div>
    </div>
    <div class="page-header-inner">
        <p class="page-header-subtitle">For Painter</p>
        <h1 class="page-header-title mt-2">モデルを探すガイド</h1>
        <p class="text-secondary-500 text-sm mt-3">モデル探し・依頼作成からマッチングまでの流れをご紹介します。</p>
    </div>
</div>

<div class="page-narrow space-y-12">

    @php
        $sections = [
            ['label' => 'Search', 'title' => 'モデル検索の方法', 'type' => 'unordered', 'items' => [
                'モデル一覧ページから検索条件を指定して検索できます',
                '都道府県・性別・年齢・スタイルタグなどで絞り込み可能',
                'キーワード検索で表示名を検索できます',
                '報酬目安の範囲で絞り込むこともできます',
            ]],
            ['label' => 'Workflow', 'title' => '依頼を投稿する流れ', 'type' => 'ordered', 'items' => [
                '画家アカウントでログイン',
                '「依頼を作成」から新しい依頼を投稿',
                'タイトル・説明・報酬・場所・日程などを入力',
                '特定のモデルに依頼する場合はモデル詳細ページからも作成可能',
                '応募してきたモデルを確認し、承認・却下を判断',
                '承認後はメッセージで詳細を打ち合わせ',
            ]],
            ['label' => 'Writing', 'title' => '依頼文の書き方', 'type' => 'unordered', 'items' => [
                '依頼内容は具体的に記載してください',
                '用途（個展・練習・作品制作など）を明記すると応募が増えます',
                '報酬額を明確に設定してください',
                '日程・場所の希望を必ず記載してください',
                '応募締切を設定するとスムーズに進められます',
            ]],
            ['label' => 'Communication', 'title' => 'モデルとのやり取り', 'type' => 'unordered', 'items' => [
                '応募を承認するとメッセージ機能が利用可能になります',
                '詳細な打ち合わせはメッセージで進められます',
                '完了後はレビューを投稿できます',
                '良い体験は積極的にレビュー投稿をお願いします',
            ]],
            ['label' => 'Notice', 'title' => '注意事項', 'type' => 'unordered', 'items' => [
                '依頼内容は正確に記載してください',
                '報酬は事前に明確にしておいてください',
                '日程・場所は事前に必ず確認してください',
                'モデルとのコミュニケーションは丁寧にお願いします',
            ]],
        ];
    @endphp

    @foreach($sections as $section)
        <section>
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-2">{{ $section['label'] }}</p>
            <h2 class="font-display text-2xl font-semibold text-secondary-900 mb-5">{{ $section['title'] }}</h2>

            @if($section['type'] === 'ordered')
                <ol class="border-t border-secondary-200">
                    @foreach($section['items'] as $i => $item)
                        <li class="flex items-baseline gap-4 py-4 border-b border-secondary-200">
                            <span class="font-display text-secondary-400 text-sm shrink-0 w-8">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-secondary-700 text-sm leading-relaxed">{{ $item }}</span>
                        </li>
                    @endforeach
                </ol>
            @else
                <ul class="border-t border-secondary-200">
                    @foreach($section['items'] as $item)
                        <li class="flex items-baseline gap-4 py-4 border-b border-secondary-200">
                            <span class="text-secondary-400 shrink-0">·</span>
                            <span class="text-secondary-700 text-sm leading-relaxed">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    @endforeach

    <div class="border-t border-secondary-200 pt-10 text-center">
        <p class="text-sm text-secondary-500 mb-5">準備ができたら、登録を始めましょう。</p>
        <a href="{{ route('register', ['role' => 'painter']) }}" class="btn-museum-dark inline-flex">
            画家として登録
        </a>
    </div>
</div>
@endsection
