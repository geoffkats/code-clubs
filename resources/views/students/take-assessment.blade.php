@extends('layouts.student')

@section('page-title', 'Take Assessment')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Assessment Header -->
        <div class="bg-white rounded-xl shadow-lg mb-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $assessment->assessment_name }}</h1>
                        <p class="text-gray-600 mt-1">{{ $assessment->club->club_name }}</p>
                        <div class="flex items-center mt-2 space-x-4 text-sm text-gray-500">
                            <span>{{ $assessment->questions->count() }} questions</span>
                            <span>{{ $assessment->total_points ?? $assessment->questions->sum('points') }} total points</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($assessment->assessment_type) }}
                            </span>
                        </div>
                    </div>
                    @if($assessment->due_date)
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-700">Due Date</p>
                            <p class="text-sm {{ $assessment->due_date < now() ? 'text-red-600' : 'text-gray-600' }}">
                                {{ \Carbon\Carbon::parse($assessment->due_date)->format('M d, Y g:i A') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
            
            @if($assessment->description)
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Instructions</h3>
                    <p class="text-gray-700">{{ $assessment->description }}</p>
                </div>
            @endif
        </div>

        <!-- Assessment Form -->
        <form method="POST" action="{{ route('student.assessment.submit', $assessment->id) }}" class="space-y-8">
            @csrf
            
            @foreach($assessment->questions as $index => $question)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Question {{ $index + 1 }}
                            <span class="text-sm font-normal text-gray-500">({{ $question->points }} points)</span>
                        </h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                        </span>
                    </div>

                    <!-- Question Text -->
                    <div class="mb-6">
                        <p class="text-gray-700 text-lg leading-relaxed">{{ $question->question_text }}</p>
                    </div>

                    <!-- Image Question -->
                    @if($question->question_type === 'image_question' && $question->image_url)
                        <div class="mb-6">
                            <img src="{{ asset('storage/' . $question->image_url) }}" 
                                 alt="Question Image" 
                                 class="max-w-full h-auto rounded-lg border border-gray-200">
                        </div>
                    @endif

                    <!-- Answer Input based on Question Type -->
                    <div class="space-y-4">
                        @switch($question->question_type)
                            @case('multiple_choice')
                                @php
                                    $options = json_decode($question->options, true) ?? [];
                                @endphp
                                <div class="space-y-3">
                                    @foreach($options as $key => $option)
                                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <input type="radio" 
                                                   name="answers[{{ $question->id }}]" 
                                                   value="{{ $key }}" 
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                            <span class="ml-3 text-gray-700">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @break

                            @case('practical_project')
                                <div class="space-y-4">
                                    @if($question->project_instructions)
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                            <h4 class="font-medium text-blue-900 mb-2">Project Instructions:</h4>
                                            <p class="text-blue-800 text-sm">{{ $question->project_instructions }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($question->project_requirements)
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                            <h4 class="font-medium text-yellow-900 mb-2">Requirements:</h4>
                                            <p class="text-yellow-800 text-sm">{{ $question->project_requirements }}</p>
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <label for="answer_{{ $question->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                            Describe your project or paste your code:
                                        </label>
                                        <textarea name="answers[{{ $question->id }}]" 
                                                  id="answer_{{ $question->id }}"
                                                  rows="6" 
                                                  required
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Describe what you created, paste your code, or explain your approach..."></textarea>
                                    </div>
                                </div>
                                @break

                            @case('image_question')
                                <div class="space-y-4">
                                    <div>
                                        <label for="answer_{{ $question->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                            Answer based on the image above:
                                        </label>
                                        <textarea name="answers[{{ $question->id }}]" 
                                                  id="answer_{{ $question->id }}"
                                                  rows="4" 
                                                  required
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Describe what you see in the image or answer the question..."></textarea>
                                    </div>
                                </div>
                                @break

                            @case('text_question')
                            @default
                                <div>
                                    <label for="answer_{{ $question->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                        Your Answer:
                                    </label>
                                    <textarea name="answers[{{ $question->id }}]" 
                                              id="answer_{{ $question->id }}"
                                              rows="4" 
                                              required
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                              placeholder="Type your answer here..."></textarea>
                                </div>
                        @endswitch
                    </div>
                </div>
            @endforeach

            <!-- Submit Button -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <p>Make sure to review all your answers before submitting.</p>
                        <p class="mt-1">You cannot change your answers after submission.</p>
                    </div>
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to submit this assessment? You cannot change your answers after submission.')"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Submit Assessment
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-save functionality (optional)
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, textarea');
    
    // Save to localStorage every 30 seconds
    setInterval(function() {
        const formData = new FormData(form);
        const answers = {};
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('answers[')) {
                answers[key] = value;
            }
        }
        localStorage.setItem('assessment_{{ $assessment->id }}', JSON.stringify(answers));
    }, 30000);
    
    // Load saved answers on page load
    const savedAnswers = localStorage.getItem('assessment_{{ $assessment->id }}');
    if (savedAnswers) {
        try {
            const answers = JSON.parse(savedAnswers);
            Object.keys(answers).forEach(key => {
                const input = document.querySelector(`[name="${key}"]`);
                if (input) {
                    if (input.type === 'radio') {
                        if (input.value === answers[key]) {
                            input.checked = true;
                        }
                    } else {
                        input.value = answers[key];
                    }
                }
            });
        } catch (e) {
            console.log('Could not load saved answers');
        }
    }
    
    // Clear saved answers on successful submission
    form.addEventListener('submit', function() {
        localStorage.removeItem('assessment_{{ $assessment->id }}');
    });
});
</script>
@endsection
