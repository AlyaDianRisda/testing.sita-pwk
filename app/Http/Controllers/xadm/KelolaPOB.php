<?php
namespace App\Http\Controllers\xadm;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class KelolaPOB extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::VIEW
    public function x0pobView(): View
    {
        return view('xadm.POB');
    }
}
