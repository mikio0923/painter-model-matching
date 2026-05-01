@extends('layouts.app')

@section('content')
<div class="page-narrow">
    {{-- パンくず --}}
    <nav class="text-sm text-secondary-500 mb-4">
        <a href="{{ url('/') }}" class="hover:text-secondary-700">ホーム</a>
        <span class="mx-1">/</span>
        <span class="text-secondary-800">利用規約</span>
    </nav>

    <section class="section-panel">
        <div class="section-panel-inner">
            <div class="mb-8">
                <p class="section-title-en mb-3">Terms of Service</p>
                <h1 class="section-title">利用規約</h1>
                <p class="text-secondary-500 text-sm mt-3">
                    最終更新日: 2026年4月30日<br>
                    <span class="text-warning-600">※ 本規約はドラフト版です。正式公開前に法務確認が必要です。</span>
                </p>
            </div>

            <div class="prose prose-sm max-w-none text-secondary-700 leading-relaxed space-y-6">
                <p>
                    本利用規約（以下「本規約」といいます。）は、{{ config('app.name', 'Palette') }}（以下「当サービス」といいます。）の運営者（以下「当社」といいます。）が提供する画家とモデルのマッチングサービスの利用条件を定めるものです。利用者の皆様（以下「利用者」といいます。）には、本規約に従って当サービスをご利用いただきます。
                </p>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第1条（適用）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>本規約は、利用者と当社との間の当サービスの利用に関わる一切の関係に適用されるものとします。</li>
                        <li>当社が当サービス上で掲載する個別規定は、本規約の一部を構成するものとします。</li>
                        <li>本規約の規定が個別規定の規定と矛盾する場合には、個別規定において特段の定めなき限り、個別規定の規定が優先されるものとします。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第2条（定義）</h2>
                    <p class="text-sm">本規約において使用する用語の定義は次のとおりとします。</p>
                    <ul class="list-disc list-inside space-y-1 text-sm mt-2">
                        <li><strong>画家</strong>：絵画・イラスト等の制作目的でモデルを募集する利用者</li>
                        <li><strong>モデル</strong>：画家からの依頼を受けてモデル業務を行う利用者</li>
                        <li><strong>依頼</strong>：画家がモデルに対して制作協力を求める案件</li>
                        <li><strong>応募</strong>：モデルが依頼に対して参加意思を示す行為</li>
                        <li><strong>マッチング</strong>：依頼と応募が成立し当事者間で合意に至ること</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第3条（利用登録）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>利用希望者は、本規約に同意の上、当社の定める方法によって利用登録を申請するものとします。</li>
                        <li>当社は、利用登録の申請者に以下の事由があると判断した場合、利用登録の申請を承認しないことがあります。
                            <ul class="list-disc list-inside ml-4 mt-1 space-y-0.5">
                                <li>登録事項に虚偽、誤記、または記載漏れがあった場合</li>
                                <li>反社会的勢力等の関係者である場合</li>
                                <li>過去に当サービスにおいて違反行為を行い退会処分を受けた場合</li>
                                <li>その他、当社が利用登録を相当でないと判断した場合</li>
                            </ul>
                        </li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第4条（年齢制限・本人確認）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>当サービスは<strong>18歳以上の方</strong>のみご利用いただけます。18歳未満の方の登録は固くお断りします。</li>
                        <li>モデルとして活動を希望する利用者は、当社所定の方法による本人確認手続きを行うことができます。本人確認の完了は、画家からの信頼を得るための重要な指標となります。</li>
                        <li>本人確認において虚偽の書類を提出した場合、当社はアカウントを直ちに停止または削除することができます。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第5条（禁止事項）</h2>
                    <p class="text-sm">利用者は、当サービスの利用にあたり、以下の行為をしてはなりません。</p>
                    <ul class="list-disc list-inside space-y-1 text-sm mt-2">
                        <li>法令または公序良俗に違反する行為</li>
                        <li>犯罪行為に関連する行為</li>
                        <li>当サービスの内容、または当サービスに含まれる著作権、商標権その他の知的財産権を侵害する行為</li>
                        <li>当社、他の利用者、またはその他第三者のサーバーまたはネットワークの機能を破壊したり、妨害したりする行為</li>
                        <li>当サービスによって得られた情報を商業的に利用する行為</li>
                        <li>当サービスの運営を妨害するおそれのある行為</li>
                        <li>不正アクセスをし、またはこれを試みる行為</li>
                        <li>他の利用者に関する個人情報等を収集または蓄積する行為</li>
                        <li>不正な目的を持って当サービスを利用する行為</li>
                        <li>当サービスの他の利用者または他の第三者に不利益、損害、不快感を与える行為</li>
                        <li>他の利用者に成りすます行為</li>
                        <li>当社が許諾しない当サービス上での宣伝、広告、勧誘、または営業行為</li>
                        <li>面識のない異性との出会いを目的とした行為</li>
                        <li>公序良俗に反する撮影、または性的・暴力的なコンテンツの制作に関する依頼または応募</li>
                        <li>その他、当社が不適切と判断する行為</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第6条（マッチング・取引）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>当サービスは、画家とモデルのマッチングの「場」を提供するものであり、当事者間で行われる取引（撮影・モデリング等）の当事者ではありません。</li>
                        <li>マッチング後の具体的な撮影内容、日時、場所、報酬等の条件は、当事者間の合意により決定するものとします。</li>
                        <li>マッチング成立後にトラブルが発生した場合、当事者間で誠実に解決に努めるものとし、当社は原則として当該トラブルに関与しません。ただし、当社が必要と認めた場合、一定の協力を行うことがあります。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第7条（手数料・支払）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>当サービスの基本機能は無料でご利用いただけます。</li>
                        <li>当社は、有料オプションまたはマッチング成立時のプラットフォーム手数料を徴収する場合があります。料金体系は当サービス上で別途公表します。</li>
                        <li>当社が決済機能を提供する場合、利用者は当社が指定する決済代行サービスの利用規約にも同意するものとします。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第8条（コンテンツ・著作権）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>当サービス上で利用者が投稿または提供する文章、画像等のコンテンツに関する著作権その他の権利は、当該利用者本人または正当な権利者に帰属します。</li>
                        <li>利用者は、投稿コンテンツについて、当社が当サービスの提供・宣伝のために必要な範囲で無償で利用することを許諾します。</li>
                        <li>マッチング後の撮影成果物（写真・絵画等）の著作権・利用範囲については、当事者間で別途取り決めるものとします。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第9条（利用制限・登録抹消）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>当社は、利用者が以下のいずれかに該当する場合、事前の通知なく、当該利用者の当サービスの全部または一部の利用を制限し、または利用者としての登録を抹消することができます。
                            <ul class="list-disc list-inside ml-4 mt-1 space-y-0.5">
                                <li>本規約のいずれかの条項に違反した場合</li>
                                <li>登録事項に虚偽の事実があることが判明した場合</li>
                                <li>料金等の支払債務の不履行があった場合</li>
                                <li>当社からの連絡に対し、相当期間返答がない場合</li>
                                <li>当サービスについて、最終の利用から一定期間利用がない場合</li>
                                <li>その他、当社が当サービスの利用を適当でないと判断した場合</li>
                            </ul>
                        </li>
                        <li>当社は本条に基づき当社が行った行為により利用者に生じた損害について、一切の責任を負いません。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第10条（退会）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>利用者は、当社の定める退会手続により、当サービスから退会できます。</li>
                        <li>退会後、{{ \App\Services\AccountDeletionService::GRACE_PERIOD_DAYS }}日間は同一アカウントでの復帰が可能ですが、それ以降は完全に削除され復帰できません。</li>
                        <li>進行中の依頼または承認済みの応募がある場合、退会できないことがあります。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第11条（保証の否認および免責事項）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>当社は、当サービスに事実上または法律上の瑕疵（安全性、信頼性、正確性、完全性、有効性、特定の目的への適合性、セキュリティなどに関する欠陥、エラーやバグ、権利侵害などを含みます。）がないことを明示的にも黙示的にも保証しておりません。</li>
                        <li>当社は、当サービスに起因して利用者に生じたあらゆる損害について、当社の故意または重過失による場合を除き、一切の責任を負いません。</li>
                        <li>当社は、当サービスに関して、利用者と他の利用者または第三者との間において生じた取引、連絡または紛争等について一切責任を負いません。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第12条（サービス内容の変更等）</h2>
                    <p class="text-sm">当社は、利用者への事前の告知をもって、当サービスの内容を変更、追加または廃止することがあり、利用者はこれを承諾するものとします。</p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第13条（利用規約の変更）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>当社は必要と判断した場合には、利用者に通知することなくいつでも本規約を変更することができるものとします。</li>
                        <li>本規約の変更後、当サービスの利用を開始した場合には、当該利用者は変更後の規約に同意したものとみなします。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第14条（個人情報の取扱い）</h2>
                    <p class="text-sm">当サービスの利用によって取得する個人情報については、当社「<a href="{{ route('privacy') }}" class="link-primary">プライバシーポリシー</a>」に従い適切に取り扱うものとします。</p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第15条（準拠法・裁判管轄）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>本規約の解釈にあたっては、日本法を準拠法とします。</li>
                        <li>当サービスに関して紛争が生じた場合には、当社の本店所在地を管轄する裁判所を専属的合意管轄とします。</li>
                    </ol>
                </section>

                <p class="text-right text-sm text-secondary-500 mt-12">以上</p>
            </div>
        </div>
    </section>
</div>
@endsection
