<div
    class="w-64 xl:w-72 fixed h-full self-start top-0 border-r dark:border-gray-800 p-6 space-y-12"
    x-data="{ theme: localStorage.getItem('color-theme') || 'light' }"
>
    <div class="flex justify-end gap-4">
        <a
            href="{{ route("posts.index") }}"
            class="bg-gray-300 dark:bg-gray-800 rounded-full p-2 hover:bg-gray-500"
            title="{{ __("Homepage") }}"
        >
            <x-heroicon-c-home class="w-6" />
        </a>
        <button
            x-show="theme === 'dark'"
            class="bg-gray-800 rounded-full p-2 hover:bg-gray-700"
            @click="toggleTheme();theme='light'"
            title="{{ __("Toggle light theme") }}"
        >
            <x-heroicon-c-sun class="w-6" />
        </button>

        <button
            x-show="theme === 'light'"
            class="bg-gray-300 rounded-full p-2 hover:bg-gray-500"
            @click="toggleTheme();theme='dark'"
            title="{{ __("Toggle dark theme") }}"
        >
            <x-heroicon-c-moon class="w-6" />
        </button>
    </div>

    @auth
        <div
            class="flex items-center gap-4 hover:bg-gray-200 dark:hover:bg-gray-900 p-2 rounded-lg"
        >
            <img
                class="w-9 rounded-lg"
                src="{{ $comment->user->photo ?? asset("img/avatar.png") }}"
            />
            <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
        </div>
    @endauth

    <div class="space-y-4">
        <div class="flex items-center gap-2 opacity-75">
            <div class="text-sm font-semibold">Recent</div>
        </div>
        <ul class="space-y-2 font-medium text-sm">
            @foreach ($recent as $post)
                <li>
                    <a
                        href="{{ route("posts.show", $post["id"]) }}"
                        class="flex items-center gap-2 hover:bg-gray-200 dark:hover:bg-gray-900 p-2 rounded-lg"
                    >
                        <img
                            src="{{ $post["image"] }}"
                            alt="Avatar"
                            class="size-8 rounded-lg mr-2"
                        />
                        <span class="w-full truncate">
                            {{ $post["title"] }}
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div>
        <livewire:search />
    </div>
</div>
