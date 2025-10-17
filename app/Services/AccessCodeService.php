<?php

namespace App\Services;

use App\Models\Report;
use App\Models\ReportAccessCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccessCodeService
{
    public function create_access_code_for_report(int $report_id, ?int $days_valid = 90): array
    {
        $plain = Str::upper(Str::random(8));
        $hash = Hash::make($plain);
        $model = ReportAccessCode::updateOrCreate(
            ['report_id' => $report_id],
            [
                'access_code_hash' => $hash,
                'access_code_plain_preview' => substr($plain, 0, 2).'******',
                'access_code_expires_at' => $days_valid ? now()->addDays($days_valid) : null,
            ]
        );
        return ['model' => $model, 'plain' => $plain];
    }

	public function verify_access_code(Report $report, string $plain): bool
	{
		$code = $report->access_code;
		if (!$code) {
			return false;
		}
		if ($code->access_code_expires_at && now()->greaterThan($code->access_code_expires_at)) {
			return false;
		}
		return Hash::check($plain, $code->access_code_hash);
	}
}


