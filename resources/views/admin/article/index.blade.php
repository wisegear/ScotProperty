@extends('layouts.admin')

@section('content')

    <div class="flex flex-col rounded items-center bg-lime-300 p-4">
        <h2 class="font-bold text-2xl">Article Management</h2>
        <p>Create new and manage existing articles here.</p>
    </div>

    <!-- Button to create new article -->  
    <div class="my-10 text-center space-x-10">
        <a href="/admin/article/create"><button class="border rounded p-2 uppercase font-bold bg-lime-500 hover:bg-lime-300 text-xs">Create New Article</button></a>
        <a href="/admin/articles"><button class="border rounded p-2 uppercase font-bold bg-slate-700 hover:bg-slate-500 text-white text-xs">Manage Categories</button></a>
    </div>

    <!-- List existing articles -->
    <div class="">
        <h2 class="font-bold text-lg">Existing Articles:</h2>
        <table class="my-4 w-full">
            <tr class="bg-lime-500">
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Order</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            @foreach ($pages as $page)
             
            <tr>
                <td>{{ $page->id }}</td>
                <td>{{ $page->title }}</td>
                <td>{{ $page->articles->name }}</td>
                <td>{{ $page->order }}</td>
                <td><a href="/admin/article/{{ $page->id }}/edit"><button class="border p-1 uppercase bg-orange-500 rounded text-xs text-white">Edit</button></a></td>
                <td>
                    <form action="/admin/article/{{ $page->id }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="border p-1 uppercase bg-red-500 rounded text-xs text-white">Delete</button>
                    </form>
                </td>
            </tr>            

            @endforeach
        </table>
    </div>

@endsection