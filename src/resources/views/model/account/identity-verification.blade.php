@extends('layouts.app')

@section('content')
<div class="page-narrow">
    <section class="section-panel">
        <div class="section-panel-inner">
            <div class="mb-8">
                <p class="section-title-en mb-3">Identity</p>
                <h1 class="section-title">本人確認</h1>
                <p class="text-secondary-600 mt-3">本人確認を完了すると、画家からのオファー率が高まります。</p>
            </div>

            {{-- ステータス表示 --}}
            @if($latest)
                @php
                    $statusColor = match($latest->status) {
                        'approved' => ['bg' => 'bg-success-50', 'border' => 'border-success-200', 'text' => 'text-success-700'],
                        'rejected' => ['bg' => 'bg-error-50', 'border' => 'border-error-200', 'text' => 'text-error-700'],
                        default => ['bg' => 'bg-warning-50', 'border' => 'border-warning-200', 'text' => 'text-warning-700'],
                    };
                @endphp
                <div class="{{ $statusColor['bg'] }} {{ $statusColor['border'] }} border rounded-xl p-5 mb-6">
                    <div class="flex items-start gap-3">
                        <div class="flex-1">
                            <p class="font-bold {{ $statusColor['text'] }} text-sm mb-1">
                                ステータス: {{ $latest->status_label }}
                            </p>
                            <p class="text-xs text-secondary-600">
                                提出書類: {{ $latest->document_type_label }}<br>
                                提出日時: {{ $latest->created_at->format('Y年m月d日 H:i') }}
                                @if($latest->reviewed_at)
                                    <br>確認日時: {{ $latest->reviewed_at->format('Y年m月d日 H:i') }}
                                @endif
                            </p>
                            @if($latest->status === 'rejected' && $latest->rejection_reason)
                                <div class="mt-3 p-3 bg-white rounded-lg text-sm text-secondary-700">
                                    <strong>差し戻し理由:</strong><br>
                                    {{ $latest->rejection_reason }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- 申請フォーム（承認済み or 審査中でない場合のみ） --}}
            @if(!$latest || $latest->status === 'rejected')
                <div class="bg-canvas-50 rounded-xl p-5 mb-6 text-sm text-secondary-700 leading-relaxed">
                    <h2 class="font-bold text-secondary-900 mb-3">提出にあたっての注意</h2>
                    <ul class="space-y-1 list-disc list-inside">
                        <li>書類は鮮明に撮影し、四隅が全て写るようにしてください。</li>
                        <li>画像はサーバー側で暗号化保存され、管理者のみ閲覧できます。</li>
                        <li>承認後6ヶ月で書類画像は自動削除されます。</li>
                        <li><strong class="text-error-600">マイナンバーカード裏面（番号面）は受け付けていません。</strong></li>
                    </ul>
                </div>

                <form method="POST" action="{{ route('model.identity-verification.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label for="document_type" class="form-label">書類種別 <span class="text-error-500">*</span></label>
                        <select name="document_type" id="document_type" required class="form-select">
                            <option value="">選択してください</option>
                            @foreach($documentTypes as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('document_type')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="front_image" class="form-label">表面の画像 <span class="text-error-500">*</span></label>
                        <input type="file" name="front_image" id="front_image" accept="image/*" required class="form-input">
                        <p class="form-help">JPEG / PNG / WebP / 10MB以下</p>
                        @error('front_image')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="back_image" class="form-label">裏面の画像（任意）</label>
                        <input type="file" name="back_image" id="back_image" accept="image/*" class="form-input">
                        <p class="form-help">運転免許証など裏面がある場合</p>
                        @error('back_image')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="selfie_image" class="form-label">セルフィー画像（任意）</label>
                        <input type="file" name="selfie_image" id="selfie_image" accept="image/*" class="form-input">
                        <p class="form-help">本人と書類を一緒に撮影した画像があると、審査がスムーズです</p>
                        @error('selfie_image')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <a href="{{ route('mypage') }}" class="btn-secondary">キャンセル</a>
                        <button type="submit" class="btn-primary">
                            審査に提出する
                        </button>
                    </div>
                </form>
            @endif

            @if($latest && in_array($latest->status, ['pending', 'reviewing']))
                <div class="text-center py-4">
                    <p class="text-secondary-500 text-sm">審査結果が出るまでしばらくお待ちください。<br>結果はメール・通知でお知らせします。</p>
                </div>
            @endif

            @if($latest && $latest->status === 'approved')
                <div class="text-center py-4">
                    <p class="text-success-700 font-bold">✓ 本人確認は完了しています</p>
                    <p class="text-secondary-500 text-sm mt-2">プロフィールに本人確認済みバッジが表示されます。</p>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
