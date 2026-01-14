<?php

use function Livewire\Volt\{state, mount, computed};
use App\Models\Comment;

state([
    "perPage" => 5,
    "content" => "",
    "parentId" => null,
    "commentId" => null,
    "post" => null,
]);

mount(function (?int $commentId = null) {
    $this->commentId = $commentId;
});

$loadMore = function () {
    $this->perPage += 5;
};

$setReplyTo = function (?int $commentId) {
    $this->parentId = $commentId;
};

$postComment = function () {
    $this->validate([
        "content" => "required|min:1|max:1000",
    ]);

    Comment::create([
        "post_id" => $this->post->id,
        "user_id" => 1,
        "parent_id" => $this->parentId,
        "content" => $this->content,
    ]);

    $this->content = "";
    $this->parentId = null;

    $this->dispatch("comment-posted");
};

$upvote = function (Comment $comment) {
    $userId = 1;
    $like = $comment
        ->likes()
        ->where("user_id", $userId)
        ->where("comment_id", $comment->id)
        ->first();

    if ($like) {
        if ($like->liked) {
            $like->delete();
        } else {
            $like->update(["liked" => true]);
        }
    } else {
        $comment->likes()->create([
            "user_id" => $userId,
            "comment_id" => $comment->id,
            "liked" => true,
        ]);
    }
};

$downvote = function (Comment $comment) {
    $userId = 1;

    $like = $comment
        ->likes()
        ->where("user_id", $userId)
        ->where("comment_id", $comment->id)
        ->first();

    if ($like) {
        if (! $like->liked) {
            $like->delete();
        } else {
            $like->update(["liked" => false]);
        }
    } else {
        $comment->likes()->create([
            "user_id" => $userId,
            "comment_id" => $comment->id,
            "liked" => false,
        ]);
    }
};

$comments = computed(function () {
    $query = Comment::with(["user", "replies.user", "replies.replies.user"])
        ->where("post_id", $this->post->id)
        ->latest();

    if ($this->commentId) {
        $query->where("parent_id", $this->commentId);
    } else {
        $query->whereNull("parent_id");
    }

    return $query->take($this->perPage)->get();
});

$hasMore = computed(function () {
    $totalQuery = Comment::where("post_id", $this->post->id);

    if ($this->commentId) {
        $totalQuery->where("parent_id", $this->commentId);
    } else {
        $totalQuery->whereNull("parent_id");
    }

    return $totalQuery->count() > $this->perPage;
});

?>

<div class="space-y-8 mt-8">
    <form wire:submit="postComment">
        <div class="relative">
            <textarea
                wire:model="content"
                class="block w-full p-4 ps-8 text-sm text-gray-900 border border-gray-50 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:bg-gray-700 outline-none shadow-sm"
                required
            ></textarea>
            <button
                type="submit"
                class="text-white absolute end-2.5 bottom-2.5 bg-gray-600 hover:bg-gray-800 focus:ring-4 focus:outline-nonefont-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-900 dark:hover:bg-gray-700 cursor-pointer"
            >
                {{ __("Post") }}
            </button>
        </div>
        @csrf
    </form>

    @if (Route::is("comments.show"))
        <div>
            <a
                href="{{ route("posts.show", $post->public_id) }}"
                class="text-blue-500 hover:underline font-bold text-sm"
            >
                {{ __("Back to post") }}
            </a>
        </div>
    @endif

    @foreach ($this->comments as $comment)
        @include("partials.comment-recursive", ["comment" => $comment])
    @endforeach

    @if ($this->hasMore)
        <div
            x-intersect="$wire.loadMore()"
            class="h-20 flex justify-center items-center"
        >
            <div
                wire:loading
                class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 dark:border-white"
            ></div>
        </div>
    @endif
</div>
