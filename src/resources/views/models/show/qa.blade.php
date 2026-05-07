{{-- Q&Aタブのコンテンツ --}}
<div class="border-x border-b border-secondary-200 bg-canvas-50 px-5 sm:px-6 py-6">
    <div class="flex items-baseline gap-3 mb-6">
        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Q &amp; A</p>
        <h2 class="font-display text-lg font-semibold text-secondary-900">よくある質問</h2>
    </div>

    {{-- 回答済みのQ&A一覧 --}}
    @if(isset($questions) && $questions->count() > 0)
        <div class="space-y-4 mb-8">
            @foreach($questions as $qa)
                <div class="border border-secondary-200 bg-canvas-50 px-5 py-4">
                    <div class="flex items-start gap-3 mb-3">
                        <span class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 mt-1 shrink-0">Q.</span>
                        <p class="text-secondary-900 flex-1 leading-relaxed">{{ $qa->question }}</p>
                    </div>
                    <div class="flex items-start gap-3 pt-3 border-t border-secondary-200">
                        <span class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 mt-1 shrink-0">A.</span>
                        <p class="text-secondary-700 flex-1 whitespace-pre-wrap leading-relaxed">{{ $qa->answer }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- 画家向け：質問フォーム --}}
    @auth
        @if(auth()->user()->role === 'painter' && auth()->user()->id !== $modelProfile->user_id)
            <div class="border border-secondary-200 bg-canvas-50 px-5 sm:px-6 py-6">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Ask a Question</p>
                <h3 class="font-display text-base font-semibold text-secondary-900 mb-4">このモデルに質問する</h3>
                <form action="{{ route('model-profile.questions.store', $modelProfile) }}" method="POST">
                    @csrf
                    <textarea name="question" rows="4" required
                        placeholder="気になることを質問してください（例：撮影の服装は指定されますか？）"
                        class="w-full px-4 py-3 bg-canvas-50 border @error('question') border-error-500 @else border-secondary-300 @enderror text-secondary-900 text-sm leading-relaxed
                               focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900
                               transition-colors duration-200">{{ old('question') }}</textarea>
                    @error('question')
                        <p class="text-xs text-error-600 mt-2">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-secondary-500 mt-3">回答はモデルのプロフィールに掲載されます。</p>
                    <div class="mt-4 flex justify-end pt-3 border-t border-secondary-200">
                        <button type="submit"
                                class="px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                            Send Question
                        </button>
                    </div>
                </form>
            </div>
        @elseif(auth()->user()->role === 'model' && auth()->user()->id === $modelProfile->user_id)
            <div class="border border-secondary-200 bg-canvas-50 px-5 py-6 text-center">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-2">Manage</p>
                <p class="text-secondary-700 text-sm mb-4">届いた質問の確認と回答はこちらから</p>
                <a href="{{ route('model.questions.index') }}"
                   class="inline-block px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                    Manage Questions
                </a>
            </div>
        @elseif(auth()->user()->role !== 'painter')
            <div class="border border-secondary-200 px-5 py-12 text-center">
                <p class="text-secondary-500 text-sm">質問機能は画家アカウントでのみ利用できます。</p>
            </div>
        @endif
    @else
        <div class="border border-secondary-200 bg-canvas-50 px-5 py-8 text-center">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-2">Login Required</p>
            <p class="text-secondary-700 text-sm mb-5">質問するにはログインが必要です</p>
            <a href="{{ route('login-register') }}"
               class="inline-block px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                Sign In
            </a>
        </div>
    @endauth

    {{-- 未ログイン・ゲストでQ&Aもない場合 --}}
    @if((!isset($questions) || $questions->isEmpty()) && !Auth::check())
        <div class="border border-secondary-200 px-5 py-12 text-center mt-6">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Questions</p>
            <p class="text-secondary-500 text-sm">ログインすると、このモデルに質問を送信できます。</p>
        </div>
    @endif
</div>
