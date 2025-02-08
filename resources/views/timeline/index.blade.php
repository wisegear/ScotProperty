@extends('layouts.app')

@section('content')

    <div class="flex flex-col justify-center items-center h-[100px] border mb-10 bg-gray-100 shadow-lg">
        <h2 class="font-bold text-2xl">Holocaust Timeline</h2>
        <p class="text-gray-600">Explore the events as they unfolded from the early 30s through to the mid 40s</p>
    </div>

    @can('Admin')
    <div class="text-center">
        <a href="/timeline/create"><button class="border rounded p-1 text-sm bg-lime-500 font-bold">Create Event</button></a>
    </div>
    @endcan

    <div class="text-center my-10">
        <h2 class="text-lg mb-4 font-bold">Select a specific year or search for something more specific.</h2>
    </div>

    <div class="flex justify-center space-x-4">
        <button class="border p-1 bg-lime-300 rounded text-sm hover:bg-lime-500 hover:text-white"><a href="/timeline?year=earlier">Earlier</a></button>
        @foreach ($years as $year)
            <button class="border p-1 bg-slate-300 rounded text-sm hover:bg-slate-500 hover:text-white"><a href="/timeline?year={{ $year }}">{{ $year }}</a></button>
        @endforeach
        <button class="border p-1 bg-lime-300 rounded text-sm hover:bg-lime-500 hover:text-white"><a href="/timeline?year=later">Later</a></button>
    </div>

    <div class="flex justify-center my-10">
        <form method="get" action="/timeline" class="mb-5 w-1/3">
            <div class="relative">
                <input type="text" class="border border-gray-300 rounded-md w-full text-sm pl-2 pr-10" id="search" name="search" placeholder="Search events instead of years">
                <button class=" absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </form>
    </div>

    <div class="relative my-10">
        <!-- Timeline -->
        <div class="timeline-outer">
            <div class="border-t border-gray-800 pb-10"></div>
    
            @foreach ($events as $index => $event)
                <div class="timeline-container {{ $loop->iteration % 2 == 0 ? 'timeline-right' : 'timeline-left' }}">
                    <div class="timeline-content">
                        <!-- Timeline Date (Formatted) -->
                        <h2 class="timeline-date">{{ \Carbon\Carbon::parse($event->date)->format('jS M Y') }}</h2>
    
                        <!-- Event Name -->
                        <h2 class="text-md font-bold pb-2">{{ $event->title }}</h2>
    
                        <!-- Event Text -->
                        <div class="text-xs">{{ $event->text }}</div>
    
                        <!-- Admin Edit Button (Check if Authenticated and Admin) -->
                        @can('Admin') <!-- Assuming you have a gate called 'Admin' for admin users -->
                        <div class="flex space-x-4 justify-end">
                            <form action="/timeline/{{ $event->id }}" method="POST" onsubmit="return confirm('Do you really want to delete this event?');">
                                {{ csrf_field() }}
                                {{ method_field ('DELETE') }} 
                                <button class="hover:text-red-500" role="button" type="submit">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                            <a class="hover:text-yellow-500" href="/timeline/{{ $event->id }}/edit" role="button"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                        </div>
                        @endcan
                    </div>
                </div>
            @endforeach
    
            <div class="border-b border-gray-800 pb-10"></div>
        </div>
    </div>

    <div class="flex justify-center">
        <div class="w-1/2">
            {{ $events->links() }}
        </div>
    </div>

@endsection