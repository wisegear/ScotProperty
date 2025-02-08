@extends('layouts.admin')

@section('content')

<div>
    <h2 class="text-2xl text-center font-bold mb-10">Create a New Article</h2>

    <div class="w-3/4 mx-auto">

        <form method="POST" action="/admin/article" enctype="multipart/form-data">
            @csrf
            
            <!-- Upload Featured Image -->
            <div class="flex justify-center">
                <label for="image">Upload Featured Image</label>
                <input type="file" name="image" id="image" accept="image/*" required onchange="previewImage(event)">
            </div>

            <!-- Image preview container -->
            <div id="image-preview" class="my-10">
                <img id="preview" class="w-full h-[400px]" />
            </div>

            <!-- Article Date -->
            <div class="mt-10">
                <p class="font-semibold text-gray-700 mb-2">Enter Date of Article <span class="text-gray-400">(dd-mm-yyyy)</span>:</p>
                <div class="mt-2 text-red-500">{{ $errors->has('date') ? 'A date is required' : '' }}</div>
                <input class="border rounded text-sm h-8 w-full" type="date" id="date" name="date" value="{{ old('date') }}">
            </div> 

            <!-- Article Order -->
            <div class="mt-10">
                <label for="article_order">Order of Article:</label>
                <div class="mt-2 text-red-500">{{ $errors->has('article_order') ? 'An Order value is required' : '' }}</div>
                <input type="number" name="article_order" min="0" max="255" class="rounded" value="{{ old('order') }}" required>
            </div>

            <!-- Post Title --> 
            <div class="mt-10">
                <p class="font-semibold text-gray-700 mb-2">Enter title:</p>
                <div class="mt-2 text-red-500">{{ $errors->has('title') ? 'A title is required' : '' }}</div>
                <input class="border rounded text-sm h-8 w-full" type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Enter a title for this article">
            </div>  
            
            <!-- Text area with TinyMCE for Excerpt of post -->
            <div class="form-group my-10">
                <p class="font-semibold text-gray-700 mb-2">Enter a summary:</p>
                <textarea class="border rounded text-sm w-full" id="summary" name="summary" placeholder="Enter a summary for this article">{{ old('summary') }}</textarea>
            </div> 

            <!-- Text area with TinyMCE for Body of post -->
            <div class="my-10">
                <p class="font-semibold text-gray-700 mb-2">Enter the body of the post:</p>
                <textarea class="w-full border rounded" name="text" id="editor" placeholder="This is the body of the post">{{ old('text') }}</textarea>    
            </div>

            <!-- Upload Additional Images for Editor -->
            <div class="my-10">
                <p class="font-semibold text-gray-700 mb-2">Upload Additional Images for Editor:</p>
                <input type="file" name="images[]" id="editorImages" multiple class="form-control">
            </div>

            <!-- Display Uploaded Images -->
            <div id="uploaded-images-preview" class="my-10">
                <h4 class="text-lg font-bold">Uploaded Images</h4>
                <p class="text-gray-600">Images will be displayed here after uploading.</p>
            </div>

            <!-- Manage category selection -->
            <div class="border rounded border-gray-300 p-2 my-10">
                <p class="font-semibold text-gray-700 mb-2">Select a category for the article:</p>
                <div class="mt-2 text-red-500">{{ $errors->has('category') ? 'A category is required' : '' }}</div>
                <ul class="flex justify-evenly mt-4 mb-10">
                    @foreach ($categories as $category)
                    <li>
                        <input type="radio" id="category" name="category" value="{{ $category->id }}"> 
                        {{ $category->name }}            
                    </li>
                    @endforeach
                </ul>
            </div>  

            <!-- Manage the post options -->
            <div>
                <p class="font-semibold text-gray-700 mb-2">Article Options:</p>
                <ul class="flex border rounded border-gray-300 py-2 text-sm justify-evenly">           
                    <li>
                        <label> Publish?</label>     
                        <input type="checkbox" class="form-field rounded-full" id="published" name="published">
                    </li>
                </ul>
            </div> 

            <button type="submit" class="my-10 border p-2 bg-lime-600 rounded text-white text-sm hover:bg-lime-500">Create New Article</button> 
        </form>
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

    <script>

        // Function to preview uploaded featured image
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
                output.style.display = 'block';  // Show the image preview
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection