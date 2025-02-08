@extends('layouts.app')

@section('content')

<div>
    <h2 class="text-2xl text-center font-bold mb-10">Edit Post</h2>

    <div class="w-3/4 mx-auto">

        <form method="POST" action="/blog/{{ $page->id }}" enctype="multipart/form-data">
        @csrf
        {{ method_field('PUT') }}
            
        <div class="flex space-x-10 mb-10">
            <div class="w-1/2">
                <!-- Upload Featured Image -->
                <div class="flex flex-col mb-4">
                    <label for="image" class="text-gray-700 mb-2 font-bold">Upload New Featured Image (optional)</label>
                    <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)">
                </div>

                <!-- Image preview container -->
                <div id="image-preview" class="">
                    <img id="preview" class="w-full" />
                </div>            
            </div>
            <div class="w-1/2">
                <h2 class="font-bold text-lg mb-12">Existing Featured Image</h2>
                @if($page->original_image)
                    <img src="{{ '/assets/images/uploads/' . 'small_' . $page->original_image }}" class="w-full h-[350px]" alt="Featured Image">
                @else
                    <p class="text-gray-600">No featured image available</p>
                @endif
            </div>
        </div>

        <!-- Date Field -->
        <div class="mt-10">
            <label class="font-semibold text-gray-700 mb-2">Date of Post <span class="text-gray-400">(dd-mm-yyyy)</span>:</label>
            <input class="border rounded text-sm h-8 w-full" type="date" name="date" value="{{ old('date', $page->GetRawOriginal('date')) }}">
        </div> 

        <!-- Post Title --> 
        <div class="mt-3">
            <label class="font-semibold text-gray-700 mb-2">Enter title:</label>
            <input class="border rounded text-sm h-8 w-full" type="text" name="title" value="{{ old('title', $page->title) }}" placeholder="Enter a title for this post">
        </div>  

        <!-- Text area with TinyMCE for Excerpt of Post -->
        <div class="form-group my-10">
            <label class="font-semibold text-gray-700 mb-2">Enter a Summary:</label>
            <textarea class="border rounded text-sm w-full" name="summary">{{ old('summary', $page->summary) }}</textarea>
        </div> 

        <!-- Text area with TinyMCE for Body of Post -->
        <div class="my-10">
            <label class="font-semibold text-gray-700 mb-2">Enter the body of the post:</label>
            <textarea class="w-full border rounded" name="body" id="editor">{{ old('body', $page->body) }}</textarea>    
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

        <!-- Upload Images for Editor -->
        <div class="my-10">
            <label class="font-semibold text-gray-700 mb-2">Upload Additional Images for Editor:</label>
            <input type="file" name="images[]" id="editorImages" multiple class="form-control">
        </div>

        <!-- Display Uploaded Images -->
        <div class="my-10" id="uploaded-images-preview">
            <h4 class="font-bold mb-2">Uploaded Images for use in the editor</h4>
            @if($page->images)
                <div class="grid grid-cols-4 gap-10">
                    @foreach(json_decode($page->images) as $image)
                        <div class="border space-y-4 p-2">
                            <img src="{{ asset($image) }}" class="h-[100px] w-full">
                            <button onclick="copyToClipboard('{{ asset($image) }}')" class="py-1 px-2 border rounded"><i class="fa-regular fa-clipboard"></i></button>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No additional images have been uploaded yet.</p>
            @endif
        </div>

        <!-- Category Selection -->
        <div class="border rounded border-gray-300 p-2 my-10">
            <label class="font-semibold text-gray-700 mb-2">Select a category for the post:</label>
            <ul class="flex justify-evenly mt-4 mb-10">
                @foreach ($categories as $category)
                    <li>
                        <label>{{ $category->name }}</label>
                        <input type="radio" name="category" value="{{ $category->id }}"
                            @if ($page->categories_id === $category->id) checked="checked" @endif>
                    </li>
                @endforeach 
            </ul>
        </div>  

        <!-- Post Tags -->
        <div class="my-10">
            <label class="font-semibold text-gray-700 mb-2">Enter tags:</label>
            <input type="text" class="w-full border rounded text-sm h-8" name="tags" value="{{ $split_tags }}" placeholder="Enter tags for the post, e.g., one-two-three">
        </div>

        <!-- Post Options -->
        <div>
            <label class="font-semibold text-gray-700 mb-2">Post Options:</label>
            <ul class="flex border rounded border-gray-300 py-2 text-sm justify-evenly">           
                <li>
                    <label>Publish?</label>     
                    <input type="checkbox" name="published" @if ($page->published) checked @endif>
                </li>
                <li>
                    <label>Featured?</label>        
                    <input type="checkbox" name="featured" @if ($page->featured) checked @endif>
                </li>
            </ul>
        </div> 

        <button type="submit" class="my-10 border p-2 bg-lime-600 rounded text-white text-sm hover:bg-green-500">Update Post</button> 
        </form>
    </div>

    <script>
        // Function to preview uploaded image
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
                output.style.display = 'block'; // Show the image preview
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Function to copy image URL to clipboard
        function copyToClipboard(text) {
            const tempInput = document.createElement('input');
            document.body.appendChild(tempInput);
            tempInput.value = text;
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            alert('Image URL copied to clipboard!');
        }
    </script>

@endsection