@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-2xl font-bold mb-1">
        <span class="text-gray-400">&gt;</span> プロフィールの登録
    </h1>
    <hr class="border-gray-200 mb-6">
    <h2 class="text-lg font-medium text-gray-700 mb-4">プロフィール情報</h2>
    <hr class="border-t-2 border-gray-300 mb-6">

    <form action="{{ route('model.profile.store') }}" method="POST" enctype="multipart/form-data" class="relative">
        @csrf
        {{-- 項目名と項目欄の間の縦線（一直線） --}}
        <div class="hidden md:block absolute top-0 bottom-0 left-[180px] w-px bg-gray-200 z-0" aria-hidden="true"></div>

        {{-- モデル名 --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-2 md:gap-y-0 md:items-center pb-6 relative z-10">
            <div class="flex items-center gap-2 md:pr-6">
                <label for="display_name" class="text-sm font-medium text-gray-700">モデル名</label>
                <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
            </div>
            <div>
                <input type="text" id="display_name" name="display_name"
                    value="{{ old('display_name', auth()->user()?->name) }}"
                    class="w-full md:max-w-md px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('display_name') border-red-500 @enderror">
                @error('display_name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- 生年月日 --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-2 md:gap-y-0 md:items-start py-6 relative z-10">
            <div class="flex items-center gap-2 md:pr-6">
                <label class="text-sm font-medium text-gray-700">生年月日</label>
                <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
            </div>
            <div class="space-y-3">
                <div class="bg-amber-50 border border-amber-200 rounded-md px-4 py-3 text-sm text-amber-800">
                    20歳未満の方は、保護者の方・所属事務所の確認書類の提出をお願いいたします。事務局にて書類を確認した後に、プロフィールを公開いたします。
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <select name="birth_year" id="birth_year" required
                        class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        onchange="updateDays()">
                        <option value="">年</option>
                        @for($y = (int) date('Y'); $y >= 1900; $y--)
                            <option value="{{ $y }}" {{ old('birth_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <span class="text-gray-500">年</span>
                    <select name="birth_month" id="birth_month" required
                        class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        onchange="updateDays()">
                        <option value="">月</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ old('birth_month') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endfor
                    </select>
                    <span class="text-gray-500">月</span>
                    <select name="birth_day" id="birth_day" required
                        class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        data-old="{{ old('birth_day') }}">
                        <option value="">日</option>
                    </select>
                    <span class="text-gray-500">日</span>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                @if($errors->hasAny(['birth_year','birth_month','birth_day']))
                    <p class="mt-1 text-xs text-red-600">{{ $errors->first('birth_year') ?? $errors->first('birth_month') ?? $errors->first('birth_day') }}</p>
                @endif
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- 性別 --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-2 md:gap-y-0 md:items-center py-6 relative z-10">
            <div class="flex items-center gap-2 md:pr-6">
                <label class="text-sm font-medium text-gray-700">性別</label>
                <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
            </div>
            <div>
                <div class="flex gap-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="gender" value="female" {{ old('gender') === 'female' ? 'checked' : '' }}
                            class="text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">女性</span>
                    </label>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="gender" value="male" {{ old('gender') === 'male' ? 'checked' : '' }}
                            class="text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">男性</span>
                    </label>
                </div>
                @error('gender')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- 所在地 --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-2 md:gap-y-0 md:items-center py-6 relative z-10">
            <div class="flex items-center gap-2 md:pr-6">
                <label for="prefecture" class="text-sm font-medium text-gray-700">所在地</label>
                <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
            </div>
            <div>
                <select name="prefecture" id="prefecture" required
                    class="w-full md:max-w-md px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('prefecture') border-red-500 @enderror">
                    <option value="">選択して下さい</option>
                    @foreach($prefectures as $p)
                        <option value="{{ $p }}" {{ old('prefecture') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
                @error('prefecture')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- 活動地域 --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-3 md:gap-y-0 md:items-start py-6 relative z-10">
            <label class="text-sm font-medium text-gray-700 pt-1 md:pr-6">活動地域</label>
            <div class="grid grid-cols-3 gap-x-4 gap-y-2">
                @foreach($prefectures as $p)
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="activity_regions[]" value="{{ $p }}"
                            {{ in_array($p, old('activity_regions', [])) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">{{ $p }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- 参考価格 --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-2 md:gap-y-0 md:items-start py-6 relative z-10">
            <div class="flex items-center gap-2 md:pr-6">
                <label for="reference_price" class="text-sm font-medium text-gray-700">参考価格</label>
                <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
            </div>
            <div class="space-y-3">
                <div class="bg-sky-50 border border-sky-200 rounded-md px-4 py-3 text-sm text-sky-800">
                    1日あたりの参考価格(日給)を「1000円以上」で記入してください。※モデルタウンにおける平均参考価格は8838.3円です。
                </div>
                <div class="flex items-center gap-2">
                    <input type="number" id="reference_price" name="reference_price" min="1000" step="1"
                        value="{{ old('reference_price', 5000) }}"
                        class="w-32 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('reference_price') border-red-500 @enderror">
                    <span class="text-sm text-gray-600">円</span>
                </div>
                @error('reference_price')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- モデルタイプ（複数選択可） --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-3 md:gap-y-0 md:items-start py-6 relative z-10">
            <label class="text-sm font-medium text-gray-700 pt-1 md:pr-6">モデルタイプ</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                @foreach($modelTypes as $t)
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="model_types[]" value="{{ $t }}"
                            {{ in_array($t, old('model_types', [])) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">{{ $t }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- 身長 --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-2 md:gap-y-0 md:items-center py-6 relative z-10">
            <div class="flex items-center gap-2 md:pr-6">
                <label for="height" class="text-sm font-medium text-gray-700">身長</label>
                <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <input type="number" id="height" name="height" min="1" max="300" required
                        value="{{ old('height') }}"
                        class="w-24 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('height') border-red-500 @enderror">
                    <span class="text-sm text-gray-600">cm</span>
                </div>
                @error('height')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- 靴のサイズ --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-2 md:gap-y-0 md:items-center py-6 relative z-10">
            <div class="flex items-center gap-2 md:pr-6">
                <label for="shoe_size" class="text-sm font-medium text-gray-700">靴のサイズ</label>
                <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
            </div>
            <div>
                <select name="shoe_size" id="shoe_size" required
                    class="w-full md:max-w-xs px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('shoe_size') border-red-500 @enderror">
                    <option value="">選択して下さい</option>
                    @foreach($shoeSizes as $s)
                        <option value="{{ $s }}" {{ old('shoe_size') === $s ? 'selected' : '' }}>{{ $s }} cm</option>
                    @endforeach
                </select>
                @error('shoe_size')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- 洋服のサイズ --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-2 md:gap-y-0 md:items-center py-6 relative z-10">
            <div class="flex items-center gap-2 md:pr-6">
                <label for="clothing_size" class="text-sm font-medium text-gray-700">洋服のサイズ</label>
                <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
            </div>
            <div>
                <select name="clothing_size" id="clothing_size" required
                    class="w-full md:max-w-xs px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('clothing_size') border-red-500 @enderror">
                    <option value="">選択して下さい</option>
                    @foreach($clothingSizes as $c)
                        <option value="{{ $c }}" {{ old('clothing_size') === $c ? 'selected' : '' }}>{{ $c }}{{ is_numeric($c) ? '号' : '' }}</option>
                    @endforeach
                </select>
                @error('clothing_size')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- 体型 --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-2 md:gap-y-0 md:items-center py-6 relative z-10">
            <div class="flex items-center gap-2 md:pr-6">
                <label for="body_type" class="text-sm font-medium text-gray-700">体型</label>
                <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
            </div>
            <div>
                <select name="body_type" id="body_type" required
                    class="w-full md:max-w-xs px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('body_type') border-red-500 @enderror">
                    <option value="">選択して下さい</option>
                    @foreach($bodyTypes as $b)
                        <option value="{{ $b }}" {{ old('body_type') === $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
                @error('body_type')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- 髪型 --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-2 md:gap-y-0 md:items-center py-6 relative z-10">
            <div class="flex items-center gap-2 md:pr-6">
                <label for="hair_type" class="text-sm font-medium text-gray-700">髪型</label>
                <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
            </div>
            <div>
                <select name="hair_type" id="hair_type" required
                    class="w-full md:max-w-xs px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('hair_type') border-red-500 @enderror">
                    <option value="">選択して下さい</option>
                    <option value="short" {{ old('hair_type') === 'short' ? 'selected' : '' }}>ショート</option>
                    <option value="medium" {{ old('hair_type') === 'medium' ? 'selected' : '' }}>ミディアム</option>
                    <option value="long" {{ old('hair_type') === 'long' ? 'selected' : '' }}>ロング</option>
                    <option value="other" {{ old('hair_type') === 'other' ? 'selected' : '' }}>その他</option>
                </select>
                @error('hair_type')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- 職業 --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-2 md:gap-y-0 md:items-center py-6 relative z-10">
            <div class="flex items-center gap-2 md:pr-6">
                <label for="occupation" class="text-sm font-medium text-gray-700">職業</label>
                <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
            </div>
            <div>
                <input type="text" id="occupation" name="occupation" required
                    value="{{ old('occupation') }}" placeholder="例：大学生、会社員"
                    class="w-full md:max-w-md px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('occupation') border-red-500 @enderror">
                @error('occupation')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- 趣味 --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-2 md:gap-y-0 md:items-center py-6 relative z-10">
            <div class="flex items-center gap-2 md:pr-6">
                <label for="hobbies" class="text-sm font-medium text-gray-700">趣味</label>
                <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
            </div>
            <div>
                <input type="text" id="hobbies" name="hobbies" required
                    value="{{ old('hobbies') }}" placeholder="例：料理、読書"
                    class="w-full md:max-w-md px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('hobbies') border-red-500 @enderror">
                @error('hobbies')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- メイン画像 必須 (縦横比4:3推奨) --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-2 md:gap-y-0 md:items-start py-6 relative z-10">
            <div class="flex items-center gap-2 md:pr-6">
                <label for="main_image" class="text-sm font-medium text-gray-700">メイン画像</label>
                <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
                <span class="text-xs text-gray-500">(縦横比4:3推奨)</span>
            </div>
            <div class="space-y-3">
                <div class="bg-sky-50 border border-sky-200 rounded-md px-4 py-3 text-sm text-sky-800">
                    サイト上に表示するプロフィール画像(縦横比4:3を推奨)を、アップロードしてください。jpgまたはpng形式、縦横2000ピクセル以下、容量1.5MB以下の画像ファイルをアップロードできます。
                </div>
                <div class="flex items-center gap-2">
                    <input type="file" id="main_image" name="main_image" accept="image/jpeg,image/png,image/jpg" required
                        class="hidden @error('main_image') border-red-500 @enderror" onchange="updateMainFileLabel(this)">
                    <label for="main_image" class="cursor-pointer inline-flex items-center px-4 py-2 border rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 @error('main_image') border-red-500 @enderror border-gray-300">
                        ファイルを選択
                    </label>
                    <span id="main_file_label" class="text-sm text-gray-500">選択されていません</span>
                </div>
                @error('main_image')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- サブ画像 (縦横比4:3推奨) --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-2 md:gap-y-0 md:items-start py-6 relative z-10">
            <label class="text-sm font-medium text-gray-700 pt-1 md:pr-6">サブ画像</label>
            <div class="space-y-4">
                <div class="bg-sky-50 border border-sky-200 rounded-md px-4 py-3 text-sm text-sky-800">
                    クライアントに伝わりやすいように、全身・バストアップの2種類は必ず登録しましょう! その他にも、パーツや横顔など、画像の登録数が多い方が相手にイメージが伝わりやすくなります。jpgまたはpng形式、縦横2000ピクセル以下、容量1.5MB以下の画像ファイルをアップロードできます。
                </div>
                @for($i = 1; $i <= 7; $i++)
                    <div class="flex items-center gap-2 {{ $i > 1 ? 'border-t border-dashed border-gray-300 pt-4' : '' }}">
                        <input type="file" id="sub_image_{{ $i }}" name="sub_images[]" accept="image/jpeg,image/png,image/jpg"
                            class="hidden" onchange="updateSubFileLabel({{ $i }}, this)">
                        <label for="sub_image_{{ $i }}" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            ファイルを選択
                        </label>
                        <span id="sub_file_label_{{ $i }}" class="text-sm text-gray-500">選択されていません</span>
                    </div>
                @endfor
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- 参考条件 --}}
        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-3 md:gap-y-0 md:items-start py-6 relative z-10">
            <div class="flex items-center gap-2 md:pr-6">
                <label class="text-sm font-medium text-gray-700">参考条件</label>
                <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
            </div>
            <div class="space-y-3">
                <div class="bg-rose-50 border border-rose-200 rounded-md px-4 py-3 text-sm text-rose-800">
                    あなたが避けたい仕事に合致する項目があればチェックを入れてください。詳細
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
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">{{ $opt }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="space-y-2">
                        @foreach($avoidRight as $opt)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="avoid_work_types[]" value="{{ $opt }}"
                                    {{ in_array($opt, old('avoid_work_types', [])) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">{{ $opt }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-gray-200 relative z-10">

        {{-- コメント：自己紹介(コメント) --}}
        <div class="space-y-4 pt-6">
            <h2 class="text-lg font-medium text-gray-700">コメント</h2>
            <hr class="border-gray-200 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-3 md:gap-y-0 md:items-start relative z-10">
                <div class="flex items-center gap-2 md:pr-6">
                    <label for="bio" class="text-sm font-medium text-gray-700">自己紹介(コメント)</label>
                    <span class="inline-block px-1.5 py-0.5 text-xs font-bold text-red-600 border border-red-500 rounded">必須</span>
                </div>
                <div class="space-y-3">
                    <div class="bg-rose-50 border border-rose-200 rounded-md px-4 py-3 text-sm text-red-600">
                        別サイトへ誘導する内容や、直接の連絡方法(SNSやLINEのアカウントなどを含む)を書くことは禁止しています。ルールを守ってご利用くださいますようお願い致します。
                    </div>
                    <textarea id="bio" name="bio" rows="6" required
                        placeholder="自己紹介を入力してください"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 resize-y @error('bio') border-red-500 @enderror">{{ old('bio') }}</textarea>
                    @error('bio')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <hr class="border-gray-200 mt-6">

        <div class="pt-6 flex justify-end">
            <button type="submit"
                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                登録する
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
