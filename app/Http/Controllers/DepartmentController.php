<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;


class DepartmentController extends Controller
{
    public function index(Request $request)
    {
         $Department = Department::all();

        if ($request->ajax()) {
            $data = Department::select('*');
            return DataTables::of($data)->addColumn('action', function ($row) {
            
                $btn = ' <a href="javascript:void(0)" id="show-department" title=" Show Department"
                    data-id="'.$row->id.'"
                    data-url=" '. route('dep.show', $row->id) . ' "
                    class="btn btn-info btn-sm "><i class="bi bi-eye-fill"></i> Show</a> ';

            if(Auth::user()->role == 'admin'){
                
               $btn .= '<a href="javascript:void(0)" id="edit-department" title=" Edit Department"
                   data-id="'.$row->id.'"
                   data-url="'. route('dep.edit', $row->id) .' " 
                   class="edit btn btn-primary btn-sm updateDepartmentForm">
                   <i class="bi bi-pencil-square"></i> Edit</a> ';

               $btn .= '<a href="javascript:void(0)" id="delete-user" title=" Delete Department" data-id="'.$row->id.'"
                   data-url="' . route('dep.destroy', $row->id) . '" class="edit btn btn-danger btn-sm">
                   <i class="bi bi-trash3"></i> Delete ';
            }

                return $btn;
            })->rawColumns(['action'])->make(true);
    }
        return view('dep.index',compact('Department'));
    }

    public function create()
    {
        $Department = Department::all();
        return view('dep.create', compact('Department'));
    }

    public function store(Request $request)
    {
        $Department = Department::all();

            $validated = $request->validate([
                'name' => 'required',
                'description' => 'required'
            ]);
            
            $Department = Department::create([
                'name'=> $request->name,
                'description'=> $request->description,
            ]);
            return response()->json($Department);    
    }

    public function show($id)
    {
            $Department = Department::find($id);
        return response()->json($Department);
    }

    public function edit($id)
    {
            $Department = Department::find($id);
        return response()->json($Department);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $Department = Department::findOrFail($id);
        $Department->update([
            'name' => $validated['name'],
            'description' => $validated['description']
        ]);
        return response()->json($Department);
    }

    public function destroy($id)
    {
        $Department = Department::findOrFail($id);
        $Department = $Department->delete();

        return response()->json($Department);
    }
}