@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
  <h1 class="text-2xl font-bold mb-6">モデル一覧</h1>

  {{-- 検索フォーム --}}
  <div class="bg-white border rounded-lg p-6 mb-6">
    <form method="GET" action="{{ route('models.index') }}" class="space-y-4">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- キーワード検索 --}}
        <div>
          <label for="keyword" class="block text-sm font-medium text-gray-700 mb-1">
            キーワード
          </label>
          <input type="text"
                 id="keyword"
                 name="keyword"
                 value="{{ request('keyword') }}"
                 placeholder="表示名で検索"
                 class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- 都道府県 --}}
        <div>
          <label for="prefecture" class="block text-sm font-medium text-gray-700 mb-1">
            都道府県
          </label>
          <select id="prefecture"
                  name="prefecture"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <option value="">すべて</option>
            @foreach($prefectures as $pref)
              <option value="{{ $pref }}" {{ request('prefecture') === $pref ? 'selected' : '' }}>
                {{ $pref }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- 性別 --}}
        <div>
          <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">
            性別
          </label>
          <select id="gender"
                  name="gender"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <option value="">すべて</option>
            <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>男性</option>
            <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>女性</option>
            <option value="other" {{ request('gender') === 'other' ? 'selected' : '' }}>その他</option>
          </select>
        </div>

        {{-- 髪型 --}}
        <div>
          <label for="hair_type" class="block text-sm font-medium text-gray-700 mb-1">
            髪型
          </label>
          <select id="hair_type"
                  name="hair_type"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <option value="">すべて</option>
            <option value="short" {{ request('hair_type') === 'short' ? 'selected' : '' }}>ショート</option>
            <option value="medium" {{ request('hair_type') === 'medium' ? 'selected' : '' }}>ミディアム</option>
            <option value="long" {{ request('hair_type') === 'long' ? 'selected' : '' }}>ロング</option>
            <option value="other" {{ request('hair_type') === 'other' ? 'selected' : '' }}>その他</option>
          </select>
        </div>

        {{-- 年齢範囲 --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            年齢
          </label>
          <div class="flex gap-2">
            <input type="number"
                   id="age_min"
                   name="age_min"
                   value="{{ request('age_min') }}"
                   placeholder="最小"
                   min="1"
                   max="150"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <span class="self-center">〜</span>
            <input type="number"
                   id="age_max"
                   name="age_max"
                   value="{{ request('age_max') }}"
                   placeholder="最大"
                   min="1"
                   max="150"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- タグ --}}
        <div>
          <label for="tag" class="block text-sm font-medium text-gray-700 mb-1">
            タグ
          </label>
          <select id="tag"
                  name="tag"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <option value="">すべて</option>
            @foreach($allTags as $tag)
              <option value="{{ $tag }}" {{ request('tag') === $tag ? 'selected' : '' }}>
                {{ $tag }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- オンライン対応 --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            オンライン対応
          </label>
          <label class="flex items-center">
            <input type="checkbox"
                   name="online_available"
                   value="1"
                   {{ request('online_available') ? 'checked' : '' }}
                   class="mr-2">
            <span class="text-sm">オンライン対応可</span>
          </label>
        </div>

        {{-- 報酬下限 --}}
        <div>
          <label for="reward_min" class="block text-sm font-medium text-gray-700 mb-1">
            報酬下限 (円)
          </label>
          <input type="number"
                 id="reward_min"
                 name="reward_min"
                 value="{{ request('reward_min') }}"
                 placeholder="例：5000"
                 min="0"
                 class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- ソート --}}
        <div>
          <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">
            並び替え
          </label>
          <select id="sort"
                  name="sort"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>新着順</option>
            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>古い順</option>
            <option value="reward_high" {{ request('sort') === 'reward_high' ? 'selected' : '' }}>報酬高い順</option>
            <option value="reward_low" {{ request('sort') === 'reward_low' ? 'selected' : '' }}>報酬低い順</option>
          </select>
        </div>
      </div>

      <div class="flex gap-2">
        <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          検索
        </button>
        <a href="{{ route('models.index') }}"
           class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
          リセット
        </a>
      </div>
    </form>
  </div>

  {{-- 検索結果数 --}}
  @if(request()->hasAny(['keyword', 'prefecture', 'gender', 'age_min', 'age_max', 'tag', 'online_available', 'reward_min']))
    <div class="mb-4 text-sm text-gray-600">
      検索結果: {{ $models->total() }}件
    </div>
  @endif

  @if($models->count() === 0)
    <p class="text-gray-600">公開中のモデルがまだいません。</p>
  @else
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      @foreach($models as $model)
        <a href="{{ route('models.show', $model) }}" class="block border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
          {{-- 画像 --}}
          <div class="aspect-[3/4] bg-gray-200 overflow-hidden">
            @if($model->profile_image_path)
              <img src="{{ Storage::url($model->profile_image_path) }}"
                   alt="{{ $model->display_name }}"
                   class="w-full h-full object-cover">
            @else
              <div class="w-full h-full flex items-center justify-center text-gray-400">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
              </div>
            @endif
          </div>

          {{-- 情報 --}}
          <div class="p-4">
            <div class="font-semibold text-lg mb-1">
              {{ $model->display_name }}
            </div>

            <div class="text-sm text-gray-600 mb-2">
              @if($model->prefecture)
                <span>{{ $model->prefecture }}</span>
              @endif

              @if($model->age)
                <span class="ml-2">{{ $model->age }}歳</span>
              @endif

              @if($model->gender)
                <span class="ml-2">
                  @if($model->gender === 'male')男性
                  @elseif($model->gender === 'female')女性
                  @elseその他
                  @endif
                </span>
              @endif
            </div>

            @if($model->reward_min || $model->reward_max)
              <div class="text-sm font-semibold text-blue-600 mb-2">
                参考価格：
                @if($model->reward_min && $model->reward_max)
                  {{ number_format($model->reward_min) }}円〜
                @elseif($model->reward_min)
                  {{ number_format($model->reward_min) }}円〜
                @elseif($model->reward_max)
                  〜{{ number_format($model->reward_max) }}円
                @endif
              </div>
            @endif

            @php $tags = $model->style_tags ?? []; @endphp
            @if(count($tags) > 0)
              <div class="mt-2 flex flex-wrap gap-1">
                @foreach(array_slice($tags, 0, 3) as $tag)
                  <span class="text-xs bg-gray-100 text-gray-700 rounded px-2 py-1">{{ $tag }}</span>
                @endforeach
                @if(count($tags) > 3)
                  <span class="text-xs text-gray-500">+{{ count($tags) - 3 }}</span>
                @endif
              </div>
            @endif
          </div>
        </a>
      @endforeach
    </div>

    <div class="mt-6">
      {{ $models->links() }}
    </div>
  @endif
</div>
@endsection
