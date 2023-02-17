<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-splade-table :for="$posts">
            @cell('action', $post)
            <Link href="{{ route('posts.edit', $post->id) }}" class="text-green-600 hover:text-green-400 font-semibold">Edit</Link>
            @endcell
        </x-splade-table>
        </div>
    </div>
</x-app-layout>
