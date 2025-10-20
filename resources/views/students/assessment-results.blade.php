@extends('layouts.student')

@section('page-title', 'Assessment Results')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results Header -->
        <div class="bg-white rounded-xl shadow-lg mb-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $assessment->assessment_name }}</h1>
                        <p class="text-gray-600 mt-1">{{ $assessment->club->club_name }}</p>
                        <p class="text-sm text-gray-500 mt-2">
                            Completed on {{ $existingScore->created_at->format('M d, Y \a\t g:i A') }}
                        </p>
                    </div>
                    <div class="text-right">
                        @php
                            $percentage = ($existingScore->score_value / $existingScore->score_max_value) * 100;
                            $scoreColor = $percentage >= 80 ? 'green' : ($percentage >= 60 ? 'yellow' : 'red');
                        @endphp
                        <div class="text-4xl font-bold text-{{ $scoreColor }}-600 mb-2">
                            {{ number_format($percentage, 1) }}%
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $existingScore->score_value }}/{{ $existingScore->score_max_value }} points
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Score Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Questions</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $assessment->questions->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Score</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $existingScore->score_value }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Max Points</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $existingScore->score_max_value }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Feedback -->
        <div class="bg-white rounded-xl shadow-lg mb-8">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Performance Feedback</h2>
                <div class="bg-{{ $scoreColor }}-50 border border-{{ $scoreColor }}-200 rounded-lg p-4">
                    @if($percentage >= 90)
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <h3 class="font-semibold text-green-800">Excellent Work!</h3>
                        </div>
                        <p class="text-green-700">Outstanding performance! You've demonstrated excellent understanding of the concepts. Keep up the great work!</p>
                    @elseif($percentage >= 80)
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <h3 class="font-semibold text-green-800">Great Job!</h3>
                        </div>
                        <p class="text-green-700">Very good performance! You've shown strong understanding of the material. Continue practicing to achieve even better results.</p>
                    @elseif($percentage >= 70)
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <h3 class="font-semibold text-yellow-800">Good Effort!</h3>
                        </div>
                        <p class="text-yellow-700">Good work! You're on the right track. Review the material and practice more to improve your understanding.</p>
                    @elseif($percentage >= 60)
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <h3 class="font-semibold text-yellow-800">Keep Practicing!</h3>
                        </div>
                        <p class="text-yellow-700">You're making progress! Focus on understanding the concepts better and don't hesitate to ask for help when needed.</p>
                    @else
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <h3 class="font-semibold text-red-800">More Practice Needed</h3>
                        </div>
                        <p class="text-red-700">Don't worry! Everyone learns at their own pace. Review the material thoroughly and consider seeking additional help from your instructor.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Question Breakdown -->
        <div class="bg-white rounded-xl shadow-lg mb-8">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Question Breakdown</h2>
                <div class="space-y-6">
                    @foreach($assessment->questions as $index => $question)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="font-medium text-gray-900">
                                    Question {{ $index + 1 }}
                                    <span class="text-sm font-normal text-gray-500">({{ $question->points }} points)</span>
                                </h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                                </span>
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-gray-700">{{ $question->question_text }}</p>
                            </div>
                            
                            @if($question->question_type === 'image_question' && $question->image_url)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $question->image_url) }}" 
                                         alt="Question Image" 
                                         class="max-w-sm h-auto rounded border border-gray-200">
                                </div>
                            @endif
                            
                            @php
                                $studentAnswers = is_array($existingScore->student_answers) ? $existingScore->student_answers : (json_decode($existingScore->student_answers, true) ?? []);
                                $studentAnswer = $studentAnswers[$question->id] ?? $studentAnswers['question_' . $question->id] ?? 'No answer provided';
                                $isCorrect = false;
                                
                                // Check if answer is correct
                                if ($question->question_type === 'multiple_choice') {
                                    $isCorrect = $studentAnswer === $question->correct_answer;
                                } elseif ($question->question_type === 'text_question') {
                                    // For text questions, we'll consider it correct if it's not empty and has reasonable length
                                    $isCorrect = !empty($studentAnswer) && strlen($studentAnswer) > 10;
                                }
                            @endphp
                            
                            <!-- Student's Answer -->
                            <div class="mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-medium text-gray-700">Your Answer:</h4>
                                    <div class="flex items-center">
                                        @if($question->question_type !== 'practical_project' && $question->question_type !== 'image_question')
                                            @if($isCorrect)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Correct
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Incorrect
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                
                                @if($question->question_type === 'multiple_choice')
                                    @php
                                        $options = is_array($question->question_options) ? $question->question_options : (json_decode($question->question_options, true) ?? []);
                                        $selectedOption = $options[$studentAnswer] ?? $studentAnswer;
                                    @endphp
                                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-sm text-blue-800">
                                            <span class="font-medium">{{ $studentAnswer }}.</span> {{ $selectedOption }}
                                        </p>
                                    </div>
                                @else
                                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-sm text-blue-800 whitespace-pre-line">{{ $studentAnswer }}</p>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Correct Answer (for auto-graded questions) -->
                            @if($question->question_type === 'multiple_choice' || $question->question_type === 'text_question')
                                <div class="mb-3">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Correct Answer:</h4>
                                    @if($question->question_type === 'multiple_choice')
                                        @php
                                            $correctOption = $options[$question->correct_answer] ?? $question->correct_answer;
                                        @endphp
                                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                            <p class="text-sm text-green-800">
                                                <span class="font-medium">{{ $question->correct_answer }}.</span> {{ $correctOption }}
                                            </p>
                                        </div>
                                    @else
                                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                            <p class="text-sm text-green-800">{{ $question->correct_answer }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Points:</span> {{ $question->points }}
                                </p>
                                @if($question->question_type === 'practical_project')
                                    @if($question->project_instructions)
                                        <p class="text-sm text-gray-600 mt-1">
                                            <span class="font-medium">Instructions:</span> {{ $question->project_instructions }}
                                        </p>
                                    @endif
                                    @if($question->project_requirements)
                                        <p class="text-sm text-gray-600 mt-1">
                                            <span class="font-medium">Requirements:</span> {{ is_array($question->project_requirements) ? implode(', ', $question->project_requirements) : $question->project_requirements }}
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">What's Next?</h3>
                    <p class="text-gray-600 mt-1">Keep learning and improving your coding skills!</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('student.assignments') }}" 
                       class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors font-medium">
                        Back to Assignments
                    </a>
                    <a href="{{ route('student.progress') }}" 
                       class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        View Progress
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
