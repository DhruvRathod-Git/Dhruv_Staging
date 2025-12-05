<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\Designation;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class UsersController extends Controller
{
    public function dashboard()
    {
        return view('users.dashboard');
    }

    public function index(Request $request)
    {
        $Designation = Designation::all();
        $Department = Department::all();

        if ($request->ajax()) {
            $data = User::select('*');
            return DataTables::of($data)->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" id="show-user" title="Show User" data-id="'.$row->id.'"
                        data-url="' . route('user.show', $row->id) . '"
                        class="btn btn-info btn-sm"><i class="bi bi-eye-fill"></i> Show</a> ';

                if (Auth::user()->role == 'admin') {
                    $btn .= '<a href="javascript:void(0)" id="edit-user" title="Edit User" data-id="'.$row->id.'"
                            data-url="' . route('user.edit', $row->id) . '"
                            class="btn btn-primary btn-sm updateUserForm">
                            <i class="bi bi-pencil-square"></i> Edit</a> ';

                    $btn .= '<a href="javascript:void(0)" id="delete-user" title="Delete User" data-id="'.$row->id.'"
                            data-url="' . route('user.destroy', $row->id) . '" 
                            class="btn btn-danger btn-sm">
                            <i class="bi bi-trash3"></i> Delete</a>';
                }

                return $btn;
            })->rawColumns(['action'])->make(true);
        }

        return view('user.index', compact('Designation', 'Department'));
    }

    public function create()
    {
        $designations = Designation::all();
        $departments = Department::all();
        return view('user.create', compact('designations', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4', 
            'number' => 'required|digits:10',
            'designation' => 'required',
            'department' => 'required',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), 
            'number' => $validated['number'],
            'designation' => $validated['designation'],
            'department' => $validated['department'],
        ]);

        return response()->json($user);
    }

    public function show($id)
    {
        $user = User::with(['designation', 'department'])->find($id);
        return response()->json($user);
    }

    public function edit($id)
    {
        $user = User::with(['designation', 'department'])->find($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'number' => 'required|digits:10',
            'designation' => 'required',
            'department' => 'required',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'number' => $validated['number'],
            'designation' => $validated['designation'],
            'department' => $validated['department'],
        ]);

        if ($request->has('password') && $request->password) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }

    public function fetchCountry()
    {
        $countries = Country::all(['name', 'id']);
        return view('dropdown', compact('countries'));
    }

    public function fetchState(Request $request)
    {
        $states = State::where('country_id', $request->country_id)->get(['name', 'id']);
        return response()->json(['states' => $states]);
    }

    public function fetchCity(Request $request)
    {
        $cities = City::where('state_id', $request->state_id)->get(['name', 'id']);
        return response()->json(['cities' => $cities]);
    }
}