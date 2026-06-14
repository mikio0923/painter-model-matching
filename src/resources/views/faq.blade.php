@extends('layouts.app')

@section('title', 'よくあるご質問')
@section('description', 'Palette に関するよくあるご質問と回答をまとめています。登録方法・報酬・応募の流れなど。')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <p class="page-header-subtitle">Q & A</p>
        <h1 class="page-header-title mt-2">よくあるご質問</h1>
        <p class="text-secondary-500 text-sm mt-3">ご利用に関してよくいただく質問と回答をまとめています。</p>
    </div>
</div>

<div class="page-narrow">
    @php
        $faqs = [
            ['q' => '登録は無料ですか？',                          'a' => 'はい、登録は無料です。モデル・画家ともに無料でアカウントを作成できます。'],
            ['q' => 'モデルとして登録するにはどうすればいいですか？', 'a' => '新規登録ページで「モデル」を選択してご登録ください。その後、プロフィールを作成いただくと依頼を受け付けられるようになります。'],
            ['q' => '画家として登録するにはどうすればいいですか？',  'a' => '新規登録ページで「画家」を選択してご登録ください。プロフィール作成後、依頼の作成が可能になります。'],
            ['q' => '報酬はどのように決まりますか？',                'a' => '依頼を作成する際に画家側で報酬を設定します。マッチング成立後は当事者間で詳細を調整いただきます。'],
            ['q' => '応募した後の流れは？',                         'a' => '画家が応募を確認し、承認または却下を判断します。承認された場合はメッセージ機能で連絡を取り合えます。'],
            ['q' => 'プロフィール画像は必須ですか？',                'a' => '必須ではありませんが、プロフィール画像があると応募・依頼の機会が増える傾向があります。'],
            ['q' => 'その他のお問い合わせは？',                     'a' => 'お問い合わせフォームからご連絡ください。できるだけ早く回答いたします。'],
        ];
    @endphp

    <div class="border-t border-secondary-200">
        @foreach($faqs as $i => $faq)
            <details class="group border-b border-secondary-200 px-1">
                <summary class="flex items-start justify-between gap-4 py-5 cursor-pointer list-none hover:bg-secondary-50 transition-colors duration-200 px-4 -mx-4">
                    <div class="flex items-baseline gap-3 min-w-0 flex-1">
                        <span class="font-display text-secondary-400 text-sm shrink-0">Q.</span>
                        <span class="text-secondary-900 font-medium leading-relaxed">{{ $faq['q'] }}</span>
                    </div>
                    <svg class="w-4 h-4 text-secondary-400 group-open:rotate-180 transition-transform duration-300 flex-shrink-0 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="pb-6 px-4 flex items-start gap-3">
                    <span class="font-display text-secondary-400 text-sm shrink-0">A.</span>
                    <p class="text-sm text-secondary-700 leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            </details>
        @endforeach
    </div>

    <div class="mt-12 text-center">
        <p class="text-sm text-secondary-500 mb-4">解決しない場合はお気軽にお問い合わせください。</p>
        <a href="{{ route('contact.create') }}" class="btn-museum-dark inline-flex">
            お問い合わせフォーム
        </a>
    </div>
</div>
@endsection
