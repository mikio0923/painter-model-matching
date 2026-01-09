@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">

    <div class="flex gap-6 mb-6">
        @php
            $mainImage = $modelProfile->mainImage();
            $displayImage = $mainImage ? $mainImage->image_path : $modelProfile->profile_image_path;
        @endphp
        @if($displayImage)
            <div class="flex-shrink-0">
                <img src="{{ Storage::url($displayImage) }}"
                     alt="{{ $modelProfile->display_name }}"
                     class="w-48 h-48 object-cover rounded-lg border border-gray-300">
            </div>
        @endif
        <div class="flex-1">
            <h1 class="text-2xl font-bold mb-4">
                {{ $modelProfile->display_name }}
            </h1>

            <div class="text-gray-600 mb-6">
                @if($modelProfile->prefecture)
                    <span>{{ $modelProfile->prefecture }}</span>
                @endif

                @if($modelProfile->age)
                    <span class="ml-2">{{ $modelProfile->age }}歳</span>
                @endif

                @if($modelProfile->gender)
                    <span class="ml-2">
                        @if($modelProfile->gender === 'male')男性
                        @elseif($modelProfile->gender === 'female')女性
                        @elseその他
                        @endif
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- 基本情報 --}}
    <div class="mb-6 space-y-2">
        @if($modelProfile->hair_type)
            <div>
                <span class="font-semibold">髪型：</span>
                @if($modelProfile->hair_type === 'short')ショート
                @elseif($modelProfile->hair_type === 'medium')ミディアム
                @elseif($modelProfile->hair_type === 'long')ロング
                @elseその他
                @endif
            </div>
        @endif

        <div>
            <span class="font-semibold">オンライン対応：</span>
            {{ $modelProfile->online_available ? '可' : '不可' }}
        </div>

        <div>
            <span class="font-semibold">報酬目安：</span>
            @if($modelProfile->reward_min || $modelProfile->reward_max)
                {{ $modelProfile->reward_min ? number_format($modelProfile->reward_min) : '—' }}
                〜
                {{ $modelProfile->reward_max ? number_format($modelProfile->reward_max) : '—' }} 円
            @else
                未設定
            @endif
        </div>
    </div>

    {{-- 画像ギャラリー --}}
    @if($modelProfile->images->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">画像ギャラリー</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($modelProfile->images as $image)
                    <div class="aspect-square overflow-hidden rounded-lg border border-gray-300">
                        <img src="{{ Storage::url($image->image_path) }}" 
                             alt="ギャラリー画像" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform cursor-pointer"
                             onclick="openImageModal('{{ Storage::url($image->image_path) }}')">
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- タグ --}}
    <div class="mb-8">
        <span class="font-semibold">スタイル：</span>
        @if(!empty($modelProfile->style_tags))
            <div class="mt-2 flex flex-wrap gap-2">
                @foreach($modelProfile->style_tags as $tag)
                    <span class="text-sm border rounded px-2 py-1">
                        {{ $tag }}
                    </span>
                @endforeach
            </div>
        @else
            <span class="text-gray-600">未設定</span>
        @endif
    </div>

    {{-- レビューセクション --}}
    @if($reviews->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">レビュー</h2>
            <div class="space-y-4">
                @foreach($reviews as $review)
                    <div class="border rounded-lg p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-semibold">
                                    {{ $review->reviewer->name }}
                                    @if($review->job)
                                        <span class="text-sm font-normal text-gray-600">
                                            ({{ $review->job->title }})
                                        </span>
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $review->created_at->format('Y年m月d日') }}
                                </p>
                            </div>
                            <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                {{ $review->rating_label }}
                            </span>
                        </div>
                        @if($review->comment)
                            <p class="text-sm text-gray-700 mt-2 whitespace-pre-wrap">{{ $review->comment }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- アクション --}}
    <div class="border-t pt-6">

        @guest
            {{-- 未ログイン --}}
            <a href="{{ route('login') }}"
               class="inline-block bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
                このモデルに依頼したい（ログイン）
            </a>

        @else
            {{-- ログイン済 --}}
            @if(auth()->user()->role === 'painter')
                <a href="{{ route('painter.jobs.create', ['model_id' => $modelProfile->id]) }}"
                   class="inline-block bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
                    このモデルに依頼する
                </a>

            @else
                {{-- モデル自身 or 他モデル --}}
                <p class="text-gray-500">
                    ※ 画家アカウントでログインすると依頼できます
                </p>
            @endif
        @endguest

    </div>

</div>

{{-- 画像モーダル --}}
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center" onclick="closeImageModal()">
    <div class="max-w-4xl max-h-full p-4 relative">
        <img id="modalImage" src="" alt="拡大画像" class="max-w-full max-h-screen object-contain">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-2xl font-bold hover:text-gray-300 bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center">×</button>
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
@endsection
