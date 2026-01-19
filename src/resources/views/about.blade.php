@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">モデルタウンとは？</h1>
    
    <div class="prose max-w-none">
        <p class="text-lg text-gray-700 mb-4">
            モデルタウンは、「モデルになりたい人」と「モデルに仕事を依頼したい人」が出会う場所です。
        </p>
        
        <h2 class="text-2xl font-semibold mt-8 mb-4">サービスについて</h2>
        <p class="text-gray-700 mb-4">
            このサイトは、画家とモデルをマッチングするためのプラットフォームです。
            画家はモデルを探し、モデルは依頼を見つけることができます。
        </p>
        
        <h2 class="text-2xl font-semibold mt-8 mb-4">使い方</h2>
        <div class="space-y-4">
            <div>
                <h3 class="text-xl font-semibold mb-2">モデルの方</h3>
                <p class="text-gray-700">
                    プロフィールを作成し、公開することで、画家からの依頼を受けることができます。
                    依頼一覧から興味のある案件に応募することも可能です。
                </p>
            </div>
            <div>
                <h3 class="text-xl font-semibold mb-2">画家の方</h3>
                <p class="text-gray-700">
                    モデル一覧から希望のモデルを探し、依頼を作成することができます。
                    応募してきたモデルの中から選んで、マッチングを進めることができます。
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

