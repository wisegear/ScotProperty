@extends('layouts.admin')

@section('content')

<div class="flex flex-col rounded items-center bg-lime-300 p-4">
    <h2 class="font-bold text-2xl">Article Category Management</h2>
    <p>Create new article categories and manage existing articles here.</p>
</div>

<!-- Display category creation success message -->
@if (session('created'))
    <div class="bg-green-500 text-white p-4 rounded mb-4 my-10 mx-auto">
        {{ session('created') }}
    </div>
@endif

<!-- Display category creation success message -->
@if (session('updated'))
    <div class="bg-green-500 text-white p-4 rounded mb-4 my-10 mx-auto">
        {{ session('updated') }}
    </div>
@endif

<!-- Display category creation success message -->
@if (session('deleted'))
    <div class="bg-red-500 text-white p-4 rounded mb-4 my-10 mx-auto">
        {{ session('deleted') }}
    </div>
@endif

<!-- Create new Articles Category --> 
<div>
    <form action="/admin/articles" method="post">
        @csrf
        <input type="hidden" name="form_type" value="create">
        <div class="flex flex-col border rounded shadow-lg my-10 w-1/2 mx-auto p-6">

            <label for="create_articles" class="font-bold mb-2">Enter name of article category</label>
            @error('create_name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
            <input type="text" id="create_articles" name="create_name" class="rounded mb-4" value="{{ old('create_name') }}">

            <label for="article_order" class="font-bold mb-2">Enter order of article category</label>
            @error('article_order')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
            <input type="number" id="article_order" name="article_order" class="rounded w-1/5" value="{{ old('article_order') }}">

            <div class="flex items-center space-x-2 my-4">
                <label for="nav_item" class="font-bold">Is this article category a navigation item?</label>
                <input type="checkbox" name="create_navigation" id="nav_item" class="rounded-full">
            </div>
            <button type="submit" class="inline-block self-start mx-auto mt-4 border rounded p-2 bg-lime-400 hover:bg-lime-300">Create</button>
        </div>
    </form>
</div>

<!-- View existing Articles and allow them to be amended --> 
<div class="my-10 flex flex-col w-2/3 mx-auto">
    <h2 class="font-bold text-xl mb-4">Existing Categories:</h2>
    <table class="border-collapse w-full">
        <thead>
            <tr class="bg-lime-400">
                <th>ID</th>
                <th>Name</th>
                <th>Order</th>
                <th>Has Articles?</th>
                <th>Is nav?</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    
                    <!-- Editable Name Input with Update Form -->
                    <form action="{{ route('articles.update', $category->id) }}" method="POST" class="flex items-center">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="form_type" value="update">
                        <td>
                            @error('update_name')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                            <input 
                                type="text" 
                                name="update_name" 
                                value="{{ $category->name }}" 
                                class="border rounded p-1 w-full"
                                required
                            />
                        </td>

                        <td>
                            @error('article_order')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                            <input 
                                type="number" 
                                name="article_order" 
                                value="{{ $category->order }}" 
                                class="border rounded p-1 w-full text-center"
                                min="0" 
                                max="255" 
                                required
                            />
                        </td>

                        <td class="text-center">{{ $category->article->count() }}</td>
                        <td>
                            <!-- Is Nav Dropdown -->
                            <select name="update_navigation" class="border rounded p-1 w-full">
                                <option value="0" {{ $category->navigation == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ $category->navigation == 1 ? 'selected' : '' }}>Yes</option>
                            </select>
                        </td>
                        <td class="space-x-4 text-center">
                            <!-- Update Button -->
                            <button type="submit" class="border rounded p-2 bg-lime-500 hover:bg-lime-400 font-bold text-xs uppercase">
                                Update
                            </button>
                        </td>
                    </form>

                    <!-- Delete Form -->
                    <form action="{{ route('articles.destroy', $category->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <td class="space-x-4 text-center">
                            <button 
                                type="submit" 
                                class="border rounded p-2 font-bold text-xs text-white uppercase 
                                {{ $category->article->count() > 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-red-500 hover:bg-red-400' }}" 
                                {{ $category->article->count() > 0 ? 'disabled' : '' }}
                                onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.');"
                            >
                                Delete
                            </button>
                        </td>
                    </form>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection