<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Blogs') }}
            </h2>
            <a href="{{ route('blogs.create') }}" style="background-color: #16a34a; color: white;" class="px-4 py-2 rounded hover:opacity-90">
                + New Blog
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-green-900 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($blogs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-semibold">Image</th>
                                        <th class="px-6 py-3 text-left font-semibold">Name</th>
                                        <th class="px-6 py-3 text-left font-semibold">Slug</th>
                                        <th class="px-6 py-3 text-left font-semibold">Status</th>
                                        <th class="px-6 py-3 text-left font-semibold">Created</th>
                                        <th class="px-6 py-3 text-center font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($blogs as $blog)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                            <td class="px-6 py-4">
                                                @if($blog->thumbnail)
                                                    <img src="{{ Storage::url($blog->thumbnail) }}" alt="{{ $blog->name }}" class="h-16 w-16 object-cover rounded">
                                                @elseif($blog->image)
                                                    <img src="{{ Storage::url($blog->image) }}" alt="{{ $blog->name }}" class="h-16 w-16 object-cover rounded">
                                                @else
                                                    <span class="text-gray-400">No image</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 font-medium">{{ $blog->name }}</td>
                                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $blog->slug }}</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                    @if($blog->status === 'Active')
                                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100
                                                    @else
                                                        bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100
                                                    @endif
                                                ">
                                                    {{ $blog->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $blog->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 text-center space-x-2">
                                                <a href="{{ route('blogs.show', $blog) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                                    View
                                                </a>
                                                <a href="{{ route('blogs.edit', $blog) }}" class="text-orange-600 dark:text-orange-400 hover:text-orange-900 dark:hover:text-orange-300">
                                                    Edit
                                                </a>
                                                <form action="{{ route('blogs.destroy', $blog) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                No blogs found. <a href="{{ route('blogs.create') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Create one</a>
                                            </td>
                                        </tr>
                                    @endempty
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $blogs->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 dark:text-gray-400 mb-4">No blogs yet</p>
                            <a href="{{ route('blogs.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Create Your First Blog
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
