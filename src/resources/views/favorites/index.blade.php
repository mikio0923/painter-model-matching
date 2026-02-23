@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <h1 class="text-2xl font-bold mb-6">お気に入り一覧</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($favorites->count() === 0)
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
            <p class="text-gray-600">お気に入りはまだありません</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($favorites as $favorite)
                @php
                    $item = $favorite->favoritable;
                @endphp
                
                @if($item instanceof \App\Models\ModelProfile)
                    {{-- モデルプロフィール --}}
                    <div class="bg-white border rounded-lg overflow-hidden hover:shadow-lg transition-shadow relative">
                        <a href="{{ route('models.show', $item) }}" class="block">
                            <div class="aspect-[3/4] bg-gray-200 overflow-hidden relative">
                                @if($item->profile_image_path)
                                    <img src="{{ Storage::url($item->profile_image_path) }}"
                                         alt="{{ $item->display_name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-1">{{ $item->display_name }}</h3>
                                <div class="text-sm text-gray-600 mb-2">
                                    @if($item->prefecture){{ $item->prefecture }}@endif
                                    @if($item->age) {{ $item->age }}歳@endif
                                </div>
                            </div>
                        </a>
                        <div class="absolute top-2 right-2 z-10" onclick="event.stopPropagation();">
                            <form action="{{ route('favorites.destroy.model', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-full bg-white/90 shadow hover:bg-red-50 text-red-500" title="お気に入りから削除（ハートをもう一度押すとOFFになります）">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @elseif($item instanceof \App\Models\Job)
                    {{-- 依頼 --}}
                    <div class="bg-white border rounded-lg p-4 hover:shadow-lg transition-shadow relative">
                        <a href="{{ route('jobs.show', $item) }}" class="block pr-10">
                            <h3 class="font-semibold text-lg mb-2">{{ $item->title }}</h3>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                {{ mb_strlen($item->description) > 100 ? mb_substr($item->description, 0, 100) . '...' : $item->description }}
                            </p>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">
                                    {{ $item->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                                    @if($item->prefecture)
                                        ({{ $item->prefecture }})
                                    @endif
                                </span>
                                @if($item->reward_amount)
                                    <span class="font-semibold text-blue-600">
                                        {{ number_format($item->reward_amount) }}円
                                    </span>
                                @endif
                            </div>
                        </a>
                        <div class="absolute top-2 right-2 z-10">
                            <form action="{{ route('favorites.destroy.job', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-full bg-white/90 shadow hover:bg-red-50 text-red-500 border border-gray-200" title="お気に入りから削除（ハートをもう一度押すとOFFになります）">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="mt-6">
            {{ $favorites->links() }}
        </div>
    @endif
</div>
@endsection

