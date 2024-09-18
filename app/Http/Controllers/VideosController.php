<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class VideosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Videos');
    }
}
