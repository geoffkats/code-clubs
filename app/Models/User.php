<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_name',
        'user_role',
        'school_id',
        'facilitator_id',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factory_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class);
    }

    /**
     * Get the facilitator this teacher reports to
     */
    public function facilitator()
    {
        return $this->belongsTo(User::class, 'facilitator_id');
    }

    /**
     * Get all teachers under this facilitator
     */
    public function teachers()
    {
        return $this->hasMany(User::class, 'facilitator_id');
    }

    /**
     * Get clubs this user teaches (for teachers)
     */
    public function clubsAsTeacher()
    {
        return $this->belongsToMany(Club::class, 'club_teacher', 'teacher_id', 'club_id');
    }

    /**
     * Get clubs this facilitator manages
     */
    public function managedClubs()
    {
        return $this->hasMany(Club::class, 'facilitator_id');
    }

    /**
     * Get resources created by this user
     */
    public function createdResources()
    {
        return $this->hasMany(LessonNote::class, 'created_by');
    }

    /**
     * Get proofs uploaded by this user
     */
    public function uploadedProofs()
    {
        return $this->hasMany(SessionProof::class, 'uploaded_by');
    }

    /**
     * Get sessions this user attended (for students)
     */
    public function attendedSessions()
    {
        return $this->belongsToMany(ClubSession::class, 'session_attendance', 'student_id', 'club_session_id');
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the user's profile image URL
     */
    public function getProfileImageUrlAttribute(): string
    {
        return $this->profile_image 
            ? asset('storage/' . $this->profile_image)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Scope users by role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('user_role', $role);
    }

    /**
     * Scope to get students
     */
    public function scopeStudents($query)
    {
        return $query->where('user_role', 'student');
    }

    /**
     * Scope to get teachers
     */
    public function scopeTeachers($query)
    {
        return $query->where('user_role', 'teacher');
    }

    /**
     * Scope to get facilitators
     */
    public function scopeFacilitators($query)
    {
        return $query->where('user_role', 'facilitator');
    }
}
