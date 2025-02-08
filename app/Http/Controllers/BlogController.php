<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPosts;
use App\Models\BlogCategories;
use App\Models\BlogTags;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;
use App\Services\ImageService;
use App\Http\Controllers\ContentImageController;

class BlogController extends Controller
{

    protected $imageService;

    // Constructor injection for ImageService
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     */
public function index()
{
   if (isset($_GET['search'])) {
       $searchTerm = $_GET['search'];
       $posts = BlogPosts::where(function ($query) use ($searchTerm) {
           $query->where('title', 'LIKE', '%' . $searchTerm . '%')
                 ->orWhere('body', 'LIKE', '%' . $searchTerm . '%');
       })->paginate(6);

       // Keep the search term in the pagination links
       $posts->appends(['search' => $searchTerm]);

   } elseif (isset($_GET['category'])) {
       $category = $_GET['category'];
       $posts = BlogPosts::GetCategories($category);  //Already Paginated in BlogPosts Model

       // Keep the category filter in the pagination links
       $posts->appends(['category' => $category]);

   } elseif (isset($_GET['tag'])) {
       $tag = $_GET['tag'];
       $posts = BlogPosts::GetTags($tag)->paginate(6);

       // Keep the tag filter in the pagination links
       $posts->appends(['tag' => $tag]);

   } else {
       $posts = BlogPosts::with('BlogCategories', 'BlogTags', 'Users')
            ->where('published', true)
            ->orderBy('date', 'desc')
            ->paginate(10);
   }

   $categories = BlogCategories::all();

   $popular_tags = DB::table('blog_post_tags')
       ->leftJoin('blog_tags', 'blog_tags.id', '=', 'blog_post_tags.tag_id')
       ->select('blog_post_tags.tag_id', 'name', DB::raw('count(*) as total'))
       ->groupBy('blog_post_tags.tag_id', 'name')
       ->orderBy('total', 'desc')
       ->limit(15)
       ->get();

   $unpublished = BlogPosts::where('published', false)->get();

   return view('blog.index', compact('posts', 'categories', 'popular_tags', 'unpublished'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('Admin');

        $categories = BlogCategories::all();

        return view('blog.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ImageService $imageService)
    {
        Gate::authorize('Admin');
    
        // Prepare a new database entry for the blog post
        $page = new BlogPosts;
    
        $page->date = $request->date;
        $page->title = $request->title;
        $page->summary = $request->summary;
        $page->slug = Str::slug($page->title, '-');
        $page->body = $request->body;
        $page->user_id = Auth::user()->id;
        $page->categories_id = $request->category;
    
        if ($request->hasFile('image')) {
            // This now returns the original image file name
            $originalImageName = $imageService->handleImageUpload($request->file('image'));
            
            // Store the original image file name in the blog post record
            $page->original_image = $originalImageName;
        }
        
        // Handle additional images for use in the editor (single version)
        $uploadedPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Use optimizeAndSaveImage for editor images to create a single optimized version
                $imagePath = $imageService->optimizeAndSaveImage($image);
                $uploadedPaths[] = $imagePath;
            }
        }
        
        // Store the additional image paths in the 'images' column as JSON
        $page->images = json_encode($uploadedPaths);
    
        // Check if the post is to be published
        $page->published = $request->has('published') ? 1 : 0;
    
        // Check whether the post is featured
        $page->featured = $request->has('featured') ? 1 : 0;
    
        // Save the post to the database
        $page->save();
    
        // Sync the tags to the post
        BlogTags::StoreTags($request->tags, $page->slug);
    
        return redirect()->action([BlogController::class, 'index'])->with('success', 'Post created successfully! Images are available for use.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $page = BlogPosts::with('BlogCategories', 'users', 'blogTags')->where('slug', $slug)->first();
        $recentPages = BlogPosts::orderBy('date', 'desc')->take(3)->get();

        return view('blog.show', compact('page', 'recentPages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('Admin');

        $page = BlogPosts::find($id);
        $categories = BlogCategories::all();
        $split_tags = BlogTags::TagsForEdit($id);

        return view('blog.edit', compact('page', 'categories', 'split_tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, ImageService $imageService)
    {
        Gate::authorize('Admin');
    
        // Retrieve the existing post from the database
        $page = BlogPosts::findOrFail($id);
    
        // Update the post fields
        $page->date          = $request->date;
        $page->title         = $request->title;
        $page->summary       = $request->summary;
        $page->slug          = Str::slug($page->title, '-');
        $page->body          = $request->body;
        $page->user_id       = Auth::user()->id;
        $page->categories_id = $request->category;
    
        // Define your uploads path (this should match your ImageService)
        $uploadPath = '/assets/images/uploads/';
    
        if ($request->hasFile('image')) {
            // Delete the old images (original and resized)
            // Retrieve the original image name from the DB
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
            // This now returns the new image’s file name
            $newImageName = $imageService->handleImageUpload($request->file('image'));
            
            // Save only the original image name in the DB.
            // If you later need to access the resized images, you can prepend the respective prefixes.
            $page->original_image = $newImageName;
            
        }
    
        // Handle additional images for the editor (single optimized version)
        $uploadedPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $imageService->optimizeAndSaveImage($image);
                $uploadedPaths[] = $imagePath;
            }
        }
        
        // Merge new images with existing ones
        $existingImages = json_decode($page->images) ?? [];
        $updatedImages = array_merge($existingImages, $uploadedPaths);
        
        // Store the updated image paths in the 'images' field
        $page->images = json_encode($updatedImages);
    
        // Check if the post is to be published
        $page->published = $request->has('published') ? 1 : 0;
    
        // Check whether the post is featured
        $page->featured  = $request->has('featured') ? 1 : 0;
    
        // Save the updated post to the database
        $page->save();
    
        // Sync the tags
        BlogTags::StoreTags($request->tags, $page->slug);
    
        return back()->with('success', 'Post updated successfully with new images!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, ImageService $imageService)
    {
        Gate::authorize('Admin');
    
        // Retrieve the post by ID
        $page = BlogPosts::findOrFail($id);
    
        // Define the uploads folder path (should match what you use in your ImageService)
        $uploadPath = '/assets/images/uploads/';
    
        // Construct the full file paths for the original and resized images
        $originalImagePath = $uploadPath . $page->original_image;
        $smallImagePath    = $uploadPath . 'small_' . $page->original_image;
        $mediumImagePath   = $uploadPath . 'medium_' . $page->original_image;
        $largeImagePath    = $uploadPath . 'large_' . $page->original_image;
    
        // Delete the main images
        $imageService->deleteImage([
            $originalImagePath,
            $smallImagePath,
            $mediumImagePath,
            $largeImagePath
        ]);
    
        // Delete all additional images stored in the 'images' JSON field, if they exist
        $additionalImages = json_decode($page->images);
        if ($additionalImages) {
            foreach ($additionalImages as $imagePath) {
                // Assuming additional images are stored with their full paths or relative to the public folder,
                // simply call deleteImage on each.
                $imageService->deleteImage([$imagePath]);
            }
        }
    
        // Delete the post from the database
        $page->delete();
    
        return back()->with('success', 'Page deleted successfully!');
    }

}
