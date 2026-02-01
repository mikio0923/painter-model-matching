<x-guest-layout>
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h2 class="text-xl font-bold mb-6">
            > 新規会員登録
            @if(isset($role) && $role === 'model')
                (モデルの方)
            @elseif(isset($role) && $role === 'painter')
                (クライアントの方)
            @endif
        </h2>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="card">
            <div class="card-body">
            @csrf

            <!-- Role (hidden, set from URL parameter) -->
            <input type="hidden" name="role" value="{{ old('role', $role ?? 'model') }}">

            <table class="w-full mb-4">
                {{-- あなたの名前 --}}
                <tr class="border-b border-secondary-200">
                    <td class="py-3 pr-4 text-sm font-medium text-secondary-700 w-1/3">
                        あなたの名前
                        <span class="text-red-500">*</span>
                    </td>
                    <td class="py-3">
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name"
                               class="form-input">
                        <p class="text-xs text-secondary-500 mt-1">※サイトには公開されません。</p>
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>

                @if(isset($role) && $role === 'model')
                    {{-- 生年月日（モデルのみ） --}}
                    <tr class="border-b border-secondary-200">
                        <td class="py-3 pr-4 text-sm font-medium text-secondary-700">
                            生年月日
                            <span class="text-red-500">*</span>
                        </td>
                        <td class="py-3">
                            <div class="bg-orange-100 border border-orange-300 rounded p-3 mb-3 text-sm text-orange-800">
                                20歳未満の方は、保護者の方・所属事務所の確認書類の提出をお願いいたします。事務局にて書類を確認した後に、プロフィールを公開いたします。
                            </div>
                            <div class="flex gap-2 items-center">
                                @php
                                    $birthYear = old('birth_year', '');
                                    $birthMonth = old('birth_month', '');
                                    $birthDay = old('birth_day', '');
                                    $currentYear = date('Y');
                                @endphp
                                <select id="birth_year" 
                                        name="birth_year"
                                        required
                                        class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        style="min-width: 100px;">
                                    <option value="">年</option>
                                    @for($year = $currentYear; $year >= 1900; $year--)
                                        <option value="{{ $year }}" {{ $birthYear == $year ? 'selected' : '' }}>{{ $year }} 年</option>
                                    @endfor
                                </select>
                                <select id="birth_month" 
                                        name="birth_month"
                                        required
                                        class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        style="min-width: 80px;">
                                    <option value="">月</option>
                                    @for($month = 1; $month <= 12; $month++)
                                        <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}" {{ $birthMonth == str_pad($month, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $month }} 月</option>
                                    @endfor
                                </select>
                                <select id="birth_day" 
                                        name="birth_day"
                                        required
                                        class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        style="min-width: 80px;">
                                    <option value="">日</option>
                                    @for($day = 1; $day <= 31; $day++)
                                        <option value="{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}" {{ $birthDay == str_pad($day, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $day }} 日</option>
                                    @endfor
                                </select>
                            </div>
                            <input type="hidden" id="birthdate" name="birthdate" value="{{ old('birthdate') }}">
                            @error('birthdate')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>

                    {{-- 性別（モデルのみ） --}}
                    <tr class="border-b border-secondary-200">
                        <td class="py-3 pr-4 text-sm font-medium text-secondary-700">
                            性別
                            <span class="text-red-500">*</span>
                        </td>
                        <td class="py-3">
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="gender" 
                                           value="男性" 
                                           {{ old('gender') === '男性' ? 'checked' : '' }}
                                           required
                                           class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-secondary-700">男性</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="gender" 
                                           value="女性" 
                                           {{ old('gender') === '女性' ? 'checked' : '' }}
                                           required
                                           class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-secondary-700">女性</span>
                                </label>
                            </div>
                            @error('gender')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                @endif

                {{-- メールアドレス --}}
                <tr class="border-b border-secondary-200">
                    <td class="py-3 pr-4 text-sm font-medium text-secondary-700">
                        メールアドレス
                        <span class="text-red-500">*</span>
                    </td>
                    <td class="py-3">
                        <input type="email" 
                               name="email" 
                               id="email"
                               value="{{ old('email', $email ?? '') }}" 
                               required 
                               readonly
                               autocomplete="username"
                               class="form-input bg-gray-100">
                        <p class="text-xs text-green-600 mt-1">✓ メール認証済み</p>
                        <div class="bg-red-100 border border-red-300 rounded p-2 mt-2 text-xs text-red-800">
                            メールアドレスは、オファー予約が成立すると、連絡先として相手に公開されます。
                        </div>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>

                @if(isset($role) && $role === 'model')
                    {{-- 電話番号（モデルのみ） --}}
                    <tr class="border-b border-secondary-200">
                        <td class="py-3 pr-4 text-sm font-medium text-secondary-700">
                            電話番号
                        </td>
                        <td class="py-3">
                            <div class="flex gap-2 items-center">
                                <input type="text" 
                                       name="phone_number_part1" 
                                       id="phone_number_part1"
                                       value="{{ old('phone_number_part1') }}" 
                                       maxlength="4"
                                       placeholder="090"
                                       class="w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <span class="text-secondary-600">-</span>
                                <input type="text" 
                                       name="phone_number_part2" 
                                       id="phone_number_part2"
                                       value="{{ old('phone_number_part2') }}" 
                                       maxlength="4"
                                       placeholder="1234"
                                       class="w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <span class="text-secondary-600">-</span>
                                <input type="text" 
                                       name="phone_number_part3" 
                                       id="phone_number_part3"
                                       value="{{ old('phone_number_part3') }}" 
                                       maxlength="4"
                                       placeholder="5678"
                                       class="w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="bg-red-100 border border-red-300 rounded p-2 mt-2 text-xs text-red-800">
                                電話番号は、オファー予約が成立すると、連絡先として相手に公開されます。
                            </div>
                            @error('phone_number_part1')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>

                    {{-- 現住所（モデルのみ） --}}
                    <tr class="border-b border-secondary-200">
                        <td class="py-3 pr-4 text-sm font-medium text-secondary-700">
                            現住所
                            <span class="text-red-500">*</span>
                        </td>
                        <td class="py-3">
                            <div class="space-y-3">
                                {{-- 郵便番号 --}}
                                <div class="flex gap-2 items-center">
                                    <span class="text-secondary-600">〒</span>
                                    <input type="text" 
                                           name="postal_code_part1" 
                                           id="postal_code_part1"
                                           value="{{ old('postal_code_part1') }}" 
                                           maxlength="3"
                                           placeholder="123"
                                           required
                                           class="w-16 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-secondary-600">-</span>
                                    <input type="text" 
                                           name="postal_code_part2" 
                                           id="postal_code_part2"
                                           value="{{ old('postal_code_part2') }}" 
                                           maxlength="4"
                                           placeholder="4567"
                                           required
                                           class="w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                {{-- 都道府県 --}}
                                @php
                                    $prefectures = [
                                        '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
                                        '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
                                        '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県',
                                        '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県',
                                        '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県',
                                        '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県',
                                        '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
                                    ];
                                @endphp
                                <div>
                                    <select name="prefecture" 
                                            id="prefecture"
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">未選択</option>
                                        @foreach($prefectures as $pref)
                                            <option value="{{ $pref }}" {{ old('prefecture') === $pref ? 'selected' : '' }}>{{ $pref }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- 市区町村 --}}
                                <div>
                                    <input type="text" 
                                           name="city" 
                                           id="city"
                                           value="{{ old('city') }}" 
                                           required
                                           placeholder="市区町村"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                {{-- 番地 --}}
                                <div>
                                    <input type="text" 
                                           name="street_number" 
                                           id="street_number"
                                           value="{{ old('street_number') }}" 
                                           required
                                           placeholder="番地"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                {{-- 番地以降の住所 --}}
                                <div>
                                    <input type="text" 
                                           name="building_name" 
                                           id="building_name"
                                           value="{{ old('building_name') }}" 
                                           placeholder="番地以降の住所"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            @error('prefecture')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            @error('city')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            @error('street_number')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                @endif

                {{-- パスワード --}}
                <tr class="border-b border-secondary-200">
                    <td class="py-3 pr-4 text-sm font-medium text-secondary-700">
                        パスワード
                        <span class="text-red-500">*</span>
                    </td>
                    <td class="py-3">
                        <input type="password" 
                               name="password" 
                               id="password"
                               required 
                               autocomplete="new-password"
                               class="form-input">
                        <p class="text-xs text-secondary-500 mt-1">※ご希望の半角英数字6文字以上</p>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>

                {{-- パスワード（確認用） --}}
                <tr class="border-b border-secondary-200">
                    <td class="py-3 pr-4 text-sm font-medium text-secondary-700">
                        パスワード(確認用)
                        <span class="text-red-500">*</span>
                    </td>
                    <td class="py-3">
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation"
                               required 
                               autocomplete="new-password"
                               class="form-input">
                        @error('password_confirmation')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
            </table>

            <div class="flex items-center justify-end mt-6">
                <a class="link-secondary text-sm" href="{{ route('login-register') }}">
                    ログインはこちら
                </a>

                <button type="submit" class="ml-4 btn-primary">
                    登録する
                </button>
            </div>
            </div>
        </form>
    </div>

    @if(isset($role) && $role === 'model')
    <script>
        // 生年月日のプルダウンから日付を更新
        function updateBirthdate() {
            const year = document.getElementById('birth_year').value;
            const month = document.getElementById('birth_month').value;
            const day = document.getElementById('birth_day').value;
            const birthdateInput = document.getElementById('birthdate');
            
            if (year && month && day) {
                // 日付の妥当性チェック
                const date = new Date(year, parseInt(month) - 1, day);
                if (date.getFullYear() == year && date.getMonth() == parseInt(month) - 1 && date.getDate() == day) {
                    const dateString = `${year}-${month}-${day}`;
                    birthdateInput.value = dateString;
                } else {
                    birthdateInput.value = '';
                }
            } else {
                birthdateInput.value = '';
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
                    option.textContent = day + ' 日';
                    if (currentDay && currentDay === option.value) {
                        option.selected = true;
                    }
                    daySelect.appendChild(option);
                }
            }
            
            updateBirthdate();
        }

        // 年と月が変更されたら日の選択肢を更新
        document.addEventListener('DOMContentLoaded', function() {
            const birthYear = document.getElementById('birth_year');
            const birthMonth = document.getElementById('birth_month');
            
            if (birthYear && birthMonth) {
                birthYear.addEventListener('change', updateDays);
                birthMonth.addEventListener('change', updateDays);
                updateDays();
            }
        });
    </script>
    @endif
</x-guest-layout>
