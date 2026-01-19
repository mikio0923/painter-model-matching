@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16 text-center">
    <div class="max-w-md mx-auto">
        <h1 class="text-6xl font-bold text-gray-800 mb-4">500</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">サーバーエラーが発生しました</h2>
        <p class="text-gray-600 mb-8">
            申し訳ございません。サーバーでエラーが発生しました。
            しばらく時間をおいてから再度お試しください。
        </p>
        <div class="space-x-4">
            <a href="{{ route('home') }}" 
               class="inline-block bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition-colors">
                ホームに戻る
            </a>
            <a href="{{ route('contact.create') }}" 
               class="inline-block bg-gray-200 text-gray-700 px-6 py-3 rounded hover:bg-gray-300 transition-colors">
                お問い合わせ
            </a>
        </div>
    </div>
</div>
@endsection
