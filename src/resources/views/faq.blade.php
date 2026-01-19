@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">よくある質問（Q&A）</h1>
    
    <div class="space-y-6">
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-2">Q. 登録は無料ですか？</h2>
            <p class="text-gray-700">
                A. はい、登録は無料です。モデルも画家も無料でアカウントを作成できます。
            </p>
        </div>
        
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-2">Q. モデルとして登録するにはどうすればいいですか？</h2>
            <p class="text-gray-700">
                A. 新規登録ページで「モデル」を選択して登録し、プロフィールを作成してください。
            </p>
        </div>
        
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-2">Q. 画家として登録するにはどうすればいいですか？</h2>
            <p class="text-gray-700">
                A. 新規登録ページで「画家」を選択して登録してください。その後、依頼を作成できます。
            </p>
        </div>
        
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-2">Q. 報酬はどのように決めますか？</h2>
            <p class="text-gray-700">
                A. 依頼を作成する際に報酬額を設定できます。モデルと画家で直接交渉することも可能です。
            </p>
        </div>
        
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-2">Q. 応募した後はどうなりますか？</h2>
            <p class="text-gray-700">
                A. 画家が応募を確認し、承認または却下を決定します。承認された場合は、メッセージ機能で連絡を取り合うことができます。
            </p>
        </div>
        
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-2">Q. プロフィール画像は必須ですか？</h2>
            <p class="text-gray-700">
                A. 必須ではありませんが、プロフィール画像があると応募や依頼が増える傾向があります。
            </p>
        </div>
        
        <div class="pb-6">
            <h2 class="text-xl font-semibold mb-2">Q. その他の質問がある場合は？</h2>
            <p class="text-gray-700">
                A. お問い合わせフォームからご連絡ください。できるだけ早く回答いたします。
            </p>
        </div>
    </div>
</div>
@endsection

