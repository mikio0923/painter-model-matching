@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-2xl font-bold mb-6">お知らせ一覧</h1>

    @if($informations->isEmpty())
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
            <p class="text-gray-600">お知らせはありません</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($informations as $info)
                <div class="bg-white border rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-sm text-gray-500">
                                    {{ $info->published_at->format('Y年m月d日') }}
                                </span>
                                @if($info->type === 'press_release')
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                        プレスリリース
                                    </span>
                                @else
                                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                        お知らせ
                                    </span>
                                @endif
                            </div>
                            <h2 class="text-lg font-semibold mb-2">
                                <a href="{{ route('information.show', $info) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $info->title }}
                                </a>
                            </h2>
                            @if($info->content)
                                <p class="text-sm text-gray-600 line-clamp-2">
                                    {{ strip_tags($info->content) }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $informations->links() }}
        </div>
    @endif
</div>
@endsection

