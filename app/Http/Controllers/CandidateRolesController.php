<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateRoles;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class CandidateRolesController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CandidateRoles $candidatesRoles)
    {
        $query = CandidateRoles::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($subquery) use ($search) {
                $subquery->where('candidate_role', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', $search . '%');
            });
        }
        $candidatesRoles = $query->paginate(10);

        return view('candidates_role.all', compact('candidatesRoles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('candidates_role.add');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'candidate_role' => 'required|max:255|unique:candidate_roles,candidate_role',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            CandidateRoles::create([
                'candidate_role' => $request->candidate_role,
                'status' => $request->role_status,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('status', $e->getMessage());
        }

        DB::commit();
        return redirect()->back()->with('status', 'Role Created Successfully!');
    }

    /**
     * Edit the specified resource.
     */
    public function edit($candidatesRole)
    {
        $candidatesRole = CandidateRoles::find($candidatesRole);
        return view('candidates_role.edit')->with(compact('candidatesRole'));
    }


    /**
     * Update the specified resource.
     */
    public function update(Request $request, CandidateRoles $candidatesRole)
    {
        $request->validate([
            'candidate_role' => [
                'required', Rule::unique('candidates', 'contact')->ignore($request->id),
            ],
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();
            $candidatesRole->update([
                'candidate_role' => $request->candidate_role,
                'status' => $request->role_status,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('status', $e->getMessage());
        }

        DB::commit();
        return redirect()->back()->with('status', 'Role Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CandidateRoles $candidatesRole)
    {
        DB::beginTransaction();
        try {
            $candidatesRole->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', 'Candidate cannot be deleted!');
        }

        DB::commit();
        return redirect()->back()->with('status', 'Candidate Role deleted successfully!');
    }




}
