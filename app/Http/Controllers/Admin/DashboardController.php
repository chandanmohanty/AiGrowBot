<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'posts'      => Post::count(),
                'published'  => Post::where('status', 'published')->count(),
                'users'      => User::count(),
                'messages'   => ContactMessage::count(),
            ],
            'latest_posts'    => Post::latest()->limit(5)->get(),
            'latest_messages' => ContactMessage::latest()->limit(5)->get(),
        ]);
    }
}
