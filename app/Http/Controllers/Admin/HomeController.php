<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;

class HomeController
{
    public function index()
    {
        $pageConfigs = ['pageHeader' => false];

        $user = Auth::user();

        return view('admin.home', ['pageConfigs' => $pageConfigs], compact('user'));
    }
}
