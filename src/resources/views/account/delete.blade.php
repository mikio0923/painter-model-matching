@extends('layouts.app')

@section('content')
<div class="page-narrow">
    <section class="section-panel">
        <div class="section-panel-inner">
            <div class="mb-8">
                <p class="section-title-en mb-3">Account</p>
                <h1 class="section-title">退会手続き</h1>
                <p class="text-secondary-600 mt-3">退会前に以下の内容をご確認ください。</p>
            </div>

            @if(count($blockers) > 0)
                <div class="bg-error-50 border border-error-200 rounded-xl p-5 mb-6">
                    <h2 class="font-bold text-error-700 text-sm mb-2">退会できません</h2>
                    <ul class="text-sm text-error-700 space-y-1 list-disc list-inside">
                        @foreach($blockers as $blocker)
                            <li>{{ $blocker }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-canvas-50 rounded-xl p-5 mb-6 text-sm text-secondary-700 leading-relaxed">
                <h2 class="font-bold text-secondary-900 mb-3">退会するとどうなりますか？</h2>
                <ul class="space-y-2 list-disc list-inside">
                    <li>アカウントは即時にログアウトされ、ログインできなくなります。</li>
                    <li>プロフィール情報はサイト上で公開されなくなります。</li>
                    <li>過去のメッセージ・レビューは相手側に「退会済みユーザー」として残ります。</li>
                    <li>退会から <strong class="text-primary-700">{{ \App\Services\AccountDeletionService::GRACE_PERIOD_DAYS }}日間</strong> はアカウント復活が可能です（同じメールでログインしてください）。</li>
                    <li>{{ \App\Services\AccountDeletionService::GRACE_PERIOD_DAYS }}日経過後、プロフィール情報・画像は完全に削除されます。</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('account.delete') }}" class="space-y-5">
                @csrf
                @method('DELETE')

                <div>
                    <label for="reason" class="form-label">退会理由（任意）</label>
                    <select name="reason" id="reason" class="form-select">
                        <option value="">選択してください</option>
                        <option value="not_matched">マッチングできなかった</option>
                        <option value="hard_to_use">使い方がわかりにくかった</option>
                        <option value="other_service">他のサービスを利用する</option>
                        <option value="trouble">トラブルがあった</option>
                        <option value="pause">一時的に利用を中止したい</option>
                        <option value="other">その他</option>
                    </select>
                </div>

                <div>
                    <label for="feedback" class="form-label">フィードバック（任意）</label>
                    <textarea name="feedback" id="feedback" rows="4" class="form-textarea" placeholder="サービス改善のためご意見をお聞かせください"></textarea>
                </div>

                <div>
                    <label for="password" class="form-label">パスワード（本人確認）<span class="text-error-500">*</span></label>
                    <input type="password" name="password" id="password" required class="form-input" autocomplete="current-password">
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-start gap-2">
                    <input type="checkbox" name="confirm" id="confirm" value="1" required class="mt-1">
                    <label for="confirm" class="text-sm text-secondary-700">
                        上記内容を確認し、退会することに同意します
                    </label>
                </div>
                @error('confirm')
                    <p class="form-error">{{ $message }}</p>
                @enderror

                @error('account')
                    <div class="bg-error-50 border border-error-200 rounded-lg p-3 text-sm text-error-700">
                        {{ $message }}
                    </div>
                @enderror

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <a href="{{ route('mypage') }}" class="btn-secondary">キャンセル</a>
                    <button type="submit"
                            @if(count($blockers) > 0) disabled @endif
                            onclick="return confirm('本当に退会しますか？この操作は{{ \App\Services\AccountDeletionService::GRACE_PERIOD_DAYS }}日間は取り消し可能ですが、それ以降は完全に削除されます。')"
                            class="btn-danger">
                        退会する
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
