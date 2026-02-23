@extends('layouts.app')

@section('content')
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
  <div class="section-panel mb-6">
    <div class="section-panel-inner px-8 sm:px-10 lg:px-12">
      <form method="GET" action="{{ route('models.index') }}" class="space-y-6">
        <div class="border border-secondary-300 rounded-lg p-6">
          <div class="space-y-4">
            {{-- キーワード --}}
            <div class="flex items-center gap-4">
              <label for="keyword" class="form-label w-32 flex-shrink-0 text-left">
                キーワード
              </label>
              <input type="text"
                     id="keyword"
                     name="keyword"
                     value="{{ request('keyword') }}"
                     placeholder="表示名で検索"
                     class="form-input flex-1">
            </div>

            {{-- 性別・年齢（横並び） --}}
            <div class="flex items-center gap-4">
              <label class="form-label w-32 flex-shrink-0 text-left">
                性別
              </label>
              <div class="flex gap-4 flex-1">
                <label class="flex items-center">
                  <input type="radio"
                         name="gender"
                         value="male"
                         {{ request('gender') === 'male' ? 'checked' : '' }}
                         class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                  <span class="ml-2 text-sm text-secondary-700">男性</span>
                </label>
                <label class="flex items-center">
                  <input type="radio"
                         name="gender"
                         value="female"
                         {{ request('gender') === 'female' ? 'checked' : '' }}
                         class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                  <span class="ml-2 text-sm text-secondary-700">女性</span>
                </label>
                <label class="flex items-center">
                  <input type="radio"
                         name="gender"
                         value="other"
                         {{ request('gender') === 'other' ? 'checked' : '' }}
                         class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                  <span class="ml-2 text-sm text-secondary-700">その他</span>
                </label>
              </div>
              <label class="form-label w-32 flex-shrink-0 text-left">
                年齢
              </label>
              <div class="flex gap-2 flex-1">
                <input type="number"
                       id="age_min"
                       name="age_min"
                       value="{{ request('age_min') }}"
                       placeholder="最小"
                       min="1"
                       max="150"
                       class="form-input">
                <span class="self-center text-secondary-600">〜</span>
                <input type="number"
                       id="age_max"
                       name="age_max"
                       value="{{ request('age_max') }}"
                       placeholder="最大"
                       min="1"
                       max="150"
                       class="form-input">
              </div>
            </div>

            {{-- 身長・体重（横並び） --}}
            <div class="flex items-center gap-4">
              <label class="form-label w-32 flex-shrink-0 text-left">
                身長
              </label>
              <div class="flex gap-2 flex-1">
                <input type="number"
                       id="height_min"
                       name="height_min"
                       value="{{ request('height_min') }}"
                       placeholder="最小"
                       min="1"
                       max="300"
                       class="form-input">
                <span class="self-center text-secondary-600">〜</span>
                <input type="number"
                       id="height_max"
                       name="height_max"
                       value="{{ request('height_max') }}"
                       placeholder="最大"
                       min="1"
                       max="300"
                       class="form-input">
                <span class="self-center text-secondary-600 text-sm">cm</span>
              </div>
              <label class="form-label w-32 flex-shrink-0 text-left">
                体重
              </label>
              <div class="flex gap-2 flex-1">
                <input type="number"
                       id="weight_min"
                       name="weight_min"
                       value="{{ request('weight_min') }}"
                       placeholder="最小"
                       min="1"
                       max="300"
                       class="form-input">
                <span class="self-center text-secondary-600">〜</span>
                <input type="number"
                       id="weight_max"
                       name="weight_max"
                       value="{{ request('weight_max') }}"
                       placeholder="最大"
                       min="1"
                       max="300"
                       class="form-input">
                <span class="self-center text-secondary-600 text-sm">kg</span>
              </div>
            </div>

            {{-- 参考価格（報酬下限） --}}
            <div class="flex items-center gap-4">
              <label for="reward_min" class="form-label w-32 flex-shrink-0 text-left">
                参考価格
              </label>
              <input type="number"
                     id="reward_min"
                     name="reward_min"
                     value="{{ request('reward_min') }}"
                     placeholder="例：5000"
                     min="0"
                     class="form-input flex-1">
              <span class="text-sm text-secondary-600 ml-2">円</span>
            </div>

            {{-- 体型（全て表示） --}}
            <div class="flex items-start gap-4">
              <label class="form-label w-32 flex-shrink-0 text-left pt-2">
                体型
              </label>
              <div class="flex-1 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($bodyTypes as $bodyType)
                  <label class="flex items-center">
                    <input type="checkbox"
                           name="body_type[]"
                           value="{{ $bodyType }}"
                           {{ in_array($bodyType, (array)request('body_type', [])) ? 'checked' : '' }}
                           class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                    <span class="ml-2 text-sm text-secondary-700">{{ $bodyType }}</span>
                  </label>
                @endforeach
              </div>
            </div>

            {{-- タグ（全て表示） --}}
            <div class="flex items-start gap-4">
              <label class="form-label w-32 flex-shrink-0 text-left pt-2">
                タグ
              </label>
              <div class="flex-1 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($allTags as $tag)
                  <label class="flex items-center">
                    <input type="checkbox"
                           name="tag[]"
                           value="{{ $tag }}"
                           {{ in_array($tag, (array)request('tag', [])) ? 'checked' : '' }}
                           class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                    <span class="ml-2 text-sm text-secondary-700">{{ $tag }}</span>
                  </label>
                @endforeach
              </div>
            </div>
          </div>

          {{-- ボタン --}}
          <div class="flex gap-2 justify-end mt-6 pt-4 border-t border-secondary-200">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
              検索
            </button>
            <button type="button" onclick="resetSearchForm();" class="px-6 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
              リセット
            </button>
          </div>
        </div>
    </form>
    </div>
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
            <a href="{{ route('models.show', $model) }}" class="card card-hover overflow-hidden relative">
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
              @auth
              <div class="absolute top-1 right-1 z-10" onclick="event.stopPropagation();">
                @if($isFavModel)
                  <form method="POST" action="{{ route('favorites.destroy.model', $model) }}" class="inline">@csrf @method('DELETE')
                    <button type="submit" class="p-1 rounded-full bg-white/90 shadow hover:bg-red-50 text-red-500" title="お気に入り解除">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                    </button>
                  </form>
                @else
                  <form method="POST" action="{{ route('favorites.store.model', $model) }}" class="inline">@csrf
                    <button type="submit" class="p-1 rounded-full bg-white/90 shadow hover:bg-red-50 text-gray-400 hover:text-red-500" title="お気に入りに追加">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                  </form>
                @endif
              </div>
              @endauth

              {{-- 情報 --}}
              <div class="card-body p-2">
                <div class="card-title mb-0.5 text-sm">
                  {{ $model->display_name }}
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
