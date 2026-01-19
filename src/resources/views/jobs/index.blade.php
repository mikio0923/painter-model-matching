@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
  <h1 class="section-title">依頼一覧</h1>

  {{-- 検索フォーム --}}
  <div class="card mb-6">
    <div class="card-body">
      <form method="GET" action="{{ route('jobs.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          {{-- キーワード検索 --}}
          <div>
            <label for="keyword" class="form-label">
              キーワード
            </label>
            <input type="text" 
                   id="keyword" 
                   name="keyword" 
                   value="{{ request('keyword') }}"
                   placeholder="タイトル・説明で検索"
                   class="form-input">
          </div>

          {{-- 都道府県 --}}
          <div>
            <label for="prefecture" class="form-label">
              都道府県
            </label>
            <select id="prefecture" 
                    name="prefecture"
                    class="form-input">
              <option value="">すべて</option>
              @foreach($prefectures as $pref)
                <option value="{{ $pref }}" {{ request('prefecture') === $pref ? 'selected' : '' }}>
                  {{ $pref }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- 場所タイプ --}}
          <div>
            <label for="location_type" class="form-label">
              場所
            </label>
            <select id="location_type" 
                    name="location_type"
                    class="form-input">
              <option value="">すべて</option>
              <option value="online" {{ request('location_type') === 'online' ? 'selected' : '' }}>オンライン</option>
              <option value="offline" {{ request('location_type') === 'offline' ? 'selected' : '' }}>オフライン</option>
            </select>
          </div>

          {{-- 報酬範囲 --}}
          <div>
            <label class="form-label">
              報酬 (円)
            </label>
            <div class="flex gap-2">
              <input type="number" 
                     id="reward_min" 
                     name="reward_min" 
                     value="{{ request('reward_min') }}"
                     placeholder="最小"
                     min="0"
                     class="form-input">
              <span class="self-center text-secondary-600">〜</span>
              <input type="number" 
                     id="reward_max" 
                     name="reward_max" 
                     value="{{ request('reward_max') }}"
                     placeholder="最大"
                     min="0"
                     class="form-input">
            </div>
          </div>
        </div>

        <div class="flex gap-2">
          <button type="submit" class="btn-primary">
            検索
          </button>
          <a href="{{ route('jobs.index') }}" class="btn-secondary">
            リセット
          </a>
        </div>
      </form>
    </div>
  </div>

  {{-- 検索結果数 --}}
  @if(request()->hasAny(['keyword', 'prefecture', 'location_type', 'reward_min', 'reward_max']))
    <div class="mb-4 text-sm text-secondary-600">
      検索結果: {{ $jobs->total() }}件
    </div>
  @endif

  @if($jobs->count() === 0)
    <div class="card">
      <div class="card-body text-center">
        <p class="text-secondary-600">該当する依頼がありません</p>
      </div>
    </div>
  @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($jobs as $job)
        <a href="{{ route('jobs.show', $job) }}" class="card">
          <div class="card-body">
            <h2 class="text-xl font-semibold mb-3 text-secondary-900">{{ $job->title }}</h2>
            
            <p class="text-secondary-600 text-sm mb-4 line-clamp-3">
              {{ mb_strlen($job->description) > 150 ? mb_substr($job->description, 0, 150) . '...' : $job->description }}
            </p>

            <div class="space-y-2 text-sm">
              <div class="flex items-center justify-between">
                <span class="text-secondary-600">投稿者</span>
                <span class="font-semibold text-secondary-900">{{ $job->painter->name }}</span>
              </div>

              <div class="flex items-center justify-between">
                <span class="text-secondary-600">場所</span>
                <span class="text-secondary-900">
                  {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                  @if($job->prefecture)
                    ({{ $job->prefecture }})
                  @endif
                </span>
              </div>

              @if($job->reward_amount)
                <div class="flex items-center justify-between">
                  <span class="text-secondary-600">報酬</span>
                  <span class="font-semibold text-primary-600">
                    {{ number_format($job->reward_amount) }}円
                    @if($job->reward_unit === 'per_hour')
                      /時間
                    @else
                      /回
                    @endif
                  </span>
                </div>
              @endif

              @if($job->scheduled_date)
                <div class="flex items-center justify-between">
                  <span class="text-secondary-600">日程</span>
                  <span class="text-secondary-900">{{ $job->scheduled_date->format('Y/m/d') }}</span>
                </div>
              @endif

              @if($job->apply_deadline)
                <div class="flex items-center justify-between">
                  <span class="text-secondary-600">応募締切</span>
                  <span class="text-secondary-900">{{ $job->apply_deadline->format('Y/m/d') }}</span>
                </div>
              @endif
            </div>

            <div class="mt-4 pt-4 border-t border-secondary-200">
              <div class="flex items-center justify-between text-xs text-secondary-500">
                <span>投稿日: {{ $job->created_at->format('Y/m/d') }}</span>
                @if($job->applications()->count() > 0)
                  <span>応募: {{ $job->applications()->count() }}件</span>
                @endif
              </div>
            </div>
          </div>
        </a>
      @endforeach
    </div>

    <div class="mt-6">
      {{ $jobs->links() }}
    </div>
  @endif
</div>
@endsection

