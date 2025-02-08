<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRolesPivot;
use App\Models\BlogPosts;
use App\Models\Support;
use App\Models\Links;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {

        // User info
        $users = User::all();

        // Links
        $links = Links::all();
        $linksunpublished = Links::where('published', false)->get();

        // Ridiculous workaround, count users, roles then add 1 as admin has two roles!!
        $users_count = User::all()->count();
        $roles_count = UserRolesPivot::all()->Count();
        $users_pending = UserRolesPivot::all()->where('role_id', '===', 2)->count();
        $users_banned = UserRolesPivot::all()->where('role_id', '===', 1)->count();
        $users_active = UserRolesPivot::all()->where('role_id', '===', 3)->count();
        $users_admin = UserRolesPivot::all()->where('role_id', '===', 4)->count();

        //Support info
        $tickets = Support::all();
        $awaiting_reply = Support::all()->where('status', '===', 'Awaiting Reply')->count();
        $open_tickets = Support::all()->where('status', '===', 'Open')->count();
        $in_progress_tickets = Support::all()->where('status', '===', 'In Progress')->count();

        //Blog info
        $blogposts = BlogPosts::all();
        $blogunpublished = BlogPosts::where('published', false)->get();

        $data = array(

            'users' => $users,
            'users_pending' => $users_pending,
            'users_active' => $users_active,
            'users_banned' => $users_banned,
            'users_admin' => $users_admin,
            'tickets' => $tickets,
            'awaiting_reply' => $awaiting_reply,
            'open_tickets' => $open_tickets,
            'in_progress_tickets' => $in_progress_tickets,
            'blogposts' => $blogposts,
            'blogunpublished' => $blogunpublished,
            'links' => $links,
            'linksunpublished' => $linksunpublished,
        );

        return view ('admin.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
