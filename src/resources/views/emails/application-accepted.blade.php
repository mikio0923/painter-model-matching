@extends('emails.layout')

@section('content')
<h2>応募が承認されました</h2>

<p>{{ $application->model->name }} さん、こんにちは。</p>

<p>おめでとうございます！以下の依頼への応募が承認されました。</p>

<div class="highlight">
    <strong>依頼:</strong> {{ $application->job->title }}<br>
    <strong>画家:</strong> {{ $application->job->painter->name ?? '(退会済み)' }}<br>
    @if($application->job->reward_amount)
        <strong>報酬:</strong> {{ number_format($application->job->reward_amount) }}円
    @endif
</div>

<p>メッセージ機能で画家と直接やり取りができます。詳細の打ち合わせを進めてください。</p>

<p style="text-align: center;">
    <a href="{{ route('mypage') }}" class="cta">マイページを開く</a>
</p>
@endsection
