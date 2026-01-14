<x-layout>
    <div class="py-8">
        <x-post.card :$post />
        <livewire:comments :post="$post" :commentId="$commentId ?? null" />
    </div>
</x-layout>
