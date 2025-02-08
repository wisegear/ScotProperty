<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArticleCategories;
use Illuminate\Support\Str;

class AdminArticleCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {

        //Get all the article categories
        $categories = ArticleCategories::all();

        return view('admin.articles.index', compact('categories'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Ignore this.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->input('form_type') === 'create') {
            $validated = $request->validate([
                'create_name' => 'required|string|max:255',
                'article_order' => 'required|integer|between:0,255',
            ]);
    
            $new_articles = new ArticleCategories;
            $new_articles->name = $validated['create_name'];
            $new_articles->slug = Str::slug($validated['create_name'], '-');
            $new_articles->order = $validated['article_order'];
            $new_articles->navigation = $request->has('create_navigation') ? 1 : 0;
            $new_articles->save();
    
            return back()->with('created', 'Articles category Created');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->input('form_type') === 'update') {
            $validated = $request->validate([
                'update_name' => 'required|string|max:255',
                'article_order' => 'required|integer|between:0,255',
                'update_navigation' => 'required|boolean',
            ]);
    
            $edit_articles = ArticleCategories::findOrFail($id);
            $edit_articles->name = $validated['update_name'];
            $edit_articles->slug = Str::slug($validated['update_name'], '-');
            $edit_articles->order = $validated['article_order'];
            $edit_articles->navigation = $validated['update_navigation'];
            $edit_articles->save();
    
            return back()->with('updated', 'Articles category updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = ArticleCategories::findOrFail($id);
        $category->delete();

        return back()->with('deleted', 'Article category deleted');
    }
}
