<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\auth\LoginController;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AttendanceController extends Controller
{

    public function index(Request $request)
{
    $User = User::all();
    if ($request->ajax()) {
        $data = Attendance::query();

        if (Auth::user()->role === 'user') {
            $data->where('user', Auth::id());
        }

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" id="show-attendance" title="Show Attendance"
                        data-id="'.$row->id.'" data-url="' . route('att.show', $row->id) . '"
                        class="btn btn-info btn-sm"><i class="bi bi-eye-fill"></i> Show</a> ';

                if (Auth::user()->role === 'admin') {
                    $btn .= '<a href="javascript:void(0)" id="edit-attendance" title="Edit Attendance"
                            data-id="'.$row->id.'" data-url="' . route('att.edit', $row->id) . '" 
                            class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a> ';

                    $btn .= '<a href="javascript:void(0)" id="delete-attendance" title="Delete Attendance"
                            data-id="'.$row->id.'" data-url="' . route('att.destroy', $row->id) . '" 
                            class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i> Delete</a>';
                }

                return $btn;
            })
            ->rawColumns(['action'])->make(true);
    }

    if (Auth::user()->role === 'user') {
        $Attendance = Attendance::with('user')->where('user', Auth::id())->get();
    } else {
        $Attendance = Attendance::with('user')->get();
    }

    return view('att.index', compact('Attendance', 'User'));
}

    public function create()
    {
        $Attendance = Attendance::all();
        $User = User::all();

        return view('att.create', compact('Attendance', 'User'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user' => 'required',
            'check_in' => 'required',
            'check_out' => 'required',
            'date' => 'required',
        ]);

        $Attendance = Attendance::create([
            'user' => $request->user,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'date' => $request->date,
        ]);

        return response()->json($Attendance);
    }

    public function show($id)
    {
        $Attendance = Attendance::find($id);
        $User = User::all();

        return response()->json($Attendance);
    }

    public function edit($id)
    {
        $Attendance = Attendance::find($id);
        $User = User::all();

        return response()->json($Attendance);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user' => 'required',
            'check_in' => 'required',
            'check_out' => 'required',
            'date' => 'required',
        ]);

        $Attendance = Attendance::findOrFail($id);
        $Attendance->update([
            'user' => $validated['user'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'date' => $validated['date'],
        ]);
    }

    public function destroy($id)
    {
        $Attendance = Attendance::findOrFail($id);
        $Attendance = $Attendance->delete();

        return response()->json($Attendance);
    }

    public function checkIn(Request $request) 
    {
        $today = Carbon::today()->toDateString();
        $Attendance = Attendance::where('user', Auth::id())->whereDate('date', $today)->first();

        if ($Attendance && $Attendance->check_in) {
            return redirect()->route('att.index')->with('error', 'You have already checked in today');
    }

        $Attendance = Attendance::create([
                'user' => auth()->id(),
                'check_in' => Carbon::now(),
                'date'=> Carbon::now(),
                'status' => 'checked_in',
            ]);

            return response()->json($Attendance);
    }
    
    public function checkOut(Request $request)
        {
            $today = Carbon::today()->toDateString();

        $Attendance = Attendance::where('user', Auth::id())
            ->whereDate('date', $today)->first();

        if (!$Attendance || !$Attendance->check_in) {
            return redirect()->route('att.index')->with('error', 'You must check in first.');
        }

        if ($Attendance->check_out) {
            return redirect()->route('att.index')->with('error', 'You have already checked out today');
        }

            $activeCheckIn = Attendance::where('user', auth()->id())
                ->whereNull('check_out')->first();

            if ($activeCheckIn) {
                $activeCheckIn->update([
                    'check_out' => Carbon::now()->format('H:i:s'),
                    'status' => 'checked_out',
                ]);
                return response()->json(['message' => 'Checked out successfully!', 'check_in' => $activeCheckIn]);
            }

        }    
}