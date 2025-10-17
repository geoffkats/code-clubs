<?php

namespace App\Services;

use App\Mail\ParentReportMail;
use App\Models\Report;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
	public function send_parent_report_email(Report $report, string $parent_email, string $access_code): void
	{
		Mail::to($parent_email)->queue(new ParentReportMail($report, $access_code));
	}
}


