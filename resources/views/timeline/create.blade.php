@extends('layouts.app')

@section('content')

    <div>
        <h2 class="text-2xl font-bold text-center">Create a new timeline event</h2>
        <p class="text-center text-gray-500">Use this form to create a new event for the timeline.</p>
        @if (session('success'))
        <div class="bg-lime-500 text-white p-4 rounded mb-4 my-10">
            {{ session('success') }}
        </div>
    @endif
    </div>

    <div class="my-10">
        <form action="/timeline" method="POST">
        @csrf
        <div class="flex flex-col w-1/2 mb-10">
            <label for="date" class="font-bold mb-2">Timeline Date</label>
            @error('qtimeline_date')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
            <input class="rounded-lg" type="date" id="timeline_date" name="timeline_date" value="{{ old('timeline_date') }}"> 
        </div>
            <div class="flex flex-col w-1/2">
                <label for="title" class="font-bold mb-2">Timeline Title</label>
                @error('timeline_title')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
                <input class="rounded-lg" type="text" id="timeline_title" name="timeline_title" placeholder="Enter the name of the author" value="{{ old('timeline_title') }}"> 
            </div>
            <div class="flex flex-col w-1/2 my-10">
                <label for="text" class="font-bold mb-2">Timeline Text</label>
                @error('timeline_text')
                    <span class="text-red-500">{{ $message }}</span>
                 @enderror
                <textarea class="rounded-lg" type="text" id="timeline_text" name="timeline_text" placeholder="Enter the quote here">{{ old('timeline_text') }}</textarea> 
            </div>
            <div class="flex space-x-10 items-center mb-10">
                <label for="published">Published?</label>
                <input type="checkbox" name="published" id="published" class="rounded-lg" checked>
            </div>
            <div>
                <button type="submit" class="border p-2 rounded-lg bg-lime-400 font-bold text-gray-600 text-sm">Create Event</button>
            </div>
        </form>
    </div>

@endsection