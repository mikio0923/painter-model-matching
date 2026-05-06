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
      // キーワード
      document.getElementById('keyword').value = '';
      // 性別（ラジオボタン）
      const genderRadios = document.querySelectorAll('input[name="gender"]');
      genderRadios.forEach(radio => {
        radio.checked = false;
      });
      // 年齢
      document.getElementById('age_min').value = '';
      document.getElementById('age_max').value = '';
      // 身長
      document.getElementById('height_min').value = '';
      document.getElementById('height_max').value = '';
      // 体重
      document.getElementById('weight_min').value = '';
      document.getElementById('weight_max').value = '';
      // 参考価格
      document.getElementById('reward_min').value = '';
      // 体型（チェックボックス）
      const bodyTypeCheckboxes = document.querySelectorAll('input[name="body_type[]"]');
      bodyTypeCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
      });
      // タグ（チェックボックス）
      const tagCheckboxes = document.querySelectorAll('input[name="tag[]"]');
      tagCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
      });
    }
  </script>

  {{-- 検索フォーム --}}
  <div class="border border-secondary-200 bg-canvas-50 mb-8 p-4 sm:p-6">
    <form method="GET" action="{{ route('models.index') }}" class="space-y-5">

      {{-- キーワード --}}
      <div class="grid grid-cols-1 sm:grid-cols-[8rem_1fr] sm:items-center gap-2 sm:gap-4">
        <label for="keyword" class="text-[10px] uppercase tracking-[0.25em] text-secondary-500">キーワード</label>
        <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}"
               placeholder="表示名で検索"
               class="form-input">
      </div>

      {{-- 性別 --}}
      <div class="grid grid-cols-1 sm:grid-cols-[8rem_1fr] sm:items-center gap-2 sm:gap-4">
        <label class="text-[10px] uppercase tracking-[0.25em] text-secondary-500">性別</label>
        <div class="flex flex-wrap gap-x-5 gap-y-2">
          @foreach([['male','男性'], ['female','女性'], ['other','その他']] as $g)
            <label class="flex items-center cursor-pointer">
              <input type="radio" name="gender" value="{{ $g[0] }}"
                     {{ request('gender') === $g[0] ? 'checked' : '' }}
                     class="border-secondary-400 text-secondary-900 focus:ring-secondary-700">
              <span class="ml-2 text-sm text-secondary-700">{{ $g[1] }}</span>
            </label>
          @endforeach
        </div>
      </div>

      {{-- 年齢 --}}
      <div class="grid grid-cols-1 sm:grid-cols-[8rem_1fr] sm:items-center gap-2 sm:gap-4">
        <label class="text-[10px] uppercase tracking-[0.25em] text-secondary-500">年齢</label>
        <div class="flex items-center gap-2">
          <input type="number" id="age_min" name="age_min" value="{{ request('age_min') }}"
                 placeholder="最小" min="1" max="150" class="form-input min-w-0 flex-1">
          <span class="text-secondary-500 shrink-0">〜</span>
          <input type="number" id="age_max" name="age_max" value="{{ request('age_max') }}"
                 placeholder="最大" min="1" max="150" class="form-input min-w-0 flex-1">
          <span class="text-sm text-secondary-500 shrink-0">歳</span>
        </div>
      </div>

      {{-- 身長 --}}
      <div class="grid grid-cols-1 sm:grid-cols-[8rem_1fr] sm:items-center gap-2 sm:gap-4">
        <label class="text-[10px] uppercase tracking-[0.25em] text-secondary-500">身長</label>
        <div class="flex items-center gap-2">
          <input type="number" id="height_min" name="height_min" value="{{ request('height_min') }}"
                 placeholder="最小" min="1" max="300" class="form-input min-w-0 flex-1">
          <span class="text-secondary-500 shrink-0">〜</span>
          <input type="number" id="height_max" name="height_max" value="{{ request('height_max') }}"
                 placeholder="最大" min="1" max="300" class="form-input min-w-0 flex-1">
          <span class="text-sm text-secondary-500 shrink-0">cm</span>
        </div>
      </div>

      {{-- 体重 --}}
      <div class="grid grid-cols-1 sm:grid-cols-[8rem_1fr] sm:items-center gap-2 sm:gap-4">
        <label class="text-[10px] uppercase tracking-[0.25em] text-secondary-500">体重</label>
        <div class="flex items-center gap-2">
          <input type="number" id="weight_min" name="weight_min" value="{{ request('weight_min') }}"
                 placeholder="最小" min="1" max="300" class="form-input min-w-0 flex-1">
          <span class="text-secondary-500 shrink-0">〜</span>
          <input type="number" id="weight_max" name="weight_max" value="{{ request('weight_max') }}"
                 placeholder="最大" min="1" max="300" class="form-input min-w-0 flex-1">
          <span class="text-sm text-secondary-500 shrink-0">kg</span>
        </div>
      </div>

      {{-- 参考価格 --}}
      <div class="grid grid-cols-1 sm:grid-cols-[8rem_1fr] sm:items-center gap-2 sm:gap-4">
        <label for="reward_min" class="text-[10px] uppercase tracking-[0.25em] text-secondary-500">参考価格</label>
        <div class="flex items-center gap-2">
          <input type="number" id="reward_min" name="reward_min" value="{{ request('reward_min') }}"
                 placeholder="例：5000" min="0" class="form-input min-w-0 flex-1">
          <span class="text-sm text-secondary-500 shrink-0">円</span>
        </div>
      </div>

      {{-- 体型 --}}
      <div class="grid grid-cols-1 sm:grid-cols-[8rem_1fr] gap-2 sm:gap-4 sm:items-start">
        <label class="text-[10px] uppercase tracking-[0.25em] text-secondary-500 sm:pt-2">体型</label>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-x-3 gap-y-2">
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

      {{-- タグ --}}
      <div class="grid grid-cols-1 sm:grid-cols-[8rem_1fr] gap-2 sm:gap-4 sm:items-start">
        <label class="text-[10px] uppercase tracking-[0.25em] text-secondary-500 sm:pt-2">タグ</label>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-x-3 gap-y-2">
          @foreach($allTags as $tag)
            <label class="flex items-center cursor-pointer min-w-0">
              <input type="checkbox" name="tag[]" value="{{ $tag }}"
                     {{ in_array($tag, (array)request('tag', [])) ? 'checked' : '' }}
                     class="border-secondary-400 text-secondary-900 focus:ring-secondary-700 shrink-0">
              <span class="ml-2 text-sm text-secondary-700 truncate">{{ $tag }}</span>
            </label>
          @endforeach
        </div>
      </div>

      {{-- ボタン --}}
      <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-5 border-t border-secondary-200">
        <button type="button" onclick="resetSearchForm();"
                class="order-2 sm:order-1 px-6 py-2.5 border border-secondary-400 text-secondary-700 text-xs uppercase tracking-[0.2em] hover:bg-secondary-100 transition-colors duration-200">
          Reset
        </button>
        <button type="submit"
                class="order-1 sm:order-2 px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.2em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
          Search
        </button>
      </div>
    </form>
  </div>

  {{-- 検索結果数 --}}
  @if(request()->hasAny(['keyword', 'prefecture', 'gender', 'age_min', 'age_max', 'tag', 'online_available', 'reward_min']))
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
                  @if($model->identity_verified)
                    <svg class="w-3.5 h-3.5 text-success-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" title="本人確認済み"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                  @endif
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

                @php $tags = $model->style_tags ?? []; @endphp
                @if(count($tags) > 0)
                  <div class="mt-1 flex flex-wrap gap-0.5">
                    @foreach(array_slice($tags, 0, 2) as $tag)
                      <span class="badge badge-secondary text-xs px-1 py-0">{{ $tag }}</span>
                    @endforeach
                    @if(count($tags) > 2)
                      <span class="text-xs text-secondary-500">+{{ count($tags) - 2 }}</span>
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

    <div class="mt-6">
      {{ $models->links() }}
    </div>
  @endif
</div>
@endsection
