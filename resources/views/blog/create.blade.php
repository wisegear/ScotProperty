@extends('layouts.app')

@section('content')

    <h2 class="text-2xl text-center font-bold mb-10">Create a New Post</h2>

    <div class="w-3/4 mx-auto">

        <form method="POST" action="/blog" enctype="multipart/form-data">
        @csrf
            
            <!-- Upload Featured Image -->
            <div class="flex flex-col justify-center">
                <label for="image">Upload Image</label>
                <input type="file" name="image" id="image" accept="image/*" required onchange="previewImage(event)">
            </div>

            <!-- Image preview container -->
            <div id="image-preview" class="my-10">
                <img id="preview" class="w-full" />
            </div>

            <div class="mx-auto" id="output"></div>            

                <!-- Post Date --> 
                <div class="mt-10">
                    <p class="font-semibold text-gray-700 mb-2">Enter Date of Post <span class="text-gray-400">(dd-mm-yyyy)</span>:</p>
                    <div class="mt-2 text-red-500">{{ $errors->has('title') ? 'A title is required' : '' }}</div>
                    <input class="border rounded text-sm h-8 w-full" type="date" id="date" name="date"  value="{{ old('date') }}">
                </div> 

                
                <!-- Post Title --> 
                <div class="mt-3">
                    <p class="font-semibold text-gray-700 mb-2">Enter title:</p>
                    <div class="mt-2 text-red-500">{{ $errors->has('title') ? 'A title is required' : '' }}</div>
                    <input class="border rounded text-sm h-8 w-full" type="text" id="title" name="title"  value="{{ old('title') }}" placeholder="Enter a title for this post">
                </div>  
                
                <!-- Text area with TinyMCE for Excerpt of post -->
                <div class="form-group my-10">
                    <p class="font-semibold text-gray-700 mb-2">Enter a Summary:</p>
                    <div class="mt-2 text-red-500">{{ $errors->has('summary') ? 'A excerpt is required' : '' }}</div>
                    <textarea class="border rounded text-sm w-full" id="excerpt" name="summary"  value="{{ old('summary') }}" placeholder="Enter a summary for this post"></textarea>
                </div> 

        <!-- Text area with TinyMCE for Body of post -->
        <div class="my-10">
            <p class="font-semibold text-gray-700 mb-2">Enter the body of the post:</p>
            <div class="mt-2 text-red-500">{{ $errors->has('body') ? 'At least some text is required for the body' : '' }}</div>
            <textarea class="w-full border rounded" name="body" id="editor" placeholder="This is the body of the post">{{ old('body') }}</textarea>    
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

            <!-- New Image Upload Field -->
            <div class="">
                <label for="images">Upload Images for This Post</label>
                <input type="file" name="images[]" id="images" multiple class="form-control">
            </div>

            <!-- Display Uploaded Images After Creation -->
            @if(isset($post) && $post->images)
                <div id="uploaded-images-preview">
                    <h4>Uploaded Images</h4>
                    @foreach(json_decode($post->images) as $image)
                        <div>
                            <img src="/{{ $image }}" class="w-full">
                            <button onclick="copyToClipboard('{{ asset($image) }}')">Click to Copy</button>
                        </div>
                    @endforeach
                </div>
            @endif

            <script>
                // Function to copy the image URL to the clipboard
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


                        <!-- Manage category selection -->
                        <div class="border rounded border-gray-300 p-2 my-10">
                            <p class="font-semibold text-gray-700 mb-2">Select a category for the post:</p>
                            <div class="mt-2 text-red-500">{{ $errors->has('category') ? 'A category is required' : '' }}</div>
                            <div class="">
                                <ul class="flex justify-evenly mt-4 mb-10">
                                @foreach ($categories as $category)
                                <li class="">
                                <input type="radio" id="category" name="category" value="{{ $category->id }}"> 
                                {{ $category->name }}            
                                </li class="list-inline-item">
                                @endforeach
                                </ul>
                            </div>
                        </div>  

                        <!-- Post Tags -->
                        <div class="my-10">
                            <p class="font-semibold text-gray-700 mb-2">Enter some tags if required:</p>
                            <input type="text" class="w-full border rounded text-sm h-8" id="tags" name="tags" placeholder="Enter tags for the post, eg. one-two-three">
                        </div>

                        <!-- Manage the post options -->
                        <div class="">
                            <p class="font-semibold text-gray-700 mb-2">Post Options:</p>
                            <ul class="flex border rounded border-gray-300 py-2 text-sm justify-evenly">           
                                <li class="list-inline-item">
                                    <label> Publish?</label>     
                                    <input type="checkbox" class="form-field rounded-full" id="published" name="published">
                                </li>
                                <li class="list-inline-item">
                                    <label> Featured?</label>        
                                    <input type="checkbox" class="form-field rounded-full" id="featured" name="featured">
                                </li>
                            </ul>
                        </div> 
                        <button type="submit" class="my-10 border p-2 bg-lime-600 rounded text-white text-sm hover:bg-green-500">Create New Post</button> 
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