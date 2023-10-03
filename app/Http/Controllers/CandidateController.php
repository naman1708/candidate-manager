<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $candidateRole = CandidateRoles::all();
        $query = Candidate::with('candidateRole');
        $search = $request->input('search');
        $role = $request->input('candidate_role');

        if (!empty($search) && !empty($role)) {
            $query->whereHas('candidateRole', function ($roleQuery) use ($role) {
                $roleQuery->where('candidate_role', $role);
            })->where(function ($query) use ($search) {
                $query->where('candidate_name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhere('contact', 'LIKE', "%$search%")
                    ->orWhere('experience', 'LIKE', "%$search%");
            });
        } elseif (empty($search) && !empty($role)) {
            $query->whereHas('candidateRole', function ($roleQuery) use ($role) {
                $roleQuery->where('candidate_role', $role);
            });
        } elseif (!empty($search) && empty($role)) {
            $query->where(function ($query) use ($search) {
                $query->where('candidate_name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhere('contact', 'LIKE', "%$search%")
                    ->orWhere('experience', 'LIKE', "%$search%");
            });
        }

        $candidates = $query->paginate(10);
        return view('candidates.all', compact('candidates', 'candidateRole'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $candidateRole = CandidateRoles::where('status', 'active')->get();
        return view('candidates.add')->with(compact('candidateRole'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'candidate_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:candidates,email'],
            'candidate_role_id' => ['required'],
            'date' => ['required'],
            'experience' => ['required'],
            'contact' => ['required', 'unique:candidates,contact'],
            'status' => ['required'],
            // 'contact_by' => ['required'],
            // 'salary' => ['required'],
            // 'expectation' => ['required'],
            // 'source' => ['required'],
            // 'upload_resume' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            // if ($request->hasFile('upload_resume')) {
            //     $resumePath = $request->file('upload_resume')->store('resumes', 'local');
            //     $data['upload_resume'] = $resumePath;
            // }
            if ($request->hasFile('upload_resume')) {
                $uploadedFile = $request->file('upload_resume');
                $originalFileName = $uploadedFile->getClientOriginalName();
                $resumePath = $uploadedFile->storeAs('resumes', $originalFileName, 'local');
                $data['upload_resume'] = $resumePath;
            }

            Candidate::create($data);
        } catch (\Exception $e) {
            DB::rollback();
            dd("rollback", $e->getMessage());
            return redirect()->back()->with('status', 'Something went wrong!');
        }

        DB::commit();
        return redirect()->back()->with('status', 'Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate)
    {
        return view('candidates.view')->with(compact('candidate'));
    }


    /**
     * Edit the specified resource.
     */
    public function edit($candidates)
    {
        $candidates = Candidate::find($candidates);
        $candidateRole = CandidateRoles::where('status', 'active')->get();
        return view('candidates.edit')->with(compact('candidates', 'candidateRole'));
    }


    /**
     * Update the specified resource.
     */
    public function update(Request $request)
    {

        $request->validate([
            'candidate_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'candidate_role_id' => ['required'],
            'date' => ['required'],
            'source' => ['required'],
            'experience' => ['required'],
            'contact' => [
                'required', Rule::unique('candidates', 'contact')->ignore($request->id),
            ],
            'contact_by' => ['required'],
            'status' => ['required'],
            'salary' => ['required'],
            'expectation' => ['required'],
            // 'upload_resume' => ['file', 'mimes:pdf,doc,docx'],
        ]);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $candidates = Candidate::find($request->id);

            if ($request->hasFile('upload_resume')) {
                $uploadedFile = $request->file('upload_resume');
                $originalFileName = $uploadedFile->getClientOriginalName();
                $resumePath = $uploadedFile->storeAs('resumes', $originalFileName, 'local');

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
            $candidates->update($data);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('status', 'Something went wrong!');
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
}
