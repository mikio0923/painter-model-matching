@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">ご利用ガイドライン</h1>
    
    <div class="prose max-w-none">
        <h2 class="text-2xl font-semibold mt-8 mb-4">基本ルール</h2>
        <ul class="list-disc pl-6 space-y-2 text-gray-700">
            <li>お互いを尊重し、礼儀正しくコミュニケーションを取りましょう</li>
            <li>虚偽の情報を登録しないでください</li>
            <li>個人情報の取り扱いには十分注意してください</li>
            <li>不適切な内容の投稿は禁止されています</li>
        </ul>
        
        <h2 class="text-2xl font-semibold mt-8 mb-4">モデルの方へ</h2>
        <ul class="list-disc pl-6 space-y-2 text-gray-700">
            <li>プロフィール情報は正確に入力してください</li>
            <li>応募する際は、依頼内容をよく確認してください</li>
            <li>約束した日程や条件は守りましょう</li>
        </ul>
        
        <h2 class="text-2xl font-semibold mt-8 mb-4">画家の方へ</h2>
        <ul class="list-disc pl-6 space-y-2 text-gray-700">
            <li>依頼内容は具体的に記載してください</li>
            <li>報酬や条件は事前に明確にしましょう</li>
            <li>応募してくれたモデルには適切に対応してください</li>
        </ul>
        
        <h2 class="text-2xl font-semibold mt-8 mb-4">トラブルについて</h2>
        <p class="text-gray-700">
            何か問題が発生した場合は、お問い合わせフォームからご連絡ください。
            迅速に対応いたします。
        </p>
    </div>
</div>
@endsection

