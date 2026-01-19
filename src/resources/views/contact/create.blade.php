@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-2xl font-bold mb-6">お問い合わせ</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- 名前 --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                お名前 <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- メールアドレス --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                メールアドレス <span class="text-red-500">*</span>
            </label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- 件名 --}}
        <div>
            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">
                件名 <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="subject" 
                   name="subject" 
                   value="{{ old('subject') }}"
                   required
                   placeholder="例：アカウントについて"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('subject')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- メッセージ --}}
        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">
                お問い合わせ内容 <span class="text-red-500">*</span>
            </label>
            <textarea id="message" 
                      name="message" 
                      rows="8"
                      required
                      placeholder="お問い合わせ内容を入力してください"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('message') }}</textarea>
            @error('message')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- 送信ボタン --}}
        <div class="flex gap-4 pt-4">
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                送信する
            </button>
            <a href="{{ route('home') }}" 
               class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                キャンセル
            </a>
        </div>
    </form>
</div>
@endsection

