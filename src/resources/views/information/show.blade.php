@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="bg-white border rounded-lg p-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-sm text-gray-500">
                {{ $information->published_at->format('Y年m月d日') }}
            </span>
            @if($information->type === 'press_release')
                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                    プレスリリース
                </span>
            @else
                <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                    お知らせ
                </span>
            @endif
        </div>

        <h1 class="text-2xl font-bold mb-6">{{ $information->title }}</h1>

        @if($information->content)
            <div class="prose max-w-none">
                {!! nl2br(e($information->content)) !!}
            </div>
        @endif

        <div class="mt-8 pt-6 border-t">
            <a href="{{ route('information.index') }}" class="text-blue-600 hover:text-blue-800">
                ← お知らせ一覧に戻る
            </a>
        </div>
    </div>
</div>
@endsection

