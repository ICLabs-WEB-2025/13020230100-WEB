<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the coming soon page for the user profile.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('profile.coming-soon');
    }
}
