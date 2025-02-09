@extends('layouts.app')
@section('content')

    <div class="flex flex-col items-center space-y-4 bg-slate-100 dark:bg-slate-700 dark:text-white p-6 rounded shadow-lg">
        <h2 class="font-bold text-2xl">Links Section</h2>
        <p class="text-center w-2/3 ">This section contains useful links to various sites that provide infomration, tools, calculators and so on 
        related to mortgages that I have checked and verified as safe and correct.</p>
    </div>

    <!-- List of links and category menu -->
    <div class="flex flex-col-reverse md:flex md:flex-row md:space-x-10 mt-10">
        <div class="md:w-9/12">
            <div class="space-y-10">
                @foreach ($links as $link)
                    <div class="flex space-x-10 border dark:border-gray-600 rounded p-4 shadow-lg">
                        <img class="rounded border dark:border-gray-600 max-h-[150px]" src="{{ 'assets/images/uploads/' . $link->image }}" alt="">
                        <div class="flex flex-col w-full">
                            <a href="{{ $link->url }}"><h2 class="font-bold text-lg dark:text-white">{{ $link->title }}</h2></a>
                            <div class="dark:text-white text-gray-500">{!! $link->description !!}</div>
                            <a href="{{ $link->url }}">
                                <button class="self-start inline-block border dark:border-gray-600 text-white rounded py-1 px-2 bg-slate-500 my-2 hover:bg-slate-400 dark:hover:text-black text-xs ">
                                    Visit Website
                                </button>
                            </a>
                            @can('Admin')
                            <div class="flex justify-end space-x-2">
                                <form action="/links/{{ $link->id }}" method="POST" onsubmit="return confirm('Do you really want to delete this Link?');">
                                {{ csrf_field() }}
                                {{ method_field ('DELETE') }} 
                                <button class="hover:text-red-500 dark:text-white" role="button" type="submit">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                                </form>
                                <a class="hover:text-yellow-500 dark:text-pink-500" href="/links/{{ $link->id }}/edit" role="button"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                            </div>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Pagination -->
            <div class="w-3/4 mx-auto mt-10">
                {{ $links->links() }}
            </div>
        </div>
        <div class="md:w-3/12 mb-10">
            <div>
                <h2 class="font-bold text-xl border-b dark:border-gray-600 mb-2 dark:text-white">Categories</h2>
                <ul class=" dark:text-white">
                    @foreach ($categories as $category)
                        <li><a href="/links?category={{ $category->name }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            @can('Admin')
            <!-- Admin -->
            <div class="hidden md:block my-6">
             <h2 class="text-xl font-bold text-gray-700 dark:text-red-300 border-b dark:border-b-gray-700 border-gray-300 mb-4"><i class="fa-solid fa-user-secret text-red-800"></i> Admin Tools</h2>
                <div class="flex justify-center">
                    <p class="border dark:border-gray-700 p-1 bg-lime-400 text-black rounded text-sm"><a href="/links/create">Create New Link</a></p>
                </div>
                <div class="flex flex-col space-y-2 text-sm mt-4">
                    @foreach ($unpublished as $link)
                        <a href="../links/{{$link->id}}/edit" class="text-gray-700 hover:text-sky-700">{{ $link->title }}</a>
                    @endforeach
                </div>
            </div> 
           @endcan 
        </div>

    </div>

@endsection