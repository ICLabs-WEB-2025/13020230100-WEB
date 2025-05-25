<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilController extends Controller
{
    /**
     * Display the coming soon page for the user profile.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        return view('profile.coming-soon');
    }
}
