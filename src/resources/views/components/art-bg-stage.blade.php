{{--
    背景に名画を循環表示する装飾レイヤー（page-header 内に配置）
    使い方:
        <div class="page-header">
            <x-art-bg-stage />
            <div class="page-header-inner">...</div>
        </div>
--}}
<div class="art-bg-stage">
    <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/great-wave.jpg') }}');"></div>
    <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/starry-night.jpg') }}');"></div>
    <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/pearl-earring.jpg') }}');"></div>
    <div class="art-bg-veil"></div>
</div>
