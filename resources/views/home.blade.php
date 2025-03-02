@extends('layouts.app')
@section('content')
    <!-- header section -->
    <div class="mb-10 flex flex-col justify-center">
        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
        <p class="md:w-2/3 w-full mx-auto my-4 text-center dark:text-white">This is a personal property-related blog. Whilst I do my best to ensure everything contained in here is accurate and up to date, 
            there are times when it may not be. Also, double-check information regardless of where you see it on the internet. If you are 
            visiting this site as part of information gathering, please always consult with a suitably qualified professional before making 
            any material decisions related to mortgages.</p>
    </div>
    <!-- Recent Posts -->
    <div>
        <p class="border-b dark:border-b-gray-700 font-bold dark:text-white">Recent Posts</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10 my-4">
            @foreach ($posts as $post)
                <a href="/blog/{{ $post->slug }}">
                    <div class="">
                        <img src="{{ '/assets/images/uploads/' . 'small_' . $post->original_image }}" class="rounded shadow-lg w-full" alt="">
                        <p class="font-bold text-center mt-2 dark:text-white">{{ $post->title }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <!-- Other Posts -->
    <div class="flex flex-col space-y-10 md:flex-row md:space-x-10 md:space-y-0">
        <!-- Other recent posts -->
        <div class="w-full">
            <h2 class="border-b dark:border-b-gray-700 font-bold mb-2 dark:text-white">Older Posts </h2>
            @foreach ($other_posts as $other_post)
                <div class="flex items-center mb-4 dark:text-white">
                    <p class="w-3/12 md:w-2/12 shrink-0">{{ $other_post->date->format('d M') }}</p>
                    <h3><a href="/blog/{{ $other_post->slug }}">{{ $other_post->title }}</a></h3>
                </div>
            @endforeach
        </div>
        <!-- Recent Guides -->
        <div class="w-full">
            <h2 class="border-b dark:border-b-gray-700 font-bold mb-2 dark:text-white">Recent Guides </h2>
            @foreach ($guides as $guide)
                <div class="flex items-center mb-4 dark:text-white">
                    <p class="w-3/12 md:w-2/12 shrink-0">{{ $guide->date->format('d M') }}</p>
                    <h3><a href="/blog/{{ $guide->slug }}">{{ $guide->title }}</a></h3>
                </div>
            @endforeach
        </div>
    </div>
    <!-- Calculators -->
    <div class="flex flex-col space-y-10 md:flex-row md:space-y-0 md:space-x-10 mt-10">
        <!-- Mortgage Calculator -->
        <div class="border rounded shadow-lg bg-gray-100 dark:bg-inherit dark:border-gray-700 dark:text-white p-4 w-full">
            <h2 class="font-bold text-lg text-center">Mortgage Calculator</h2>
            <p class="text-center">Check out our Mortgage Calculator to understand what you will pay whether on a Capital Repayment or 
                Interest Only basis. Also understand the impact stress rates being applied could have on payments.</p>
            <div class="text-center">
                <a href="/calculators/mortgage-payments"><button class="mt-4 border dark:border-gray-700 rounded bg-slate-500 hover:bg-slate-400 text-white p-2 text-xs uppercase">Open Calculator</button></a>
            </div>
        </div>
        <!-- Stamp Duty Calculator -->
        <div class="border rounded shadow-lg bg-gray-100 dark:bg-inherit dark:border-gray-700 dark:text-white p-4 w-full">
            <h2 class="font-bold text-lg text-center">Stamp Duty Calculator</h2>
            <p class="text-center">If you need to know what stamp duty will be paid on a property purchase, I have that covered as well. 
                Click below to see the calculator which covers all regions in the UK.</p>
            <div class="text-center">
                <a href="/calculators/stamp-duty"><button class="mt-4 border dark:border-gray-700 rounded bg-slate-500 hover:bg-slate-400 text-white p-2 text-xs uppercase">Open Calculator</button></a>
            </div>
        </div>
    </div>
@endsection