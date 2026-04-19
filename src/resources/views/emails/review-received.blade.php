@extends('emails.layout')

@section('content')
<h2>レビューが投稿されました</h2>

<p>{{ $review->reviewedUser->name }} さん、こんにちは。</p>

<p>{{ $review->reviewer->name ?? '(退会済みユーザー)' }} さんからレビューが届きました。</p>

<div class="highlight">
    <strong>依頼:</strong> {{ $review->job->title }}<br>
    <strong>評価:</strong> {{ $review->rating_label }}<br>
    @if($review->comment)
        <strong>コメント:</strong> {{ Str::limit($review->comment, 150) }}
    @endif
</div>

<p style="text-align: center;">
    <a href="{{ route('mypage') }}" class="cta">マイページで確認する</a>
</p>
@endsection
