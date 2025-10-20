<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionAttendance extends Model
{
    use HasFactory;

    protected $table = 'session_attendance';
    
    protected $fillable = [
        'club_session_id',
        'student_id',
        'attended_at',
        'notes'
    ];

    protected $casts = [
        'attended_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the session this attendance record belongs to
     */
    public function session()
    {
        return $this->belongsTo(ClubSession::class, 'club_session_id');
    }

    /**
     * Get the student who attended
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Scope attendance by session
     */
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('club_session_id', $sessionId);
    }

    /**
     * Scope attendance by student
     */
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope attendance by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attended_at', [$startDate, $endDate]);
    }
}
