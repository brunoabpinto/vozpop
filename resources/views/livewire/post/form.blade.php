<?php

use function Livewire\Volt\{state, computed};
use App\Models\Post;

state(["url" => "", "search" => ""]);

$submit = function () {
    $this->validate([
        "url" => "required|url",
    ]);

    Post::create([
        "url" => $this->url,
        "user_id" => auth()->id(),
    ]);

    $this->url = "";
    $this->search = "";
};

$posts = computed(function () {
    if (empty($this->search)) {
        return Post::with("user")
            ->latest()
            ->get();
    }

    return Post::with("user")
        ->where("title", "like", "%{$this->search}%")
        ->orWhere("url", "like", "%{$this->search}%")
        ->latest()
        ->get();
});

?>

<div>
    <div class="py-12">
        <form wire:submit="submit">
            <label
                for="default-search"
                class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white"
            >
                Search
            </label>
            <div class="relative">
                <input
                    type="text"
                    wire:model.live="url"
                    wire:keydown="search = url"
                    class="block w-full p-4 ps-8 text-sm text-gray-900 border border-gray-50 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:bg-gray-700 outline-none shadow-sm"
                    placeholder="https://www.example.com"
                    required
                />
                <button
                    type="submit"
                    class="text-white absolute end-2.5 bottom-2.5 bg-gray-600 hover:bg-gray-800 focus:ring-4 focus:outline-nonefont-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-900 dark:hover:bg-gray-700 cursor-pointer"
                >
                    {{ __("Post") }}
                </button>
            </div>
        </form>
        @error("url")
            <div class="mt-4">
                <span class="text-red-300 text-sm">{{ $message }}</span>
            </div>
        @enderror
    </div>

    <div class="space-y-4">
        @forelse ($this->posts as $post)
            <x-post.card :$post />
        @empty
            <p class="text-gray-500">No posts found</p>
        @endforelse
    </div>
</div>
