<?php
namespace App\Http\Controllers\xadm;

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
    public function x0homeView(): View
    {
        return view('xadm.Home');
    }

}