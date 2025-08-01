<?php
namespace App\Http\Controllers\xdos;

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
    public function x1homeView(): View
    {
        return view('xdos.Home');
    }

}