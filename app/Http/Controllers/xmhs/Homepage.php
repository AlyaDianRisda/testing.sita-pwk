<?php
namespace App\Http\Controllers\xmhs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Homepage extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::VIEW
    public function x2homeView(): View
    {
        return view('xmhs.Home');
    }

}