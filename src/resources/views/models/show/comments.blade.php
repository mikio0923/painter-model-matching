{{-- Commentsタブのコンテンツ --}}
<div class="bg-white rounded-b-lg shadow-sm">
    <div class="px-6 py-6">
        <h2 class="text-xl font-bold text-secondary-900 mb-6">> Comments / レビュー・コメント</h2>
        
        @if($reviews->count() > 0)
            <div class="space-y-4">
                @foreach($reviews as $review)
                    <div class="border border-secondary-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <p class="font-semibold text-secondary-900">
                                    {{ $review->reviewer->name }}
                                    @if($review->job)
                                        <span class="text-sm font-normal text-secondary-600">
                                            ({{ $review->job->title }})
                                        </span>
                                    @endif
                                </p>
                                <p class="text-sm text-secondary-600">
                                    {{ $review->created_at->format('Y年m月d日') }}
                                </p>
                            </div>
                            <span class="badge badge-success">
                                {{ $review->rating_label }}
                            </span>
                        </div>
                        @if($review->comment)
                            <p class="text-sm text-secondary-700 mt-2 whitespace-pre-wrap leading-relaxed">{{ $review->comment }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-secondary-600">レビューがまだありません。</p>
            </div>
        @endif
    </div>
</div>
