<div class="py-8">
    <form method="POST" action="{{ route("posts.store") }}">
        <label
            for="default-search"
            class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white"
        >
            Search
        </label>
        <div class="relative">
            <input
                type="text"
                name="url"
                class="block w-full p-4 ps-8 text-sm text-gray-900 border border-gray-50 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:bg-gray-700 outline-none shadow-sm"
                placeholder="{{ __("Search or publish a link") }}"
                required
            />
            <button
                type="submit"
                class="text-white absolute end-2.5 bottom-2.5 bg-gray-600 hover:bg-gray-800 focus:ring-4 focus:outline-nonefont-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-900 dark:hover:bg-gray-700 cursor-pointer"
            >
                {{ __("Search") }}
            </button>
        </div>
        @csrf
    </form>
    @if ($errors->any())
        <div class="mt-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="text-red-300 text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
