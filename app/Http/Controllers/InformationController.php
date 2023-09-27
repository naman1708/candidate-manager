<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Information::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('candidate_name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->orWhere('categories', 'LIKE', "%$search%")
                ->orWhere('experience', 'LIKE', "%$search%");
        }
        $informations = $query->paginate(10);
        return view('information.all', compact('informations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('information.add');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'candidate_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:information,email'],
            'categories' => ['required'],
            'date' => ['required'],
            'source' => ['required'],
            'experience' => ['required'],
            'contact' => ['required'],
            'status' => ['required'],
            'salary' => ['required'],
            'expectation' => ['required'],
            'upload_resume' => ['required', 'file', 'mimes:pdf,doc,docx'],
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

            Information::create($data);
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
    public function show($information)
    {
        $information = Information::find($information);
        return view('information.view')->with(compact('information'));
    }


    /**
     * Edit the specified resource.
     */
    public function edit($information)
    {
        $information = Information::find($information);
        return view('information.edit')->with(compact('information'));
    }


    /**
     * Update the specified resource.
     */
    public function update(Request $request)
    {
        $request->validate([
            'candidate_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'categories' => ['required'],
            'date' => ['required'],
            'source' => ['required'],
            'experience' => ['required'],
            'contact' => ['required'],
            'status' => ['required'],
            'salary' => ['required'],
            'expectation' => ['required'],
            'upload_resume' => ['file', 'mimes:pdf,doc,docx'],
        ]);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $information = Information::find($request->id);

            if ($request->hasFile('upload_resume')) {
                $uploadedFile = $request->file('upload_resume');
                $originalFileName = $uploadedFile->getClientOriginalName();
                $resumePath = $uploadedFile->storeAs('resumes', $originalFileName, 'local');

                if (!$uploadedFile->isValid()) {
                    return redirect()->back()->with('status', 'There was an error uploading the resume file.');
                }
                // Delete the old resume file if it exists
                if (!empty($information->upload_resume)) {
                    Storage::disk('local')->delete($information->upload_resume);
                }
                $data['upload_resume'] = $resumePath;
            } elseif (empty($data['upload_resume'])) {
                $data['upload_resume'] = $information->upload_resume;
            }
            $information->update($data);
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
    public function destroy($information)
    {
        DB::beginTransaction();
        try {
            Information::find($information)->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', 'Information cannot be deleted!');
        }
        DB::commit();
        return redirect()->back()->with('status', 'Information deleted successfully!');
    }


    /**
     * Download resume the specified resource from storage.
     */
    public function downloadResume($resume)
    {
        DB::beginTransaction();
        try {
            $information = Information::find($resume);
            $resumePath = $information->upload_resume;
            if (Storage::disk('local')->exists($resumePath)) {
                return Storage::download($resumePath);
            } else {
                return redirect()->back()->with('status', 'Resume not found.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', 'Resume cannot be Download.');
        }

        DB::commit();
        return redirect()->back()->with('status', 'Download Resume deleted successfully!');
    }
}
