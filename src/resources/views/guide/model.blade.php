@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">モデルになるガイド</h1>

    <div class="prose max-w-none">
        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">モデル登録の流れ</h2>
            <ol class="list-decimal list-inside space-y-2">
                <li>新規登録でモデルアカウントを作成</li>
                <li>プロフィール情報を入力（表示名、年齢、性別、都道府県など）</li>
                <li>プロフィール画像をアップロード</li>
                <li>報酬目安を設定</li>
                <li>プロフィールを公開</li>
            </ol>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">プロフィール作成のポイント</h2>
            <ul class="list-disc list-inside space-y-2">
                <li>プロフィール画像は複数枚アップロード可能です</li>
                <li>自己紹介や経験・実績を詳しく記載すると、依頼が来やすくなります</li>
                <li>スタイルタグを適切に設定することで、検索されやすくなります</li>
                <li>報酬目安を設定することで、適切な依頼が来やすくなります</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">依頼を受ける流れ</h2>
            <ol class="list-decimal list-inside space-y-2">
                <li>画家から依頼が来ます</li>
                <li>依頼内容を確認し、応募します</li>
                <li>画家が応募を承認すると、メッセージのやり取りが可能になります</li>
                <li>詳細を確認し、依頼を完了します</li>
                <li>完了後、お互いにレビューを投稿できます</li>
            </ol>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">注意事項</h2>
            <ul class="list-disc list-inside space-y-2">
                <li>プロフィール情報は正確に入力してください</li>
                <li>応募後は誠実に対応してください</li>
                <li>報酬や条件は事前に確認してください</li>
                <li>安全のため、初回は公共の場所での撮影を推奨します</li>
            </ul>
        </section>
    </div>
</div>
@endsection

