@extends('layouts.app')

@section('title', 'お問い合わせ')
@section('description', 'Palette へのお問い合わせはこちらから。サービスに関するご質問・ご要望をお寄せください。')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <p class="page-header-subtitle">Contact</p>
        <h1 class="page-header-title mt-2">お問い合わせ</h1>
        <p class="text-secondary-500 text-sm mt-3">サービスに関するご質問・ご要望をお寄せください。</p>
    </div>
</div>

<div class="page-narrow">
    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 mb-6 text-sm text-secondary-700">
            <p class="text-[10px] uppercase tracking-[0.3em] text-success-700 mb-1">Sent</p>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('contact.store') }}" method="POST" class="border border-secondary-200 bg-canvas-50 p-5 sm:p-8 space-y-6">
        @csrf

        <div>
            <label for="name" class="form-label">
                お名前 <span class="text-error-500">*</span>
            </label>
            <input type="text" id="name" name="name" required
                   value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}"
                   class="form-input">
            @error('name')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="email" class="form-label">
                メールアドレス <span class="text-error-500">*</span>
            </label>
            <input type="email" id="email" name="email" required
                   value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}"
                   class="form-input">
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="subject" class="form-label">
                件名 <span class="text-error-500">*</span>
            </label>
            <input type="text" id="subject" name="subject" required
                   value="{{ old('subject') }}"
                   placeholder="例：アカウントについて"
                   class="form-input">
            @error('subject')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="message" class="form-label">
                お問い合わせ内容 <span class="text-error-500">*</span>
            </label>
            <textarea id="message" name="message" rows="8" required
                      placeholder="お問い合わせ内容を入力してください"
                      class="form-textarea">{{ old('message') }}</textarea>
            @error('message')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-3 border-t border-secondary-200">
            <a href="{{ route('home') }}"
               class="order-2 sm:order-1 px-6 py-2.5 border border-secondary-400 text-secondary-700 text-xs uppercase tracking-[0.2em] hover:bg-secondary-100 transition-colors duration-200 text-center">
                Cancel
            </a>
            <button type="submit"
                    class="order-1 sm:order-2 px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                Send
            </button>
        </div>
    </form>
</div>
@endsection
