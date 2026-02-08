@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-2">
        <span class="text-gray-400">&gt;</span> 本人確認
    </h1>
    <p class="text-sm text-gray-600 mb-6">本人確認の状態を確認し、オファー率を高めるための設定ができます。</p>

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-900">現在のステータス</h2>
        </div>
        <div class="p-6">
            @if($modelProfile)
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex-shrink-0 w-16 h-16 rounded-full flex items-center justify-center
                        {{ $modelProfile->identity_verified ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600' }}">
                        @if($modelProfile->identity_verified)
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @else
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <div class="font-semibold text-lg {{ $modelProfile->identity_verified ? 'text-green-700' : 'text-amber-700' }}">
                            {{ $modelProfile->identity_verified ? '本人確認済み' : '未確認' }}
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            @if($modelProfile->identity_verified)
                                プロフィールに「本人確認済み」バッジが表示されています。
                            @else
                                本人確認を行うと、画家からのオファーが増えやすくなります。
                            @endif
                        </p>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-800">
                        現在、本人確認は自己申告形式となっています。プロフィール編集画面から「本人確認済み」にチェックを入れることで、プロフィールに表示されます。
                    </p>
                    <p class="text-sm text-blue-700 mt-2">
                        将来的に、書類による本人確認サービスの提供を予定しています。
                    </p>
                </div>

                <a href="{{ route('model.profile.edit') }}#identity" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    プロフィールを編集して本人確認を設定
                </a>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-600 mb-4">まずプロフィールを作成してください。</p>
                    <a href="{{ route('model.profile.create') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        プロフィールを作成する
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="mb-4">
        <a href="{{ route('mypage') }}" class="text-blue-600 hover:text-blue-800 text-sm">← マイページに戻る</a>
    </div>
</div>
@endsection
