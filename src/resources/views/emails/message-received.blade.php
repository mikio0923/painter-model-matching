@extends('emails.layout')

@section('content')
<h2>新しいメッセージが届いています</h2>

<p>{{ $message->receiver->name }} さん、こんにちは。</p>

<p>{{ $message->sender->name ?? '(退会済みユーザー)' }} さんから新しいメッセージが届きました。</p>

<div class="highlight">
    <strong>依頼:</strong> {{ $message->job->title }}<br>
    <strong>メッセージ:</strong> {{ Str::limit($message->body, 150) }}
</div>

<p style="text-align: center;">
    <a href="{{ route('messages.show', ['job' => $message->job_id, 'other_user_id' => $message->sender_id]) }}" class="cta">メッセージを確認する</a>
</p>
@endsection
