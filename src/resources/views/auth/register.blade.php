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

        <form method="POST" action="{{ route('register') }}" class="card" id="register-form">
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
                        <p class="form-error js-client-error" data-field="name" style="display:none"></p>
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
                        <div class="bg-orange-100 border border-orange-300 rounded p-3 mt-2 text-sm text-orange-800">
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
                                       maxlength="3"
                                       placeholder="080"
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
                            <div class="bg-orange-100 border border-orange-300 rounded p-3 mt-2 text-sm text-orange-800">
                                電話番号は、オファー予約が成立すると、連絡先として相手に公開されます。
                            </div>
                            @if($errors->hasAny(['phone_number_part1', 'phone_number_part2', 'phone_number_part3']))
                                <p class="form-error">{{ $errors->first('phone_number_part1') ?: $errors->first('phone_number_part2') ?: $errors->first('phone_number_part3') }}</p>
                            @endif
                            <p class="form-error js-client-error" data-field="phone" style="display:none"></p>
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
                                @error('postal_code_part1')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                                @error('postal_code_part2')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                                <p class="form-error js-client-error" data-field="postal_code" style="display:none"></p>
                                <p id="zipcode-search-status" class="text-xs text-secondary-500 mt-1 hidden" aria-live="polite"></p>

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
                            <p class="form-error js-client-error" data-field="prefecture" style="display:none"></p>
                            <p class="form-error js-client-error" data-field="city" style="display:none"></p>
                            <p class="form-error js-client-error" data-field="street_number" style="display:none"></p>
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
                        <p class="form-error js-client-error" data-field="password" style="display:none"></p>
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
                        <p class="form-error js-client-error" data-field="password_confirmation" style="display:none"></p>
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

            // 郵便番号から住所を自動入力（zipcloud API）
            const zipPart1 = document.getElementById('postal_code_part1');
            const zipPart2 = document.getElementById('postal_code_part2');
            const prefectureSelect = document.getElementById('prefecture');
            const cityInput = document.getElementById('city');
            const streetInput = document.getElementById('street_number');
            const zipStatus = document.getElementById('zipcode-search-status');

            function searchAddressByZipcode() {
                const z1 = (zipPart1 && zipPart1.value.trim()) || '';
                const z2 = (zipPart2 && zipPart2.value.trim()) || '';
                if (z1.length !== 3 || z2.length !== 4) return;

                const zipcode = z1 + z2;
                if (!/^\d{7}$/.test(zipcode)) return;

                if (zipStatus) {
                    zipStatus.classList.remove('hidden');
                    zipStatus.textContent = '住所を検索しています...';
                    zipStatus.classList.remove('text-red-600', 'text-green-600');
                    zipStatus.classList.add('text-secondary-500');
                }

                fetch('https://zipcloud.ibsnet.co.jp/api/search?zipcode=' + encodeURIComponent(zipcode))
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        if (!zipStatus) return;
                        zipStatus.classList.remove('hidden');
                        if (data.status !== 200 || !data.results || data.results.length === 0) {
                            zipStatus.textContent = '該当する住所が見つかりませんでした。';
                            zipStatus.classList.add('text-red-600');
                            zipStatus.classList.remove('text-green-600', 'text-secondary-500');
                            return;
                        }
                        const r = data.results[0];
                        if (prefectureSelect && r.address1) {
                            prefectureSelect.value = r.address1;
                        }
                        if (cityInput && r.address2) {
                            cityInput.value = r.address2;
                        }
                        if (streetInput && r.address3) {
                            streetInput.value = r.address3;
                        }
                        zipStatus.textContent = '住所を自動入力しました。';
                        zipStatus.classList.add('text-green-600');
                        zipStatus.classList.remove('text-red-600', 'text-secondary-500');
                    })
                    .catch(function() {
                        if (zipStatus) {
                            zipStatus.classList.remove('hidden');
                            zipStatus.textContent = '住所の取得に失敗しました。';
                            zipStatus.classList.add('text-red-600');
                            zipStatus.classList.remove('text-green-600', 'text-secondary-500');
                        }
                    });
            }

            if (zipPart1 && zipPart2) {
                zipPart1.addEventListener('input', searchAddressByZipcode);
                zipPart1.addEventListener('blur', searchAddressByZipcode);
                zipPart2.addEventListener('input', searchAddressByZipcode);
                zipPart2.addEventListener('blur', searchAddressByZipcode);
            }
        });
    </script>
    @endif

    <script>
        // 入力中（blur）でバリデーション表示
        document.addEventListener('DOMContentLoaded', function() {
            function showClientError(fieldKey, message) {
                var el = document.querySelector('.js-client-error[data-field="' + fieldKey + '"]');
                if (el) {
                    el.textContent = message || '';
                    el.style.display = message ? 'block' : 'none';
                }
            }

            function validateName() {
                var v = (document.getElementById('name') && document.getElementById('name').value.trim()) || '';
                if (v.length === 0) {
                    showClientError('name', '名前を入力してください。');
                    return false;
                }
                showClientError('name', '');
                return true;
            }

            function validatePhone() {
                var p1 = document.getElementById('phone_number_part1');
                var p2 = document.getElementById('phone_number_part2');
                var p3 = document.getElementById('phone_number_part3');
                if (!p1 || !p2 || !p3) return true;
                var v1 = p1.value.trim(), v2 = p2.value.trim(), v3 = p3.value.trim();
                if (v1 === '' && v2 === '' && v3 === '') { showClientError('phone', ''); return true; }
                if (v1 !== '' && (v1.length < 2 || v1.length > 3 || !/^\d+$/.test(v1))) {
                    showClientError('phone', '電話番号の市外局番は2桁以上3桁以下で入力してください。（例：080、03）');
                    return false;
                }
                if (v2 !== '' && (v2.length !== 4 || !/^\d+$/.test(v2))) {
                    showClientError('phone', '電話番号の市内局番は4桁で入力してください。');
                    return false;
                }
                if (v3 !== '' && (v3.length !== 4 || !/^\d+$/.test(v3))) {
                    showClientError('phone', '電話番号の加入者番号は4桁で入力してください。');
                    return false;
                }
                showClientError('phone', '');
                return true;
            }

            function validatePostalCode() {
                var z1 = document.getElementById('postal_code_part1');
                var z2 = document.getElementById('postal_code_part2');
                if (!z1 || !z2) return true;
                var v1 = z1.value.trim(), v2 = z2.value.trim();
                if (v1.length !== 3 || !/^\d+$/.test(v1)) {
                    showClientError('postal_code', '郵便番号は半角数字3桁で入力してください。');
                    return false;
                }
                if (v2.length !== 4 || !/^\d+$/.test(v2)) {
                    showClientError('postal_code', '郵便番号は半角数字4桁で入力してください。');
                    return false;
                }
                showClientError('postal_code', '');
                return true;
            }

            function validatePrefecture() {
                var el = document.getElementById('prefecture');
                if (!el) return true;
                if (!el.value || el.value === '') {
                    showClientError('prefecture', '都道府県を選択してください。');
                    return false;
                }
                showClientError('prefecture', '');
                return true;
            }

            function validateCity() {
                var el = document.getElementById('city');
                if (!el) return true;
                if (!el.value.trim()) {
                    showClientError('city', '市区町村を入力してください。');
                    return false;
                }
                showClientError('city', '');
                return true;
            }

            function validateStreetNumber() {
                var el = document.getElementById('street_number');
                if (!el) return true;
                if (!el.value.trim()) {
                    showClientError('street_number', '番地を入力してください。');
                    return false;
                }
                showClientError('street_number', '');
                return true;
            }

            function validatePassword() {
                var el = document.getElementById('password');
                if (!el) return true;
                if (el.value.length > 0 && el.value.length < 6) {
                    showClientError('password', 'パスワードは6文字以上で入力してください。');
                    return false;
                }
                showClientError('password', '');
                return true;
            }

            function validatePasswordConfirmation() {
                var pw = document.getElementById('password');
                var cf = document.getElementById('password_confirmation');
                if (!cf) return true;
                if (cf.value.length > 0 && pw && cf.value !== pw.value) {
                    showClientError('password_confirmation', 'パスワードが一致しません。');
                    return false;
                }
                showClientError('password_confirmation', '');
                return true;
            }

            var nameEl = document.getElementById('name');
            if (nameEl) {
                nameEl.addEventListener('blur', validateName);
            }
            var phone1 = document.getElementById('phone_number_part1');
            var phone2 = document.getElementById('phone_number_part2');
            var phone3 = document.getElementById('phone_number_part3');
            if (phone1) { phone1.addEventListener('blur', validatePhone); }
            if (phone2) { phone2.addEventListener('blur', validatePhone); }
            if (phone3) { phone3.addEventListener('blur', validatePhone); }
            var zip1 = document.getElementById('postal_code_part1');
            var zip2 = document.getElementById('postal_code_part2');
            if (zip1) { zip1.addEventListener('blur', validatePostalCode); }
            if (zip2) { zip2.addEventListener('blur', validatePostalCode); }
            var prefectureEl = document.getElementById('prefecture');
            if (prefectureEl) { prefectureEl.addEventListener('change', validatePrefecture); prefectureEl.addEventListener('blur', validatePrefecture); }
            var cityEl = document.getElementById('city');
            if (cityEl) { cityEl.addEventListener('blur', validateCity); }
            var streetEl = document.getElementById('street_number');
            if (streetEl) { streetEl.addEventListener('blur', validateStreetNumber); }
            var passwordEl = document.getElementById('password');
            if (passwordEl) { passwordEl.addEventListener('blur', validatePassword); passwordEl.addEventListener('input', function() { validatePassword(); validatePasswordConfirmation(); }); }
            var confirmEl = document.getElementById('password_confirmation');
            if (confirmEl) { confirmEl.addEventListener('blur', validatePasswordConfirmation); confirmEl.addEventListener('input', validatePasswordConfirmation); }
        });
    </script>
</x-guest-layout>
