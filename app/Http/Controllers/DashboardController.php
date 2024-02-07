<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateRoles;
use App\Models\ScheduleInterview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $total['candidate'] = Candidate::count();
        $total['candidatesRole'] = CandidateRoles::count();

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


        // Today Uploaded Candidates List

        $managers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['manager', 'admin']);
        })->get();
        $candidateRole = CandidateRoles::get();

        $currentUploadedCandidates = Candidate::with('candidateRole', 'createby')
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->orderBy('id', 'desc')
            ->paginate(10);



        return view('dashboard')->with(compact('total', 'currentUploadedCandidates', 'managers', 'candidateRole'));
    }
}
