@extends('layouts.app')

@section('content')
<div class="page-narrow">
    {{-- ヘッダー部分（丸アイコン、名前、年齢、出身地、タブ） --}}
    <div class="bg-gray-800 text-white mb-6 rounded-t-lg">
        <div class="px-6 py-6">
            <div class="flex items-start gap-6 mb-4">
                {{-- 丸アイコン --}}
                @php
                    $displayImage = $modelProfile->profile_image_path;
                @endphp
                @if($displayImage)
                    <div class="flex-shrink-0">
                        <img src="{{ Storage::url($displayImage) }}"
                             alt="{{ $modelProfile->display_name }}"
                             class="w-24 h-24 object-cover rounded-full border-4 border-white shadow-lg">
                    </div>
                @else
                    <div class="flex-shrink-0 w-24 h-24 rounded-full bg-gray-700 border-4 border-white shadow-lg flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                @endif

                {{-- 名前、年齢、出身地 --}}
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">
                        {{ $modelProfile->display_name }}
                    </h1>
                    <div class="text-gray-300 text-lg">
                        @if($modelProfile->prefecture)
                            <span>{{ $modelProfile->prefecture }}</span>
                        @endif
                        @if($modelProfile->age)
                            <span class="ml-2">{{ $modelProfile->age }}歳</span>
                        @endif
                        @if($modelProfile->gender)
                            <span class="ml-2">
                                (@if($modelProfile->gender === 'male')男性
                                @elseif($modelProfile->gender === 'female')女性
                                @elseその他
                                @endif)
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- タブナビゲーション --}}
            <div class="flex gap-6 border-t border-gray-700 pt-4">
                <a href="{{ route('models.show', ['modelProfile' => $modelProfile, 'tab' => 'profile']) }}"
                   class="px-4 py-2 text-sm font-medium transition-colors {{ $tab === 'profile' ? 'text-white border-b-2 border-white' : 'text-gray-400 hover:text-gray-200' }}">
                    Profile
                </a>
                <a href="{{ route('models.show', ['modelProfile' => $modelProfile, 'tab' => 'qa']) }}"
                   class="px-4 py-2 text-sm font-medium transition-colors {{ $tab === 'qa' ? 'text-white border-b-2 border-white' : 'text-gray-400 hover:text-gray-200' }}">
                    Q&A
                </a>
                <a href="{{ route('models.show', ['modelProfile' => $modelProfile, 'tab' => 'photo']) }}"
                   class="px-4 py-2 text-sm font-medium transition-colors {{ $tab === 'photo' ? 'text-white border-b-2 border-white' : 'text-gray-400 hover:text-gray-200' }}">
                    Photo
                </a>
                <a href="{{ route('models.show', ['modelProfile' => $modelProfile, 'tab' => 'comments']) }}"
                   class="px-4 py-2 text-sm font-medium transition-colors {{ $tab === 'comments' ? 'text-white border-b-2 border-white' : 'text-gray-400 hover:text-gray-200' }}">
                    Comments
                </a>
            </div>
        </div>
    </div>

    {{-- タブコンテンツ --}}
    @if($tab === 'profile')
        @include('models.show.profile', ['modelProfile' => $modelProfile, 'isFavorite' => $isFavorite, 'favoritesCount' => $favoritesCount])
    @elseif($tab === 'qa')
        @include('models.show.qa', ['modelProfile' => $modelProfile, 'questions' => $questions ?? collect()])
    @elseif($tab === 'photo')
        @include('models.show.photo', ['modelProfile' => $modelProfile])
    @elseif($tab === 'comments')
        @include('models.show.comments', ['modelProfile' => $modelProfile, 'reviews' => $reviews])
    @endif

</div>

{{-- 画像モーダル --}}
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center" onclick="closeImageModal()">
    <div class="max-w-4xl max-h-full p-4 relative">
        <img id="modalImage" src="" alt="拡大画像" class="max-w-full max-h-screen object-contain">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-2xl font-bold hover:text-gray-300 bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center">×</button>
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
@endsection
