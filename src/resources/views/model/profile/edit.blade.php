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

        {{-- プロフィール画像（旧形式、後方互換性のため残す） --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                プロフィール画像（メイン画像）
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

        {{-- 複数画像ギャラリー --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                画像ギャラリー（最大10枚）
            </label>
            
            @if($modelProfile->images->count() > 0)
                <div class="grid grid-cols-3 md:grid-cols-5 gap-4 mb-4" id="existingImages">
                    @foreach($modelProfile->images as $image)
                        <div class="relative" data-image-id="{{ $image->id }}">
                            <img src="{{ Storage::url($image->image_path) }}" 
                                 alt="ギャラリー画像" 
                                 class="w-full aspect-square object-cover rounded-lg border border-gray-300">
                            <div class="absolute top-2 right-2 flex gap-1">
                                @if($image->is_main)
                                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">メイン</span>
                                @else
                                    <button type="button" 
                                            onclick="setMainImage({{ $image->id }})"
                                            class="bg-blue-500 text-white text-xs px-2 py-1 rounded hover:bg-blue-600">
                                        メインに
                                    </button>
                                @endif
                                <button type="button" 
                                        onclick="deleteImage({{ $image->id }})"
                                        class="bg-red-500 text-white text-xs px-2 py-1 rounded hover:bg-red-600">
                                    削除
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- 選択した画像のプレビュー --}}
            <div id="imagePreview" class="grid grid-cols-3 md:grid-cols-5 gap-4 mb-4 hidden"></div>

            <div class="flex gap-2">
                <input type="file" 
                       id="images" 
                       name="images[]" 
                       accept="image/jpeg,image/png,image/jpg,image/gif"
                       multiple
                       class="hidden"
                       onchange="handleImageSelection(event)">
                <button type="button" 
                        onclick="document.getElementById('images').click()"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    画像を選択
                </button>
                <span id="selectedCount" class="self-center text-sm text-gray-600"></span>
            </div>
            @error('images')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">複数選択可能、JPEG、PNG、GIF形式、各最大5MB</p>
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
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    生年月日
                </label>
                <div class="flex gap-2">
                    @php
                        $birthYear = old('birth_year', $modelProfile->birthdate ? $modelProfile->birthdate->format('Y') : '');
                        $birthMonth = old('birth_month', $modelProfile->birthdate ? $modelProfile->birthdate->format('m') : '');
                        $birthDay = old('birth_day', $modelProfile->birthdate ? $modelProfile->birthdate->format('d') : '');
                        $currentYear = date('Y');
                        $currentMonth = date('m');
                        $currentDay = date('d');
                    @endphp
                    <select id="birth_year" 
                            name="birth_year"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            onchange="updateBirthdate()">
                        <option value="">年</option>
                        @for($year = $currentYear; $year >= 1900; $year--)
                            <option value="{{ $year }}" {{ $birthYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    <select id="birth_month" 
                            name="birth_month"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            onchange="updateBirthdate()">
                        <option value="">月</option>
                        @for($month = 1; $month <= 12; $month++)
                            <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}" {{ $birthMonth == str_pad($month, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $month }}</option>
                        @endfor
                    </select>
                    <select id="birth_day" 
                            name="birth_day"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            onchange="updateBirthdate()">
                        <option value="">日</option>
                        @for($day = 1; $day <= 31; $day++)
                            <option value="{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}" {{ $birthDay == str_pad($day, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $day }}</option>
                        @endfor
                    </select>
                </div>
                <input type="hidden" 
                       id="birthdate" 
                       name="birthdate" 
                       value="{{ old('birthdate', $modelProfile->birthdate ? $modelProfile->birthdate->format('Y-m-d') : '') }}">
                @error('birthdate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="age" class="block text-sm font-medium text-gray-700 mb-1">
                    年齢（自動計算）
                </label>
                <input type="number" 
                       id="age" 
                       name="age" 
                       value="{{ old('age', $modelProfile->age) }}"
                       min="1" 
                       max="150"
                       readonly
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100">
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
            <select id="prefecture" 
                    name="prefecture"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">選択してください</option>
                @foreach($prefectures as $pref)
                    <option value="{{ $pref }}" {{ old('prefecture', $modelProfile->prefecture) === $pref ? 'selected' : '' }}>
                        {{ $pref }}
                    </option>
                @endforeach
            </select>
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

        {{-- 自己紹介 --}}
        <div>
            <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">
                自己紹介
            </label>
            <textarea id="bio" 
                      name="bio" 
                      rows="4"
                      placeholder="自己紹介を入力してください"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('bio', $modelProfile->bio) }}</textarea>
            @error('bio')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- 経験・実績 --}}
        <div>
            <label for="experience" class="block text-sm font-medium text-gray-700 mb-1">
                経験・実績
            </label>
            <textarea id="experience" 
                      name="experience" 
                      rows="4"
                      placeholder="経験や実績を入力してください"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('experience', $modelProfile->experience) }}</textarea>
            @error('experience')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- ポートフォリオURL --}}
        <div>
            <label for="portfolio_url" class="block text-sm font-medium text-gray-700 mb-1">
                ポートフォリオURL
            </label>
            <input type="url" 
                   id="portfolio_url" 
                   name="portfolio_url" 
                   value="{{ old('portfolio_url', $modelProfile->portfolio_url) }}"
                   placeholder="https://example.com"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('portfolio_url')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- SNSリンク --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                SNSリンク（カンマ区切りでURLを入力）
            </label>
            <input type="text" 
                   name="sns_links_input" 
                   value="{{ old('sns_links_input', is_array($modelProfile->sns_links) ? implode(',', $modelProfile->sns_links) : '') }}"
                   placeholder="https://twitter.com/example, https://instagram.com/example"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <p class="mt-1 text-sm text-gray-500">カンマ区切りで複数のURLを入力できます</p>
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
// 選択した画像を保持する配列
let selectedFiles = [];

// 画像選択処理
function handleImageSelection(event) {
    const files = Array.from(event.target.files);
    const maxFiles = 10;
    const existingCount = {{ $modelProfile->images->count() }};
    
    // 既存の画像と合わせて最大10枚まで
    if (selectedFiles.length + files.length + existingCount > maxFiles) {
        alert(`画像は最大${maxFiles}枚までアップロードできます。既存の画像を含めて${maxFiles}枚を超えています。`);
        return;
    }
    
    // 新しいファイルを追加
    selectedFiles = [...selectedFiles, ...files];
    
    // ファイル入力に選択したファイルを設定（DataTransferを使用）
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => {
        dataTransfer.items.add(file);
    });
    event.target.files = dataTransfer.files;
    
    // プレビューを更新
    updateImagePreview();
    updateSelectedCount();
}

// 画像プレビューを更新
function updateImagePreview() {
    const previewContainer = document.getElementById('imagePreview');
    previewContainer.innerHTML = '';
    
    if (selectedFiles.length === 0) {
        previewContainer.classList.add('hidden');
        return;
    }
    
    previewContainer.classList.remove('hidden');
    
    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" 
                     alt="プレビュー ${index + 1}" 
                     class="w-full aspect-square object-cover rounded-lg border border-gray-300">
                <button type="button" 
                        onclick="removeImage(${index})"
                        class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded hover:bg-red-600">
                    削除
                </button>
            `;
            previewContainer.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

// 選択した画像を削除
function removeImage(index) {
    selectedFiles.splice(index, 1);
    
    // ファイル入力も更新
    const fileInput = document.getElementById('images');
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => {
        dataTransfer.items.add(file);
    });
    fileInput.files = dataTransfer.files;
    
    updateImagePreview();
    updateSelectedCount();
}

// 選択した画像数を更新
function updateSelectedCount() {
    const countElement = document.getElementById('selectedCount');
    const existingCount = {{ $modelProfile->images->count() }};
    const totalCount = selectedFiles.length + existingCount;
    
    if (selectedFiles.length > 0) {
        countElement.textContent = `選択中: ${selectedFiles.length}枚（合計: ${totalCount}枚）`;
    } else {
        countElement.textContent = '';
    }
}

// 月に応じて日の選択肢を更新
function updateDays() {
    const year = document.getElementById('birth_year').value;
    const month = document.getElementById('birth_month').value;
    const daySelect = document.getElementById('birth_day');
    const currentDay = daySelect.value;
    
    // 現在の選択肢をクリア（最初の「日」オプションを除く）
    while (daySelect.options.length > 1) {
        daySelect.remove(1);
    }
    
    if (year && month) {
        // その月の日数を計算
        const daysInMonth = new Date(parseInt(year), parseInt(month), 0).getDate();
        
        // 日付の選択肢を追加
        for (let day = 1; day <= daysInMonth; day++) {
            const option = document.createElement('option');
            option.value = String(day).padStart(2, '0');
            option.textContent = day;
            if (currentDay && currentDay === option.value) {
                option.selected = true;
            }
            daySelect.appendChild(option);
        }
    }
    
    updateBirthdate();
}

// 生年月日のプルダウンから日付を更新
function updateBirthdate() {
    const year = document.getElementById('birth_year').value;
    const month = document.getElementById('birth_month').value;
    const day = document.getElementById('birth_day').value;
    const birthdateInput = document.getElementById('birthdate');
    const ageInput = document.getElementById('age');
    
    if (year && month && day) {
        // 日付の妥当性チェック
        const date = new Date(year, parseInt(month) - 1, day);
        if (date.getFullYear() == year && date.getMonth() == parseInt(month) - 1 && date.getDate() == day) {
            const dateString = `${year}-${month}-${day}`;
            birthdateInput.value = dateString;
            calculateAge();
        } else {
            birthdateInput.value = '';
            ageInput.value = '';
        }
    } else {
        birthdateInput.value = '';
        ageInput.value = '';
    }
}

// 年と月が変更されたら日の選択肢を更新
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('birth_year').addEventListener('change', updateDays);
    document.getElementById('birth_month').addEventListener('change', updateDays);
    updateDays();
});

// 生年月日から年齢を計算
function calculateAge() {
    const birthdateInput = document.getElementById('birthdate');
    const ageInput = document.getElementById('age');
    
    if (birthdateInput.value) {
        const birthdate = new Date(birthdateInput.value);
        const today = new Date();
        let age = today.getFullYear() - birthdate.getFullYear();
        const monthDiff = today.getMonth() - birthdate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
            age--;
        }
        
        ageInput.value = age;
    } else {
        ageInput.value = '';
    }
}

// ページ読み込み時に年齢を計算
document.addEventListener('DOMContentLoaded', function() {
    updateBirthdate();
});

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

// メイン画像を設定
function setMainImage(imageId) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'main_image_id';
    input.value = imageId;
    document.querySelector('form').appendChild(input);
    document.querySelector('form').submit();
}

// 画像を削除
function deleteImage(imageId) {
    if (!confirm('この画像を削除しますか？')) {
        return;
    }
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'delete_images[]';
    input.value = imageId;
    document.querySelector('form').appendChild(input);
    document.querySelector('form').submit();
}
</script>
@endsection

