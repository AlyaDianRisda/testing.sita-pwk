<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
  

    // PAGE::USER_HOME
    public function userHome(): View 
    {
        return view('xmhs.Home');
    } 
  

    // PAGE::ADMIN_HOME
    public function adminHome(): View
    {
        return view('xadm.Home');
    }
  

    // PAGE::DOSEN_HOME
    public function dosenHome(): View
    {
        return view('xdos.Home');
    }
}