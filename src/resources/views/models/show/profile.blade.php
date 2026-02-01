{{-- Profileタブのコンテンツ --}}
<div class="bg-white rounded-b-lg shadow-sm">
    <div class="px-6 py-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-secondary-900">> Profile / プロフィール</h2>
            <span class="text-sm text-secondary-600">
                最終更新: {{ $modelProfile->updated_at->format('n月j日') }} ({{ $modelProfile->updated_at->diffForHumans() }})
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- 左カラム：詳細情報 --}}
            <div class="lg:col-span-2 space-y-4">
                {{-- 参考価格 --}}
                <div class="bg-white border border-secondary-300 rounded-lg p-4">
                    <div class="text-sm text-secondary-600 mb-2">1日あたりの参考価格</div>
                    <div class="text-2xl font-bold text-primary-600">
                        @if($modelProfile->reward_min || $modelProfile->reward_max)
                            @if($modelProfile->reward_min && $modelProfile->reward_max)
                                {{ number_format($modelProfile->reward_min) }}円〜{{ number_format($modelProfile->reward_max) }}円
                            @elseif($modelProfile->reward_min)
                                {{ number_format($modelProfile->reward_min) }}円〜
                            @elseif($modelProfile->reward_max)
                                〜{{ number_format($modelProfile->reward_max) }}円
                            @endif
                        @else
                            未設定
                        @endif
                    </div>
                </div>

                {{-- 活動地域 --}}
                @if($modelProfile->prefecture)
                    <div class="bg-white border border-secondary-300 rounded-lg p-4">
                        <div class="text-sm text-secondary-600 mb-2">活動地域</div>
                        <div class="text-secondary-900 font-medium text-lg">{{ $modelProfile->prefecture }}</div>
                    </div>
                @endif

                {{-- コメント（自己紹介） --}}
                @if($modelProfile->bio)
                    <div class="bg-white border border-secondary-300 rounded-lg p-4">
                        <div class="text-sm text-secondary-600 mb-2">コメント</div>
                        <div class="text-secondary-900 whitespace-pre-wrap leading-relaxed">{{ $modelProfile->bio }}</div>
                    </div>
                @endif

                {{-- 趣味 --}}
                @if($modelProfile->experience)
                    <div class="bg-white border border-secondary-300 rounded-lg p-4">
                        <div class="text-sm text-secondary-600 mb-2">趣味</div>
                        <div class="text-secondary-900">{{ $modelProfile->experience }}</div>
                    </div>
                @endif

                {{-- 職業 --}}
                @if($modelProfile->portfolio_url)
                    <div class="bg-white border border-secondary-300 rounded-lg p-4">
                        <div class="text-sm text-secondary-600 mb-2">職業</div>
                        <div class="text-secondary-900">
                            <a href="{{ $modelProfile->portfolio_url }}" target="_blank" rel="noopener noreferrer" class="link-primary">
                                クリエイター関連
                            </a>
                        </div>
                    </div>
                @else
                    <div class="bg-white border border-secondary-300 rounded-lg p-4">
                        <div class="text-sm text-secondary-600 mb-2">職業</div>
                        <div class="text-secondary-900">未設定</div>
                    </div>
                @endif

                {{-- 本人確認 --}}
                <div class="bg-white border border-secondary-300 rounded-lg p-4">
                    <div class="text-sm text-secondary-600 mb-2">本人確認</div>
                    <div class="text-secondary-900 font-bold">未</div>
                </div>

                {{-- 参考条件 --}}
                <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4">
                    <div class="text-sm font-semibold text-yellow-800 mb-2">参考条件</div>
                    <div class="text-sm text-yellow-700">
                        水着撮影、露出度の高い衣装が含まれるお仕事には対応できない可能性があります。
                    </div>
                </div>

                {{-- モデル詳細情報 --}}
                <div class="bg-white border border-secondary-300 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-secondary-900 mb-4">モデル詳細情報</h3>
                    <div class="space-y-3">
                        @if($modelProfile->height)
                            <div>
                                <div class="text-sm text-secondary-600 mb-1">身長</div>
                                <div class="text-secondary-900 font-medium">{{ $modelProfile->height }} cm</div>
                            </div>
                        @endif

                        {{-- サイズ（バスト、ウエスト、ヒップ） --}}
                        <div>
                            <div class="text-sm text-secondary-600 mb-1">サイズ</div>
                            <div class="text-secondary-900 font-medium">
                                @if($modelProfile->bust && $modelProfile->waist && $modelProfile->hip)
                                    B:{{ $modelProfile->bust }} - W:{{ $modelProfile->waist }} - H:{{ $modelProfile->hip }}
                                @else
                                    未設定
                                @endif
                            </div>
                        </div>

                        @if($modelProfile->body_type)
                            <div>
                                <div class="text-sm text-secondary-600 mb-1">体形</div>
                                <div class="text-secondary-900 font-medium">{{ $modelProfile->body_type }}</div>
                            </div>
                        @endif

                        @if($modelProfile->hair_type)
                            <div>
                                <div class="text-sm text-secondary-600 mb-1">髪型</div>
                                <div class="text-secondary-900 font-medium">
                                    @if($modelProfile->hair_type === 'short')ショートヘアー
                                    @elseif($modelProfile->hair_type === 'medium')ミディアムヘアー
                                    @elseif($modelProfile->hair_type === 'long')ロングヘアー
                                    @elseif($modelProfile->hair_type === 'semi_long')セミロングヘアー
                                    @elseその他
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- 洋服のサイズ --}}
                        <div>
                            <div class="text-sm text-secondary-600 mb-1">洋服のサイズ</div>
                            <div class="text-secondary-900 font-medium">
                                @if($modelProfile->clothing_size)
                                    {{ $modelProfile->clothing_size }} 号
                                @else
                                    未設定
                                @endif
                            </div>
                        </div>

                        {{-- 靴のサイズ --}}
                        <div>
                            <div class="text-sm text-secondary-600 mb-1">靴のサイズ</div>
                            <div class="text-secondary-900 font-medium">
                                @if($modelProfile->shoe_size)
                                    {{ $modelProfile->shoe_size }} cm
                                @else
                                    未設定
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 違反報告 --}}
                <div class="bg-white border border-secondary-300 rounded-lg p-4">
                    <a href="#" class="text-sm text-red-600 hover:text-red-700 underline">違反報告をする</a>
                </div>
            </div>

            {{-- 右カラム：メイン画像とアクション --}}
            <div class="lg:col-span-1">
                {{-- メイン画像 --}}
                @php
                    $mainImage = $modelProfile->mainImage();
                    $displayImage = $mainImage ? $mainImage->image_path : $modelProfile->profile_image_path;
                @endphp
                @if($displayImage)
                    <div class="mb-6">
                        <img src="{{ Storage::url($displayImage) }}"
                             alt="{{ $modelProfile->display_name }}"
                             class="w-full h-auto object-cover rounded-lg shadow-md">
                    </div>
                @endif

                {{-- アクションボタン --}}
                <div class="space-y-3">
                    @auth
                        @if($isFavorite)
                            <form action="{{ route('favorites.destroy.model', $modelProfile) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full px-4 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                    お気に入り解除
                                </button>
                            </form>
                        @else
                            <form action="{{ route('favorites.store.model', $modelProfile) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                        class="w-full px-4 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors">
                                    お気に入りに追加
                                </button>
                            </form>
                        @endif

                        @if(auth()->user()->role === 'painter')
                            <a href="{{ route('painter.jobs.create', ['model_id' => $modelProfile->id]) }}"
                               class="block w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors text-center">
                                このモデルに依頼する
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login-register') }}"
                           class="block w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors text-center">
                            このモデルに依頼したい（ログイン）
                        </a>
                    @endauth
                </div>

                {{-- タグ --}}
                @if(!empty($modelProfile->style_tags))
                    <div class="mt-6">
                        <div class="text-sm text-secondary-600 mb-2">スタイルタグ</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($modelProfile->style_tags as $tag)
                                <span class="badge badge-secondary">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
