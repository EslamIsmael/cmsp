<?php

namespace App\Http\Controllers\Prospect;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $prospect = Auth::user()->prospect;

        return view('prospect.home')->with(['prospect' => $prospect]);
    }
}
