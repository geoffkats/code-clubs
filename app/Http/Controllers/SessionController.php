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
            ->withCount(['attendance_records'])
            ->orderBy('session_date', 'desc')
            ->paginate(20);

        $clubs = Club::with(['school'])->orderBy('club_name')->get();

        return view('sessions.index', compact('sessions', 'clubs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'club_id' => ['required', 'exists:clubs,id'],
            'session_date' => ['required', 'date'],
            'session_week_number' => ['required', 'integer', 'min:1', 'max:52'],
        ]);

        // Check if session already exists for this club and week
        $existingSession = SessionSchedule::where('club_id', $request->club_id)
            ->where('session_week_number', $request->session_week_number)
            ->first();

        if ($existingSession) {
            $clubName = $existingSession->club->club_name ?? 'Unknown Club';
            return redirect()->back()
                ->with('error', "A session for {$clubName} already exists for week {$request->session_week_number}. Please choose a different week number.");
        }

        try {
            $data = $request->only(['club_id', 'session_date', 'session_week_number']);
            $session = SessionSchedule::create($data);
            
            return redirect()->back()
                ->with('success', 'Session created successfully!');
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return redirect()->back()
                ->with('error', 'A session for this club and week already exists. Please choose a different week number.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create session. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $session = SessionSchedule::findOrFail($id);
            $session->delete();
            
            return redirect()->route('sessions.index')
                ->with('success', 'Session deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('sessions.index')
                ->with('error', 'Failed to delete session. Please try again.');
        }
    }
}
