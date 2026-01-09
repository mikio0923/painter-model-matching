@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-2xl font-bold mb-6">プロフィール編集</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('model.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- プロフィール画像 --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                プロフィール画像
            </label>
            @if($modelProfile->profile_image_path)
                <div class="mb-4">
                    <img src="{{ Storage::url($modelProfile->profile_image_path) }}" 
                         alt="プロフィール画像" 
                         class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                </div>
            @endif
            <input type="file" 
                   id="profile_image" 
                   name="profile_image" 
                   accept="image/jpeg,image/png,image/jpg,image/gif"
                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            @error('profile_image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">JPEG、PNG、GIF形式、最大5MB</p>
        </div>

        {{-- 表示名 --}}
        <div>
            <label for="display_name" class="block text-sm font-medium text-gray-700 mb-1">
                表示名 <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="display_name" 
                   name="display_name" 
                   value="{{ old('display_name', $modelProfile->display_name) }}"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('display_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- 基本情報 --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div>
                <label for="age" class="block text-sm font-medium text-gray-700 mb-1">
                    年齢
                </label>
                <input type="number" 
                       id="age" 
                       name="age" 
                       value="{{ old('age', $modelProfile->age) }}"
                       min="1" 
                       max="150"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('age')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">
                    性別
                </label>
                <select id="gender" 
                        name="gender"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">選択してください</option>
                    <option value="male" {{ old('gender', $modelProfile->gender) === 'male' ? 'selected' : '' }}>男性</option>
                    <option value="female" {{ old('gender', $modelProfile->gender) === 'female' ? 'selected' : '' }}>女性</option>
                    <option value="other" {{ old('gender', $modelProfile->gender) === 'other' ? 'selected' : '' }}>その他</option>
                </select>
                @error('gender')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="height" class="block text-sm font-medium text-gray-700 mb-1">
                    身長 (cm)
                </label>
                <input type="number" 
                       id="height" 
                       name="height" 
                       value="{{ old('height', $modelProfile->height) }}"
                       min="1" 
                       max="300"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('height')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- 都道府県 --}}
        <div>
            <label for="prefecture" class="block text-sm font-medium text-gray-700 mb-1">
                都道府県
            </label>
            <input type="text" 
                   id="prefecture" 
                   name="prefecture" 
                   value="{{ old('prefecture', $modelProfile->prefecture) }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('prefecture')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- 体型 --}}
        <div>
            <label for="body_type" class="block text-sm font-medium text-gray-700 mb-1">
                体型
            </label>
            <input type="text" 
                   id="body_type" 
                   name="body_type" 
                   value="{{ old('body_type', $modelProfile->body_type) }}"
                   placeholder="例：スリム、普通、グラマー"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('body_type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- 髪型 --}}
        <div>
            <label for="hair_type" class="block text-sm font-medium text-gray-700 mb-1">
                髪型
            </label>
            <select id="hair_type" 
                    name="hair_type"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">選択してください</option>
                <option value="short" {{ old('hair_type', $modelProfile->hair_type) === 'short' ? 'selected' : '' }}>ショート</option>
                <option value="medium" {{ old('hair_type', $modelProfile->hair_type) === 'medium' ? 'selected' : '' }}>ミディアム</option>
                <option value="long" {{ old('hair_type', $modelProfile->hair_type) === 'long' ? 'selected' : '' }}>ロング</option>
                <option value="other" {{ old('hair_type', $modelProfile->hair_type) === 'other' ? 'selected' : '' }}>その他</option>
            </select>
            @error('hair_type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- スタイルタグ --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                スタイルタグ（カンマ区切りで入力）
            </label>
            <input type="text" 
                   name="style_tags_input" 
                   value="{{ old('style_tags_input', is_array($modelProfile->style_tags) ? implode(',', $modelProfile->style_tags) : '') }}"
                   placeholder="例：清楚,クール,セクシー"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <p class="mt-1 text-sm text-gray-500">カンマ区切りで複数のタグを入力できます</p>
        </div>

        {{-- ポーズ範囲 --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                ポーズ範囲（カンマ区切りで入力）
            </label>
            <input type="text" 
                   name="pose_ranges_input" 
                   value="{{ old('pose_ranges_input', is_array($modelProfile->pose_ranges) ? implode(',', $modelProfile->pose_ranges) : '') }}"
                   placeholder="例：全身,バストアップ,顔"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <p class="mt-1 text-sm text-gray-500">カンマ区切りで複数の範囲を入力できます</p>
        </div>

        {{-- 報酬 --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="reward_min" class="block text-sm font-medium text-gray-700 mb-1">
                    報酬下限 (円)
                </label>
                <input type="number" 
                       id="reward_min" 
                       name="reward_min" 
                       value="{{ old('reward_min', $modelProfile->reward_min) }}"
                       min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('reward_min')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="reward_max" class="block text-sm font-medium text-gray-700 mb-1">
                    報酬上限 (円)
                </label>
                <input type="number" 
                       id="reward_max" 
                       name="reward_max" 
                       value="{{ old('reward_max', $modelProfile->reward_max) }}"
                       min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('reward_max')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- チェックボックス --}}
        <div class="space-y-2">
            <label class="flex items-center">
                <input type="checkbox" 
                       name="online_available" 
                       value="1"
                       {{ old('online_available', $modelProfile->online_available) ? 'checked' : '' }}
                       class="mr-2">
                <span>オンライン対応可能</span>
            </label>

            <label class="flex items-center">
                <input type="checkbox" 
                       name="is_public" 
                       value="1"
                       {{ old('is_public', $modelProfile->is_public) ? 'checked' : '' }}
                       class="mr-2">
                <span>プロフィールを公開する</span>
            </label>
        </div>

        {{-- 送信ボタン --}}
        <div class="flex gap-4 pt-4">
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                更新する
            </button>
            <a href="{{ route('models.show', $modelProfile) }}" 
               class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                キャンセル
            </a>
        </div>
    </form>
</div>

<script>
// スタイルタグとポーズ範囲を配列形式に変換
document.querySelector('form').addEventListener('submit', function(e) {
    const styleTagsInput = document.querySelector('input[name="style_tags_input"]');
    const poseRangesInput = document.querySelector('input[name="pose_ranges_input"]');
    
    if (styleTagsInput && styleTagsInput.value) {
        const tags = styleTagsInput.value.split(',').map(tag => tag.trim()).filter(tag => tag);
        tags.forEach((tag, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `style_tags[${index}]`;
            input.value = tag;
            this.appendChild(input);
        });
    }
    
    if (poseRangesInput && poseRangesInput.value) {
        const ranges = poseRangesInput.value.split(',').map(range => range.trim()).filter(range => range);
        ranges.forEach((range, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `pose_ranges[${index}]`;
            input.value = range;
            this.appendChild(input);
        });
    }
});
</script>
@endsection

