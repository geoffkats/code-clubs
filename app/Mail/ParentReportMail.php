<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParentReportMail extends Mailable
{
	use Queueable, SerializesModels;

	public Report $report;
	public string $access_code;

	public function __construct(Report $report, string $access_code)
	{
		$this->report = $report;
		$this->access_code = $access_code;
	}

	public function build(): self
	{
		$url = url('/r/'.$this->report->id.'?code='.$this->access_code);
		return $this->subject('Your child\'s Code Club report')
			->view('mail.parent_report')
			->with([
				'report' => $this->report,
				'parent_access_url' => $url,
			]);
	}
}


