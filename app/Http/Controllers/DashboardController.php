<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateRoles;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $total['candidate'] = Candidate::count();
        $total['candidatesRole'] = CandidateRoles::count();
        return view('dashboard')->with(compact('total'));
    }
}
