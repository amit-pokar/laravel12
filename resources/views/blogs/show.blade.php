<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $blog->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('blogs.edit', $blog) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Edit
                </a>
                <form action="{{ route('blogs.destroy', $blog) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure?')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Blog Meta Information --}}
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Slug</p>
                                <p class="font-medium">{{ $blog->slug }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Status</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($blog->status === 'Active')
                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100
                                    @else
                                        bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100
                                    @endif
                                ">
                                    {{ $blog->status }}
                                </span>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Created</p>
                                <p class="font-medium">{{ $blog->created_at->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Last Updated</p>
                                <p class="font-medium">{{ $blog->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Blog Image --}}
                    @if($blog->image)
                        <div class="mb-6">
                            <img src="{{ Storage::url($blog->image) }}" alt="{{ $blog->name }}" class="w-full h-96 object-cover rounded-lg">
                        </div>
                    @endif

                    {{-- Blog Content --}}
                    <div class="prose dark:prose-invert max-w-none">
                        {!! $blog->content !!}
                    </div>
                </div>
            </div>

            {{-- Back Button --}}
            <div class="mt-6">
                <a href="{{ route('blogs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 transition ease-in-out duration-150">
                    Back to Blogs
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
