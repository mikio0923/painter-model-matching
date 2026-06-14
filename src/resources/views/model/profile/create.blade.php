@extends('layouts.app')

@section('title', 'プロフィール登録')

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
            <span class="page-header-breadcrumb-current">プロフィール登録</span>
        </div>
        <p class="page-header-subtitle">Create Profile</p>
        <h1 class="page-header-title mt-2">プロフィール登録</h1>
        <p class="text-secondary-500 text-sm mt-3">あなたを表す情報を登録してください。</p>
    </div>
</div>

<div class="page-narrow">

    @php
        $required = '<span class="text-error-500 ml-1">*</span>';
        $rowLabel = 'block text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-2';
        $input    = 'w-full px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900 transition-colors duration-200';
        $select   = $input;
    @endphp

    <form action="{{ route('model.profile.store') }}" method="POST" enctype="multipart/form-data"
          class="border border-secondary-200 bg-canvas-50 p-5 sm:p-8 space-y-6">
        @csrf

        {{-- セクション: 基本情報 --}}
        <div class="border-b border-secondary-200 pb-2">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Section 01</p>
            <h2 class="font-display text-base font-semibold text-secondary-900 mt-1">基本情報</h2>
        </div>

        {{-- モデル名 --}}
        <div>
            <label for="display_name" class="{{ $rowLabel }}">
                モデル名 <span class="text-error-500">*</span>
            </label>
            <input type="text" id="display_name" name="display_name"
                   value="{{ old('display_name', auth()->user()?->name) }}"
                   class="{{ $input }}">
            @error('display_name')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- 生年月日 --}}
        <div>
            <p class="{{ $rowLabel }}">生年月日 <span class="text-error-500">*</span></p>
            <div class="border-l-2 border-warning-500 bg-canvas-50 px-4 py-3 mb-3 text-sm text-secondary-700 leading-relaxed">
                <p class="text-[10px] uppercase tracking-[0.3em] text-warning-700 mb-1">Notice</p>
                20歳未満の方は、保護者の方・所属事務所の確認書類の提出をお願いいたします。事務局にて書類を確認した後にプロフィールを公開します。
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <select name="birth_year" id="birth_year" required onchange="updateDays()" class="px-3 py-2 border border-secondary-300 bg-canvas-50 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900">
                    <option value="">年</option>
                    @for($y = (int) date('Y'); $y >= 1900; $y--)
                        <option value="{{ $y }}" {{ old('birth_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <span class="text-secondary-500 text-sm">年</span>
                <select name="birth_month" id="birth_month" required onchange="updateDays()" class="px-3 py-2 border border-secondary-300 bg-canvas-50 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900">
                    <option value="">月</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ old('birth_month') == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endfor
                </select>
                <span class="text-secondary-500 text-sm">月</span>
                <select name="birth_day" id="birth_day" required data-old="{{ old('birth_day') }}" class="px-3 py-2 border border-secondary-300 bg-canvas-50 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900">
                    <option value="">日</option>
                </select>
                <span class="text-secondary-500 text-sm">日</span>
            </div>
            @if($errors->hasAny(['birth_year','birth_month','birth_day']))
                <p class="text-xs text-error-600 mt-2">{{ $errors->first('birth_year') ?? $errors->first('birth_month') ?? $errors->first('birth_day') }}</p>
            @endif
        </div>

        {{-- 性別 --}}
        <div>
            <p class="{{ $rowLabel }}">性別 <span class="text-error-500">*</span></p>
            <div class="flex gap-6">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="gender" value="female" {{ old('gender') === 'female' ? 'checked' : '' }}
                           class="text-secondary-900 border-secondary-300 focus:ring-secondary-900">
                    <span class="text-sm text-secondary-700">女性</span>
                </label>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="gender" value="male" {{ old('gender') === 'male' ? 'checked' : '' }}
                           class="text-secondary-900 border-secondary-300 focus:ring-secondary-900">
                    <span class="text-sm text-secondary-700">男性</span>
                </label>
            </div>
            @error('gender')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- 所在地 --}}
        <div>
            <label for="prefecture" class="{{ $rowLabel }}">
                所在地 <span class="text-error-500">*</span>
            </label>
            <select name="prefecture" id="prefecture" required class="{{ $select }}">
                <option value="">選択して下さい</option>
                @foreach($prefectures as $p)
                    <option value="{{ $p }}" {{ old('prefecture') === $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
            @error('prefecture')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- 活動地域 --}}
        <div>
            <p class="{{ $rowLabel }}">活動地域</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-2">
                @foreach($prefectures as $p)
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="activity_regions[]" value="{{ $p }}"
                               {{ in_array($p, old('activity_regions', [])) ? 'checked' : '' }}
                               class="border-secondary-300 text-secondary-900 focus:ring-secondary-900">
                        <span class="text-sm text-secondary-700">{{ $p }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- 参考価格 --}}
        <div>
            <label for="reference_price" class="{{ $rowLabel }}">
                参考価格（1日あたり） <span class="text-error-500">*</span>
            </label>
            <div class="border-l-2 border-secondary-400 bg-canvas-50 px-4 py-3 mb-3 text-sm text-secondary-700 leading-relaxed">
                1000円以上で記入してください。Palette における平均参考価格は約 8,800 円です。
            </div>
            <div class="flex items-center gap-2">
                <input type="number" id="reference_price" name="reference_price" min="1000" step="1"
                       value="{{ old('reference_price', 5000) }}"
                       class="w-40 px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900 transition-colors duration-200">
                <span class="text-sm text-secondary-600">円</span>
            </div>
            @error('reference_price')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- セクション: 体格・スタイル --}}
        <div class="border-b border-secondary-200 pb-2 pt-4">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Section 02</p>
            <h2 class="font-display text-base font-semibold text-secondary-900 mt-1">体格・スタイル</h2>
        </div>

        {{-- モデルタイプ --}}
        <div>
            <p class="{{ $rowLabel }}">モデルタイプ</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                @foreach($modelTypes as $t)
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="model_types[]" value="{{ $t }}"
                               {{ in_array($t, old('model_types', [])) ? 'checked' : '' }}
                               class="border-secondary-300 text-secondary-900 focus:ring-secondary-900">
                        <span class="text-sm text-secondary-700">{{ $t }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- 身長 --}}
        <div>
            <label for="height" class="{{ $rowLabel }}">身長 <span class="text-error-500">*</span></label>
            <div class="flex items-center gap-2">
                <input type="number" id="height" name="height" min="1" max="300" required
                       value="{{ old('height') }}"
                       class="w-32 px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900 transition-colors duration-200">
                <span class="text-sm text-secondary-600">cm</span>
            </div>
            @error('height')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- 靴のサイズ --}}
        <div>
            <label for="shoe_size" class="{{ $rowLabel }}">靴のサイズ <span class="text-error-500">*</span></label>
            <select name="shoe_size" id="shoe_size" required class="{{ $select }} max-w-xs">
                <option value="">選択して下さい</option>
                @foreach($shoeSizes as $s)
                    <option value="{{ $s }}" {{ old('shoe_size') === $s ? 'selected' : '' }}>{{ $s }} cm</option>
                @endforeach
            </select>
            @error('shoe_size')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- 洋服のサイズ --}}
        <div>
            <label for="clothing_size" class="{{ $rowLabel }}">洋服のサイズ <span class="text-error-500">*</span></label>
            <select name="clothing_size" id="clothing_size" required class="{{ $select }} max-w-xs">
                <option value="">選択して下さい</option>
                @foreach($clothingSizes as $c)
                    <option value="{{ $c }}" {{ old('clothing_size') === $c ? 'selected' : '' }}>{{ $c }}{{ is_numeric($c) ? '号' : '' }}</option>
                @endforeach
            </select>
            @error('clothing_size')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- 体型 --}}
        <div>
            <label for="body_type" class="{{ $rowLabel }}">体型 <span class="text-error-500">*</span></label>
            <select name="body_type" id="body_type" required class="{{ $select }} max-w-xs">
                <option value="">選択して下さい</option>
                @foreach($bodyTypes as $b)
                    <option value="{{ $b }}" {{ old('body_type') === $b ? 'selected' : '' }}>{{ $b }}</option>
                @endforeach
            </select>
            @error('body_type')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- 髪型 --}}
        <div>
            <label for="hair_type" class="{{ $rowLabel }}">髪型 <span class="text-error-500">*</span></label>
            <select name="hair_type" id="hair_type" required class="{{ $select }} max-w-xs">
                <option value="">選択して下さい</option>
                <option value="short" {{ old('hair_type') === 'short' ? 'selected' : '' }}>ショート</option>
                <option value="medium" {{ old('hair_type') === 'medium' ? 'selected' : '' }}>ミディアム</option>
                <option value="long" {{ old('hair_type') === 'long' ? 'selected' : '' }}>ロング</option>
                <option value="semi_long" {{ old('hair_type') === 'semi_long' ? 'selected' : '' }}>セミロング</option>
                <option value="super_long" {{ old('hair_type') === 'super_long' ? 'selected' : '' }}>スーパーロング</option>
                <option value="other" {{ old('hair_type') === 'other' ? 'selected' : '' }}>その他</option>
            </select>
            @error('hair_type')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- セクション: プロフィール --}}
        <div class="border-b border-secondary-200 pb-2 pt-4">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Section 03</p>
            <h2 class="font-display text-base font-semibold text-secondary-900 mt-1">プロフィール</h2>
        </div>

        {{-- 職業 --}}
        <div>
            <label for="occupation" class="{{ $rowLabel }}">職業 <span class="text-error-500">*</span></label>
            <input type="text" id="occupation" name="occupation" required
                   value="{{ old('occupation') }}" placeholder="例：大学生、会社員"
                   class="{{ $input }}">
            @error('occupation')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- 趣味 --}}
        <div>
            <label for="hobbies" class="{{ $rowLabel }}">趣味 <span class="text-error-500">*</span></label>
            <input type="text" id="hobbies" name="hobbies" required
                   value="{{ old('hobbies') }}" placeholder="例：料理、読書"
                   class="{{ $input }}">
            @error('hobbies')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- セクション: 画像 --}}
        <div class="border-b border-secondary-200 pb-2 pt-4">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Section 04</p>
            <h2 class="font-display text-base font-semibold text-secondary-900 mt-1">画像</h2>
        </div>

        {{-- メイン画像 --}}
        <div>
            <p class="{{ $rowLabel }}">メイン画像 <span class="text-error-500">*</span> <span class="text-secondary-400 normal-case tracking-normal text-[11px] ml-2">縦横比 4:3 推奨</span></p>
            <div class="border-l-2 border-secondary-400 bg-canvas-50 px-4 py-3 mb-3 text-sm text-secondary-700 leading-relaxed">
                サイト上に表示するプロフィール画像（縦横比 4:3 推奨）をアップロードしてください。jpg または png 形式、縦横 2000px 以下、容量 1.5MB 以下。
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <input type="file" id="main_image" name="main_image" accept="image/jpeg,image/png,image/jpg" required
                       class="hidden" onchange="updateMainFileLabel(this)">
                <label for="main_image"
                       class="cursor-pointer inline-flex items-center px-5 py-2.5 border border-secondary-400 text-secondary-700 text-xs uppercase tracking-[0.2em] hover:bg-secondary-100 transition-colors duration-200">
                    Choose File
                </label>
                <span id="main_file_label" class="text-sm text-secondary-500">選択されていません</span>
            </div>
            @error('main_image')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- サブ画像 --}}
        <div>
            <p class="{{ $rowLabel }}">サブ画像（最大 7 枚）</p>
            <div class="border-l-2 border-secondary-400 bg-canvas-50 px-4 py-3 mb-3 text-sm text-secondary-700 leading-relaxed">
                クライアントに伝わりやすいよう、全身・バストアップの 2 種類は必ず登録しましょう。パーツや横顔など、登録数が多いほど相手にイメージが伝わりやすくなります。
            </div>
            <div class="space-y-3">
                @for($i = 1; $i <= 7; $i++)
                    <div class="flex items-center gap-3 flex-wrap {{ $i > 1 ? 'pt-3 border-t border-dashed border-secondary-200' : '' }}">
                        <input type="file" id="sub_image_{{ $i }}" name="sub_images[]" accept="image/jpeg,image/png,image/jpg"
                               class="hidden" onchange="updateSubFileLabel({{ $i }}, this)">
                        <label for="sub_image_{{ $i }}"
                               class="cursor-pointer inline-flex items-center px-5 py-2 border border-secondary-400 text-secondary-700 text-[10px] uppercase tracking-[0.2em] hover:bg-secondary-100 transition-colors duration-200">
                            File {{ $i }}
                        </label>
                        <span id="sub_file_label_{{ $i }}" class="text-sm text-secondary-500">選択されていません</span>
                    </div>
                @endfor
            </div>
        </div>

        {{-- セクション: 条件・コメント --}}
        <div class="border-b border-secondary-200 pb-2 pt-4">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Section 05</p>
            <h2 class="font-display text-base font-semibold text-secondary-900 mt-1">条件・コメント</h2>
        </div>

        {{-- 参考条件（避けたい仕事） --}}
        <div>
            <p class="{{ $rowLabel }}">避けたい仕事 <span class="text-error-500">*</span></p>
            <div class="border-l-2 border-error-500 bg-canvas-50 px-4 py-3 mb-3 text-sm text-secondary-700 leading-relaxed">
                避けたい仕事に合致する項目があればチェックしてください。
            </div>
            @php
                $avoidLeft = ['専属契約', '水着撮影', '衣装チェンジ(着替え)', '商用ストックフォト', '撮影データの販売'];
                $avoidRight = ['スカウト', '露出度の高い衣装', '個室での撮影', '長期に渡る撮影', '撮影データの私的利用(SNS投稿など)'];
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2">
                <div class="space-y-2">
                    @foreach($avoidLeft as $opt)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="avoid_work_types[]" value="{{ $opt }}"
                                   {{ in_array($opt, old('avoid_work_types', [])) ? 'checked' : '' }}
                                   class="border-secondary-300 text-secondary-900 focus:ring-secondary-900">
                            <span class="text-sm text-secondary-700">{{ $opt }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="space-y-2">
                    @foreach($avoidRight as $opt)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="avoid_work_types[]" value="{{ $opt }}"
                                   {{ in_array($opt, old('avoid_work_types', [])) ? 'checked' : '' }}
                                   class="border-secondary-300 text-secondary-900 focus:ring-secondary-900">
                            <span class="text-sm text-secondary-700">{{ $opt }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- 自己紹介 --}}
        <div>
            <label for="bio" class="{{ $rowLabel }}">自己紹介 <span class="text-error-500">*</span></label>
            <div class="border-l-2 border-error-500 bg-canvas-50 px-4 py-3 mb-3 text-sm text-secondary-700 leading-relaxed">
                別サイトへ誘導する内容や、直接の連絡方法（SNS や LINE のアカウントを含む）を書くことは禁止しています。
            </div>
            <textarea id="bio" name="bio" rows="6" required
                      placeholder="自己紹介を入力してください"
                      class="{{ $input }} resize-y leading-relaxed">{{ old('bio') }}</textarea>
            @error('bio')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- 送信 --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-4 border-t border-secondary-200">
            <a href="{{ route('mypage') }}"
               class="order-2 sm:order-1 px-6 py-2.5 border border-secondary-400 text-secondary-700 text-xs uppercase tracking-[0.2em] hover:bg-secondary-100 transition-colors duration-200 text-center">
                Cancel
            </a>
            <button type="submit"
                    class="order-1 sm:order-2 px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                Save Profile
            </button>
        </div>
    </form>
</div>

<script>
function updateDays() {
    const y = document.getElementById('birth_year').value;
    const m = document.getElementById('birth_month').value;
    const sel = document.getElementById('birth_day');
    const oldDay = sel.value || (sel.dataset.old || '');
    sel.innerHTML = '<option value="">日</option>';
    if (!y || !m) return;
    const days = new Date(parseInt(y, 10), parseInt(m, 10), 0).getDate();
    for (let d = 1; d <= days; d++) {
        const o = document.createElement('option');
        o.value = d;
        o.textContent = d;
        if (String(d) === oldDay) o.selected = true;
        sel.appendChild(o);
    }
}
function updateMainFileLabel(input) {
    const el = document.getElementById('main_file_label');
    el.textContent = input.files?.length ? input.files[0].name : '選択されていません';
}
function updateSubFileLabel(i, input) {
    const el = document.getElementById('sub_file_label_' + i);
    el.textContent = input.files?.length ? input.files[0].name : '選択されていません';
}
document.addEventListener('DOMContentLoaded', function() { updateDays(); });
</script>
@endsection
