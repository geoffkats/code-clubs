<?php

namespace App\Services;

use App\Models\Report;
use App\Models\ReportAccessCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * AccessCodeService
 * 
 * Enterprise-grade service for managing secure access codes for student reports.
 * Handles generation, validation, and lifecycle management of access codes.
 * 
 * @package App\Services
 * @version 1.0.0
 * @author Code Club System
 */
class AccessCodeService
{
    /**
     * Default expiration period for access codes (in days)
     */
    private const DEFAULT_EXPIRATION_DAYS = 90;
    
    /**
     * Access code length
     */
    private const ACCESS_CODE_LENGTH = 8;
    
    /**
     * Preview length for access codes (first X characters shown)
     */
    private const PREVIEW_LENGTH = 2;

    /**
     * Create or update access code for a report
     * 
     * This method ensures every report has a secure access code for parent viewing.
     * Uses updateOrCreate to handle both new and existing reports gracefully.
     * 
     * @param int $report_id The ID of the report
     * @param int|null $days_valid Number of days the code should be valid (default: 90)
     * @return array Contains the model and plain text code
     * @throws \Exception If code generation fails
     */
    public function create_access_code_for_report(int $report_id, ?int $days_valid = self::DEFAULT_EXPIRATION_DAYS): array
    {
        try {
            // Get report with student information
            $report = Report::with('student')->findOrFail($report_id);
            
            // Generate unique access code with child's first name
            $plain = $this->generateUniqueAccessCode($report->student->student_first_name);
            $hash = Hash::make($plain);
            
            // Calculate expiration date
            $expires_at = $days_valid ? now()->addDays($days_valid) : null;
            
            // Create or update access code record
            $model = ReportAccessCode::updateOrCreate(
                ['report_id' => $report_id],
                [
                    'access_code_hash' => $hash,
                    'access_code_plain_preview' => $plain, // Store full code for display
                    'access_code_expires_at' => $expires_at,
                ]
            );
            
            // Log successful code generation for audit trail
            Log::info('Access code generated for report', [
                'report_id' => $report_id,
                'student_name' => $report->student->student_first_name,
                'access_code' => $plain,
                'expires_at' => $expires_at,
                'generated_by' => auth()->id() ?? 'system'
            ]);
            
            return [
                'model' => $model, 
                'plain' => $plain,
                'expires_at' => $expires_at
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to generate access code', [
                'report_id' => $report_id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
     * Generate a unique access code with child's first name
     * 
     * Creates a memorable access code using the child's first name and a unique number.
     * Format: firstName-XXXX (e.g., "john-2500", "mary-1234")
     * 
     * @param string $firstName The child's first name
     * @return string The generated unique access code
     */
    private function generateUniqueAccessCode(string $firstName): string
    {
        // Clean and normalize the first name
        $cleanName = strtolower(preg_replace('/[^a-zA-Z]/', '', $firstName));
        
        // Generate a unique 4-digit number
        $uniqueNumber = $this->generateUniqueNumber();
        
        // Combine name and number
        return $cleanName . '-' . $uniqueNumber;
    }
    
    /**
     * Generate a unique 4-digit number
     * 
     * @return string A 4-digit number
     */
    private function generateUniqueNumber(): string
    {
        // Generate a random 4-digit number between 1000-9999
        return str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Generate a secure access code (legacy method)
     * 
     * Creates a cryptographically secure random access code using only
     * uppercase letters and numbers for better readability.
     * 
     * @return string The generated access code
     */
    private function generateSecureAccessCode(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        
        for ($i = 0; $i < self::ACCESS_CODE_LENGTH; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        return $code;
    }
    
    /**
     * Create a preview version of the access code
     * 
     * Shows first few characters followed by asterisks for security.
     * 
     * @param string $plain The plain text access code
     * @return string The preview version
     */
    private function createPreview(string $plain): string
    {
        $visible = substr($plain, 0, self::PREVIEW_LENGTH);
        $hidden = str_repeat('*', self::ACCESS_CODE_LENGTH - self::PREVIEW_LENGTH);
        return $visible . $hidden;
    }

    /**
     * Verify an access code for a report
     * 
     * Validates that the provided access code is correct and not expired.
     * Includes comprehensive logging for security auditing.
     * 
     * @param Report $report The report to verify access for
     * @param string $plain The plain text access code to verify
     * @return bool True if the code is valid and not expired
     */
    public function verify_access_code(Report $report, string $plain): bool
    {
        $code = $report->access_code;
        
        // Check if access code exists
        if (!$code) {
            Log::warning('Access code verification failed - no code exists', [
                'report_id' => $report->id,
                'student_id' => $report->student_id
            ]);
            return false;
        }
        
        // Check if access code has expired
        if ($code->access_code_expires_at && now()->greaterThan($code->access_code_expires_at)) {
            Log::warning('Access code verification failed - code expired', [
                'report_id' => $report->id,
                'expires_at' => $code->access_code_expires_at,
                'current_time' => now()
            ]);
            return false;
        }
        
        // Verify the access code hash
        $isValid = Hash::check($plain, $code->access_code_hash);
        
        if ($isValid) {
            Log::info('Access code verification successful', [
                'report_id' => $report->id,
                'student_id' => $report->student_id,
                'verified_at' => now()
            ]);
        } else {
            Log::warning('Access code verification failed - invalid code', [
                'report_id' => $report->id,
                'student_id' => $report->student_id,
                'attempted_at' => now()
            ]);
        }
        
        return $isValid;
    }
    
    /**
     * Get access code statistics for reporting
     * 
     * @return array Statistics about access codes
     */
    public function getAccessCodeStatistics(): array
    {
        $totalCodes = ReportAccessCode::count();
        $activeCodes = ReportAccessCode::where(function($query) {
            $query->whereNull('access_code_expires_at')
                  ->orWhere('access_code_expires_at', '>', now());
        })->count();
        $expiredCodes = ReportAccessCode::where('access_code_expires_at', '<=', now())->count();
        
        return [
            'total_codes' => $totalCodes,
            'active_codes' => $activeCodes,
            'expired_codes' => $expiredCodes,
            'expiration_rate' => $totalCodes > 0 ? round(($expiredCodes / $totalCodes) * 100, 2) : 0
        ];
    }
}


