@extends('layouts.app')

@section('content')

    <!-- Post Header Section -->
    <div class="">    
        <h1 class="text-4xl font-bold text-center dark:text-white">{{$page->title}}</h1>
            <ul class="flex flex-col md:flex-row items-center md:justify-center md:space-y-0 md:space-x-10 space-y-2 my-4">
                <li><a href="/profile/{{ $page->users->name_slug }}" class="text-gray-700 dark:text-gray-400 hover:text-sky-700"><i class="fa-solid fa-user mr-2"></i>{{ $page->users->name }}</a></li>
                <li class="text-gray-700 dark:text-gray-400"><i class="fa-solid fa-calendar-days mr-2"></i>{{ $page->date->format('d-m-Y') }}</li>
                <li><a href="/blog?category={{ $page->blogcategories->name }}" class="text-gray-700 dark:text-gray-400 hover:text-sky-700"><i class="fa-solid fa-folder mr-2"></i>{{ $page->blogcategories->name }}</a></li>
            </ul>    
        <p class="text-center md:w-1/2 mx-auto text-gray-500 dark:text-gray-300">{{$page->summary }}</p>
            <div class="flex space-x-4 my-6 justify-center">
                @foreach ($page->blogtags as $tag)
                    <a href="/blog?tag={{ $tag->name }}" class="p-1 text-xs uppercase border dark:border-gray-700 rounded font-bold bg-slate-600 text-white hover:bg-slate-400 hover:text-white">{{ $tag->name }}</a>
                @endforeach
            </div>

            <img src="{{ '/assets/images/uploads/' . 'large_' . $page->original_image }}" class="w-full h-[400px] shadow-lg border dark:border-gray-700 rounded" alt="">
    </div>

    <!-- Wrapper around post to split into post and sidebar -->

    <div class="flex space-x-10">
        <!-- Wrapper for the post content -->
        <div class="w-full">
            <!-- Table of contents -->
            <div class="w-full md:w-2/3 lg:w-1/3 mx-auto">
                @if(count($page->getBodyHeadings('h2')) > 2)
                    <div class="toc my-10">
                        <h2 id="toc-title" class="mb-2 border-b border-gray-300 cursor-pointer dark:text-white">
                            <i class="fa-solid fa-arrow-down-short-wide text-blue-600"></i> Table of contents
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

            <!-- Post text, separate from other content.  We do this as wise1text is used for formatting -->

            <div class="wise1text mt-10">
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

            <div class="flex flex-col md:flex-row items-center rounded shadow-lg border bg-slate-100 dark:bg-gray-600 dark:border-gray-700 p-4 my-10 space-y-2">
                <div class="w-3/12 lg:w-2/12">
                    <img src="/assets/images/avatars/{{ $page->users->avatar }}" class="mx-auto rounded-full h-20 border border-gray-400 p-1">
                </div>
                <div class="w-full lg:w-10/12">
                    <p class="font-bold text-center text-gray-700 dark:text-gray-300">{{ $page->users->name }}</p>
                    @if (empty($page->users->bio))
                    <!-- If no user Bio -->
                    <p class="text-center">User has not provided any information about themselves.</p>
                    @else
                        <!-- display user Bio -->
                        <p class="text-center text-sm dark:text-gray-200">{{ $page->users->bio }}</p>
                    @endif   
                </div>
            </div>

            <!-- Previous / Next Navigation -->
            @if ($previousPage || $nextPage)
                <div class="flex justify-between space-x-10 items-center mb-10">
                    @if ($previousPage)
                        <a href="{{ url('/blog/' . $previousPage->slug) }}"
                        class="text-slate-500 flex items-center">
                            <div class="flex gap-4 items-center p-2">
                                <i class="fa-solid fa-arrow-left ml-2"></i>
                                <div>
                                    <h3 class="font-semibold">{{ $previousPage->title }}</h3>
                                </div>
                            </div>
                        </a>
                    @else
                        <span>No Older Posts</span> <!-- preserves space on the left if no previous -->
                    @endif
                    
                    @if ($nextPage)
                        <a href="{{ url('/blog/' . $nextPage->slug) }}"
                        class="text-slate-500 flex items-center">
                            <div class="flex gap-4 items-center p-2">
                                <div>
                                    <h3 class="font-semibold">{{ $nextPage->title }}</h3>
                                </div>
                                <i class="fa-solid fa-arrow-right mr-2"></i>
                            </div>
                        </a>
                    @else
                        <span>No Newer Posts</span> <!-- preserves space on the right if no next -->
                    @endif
                </div>
            @endif

            <!-- Comments section, layout and formatting handled in comments.blade.php -->

            @include('comments', ['comments' => $page->comments, 'model' => $page])
        </div>
        <!-- Wrapper for the sidebar content -->
        <div class="hidden md:block w-3/12 mt-10">
            <H2 class="text-xl font-bold mb-4 dark:text-white">Recent Posts:</H2>
            @foreach ($recentPages as $recentPage)
                <div class="mb-6">
                    <img src="{{ '/assets/images/uploads/' . 'large_' . $recentPage->original_image }}" class="h-[150px] w-full rounded border dark:border-gray-700 p-1" alt="">
                    <h3 class="font-bold mt-2 dark:text-white"><a href="/blog/{{ $recentPage->slug }}">{{ $recentPage->title }}</a></h2>
                </div>               
            @endforeach
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