<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\SessionSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClubSessionsController extends Controller
{
	public function generate(int $club_id)
	{
		$club = Club::where('school_id', auth()->user()->school_id)->findOrFail($club_id);
		$start = $club->club_start_date ? Carbon::parse($club->club_start_date) : Carbon::now();
		for ($i = 1; $i <= ($club->club_duration_weeks ?? 8); $i++) {
			SessionSchedule::updateOrCreate(
				['club_id' => $club->id, 'session_week_number' => $i],
				['session_date' => $start->copy()->addWeeks($i - 1)->toDateString()]
			);
		}
		return redirect()->route('attendance.grid', ['club_id' => $club->id, 'week' => 1]);
	}
}


