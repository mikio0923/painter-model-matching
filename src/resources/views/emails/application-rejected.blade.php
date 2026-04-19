@extends('emails.layout')

@section('content')
<h2>応募結果のお知らせ</h2>

<p>{{ $application->model->name }} さん、こんにちは。</p>

<p>以下の依頼への応募について、残念ながら今回はご縁がありませんでした。</p>

<div class="highlight">
    <strong>依頼:</strong> {{ $application->job->title }}
</div>

<p>他にもたくさんの依頼が掲載されています。ぜひ次の機会をお探しください。</p>

<p style="text-align: center;">
    <a href="{{ route('jobs.index') }}" class="cta">依頼を探す</a>
</p>
@endsection
