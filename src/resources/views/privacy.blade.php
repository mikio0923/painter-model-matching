@extends('layouts.app')

@section('content')
<div class="page-narrow">
    {{-- パンくず --}}
    <nav class="text-sm text-secondary-500 mb-4">
        <a href="{{ url('/') }}" class="hover:text-secondary-700">ホーム</a>
        <span class="mx-1">/</span>
        <span class="text-secondary-800">プライバシーポリシー</span>
    </nav>

    <section class="section-panel">
        <div class="section-panel-inner">
            <div class="mb-8">
                <p class="section-title-en mb-3">Privacy Policy</p>
                <h1 class="section-title">プライバシーポリシー</h1>
                <p class="text-secondary-500 text-sm mt-3">
                    最終更新日: 2026年4月30日<br>
                    <span class="text-warning-600">※ 本ポリシーはドラフト版です。正式公開前に法務確認が必要です。</span>
                </p>
            </div>

            <div class="prose prose-sm max-w-none text-secondary-700 leading-relaxed space-y-6">
                <p>
                    {{ config('app.name', 'Palette') }}（以下「当サービス」といいます。）は、利用者の個人情報の重要性を認識し、個人情報の保護に関する法律（以下「個人情報保護法」といいます。）を遵守するとともに、以下のプライバシーポリシー（以下「本ポリシー」といいます。）に従って、個人情報の適切な取扱いを行います。
                </p>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第1条（個人情報の定義）</h2>
                    <p class="text-sm">
                        本ポリシーにおいて「個人情報」とは、個人情報保護法第2条第1項により定義された個人情報を指し、生存する個人に関する情報であって、当該情報に含まれる氏名、生年月日、住所、電話番号、メールアドレス、その他の記述等により特定の個人を識別できるもの（他の情報と容易に照合することができ、それにより特定の個人を識別することができることとなるものを含みます。）を指します。
                    </p>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第2条（取得する個人情報）</h2>
                    <p class="text-sm">当サービスは、以下の個人情報を取得します。</p>
                    <ul class="list-disc list-inside space-y-1 text-sm mt-2">
                        <li>氏名・表示名</li>
                        <li>メールアドレス</li>
                        <li>パスワード（暗号化して保存）</li>
                        <li>生年月日（モデル登録時、年齢確認のため）</li>
                        <li>性別（モデル登録時）</li>
                        <li>電話番号（モデル登録時、任意）</li>
                        <li>住所情報（郵便番号・都道府県・市区町村・番地等）</li>
                        <li>プロフィール画像、ポートフォリオ画像</li>
                        <li>本人確認書類の画像（本人確認申請時のみ）</li>
                        <li>身体情報（身長、体重等。モデル登録時、任意）</li>
                        <li>当サービスの利用履歴・操作ログ</li>
                        <li>IPアドレス、Cookie情報、ブラウザ情報</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第3条（個人情報の利用目的）</h2>
                    <p class="text-sm">当サービスは、取得した個人情報を以下の目的で利用します。</p>
                    <ul class="list-disc list-inside space-y-1 text-sm mt-2">
                        <li>当サービスの提供・運営のため</li>
                        <li>利用者の本人確認・認証のため</li>
                        <li>利用者からのお問い合わせに対応するため</li>
                        <li>利用者に対する重要な通知（規約変更等）の連絡のため</li>
                        <li>マッチング機能の提供（画家とモデルの相互紹介）のため</li>
                        <li>当サービスの改善・新機能開発のための統計分析（個人を特定しない形式に限る）</li>
                        <li>利用規約に違反した利用者の特定および当該利用への対応のため</li>
                        <li>有料サービスの料金請求・決済処理のため</li>
                        <li>プロモーション・キャンペーン情報の配信のため（オプトアウト可能）</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第4条（個人情報の第三者提供）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>当社は、次に掲げる場合を除いて、あらかじめ利用者の同意を得ることなく、第三者に個人情報を提供することはありません。
                            <ul class="list-disc list-inside ml-4 mt-1 space-y-0.5">
                                <li>法令に基づく場合</li>
                                <li>人の生命、身体または財産の保護のために必要がある場合であって、本人の同意を得ることが困難であるとき</li>
                                <li>公衆衛生の向上または児童の健全な育成の推進のために特に必要がある場合であって、本人の同意を得ることが困難であるとき</li>
                                <li>国の機関もしくは地方公共団体またはその委託を受けた者が法令の定める事務を遂行することに対して協力する必要がある場合であって、本人の同意を得ることにより当該事務の遂行に支障を及ぼすおそれがあるとき</li>
                            </ul>
                        </li>
                        <li>マッチング機能においては、画家・モデル相互間で氏名・プロフィール情報・連絡情報の一部が表示されることがありますが、これは利用者本人の同意の上で公開された情報の提供に該当します。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第5条（業務委託先への提供）</h2>
                    <p class="text-sm">当社は、当サービスの運営に必要な範囲で、以下の業務委託先に個人情報を提供することがあります。委託先には、適切な監督を行います。</p>
                    <ul class="list-disc list-inside space-y-1 text-sm mt-2">
                        <li>クラウドインフラ提供事業者（AWS等）</li>
                        <li>メール配信サービス提供事業者</li>
                        <li>決済代行サービス提供事業者（Stripe等、決済機能利用時のみ）</li>
                        <li>本人確認業務委託事業者（外部eKYCサービス導入時のみ）</li>
                        <li>カスタマーサポート業務受託事業者</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第6条（個人情報の保管・保護）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>当社は、個人情報の漏えい、滅失または毀損を防ぐため、適切なセキュリティ対策を講じます。</li>
                        <li>パスワードは暗号化（ハッシュ化）して保存し、当社管理者であっても復元できません。</li>
                        <li>本人確認書類の画像は非公開ストレージに保存し、当社管理者のみが閲覧できます。承認後6ヶ月で自動削除されます。</li>
                        <li>通信は SSL/TLS による暗号化を行います。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第7条（保有期間と削除）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>当社は、利用目的の達成に必要な範囲内で個人情報を保有します。</li>
                        <li>退会後{{ \App\Services\AccountDeletionService::GRACE_PERIOD_DAYS }}日間は復帰可能とするためアカウント情報を保持し、それ以降は氏名・連絡先等を匿名化します。</li>
                        <li>過去のメッセージ・レビュー本文は、相手方の利便性のため、退会後も匿名化された形で保持される場合があります。</li>
                        <li>決済関連情報は、法令（電子帳簿保存法）に基づき必要な期間保存します。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第8条（個人情報の開示・訂正・削除請求）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>利用者は、当社の保有する自己の個人情報について、開示・訂正・追加・削除・利用停止を求めることができます。</li>
                        <li>請求の方法は、<a href="{{ route('contact.create') }}" class="link-primary">お問い合わせフォーム</a>よりご連絡ください。当社は、合理的な期間内に対応いたします。</li>
                        <li>本人確認のため、ご本人であることが確認できる書類の提示をお願いする場合があります。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第9条（Cookie等の利用）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>当サービスでは、利便性向上のため Cookie および類似の技術を利用します。</li>
                        <li>Cookie はブラウザの設定により無効化することができますが、当サービスの一部機能が利用できなくなる場合があります。</li>
                        <li>当サービスは、利用状況分析のため Google Analytics 等の解析ツールを利用することがあります。これらのツールはCookieを使用しますが、個人を特定する情報は含まれません。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第10条（プライバシーポリシーの変更）</h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>当社は、必要に応じて本ポリシーを変更することがあります。</li>
                        <li>変更後の本ポリシーは、当サービス上に掲載した時点から効力を生じるものとします。</li>
                        <li>重要な変更については、利用者へ事前に通知します。</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-lg font-bold text-secondary-900 mt-8 mb-3">第11条（お問い合わせ窓口）</h2>
                    <p class="text-sm">本ポリシーに関するお問い合わせは、下記の窓口までお願いいたします。</p>
                    <div class="bg-canvas-50 rounded-lg p-4 text-sm mt-3">
                        <p>{{ config('app.name', 'Palette') }} 個人情報保護担当</p>
                        <p>お問い合わせ: <a href="{{ route('contact.create') }}" class="link-primary">お問い合わせフォーム</a></p>
                    </div>
                </section>

                <p class="text-right text-sm text-secondary-500 mt-12">以上</p>
            </div>
        </div>
    </section>
</div>
@endsection
