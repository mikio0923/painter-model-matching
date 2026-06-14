@extends('layouts.app')

@section('title', 'ご利用ガイドライン')
@section('description', 'Palette のご利用にあたってのガイドライン。誠実な利用・条件遵守・安全な取引のために。')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ url('/') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Home
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">ご利用ガイドライン</span>
        </div>
        <p class="page-header-subtitle">Guidelines</p>
        <h1 class="page-header-title mt-2">ご利用ガイドライン</h1>
        <p class="text-secondary-500 text-sm mt-3">{{ config('app.name', 'Palette') }} を安心してご利用いただくための共通ルールです。</p>
    </div>
</div>

<div class="page-narrow space-y-10">

    <p class="text-sm text-secondary-700 leading-relaxed">
        {{ config('app.name', 'Palette') }} をご利用いただく皆さまには、以下のガイドラインに従ってご利用いただきます。
        違反が確認された場合は、<a href="{{ route('contact.create') }}" class="link-primary">お問い合わせ</a>よりご連絡ください。
    </p>

    @php
        $appName = config('app.name', 'Palette');
        $items = [
            [
                'title' => '誠実に利用する',
                'paragraphs' => [
                    "相手を尊重し、何事も正直に、誠実な心で {$appName} をご利用ください。",
                    "{$appName} はマッチングの場を提供するサービスです。{$appName} 上での身分確認・実在確認は行っておりません。プロフィール画像やポートフォリオなどを参考に、利用者ご自身の判断と責任でやりとりを進めてください。",
                ],
            ],
            [
                'title' => '条件・約束を守る',
                'paragraphs' => [
                    '仕事の内容や条件など、相手と事前に話し合って決めたこと（合意事項）を必ず守りましょう。',
                    '※ 撮影の内容、日時、場所、拘束時間、報酬 など',
                ],
            ],
            [
                'title' => '直前のキャンセルを避ける',
                'paragraphs' => [
                    'いったん決定したお仕事を簡単にキャンセルしてはいけません。やむを得ずキャンセルする場合は、相手に連絡し、承諾を得てください。',
                    'キャンセルにより様々な損害が生じる場合があります（撮影場所のレンタル料金・カメラマンへの報酬・移動費用など）。費用の清算については双方で折り合いをつけてください。',
                ],
            ],
            [
                'title' => '依頼内容は丁寧に記載する',
                'paragraphs' => [
                    '画家は、依頼の内容を丁寧に書きましょう。モデルが安心してエントリーできるよう、以下の事項は必ず記載してください。',
                ],
                'bullets' => [
                    '撮影内容', '募集対象（年齢層・性別など）', '撮影日時・場所',
                    '報酬の金額・支払い方法', '撮影時間（開始〜終了時間）',
                    '撮影時の衣装・髪型やメイク', '撮影状況（スタッフの人数など）',
                    '撮影環境（屋外・室内・スタジオなど）', '撮影データの用途',
                    'その他、注意点など',
                ],
                'paragraphs_after' => [
                    '以下のいずれかに該当する依頼の掲載はお断りしており、同様の依頼を行うことも禁止しています。',
                    '・アダルト要素（フェチ系を含む）のあるもの',
                    '・撮影時に危険が伴うと思われるもの',
                    '・個人による、下着や水着のほか過度な露出のある撮影',
                    '・ホテルなど第三者の目が届きにくい場所での撮影',
                    '・その他、運営が不適切と判断するもの',
                ],
            ],
            [
                'title' => 'エントリーに責任を持つ',
                'paragraphs' => [
                    'モデルは、依頼の内容をしっかりと確認してからエントリー（応募）しましょう。',
                    '画家はエントリーを受信後に審査を重ねてオファーしていますので、以下の事項を守ってご応募ください。',
                ],
                'bullets' => [
                    '依頼の内容をしっかり確認する',
                    '記載されている日程・場所を確認する',
                    '希望条件はエントリー時に伝える（可能な範囲で）',
                    '応募後の数日はマイページを定期的にチェックする',
                    'オファーが来たら必ず返信する',
                ],
                'paragraphs_after' => [
                    '※ 依頼内容をよく確認せずにエントリーを繰り返す行為は、注意・警告（アカウント停止）の対象になります。',
                ],
            ],
            [
                'title' => '事前にすべて伝える・確認する',
                'paragraphs' => [
                    'お仕事に関するさまざまな事項において、認識の相違が生まれないよう、丁寧かつ正確にやりとりしましょう。',
                    '画家もモデルも、分からないことや知りたいことがあればしっかり確認しましょう。',
                    'やりとりの中で、理解したことを自分の言葉で復唱すると、認識の相違が生まれにくくなります。',
                    '特に、長期の継続依頼・画家が衣装を用意する場合・撮影に伴う身体的な接触がある場合などは、必ずお互いの認識を共有してください。',
                ],
            ],
            [
                'title' => '報酬は、直接支払う・直接受け取る',
                'paragraphs' => [
                    "{$appName} では、モデルへの報酬の支払い手続きは行いません。",
                    '画家とモデルの間で、報酬の金額・支払い方法などを決め、領収書が必要な場合にはモデルから受け取ってください。',
                ],
            ],
            [
                'title' => '必要に応じて契約書を交わす',
                'paragraphs' => [
                    'モデルおよび画家は、必要に応じて契約書を交わしましょう。',
                    '肖像写真の使用期限・使用用途を限定する場合や、なんらかの条件・制約が発生する場合は、契約書を交わしたうえで取引してください。',
                ],
            ],
            [
                'title' => '当事者同士でやりとりする',
                'paragraphs' => [
                    '原則として、代理での依頼・受付は禁止しています。必ず当事者同士でやりとりしてください。',
                    '※ 20歳未満のモデルは、保護者によるやりとりが必要です。',
                ],
            ],
        ];
    @endphp

    <div class="space-y-6">
        @foreach($items as $index => $item)
            <article class="border border-secondary-200 bg-canvas-50 px-5 sm:px-6 py-6">
                <div class="flex gap-5">
                    <div class="shrink-0">
                        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">No.</p>
                        <p class="font-display text-2xl text-secondary-900">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="flex-1 min-w-0 pt-1">
                        <h2 class="font-display text-lg sm:text-xl font-semibold text-secondary-900 mb-3">{{ $item['title'] }}</h2>
                        <div class="text-secondary-700 text-sm leading-relaxed space-y-3">
                            @foreach($item['paragraphs'] ?? [] as $p)
                                <p>{{ $p }}</p>
                            @endforeach

                            @if(!empty($item['bullets']))
                                <ul class="list-disc pl-5 space-y-1 marker:text-secondary-400">
                                    @foreach($item['bullets'] as $b)
                                        <li>{{ $b }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            @foreach($item['paragraphs_after'] ?? [] as $p)
                                <p>{{ $p }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div class="border-t border-secondary-200 pt-8 text-center">
        <p class="text-sm text-secondary-500 mb-4">違反が見受けられる場合は遠慮なくご連絡ください。</p>
        <a href="{{ route('contact.create') }}" class="btn-museum-outline inline-flex">
            お問い合わせ
        </a>
    </div>

</div>
@endsection
