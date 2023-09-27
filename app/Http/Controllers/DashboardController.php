<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Models\Investment;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $informations = Information::all();
        $total['count'] = $informations->count();
        return view('dashboard')->with(compact('total'));
    }
}
