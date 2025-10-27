<?php
// config/upload_limits.php
// Increased limits for video uploads
ini_set('upload_max_filesize', '50M');        // Increased from 10M to 50M
ini_set('post_max_size', '60M');              // Increased from 12M to 60M  
ini_set('max_execution_time', '600');         // Increased from 300 to 600 seconds (10 minutes)
ini_set('memory_limit', '512M');              // Increased from 256M to 512M
ini_set('max_input_time', '600');            // Increased: 10 minutes for input processing
ini_set('file_uploads', '1');                 // Ensure file uploads are enabled
