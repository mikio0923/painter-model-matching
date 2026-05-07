@extends('layouts.app')

@section('title', 'モデルになるガイド')
@section('description', 'モデル登録から依頼受諾までの流れ、プロフィール作成のポイントをご紹介します。')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <div class="art-bg-stage">
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/pearl-earring.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/great-wave.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/starry-night.jpg') }}');"></div>
        <div class="art-bg-veil"></div>
    </div>
    <div class="page-header-inner">
        <p class="page-header-subtitle">For Model</p>
        <h1 class="page-header-title mt-2">モデルになるガイド</h1>
        <p class="text-secondary-500 text-sm mt-3">登録からマッチング、依頼の受諾までの流れをご紹介します。</p>
    </div>
</div>

<div class="page-narrow space-y-12">

    @php
        $sections = [
            ['label' => 'Registration', 'title' => 'モデル登録の流れ', 'type' => 'ordered', 'items' => [
                '新規登録でモデルアカウントを作成',
                'プロフィール情報を入力（表示名、年齢、性別、都道府県など）',
                'プロフィール画像をアップロード',
                '報酬目安を設定',
                'プロフィールを公開',
            ]],
            ['label' => 'Profile Tips', 'title' => 'プロフィール作成のポイント', 'type' => 'unordered', 'items' => [
                'プロフィール画像は複数枚アップロード可能です',
                '自己紹介・経歴を丁寧に書くと、依頼が来やすくなります',
                'スタイルタグを設定すると、画家からの検索でヒットしやすくなります',
                '報酬目安を設定することで、条件に合う依頼が届きやすくなります',
            ]],
            ['label' => 'Workflow', 'title' => '依頼を受ける流れ', 'type' => 'ordered', 'items' => [
                '画家から依頼またはオファーが届きます',
                '依頼内容を確認して応募します',
                '画家が応募を承認すると、メッセージのやり取りが始まります',
                '日程・場所・条件を詳細を確認し、撮影に臨みます',
                '完了後、お互いにレビューを投稿できます',
            ]],
            ['label' => 'Notice', 'title' => '注意事項', 'type' => 'unordered', 'items' => [
                'プロフィール情報は正確に入力してください',
                '応募後は誠実に対応してください',
                '報酬や条件は事前に必ず確認してください',
                '安全のため、初回は公共の場所での撮影を推奨します',
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
        <a href="{{ route('register', ['role' => 'model']) }}" class="btn-museum-dark inline-flex">
            モデルとして登録
        </a>
    </div>
</div>
@endsection
