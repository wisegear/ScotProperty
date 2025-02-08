@extends('layouts.app')

@section('content')

<!-- Article Header Section -->

<div class="">    
    <h1 class="text-4xl font-bold text-center dark:text-white">{{$page->title}}</h1>
        <ul class="my-4 flex flex-col md:flex-row items-center md:justify-center md:space-y-0 space-y-4 md:space-x-10">
            <li><a href="/profile/{{ $page->user->name_slug }}" class="text-gray-700 hover:text-sky-700 dark:text-white"><i class="fa-solid fa-user mr-2"></i>{{ $page->user->name }}</a></li>
            <li class="text-gray-700 dark:text-white"><i class="fa-solid fa-calendar-days mr-2 "></i>{{ $page->date->format('d-m-Y') }}</li>
            <li class="text-gray-700 dark:text-white"><i class="fa-solid fa-folder mr-2"></i>{{ $page->articles->name }}</li>
        </ul>    
    <p class="text-center md:w-1/2 mx-auto text-gray-500 mb-10 dark:text-gray-300">{{$page->summary }}</p>

    <img src="{{ '/assets/images/uploads/' . 'large_' . $page->original_image }}" class="w-full max-h-[350px] shadow-lg border dark:border-gray-700 rounded p-1" alt="">
</div>

<!-- Main article section split by flex of main content and sidebar content -->

<div class="flex flex-col md:flex-row mt-10 space-x-10">
    <!-- Main Content -->
    <div class="w-full">
        <div class="w-1/2 mx-auto mb-10">
            @if(count($page->getBodyHeadings('h2')) > 2)
                <div class="toc">
                    <h2 id="toc-title" class="mb-2 border-b border-gray-300 cursor-pointer dark:text-white">
                        <i class="fa-solid fa-arrow-down-short-wide text-lime-600 mr-2"></i><span class="font-bold">Table of contents</span>
                        <span id="toc-arrow" class="ml-2 transform transition-transform duration-200"></span>
                    </h2>
                    <ul id="toc-list" class="space-y-2 hidden">
                        @foreach($page->getBodyHeadings('h2') as $heading)
                            <li><a href="#{{ Str::slug($heading) }}" class="hover:text-blue-700 dark:text-white">{{ $heading }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Post Text, separate from other content due to Wise1Text -->

        <div class="wise1text">
            {!! $page->addAnchorLinkstoHeadings() !!}
        </div>

            <!-- Share Buttons -->
            <div class="mt-10 text-center">
                <a href="#blank"><button class="border border-gray-300 dark:border-gray-600 dark:text-gray-300 p-1 text-gray-500 text-xs font-bold py-1 px-2 mr-2">Share</button></a>

                <!-- Twitter Share -->
                <a href="http://twitter.com/share?url={{ url()->current() }}&text={{ urlencode($page->title) }}">
                    <button id="social-button" aria-label="twitter-button" class="border border-gray-300 py-1 px-2 text-indigo-500 text-xs mr-2 dark:border-gray-600 hover:border-gray-400">
                        <i class="fa-brands fa-twitter text-[#1da1f2]"></i>
                    </button>
                </a>

                <!-- LinkedIn Share -->
                <a href="http://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}&title={{ urlencode($page->title) }}">
                    <button id="social-button" aria-label="linkedin-button" class="border border-gray-300 p-1 text-indigo-500 text-xs py-1 px-2 mr-2 dark:border-gray-600 hover:border-gray-400">
                        <i class="fa-brands fa-linkedin-in text-[#0a66c2]"></i>
                    </button>
                </a>

                <!-- Facebook Share -->
                <a href="http://www.facebook.com/sharer.php?u={{ url()->current() }}">
                    <button id="social-button" aria-label="facebook-button" class="border border-gray-300 p-1 text-indigo-500 text-xs py-1 px-2 dark:border-gray-600 hover:border-gray-400">
                        <i class="fa-brands fa-facebook-f text-[#1877f2]"></i>
                    </button>
                </a>
            </div>

            <!-- Author Box -->

            <div class="flex flex-col md:flex-row items-center rounded shadow-lg border bg-lime-100 dark:bg-gray-600 dark:border-gray-700 p-4 my-10 space-y-2">
                <div class="w-3/12 lg:w-2/12">
                    <img src="/assets/images/avatars/{{ $page->user->avatar }}" class="mx-auto rounded-full h-20 border border-gray-400 p-1">
                </div>
                <div class="w-full lg:w-10/12">
                    <p class="font-bold text-center text-gray-700 dark:text-gray-300">{{ $page->user->name }}</p>
                    @if (empty($page->user->bio))
                    <!-- If no user Bio -->
                    <p class="text-center">User has not provided any information about themselves.</p>
                    @else
                        <!-- display user Bio -->
                        <p class="text-center text-sm dark:text-gray-200">{{ $page->user->bio }}</p>
                    @endif   
                </div>
            </div>

        @include('comments', ['comments' => $page->comments, 'model' => $page])

    </div>
    <!-- Sidebar Content -->
    <div class="hidden md:block md:w-3/12 space-y-6">
        <div>
            <h2 class="font-bold text-xl border-b dark:border-b-gray-600 dark:text-white">{{ $page->articles->name }}</h2>
            <ol class="my-2 list-decimal list-inside dark:text-white">
                @foreach ($allPages as $item)
                    <li class="mb-2"><a href="/article/{{ $item->slug }}" class="dark:text-white">{{ $item->title }}</a></li>
                @endforeach
            </ol>
        </div>
        <div class="space-y-6">
            <h2 class="font-bold text-xl border-b dark:border-b-gray-600 dark:text-white">Recent Blog Posts</h2>
            @foreach ($posts as $post)
                <div class="">
                    <img src="{{ '/assets/images/uploads/' . 'small_' . $post->original_image }}" class="h-[150px] w-full rounded border dark:border-gray-600 p-1" alt="Post Image">
                    <h3 class="font-bold mt-2 dark:text-white"><a href="/blog/{{ $post->slug }}">{{ $post->title }}</a></h2>
                </div>               
            @endforeach

        </div>
    </div>

</div>

<script>

// This handles the dropdown of the table of contents

    document.addEventListener("DOMContentLoaded", function () {
        const tocTitle = document.getElementById('toc-title');
        const tocList = document.getElementById('toc-list');
        const tocArrow = document.getElementById('toc-arrow');

        // Toggle the visibility of the table of contents
        tocTitle.addEventListener('click', function () {
            tocList.classList.toggle('hidden'); // Show or hide the TOC list
            tocArrow.classList.toggle('rotate-90'); // Rotate the arrow indicator
        });
    });

</script>

@endsection