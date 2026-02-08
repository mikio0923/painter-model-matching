{{-- Profileタブのコンテンツ（3カラム: 左=詳細 / 中央=写真 / 右=いいね・アクション） --}}
@php
    $favoritesCount = $favoritesCount ?? 0;
    $images = $modelProfile->images ?? collect();
    $allImages = collect();
    if ($modelProfile->profile_image_path) {
        $allImages->push(['image_path' => $modelProfile->profile_image_path, 'is_profile' => true]);
    }
    foreach ($images as $img) {
        if (!$modelProfile->profile_image_path || $img->image_path !== $modelProfile->profile_image_path) {
            $allImages->push(['image_path' => $img->image_path, 'is_profile' => false]);
        }
    }
    $activityRegions = $modelProfile->activity_regions ?? [];
    $activityRegionsStr = is_array($activityRegions) ? implode('　', $activityRegions) : ($modelProfile->prefecture ?? '');
    if (empty($activityRegionsStr) && $modelProfile->prefecture) {
        $activityRegionsStr = $modelProfile->prefecture;
    }
    $hairTypeLabels = ['short' => 'ショート', 'medium' => 'ミディアム', 'long' => 'ロング', 'semi_long' => 'セミロング', 'super_long' => 'スーパーロング', 'other' => 'その他'];
    $hairTypeDisplay = $hairTypeLabels[$modelProfile->hair_type ?? ''] ?? $modelProfile->hair_type ?? null;
    $hobbiesList = $modelProfile->hobbies ? array_filter(array_map('trim', preg_split('/[\s,、\n]+/u', $modelProfile->hobbies))) : [];
@endphp

<div class="bg-white rounded-b-lg shadow-sm">
    <div class="px-6 py-6">
        <h2 class="text-xl font-bold text-secondary-900 mb-6">> Profile / プロフィール</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- 左カラム：モデルの詳細情報 --}}
            <div class="space-y-4 order-2 lg:order-1">
                {{-- モデルプロフィール --}}
                <div class="border border-secondary-200 rounded-lg overflow-hidden">
                    <div class="bg-secondary-100 px-4 py-2">
                        <h3 class="text-base font-semibold text-secondary-900">モデルプロフィール</h3>
                    </div>
                    <div class="divide-y divide-dashed divide-secondary-300">
                        <div class="flex justify-between items-center px-4 py-3 text-sm">
                            <span class="text-secondary-600">1日あたりの参考価格</span>
                            <span class="text-secondary-900 font-medium text-primary-600">
                                @if($modelProfile->reward_min || $modelProfile->reward_max)
                                    @if($modelProfile->reward_min && $modelProfile->reward_max)
                                        {{ number_format($modelProfile->reward_min) }} 円〜{{ number_format($modelProfile->reward_max) }} 円
                                    @elseif($modelProfile->reward_min)
                                        {{ number_format($modelProfile->reward_min) }} 円
                                    @elseif($modelProfile->reward_max)
                                        〜{{ number_format($modelProfile->reward_max) }} 円
                                    @endif
                                @else
                                    未設定
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center px-4 py-3 text-sm">
                            <span class="text-secondary-600">活動地域</span>
                            <span class="text-secondary-900 font-medium">{{ $activityRegionsStr ?: '未設定' }}</span>
                        </div>
                        <div class="flex justify-between items-center px-4 py-3 text-sm">
                            <span class="text-secondary-600">本人確認</span>
                            <span class="text-secondary-900 font-medium">{{ $modelProfile->identity_verified ? '済' : '未' }}</span>
                        </div>
                        <div class="flex justify-between items-center px-4 py-3 text-sm">
                            <span class="text-secondary-600">取引条件</span>
                            <span class="text-secondary-900 font-medium text-sm">{{ $modelProfile->terms_text ?: '特になし' }}</span>
                        </div>
                    </div>
                </div>

                {{-- 趣味 --}}
                @if(count($hobbiesList) > 0 || $modelProfile->experience)
                    <div class="border border-secondary-200 rounded-lg p-4">
                        <div class="text-sm text-secondary-600 mb-1">趣味</div>
                        @if(count($hobbiesList) > 0)
                            <ul class="text-secondary-900 text-sm space-y-1 list-disc list-inside">
                                @foreach($hobbiesList as $hobby)
                                    <li>{{ $hobby }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-secondary-900 text-sm">{{ $modelProfile->experience }}</div>
                        @endif
                    </div>
                @endif

                {{-- 参考条件 --}}
                @if(!empty($modelProfile->avoid_work_types))
                    <div class="border border-amber-200 rounded-lg p-4 bg-amber-50">
                        <div class="text-sm font-semibold text-amber-800 mb-1">参考条件</div>
                        <div class="text-sm text-amber-800 space-y-1">
                            @foreach($modelProfile->avoid_work_types as $avoid)
                                <p>{{ $avoid }} が含まれるお仕事には対応できない可能性があります。</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- モデル詳細情報 --}}
                <div class="border border-secondary-200 rounded-lg overflow-hidden">
                    <div class="bg-secondary-100 px-4 py-2">
                        <h3 class="text-base font-semibold text-secondary-900">モデル詳細情報</h3>
                    </div>
                    <div class="divide-y divide-dashed divide-secondary-300">
                        @if($modelProfile->height)
                            <div class="flex justify-between items-center px-4 py-3 text-sm">
                                <span class="text-secondary-600">身長</span>
                                <span class="text-secondary-900 font-medium">{{ $modelProfile->height }} cm</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center px-4 py-3 text-sm">
                            <span class="text-secondary-600">サイズ</span>
                            <span class="text-secondary-900 font-medium">
                                @if($modelProfile->bust && $modelProfile->waist && $modelProfile->hip)
                                    B:{{ $modelProfile->bust }} - W:{{ $modelProfile->waist }} - H:{{ $modelProfile->hip }}
                                @else
                                    未設定
                                @endif
                            </span>
                        </div>
                        @if($modelProfile->body_type)
                            <div class="flex justify-between items-center px-4 py-3 text-sm">
                                <span class="text-secondary-600">体型</span>
                                <span class="text-secondary-900 font-medium">{{ $modelProfile->body_type }}</span>
                            </div>
                        @endif
                        @if($hairTypeDisplay)
                            <div class="flex justify-between items-center px-4 py-3 text-sm">
                                <span class="text-secondary-600">髪型</span>
                                <span class="text-secondary-900 font-medium">{{ $hairTypeDisplay }}</span>
                            </div>
                        @endif
                        @if($modelProfile->clothing_size)
                            <div class="flex justify-between items-center px-4 py-3 text-sm">
                                <span class="text-secondary-600">洋服のサイズ</span>
                                <span class="text-secondary-900 font-medium">{{ $modelProfile->clothing_size }}{{ is_numeric($modelProfile->clothing_size) ? ' 号' : '' }}</span>
                            </div>
                        @endif
                        @if($modelProfile->shoe_size)
                            <div class="flex justify-between items-center px-4 py-3 text-sm">
                                <span class="text-secondary-600">靴のサイズ</span>
                                <span class="text-secondary-900 font-medium">{{ $modelProfile->shoe_size }} cm</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center px-4 py-3 text-sm">
                            <span class="text-secondary-600">職業</span>
                            <span class="text-secondary-900 font-medium">{{ $modelProfile->occupation ?? '未設定' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 中央カラム：モデルの写真 --}}
            <div class="order-1 lg:order-2" id="profileImageCarousel">
                @if($allImages->count() > 0)
                    <div class="relative">
                        <div class="relative aspect-[3/4] bg-secondary-100 rounded-lg overflow-hidden">
                            @foreach($allImages as $index => $image)
                                <div class="carousel-slide absolute inset-0 transition-opacity duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}" data-index="{{ $index }}">
                                    <img src="{{ Storage::url($image['image_path']) }}"
                                         alt="{{ $modelProfile->display_name }}"
                                         class="w-full h-full object-cover cursor-pointer"
                                         onclick="openImageModal('{{ Storage::url($image['image_path']) }}')">
                                </div>
                            @endforeach

                            @if($allImages->count() > 1)
                                <button type="button" onclick="profilePrevImage()"
                                        class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-10 h-10 rounded-full flex items-center justify-center z-10 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button type="button" onclick="profileNextImage()"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-10 h-10 rounded-full flex items-center justify-center z-10 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            @endif
                        </div>

                        {{-- サムネイル --}}
                        @if($allImages->count() > 1)
                            <div class="flex gap-2 mt-3 overflow-x-auto scrollbar-hide pb-1">
                                @foreach($allImages as $index => $image)
                                    <button type="button" onclick="profileGoToImage({{ $index }})"
                                            class="profile-thumb flex-shrink-0 w-16 h-16 rounded overflow-hidden border-2 transition-colors {{ $index === 0 ? 'border-primary-500' : 'border-transparent hover:border-secondary-300' }}"
                                            data-index="{{ $index }}">
                                        <img src="{{ Storage::url($image['image_path']) }}" alt="" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="aspect-[3/4] bg-secondary-100 rounded-lg flex items-center justify-center">
                        <p class="text-secondary-500 text-sm">画像がまだ登録されていません</p>
                    </div>
                @endif
            </div>

            {{-- 右カラム：いいね数・アクション・タグ --}}
            <div class="space-y-4 order-3">
                {{-- 最終更新 --}}
                <div class="text-sm text-secondary-600">
                    最終更新: {{ $modelProfile->updated_at->format('n月j日') }} ({{ $modelProfile->updated_at->diffForHumans() }})
                </div>

                {{-- いいね（Good）--}}
                <div class="flex flex-col gap-3">
                    @auth
                        @if($isFavorite)
                            <form action="{{ route('favorites.destroy.model', $modelProfile) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full px-4 py-3 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-lg flex items-center justify-center gap-2 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/></svg>
                                    Good ({{ $favoritesCount }})
                                </button>
                            </form>
                        @else
                            <form action="{{ route('favorites.store.model', $modelProfile) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                        class="w-full px-4 py-3 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-lg flex items-center justify-center gap-2 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                                    Good ({{ $favoritesCount }})
                                </button>
                            </form>
                        @endif
                    @else
                        <div class="px-4 py-3 bg-accent-500 text-white font-semibold rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/></svg>
                            Good ({{ $favoritesCount }})
                        </div>
                    @endauth

                    {{-- メッセージを送る --}}
                    @auth
                        @if(auth()->user()->role === 'painter')
                            <a href="{{ route('painter.jobs.create', ['model_id' => $modelProfile->id]) }}"
                               class="block w-full px-4 py-3 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-lg text-center transition-colors">
                                メッセージを送る
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login-register') }}"
                           class="block w-full px-4 py-3 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-lg text-center transition-colors">
                            メッセージを送る
                        </a>
                    @endauth
                </div>

                {{-- タグ --}}
                @if(!empty($modelProfile->style_tags))
                    <div class="flex flex-wrap gap-2">
                        @foreach($modelProfile->style_tags as $tag)
                            <span class="badge badge-secondary text-xs">#{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($allImages->count() > 1)
<script>
(function() {
    let profileCurrentIndex = 0;
    const profileTotal = {{ $allImages->count() }};
    let autoSlideInterval = null;
    const autoSlideDelay = 4000; // 4秒ごとに切り替え

    window.profileShowImage = function(index) {
        profileCurrentIndex = index;
        document.querySelectorAll('#profileImageCarousel .carousel-slide').forEach((el, i) => {
            el.classList.toggle('opacity-100', i === index);
            el.classList.toggle('opacity-0', i !== index);
        });
        document.querySelectorAll('#profileImageCarousel .profile-thumb').forEach((el, i) => {
            el.classList.toggle('border-primary-500', i === index);
            el.classList.toggle('border-transparent', i !== index);
        });
    };

    window.profileNextImage = function() {
        profileShowImage((profileCurrentIndex + 1) % profileTotal);
        resetAutoSlide();
    };

    window.profilePrevImage = function() {
        profileShowImage((profileCurrentIndex - 1 + profileTotal) % profileTotal);
        resetAutoSlide();
    };

    window.profileGoToImage = function(index) {
        profileShowImage(index);
        resetAutoSlide();
    };

    function startAutoSlide() {
        autoSlideInterval = setInterval(function() {
            profileShowImage((profileCurrentIndex + 1) % profileTotal);
        }, autoSlideDelay);
    }

    function stopAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
            autoSlideInterval = null;
        }
    }

    function resetAutoSlide() {
        stopAutoSlide();
        startAutoSlide();
    }

    // カルーセルにマウスを乗せたら停止、離したら再開
    const carousel = document.getElementById('profileImageCarousel');
    if (carousel) {
        carousel.addEventListener('mouseenter', stopAutoSlide);
        carousel.addEventListener('mouseleave', startAutoSlide);
    }

    // 初期化：自動スライド開始
    startAutoSlide();
})();
</script>
@endif
