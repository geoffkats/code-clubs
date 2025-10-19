<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Real AI Service for generating dynamic content
 * 
 * This service integrates with actual AI APIs to generate
 * personalized, dynamic content based on student performance.
 */
class RealAIService
{
    private $apiKey;
    private $baseUrl;
    
    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key', env('OPENAI_API_KEY'));
        $this->baseUrl = config('services.openai.base_url', 'https://api.openai.com/v1');
    }
    
    /**
     * Generate AI content for student reports
     */
    public function generateReportContent(array $studentData, array $metrics): array
    {
        try {
            // Generate different types of content
            $content = [
                'favorite_concept' => $this->generateFavoriteConcept($studentData, $metrics),
                'challenges_overcome' => $this->generateChallengesOvercome($studentData, $metrics),
                'special_achievements' => $this->generateSpecialAchievements($studentData, $metrics),
                'areas_for_growth' => $this->generateAreasForGrowth($studentData, $metrics),
                'next_steps' => $this->generateNextSteps($studentData, $metrics),
                'parent_feedback' => $this->generateParentFeedback($studentData, $metrics),
            ];
            
            return $content;
            
        } catch (\Exception $e) {
            Log::error('Real AI service failed, falling back to templates', [
                'error' => $e->getMessage(),
                'student_name' => $studentData['name'] ?? 'Unknown'
            ]);
            
            // Fallback to template-based generation
            return $this->generateFallbackContent($metrics);
        }
    }
    
    /**
     * Generate favorite coding concept using AI
     */
    private function generateFavoriteConcept(array $studentData, array $metrics): string
    {
        $prompt = $this->buildPrompt('favorite_concept', $studentData, $metrics);
        return $this->callAI($prompt, 'concept');
    }
    
    /**
     * Generate challenges overcome using AI
     */
    private function generateChallengesOvercome(array $studentData, array $metrics): string
    {
        $prompt = $this->buildPrompt('challenges_overcome', $studentData, $metrics);
        return $this->callAI($prompt, 'challenges');
    }
    
    /**
     * Generate special achievements using AI
     */
    private function generateSpecialAchievements(array $studentData, array $metrics): string
    {
        $prompt = $this->buildPrompt('special_achievements', $studentData, $metrics);
        return $this->callAI($prompt, 'achievements');
    }
    
    /**
     * Generate areas for growth using AI
     */
    private function generateAreasForGrowth(array $studentData, array $metrics): string
    {
        $prompt = $this->buildPrompt('areas_for_growth', $studentData, $metrics);
        return $this->callAI($prompt, 'growth');
    }
    
    /**
     * Generate next steps using AI
     */
    private function generateNextSteps(array $studentData, array $metrics): string
    {
        $prompt = $this->buildPrompt('next_steps', $studentData, $metrics);
        return $this->callAI($prompt, 'steps');
    }
    
    /**
     * Generate parent feedback using AI
     */
    private function generateParentFeedback(array $studentData, array $metrics): string
    {
        $prompt = $this->buildPrompt('parent_feedback', $studentData, $metrics);
        return $this->callAI($prompt, 'feedback');
    }
    
    /**
     * Build AI prompts based on content type and student data
     */
    private function buildPrompt(string $contentType, array $studentData, array $metrics): string
    {
        $studentName = $studentData['name'] ?? 'Student';
        $clubName = $studentData['club_name'] ?? 'Code Club';
        $performanceLevel = $metrics['performance_level'] ?? 'satisfactory';
        $averageScore = $metrics['average_score'] ?? 0;
        $attendancePercentage = $metrics['attendance_percentage'] ?? 0;
        
        $basePrompt = "You are an experienced coding instructor writing a personalized report for a student in a code club. ";
        $basePrompt .= "Student: {$studentName}, Club: {$clubName}, Performance Level: {$performanceLevel}, ";
        $basePrompt .= "Average Score: {$averageScore}%, Attendance: {$attendancePercentage}%. ";
        
        switch ($contentType) {
            case 'favorite_concept':
                return $basePrompt . "Generate a specific coding concept or skill that this student particularly enjoys or excels at. Be specific and technical. Keep it under 50 words.";
                
            case 'challenges_overcome':
                return $basePrompt . "Describe the specific coding challenges this student has overcome this term. Be specific about programming concepts, projects, or skills they mastered. Keep it under 100 words.";
                
            case 'special_achievements':
                return $basePrompt . "List 1-2 specific achievements or accomplishments this student has made in coding. Be creative but realistic. Keep it under 80 words.";
                
            case 'areas_for_growth':
                return $basePrompt . "Identify 1-2 specific areas where this student can improve their coding skills. Be constructive and specific. Keep it under 80 words.";
                
            case 'next_steps':
                return $basePrompt . "Suggest 1-2 specific next steps or goals for this student's coding journey. Be encouraging and specific. Keep it under 80 words.";
                
            case 'parent_feedback':
                return $basePrompt . "Write a personalized message for parents about their child's progress in coding. Be encouraging, specific, and professional. Keep it under 120 words.";
                
            default:
                return $basePrompt . "Generate personalized content for this student. Keep it under 100 words.";
        }
    }
    
    /**
     * Call AI API with prompt and return response
     */
    private function callAI(string $prompt, string $contentType): string
    {
        // Check cache first
        $cacheKey = 'ai_content_' . md5($prompt);
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }
        
        // If no API key, use fallback
        if (empty($this->apiKey)) {
            return $this->generateFallbackContentSingle($contentType);
        }
        
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an experienced coding instructor writing personalized student reports. Be specific, encouraging, and professional.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 150,
                    'temperature' => 0.7,
                ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? '';
                
                // Cache the result for 1 hour
                Cache::put($cacheKey, $content, 3600);
                
                return trim($content);
            } else {
                Log::warning('AI API request failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return $this->generateFallbackContentSingle($contentType);
            }
            
        } catch (\Exception $e) {
            Log::error('AI API call failed', [
                'error' => $e->getMessage(),
                'prompt' => $prompt
            ]);
            
            return $this->generateFallbackContentSingle($contentType);
        }
    }
    
    /**
     * Generate fallback content when AI is unavailable
     */
    private function generateFallbackContentSingle($contentType): string
    {
        $fallbacks = [
            'concept' => 'Basic Programming Concepts',
            'challenges' => 'Successfully learned fundamental programming skills and completed coding projects.',
            'achievements' => 'Completed coding projects and demonstrated understanding of programming concepts.',
            'growth' => 'Continue practicing coding skills and exploring new programming concepts.',
            'steps' => 'Keep coding regularly and try more challenging projects.',
            'feedback' => 'Your child is making good progress in coding and showing enthusiasm for programming.'
        ];
        
        return $fallbacks[$contentType] ?? 'Great progress in coding this term!';
    }
    
    /**
     * Generate fallback content array for when AI service fails completely
     */
    private function generateFallbackContent(array $metrics): array
    {
        $performanceLevel = $metrics['performance_level'] ?? 'satisfactory';
        
        return [
            'favorite_concept' => $this->getFallbackConcept($performanceLevel),
            'challenges_overcome' => $this->getFallbackChallenges($performanceLevel),
            'special_achievements' => $this->getFallbackAchievements($performanceLevel),
            'areas_for_growth' => $this->getFallbackGrowth($performanceLevel),
            'next_steps' => $this->getFallbackSteps($performanceLevel),
            'parent_feedback' => $this->getFallbackFeedback($performanceLevel),
        ];
    }
    
    private function getFallbackConcept(string $level): string
    {
        $concepts = [
            'excellent' => 'Advanced Programming Concepts',
            'very_good' => 'Interactive Game Development',
            'good' => 'Sprite Animation and Movement',
            'satisfactory' => 'Basic Programming Blocks',
            'needs_improvement' => 'Getting Started with Coding'
        ];
        
        return $concepts[$level] ?? 'Programming Fundamentals';
    }
    
    private function getFallbackChallenges(string $level): string
    {
        $challenges = [
            'excellent' => 'Mastered complex coding concepts and created sophisticated interactive projects.',
            'very_good' => 'Successfully tackled challenging coding problems and created engaging projects.',
            'good' => 'Overcame various coding challenges and completed functional projects.',
            'satisfactory' => 'Worked through basic coding challenges with steady progress.',
            'needs_improvement' => 'Showed determination in learning fundamental coding concepts.'
        ];
        
        return $challenges[$level] ?? 'Made progress in understanding programming concepts.';
    }
    
    private function getFallbackAchievements(string $level): string
    {
        $achievements = [
            'excellent' => 'Outstanding coding projects and peer mentorship recognition.',
            'very_good' => 'Creative problem-solving and innovative project development.',
            'good' => 'Successful completion of coding challenges and projects.',
            'satisfactory' => 'Steady progress in programming skills and project completion.',
            'needs_improvement' => 'Persistent effort in learning coding fundamentals.'
        ];
        
        return $achievements[$level] ?? 'Continued progress in programming skills.';
    }
    
    private function getFallbackGrowth(string $level): string
    {
        $growth = [
            'excellent' => 'Explore advanced programming languages and complex algorithms.',
            'very_good' => 'Develop more sophisticated projects and mentor other students.',
            'good' => 'Try more challenging coding projects and advanced concepts.',
            'satisfactory' => 'Continue practicing coding skills and explore new projects.',
            'needs_improvement' => 'Focus on mastering basic programming concepts and regular practice.'
        ];
        
        return $growth[$level] ?? 'Continue practicing and exploring new coding concepts.';
    }
    
    private function getFallbackSteps(string $level): string
    {
        $steps = [
            'excellent' => 'Lead coding workshops and develop advanced projects.',
            'very_good' => 'Create portfolio projects and explore new programming languages.',
            'good' => 'Build more complex projects and learn advanced coding techniques.',
            'satisfactory' => 'Practice coding regularly and complete more challenging projects.',
            'needs_improvement' => 'Focus on consistent practice and mastering basic concepts.'
        ];
        
        return $steps[$level] ?? 'Keep coding regularly and try new projects.';
    }
    
    private function getFallbackFeedback(string $level): string
    {
        $feedback = [
            'excellent' => 'Your child demonstrates exceptional coding talent and leadership. They excel at complex programming challenges and help other students.',
            'very_good' => 'Your child shows strong coding skills and creativity. They tackle challenging projects with enthusiasm and skill.',
            'good' => 'Your child is making solid progress in coding. They complete projects successfully and show good understanding.',
            'satisfactory' => 'Your child is developing coding skills steadily. With continued practice, they will continue to improve.',
            'needs_improvement' => 'Your child is learning coding fundamentals. With consistent practice and support, they will build confidence.'
        ];
        
        return $feedback[$level] ?? 'Your child is making good progress in coding this term.';
    }
}
