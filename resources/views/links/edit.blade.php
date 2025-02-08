@extends('layouts.app')

@section('content')

    <h2 class="text-2xl text-center font-bold mb-10">Edit an Existing Link</h2>

    <div class="w-3/4 mx-auto">

        <form method="POST" action="/links/{{ $page->id }}" enctype="multipart/form-data">
        @csrf
        {{ method_field('PUT') }}
            
        <div class="flex space-x-10 mb-10">
            <div class="w-1/2">
                <!-- Upload Featured Image -->
                <div class="flex flex-col mb-4">
                    <label for="image" class="text-gray-700 mb-2 font-bold">Upload New Link Image (optional)</label>
                    <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)">
                </div>

                <!-- Image preview container -->
                <div id="image-preview" class="">
                    <img id="preview" class="w-full" />
                </div>            
            </div>
            <div class="w-1/2">
                <h2 class="font-bold text-lg mb-12">Existing Link Image</h2>
                @if($page->image)
                    <img src="{{ '/assets/images/uploads/' . $page->image }}" class="w-full h-[350px]" alt="Featured Image">
                @else
                    <p class="text-gray-600">No featured image available</p>
                @endif
            </div>
        </div>       
                
                <!-- Title --> 
                <div class="mt-3">
                    <p class="font-semibold text-gray-700 mb-2">Link title:</p>
                    <div class="mt-2 text-red-500">{{ $errors->has('title') ? 'A title is required' : '' }}</div>
                    <input class="border rounded text-sm h-8 w-full" type="text" id="title" name="title"  value="{{ $page->title }}" placeholder="Enter a title for this link">
                </div>  

                <!-- URL --> 
                <div class="mt-3">
                    <p class="font-semibold text-gray-700 mb-2">Link URL:</p>
                    <div class="mt-2 text-red-500">{{ $errors->has('title') ? 'A URL is required' : '' }}</div>
                    <input class="border rounded text-sm h-8 w-full" type="url" id="title" name="url"  value="{{ $page->url }}" placeholder="Enter a URL for this link">
                </div>  
                
        <!-- Text area with TinyMCE for Body of post -->
        <div class="my-10">
            <p class="font-semibold text-gray-700 mb-2">Enter the link description:</p>
            <div class="mt-2 text-red-500">{{ $errors->has('text') ? 'At least some text is required for the link' : '' }}</div>
            <textarea class="w-full border rounded" name="text" id="editor" placeholder="This is the body of the link">{{ $page->description }}</textarea>    
        </div>

<!-- Place the first <script> tag in your HTML's <head> -->
    <script src="https://cdn.tiny.cloud/1/a1rn9rzvnlulpzdgoe14w7kqi1qpfsx7cx9am2kbgg226dqz/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        tinymce.init({
            selector: '#editor',  // Select the textarea by its ID
            plugins: 'advlist autolink lists link image charmap preview anchor image code fullscreen insertdatetime media table paste help wordcount',
            toolbar: 'undo redo | h1 h2 h3 | formatselect | bold italic backcolor | table | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat | image | help',
            menubar: 'file edit view insert format tools table help',
            branding: false,
            block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6;'
        });
    </script>

        <!-- Manage category selection -->
        <div class="border rounded border-gray-300 p-2 my-10">
            <p class="font-semibold text-gray-700 mb-2">Select a category for the link:</p>
            <div class="mt-2 text-red-500">{{ $errors->has('category') ? 'A category is required' : '' }}</div>
            <div class="">
                <ul class="flex justify-evenly mt-4 mb-10">
                    @foreach ($categories as $category)
                        <li>
                            <label>{{ $category->name }}</label>
                            <input type="radio" name="category" value="{{ $category->id }}"
                                @if ($page->category_id === $category->id) checked="checked" @endif>
                        </li>
                    @endforeach 
                </ul>
            </div>
        </div>  


        <!-- Manage the post options -->
        <div class="">
            <p class="font-semibold text-gray-700 mb-2">Link Options:</p>
            <ul class="flex border rounded border-gray-300 py-2 text-sm justify-evenly">           
                <li class="list-inline-item">
                    <label>Publish?</label>     
                    <input type="checkbox" name="published" @if ($page->published) checked @endif>
                </li>
            </ul>
        </div> 
        <button type="submit" class="my-10 border p-2 bg-lime-600 rounded text-white text-sm hover:bg-green-500">Update Link</button> 
    </form>

    </div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview');
            output.src = reader.result;
            output.style.display = 'block';  // Show the image preview
        };
        reader.readAsDataURL(event.target.files[0]); // Read the image as a data URL
    }
    </script>

@endsection