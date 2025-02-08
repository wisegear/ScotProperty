<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Links;
use App\Models\LinksCategories;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Services\ImageService;

class LinksController extends Controller
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
       // If the visitor uses the search box.
       $categoryName = $_GET['category'] ?? null;
    
       if ($categoryName) {
           $links = Links::whereHas('link_category', function ($query) use ($categoryName) {
               $query->where('name', $categoryName);
           })->with('link_category')->orderBy('id', 'desc')->paginate(6);
           
           // Append category query parameter to pagination links
           $links->appends(['category' => $categoryName]);
       } else {
           $links = Links::paginate(6);
       }
    
       $categories = LinksCategories::all();
       $unpublished = Links::where('published', false)->get();
    
       return view('links.index', compact('links', 'categories', 'unpublished'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories = LinksCategories::all();
        
        return view('links.create', compact('categories'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ImageService $imageService)
    {
        Gate::authorize('Admin');
    
        // Prepare a new database entry for the blog post
        $page = new Links;
    
        $page->title = $request->title;
        $page->url = $request->url;
        $page->slug = Str::slug($page->title, '-');
        $page->description = $request->text;
        $page->category_id = $request->category;
    
        if ($request->hasFile('image')) {
            // Use handleImageUpload for the link image
            $imageName = $imageService->handleLinkImageUpload($request->file('image'));
            $page->image = $imageName;
        }
           
        // Check if the post is to be published
        $page->published = $request->has('published') ? 1 : 0;
    
        // Save the post to the database
        $page->save();
    
        return redirect()->action([LinksController::class, 'index'])->with('success', 'Link created successfully! Images are available for use.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $page = Links::findOrFail($id);
        $categories = LinksCategories::all();

        return view('links.edit', compact('page', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, ImageService $imageService)
    {
        Gate::authorize('Admin');
    
        // Find the existing link by ID
        $page = Links::findOrFail($id);
    
        // Update the link fields
        $page->title        = $request->title;
        $page->url          = $request->url;
        $page->slug         = Str::slug($request->title, '-');
        $page->description  = $request->text;
        $page->category_id  = $request->category;
        $page->published    = $request->has('published') ? 1 : 0;
    
        // Define the uploads folder path (must match what is used in handleLinkImageUpload)
        $uploadPath = '/assets/images/uploads/';
    
        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
            // If an old image exists, delete it
            if ($page->image) {
                // Construct the full path to the old image
                $oldImagePath = $uploadPath . $page->image;
                $imageService->deleteImage($oldImagePath);
            }
    
            // Upload the new image using the image service
            // This returns only the image file name (without any path)
            $newImageName = $imageService->handleLinkImageUpload($request->file('image'));
    
            // Store only the file name in the database
            $page->image = $newImageName;
        }
    
        // Save the updated link
        $page->save();
    
        return redirect()->action([LinksController::class, 'index'])
                         ->with('success', 'Link updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, ImageService $imageService)
    {
        Gate::authorize('Admin');
    
        // Find the link by ID
        $page = Links::findOrFail($id);
    
        // Define the uploads folder path (must match what is used in handleLinkImageUpload)
        $uploadPath = '/assets/images/uploads/';
    
        // Delete the associated image if it exists
        if ($page->image) {
            // Construct the full path to the image using the stored file name
            $imagePath = $uploadPath . $page->image;
            $imageService->deleteImage($imagePath);
        }
    
        // Delete the link record from the database
        $page->delete();
    
        return back()->with('success', 'Link deleted successfully!');
    }
}
