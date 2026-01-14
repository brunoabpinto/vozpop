<div
    class="text-gray-700 dark:text-gray-300"
    x-data="{
        showForm: false,
    }"
>
    <div class="flex items-center gap-2">
        <img
            class="w-9 rounded-lg"
            src="{{ $comment->user->photo ?? asset("img/avatar.png") }}"
        />
        <div>
            <div class="font-semibold text-sm">
                {{ $comment->user->username }}
            </div>
            <span class="text-xs text-gray-400 font-semibold">
                {{ $comment->created_at->diffForHumans() }}
            </span>
        </div>
    </div>
    <div class="text-sm mt-2 ml-2 py-2 px-4 border-s-2">
        {{ $comment->content }}
    </div>

    <div class="py-2" x-show="showForm" x-transition x-cloak>
        <form wire:submit="postComment" @submit="showForm = !showForm">
            <div class="relative">
                <textarea
                    x-ref="textarea"
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
    </div>

    <div class="flex items-center gap-2 justify-start">
        <div
            class="inline-flex items-center gap-2 bg-gray-200 dark:bg-gray-900 rounded-xl p-1"
        >
            <button
                class="cursor-pointer"
                wire:click="upvote({{ $comment->id }})"
            >
                <x-heroicon-s-arrow-up-circle
                    width="16"
                    @class(["text-green-400 dark:text-green-300 opacity-100" => $comment->is_liked === 1, "opacity-50 hover:opacity-100 hover:text-green-400 hover:dark:text-green-300"])
                />
            </button>
            <span
                class="text-[11px] font-semibold text-gray-600 dark:text-gray-400"
            >
                {{ $comment->votes }}
            </span>
            <button
                class="cursor-pointer"
                wire:click="downvote({{ $comment->id }})"
            >
                <x-heroicon-s-arrow-down-circle
                    width="16"
                    @class(["text-red-400 dark:text-red-300 opacity-100" => $comment->is_liked === 0, "opacity-50 hover:opacity-100 hover:text-red-400 hover:dark:text-red-300"])
                />
            </button>
        </div>

        <div
            class="inline-flex gap-4 bg-gray-200 dark:bg-gray-900 rounded-xl p-1 items-center"
        >
            <button
                type="button"
                @click="showForm = !showForm;  $nextTick(() => $refs.textarea?.focus())"
                wire:click="setReplyTo({{ $comment->id }})"
            >
                <x-heroicon-c-chat-bubble-left-right
                    width="16"
                    class="opacity-50 hover:opacity-100 hover:text-blue-400 hover:dark:text-blue-300"
                />
            </button>
        </div>
    </div>
</div>
