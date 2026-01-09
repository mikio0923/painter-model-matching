@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
  <h1 class="text-2xl font-bold mb-6">依頼一覧</h1>

  {{-- 検索フォーム --}}
  <div class="bg-white border rounded-lg p-6 mb-6">
    <form method="GET" action="{{ route('jobs.index') }}" class="space-y-4">
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
                 placeholder="タイトル・説明で検索"
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

        {{-- 場所タイプ --}}
        <div>
          <label for="location_type" class="block text-sm font-medium text-gray-700 mb-1">
            場所
          </label>
          <select id="location_type" 
                  name="location_type"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <option value="">すべて</option>
            <option value="online" {{ request('location_type') === 'online' ? 'selected' : '' }}>オンライン</option>
            <option value="offline" {{ request('location_type') === 'offline' ? 'selected' : '' }}>オフライン</option>
          </select>
        </div>

        {{-- 報酬範囲 --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            報酬 (円)
          </label>
          <div class="flex gap-2">
            <input type="number" 
                   id="reward_min" 
                   name="reward_min" 
                   value="{{ request('reward_min') }}"
                   placeholder="最小"
                   min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <span class="self-center">〜</span>
            <input type="number" 
                   id="reward_max" 
                   name="reward_max" 
                   value="{{ request('reward_max') }}"
                   placeholder="最大"
                   min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          </div>
        </div>
      </div>

      <div class="flex gap-2">
        <button type="submit" 
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          検索
        </button>
        <a href="{{ route('jobs.index') }}" 
           class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
          リセット
        </a>
      </div>
    </form>
  </div>

  {{-- 検索結果数 --}}
  @if(request()->hasAny(['keyword', 'prefecture', 'location_type', 'reward_min', 'reward_max']))
    <div class="mb-4 text-sm text-gray-600">
      検索結果: {{ $jobs->total() }}件
    </div>
  @endif

  @if($jobs->count() === 0)
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
      <p class="text-gray-600">該当する依頼がありません</p>
    </div>
  @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($jobs as $job)
        <a href="{{ route('jobs.show', $job) }}" class="block border rounded-lg p-6 hover:shadow-lg transition-shadow">
          <h2 class="text-xl font-semibold mb-3">{{ $job->title }}</h2>
          
          <p class="text-gray-600 text-sm mb-4 line-clamp-3">
            {{ mb_strlen($job->description) > 150 ? mb_substr($job->description, 0, 150) . '...' : $job->description }}
          </p>

          <div class="space-y-2 text-sm">
            <div class="flex items-center justify-between">
              <span class="text-gray-600">投稿者</span>
              <span class="font-semibold">{{ $job->painter->name }}</span>
            </div>

            <div class="flex items-center justify-between">
              <span class="text-gray-600">場所</span>
              <span>
                {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                @if($job->prefecture)
                  ({{ $job->prefecture }})
                @endif
              </span>
            </div>

            @if($job->reward_amount)
              <div class="flex items-center justify-between">
                <span class="text-gray-600">報酬</span>
                <span class="font-semibold text-blue-600">
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
                <span class="text-gray-600">日程</span>
                <span>{{ $job->scheduled_date->format('Y/m/d') }}</span>
              </div>
            @endif

            @if($job->apply_deadline)
              <div class="flex items-center justify-between">
                <span class="text-gray-600">応募締切</span>
                <span>{{ $job->apply_deadline->format('Y/m/d') }}</span>
              </div>
            @endif
          </div>

          <div class="mt-4 pt-4 border-t">
            <div class="flex items-center justify-between text-xs text-gray-500">
              <span>投稿日: {{ $job->created_at->format('Y/m/d') }}</span>
              @if($job->applications()->count() > 0)
                <span>応募: {{ $job->applications()->count() }}件</span>
              @endif
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

