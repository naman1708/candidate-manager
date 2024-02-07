<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateRoles;
use App\Models\ScheduleInterview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */


    public function __invoke(Request $request)
    {
        // Total count of candidates and roles
        $total['candidate'] = Candidate::count();
        $total['candidatesRole'] = CandidateRoles::count();

        // Current date
        $currentDate = Carbon::now()->toDateString();

        $total['pendingInterviews'] = ScheduleInterview::with(['candidate.candidateRole'])
            ->where('interview_status', 'pending')
            ->when($request->search, function ($query) use ($request) {
                $searchTerm = '%' . $request->search . '%';
                $query->whereHas('candidate', function ($subQuery) use ($searchTerm) {
                    $subQuery->where('candidate_name', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm)
                        ->orWhere('contact', 'like', $searchTerm);
                })->orWhereHas('candidate.candidateRole', function ($subQuery) use ($searchTerm) {
                    $subQuery->where('candidate_role', 'like', $searchTerm);
                });
            })
            ->when(!empty($request->from_date) && !empty($request->to_date), function ($query) use ($request) {

                $from_date = Carbon::parse($request->from_date);
                $to_date = Carbon::parse($request->to_date);

                if ($from_date && $to_date) {
                    $query->whereBetween('interview_date', [$from_date->startOfDay()->toDateString(), $to_date->endOfDay()->toDateString()]);
                }
            })
            ->orderByRaw('interview_date >= CURDATE() desc, interview_date')
            ->paginate(10);




        // ====================Today Uploaded Candidate list=============

        $candidateRole = CandidateRoles::get();

        $query = Candidate::with('candidateRole', 'createby')
            ->whereDate('created_at', now()->format('Y-m-d'));


        // Apply filters
        if (!empty($request->iteam)) {
            $query->where(function ($query) use ($request) {
                $searchTerm = '%' . $request->iteam . '%';
                $query->where('candidate_name', 'LIKE', $searchTerm)
                    ->orWhere('email', 'LIKE', $searchTerm)
                    ->orWhere('contact', 'LIKE', $searchTerm)
                    ->orWhere('experience', 'LIKE', $searchTerm);
            });
        }

        if (!empty($request->role)) {
            $query->where('candidate_role_id', $request->role);
        }

        if (!empty($request->tag)) {
            $query->where('interview_status_tag', $request->tag);
        }

        if (!empty($request->manager)) {
            $query->where('user_id', $request->manager);
        }

        // Get managers
        $managers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['manager', 'admin']);
        })->get();

        if (Auth::user()->hasRole(['admin'])) {

            $uploadedCandidates = $query->paginate(10);
        } elseif (Auth::user()->hasRole(['manager'])) {

            $uploadedCandidates = $query->where('user_id', Auth::id())->paginate(10);
        } else {

            $uploadedCandidates = collect();
        }
        // Uploaded candidates
        $uploadedCandidates = $query->orderBy('id', 'desc')->paginate(10);

        return view('dashboard')->with(compact('total', 'uploadedCandidates', 'candidateRole', 'managers'));
    }
}
