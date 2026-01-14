<?php

use function Livewire\Volt\{state, computed, uses};
use App\Models\Post;

uses(["Livewire\WithPagination"]);

state(["perPage" => 10]);

$loadMore = function () {
    $this->perPage += 10;
    $this->resetPage();
};

$upvote = function (Post $post) {
    $userId = 1;
    $like = $post
        ->likes()
        ->where("user_id", $userId)
        ->where("post_id", $post->id)
        ->first();

    if ($like) {
        if ($like->liked) {
            $like->delete();
        } else {
            $like->update(["liked" => true]);
        }
    } else {
        $post->likes()->create([
            "user_id" => $userId,
            "post_id" => $post->id,
            "liked" => true,
        ]);
    }
};

$downvote = function (Post $post) {
    $userId = 1;
    $like = $post
        ->likes()
        ->where("user_id", $userId)
        ->where("post_id", $post->id)
        ->first();

    if ($like) {
        if (! $like->liked) {
            $like->delete();
        } else {
            $like->update(["liked" => false]);
        }
    } else {
        $post->likes()->create([
            "user_id" => $userId,
            "post_id" => $post->id,
            "liked" => false,
        ]);
    }
};

$posts = computed(function () {
    return Post::with("user")
        ->latest()
        ->take($this->perPage)
        ->get();
});

$hasMore = computed(function () {
    return Post::count() > $this->perPage;
});

?>

<div class="space-y-8">
    @foreach ($this->posts as $post)
        <x-post.card :$post />
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
