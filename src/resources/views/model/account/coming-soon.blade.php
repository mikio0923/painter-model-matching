@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-2">
        <span class="text-gray-400">&gt;</span> {{ $title }}
    </h1>

    <div class="bg-amber-50 border border-amber-200 rounded-lg p-8 text-center mt-8">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-amber-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h2 class="text-xl font-semibold text-amber-800 mb-2">準備中です</h2>
        <p class="text-amber-700 mb-6">{{ $description }}</p>
        <a href="{{ route('mypage') }}" class="inline-block bg-amber-600 text-white px-6 py-2 rounded hover:bg-amber-700">
            マイページに戻る
        </a>
    </div>
</div>
@endsection
