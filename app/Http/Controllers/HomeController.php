<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SystemConfig;
use App\Order;
use App\OrderCategory;
use App\QrCode;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $cats = SystemConfig::where('type', 'CAT')->get();
        return view('home', ['cats' => $cats]);
    }

}
