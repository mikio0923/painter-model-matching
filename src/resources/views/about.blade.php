@extends('layouts.app')

@section('title', 'サービスについて')
@section('description', '画家とモデルをつなぐマッチングプラットフォーム Palette のサービス概要・使い方をご紹介します。')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <div class="art-bg-stage">
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/great-wave.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/starry-night.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/pearl-earring.jpg') }}');"></div>
        <div class="art-bg-veil"></div>
    </div>
    <div class="page-header-inner">
        <p class="page-header-subtitle">About</p>
        <h1 class="page-header-title mt-2">サービスについて</h1>
        <p class="text-secondary-500 text-sm mt-3">{{ config('app.name', 'Palette') }} は、画家とモデルをつなぐマッチングプラットフォームです。</p>
    </div>
</div>

<div class="page-narrow space-y-12">

    <section>
        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-2">Concept</p>
        <h2 class="font-display text-2xl font-semibold text-secondary-900 mb-5">画家とモデルが出会う場所</h2>
        <p class="text-secondary-700 leading-relaxed">
            {{ config('app.name', 'Palette') }} は「モデルになりたい人」と「モデルを依頼したい画家」が静かに出会うための場です。<br>
            ポートレート・人物画の制作を中心に、創作活動を支える信頼できるマッチングを目指しています。
        </p>
    </section>

    <section>
        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-2">How to Use</p>
        <h2 class="font-display text-2xl font-semibold text-secondary-900 mb-6">使い方</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 border-t border-l border-secondary-200">
            <div class="border-r border-b border-secondary-200 px-6 py-7">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-2">For Model</p>
                <h3 class="font-display text-lg font-semibold text-secondary-900 mb-3">モデルの方へ</h3>
                <p class="text-sm text-secondary-700 leading-relaxed">
                    プロフィールを作成して公開すれば、画家からの依頼が届きます。依頼一覧からご自身で気になる案件に応募することも可能です。
                </p>
            </div>
            <div class="border-r border-b border-secondary-200 px-6 py-7">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-2">For Painter</p>
                <h3 class="font-display text-lg font-semibold text-secondary-900 mb-3">画家の方へ</h3>
                <p class="text-sm text-secondary-700 leading-relaxed">
                    モデル一覧から条件に合う方を探し、依頼を作成できます。応募してきたモデルから選び、メッセージで詳細を進められます。
                </p>
            </div>
        </div>
    </section>

    <section class="border-t border-secondary-200 pt-10">
        <p class="text-sm text-secondary-600 mb-5">ご利用にあたっては、ガイドライン・利用規約をご確認ください。</p>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('guideline') }}" class="btn-museum-outline">
                ガイドライン
            </a>
            <a href="{{ route('terms') }}" class="btn-museum-outline">
                利用規約
            </a>
            <a href="{{ route('contact.create') }}" class="btn-museum-outline">
                お問い合わせ
            </a>
        </div>
    </section>

</div>
@endsection
