@extends('layouts.app')

@section('content')
<div class="page-narrow">

    <div class="card mb-6">
        <div class="card-body">
            <div class="flex gap-6 mb-6">
                @php
                    $mainImage = $modelProfile->mainImage();
                    $displayImage = $mainImage ? $mainImage->image_path : $modelProfile->profile_image_path;
                @endphp
                @if($displayImage)
                    <div class="flex-shrink-0">
                        <img src="{{ asset('storage/' . $displayImage) }}"
                             alt="{{ $modelProfile->display_name }}"
                             class="w-48 h-48 object-cover rounded-xl border border-secondary-300">
                    </div>
                @endif
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-2xl font-bold text-secondary-900">
                            {{ $modelProfile->display_name }}
                        </h1>
                        @auth
                            @if($isFavorite)
                                <form action="{{ route('favorites.destroy.model', $modelProfile) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-outline-sm flex items-center gap-2 text-error-600 border-error-600 hover:bg-error-50">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                            <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                        </svg>
                                        お気に入り解除
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('favorites.store.model', $modelProfile) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="btn-secondary-sm flex items-center gap-2">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                            <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" fill-rule="evenodd"/>
                                        </svg>
                                        お気に入りに追加
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>

                    <div class="text-secondary-600 mb-6">
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
        </div>
    </div>

    {{-- 基本情報 --}}
    <div class="card mb-6">
        <div class="card-body">
            <div class="space-y-2">
                @if($modelProfile->hair_type)
                    <div>
                        <span class="font-semibold text-secondary-900">髪型：</span>
                        <span class="text-secondary-700">
                            @if($modelProfile->hair_type === 'short')ショート
                            @elseif($modelProfile->hair_type === 'medium')ミディアム
                            @elseif($modelProfile->hair_type === 'long')ロング
                            @elseその他
                            @endif
                        </span>
                    </div>
                @endif

                <div>
                    <span class="font-semibold text-secondary-900">オンライン対応：</span>
                    <span class="text-secondary-700">{{ $modelProfile->online_available ? '可' : '不可' }}</span>
                </div>

                <div>
                    <span class="font-semibold text-secondary-900">報酬目安：</span>
                    <span class="text-secondary-700">
                        @if($modelProfile->reward_min || $modelProfile->reward_max)
                            {{ $modelProfile->reward_min ? number_format($modelProfile->reward_min) : '—' }}
                            〜
                            {{ $modelProfile->reward_max ? number_format($modelProfile->reward_max) : '—' }} 円
                        @else
                            未設定
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- 自己紹介 --}}
    @if($modelProfile->bio)
        <div class="card mb-6">
            <div class="card-body">
                <h2 class="section-subtitle mb-2">自己紹介</h2>
                <p class="text-secondary-700 whitespace-pre-wrap">{{ $modelProfile->bio }}</p>
            </div>
        </div>
    @endif

    {{-- 経験・実績 --}}
    @if($modelProfile->experience)
        <div class="card mb-6">
            <div class="card-body">
                <h2 class="section-subtitle mb-2">経験・実績</h2>
                <p class="text-secondary-700 whitespace-pre-wrap">{{ $modelProfile->experience }}</p>
            </div>
        </div>
    @endif

    {{-- ポートフォリオ・SNS --}}
    @if($modelProfile->portfolio_url || !empty($modelProfile->sns_links))
        <div class="card mb-6">
            <div class="card-body">
                <h2 class="section-subtitle mb-2">リンク</h2>
                <div class="space-y-2">
                    @if($modelProfile->portfolio_url)
                        <div>
                            <a href="{{ $modelProfile->portfolio_url }}" target="_blank" rel="noopener noreferrer" class="link-primary">
                                ポートフォリオ →
                            </a>
                        </div>
                    @endif
                    @if(!empty($modelProfile->sns_links))
                        @foreach($modelProfile->sns_links as $link)
                            <div>
                                <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="link-primary">
                                    {{ $link }} →
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- 画像ギャラリー --}}
    @if($modelProfile->images->count() > 0)
        <div class="card mb-6">
            <div class="card-body">
                <h2 class="section-subtitle mb-4">画像ギャラリー</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($modelProfile->images as $image)
                        <div class="aspect-square overflow-hidden rounded-xl border border-secondary-300 cursor-pointer hover:shadow-md transition-shadow"
                             onclick="openImageModal('{{ asset('storage/' . $image->image_path) }}')">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                 alt="ギャラリー画像"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- タグ --}}
    <div class="card mb-6">
        <div class="card-body">
            <span class="font-semibold text-secondary-900">スタイル：</span>
            @if(!empty($modelProfile->style_tags))
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($modelProfile->style_tags as $tag)
                        <span class="badge badge-secondary">{{ $tag }}</span>
                    @endforeach
                </div>
            @else
                <span class="text-secondary-600">未設定</span>
            @endif
        </div>
    </div>

    {{-- レビューセクション --}}
    @if($reviews->count() > 0)
        <div class="card mb-6">
            <div class="card-body">
                <h2 class="section-subtitle mb-4">レビュー</h2>
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <div class="border border-secondary-200 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-secondary-900">
                                        {{ $review->reviewer->name }}
                                        @if($review->job)
                                            <span class="text-sm font-normal text-secondary-600">
                                                ({{ $review->job->title }})
                                            </span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-secondary-600">
                                        {{ $review->created_at->format('Y年m月d日') }}
                                    </p>
                                </div>
                                <span class="badge badge-success">
                                    {{ $review->rating_label }}
                                </span>
                            </div>
                            @if($review->comment)
                                <p class="text-sm text-secondary-700 mt-2 whitespace-pre-wrap">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- アクション --}}
    <div class="card">
        <div class="card-body">
            @guest
                {{-- 未ログイン --}}
                <a href="{{ route('login-register') }}"
                   class="btn-cute-blue">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    このモデルに依頼したい（ログイン）
                </a>

            @else
                {{-- ログイン済 --}}
                @if(auth()->user()->role === 'painter')
                    <a href="{{ route('painter.jobs.create', ['model_id' => $modelProfile->id]) }}"
                       class="btn-primary">
                        このモデルに依頼する
                    </a>

                @else
                    {{-- モデル自身 or 他モデル --}}
                    <p class="text-secondary-500">
                        ※ 画家アカウントでログインすると依頼できます
                    </p>
                @endif
            @endguest
        </div>
    </div>

</div>

{{-- 画像モーダル --}}
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center" onclick="closeImageModal()">
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
