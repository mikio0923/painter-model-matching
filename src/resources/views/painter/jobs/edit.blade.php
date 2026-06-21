@extends('layouts.app')

@section('title', '依頼を編集')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('painter.jobs.index') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                依頼一覧
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">編集</span>
        </div>
        <p class="page-header-subtitle">Edit Job</p>
        <h1 class="page-header-title mt-2">依頼を編集</h1>
    </div>
</div>

<div class="page-narrow">

    @php
        $rowLabel = 'block text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-2';
        $input    = 'w-full px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900 transition-colors duration-200';
    @endphp

    <form action="{{ route('painter.jobs.update', $job) }}" method="POST"
          class="border border-secondary-200 bg-canvas-50 p-5 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        {{-- セクション: 案件概要 --}}
        <div class="border-b border-secondary-200 pb-2">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Section 01</p>
            <h2 class="font-display text-base font-semibold text-secondary-900 mt-1">案件概要</h2>
        </div>

        <div>
            <label for="title" class="{{ $rowLabel }}">タイトル <span class="text-error-500">*</span></label>
            <input type="text" id="title" name="title" value="{{ old('title', $job->title) }}" required class="{{ $input }}">
            @error('title')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="description" class="{{ $rowLabel }}">説明 <span class="text-error-500">*</span></label>
            <textarea id="description" name="description" rows="6" required class="{{ $input }} resize-y leading-relaxed">{{ old('description', $job->description) }}</textarea>
            @error('description')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="usage_purpose" class="{{ $rowLabel }}">用途</label>
            <input type="text" id="usage_purpose" name="usage_purpose" value="{{ old('usage_purpose', $job->usage_purpose) }}"
                   placeholder="例：個展、練習、作品制作" class="{{ $input }}">
            @error('usage_purpose')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="category" class="{{ $rowLabel }}">カテゴリ</label>
            <input type="text" id="category" name="category" value="{{ old('category', $job->category) }}"
                   placeholder="例：広告用モデル募集" class="{{ $input }}">
            @error('category')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- セクション: 報酬・条件 --}}
        <div class="border-b border-secondary-200 pb-2 pt-4">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Section 02</p>
            <h2 class="font-display text-base font-semibold text-secondary-900 mt-1">報酬・条件</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="reward_amount" class="{{ $rowLabel }}">報酬額（円）</label>
                <input type="number" id="reward_amount" name="reward_amount" value="{{ old('reward_amount', $job->reward_amount) }}"
                       min="0" placeholder="例：5000" class="{{ $input }}">
                @error('reward_amount')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="reward_unit" class="{{ $rowLabel }}">単位</label>
                <select id="reward_unit" name="reward_unit" class="{{ $input }}">
                    <option value="per_session" {{ old('reward_unit', $job->reward_unit) === 'per_session' ? 'selected' : '' }}>1回あたり</option>
                    <option value="per_hour" {{ old('reward_unit', $job->reward_unit) === 'per_hour' ? 'selected' : '' }}>1時間あたり</option>
                </select>
                @error('reward_unit')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="transportation_fee" class="{{ $rowLabel }}">交通費の支給</label>
                <input type="text" id="transportation_fee" name="transportation_fee" value="{{ old('transportation_fee', $job->transportation_fee) }}"
                       placeholder="例：なし / あり / 応相談" class="{{ $input }}">
                @error('transportation_fee')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="costume_provided" class="{{ $rowLabel }}">衣装の提供</label>
                <input type="text" id="costume_provided" name="costume_provided" value="{{ old('costume_provided', $job->costume_provided) }}"
                       placeholder="例：なし / あり / 応相談" class="{{ $input }}">
                @error('costume_provided')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="target" class="{{ $rowLabel }}">募集対象</label>
                <input type="text" id="target" name="target" value="{{ old('target', $job->target) }}"
                       placeholder="例：女性 / 男性 / 指定なし" class="{{ $input }}">
                @error('target')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="recruitment_number" class="{{ $rowLabel }}">募集人数</label>
                <input type="number" id="recruitment_number" name="recruitment_number" value="{{ old('recruitment_number', $job->recruitment_number) }}"
                       min="1" placeholder="例：1" class="{{ $input }}">
                @error('recruitment_number')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- セクション: 場所 --}}
        <div class="border-b border-secondary-200 pb-2 pt-4">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Section 03</p>
            <h2 class="font-display text-base font-semibold text-secondary-900 mt-1">場所</h2>
        </div>

        <div>
            <p class="{{ $rowLabel }}">場所区分 <span class="text-error-500">*</span></p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <label class="cursor-pointer">
                    <input type="radio" name="location_type" value="online"
                           {{ old('location_type', $job->location_type) === 'online' ? 'checked' : '' }} required class="peer sr-only">
                    <span class="block text-center py-3 border border-secondary-300 text-sm text-secondary-700 peer-checked:border-secondary-900 peer-checked:bg-secondary-900 peer-checked:text-canvas-50 transition-colors duration-200">
                        オンライン
                        <span class="block text-[9px] tracking-[0.2em] uppercase text-secondary-500 peer-checked:text-secondary-300">Online</span>
                    </span>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="location_type" value="offline"
                           {{ old('location_type', $job->location_type) === 'offline' ? 'checked' : '' }} required class="peer sr-only">
                    <span class="block text-center py-3 border border-secondary-300 text-sm text-secondary-700 peer-checked:border-secondary-900 peer-checked:bg-secondary-900 peer-checked:text-canvas-50 transition-colors duration-200">
                        オフライン
                        <span class="block text-[9px] tracking-[0.2em] uppercase text-secondary-500 peer-checked:text-secondary-300">Offline</span>
                    </span>
                </label>
            </div>
            @error('location_type')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="prefecture" class="{{ $rowLabel }}">都道府県</label>
                <select id="prefecture" name="prefecture" class="{{ $input }}">
                    <option value="">選択してください</option>
                    @foreach($prefectures as $pref)
                        <option value="{{ $pref }}" {{ old('prefecture', $job->prefecture) === $pref ? 'selected' : '' }}>{{ $pref }}</option>
                    @endforeach
                </select>
                @error('prefecture')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="city" class="{{ $rowLabel }}">市区町村</label>
                <input type="text" id="city" name="city" value="{{ old('city', $job->city) }}" class="{{ $input }}">
                @error('city')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="address" class="{{ $rowLabel }}">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $job->address) }}" class="{{ $input }}">
            @error('address')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="access" class="{{ $rowLabel }}">アクセス・補足</label>
            <textarea id="access" name="access" rows="4" class="{{ $input }} resize-y leading-relaxed">{{ old('access', $job->access) }}</textarea>
            @error('access')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- セクション: 日程・ステータス --}}
        <div class="border-b border-secondary-200 pb-2 pt-4">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Section 04</p>
            <h2 class="font-display text-base font-semibold text-secondary-900 mt-1">日程・ステータス</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="scheduled_date" class="{{ $rowLabel }}">撮影日</label>
                <input type="date" id="scheduled_date" name="scheduled_date"
                       value="{{ old('scheduled_date', $job->scheduled_date ? $job->scheduled_date->format('Y-m-d') : '') }}"
                       class="{{ $input }}">
                @error('scheduled_date')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="apply_deadline" class="{{ $rowLabel }}">応募締切</label>
                <input type="date" id="apply_deadline" name="apply_deadline"
                       value="{{ old('apply_deadline', $job->apply_deadline ? $job->apply_deadline->format('Y-m-d') : '') }}"
                       class="{{ $input }}">
                @error('apply_deadline')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="status" class="{{ $rowLabel }}">ステータス</label>
            <select id="status" name="status" class="{{ $input }} max-w-xs">
                <option value="open" {{ old('status', $job->status) === 'open' ? 'selected' : '' }}>募集中</option>
                <option value="closed" {{ old('status', $job->status) === 'closed' ? 'selected' : '' }}>締切</option>
                <option value="done" {{ old('status', $job->status) === 'done' ? 'selected' : '' }}>完了</option>
            </select>
            @error('status')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- 送信 --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-4 border-t border-secondary-200">
            <a href="{{ route('painter.jobs.index') }}"
               class="order-2 sm:order-1 px-6 py-2.5 border border-secondary-400 text-secondary-700 text-xs uppercase tracking-[0.2em] hover:bg-secondary-100 transition-colors duration-200 text-center">
                Cancel
            </a>
            <button type="submit"
                    class="order-1 sm:order-2 px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                Update
            </button>
        </div>
    </form>
</div>

@include('painter.jobs._date_validation')

@endsection
