{{-- Q&Aタブのコンテンツ --}}
<div class="bg-white rounded-b-lg shadow-sm">
    <div class="px-6 py-6">
        <h2 class="text-xl font-bold text-secondary-900 mb-6">> Q&A / よくある質問</h2>

        {{-- 回答済みのQ&A一覧 --}}
        @if(isset($questions) && $questions->count() > 0)
            <div class="space-y-4 mb-8">
                @foreach($questions as $qa)
                    <div class="border border-secondary-200 rounded-lg p-4">
                        <div class="flex items-start gap-2 mb-2">
                            <span class="text-sm font-medium text-secondary-600">Q.</span>
                            <p class="text-secondary-900 flex-1">{{ $qa->question }}</p>
                        </div>
                        <div class="flex items-start gap-2 pl-4 border-l-2 border-primary-200">
                            <span class="text-sm font-medium text-secondary-600">A.</span>
                            <p class="text-secondary-800 flex-1 whitespace-pre-wrap">{{ $qa->answer }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- 画家向け：質問フォーム --}}
        @auth
            @if(auth()->user()->role === 'painter' && auth()->user()->id !== $modelProfile->user_id)
                <div class="border border-primary-200 rounded-lg p-6 bg-primary-50/50">
                    <h3 class="font-semibold text-secondary-900 mb-3">このモデルに質問する</h3>
                    <form action="{{ route('model-profile.questions.store', $modelProfile) }}" method="POST">
                        @csrf
                        <textarea name="question" rows="4" required
                            class="w-full px-3 py-2 border border-secondary-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('question') border-red-500 @enderror"
                            placeholder="気になることを質問してください（例：撮影の服装は指定されますか？）">{{ old('question') }}</textarea>
                        @error('question')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-secondary-600">回答はモデルのプロフィールに掲載されます。</p>
                        <button type="submit" class="mt-4 btn-primary">
                            質問を送信
                        </button>
                    </form>
                </div>
            @elseif(auth()->user()->role === 'model' && auth()->user()->id === $modelProfile->user_id)
                <div class="text-center py-6 bg-secondary-50 rounded-lg">
                    <p class="text-secondary-600 mb-2">届いた質問の確認と回答はこちらから</p>
                    <a href="{{ route('model.questions.index') }}" class="btn-primary">
                        あなたへの質問を管理
                    </a>
                </div>
            @elseif(auth()->user()->role !== 'painter')
                <div class="text-center py-6 text-secondary-600">
                    <p>質問機能は画家アカウントでのみ利用できます。</p>
                </div>
            @endif
        @else
            <div class="text-center py-6 bg-secondary-50 rounded-lg">
                <p class="text-secondary-600 mb-4">質問するにはログインが必要です</p>
                <a href="{{ route('login-register') }}" class="btn-primary">ログインする</a>
            </div>
        @endauth

        {{-- 未ログイン・ゲストでQ&Aもない場合 --}}
        @if((!isset($questions) || $questions->isEmpty()) && !Auth::check())
            <div class="text-center py-12">
                <p class="text-secondary-600">ログインすると、このモデルに質問を送信できます。</p>
            </div>
        @endif
    </div>
</div>
