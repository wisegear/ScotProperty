<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LinksCategories;
use App\Models\Links;

class AdminLinksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $categories = LinksCategories::with('links')->get();
        $links = Links::orderBy('id', 'desc')->paginate(12);
        
        return view('admin.links.index', compact('categories', 'links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not required, form displayed on Admin links home page.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = new LinksCategories();             

        $category->name = $request->new_category_name;

        $category->save();

        return back();
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
        $category = LinksCategories::find($id);

        $category->name = $request->category_name;

        $category->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = LinksCategories::find($id);

        LinksCategories::destroy($id);

        return back();
    }
}
