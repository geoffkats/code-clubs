<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DebugGemini extends Command
{
    protected $signature = 'ai:debug-gemini';
    protected $description = 'Debug Gemini API response structure';

    public function handle()
    {
        $this->info('🔍 Debugging Gemini API Response Structure...');
        
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            $this->error('❌ No GEMINI_API_KEY found in .env');
            return 1;
        }
        
        $this->info("✅ API Key found: " . substr($apiKey, 0, 10) . "...");
        
        // Test with a very simple prompt
        $prompt = "Say hello";
        
        $this->info("📝 Testing with prompt: " . substr($prompt, 0, 50) . "...");
        
        try {
            $response = Http::timeout(30)
                ->withOptions([
                    'verify' => false,
                ])
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $apiKey,
                ])
                ->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent', [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 500,
                        'temperature' => 0.7,
                    ]
                ]);
            
            $this->info("📊 Response Status: " . $response->status());
            
            if ($response->successful()) {
                $data = $response->json();
                
                $this->info("📋 Full Response Structure:");
                $this->line(json_encode($data, JSON_PRETTY_PRINT));
                
                // Try different ways to extract content
                $this->info("\n🔍 Content Extraction Attempts:");
                
                // Method 1: Standard structure
                $content1 = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'NOT_FOUND';
                $this->line("Method 1 (standard): " . ($content1 !== 'NOT_FOUND' ? $content1 : 'NOT_FOUND'));
                
                // Method 2: Alternative structure
                $content2 = $data['candidates'][0]['text'] ?? 'NOT_FOUND';
                $this->line("Method 2 (alternative): " . ($content2 !== 'NOT_FOUND' ? $content2 : 'NOT_FOUND'));
                
                // Method 3: Direct content
                $content3 = $data['candidates'][0]['content']['text'] ?? 'NOT_FOUND';
                $this->line("Method 3 (direct): " . ($content3 !== 'NOT_FOUND' ? $content3 : 'NOT_FOUND'));
                
                // Method 4: Check if content exists
                if (isset($data['candidates'][0]['content'])) {
                    $this->line("Content object exists: " . json_encode($data['candidates'][0]['content']));
                }
                
                // Method 5: Check finish reason
                $finishReason = $data['candidates'][0]['finishReason'] ?? 'UNKNOWN';
                $this->line("Finish reason: " . $finishReason);
                
                return 0;
            } else {
                $this->error("❌ API request failed: " . $response->status());
                $this->error("Response: " . $response->body());
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Exception: " . $e->getMessage());
            return 1;
        }
    }
}
