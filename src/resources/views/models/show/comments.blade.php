{{-- Commentsタブのコンテンツ --}}
<div class="border-x border-b border-secondary-200 bg-canvas-50 px-5 sm:px-6 py-6">
    <div class="flex items-baseline gap-3 mb-6">
        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Comments</p>
        <h2 class="font-display text-lg font-semibold text-secondary-900">レビュー・コメント</h2>
    </div>

    @if($reviews->count() > 0)
        <div class="space-y-4">
            @foreach($reviews as $review)
                @php
                    $ratingClass = match($review->rating) {
                        'very_good' => 'text-success-700',
                        'good'      => 'text-success-700',
                        'bad'       => 'text-error-600',
                        default     => 'text-secondary-500',
                    };
                @endphp
                <article class="border border-secondary-200 bg-canvas-50 px-5 py-4 hover:border-secondary-400 transition-colors duration-300">
                    <div class="flex items-start justify-between gap-4 mb-3">
                        <div class="min-w-0 flex-1">
                            <p class="text-secondary-900 font-medium">
                                {{ $review->reviewer->name }}
                                @if($review->job)
                                    <span class="text-xs text-secondary-500 font-normal ml-1">
                                        ({{ $review->job->title }})
                                    </span>
                                @endif
                            </p>
                            <p class="text-[10px] tracking-[0.2em] uppercase text-secondary-400 mt-1">
                                {{ $review->created_at->format('Y . n . j') }}
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-[10px] tracking-[0.25em] uppercase {{ $ratingClass }}">
                                ● {{ $review->rating_label }}
                            </p>
                        </div>
                    </div>
                    @if($review->comment)
                        <p class="text-sm text-secondary-700 leading-relaxed whitespace-pre-wrap pt-3 border-t border-secondary-200">{{ $review->comment }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    @else
        <div class="border border-secondary-200 px-5 py-16 text-center">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Reviews</p>
            <p class="text-secondary-500 text-sm">レビューがまだありません。</p>
        </div>
    @endif
</div>
