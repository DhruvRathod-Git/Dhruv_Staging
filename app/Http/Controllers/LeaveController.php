<?php

namespace App\Http\Controllers;

use App\Models\Leave;
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

class LeaveController extends Controller
{
    
    public function index(Request $request)
{

    $User = User::all();

    if ($request->ajax()) {
        $data = Leave::query();

        if (Auth::user()->role === 'user') {
            $data->where('user', Auth::id());
        }

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
            if(Auth::user()->role === 'admin'){
                $btn = '<a href="javascript:void(0)" id="approve-leave" title="Approve Leave"
                            data-id="'.$row->id.'" data-url="' . route('leave.approve', $row->id) . '"
                            class="btn btn-primary btn-sm"><i class="bi bi-check-lg"></i> Approve</a> ';

                $btn .= '<a href="javascript:void(0)" id="reject-leave" title="Reject Leave"
                            data-id="'.$row->id.'" data-url="' . route('leave.reject', $row->id) . '"
                            class="btn btn-secondary btn-sm"><i class="bi bi-x-lg"></i> Reject</a>';

                $btn .= '<a href="javascript:void(0)" id="delete-leave" title="Delete Leave" data-id="'.$row->id.'"
                    data-url="' . route('leave.destroy', $row->id) . '" class="edit btn btn-danger btn-sm ms-1">
                    <i class="bi bi-trash3"></i> Delete ';

                return $btn;
            }
            })
            ->rawColumns(['action'])->make(true);
    }

    if (Auth::user()->role === 'user') {
        $Leave = Leave::with('user')->where('user', Auth::id())->get();
    } else {
        $Leave = Leave::with('user')->get();
    }

    $Status = $Leave->map(function($Leave) {
        if ($Leave->status === 'Approved') {
            $Leave->status_badge = '<span class="badge bg-success px-3 py-2"><i class="bi bi-check2-circle"></i> Approved</span>';
        } elseif ($Leave->status === 'Pending') {
            $Leave->status_badge = '<span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-hourglass-split"></i> Pending</span>';
        } elseif ($Leave->status === 'Rejected') {
            $Leave->status_badge = '<span class="badge bg-danger px-3 py-2"><i class="bi bi-x-circle"></i> Rejected</span>';
        }
        return $Leave;
    });
    
    return view('leave.index', compact('Leave' ,'Status', 'User'));
}

    public function create()
    {
        $Leave = Leave::all();
        $User = User::all();

        return view('leave.create', compact('Leave', 'User'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user' => 'required',
            'leave_date' => 'required',
            'reason' => 'required',
        ]);
        

        $Leave = Leave::create([
            'user' => $request->user,
            'leave_date' => $request->leave_date,
            'reason' => $request->reason,
        ]);

        return response()->json($Leave);
    }

     public function approve($id)
    {
        $Leave = Leave::findOrFail($id);
        $Leave->update([
            'status' => 'Approved'
        ]);

        return redirect()->route('leave.index')->with('success', 'Leave Approved');
    }

    public function reject($id)
    {
        $Leave = Leave::findOrFail($id);
        $Leave->update([
            'status' => 'Rejected'
        ]);

        return redirect()->route('leave.index')->with('success', 'Leave Rejected');
    }

    public function destroy($id)
    {
        $Leave = Leave::findOrFail($id);
        $Leave = $Leave->delete();

        return response()->json($Leave);
    }
}