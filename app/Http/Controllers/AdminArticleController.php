<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleCategories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;
use App\Services\ImageService;

class AdminArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Article::all();

        return view('admin.article.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ArticleCategories::all();
        
        return view('admin.article.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ImageService $imageService)
    {
  
        // Prepare a new database entry.
        $page = new Article;
    
        $page->date = $request->date;
        $page->order = $request->article_order;
        $page->title = $request->title;
        $page->summary = $request->summary;
        $page->slug = Str::slug($page->title, '-');
        $page->text = $request->text;
        $page->user_id = Auth::user()->id;
        $page->articles_id = $request->category;
    
        if ($request->hasFile('image')) {
            // This now returns the original image file name
            $originalImageName = $imageService->handleImageUpload($request->file('image'));
            
            // Store the original image file name in the blog post record
            $page->original_image = $originalImageName;
        }
    
        // Handle additional images for the editor
        $uploadedPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $imageService->optimizeAndSaveImage($image);
                $uploadedPaths[] = $imagePath;
            }
        }
    
        // Store the image paths in the `images` JSON column
        $page->images = json_encode($uploadedPaths);
    
        // Check if the article is to be published
        $page->published = $request->has('published') ? 1 : 0;
    
        // Save the new article to the database
        $page->save();
    
        return redirect()->action([AdminArticleController::class, 'index'])->with('success', 'Article created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //  Not used
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $categories = ArticleCategories::all();
        $page = Article::findorFail($id);

        return view('admin.article.edit', compact('page', 'categories'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, ImageService $imageService)
    {
        // Retrieve the existing article from the database
        $page = Article::findOrFail($id);
    
        // Update the article fields
        $page->date         = $request->date;
        $page->order        = $request->article_order;
        $page->title        = $request->title;
        $page->summary      = $request->summary;
        $page->slug         = Str::slug($page->title, '-');
        $page->text         = $request->text;
        $page->user_id      = Auth::user()->id;
        $page->articles_id  = $request->category;
    
        // Define the uploads folder path (must match your image service configuration)
        $uploadPath = '/assets/images/uploads/';
    
        // Handle featured image upload if a new image is uploaded
        if ($request->hasFile('image')) {
            // Delete the old images (original and resized) if they exist
            $oldImageName = $page->original_image;
            if ($oldImageName) {
                // Compute the full paths for each image version
                $originalPath = $uploadPath . $oldImageName;
                $smallPath    = $uploadPath . 'small_' . $oldImageName;
                $mediumPath   = $uploadPath . 'medium_' . $oldImageName;
                $largePath    = $uploadPath . 'large_' . $oldImageName;
                
                // Pass all these paths to your deleteImage method
                $imageService->deleteImage([$originalPath, $smallPath, $mediumPath, $largePath]);
            }
    
            // Upload the new image using your image service
            // This returns the new image’s file name (without any path)
            $newImageName = $imageService->handleImageUpload($request->file('image'));
            
            // Save only the original image name in the DB.
            // If needed, you can always prepend the respective prefixes for resized images later.
            $page->original_image = $newImageName;
        }
    
        // Handle additional images for the editor
        $uploadedPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $imageService->optimizeAndSaveImage($image);
                $uploadedPaths[] = $imagePath;
            }
        }
    
        // Merge new images with existing ones (if any)
        $existingImages = json_decode($page->images) ?? [];
        $updatedImages = array_merge($existingImages, $uploadedPaths);
        
        // Store the updated image paths in the 'images' field
        $page->images = json_encode($updatedImages);
    
        // Check if the article is to be published
        $page->published = $request->has('published') ? 1 : 0;
    
        // Save the updated article to the database
        $page->save();
    
        return back()->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, ImageService $imageService)
    {
        // Retrieve the article by ID
        $page = Article::findOrFail($id);
    
        // Define the uploads folder path (must match your image service configuration)
        $uploadPath = '/assets/images/uploads/';
    
        // Delete the main featured image sizes (if an original image exists)
        if ($page->original_image) {
            // Construct the full paths for the original and resized images
            $originalPath = $uploadPath . $page->original_image;
            $smallPath    = $uploadPath . 'small_' . $page->original_image;
            $mediumPath   = $uploadPath . 'medium_' . $page->original_image;
            $largePath    = $uploadPath . 'large_' . $page->original_image;
            
            // Pass all these paths to the deleteImage method
            $imageService->deleteImage([$originalPath, $smallPath, $mediumPath, $largePath]);
        }
    
        // Delete additional images stored in the 'images' JSON field (if any)
        $additionalImages = json_decode($page->images);
        if ($additionalImages) {
            foreach ($additionalImages as $imageFileName) {
                // Construct the full path for each additional image
                $fullImagePath = $uploadPath . $imageFileName;
                $imageService->deleteImage([$fullImagePath]);
            }
        }
    
        // Delete the article record from the database
        $page->delete();
    
        return back()->with('success', 'Article deleted successfully!');
    }
}
