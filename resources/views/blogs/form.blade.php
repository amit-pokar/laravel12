@csrf
<div class="space-y-6">
    {{-- Blog Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Blog Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $blog->name ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @else border @enderror"
            required>
        <p class="mt-1 text-sm text-gray-500">The slug will be auto-generated from the blog name</p>
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Blog Image --}}
    <div>
        <label for="image" class="block text-sm font-medium text-gray-700">Blog Image</label>
        <input type="file" name="image" id="image" accept="image/*"
            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('image') border-red-500 @else border @enderror">
        <p class="mt-1 text-sm text-gray-500">Max size: 2MB (JPEG, PNG, JPG, GIF)</p>
        @if(isset($blog) && $blog->image)
            <div class="mt-2">
                <p class="text-sm font-medium text-gray-700 mb-2">Current Image:</p>
                <img src="{{ Storage::url($blog->image) }}" alt="Blog Image" class="h-32 w-32 object-cover rounded">
            </div>
        @endif
        @error('image')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Blog Content --}}
    <div>
        <label for="content" class="block text-sm font-medium text-gray-700">Blog Content (HTML)</label>
        <textarea name="content" id="content" rows="10"
            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('content') border-red-500 @else border @enderror"
            required>{{ old('content', $blog->content ?? '') }}</textarea>
        @error('content')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Status --}}
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" id="status"
            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @else border @enderror"
            required>
            <option value="">Select Status</option>
            <option value="Active" @selected(old('status', $blog->status ?? '') === 'Active')>Active</option>
            <option value="Inactive" @selected(old('status', $blog->status ?? '') === 'Inactive')>Inactive</option>
        </select>
        @error('status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Submit Button --}}
    <div class="flex gap-4">
        <button type="submit" style="background-color: #16a34a; color: white;" class="px-4 py-2 rounded hover:opacity-90">
            {{ isset($blog) ? 'Update Blog' : 'Create Blog' }}
        </button>
        <a href="{{ route('blogs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
            Cancel
        </a>
    </div>
</div>
