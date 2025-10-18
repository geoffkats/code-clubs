<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use App\Models\School;
use App\Models\Club;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentsDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_route_exists_and_requires_authentication(): void
    {
        $school = School::factory()->create();
        $student = Student::factory()->create(['school_id' => $school->id]);

        $response = $this->get(route('students.dashboard', ['student_id' => $student->id]));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_student_dashboard(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $school = School::factory()->create();
        $club = Club::factory()->create(['school_id' => $school->id]);

        $student = Student::factory()->create([
            'school_id' => $school->id,
            'student_first_name' => 'Ada',
            'student_last_name' => 'Lovelace',
        ]);
        $student->clubs()->attach($club->id);

        $response = $this->get(route('students.dashboard', ['student_id' => $student->id]));
        $response->assertStatus(200);
        $response->assertSeeText('Student Dashboard');
        $response->assertSeeText('Ada');
    }
}
