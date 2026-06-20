@extends('layouts.app')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('mypage') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                My Page
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">プロフィール編集</span>
        </div>
        <p class="page-header-subtitle">Edit Profile</p>
        <h1 class="page-header-title mt-2">プロフィール編集</h1>
    </div>
</div>

<div class="page-narrow">
    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 mb-6 text-sm text-secondary-700">
            <p class="text-[10px] uppercase tracking-[0.3em] text-success-700 mb-1">Saved</p>
            {{ session('success') }}
        </div>
    @endif

    @php
        $rowLabel = 'block text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-2';
        $input    = 'w-full px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900 transition-colors duration-200';
    @endphp

    <form action="{{ route('model.profile.update') }}" method="POST" enctype="multipart/form-data"
          class="border border-secondary-200 bg-canvas-50 p-5 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        {{-- セクション: 画像 --}}
        <div class="border-b border-secondary-200 pb-2">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Section 01</p>
            <h2 class="font-display text-base font-semibold text-secondary-900 mt-1">画像</h2>
        </div>

        {{-- プロフィール画像（旧形式・後方互換） --}}
        <div>
            <p class="{{ $rowLabel }}">プロフィール画像（メイン）</p>
            @if($modelProfile->profile_image_path)
                <div class="mb-4">
                    <img src="{{ Storage::url($modelProfile->profile_image_path) }}"
                         alt="プロフィール画像"
                         class="w-32 h-32 object-cover border border-secondary-200">
                </div>
            @endif
            <input type="file" id="profile_image" name="profile_image"
                   accept="image/jpeg,image/png,image/jpg,image/gif"
                   class="block w-full text-sm text-secondary-500 file:mr-4 file:py-2 file:px-4 file:border file:border-secondary-400 file:text-xs file:uppercase file:tracking-[0.2em] file:text-secondary-700 file:bg-canvas-50 hover:file:bg-secondary-100 file:cursor-pointer">
            @error('profile_image')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            <p class="text-xs text-secondary-500 mt-2">JPEG / PNG / GIF、最大 5MB</p>
        </div>

        {{-- ポートフォリオは別画面で管理 --}}
        <div class="border-l-2 border-secondary-400 bg-canvas-50 px-4 py-3 text-sm text-secondary-700 leading-relaxed">
            ポートフォリオ画像の追加・編集・削除は
            <a href="{{ route('model.portfolio.edit') }}" class="link-primary font-medium">ポートフォリオ管理画面</a>
            から行えます。
        </div>

        {{-- セクション: 基本情報 --}}
        <div class="border-b border-secondary-200 pb-2 pt-4">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Section 02</p>
            <h2 class="font-display text-base font-semibold text-secondary-900 mt-1">基本情報</h2>
        </div>

        <div>
            <label for="display_name" class="{{ $rowLabel }}">表示名 <span class="text-error-500">*</span></label>
            <input type="text" id="display_name" name="display_name"
                   value="{{ old('display_name', $modelProfile->display_name) }}" required
                   class="{{ $input }}">
            @error('display_name')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        @php
            $birthYear = old('birth_year', $modelProfile->birthdate ? $modelProfile->birthdate->format('Y') : '');
            $birthMonth = old('birth_month', $modelProfile->birthdate ? $modelProfile->birthdate->format('m') : '');
            $birthDay = old('birth_day', $modelProfile->birthdate ? $modelProfile->birthdate->format('d') : '');
            $currentYear = date('Y');
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="{{ $rowLabel }}">生年月日</p>
                <div class="flex gap-2">
                    <select id="birth_year" name="birth_year" onchange="updateBirthdate()"
                            class="flex-1 px-3 py-2 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900">
                        <option value="">年</option>
                        @for($year = $currentYear; $year >= 1900; $year--)
                            <option value="{{ $year }}" {{ $birthYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    <select id="birth_month" name="birth_month" onchange="updateBirthdate()"
                            class="flex-1 px-3 py-2 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900">
                        <option value="">月</option>
                        @for($month = 1; $month <= 12; $month++)
                            <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}" {{ $birthMonth == str_pad($month, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $month }}</option>
                        @endfor
                    </select>
                    <select id="birth_day" name="birth_day" onchange="updateBirthdate()"
                            class="flex-1 px-3 py-2 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900">
                        <option value="">日</option>
                        @for($day = 1; $day <= 31; $day++)
                            <option value="{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}" {{ $birthDay == str_pad($day, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $day }}</option>
                        @endfor
                    </select>
                </div>
                <input type="hidden" id="birthdate" name="birthdate"
                       value="{{ old('birthdate', $modelProfile->birthdate ? $modelProfile->birthdate->format('Y-m-d') : '') }}">
                @error('birthdate')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="age" class="{{ $rowLabel }}">年齢（自動計算）</label>
                <input type="number" id="age" name="age" value="{{ old('age', $modelProfile->age) }}"
                       min="1" max="150" readonly
                       class="w-full px-4 py-3 bg-secondary-100 border border-secondary-300 text-secondary-700 text-sm">
                @error('age')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="gender" class="{{ $rowLabel }}">性別</label>
                <select id="gender" name="gender" class="{{ $input }}">
                    <option value="">選択してください</option>
                    <option value="male"   {{ old('gender', $modelProfile->gender) === 'male'   ? 'selected' : '' }}>男性</option>
                    <option value="female" {{ old('gender', $modelProfile->gender) === 'female' ? 'selected' : '' }}>女性</option>
                    <option value="other"  {{ old('gender', $modelProfile->gender) === 'other'  ? 'selected' : '' }}>その他</option>
                </select>
                @error('gender')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="height" class="{{ $rowLabel }}">身長 (cm)</label>
                <input type="number" id="height" name="height"
                       value="{{ old('height', $modelProfile->height) }}" min="1" max="300"
                       class="{{ $input }}">
                @error('height')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="bust" class="{{ $rowLabel }}">B (cm)</label>
                <input type="number" id="bust" name="bust" value="{{ old('bust', $modelProfile->bust) }}"
                       min="1" max="200" placeholder="バスト" class="{{ $input }}">
                @error('bust')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="waist" class="{{ $rowLabel }}">W (cm)</label>
                <input type="number" id="waist" name="waist" value="{{ old('waist', $modelProfile->waist) }}"
                       min="1" max="200" placeholder="ウエスト" class="{{ $input }}">
                @error('waist')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="hip" class="{{ $rowLabel }}">H (cm)</label>
                <input type="number" id="hip" name="hip" value="{{ old('hip', $modelProfile->hip) }}"
                       min="1" max="200" placeholder="ヒップ" class="{{ $input }}">
                @error('hip')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="prefecture" class="{{ $rowLabel }}">都道府県</label>
            <select id="prefecture" name="prefecture" class="{{ $input }}">
                <option value="">選択してください</option>
                @foreach($prefectures as $pref)
                    <option value="{{ $pref }}" {{ old('prefecture', $modelProfile->prefecture) === $pref ? 'selected' : '' }}>{{ $pref }}</option>
                @endforeach
            </select>
            @error('prefecture')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <p class="{{ $rowLabel }}">活動地域</p>
            <p class="text-xs text-secondary-500 mb-2">活動可能な地域にチェックを入れてください。</p>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2 max-h-48 overflow-y-auto border border-secondary-200 p-3 bg-secondary-50">
                @foreach($prefectures as $p)
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="activity_regions[]" value="{{ $p }}"
                               {{ in_array($p, old('activity_regions', $modelProfile->activity_regions ?? [])) ? 'checked' : '' }}
                               class="border-secondary-300 text-secondary-900 focus:ring-secondary-900">
                        <span class="text-sm text-secondary-700">{{ $p }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- セクション: 体型・スタイル --}}
        <div class="border-b border-secondary-200 pb-2 pt-4">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Section 03</p>
            <h2 class="font-display text-base font-semibold text-secondary-900 mt-1">体型・スタイル</h2>
        </div>

        <div>
            <label for="body_type" class="{{ $rowLabel }}">体型</label>
            <select id="body_type" name="body_type" class="{{ $input }} max-w-md">
                <option value="">選択してください</option>
                @foreach($bodyTypes as $b)
                    <option value="{{ $b }}" {{ old('body_type', $modelProfile->body_type) === $b ? 'selected' : '' }}>{{ $b }}</option>
                @endforeach
            </select>
            @error('body_type')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="shoe_size" class="{{ $rowLabel }}">靴のサイズ (cm)</label>
            <select id="shoe_size" name="shoe_size" class="{{ $input }} max-w-md">
                <option value="">選択してください</option>
                @foreach($shoeSizes as $s)
                    <option value="{{ $s }}" {{ old('shoe_size', $modelProfile->shoe_size) === $s ? 'selected' : '' }}>{{ $s }} cm</option>
                @endforeach
            </select>
            @error('shoe_size')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="clothing_size" class="{{ $rowLabel }}">洋服のサイズ</label>
            <select id="clothing_size" name="clothing_size" class="{{ $input }} max-w-md">
                <option value="">選択してください</option>
                @foreach($clothingSizes as $c)
                    <option value="{{ $c }}" {{ old('clothing_size', $modelProfile->clothing_size) === $c ? 'selected' : '' }}>{{ $c }}{{ is_numeric($c) ? '号' : '' }}</option>
                @endforeach
            </select>
            @error('clothing_size')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <p class="{{ $rowLabel }}">モデルタイプ</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                @foreach($modelTypes as $t)
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="model_types[]" value="{{ $t }}"
                               {{ in_array($t, old('model_types', $modelProfile->model_types ?? [])) ? 'checked' : '' }}
                               class="border-secondary-300 text-secondary-900 focus:ring-secondary-900">
                        <span class="text-sm text-secondary-700">{{ $t }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label for="hair_type" class="{{ $rowLabel }}">髪型</label>
            <select id="hair_type" name="hair_type" class="{{ $input }} max-w-md">
                <option value="">選択してください</option>
                <option value="short"      {{ old('hair_type', $modelProfile->hair_type) === 'short'      ? 'selected' : '' }}>ショート</option>
                <option value="medium"     {{ old('hair_type', $modelProfile->hair_type) === 'medium'     ? 'selected' : '' }}>ミディアム</option>
                <option value="long"       {{ old('hair_type', $modelProfile->hair_type) === 'long'       ? 'selected' : '' }}>ロング</option>
                <option value="semi_long"  {{ old('hair_type', $modelProfile->hair_type) === 'semi_long'  ? 'selected' : '' }}>セミロング</option>
                <option value="super_long" {{ old('hair_type', $modelProfile->hair_type) === 'super_long' ? 'selected' : '' }}>スーパーロング</option>
                <option value="other"      {{ old('hair_type', $modelProfile->hair_type) === 'other'      ? 'selected' : '' }}>その他</option>
            </select>
            @error('hair_type')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- セクション: プロフィール --}}
        <div class="border-b border-secondary-200 pb-2 pt-4">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Section 04</p>
            <h2 class="font-display text-base font-semibold text-secondary-900 mt-1">プロフィール</h2>
        </div>

        <div>
            <label for="occupation" class="{{ $rowLabel }}">職業</label>
            <input type="text" id="occupation" name="occupation"
                   value="{{ old('occupation', $modelProfile->occupation) }}"
                   placeholder="例：大学生、会社員" class="{{ $input }}">
            @error('occupation')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="hobbies" class="{{ $rowLabel }}">趣味</label>
            <input type="text" id="hobbies" name="hobbies"
                   value="{{ old('hobbies', $modelProfile->hobbies) }}"
                   placeholder="例：料理、読書" class="{{ $input }}">
            @error('hobbies')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="bio" class="{{ $rowLabel }}">自己紹介</label>
            <textarea id="bio" name="bio" rows="5" placeholder="自己紹介を入力してください"
                      class="{{ $input }} resize-y leading-relaxed">{{ old('bio', $modelProfile->bio) }}</textarea>
            @error('bio')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="experience" class="{{ $rowLabel }}">経験・実績</label>
            <textarea id="experience" name="experience" rows="4" placeholder="経験や実績を入力してください"
                      class="{{ $input }} resize-y leading-relaxed">{{ old('experience', $modelProfile->experience) }}</textarea>
            @error('experience')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="portfolio_url" class="{{ $rowLabel }}">ポートフォリオ URL</label>
            <input type="url" id="portfolio_url" name="portfolio_url"
                   value="{{ old('portfolio_url', $modelProfile->portfolio_url) }}"
                   placeholder="https://example.com" class="{{ $input }}">
            @error('portfolio_url')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="sns_links_input" class="{{ $rowLabel }}">SNS リンク（カンマ区切り）</label>
            <input type="text" id="sns_links_input" name="sns_links_input"
                   value="{{ old('sns_links_input', is_array($modelProfile->sns_links) ? implode(',', $modelProfile->sns_links) : '') }}"
                   placeholder="https://twitter.com/example, https://instagram.com/example"
                   class="{{ $input }}">
            <p class="text-xs text-secondary-500 mt-2">カンマ区切りで複数の URL を入力できます。</p>
        </div>

        <div>
            <label for="style_tags_input" class="{{ $rowLabel }}">スタイルタグ（カンマ区切り）</label>
            <input type="text" id="style_tags_input" name="style_tags_input"
                   value="{{ old('style_tags_input', is_array($modelProfile->style_tags) ? implode(',', $modelProfile->style_tags) : '') }}"
                   placeholder="例：清楚,クール,セクシー" class="{{ $input }}">
            <p class="text-xs text-secondary-500 mt-2">カンマ区切りで複数のタグを入力できます。</p>
        </div>

        <div>
            <label for="pose_ranges_input" class="{{ $rowLabel }}">ポーズ範囲（カンマ区切り）</label>
            <input type="text" id="pose_ranges_input" name="pose_ranges_input"
                   value="{{ old('pose_ranges_input', is_array($modelProfile->pose_ranges) ? implode(',', $modelProfile->pose_ranges) : '') }}"
                   placeholder="例：全身,バストアップ,顔" class="{{ $input }}">
            <p class="text-xs text-secondary-500 mt-2">カンマ区切りで複数の範囲を入力できます。</p>
        </div>

        {{-- セクション: 条件・公開設定 --}}
        <div class="border-b border-secondary-200 pb-2 pt-4">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Section 05</p>
            <h2 class="font-display text-base font-semibold text-secondary-900 mt-1">条件・公開設定</h2>
        </div>

        <div>
            <p class="{{ $rowLabel }}">避けたい仕事・参考条件</p>
            <div class="border-l-2 border-error-500 bg-canvas-50 px-4 py-3 mb-3 text-sm text-secondary-700 leading-relaxed">
                避けたい仕事に合致する項目があればチェックしてください。
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2">
                <div class="space-y-2">
                    @foreach(array_slice($avoidWorkTypes, 0, 5) as $opt)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="avoid_work_types[]" value="{{ $opt }}"
                                   {{ in_array($opt, old('avoid_work_types', $modelProfile->avoid_work_types ?? [])) ? 'checked' : '' }}
                                   class="border-secondary-300 text-secondary-900 focus:ring-secondary-900">
                            <span class="text-sm text-secondary-700">{{ $opt }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="space-y-2">
                    @foreach(array_slice($avoidWorkTypes, 5, 5) as $opt)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="avoid_work_types[]" value="{{ $opt }}"
                                   {{ in_array($opt, old('avoid_work_types', $modelProfile->avoid_work_types ?? [])) ? 'checked' : '' }}
                                   class="border-secondary-300 text-secondary-900 focus:ring-secondary-900">
                            <span class="text-sm text-secondary-700">{{ $opt }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="reward_min" class="{{ $rowLabel }}">報酬下限（円）</label>
                <input type="number" id="reward_min" name="reward_min"
                       value="{{ old('reward_min', $modelProfile->reward_min) }}" min="0"
                       class="{{ $input }}">
                @error('reward_min')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="reward_max" class="{{ $rowLabel }}">報酬上限（円）</label>
                <input type="number" id="reward_max" name="reward_max"
                       value="{{ old('reward_max', $modelProfile->reward_max) }}" min="0"
                       class="{{ $input }}">
                @error('reward_max')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>
        </div>

        <div id="identity">
            <p class="{{ $rowLabel }}">本人確認</p>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="identity_verified" value="1"
                       {{ old('identity_verified', $modelProfile->identity_verified) ? 'checked' : '' }}
                       class="border-secondary-300 text-secondary-900 focus:ring-secondary-900">
                <span class="text-sm text-secondary-700">本人確認済み（書類提出・確認完了）</span>
            </label>
            <p class="text-xs text-secondary-500 mt-2">確認済みの場合はチェックを入れてください。</p>
        </div>

        <div>
            <label for="terms_text" class="{{ $rowLabel }}">取引条件</label>
            <textarea id="terms_text" name="terms_text" rows="3"
                      placeholder="例：総合評価が-1以下のクライアントからのオファーを受け付けない。"
                      class="{{ $input }} resize-y leading-relaxed">{{ old('terms_text', $modelProfile->terms_text) }}</textarea>
            @error('terms_text')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            <p class="text-xs text-secondary-500 mt-2">クライアントへの取引条件を自由に記載できます。未記入の場合は「特になし」等で表示されます。</p>
        </div>

        <div class="space-y-3 pt-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="online_available" value="1"
                       {{ old('online_available', $modelProfile->online_available) ? 'checked' : '' }}
                       class="border-error-400 text-error-600 focus:ring-error-500 accent-error-600">
                <span class="text-sm text-secondary-700">オンライン対応可能</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_public" value="1"
                       {{ old('is_public', $modelProfile->is_public) ? 'checked' : '' }}
                       class="border-error-400 text-error-600 focus:ring-error-500 accent-error-600">
                <span class="text-sm text-secondary-700">プロフィールを公開する</span>
            </label>
        </div>

        {{-- 送信 --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-4 border-t border-secondary-200">
            <a href="{{ route('mypage') }}"
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

<script>
function updateDays() {
    const year = document.getElementById('birth_year').value;
    const month = document.getElementById('birth_month').value;
    const daySelect = document.getElementById('birth_day');
    const currentDay = daySelect.value;

    while (daySelect.options.length > 1) daySelect.remove(1);

    if (year && month) {
        const daysInMonth = new Date(parseInt(year), parseInt(month), 0).getDate();
        for (let day = 1; day <= daysInMonth; day++) {
            const option = document.createElement('option');
            option.value = String(day).padStart(2, '0');
            option.textContent = day;
            if (currentDay && currentDay === option.value) option.selected = true;
            daySelect.appendChild(option);
        }
    }
    updateBirthdate();
}

function updateBirthdate() {
    const year = document.getElementById('birth_year').value;
    const month = document.getElementById('birth_month').value;
    const day = document.getElementById('birth_day').value;
    const birthdateInput = document.getElementById('birthdate');
    const ageInput = document.getElementById('age');

    if (year && month && day) {
        const date = new Date(year, parseInt(month) - 1, day);
        if (date.getFullYear() == year && date.getMonth() == parseInt(month) - 1 && date.getDate() == day) {
            birthdateInput.value = `${year}-${month}-${day}`;
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

function calculateAge() {
    const birthdateInput = document.getElementById('birthdate');
    const ageInput = document.getElementById('age');

    if (birthdateInput.value) {
        const birthdate = new Date(birthdateInput.value);
        const today = new Date();
        let age = today.getFullYear() - birthdate.getFullYear();
        const monthDiff = today.getMonth() - birthdate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) age--;
        ageInput.value = age;
    } else {
        ageInput.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('birth_year').addEventListener('change', updateDays);
    document.getElementById('birth_month').addEventListener('change', updateDays);
    updateDays();
    updateBirthdate();
});

document.querySelector('form').addEventListener('submit', function(e) {
    const styleTagsInput = document.querySelector('input[name="style_tags_input"]');
    const poseRangesInput = document.querySelector('input[name="pose_ranges_input"]');

    if (styleTagsInput && styleTagsInput.value) {
        const tags = styleTagsInput.value.split(',').map(t => t.trim()).filter(Boolean);
        tags.forEach((tag, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `style_tags[${index}]`;
            input.value = tag;
            this.appendChild(input);
        });
    }

    if (poseRangesInput && poseRangesInput.value) {
        const ranges = poseRangesInput.value.split(',').map(r => r.trim()).filter(Boolean);
        ranges.forEach((range, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `pose_ranges[${index}]`;
            input.value = range;
            this.appendChild(input);
        });
    }
});

// ポートフォリオ画像の操作は別画面（model.portfolio.edit）に分離済み
</script>
@endsection
