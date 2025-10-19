<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RealAIService;

class TestAI extends Command
{
    protected $signature = 'ai:test';
    protected $description = 'Test AI service integration';

    public function handle()
    {
        $this->info('🤖 Testing AI Service Integration...');
        
        // Check environment variables
        $this->info("\n📋 Environment Check:");
        $this->line("GEMINI_API_KEY: " . (env('GEMINI_API_KEY') ? '✅ Set' : '❌ Not set'));
        $this->line("OPENAI_API_KEY: " . (env('OPENAI_API_KEY') ? '✅ Set' : '❌ Not set'));
        
        if (!env('GEMINI_API_KEY') && !env('OPENAI_API_KEY')) {
            $this->error('❌ No AI API keys found! Please set GEMINI_API_KEY or OPENAI_API_KEY in your .env file');
            return 1;
        }
        
        // Test AI service
        $this->info("\n🧪 Testing AI Service...");
        
        try {
            $aiService = new RealAIService();
            
            $studentData = [
                'name' => 'Test Student',
                'club_name' => 'Test Code Club',
                'age' => 12,
                'grade' => '6th'
            ];
            
            $metrics = [
                'performance_level' => 'excellent',
                'average_score' => 85,
                'attendance_percentage' => 95,
                'total_assessments' => 3
            ];
            
            $this->info("Generating test content...");
            $content = $aiService->generateReportContent($studentData, $metrics);
            
            $this->info("\n✅ AI Content Generated Successfully!");
            $this->line("\n📝 Sample Content:");
            $this->line("Favorite Concept: " . $content['favorite_concept']);
            $this->line("Challenges: " . substr($content['challenges_overcome'], 0, 100) . "...");
            $this->line("Achievements: " . substr($content['special_achievements'], 0, 100) . "...");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ AI Service Test Failed: " . $e->getMessage());
            return 1;
        }
    }
}
