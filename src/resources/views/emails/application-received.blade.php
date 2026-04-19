@extends('emails.layout')

@section('content')
<h2>新しい応募が届きました</h2>

<p>{{ $application->job->painter->name }} さん、こんにちは。</p>

<p>あなたの依頼に新しい応募がありました。</p>

<div class="highlight">
    <strong>依頼:</strong> {{ $application->job->title }}<br>
    <strong>応募者:</strong> {{ $application->model->name }}<br>
    @if($application->message)
        <strong>メッセージ:</strong> {{ Str::limit($application->message, 100) }}
    @endif
</div>

<p style="text-align: center;">
    <a href="{{ route('painter.jobs.applications.index', $application->job) }}" class="cta">応募を確認する</a>
</p>

<p>応募の承認・却下はマイページから行えます。</p>
@endsection
