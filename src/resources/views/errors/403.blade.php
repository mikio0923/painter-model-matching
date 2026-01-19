@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16 text-center">
    <div class="max-w-md mx-auto">
        <h1 class="text-6xl font-bold text-gray-800 mb-4">403</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">アクセス権限がありません</h2>
        <p class="text-gray-600 mb-8">
            このページにアクセスする権限がありません。
        </p>
        <div class="space-x-4">
            <a href="{{ route('home') }}" 
               class="inline-block bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition-colors">
                ホームに戻る
            </a>
            <a href="javascript:history.back()" 
               class="inline-block bg-gray-200 text-gray-700 px-6 py-3 rounded hover:bg-gray-300 transition-colors">
                前のページに戻る
            </a>
        </div>
    </div>
</div>
@endsection
