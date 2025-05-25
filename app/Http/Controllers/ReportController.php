<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display the coming soon page for reports.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('reports.coming-soon');
    }
}
