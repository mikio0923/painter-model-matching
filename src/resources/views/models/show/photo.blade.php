{{-- Photoタブ：ポートフォリオ・日記 --}}
<div class="border-x border-b border-secondary-200 bg-canvas-50 px-5 sm:px-6 py-6">
    <div class="flex items-baseline gap-3 mb-6">
        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Photo</p>
        <h2 class="font-display text-lg font-semibold text-secondary-900">ポートフォリオ・日記</h2>
    </div>

    @php
        $portfolioImages = ($modelProfile->images ?? collect())->sortByDesc('created_at')->values();
    @endphp

    @if($portfolioImages->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-5">
            @foreach($portfolioImages as $img)
                <div class="border border-secondary-200 bg-canvas-50 hover:border-secondary-400 transition-colors duration-300">
                    <button type="button"
                            onclick="openImageModal('{{ Storage::url($img->image_path) }}')"
                            class="block w-full text-left">
                        <div class="aspect-[3/4] overflow-hidden bg-secondary-100">
                            <img src="{{ Storage::url($img->image_path) }}"
                                 alt="{{ $img->caption ? \Illuminate\Support\Str::limit($img->caption, 50) : 'ポートフォリオ画像' }}"
                                 class="w-full h-full object-cover">
                        </div>
                    </button>
                    <div class="px-3 py-3 border-t border-secondary-200">
                        @if($img->caption)
                            <p class="text-sm text-secondary-700 line-clamp-3 mb-2 leading-relaxed">{{ $img->caption }}</p>
                        @endif
                        <p class="text-[10px] tracking-[0.2em] uppercase text-secondary-400">{{ $img->created_at->format('Y . n . j') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="border border-secondary-200 px-5 py-16 text-center">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Posts</p>
            <p class="text-secondary-500 text-sm">まだ投稿がありません。</p>
        </div>
    @endif
</div>
