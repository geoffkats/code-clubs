<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
	use HasFactory;

	protected $fillable = [
		'school_name',
		'address',
		'contact_email',
	];

	public function users(): HasMany
	{
		return $this->hasMany(User::class);
	}

	public function students(): HasMany
	{
		return $this->hasMany(Student::class);
	}

	public function clubs(): HasMany
	{
		return $this->hasMany(Club::class);
	}

	// Accessor for clubs count
	public function getClubsCountAttribute(): int
	{
		return $this->clubs()->count();
	}

	// Accessor for students count
	public function getStudentsCountAttribute(): int
	{
		return $this->students()->count();
	}
}


