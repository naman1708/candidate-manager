<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class InformationController extends Controller
{


    public function informations(Request $request)
    {
        $query = Information::with('manager');

        // Apply search filters
        $search = $request->search;
        if (!empty($search)) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('portal_name', 'LIKE', "%$search%")
                    ->orWhere('username', 'LIKE', "%$search%")
                    ->orWhere('password', 'LIKE', "%$search%")
                    ->orWhere('phone_number', 'LIKE', "%$search%");
            });
        }

        // Apply manager filter
        if (!empty($request->manager)) {
            $query->where('user_id', $request->manager);
        }

        if (Auth::user()->hasRole(['admin'])) {
            // Admin sees all information
        } elseif (Auth::user()->hasRole(['manager'])) {
            // Manager sees only their own information
            $query->where('user_id', Auth::id());
        } else {
            // Other roles see no information
            $informations = collect();
            $managers = [];
            return view('getInformation.all', compact('informations', 'managers'));
        }

        $informations = $query->orderBy('id', 'desc')->paginate(10);

        // Get managers(users)
        $managers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['manager', 'admin']);
        })->pluck('name', 'id')->toArray();

        return view('getInformation.all', compact('informations', 'managers'));
    }



    public function create()
    {
        return view('getInformation.add');
    }


    public function store(Request $request)
    {
        $request->validate([
            'portal_name.*' => 'required',
            'phone_number.*' => 'required',
            'username.*' => 'required',
            'password.*' => 'required',
            // 'security_question.*' => 'required',
            // 'security_answer.*' => 'required',
        ]);

        DB::beginTransaction();
        try {

            foreach ($request->portal_name as $key => $value) {
                Information::create([
                    'portal_name' => $request->portal_name[$key],
                    'phone_number' => $request->phone_number[$key],
                    'username' => $request->username[$key],
                    'password' => $request->password[$key],
                    'security_question' => $request->security_question[$key],
                    'security_answer' => $request->security_answer[$key],
                    'user_id' => Auth::id()
                ]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('status', $e->getMessage());
        }
        DB::commit();
        return Redirect::route('informations')->with('status', 'Details saved successfully');
    }



    public function show(Information $information)
    {
        $information = Information::with('manager')->find($information->id);
        return view('getInformation.view', compact('information'));
    }


    public function edit(Information $information)
    {
        return view('getInformation.edit', compact('information'));
    }



    public function update(Request $request, Information $information)
    {
        $request->validate([
            'portal_name' => 'required',
            'phone_number' => 'required',
            'username' => 'required',
            'password' => 'required',
            // 'security_question' => 'required',
            // 'security_answer' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $information->update([
                'portal_name' => $request->portal_name,
                'phone_number' => $request->phone_number,
                'username' => $request->username,
                'password' => $request->password,
                'security_question' => $request->security_question,
                'security_answer' => $request->security_answer,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('status', $e->getMessage());
        }
        DB::commit();
        return Redirect::route('informations')->with('status', 'Details Updated successfully');
    }


    public function delete(Information $information)
    {
        DB::beginTransaction();
        try {

            $information->delete();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('status', $e->getMessage());
        }
        DB::commit();
        return Redirect::route('informations')->with('status', 'Details Deleted successfully');
    }
}
