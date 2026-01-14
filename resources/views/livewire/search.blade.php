<?php

use function Livewire\Volt\{state, computed};
use App\Models\Post;

state(["search" => ""]);

$filteredResults = computed(function () {
    if (empty($this->search)) {
        return [];
    }

    return Post::where("title", "like", "%{$this->search}%")
        ->latest()
        ->take(10)
        ->get();
});

?>

<div class="space-y-4">
    <div class="relative">
        <input
            type="text"
            name="url"
            wire:model.live="search"
            class="block w-full p-2 text-sm text-gray-900 border border-gray-50 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:bg-gray-700 outline-none shadow-sm"
            placeholder="{{ __("Search posts...") }}"
            required
        />
    </div>

    @if (! empty($search))
        <ul class="space-y-2 font-medium text-sm">
            @forelse ($this->filteredResults as $post)
                <li>
                    <a
                        href="{{ route("posts.show", $post->public_id) }}"
                        class="flex items-center gap-2 hover:bg-gray-200 dark:hover:bg-gray-900 p-2 rounded-lg"
                        title="{{ $post->title }}"
                    >
                        <img
                            src="{{ $post->image }}"
                            alt="Avatar"
                            class="size-8 rounded-lg mr-2"
                        />
                        <span class="w-full truncate">
                            {{ $post->title }}
                        </span>
                    </a>
                </li>
            @empty
                <li>No results found</li>
            @endforelse
        </ul>
    @endif
</div>
