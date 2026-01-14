@php
    $depth ??= 0;
@endphp

<div>
    <x-comment.card :$comment />

    @if ($comment->replies->count() > 0)
        <div class="ml-8 space-y-4 mt-4">
            @foreach ($comment->replies as $reply)
                @if ($depth >= 5)
                    <div>
                        <x-comment.card :comment="$reply" />
                        @if ($reply->replies->count() > 0)
                            <a
                                href="{{ route("comments.show", [$post->public_id, $reply->id]) }}"
                                class="text-blue-500 hover:underline font-bold text-sm ml-8 mt-2 inline-block"
                            >
                                {{ __("View More") }}
                            </a>
                        @endif
                    </div>
                @else
                    @include("partials.comment-recursive", ["comment" => $reply, "depth" => $depth + 1])
                @endif
            @endforeach
        </div>
    @endif
</div>
