@extends('emails.layout')

@section('content')
<h2>依頼の応募期限が近づいています</h2>

<p>{{ $job->painter->name ?? '(退会済み)' }} さん、こんにちは。</p>

<p>以下の依頼の応募期限が近づいています。</p>

<div class="highlight">
    <strong>依頼:</strong> {{ $job->title }}<br>
    <strong>応募期限:</strong> {{ $job->apply_deadline?->format('Y年m月d日') }}<br>
    <strong>現在の応募数:</strong> {{ $job->applications()->count() }}件
</div>

<p>期限後は自動的に応募を受け付けなくなります。応募状況をご確認ください。</p>

<p style="text-align: center;">
    <a href="{{ route('painter.jobs.index') }}" class="cta">依頼を管理する</a>
</p>
@endsection
