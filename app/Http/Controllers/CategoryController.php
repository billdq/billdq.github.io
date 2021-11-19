<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SystemConfig;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cats = SystemConfig::where('type', 'CAT')->get();
        return view('cats', ['cats' => $cats]);
    }

    public function createCat()
    {
        return view('cat');
    }

    public function store(Request $request)
    {
        $config = new SystemConfig();
        $config->{'type'} = 'CAT';
        $config->{'key'} = $request->input('key');
        $config->{'value'} = $request->input('value');
        $config->save();

        return redirect()->route('cats', ['key' => $config->{"key"}]);
    }

    public function show($key)
    {
        $cat = SystemConfig::where('type', 'CAT')
                ->where('key', $key)->first();
        return view('cat', ['cat' => $cat]);
    }

    public function update(Request $request, $key)
    {
        $cat = SystemConfig::where('type', 'CAT')
                ->where('key', $key)->first();

        if ($cat) {
            $cat->{'value'} = $request->input('value');
            $cat->save();
        }

        return redirect()->route('cats', ['key' => $key]);
    }

    public function destroy($key)
    {
        $cat = SystemConfig::where('type', 'CAT')
                ->where('key', $key)->first();

        if ($cat) {
            $cat->delete();
        }

        return "success";
    }
}
