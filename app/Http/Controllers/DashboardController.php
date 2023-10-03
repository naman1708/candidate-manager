<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $informations = Candidate::with('candidateRole')->get();
        $total['count'] = $informations->count();
        $total['candidateRole'] = $informations->pluck('candidateRole')->count(); 
        return view('dashboard')->with(compact('total'));
    }
}
