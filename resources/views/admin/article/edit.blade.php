@extends('layouts.admin')

@section('content')

<div>
    <h2 class="text-2xl text-center font-bold mb-10">Edit an Existing Article</h2>

    <div class="w-3/4 mx-auto">

        <form method="POST" action="/admin/article/{{ $page->id }}" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            
            <div class="flex space-x-10 mb-10">
                <div class="w-1/2">
                    <!-- Upload Featured Image -->
                    <div class="flex flex-col justify-center">
                        <label for="image" class="text-gray-700 mb-2">Upload New Featured Image (optional)</label>
                        <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)">
                    </div>

                    <!-- Image preview container -->
                    <div id="image-preview" class="my-10">
                        <img id="preview" class="w-full h-[300px]" />
                    </div>            
                </div>
                <div class="w-1/2">
                    <h2 class="font-bold text-lg mb-10">Existing Featured Image</h2>
                    @if($page->original_image)
                        <img src="{{ '/assets/images/uploads/' . 'small_' . $page->original_image }}" alt="Featured Image">
                    @else
                        <p class="text-gray-600">No featured image available</p>
                    @endif
                </div>
            </div>           

            <!-- Article Date --> 
            <div class="mt-10">
                <p class="font-semibold text-gray-700 mb-2">Enter Date of Article <span class="text-gray-400">(dd-mm-yyyy)</span>:</p>
                <input class="border rounded text-sm h-8 w-full" type="date" id="date" name="date" value="{{ old('date', $page->GetRawOriginal('date')) }}">
            </div> 

            <!-- Article Order -->
            <div class="mt-10">
                <label for="article_order">Order of Article:</label>
                <input type="number" name="article_order" min="0" max="255" class="rounded" value="{{ old('order', $page->order) }}" required>
            </div>

            <!-- Article Title --> 
            <div class="mt-10">
                <p class="font-semibold text-gray-700 mb-2">Enter Title:</p>
                <input class="border rounded text-sm h-8 w-full" type="text" id="title" name="title" value="{{ old('title', $page->title) }}" placeholder="Enter a title for this article">
            </div>  

            <!-- Text area for summary section -->
            <div class="my-10">
                <label class="font-semibold text-gray-700 mb-2">Enter a Summary:</label>
                <textarea class="border rounded text-sm w-full" id="summary" name="summary" placeholder="Enter a summary for this article">{{ old('summary', $page->summary) }}</textarea>
            </div> 

            <!-- Text area with TinyMCE for Body of post -->
            <div class="my-10">
                <p class="font-semibold text-gray-700 mb-2">Enter the Body of the Article:</p>
                <textarea class="w-full border rounded" name="text" id="editor" placeholder="This is the body of the article">{{ old('text', $page->text) }}</textarea>    
            </div>

            <!-- Upload Additional Images for Editor -->
            <div class="my-10">
                <p class="font-semibold text-gray-700 mb-2">Upload Additional Images for Editor:</p>
                <input type="file" name="images[]" id="editorImages" multiple class="form-control">
            </div>

            <!-- Display Uploaded Images -->
            <div id="uploaded-images-preview" class="my-10">
                <h4 class="text-lg font-bold">Uploaded Images</h4>
                @if($page->images)
                    @foreach(json_decode($page->images) as $image)
                        <div class="my-4">
                            <img src="{{ asset($image) }}" width="150" style="margin-right: 10px;">
                            <button onclick="copyToClipboard('{{ asset($image) }}')" class="bg-gray-300 px-2 py-1 rounded">Copy URL</button>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-600">No additional images have been uploaded yet.</p>
                @endif
            </div>

            <!-- Manage category selection -->
            <div class="border rounded border-gray-300 p-2 my-10">
                <p class="font-semibold text-gray-700 mb-2">Select a Category for the Article:</p>
                <ul class="flex justify-evenly mt-4 mb-10">
                    @foreach ($categories as $category)
                        <li>
                            <input type="radio" id="category" name="category" value="{{ $category->id }}"
                                @if ($page->articles_id === $category->id)
                                    checked="checked"
                                @endif>
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
                        <input type="checkbox" class="form-field rounded-full" id="published" name="published" @if($page->published) checked @endif>
                    </li>
                </ul>
            </div> 

            <button type="submit" class="my-10 border p-2 bg-lime-600 rounded text-white text-sm hover:bg-lime-500">Update Article</button> 
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

        // Function to preview uploaded featured image
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