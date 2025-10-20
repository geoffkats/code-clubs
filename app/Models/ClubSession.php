<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'teacher_id',
        'session_date',
        'session_time',
        'session_notes'
    ];

    protected $casts = [
        'session_date' => 'date',
        'session_time' => 'datetime:H:i',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the club this session belongs to
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Get the teacher who created this session
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get all proofs uploaded for this session
     */
    public function proofs()
    {
        return $this->hasMany(SessionProof::class, 'session_id');
    }

    /**
     * Get all students who attended this session
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'session_attendance', 'club_session_id', 'student_id')
            ->withPivot(['attended_at', 'notes'])
            ->withTimestamps();
    }

    /**
     * Get attendance records for this session
     */
    public function attendance()
    {
        return $this->hasMany(SessionAttendance::class, 'club_session_id');
    }

    /**
     * Get the number of students who attended this session
     */
    public function getStudentCountAttribute()
    {
        return $this->attendance()->count();
    }

    /**
     * Scope sessions by teacher
     */
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope sessions by club
     */
    public function scopeByClub($query, $clubId)
    {
        return $query->where('club_id', $clubId);
    }

    /**
     * Scope sessions by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('session_date', [$startDate, $endDate]);
    }
}
