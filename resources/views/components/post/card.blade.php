<div
    class="bg-gray-50 border border-gray-50 rounded-lg shadow-sm md:flex-row dark:border-gray-700 dark:bg-gray-800 overflow-hidden dark:bg-gray-800 w-3xl 2xl:w-4xl"
>
    <a
        target="{{ Route::is("posts.show") ? "_blank" : "_self" }}"
        href="{{ Route::is("posts.show") ? $post->url : route("posts.show", $post->public_id) }}"
        class="flex flex-col items-center md:flex-row dark:hover:bg-gray-700 hover:bg-gray-100"
    >
        <img
            class="object-cover h-42 md:w-48"
            src="{{ $post->image }}"
            alt=""
        />
        <div class="flex flex-col justify-start p-4 leading-normal max-h-42">
            <h5
                class="my-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white"
            >
                {{ $post->title }}
            </h5>
            <p
                class="mb-3 font-normal text-sm text-gray-700 dark:text-gray-400"
            >
                {!! $post->lead !!}
            </p>
        </div>
    </a>

    <div
        class="flex items-center justify-end gap-2 p-2 border-t dark:border-gray-700"
    >
        <div class="flex items-center gap-2">
            <img
                class="w-6 rounded-lg"
                src="{{ $post->user->photo ?? asset("img/avatar.png") }}"
            />
            <div class="text-xs font-semibold">
                {{ $post->user->username }}
            </div>
        </div>
        <span class="text-xs text-gray-400 font-bold">
            {{ $post->created_at->diffForHumans() }}
        </span>
        <div
            class="inline-flex items-center gap-2 bg-gray-100 dark:bg-gray-900 rounded-xl p-2"
        >
            <button
                class="cursor-pointer"
                wire:click="upvote('{{ $post->public_id }}')"
            >
                <x-heroicon-s-arrow-up-circle
                    width="18"
                    @class(["text-green-300" => $post->is_liked === 1, "opacity-50 hover:opacity-100 hover:text-green-300"])
                />
            </button>
            <span class="text-xs font-bold">{{ $post->votes }}</span>

            <button
                class="cursor-pointer"
                wire:click="downvote('{{ $post->public_id }}')"
            >
                <x-heroicon-s-arrow-down-circle
                    width="18"
                    @class(["text-red-300" => $post->is_liked === 0, "opacity-50 hover:opacity-100 hover:text-red-300"])
                />
            </button>
        </div>
        <div
            class="inline-flex gap-4 bg-gray-100 dark:bg-gray-900 rounded-xl p-2 items-center"
        >
            <div class="flex gap-2 items-center">
                <x-heroicon-o-chat-bubble-bottom-center-text
                    width="18"
                    class="opacity-50"
                />
                <span class="text-xs font-bold">
                    {{ $post->comments->count() }}
                </span>
            </div>
        </div>
        <div
            class="inline-flex gap-4 bg-gray-100 dark:bg-gray-900 rounded-xl p-2 items-center"
        >
            <x-heroicon-s-share width="18" class="opacity-50" />
        </div>
        <div
            class="inline-flex gap-4 bg-gray-100 dark:bg-gray-900 rounded-xl p-2 items-center"
        >
            <a href="{{ $post->url }}" target="_blank">
                <x-heroicon-s-link width="18" class="opacity-50" />
            </a>
        </div>
    </div>
</div>
