<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RegistrationToken;
use App\Mail\RegistrationVerificationMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view (email verification step).
     */
    public function create(Request $request): View|RedirectResponse
    {
        $role = $request->get('role', 'model');

        // 認証済みでフォーム表示を要求された場合（バリデーション失敗後の再表示など）
        if ($request->get('step') === 'form' && session('verified_email') && session('verified_role')) {
            return view('auth.register', [
                'email' => session('verified_email'),
                'role' => session('verified_role'),
            ]);
        }

        return view('auth.register-email', [
            'role' => $role,
        ]);
    }

    /**
     * Send registration verification email.
     */
    public function sendVerificationEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'role' => ['required', 'string', 'in:model,painter'],
        ]);

        // 既に登録されているメールアドレスかチェック
        $existingUser = User::where('email', $request->email)
            ->where('role', $request->role)
            ->first();

        if ($existingUser) {
            throw ValidationException::withMessages([
                'email' => 'このメールアドレスは既に登録されています。',
            ]);
        }

        // トークンを生成して保存
        $token = RegistrationToken::createToken($request->email, $request->role);

        // メールを送信
        Mail::to($request->email)->send(new RegistrationVerificationMail(
            $request->email,
            $token,
            $request->role
        ));

        return redirect()->route('register.email.sent')
            ->with('email', $request->email)
            ->with('role', $request->role);
    }

    /**
     * Display email sent confirmation page.
     */
    public function emailSent(Request $request): View
    {
        return view('auth.register-email-sent', [
            'email' => session('email'),
            'role' => session('role'),
        ]);
    }

    /**
     * Verify email token and show registration form.
     */
    public function verify(Request $request): View|RedirectResponse
    {
        // コピペ時に改行や前後の空白が入ることがあるためトリム
        $email = trim((string) $request->get('email'));
        $token = trim((string) $request->get('token'));

        if ($email === '' || $token === '') {
            return redirect()->route('register')
                ->with('error', '無効な認証リンクです。');
        }

        $registrationToken = RegistrationToken::verifyToken($email, $token);

        if (!$registrationToken) {
            // 既にこのメールで認証済み（リンクを2回目にクリックした等）の場合は登録フォームへ
            if (session('verified_email') === $email && session('verified_role')) {
                return view('auth.register', [
                    'email' => $email,
                    'role' => session('verified_role'),
                ]);
            }
            return redirect()->route('register')
                ->with('error', '認証リンクが無効または期限切れです。再度メールアドレスを入力し、届いたメールのリンクは1回だけクリックしてください。');
        }

        // トークンを削除
        $registrationToken->delete();

        // セッションに保存
        session([
            'verified_email' => $email,
            'verified_role' => $registrationToken->role,
            'verification_token' => $token,
        ]);

        return view('auth.register', [
            'email' => $email,
            'role' => $registrationToken->role,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // セッションから認証済みメールアドレスとロールを取得
        $verifiedEmail = session('verified_email');
        $verifiedRole = session('verified_role');

        if (!$verifiedEmail || !$verifiedRole) {
            return redirect()->route('register')
                ->with('error', 'メール認証が必要です。');
        }

        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($verifiedEmail) {
                    if ($value !== $verifiedEmail) {
                        $fail('認証されたメールアドレスと一致しません。');
                    }
                },
                Rule::unique(User::class)->where(function ($query) use ($verifiedRole) {
                    return $query->where('role', $verifiedRole);
                }),
            ],
            'password' => ['required', 'confirmed', 'string', 'min:6'],
            'role' => ['required', 'string', 'in:model,painter'],
        ];

        // モデルの場合、追加のバリデーション
        if ($verifiedRole === 'model') {
            $validationRules['birth_year'] = ['required', 'integer', 'min:1900', 'max:' . date('Y')];
            $validationRules['birth_month'] = ['required', 'string', 'regex:/^(0[1-9]|1[0-2])$/'];
            $validationRules['birth_day'] = ['required', 'string', 'regex:/^(0[1-9]|[12][0-9]|3[01])$/'];
            $validationRules['birthdate'] = [
                'nullable',
                'date',
                'before:today',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('birth_year') && $request->filled('birth_month') && $request->filled('birth_day')) {
                        try {
                            $date = \Carbon\Carbon::create(
                                $request->birth_year,
                                $request->birth_month,
                                $request->birth_day
                            );
                            if ($date->isFuture()) {
                                $fail('生年月日は今日以前の日付である必要があります。');
                            }
                        } catch (\Exception $e) {
                            $fail('無効な日付です。');
                        }
                    }
                },
            ];
            $validationRules['gender'] = ['required', 'string', 'in:男性,女性'];
            $validationRules['phone_number_part1'] = ['nullable', 'string', 'min:2', 'max:3', 'regex:/^[0-9]+$/'];
            $validationRules['phone_number_part2'] = ['nullable', 'string', 'size:4', 'regex:/^[0-9]+$/'];
            $validationRules['phone_number_part3'] = ['nullable', 'string', 'size:4', 'regex:/^[0-9]+$/'];
            $validationRules['postal_code_part1'] = ['required', 'string', 'size:3', 'regex:/^[0-9]+$/'];
            $validationRules['postal_code_part2'] = ['required', 'string', 'size:4', 'regex:/^[0-9]+$/'];
            $validationRules['prefecture'] = ['required', 'string'];
            $validationRules['city'] = ['required', 'string'];
            $validationRules['street_number'] = ['required', 'string', 'max:255'];
            $validationRules['building_name'] = ['nullable', 'string', 'max:255'];
        }

        try {
            $request->validate($validationRules, [
                'phone_number_part1.regex' => '電話番号は半角数字のみで入力してください。',
                'phone_number_part1.min' => '電話番号の市外局番は2桁以上3桁以下で入力してください。（例：080、03）',
                'phone_number_part1.max' => '電話番号の市外局番は2桁以上3桁以下で入力してください。（例：080、03）',
                'phone_number_part2.regex' => '電話番号は半角数字のみで入力してください。',
                'phone_number_part2.size' => '電話番号の市内局番は4桁で入力してください。',
                'phone_number_part3.regex' => '電話番号は半角数字のみで入力してください。',
                'phone_number_part3.size' => '電話番号の加入者番号は4桁で入力してください。',
                'password.min' => 'パスワードは6文字以上で入力してください。',
                'postal_code_part1.regex' => '郵便番号は半角数字のみで入力してください。',
                'postal_code_part2.regex' => '郵便番号は半角数字のみで入力してください。',
            ], [
                'phone_number_part1' => '電話番号（市外局番）',
                'phone_number_part2' => '電話番号（市内局番）',
                'phone_number_part3' => '電話番号（加入者番号）',
                'postal_code_part1' => '郵便番号（前半）',
                'postal_code_part2' => '郵便番号（後半）',
            ]);
        } catch (ValidationException $e) {
            return redirect()->route('register', ['role' => $verifiedRole, 'step' => 'form'])
                ->withInput()
                ->withErrors($e->validator);
        }

        // ロールが一致するか確認
        if ($request->role !== $verifiedRole) {
            return redirect()->route('register')
                ->with('error', '認証されたロールと一致しません。');
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ];

        // モデルの場合、連絡先情報を追加
        if ($verifiedRole === 'model') {
            $userData['phone_number_part1'] = $request->phone_number_part1;
            $userData['phone_number_part2'] = $request->phone_number_part2;
            $userData['phone_number_part3'] = $request->phone_number_part3;
            $userData['postal_code_part1'] = $request->postal_code_part1;
            $userData['postal_code_part2'] = $request->postal_code_part2;
            $userData['prefecture'] = $request->prefecture;
            $userData['city'] = $request->city;
            $userData['street_number'] = $request->street_number;
            $userData['building_name'] = $request->building_name;
        }

        $user = User::create($userData);

        // モデルの場合、ModelProfileも作成
        if ($verifiedRole === 'model') {
            // 生年月日から年齢を計算
            $birthdate = null;
            $age = null;
            if ($request->filled('birth_year') && $request->filled('birth_month') && $request->filled('birth_day')) {
                $birthdate = \Carbon\Carbon::create(
                    $request->birth_year,
                    $request->birth_month,
                    $request->birth_day
                );
                $age = $birthdate->age;
            }

            \App\Models\ModelProfile::create([
                'user_id' => $user->id,
                'display_name' => $user->name,
                'birthdate' => $birthdate,
                'age' => $age,
                'gender' => $request->gender,
                'prefecture' => $request->prefecture,
                'is_public' => false,
            ]);
        }

        // セッションをクリア
        session()->forget(['verified_email', 'verified_role', 'verification_token']);

        event(new Registered($user));

        Auth::login($user);

        // ロール別にマイページにリダイレクト
        return redirect(route('mypage', absolute: false))->with('success', '登録が完了しました。プロフィールを設定してください。');
    }
}
