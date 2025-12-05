<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\auth\LoginController;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;


class TodoController extends Controller
{

    public function index(Request $request)
{  
    $User = User::all();

    if ($request->ajax()) {
        $data = Todo::query();

        if (Auth::user()->role === 'user') {
            $data->where('assign', Auth::id());
        }

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = ' <a href="javascript:void(0)" id="show-todo" title=" Show List"
                    data-id="'.$row->id.'" data-url=" '. route('todo.show', $row->id) . ' "
                    class="btn btn-info btn-sm "><i class="bi bi-eye-fill"></i> Show</a> ';

                    $btn .= '<a href="javascript:void(0)" id="edit-todo" title="Edit List" data-id="'.$row->id.'"
                        data-url="' . route('todo.edit', $row->id) . '"
                        class="edit btn btn-primary btn-sm">
                        <i class="bi bi-pencil-square"></i> Edit</a> ';
                    
                    if (Auth::user()->role == 'admin') {
                    $btn .= '<a href="javascript:void(0)" id="delete-todo" title="Delete List" data-id="'.$row->id.'"
                        data-url="' . route('todo.destroy', $row->id) . '" class="edit btn btn-danger btn-sm">
                        <i class="bi bi-trash3"></i> Delete ';
                }
                
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    if (Auth::user()->role === 'user') {
        $Todo = Todo::with('user')->where('assign', Auth::id())->get();
    } else {
        $Todo = Todo::with('user')->get();
    }

    return view('todo.index', compact('Todo', 'User'));
}

    public function create()
    {
        $Todo = Todo::all();
        $User = User::all();

        return view('todo.create', compact('Todo', 'User'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'task' => 'required|string|max:255',
        'assign' => 'required',
        'progress' => 'nullable|string|max:50',
        'priority' => 'nullable|string|max:50',
        'date' => 'nullable|date',
        'note' => 'nullable|string',
    ]);

    $Todo = Todo::create([
        'task' => $request->task,
        'assign' => $request->assign,
        'progress' => $request->progress,
        'priority' => $request->priority,
        'date' => $request->date,
        'note' => $request->note
    ]);

    return response()->json($Todo);
}

    public function show($id)
    {
        $Todo = Todo::find($id);
        return response()->json($Todo);
    }

    public function edit($id)
    {
         $Todo = Todo::find($id);
         return response()->json($Todo);
    }

    public function update(Request $request, $id)
    {

        if(Auth::user()->role === 'admin'){
        $validated = $request->validate([
            'task' => 'required',
            'assign' => 'required',
            'progress' => 'required',
            'priority' => 'required',
            'date' => 'required',
            'note' => 'required',
        ]);

        $Todo = Todo::findOrFail($id);
        $Todo->update([
         'task' => $validated['task'],
         'assign' => $validated['assign'],
         'progress' => $validated['progress'],
         'priority' => $validated['priority'],
         'date' => $validated['date'],
         'note' => $validated['note'],
        ]);
    
        return response()->json($Todo);
    }else{
         $validated = $request->validate([
             'progress' => 'required',
             'note' => 'required',
         ]);
           $Todo = Todo::findOrFail($id);
           $Todo->update([
           'progress' => $validated['progress'],
           'note' => $validated['note'],
           ]);
            return response()->json($Todo);
    }
    }

    public function destroy($id)
    {
        $Todo = Todo::findOrFail($id);
        $Todo = $Todo->delete();

        return response()->json($Todo);
    }
}
