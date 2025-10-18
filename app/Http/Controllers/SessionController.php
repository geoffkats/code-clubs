<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\SessionSchedule;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = SessionSchedule::with(['club.school'])
            ->withCount(['attendance'])
            ->orderBy('session_date', 'desc')
            ->paginate(20);
            
        $clubs = Club::with('school')->orderBy('club_name')->get();
        
        return view('sessions.index', compact('sessions', 'clubs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'club_id' => ['required', 'exists:clubs,id'],
            'session_title' => ['required', 'string'],
            'session_date' => ['required', 'date'],
            'session_time' => ['required'],
            'session_week_number' => ['required', 'integer', 'min:1', 'max:52'],
        ]);

        $session = SessionSchedule::create($data);
        
        return redirect()->route('sessions.index')
            ->with('success', 'Session created successfully!');
    }
}
