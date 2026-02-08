{{-- Photoタブ：ポートフォリオ・日記 --}}
<div class="bg-white rounded-b-lg shadow-sm">
    <div class="px-6 py-6">
        <h2 class="text-xl font-bold text-secondary-900 mb-6">> Photo / ポートフォリオ・日記</h2>
        
        @php
            $portfolioImages = ($modelProfile->images ?? collect())->sortByDesc('created_at')->values();
        @endphp

        @if($portfolioImages->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                @foreach($portfolioImages as $img)
                    <div class="card overflow-hidden">
                        <button type="button"
                                onclick="openImageModal('{{ Storage::url($img->image_path) }}')"
                                class="block w-full text-left">
                            <div class="aspect-[3/4] card-media">
                                <img src="{{ Storage::url($img->image_path) }}"
                                     alt="{{ $img->caption ? \Illuminate\Support\Str::limit($img->caption, 50) : 'ポートフォリオ画像' }}"
                                     class="w-full h-full object-cover">
                            </div>
                        </button>
                        <div class="card-body p-3">
                            @if($img->caption)
                                <p class="text-sm text-secondary-700 line-clamp-3 mb-2">{{ $img->caption }}</p>
                            @endif
                            <p class="text-xs text-secondary-500">{{ $img->created_at->format('Y年n月j日') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-secondary-600">まだ投稿がありません。</p>
            </div>
        @endif
    </div>
</div>

