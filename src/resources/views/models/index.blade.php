@extends('layouts.app')

@section('content')

{{-- ページヘッダー（背景に名画が静かに循環） --}}
<div class="page-header">
    <div class="art-bg-stage">
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/pearl-earring.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/great-wave.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/starry-night.jpg') }}');"></div>
        <div class="art-bg-veil"></div>
    </div>
    <div class="page-header-inner">
        <p class="page-header-subtitle">Models</p>
        <h1 class="page-header-title mt-2">モデル一覧</h1>
        <p class="text-secondary-500 text-sm mt-3">人物画・ポートレート制作にご協力いただけるモデルをお探しください。</p>
    </div>
</div>

<div class="page">
  <script>
    function resetSearchForm() {
      ['keyword','gender','age_min','age_max','height_min','height_max','weight_min','weight_max','reward_min'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
      });
      document.querySelectorAll('input[name="body_type[]"]').forEach(cb => cb.checked = false);
    }
  </script>

  {{-- 検索フォーム --}}
  <div class="bg-canvas-50 rounded-xl border border-secondary-200 p-6 mb-8">
    <form method="GET" action="{{ route('models.index') }}" id="model-search-form">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- キーワード --}}
        <div class="lg:col-span-2">
          <label for="keyword" class="form-label">キーワード</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}"
                   placeholder="表示名で検索" class="form-input pl-10">
          </div>
        </div>

        {{-- 性別 --}}
        <div>
          <label for="gender" class="form-label">性別</label>
          <select id="gender" name="gender" class="form-input">
            <option value="">すべて</option>
            @foreach([['male','男性'], ['female','女性'], ['other','その他']] as $g)
              <option value="{{ $g[0] }}" {{ request('gender') === $g[0] ? 'selected' : '' }}>{{ $g[1] }}</option>
            @endforeach
          </select>
        </div>

        {{-- 参考価格 --}}
        <div>
          <label for="reward_min" class="form-label">参考価格（円〜）</label>
          <input type="number" id="reward_min" name="reward_min" value="{{ request('reward_min') }}"
                 placeholder="例：5000" min="0" class="form-input">
        </div>

        {{-- 年齢 --}}
        <div class="sm:col-span-2">
          <label class="form-label">年齢</label>
          <div class="flex items-center gap-2">
            <input type="number" id="age_min" name="age_min" value="{{ request('age_min') }}"
                   placeholder="最小" min="1" max="150" class="form-input min-w-0 flex-1">
            <span class="text-secondary-400 text-sm shrink-0">〜</span>
            <input type="number" id="age_max" name="age_max" value="{{ request('age_max') }}"
                   placeholder="最大" min="1" max="150" class="form-input min-w-0 flex-1">
            <span class="text-sm text-secondary-500 shrink-0">歳</span>
          </div>
        </div>

        {{-- 身長 --}}
        <div class="sm:col-span-2">
          <label class="form-label">身長</label>
          <div class="flex items-center gap-2">
            <input type="number" id="height_min" name="height_min" value="{{ request('height_min') }}"
                   placeholder="最小" min="1" max="300" class="form-input min-w-0 flex-1">
            <span class="text-secondary-400 text-sm shrink-0">〜</span>
            <input type="number" id="height_max" name="height_max" value="{{ request('height_max') }}"
                   placeholder="最大" min="1" max="300" class="form-input min-w-0 flex-1">
            <span class="text-sm text-secondary-500 shrink-0">cm</span>
          </div>
        </div>

        {{-- 体重 --}}
        <div class="sm:col-span-2">
          <label class="form-label">体重</label>
          <div class="flex items-center gap-2">
            <input type="number" id="weight_min" name="weight_min" value="{{ request('weight_min') }}"
                   placeholder="最小" min="1" max="300" class="form-input min-w-0 flex-1">
            <span class="text-secondary-400 text-sm shrink-0">〜</span>
            <input type="number" id="weight_max" name="weight_max" value="{{ request('weight_max') }}"
                   placeholder="最大" min="1" max="300" class="form-input min-w-0 flex-1">
            <span class="text-sm text-secondary-500 shrink-0">kg</span>
          </div>
        </div>

        {{-- 体型 --}}
        <div class="sm:col-span-2 lg:col-span-4">
          <label class="form-label">体型</label>
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-x-3 gap-y-2">
            @foreach($bodyTypes as $bodyType)
              <label class="flex items-center cursor-pointer min-w-0">
                <input type="checkbox" name="body_type[]" value="{{ $bodyType }}"
                       {{ in_array($bodyType, (array)request('body_type', [])) ? 'checked' : '' }}
                       class="border-secondary-400 text-secondary-900 focus:ring-secondary-700 shrink-0">
                <span class="ml-2 text-sm text-secondary-700 truncate">{{ $bodyType }}</span>
              </label>
            @endforeach
          </div>
        </div>

        {{-- ボタン --}}
        <div class="sm:col-span-2 lg:col-span-4 flex items-end gap-3 sm:justify-end">
          <button type="submit" class="btn-primary flex-1 sm:flex-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            検索
          </button>
          <button type="button" onclick="resetSearchForm()" class="btn-secondary flex-1 sm:flex-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            リセット
          </button>
        </div>

      </div>
    </form>
  </div>

  {{-- 検索結果数 --}}
  @if(request()->hasAny(['keyword', 'prefecture', 'gender', 'age_min', 'age_max', 'online_available', 'reward_min']))
    <div class="mb-4 text-sm text-secondary-600">
      検索結果: {{ $models->total() }}件
    </div>
  @endif

  @if($models->count() === 0)
    <div class="card">
      <div class="card-body text-center">
        <p class="text-secondary-600">公開中のモデルがまだいません。</p>
      </div>
    </div>
  @else
    <div class="section-panel">
      <div class="section-panel-inner px-8 sm:px-10 lg:px-12">
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-6 gap-3">
          @foreach($models as $model)
            @php $isFavModel = in_array($model->id, $favoriteModelIds ?? []); @endphp
            <div class="relative rounded-xl border-2 border-secondary-200 bg-white shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            {{-- お気に入りボタン（リンクの外に配置） --}}
            <div class="absolute top-1 right-1 z-20">
                <x-favorite-button type="model" :id="$model->id" :favorited="$isFavModel" />
            </div>
            <a href="{{ route('models.show', $model) }}" class="block relative">
              {{-- 画像 --}}
              <div class="aspect-[3/4] card-media relative">
                @if($model->profile_image_path)
                  <img src="{{ Storage::url($model->profile_image_path) }}"
                       alt="{{ $model->display_name }}"
                       class="w-full h-full object-cover">
                @else
                  <div class="w-full h-full flex items-center justify-center text-secondary-400">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                  </div>
                @endif
              </div>

              {{-- 情報 --}}
              <div class="p-2">
                <div class="card-title mb-0.5 text-sm flex items-center gap-1">
                  <span class="truncate">{{ $model->display_name }}</span>
                  {{-- 本人確認済みアイコン（一旦停止）
                  @if($model->identity_verified)
                    <svg class="w-3.5 h-3.5 text-success-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" title="本人確認済み"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                  @endif
                  --}}
                </div>

                <div class="card-meta mb-1 text-xs">
                  @if($model->prefecture)
                    <span>{{ $model->prefecture }}</span>
                  @endif

                  @if($model->age)
                    <span class="ml-1">{{ $model->age }}歳</span>
                  @endif

                  @if($model->gender)
                    <span class="ml-1">
                      @if($model->gender === 'male')男性
                      @elseif($model->gender === 'female')女性
                      @elseその他
                      @endif
                    </span>
                  @endif
                </div>

                @if($model->reward_min || $model->reward_max)
                  <div class="card-price mb-1 text-xs">
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

              </div>
            </a>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="mt-8">
      {{ $models->links('vendor.pagination.block-ten') }}
    </div>
  @endif
</div>
@endsection
