@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">モデルを探すガイド</h1>

    <div class="prose max-w-none">
        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">モデル検索の方法</h2>
            <ul class="list-disc list-inside space-y-2">
                <li>モデル一覧ページから検索条件を指定して検索できます</li>
                <li>都道府県、性別、年齢、髪型、スタイルタグなどで絞り込めます</li>
                <li>キーワード検索で表示名を検索できます</li>
                <li>報酬範囲で検索することも可能です</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">依頼を投稿する流れ</h2>
            <ol class="list-decimal list-inside space-y-2">
                <li>画家アカウントでログイン</li>
                <li>「依頼を作成」から新しい依頼を投稿</li>
                <li>タイトル、説明、報酬、場所、日程などを入力</li>
                <li>特定のモデルに依頼したい場合は、モデル詳細ページから「依頼する」をクリック</li>
                <li>応募してきたモデルから承認します</li>
                <li>承認後、メッセージで詳細を確認</li>
            </ol>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">依頼内容の書き方</h2>
            <ul class="list-disc list-inside space-y-2">
                <li>依頼内容は具体的に記載してください</li>
                <li>用途（個展、練習、作品制作など）を明記すると良いです</li>
                <li>報酬額を明確に設定してください</li>
                <li>日程や場所の希望を記載してください</li>
                <li>応募締切を設定すると、スムーズに進められます</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">モデルとのやり取り</h2>
            <ul class="list-disc list-inside space-y-2">
                <li>応募を承認すると、メッセージ機能が利用可能になります</li>
                <li>詳細な打ち合わせはメッセージで行えます</li>
                <li>依頼完了後は、レビューを投稿できます</li>
                <li>良い体験をした場合は、ぜひレビューを投稿してください</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">注意事項</h2>
            <ul class="list-disc list-inside space-y-2">
                <li>依頼内容は正確に記載してください</li>
                <li>報酬は事前に明確にしておいてください</li>
                <li>日程や場所は事前に確認してください</li>
                <li>モデルとのコミュニケーションは丁寧に行ってください</li>
            </ul>
        </section>
    </div>
</div>
@endsection

