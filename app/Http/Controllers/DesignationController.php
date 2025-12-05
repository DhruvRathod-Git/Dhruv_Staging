<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class DesignationController extends Controller
{
    
    public function index(Request $request)
    {
        $Designation = Designation::all();

        if ($request->ajax()) {
            $data = Designation::select('*');
            return DataTables::of($data)->addColumn('action', function ($row) {
            
                $btn = ' <a href="javascript:void(0)" id="show-designation" title="Show Designation" data-id="'.$row->id.'"
                    data-url=" '. route('des.show', $row->id) . '"
                    class="btn btn-info btn-sm "><i class="bi bi-eye-fill"></i> Show</a> ';

            if(Auth::user()->role == 'admin'){
                
               $btn .= '<a href="javascript:void(0)" id="edit-designation" title=" Edit Designation" data-id="'.$row->id.'"
                   data-url="' . route('des.edit', $row->id) . '" 
                   class="edit btn btn-primary btn-sm updateDesignationForm">
                   <i class="bi bi-pencil-square"></i> Edit</a> ';

                $btn .= '<a href="javascript:void(0)" id="delete-user" title=" Delete Designation" data-id="'.$row->id.'"
                    data-url="' . route('des.destroy', $row->id) . '" class="edit btn btn-danger btn-sm">
                    <i class="bi bi-trash3"></i> Delete ';
            }

                return $btn;
            })->rawColumns(['action'])->make(true);
    }

        return view('des.index', compact('Designation'));
    }

    public function create()
    {
        $Designation = Designation::all();
        return view('des.create', compact('Designation'));
    }

     public function store(Request $request)
    {
        $Designation = Designation::all();

            $validated = $request->validate([
                'name' => 'required|string',
                'description' => 'required',
        ]);

        $Designation = Designation::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json($Designation);
    }

    public function show($id)
    {
            $Designation = Designation::find($id);
       return response()->json($Designation);
    }

    public function edit($id)
    {
            $Designation = Designation::find($id);
        return response()->json($Designation);
    }

       public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $Designation = Designation::findOrFail($id);
        $Designation->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        return response()->json($Designation);
    }

    public function destroy($id)
    {
        $Designation = Designation::findOrFail($id);
        $Designation = $Designation->delete();
        
        return response()->json($Designation);
    }
}