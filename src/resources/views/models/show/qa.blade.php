{{-- Q&Aタブ: モデル本人が登録した FAQ のみ公開する --}}
<div class="border-x border-b border-secondary-200 bg-canvas-50 px-5 sm:px-6 py-6">
    <div class="flex items-baseline gap-3 mb-6">
        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Q &amp; A</p>
        <h2 class="font-display text-lg font-semibold text-secondary-900">よくある質問</h2>
    </div>

    @if(isset($questions) && $questions->count() > 0)
        <div class="space-y-4">
            @foreach($questions as $qa)
                <div class="border border-secondary-200 bg-canvas-50 px-5 py-4">
                    <div class="flex items-start gap-3 mb-3">
                        <span class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 mt-1 shrink-0">Q.</span>
                        <p class="text-secondary-900 flex-1 leading-relaxed whitespace-pre-wrap">{{ $qa->question }}</p>
                    </div>
                    <div class="flex items-start gap-3 pt-3 border-t border-secondary-200">
                        <span class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 mt-1 shrink-0">A.</span>
                        <p class="text-secondary-700 flex-1 whitespace-pre-wrap leading-relaxed">{{ $qa->answer }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="border border-secondary-200 px-5 py-12 text-center">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Q&amp;A</p>
            <p class="text-secondary-500 text-sm">まだ Q&amp;A が登録されていません。</p>
        </div>
    @endif

    @auth
        @if(auth()->user()->role === 'model' && auth()->user()->id === $modelProfile->user_id)
            <div class="border border-secondary-200 bg-canvas-50 px-5 py-6 text-center mt-6">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-2">Manage</p>
                <p class="text-secondary-700 text-sm mb-4">あなたの Q&A はこちらから編集できます</p>
                <a href="{{ route('model.questions.index') }}"
                   class="inline-block px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                    Q&amp;A を編集
                </a>
            </div>
        @endif
    @endauth
</div>
