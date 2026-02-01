{{-- Photoタブのコンテンツ --}}
<div class="bg-white rounded-b-lg shadow-sm">
    <div class="px-6 py-6">
        <h2 class="text-xl font-bold text-secondary-900 mb-6">> Photo / 写真ギャラリー</h2>
        
        @php
            $images = $modelProfile->images ?? collect();
            // プロフィール画像も含める
            $allImages = collect();
            
            if ($modelProfile->profile_image_path) {
                $allImages->push(['image_path' => $modelProfile->profile_image_path, 'is_profile' => true]);
            }
            
            // ギャラリー画像を追加（プロフィール画像と重複しないように）
            foreach ($images as $img) {
                if (!$modelProfile->profile_image_path || $img->image_path !== $modelProfile->profile_image_path) {
                    $allImages->push(['image_path' => $img->image_path, 'is_profile' => false]);
                }
            }
        @endphp

        @if($allImages->count() > 0)

            <div class="max-w-4xl mx-auto">
                {{-- メイン画像カルーセル --}}
                <div class="relative mb-4" id="imageCarousel">
                    <div class="relative aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden">
                        @foreach($allImages as $index => $image)
                            <div class="carousel-slide absolute inset-0 transition-opacity duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                                 data-index="{{ $index }}">
                                <img src="{{ Storage::url($image['image_path']) }}"
                                     alt="ギャラリー画像 {{ $index + 1 }}"
                                     class="w-full h-full object-cover cursor-pointer"
                                     onclick="openImageModal('{{ Storage::url($image['image_path']) }}')">
                            </div>
                        @endforeach

                        {{-- 左矢印ボタン --}}
                        @if($allImages->count() > 1)
                            <button onclick="previousImage()" 
                                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-gray-800 bg-opacity-70 hover:bg-opacity-90 text-white w-10 h-10 rounded flex items-center justify-center transition-all z-10">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>

                            {{-- 右矢印ボタン --}}
                            <button onclick="nextImage()" 
                                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-gray-800 bg-opacity-70 hover:bg-opacity-90 text-white w-10 h-10 rounded flex items-center justify-center transition-all z-10">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        @endif
                    </div>

                    {{-- 画像インジケーター --}}
                    @if($allImages->count() > 1)
                        <div class="flex justify-center gap-2 mt-4">
                            @foreach($allImages as $index => $image)
                                <button onclick="goToImage({{ $index }})"
                                        class="indicator-dot w-12 h-1 rounded transition-all {{ $index === 0 ? 'bg-gray-800' : 'bg-gray-300' }}"
                                        data-index="{{ $index }}"></button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- サムネイル画像 --}}
                @if($allImages->count() > 1)
                    <div class="bg-gray-800 rounded-lg p-4">
                        <div class="flex gap-2 overflow-x-auto scrollbar-hide">
                            @foreach($allImages as $index => $image)
                                <button onclick="goToImage({{ $index }})"
                                        class="thumbnail-item flex-shrink-0 w-20 h-20 rounded overflow-hidden border-2 transition-all {{ $index === 0 ? 'border-white' : 'border-transparent' }}"
                                        data-index="{{ $index }}">
                                    <img src="{{ Storage::url($image['image_path']) }}"
                                         alt="サムネイル {{ $index + 1 }}"
                                         class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-secondary-600">画像がまだ登録されていません。</p>
            </div>
        @endif
    </div>
</div>

@if($allImages->count() > 0)
<script>
let currentImageIndex = 0;
const totalImages = {{ $allImages->count() }};
let autoSlideInterval;

function showImage(index) {
    // すべてのスライドを非表示
    document.querySelectorAll('.carousel-slide').forEach((slide, i) => {
        if (i === index) {
            slide.classList.remove('opacity-0');
            slide.classList.add('opacity-100');
        } else {
            slide.classList.remove('opacity-100');
            slide.classList.add('opacity-0');
        }
    });

    // インジケーターを更新
    document.querySelectorAll('.indicator-dot').forEach((dot, i) => {
        if (i === index) {
            dot.classList.remove('bg-gray-300');
            dot.classList.add('bg-gray-800');
        } else {
            dot.classList.remove('bg-gray-800');
            dot.classList.add('bg-gray-300');
        }
    });

    // サムネイルを更新
    document.querySelectorAll('.thumbnail-item').forEach((thumb, i) => {
        if (i === index) {
            thumb.classList.remove('border-transparent');
            thumb.classList.add('border-white');
        } else {
            thumb.classList.remove('border-white');
            thumb.classList.add('border-transparent');
        }
    });

    currentImageIndex = index;
}

function nextImage() {
    const nextIndex = (currentImageIndex + 1) % totalImages;
    showImage(nextIndex);
    resetAutoSlide();
}

function previousImage() {
    const prevIndex = (currentImageIndex - 1 + totalImages) % totalImages;
    showImage(prevIndex);
    resetAutoSlide();
}

function goToImage(index) {
    showImage(index);
    resetAutoSlide();
}

function resetAutoSlide() {
    clearInterval(autoSlideInterval);
    startAutoSlide();
}

function startAutoSlide() {
    autoSlideInterval = setInterval(() => {
        nextImage();
    }, 3000);
}

// カルーセルにマウスオーバーしたら自動スライドを停止
document.getElementById('imageCarousel').addEventListener('mouseenter', () => {
    clearInterval(autoSlideInterval);
});

// カルーセルからマウスアウトしたら自動スライドを再開
document.getElementById('imageCarousel').addEventListener('mouseleave', () => {
    startAutoSlide();
});

// 初期化：自動スライドを開始
@if($allImages->count() > 1)
    startAutoSlide();
@endif
</script>
@endif
