<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\AccessCodeService;
use Illuminate\Http\Request;

class ParentReportController extends Controller
{
	public function show_public(int $report_id, Request $request, AccessCodeService $codes)
	{
		$code = (string) $request->query('code');
		$report = Report::with(['student', 'club', 'access_code'])->findOrFail($report_id);
		if (!$code || !$codes->verify_access_code($report, $code)) {
			abort(403, 'Invalid or expired access code');
		}
		return view('reports.public', compact('report'));
	}
}


