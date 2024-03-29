<?php

namespace App\Http\Controllers;

use App\Exports\CandidatesExport;
use App\Imports\CandidatesImport;
use App\Models\Candidate;
use App\Models\CandidateRoles;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;


class CandidateController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:candidate-list|candidate-view|candidate-create|candidate-edit|candidate-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:candidate-view', ['only' => ['show']]);
        $this->middleware('permission:candidate-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:candidate-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:candidate-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        $candidateRole = CandidateRoles::get();
        $query = Candidate::with('candidateRole', 'createby');
        $search = $request->input('search');
        $role = $request->input('candidate_role');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        // Apply date range filter
        if (!empty($from_date) && !empty($to_date)) {
            $from_date = $this->validateAndParseDate($from_date);
            $to_date = $this->validateAndParseDate($to_date);

            if ($from_date && $to_date) {
                $query->whereBetween('date', [$from_date->startOfDay(), $to_date->endOfDay()]);
            }
        }

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('candidate_name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhere('contact', 'LIKE', "%$search%")
                    ->orWhere('experience', 'LIKE', "%$search");
            });
        }

        if (!empty($role)) {
            $query->where('candidate_role_id', $role);
        }

        if (!empty($request->tag)) {
            $query->where('interview_status_tag', $request->tag);
        }

        if (!empty($request->manager)) {
            $query->where('user_id', $request->manager);
        }


        $managers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['manager', 'admin']);
        })->get();


        // $managers = User::whereHas('roles', function ($query) {
        //     $query->where('name', 'manager');
        // })->whereDoesntHave('roles', function ($query) {
        //     $query->where('name', 'admin');
        // })->get();

        $candidates = $query->orderBy('id', 'desc')->paginate(10);

        return view('candidates.all', compact('candidates', 'candidateRole', 'role', 'managers'));
    }

    private function validateAndParseDate($date)
    {
        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $candidateRole = CandidateRoles::where('status', 'active')->pluck('candidate_role', 'id')->toArray();
        return view('candidates.add')->with(compact('candidateRole'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'candidate_name' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'unique:candidates,contact'],
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            if ($request->hasFile('upload_resume')) {
                $uploadedFile = $request->file('upload_resume');
                $extension = $uploadedFile->getClientOriginalExtension();
                $randomFileName = time() . '_' . uniqid() . '.' . $extension;
                $resumePath = $uploadedFile->storeAs('resumes', $randomFileName, 'local');
                $data['upload_resume'] = $resumePath;
            }
            $data['user_id'] = Auth::id();
            Candidate::create($data);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('status', $e->getMessage());
        }

        DB::commit();
        return redirect()->back()->with('status', 'Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate)
    {
        // $candidate->load('scheduleInterview');
        $candidate->with('scheduleInterview');
        return view('candidates.view')->with(compact('candidate'));
    }


    /**
     * Edit the specified resource.
     */
    public function edit($candidates)
    {
        $candidates = Candidate::find($candidates);
        $candidateRole = CandidateRoles::where('status', 'active')->pluck('candidate_role', 'id')->toArray();
        return view('candidates.edit')->with(compact('candidates', 'candidateRole'));
    }


    /**
     * Update the specified resource.
     */
    public function update(Request $request)
    {

        $request->validate([
            'candidate_name' => ['required', 'string', 'max:255'],
            'contact' => [
                'required', Rule::unique('candidates', 'contact')->ignore($request->id),
            ],
        ]);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $candidates = Candidate::find($request->id);

            if ($request->hasFile('upload_resume')) {
                $uploadedFile = $request->file('upload_resume');
                $extension = $uploadedFile->getClientOriginalExtension();
                $randomFileName = time() . '_' . uniqid() . '.' . $extension;
                $resumePath = $uploadedFile->storeAs('resumes', $randomFileName, 'local');

                if (!$uploadedFile->isValid()) {
                    return redirect()->back()->with('status', 'There was an error uploading the resume file.');
                }
                // Delete the old resume file if it exists
                if (!empty($candidates->upload_resume)) {
                    Storage::disk('local')->delete($candidates->upload_resume);
                }
                $data['upload_resume'] = $resumePath;
            } elseif (empty($data['upload_resume'])) {
                $data['upload_resume'] = $candidates->upload_resume;
            }

            $data['superadmin_instruction'] = isset($request->superadmin_instruction) ? $request->superadmin_instruction : '';
            $candidates->update($data);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('status', $e->getMessage());
        }

        DB::commit();
        return redirect()->back()->with('status', 'Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate)
    {
        DB::beginTransaction();
        try {
            $candidate->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', 'Candidate cannot be deleted!');
        }
        DB::commit();
        return redirect()->back()->with('status', 'Candidate deleted successfully!');
    }

    /**
     * Download resume the specified resource from storage.
     */
    public function downloadResume($resume)
    {
        DB::beginTransaction();
        try {
            $candidate = Candidate::find($resume);
            if (!$candidate) {
                return redirect()->back()->with('status', 'Candidate not found.');
            }

            $resumePath = $candidate->upload_resume;
            if (!$resumePath || !Storage::disk('local')->exists($resumePath)) {
                return redirect()->back()->with('status', 'Resume not found.');
            }

            return Storage::download($resumePath);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }
    }


    public function importFileView()
    {
        return view('candidates.import');
    }


    /**
     * Candidate data Import .
     */

    public function import(Request $request)
    {
        // $this->validate($request, [
        //     'file' => 'required|mimes:csv,xlsx,xls',
        // ]);

        DB::beginTransaction();
        try {
            $file = $request->file('file');
            $import = new CandidatesImport();
            Excel::import($import, $file);
            $newCandidatesCount = $import->getNewCandidatesCount();
            $updatedCandidatesCount = $import->getUpdatedCandidatesCount();
            $totalCandidatesCount = $newCandidatesCount + $updatedCandidatesCount;
            $uploadedFileName = $file->getClientOriginalName();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'File import failed: ' . $e->getMessage()]);
        }

        DB::commit();
        $message = "File <strong>'{$uploadedFileName}'</strong> successfully imported. <strong>{$newCandidatesCount}</strong> new candidates added and <strong>{$updatedCandidatesCount}</strong> updated from <strong>{$totalCandidatesCount}</strong> records.";
        return response()->json(['message' => $message]);
    }


    /**
     * Candidate Data Export .
     */

    public function export()
    {
        return Excel::download(new CandidatesExport, 'Candidates.csv');
    }

    /**
     * Selected Candidate Data Export .
     */
    public function selectedCandidateExport(Request $request)
    {

        $selectedCandidates = explode(',', $request->input('selectedCandidates'));
        return Excel::download(new CandidatesExport($selectedCandidates), 'Candidates.csv');
    }



    /**
     * Download Sample CSV Candidate
     */
    public function downloadSampleCsv()
    {
        DB::beginTransaction();
        $filePath = storage_path('app/public/sample.csv');
        $fileName = 'sample.csv';
        if (file_exists($filePath)) {
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/csv',
                'Content-Disposition' => 'attachment; filename=' . $fileName,
            ]);
        }
        return redirect()->back()->with('status', 'Sample CSV file Not Found.');
    }

    public function uploadResume(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,jpg,jpeg,png,docx',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();;
        $file->storeAs('resumes', $fileName, 'local');

        return redirect()->back()->with('status', "Resume uploaded successfully");
    }

    // Candidate AddEdit Instructions
    public function candidateAddEditInstructions(Request $request, $candidateId = null)
    {
        $candidate = Candidate::findOrFail($candidateId);
        $candidate->update([
            'superadmin_instruction' => $request->superadmin_instruction,
        ]);

        return Redirect::back()->with('status', 'Instruction details saved successfully');
    }


    public function getSuperadminInstruction($candidate_id)
    {
        $candidate = Candidate::findOrFail($candidate_id);
        return response()->json(['superadmin_instruction' => $candidate->superadmin_instruction]);
    }


    
}
