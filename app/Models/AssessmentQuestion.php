<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'question_type',
        'question_text',
        'question_options',
        'correct_answer',
        'project_instructions',
        'project_requirements',
        'project_output_format',
        'image_description',
        'points',
        'order'
    ];

    protected $casts = [
        'question_options' => 'array',
        'project_requirements' => 'array',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    // Question type constants
    const TYPE_MULTIPLE_CHOICE = 'multiple_choice';
    const TYPE_PRACTICAL_PROJECT = 'practical_project';
    const TYPE_IMAGE_QUESTION = 'image_question';
    const TYPE_TEXT_QUESTION = 'text_question';

    // Project output format constants
    const OUTPUT_SCRATCH_PROJECT = 'scratch_project';
    const OUTPUT_PYTHON_FILE = 'python_file';
    const OUTPUT_HTML_FILE = 'html_file';
    const OUTPUT_JAVASCRIPT_FILE = 'javascript_file';
    const OUTPUT_MOBILE_APP = 'mobile_app';
    const OUTPUT_ROBOTICS_PROJECT = 'robotics_project';

    public function getQuestionTypeLabelAttribute(): string
    {
        return match($this->question_type) {
            self::TYPE_MULTIPLE_CHOICE => 'Multiple Choice',
            self::TYPE_PRACTICAL_PROJECT => 'Practical Project',
            self::TYPE_IMAGE_QUESTION => 'Image Question',
            self::TYPE_TEXT_QUESTION => 'Text Question',
            default => 'Unknown'
        };
    }

    public function getProjectOutputFormatLabelAttribute(): string
    {
        return match($this->project_output_format) {
            self::OUTPUT_SCRATCH_PROJECT => 'Scratch Project',
            self::OUTPUT_PYTHON_FILE => 'Python File',
            self::OUTPUT_HTML_FILE => 'HTML File',
            self::OUTPUT_JAVASCRIPT_FILE => 'JavaScript File',
            self::OUTPUT_MOBILE_APP => 'Mobile App',
            self::OUTPUT_ROBOTICS_PROJECT => 'Robotics Project',
            default => 'Unknown'
        };
    }
}