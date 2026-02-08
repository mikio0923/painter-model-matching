@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    {{-- パンくず --}}
    <nav class="text-sm text-gray-500 mb-4">
        <a href="{{ url('/') }}" class="hover:text-gray-700">ホーム</a>
        <span class="mx-1">/</span>
        <span class="text-gray-800">GUIDE LINE</span>
        <span class="mx-1">/</span>
        <span class="text-gray-800">ご利用ガイドライン</span>
    </nav>

    <h1 class="text-3xl font-bold mb-6">ご利用ガイドライン</h1>

    <p class="text-gray-700 mb-10">
        ModelTownをご利用いただく皆さまには、以下のガイドラインに従ってご利用いただきます。違反が確認された場合は、<a href="{{ route('contact.create') }}" class="text-blue-600 hover:underline">お問い合わせ</a>よりご連絡ください。
    </p>

    <div class="space-y-5">
        @php
            $items = [
                [
                    'title' => '誠実に利用する',
                    'body' => true,
                    'paragraphs' => [
                        '相手を尊重し、何事も正直に、誠実な心で、ModelTownをご利用ください。',
                        'ModelTownでは、皆さまに少しでも安心してサービスをご利用いただくため、本人確認サービスを提供しております。信頼の一つの指標になりますので、クライアント(依頼主)の方もモデルの方も、本人確認サービスを是非ご利用ください。',
                    ],
                ],
                [
                    'title' => '条件・約束を守る',
                    'body' => true,
                    'paragraphs' => [
                        '仕事の内容や条件など、相手と事前に話し合って決めたこと（合意事項）を必ず守りましょう。',
                        '※　撮影の内容、日時、場所、拘束時間、報酬、など',
                    ],
                ],
                [
                    'title' => 'ドタキャンしない',
                    'body' => true,
                    'paragraphs' => [
                        'いったん決定した仕事を、簡単にキャンセルしてはいけません。やむを得ずキャンセルする場合は、相手に連絡し、承諾を得てください。',
                        '仕事のキャンセルによって、様々な損害が生じることがあります。キャンセルにかかる費用の清算(撮影場所のレンタル料金やカメラマンへの報酬、他のキャンセル費用、移動費用など)については、双方で折り合いをつけてください。',
                    ],
                ],
                [
                    'title' => 'ジョブは詳細まで記載する',
                    'body' => true,
                    'paragraphs' => [
                        'クライアント(依頼主)は、ジョブの内容を丁寧に書きましょう。',
                        'モデルが安心してエントリー(応募)できるように、下記の事項は必ず記載してください。',
                    ],
                    'bullets' => [
                        '撮影内容',
                        '募集対象(年齢層、性別など)',
                        '撮影日時、場所',
                        '報酬の金額、支払い方法',
                        '撮影時間(開始～終了時間)',
                        '撮影時の衣装、髪型やメイク',
                        '撮影状況(スタッフの人数など)',
                        '撮影環境(屋外、室内、スタジオなど)',
                        '撮影データの用途',
                        'その他、注意点など',
                    ],
                    'paragraphs_after' => [
                        '以下のいずれかの条件と一致するジョブの掲載はお断りしており、また、モデルタウンにおいて同様の依頼をすることも禁止しています。',
                        '・アダルト要素（フェチ系を含む）のあるもの',
                        '・撮影時に危険が伴うと思われるもの',
                        '・個人による、下着や水着のほか、過度な露出のある撮影',
                        '・ホテルなど、第三者の目が届きにくい場所での撮影',
                        '・その他、事務局が不適切と判断するもの',
                    ],
                ],
                [
                    'title' => 'エントリーに責任を持つ',
                    'body' => true,
                    'paragraphs' => [
                        'モデルは、ジョブの内容をしっかりと確認してからエントリー(応募)しましょう。',
                        'クライアント(依頼主)は、エントリーを受信後に厳正な審査を重ねてオファーしていますので、下記の事項を守ってエントリーしてください。',
                    ],
                    'bullets' => [
                        'ジョブの内容をしっかり確認する',
                        '記載されている日程や場所を確認する',
                        '希望条件はエントリー時に伝える(可能な範囲で)',
                        '応募後の数日はマイページを定期的にチェックする',
                        'オファーが来たら必ず返信する',
                    ],
                    'paragraphs_after' => [
                        '※ジョブの内容をよく確認せずにエントリーを繰り返す行為は、注意・警告(アカウント停止)の対象になります。',
                    ],
                ],
                [
                    'title' => '事前に全部、伝える確認する',
                    'body' => true,
                    'paragraphs' => [
                        '仕事に関する様々な事項において、認識の相違が生まれないよう、丁寧に正確にやりとりしましょう。',
                        'クライアント(依頼主)もモデルも、分からないこと知りたいことがあれば、しっかりと確認しましょう。',
                        'やりとりの中で、自分が理解したことを、自分の言葉でも復唱するようにすると、認識の相違は生まれにくくなります。',
                        '特に、長期継続での依頼となる場合、クライアントが衣装を用意する場合、撮影に伴い身体的な接触がある場合など、必ずお互いの認識を共有してください。',
                    ],
                ],
                [
                    'title' => '報酬は、直接支払う(直接受け取る)',
                    'body' => true,
                    'paragraphs' => [
                        'ModelTown(当サイト)では、モデルに対する報酬の支払い手続きは、出来ません。',
                        'クライアント(依頼主)とモデルの間で、報酬の金額や支払い方法などを決め、領収書が必要な場合にはモデルから受け取ってください。',
                    ],
                    'link' => ['領収書のテンプレート(PDF)', '#', 'こちらの %s を印刷し、活用いただくこともできます。'],
                ],
                [
                    'title' => '必要に応じて契約書を交わす',
                    'body' => true,
                    'paragraphs' => [
                        'モデルおよびクライアント(依頼主)は、必要に応じて契約書を交わしましょう。',
                        'たとえば、肖像写真の使用期限や使用用途などを限定する、何らかの条件がある、制約が発生する場合などには、契約書を交わしたうえで取引してください。',
                    ],
                    'link' => ['契約書のテンプレート(PDF)', '#', 'こちらの %s を印刷し、ご活用いただくこともできます。'],
                ],
                [
                    'title' => '当事者同士でやりとりする',
                    'body' => true,
                    'paragraphs' => [
                        '原則として、代理で仕事を依頼したり、受け付けることを禁止しています。必ず、当事者同士でやりとりしてください。',
                        '※20歳未満のモデルは、保護者によるやりとりが必要です。',
                    ],
                ],
            ];
        @endphp

        @foreach($items as $index => $item)
            <div class="border border-gray-200 rounded-lg bg-white px-5 py-4 sm:px-6 sm:py-4 shadow-sm">
                <div class="flex gap-4">
                    {{-- 番号円 --}}
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-black text-white flex items-center justify-center text-xl font-bold">
                        {{ $index }}
                    </div>
                    {{-- コンテンツ --}}
                    <div class="flex-1 min-w-0">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">{{ $item['title'] }}</h2>
                    <div class="text-gray-700 space-y-3 text-[15px] leading-relaxed">
                        @foreach($item['paragraphs'] ?? [] as $p)
                            <p>{{ $p }}</p>
                        @endforeach
                        @if(!empty($item['bullets']))
                            <ul class="list-disc pl-6 space-y-1">
                                @foreach($item['bullets'] as $b)
                                    <li>{{ $b }}</li>
                                @endforeach
                            </ul>
                        @endif
                        @if(!empty($item['paragraphs_after']))
                            @foreach($item['paragraphs_after'] as $p)
                                <p>{{ $p }}</p>
                            @endforeach
                        @endif
                        @if(!empty($item['link']))
                            @php
                                $linkText = $item['link'][0];
                                $linkUrl = $item['link'][1];
                                $linkTemplate = $item['link'][2] ?? 'こちらの %s をご利用ください。';
                            @endphp
                            <p>{!! sprintf($linkTemplate, '<a href="' . e($linkUrl) . '" class="text-blue-600 hover:underline">' . e($linkText) . '</a>') !!}</p>
                        @endif
                    </div>
                </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
