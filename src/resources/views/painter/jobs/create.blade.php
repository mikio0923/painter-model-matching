@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-2xl font-bold mb-6">依頼を作成</h1>

    @if($modelProfile)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-800 font-semibold mb-2">依頼対象モデル</p>
            <p class="text-blue-900">{{ $modelProfile->display_name }}</p>
        </div>
    @endif

    <form action="{{ route('painter.jobs.store') }}" method="POST" class="space-y-6">
        @csrf

        @if($modelProfile)
            <input type="hidden" name="model_id" value="{{ $modelProfile->id }}">
        @endif

        {{-- タイトル --}}
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                タイトル <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="title" 
                   name="title" 
                   value="{{ old('title') }}"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- 説明 --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                説明 <span class="text-red-500">*</span>
            </label>
            <textarea id="description" 
                      name="description" 
                      rows="5"
                      required
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- 用途 --}}
        <div>
            <label for="usage_purpose" class="block text-sm font-medium text-gray-700 mb-1">
                用途（任意）
            </label>
            <input type="text" 
                   id="usage_purpose" 
                   name="usage_purpose" 
                   value="{{ old('usage_purpose') }}"
                   placeholder="例：個展、練習、作品制作"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('usage_purpose')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- 報酬 --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="reward_amount" class="block text-sm font-medium text-gray-700 mb-1">
                    報酬額（任意）
                </label>
                <input type="number" 
                       id="reward_amount" 
                       name="reward_amount" 
                       value="{{ old('reward_amount') }}"
                       min="0"
                       placeholder="例：5000"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('reward_amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="reward_unit" class="block text-sm font-medium text-gray-700 mb-1">
                    単位（任意）
                </label>
                <select id="reward_unit" 
                        name="reward_unit"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="per_session" {{ old('reward_unit', 'per_session') === 'per_session' ? 'selected' : '' }}>1回あたり</option>
                    <option value="per_hour" {{ old('reward_unit') === 'per_hour' ? 'selected' : '' }}>1時間あたり</option>
                </select>
                @error('reward_unit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- 場所 --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                場所 <span class="text-red-500">*</span>
            </label>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input type="radio" 
                           name="location_type" 
                           value="online" 
                           {{ old('location_type', 'online') === 'online' ? 'checked' : '' }}
                           required
                           class="mr-2">
                    <span>オンライン</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" 
                           name="location_type" 
                           value="offline" 
                           {{ old('location_type') === 'offline' ? 'checked' : '' }}
                           required
                           class="mr-2">
                    <span>オフライン</span>
                </label>
            </div>
            @error('location_type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- 都道府県・市区町村（オフラインの場合） --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="prefecture" class="block text-sm font-medium text-gray-700 mb-1">
                    都道府県（任意）
                </label>
                <select id="prefecture" 
                        name="prefecture"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">選択してください</option>
                    @foreach($prefectures as $pref)
                        <option value="{{ $pref }}" {{ old('prefecture') === $pref ? 'selected' : '' }}>{{ $pref }}</option>
                    @endforeach
                </select>
                @error('prefecture')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                    市区町村（任意）
                </label>
                <input type="text" 
                       id="city" 
                       name="city" 
                       value="{{ old('city') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('city')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- 日程 --}}
        <div>
            <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-1">
                日程（任意）
            </label>
            <input type="date" 
                   id="scheduled_date" 
                   name="scheduled_date" 
                   value="{{ old('scheduled_date') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('scheduled_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- 締切 --}}
        <div>
            <label for="apply_deadline" class="block text-sm font-medium text-gray-700 mb-1">
                応募締切（任意）
            </label>
            <input type="date" 
                   id="apply_deadline" 
                   name="apply_deadline" 
                   value="{{ old('apply_deadline') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('apply_deadline')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- 送信ボタン --}}
        <div class="flex gap-4 pt-4">
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                依頼を作成
            </button>
            <a href="{{ route('painter.jobs.index') }}" 
               class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                キャンセル
            </a>
        </div>
    </form>
</div>
@endsection

