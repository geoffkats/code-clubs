<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleLargeFileUploads
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply to file upload routes
        if ($request->is('facilitator/proofs*') || $request->is('admin/proofs*')) {
            // Set execution time limit for file uploads
            set_time_limit(300); // 5 minutes
            
            // Increase memory limit
            ini_set('memory_limit', '256M');
            
            // Log current settings
            \Log::info('File upload middleware applied:', [
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'max_execution_time' => ini_get('max_execution_time'),
                'memory_limit' => ini_get('memory_limit'),
                'request_size' => $request->header('content-length'),
                'has_file' => $request->hasFile('proof_url')
            ]);
        }
        
        return $next($request);
    }
}
