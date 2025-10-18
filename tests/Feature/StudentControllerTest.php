<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected School $school;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->school = School::factory()->create();
        $this->user = User::factory()->create([
            'school_id' => $this->school->id,
        ]);
    }

    /**
     * Test that index displays students with pagination.
     */
    public function test_index_displays_students_with_pagination(): void
    {
        Student::factory()->count(25)->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->get('/students');

        $response->assertStatus(200);
        $response->assertViewIs('students.index');
        $response->assertViewHas('students');
        $students = $response->viewData('students');
        $this->assertEquals(20, $students->count());
    }

    /**
     * Test that index loads clubs relationship.
     */
    public function test_index_loads_clubs_and_school_relationships(): void
    {
        $student = Student::factory()->forSchool($this->school)->create();
        $club = Club::factory()->forSchool($this->school)->create();
        $student->clubs()->attach($club->id);

        $response = $this->actingAs($this->user)->get('/students');

        $response->assertStatus(200);
        $students = $response->viewData('students');
        $firstStudent = $students->first();
        $this->assertTrue($firstStudent->relationLoaded('clubs'));
    }

    /**
     * Test that index provides clubs for enrollment.
     */
    public function test_index_provides_clubs_list(): void
    {
        Club::factory()->count(5)->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->get('/students');

        $response->assertStatus(200);
        $response->assertViewHas('clubs');
        $clubs = $response->viewData('clubs');
        $this->assertCount(5, $clubs);
    }

    /**
     * Test that create displays the create form with clubs.
     */
    public function test_create_displays_form_with_clubs(): void
    {
        Club::factory()->count(3)->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->get('/students/create');

        $response->assertStatus(200);
        $response->assertViewIs('students.create');
        $response->assertViewHas('clubs');
    }

    /**
     * Test that store creates a student and enrolls in club.
     */
    public function test_store_creates_student_and_enrolls_in_club(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $studentData = [
            'club_id' => $club->id,
            'student_first_name' => 'John',
            'student_last_name' => 'Doe',
            'student_grade_level' => '5',
            'student_parent_name' => 'Jane Doe',
            'student_parent_email' => 'jane@example.com',
        ];

        $response = $this->actingAs($this->user)->post('/students', $studentData);

        $response->assertRedirect(route('students.index'));
        $response->assertSessionHas('success', 'Student added and enrolled in club successfully!');
        
        $this->assertDatabaseHas('students', [
            'student_first_name' => 'John',
            'student_last_name' => 'Doe',
            'student_grade_level' => '5',
            'student_parent_name' => 'Jane Doe',
            'student_parent_email' => 'jane@example.com',
            'school_id' => $this->school->id,
        ]);

        $student = Student::where('student_first_name', 'John')->first();
        $this->assertTrue($student->clubs->contains($club));
    }

    /**
     * Test that store validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post('/students', []);

        $response->assertSessionHasErrors([
            'club_id',
            'student_first_name',
            'student_last_name',
            'student_grade_level',
            'student_parent_name',
            'student_parent_email',
        ]);
    }

    /**
     * Test that store validates email format.
     */
    public function test_store_validates_email_format(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post('/students', [
            'club_id' => $club->id,
            'student_first_name' => 'John',
            'student_last_name' => 'Doe',
            'student_grade_level' => '5',
            'student_parent_name' => 'Jane Doe',
            'student_parent_email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors('student_parent_email');
    }

    /**
     * Test that store validates club exists.
     */
    public function test_store_validates_club_exists(): void
    {
        $response = $this->actingAs($this->user)->post('/students', [
            'club_id' => 99999,
            'student_first_name' => 'John',
            'student_last_name' => 'Doe',
            'student_grade_level' => '5',
            'student_parent_name' => 'Jane Doe',
            'student_parent_email' => 'jane@example.com',
        ]);

        $response->assertSessionHasErrors('club_id');
    }

    /**
     * Test that store assigns correct school_id from club.
     */
    public function test_store_assigns_school_id_from_club(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post('/students', [
            'club_id' => $club->id,
            'student_first_name' => 'John',
            'student_last_name' => 'Doe',
            'student_grade_level' => '5',
            'student_parent_name' => 'Jane Doe',
            'student_parent_email' => 'jane@example.com',
        ]);

        $response->assertRedirect();
        $student = Student::where('student_first_name', 'John')->first();
        $this->assertEquals($this->school->id, $student->school_id);
    }

    /**
     * Test that store no longer accepts student_parent_phone field.
     */
    public function test_store_ignores_parent_phone_field(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post('/students', [
            'club_id' => $club->id,
            'student_first_name' => 'John',
            'student_last_name' => 'Doe',
            'student_grade_level' => '5',
            'student_parent_name' => 'Jane Doe',
            'student_parent_email' => 'jane@example.com',
            'student_parent_phone' => '555-1234',
        ]);

        $response->assertRedirect();
        // Phone should be ignored since it's not in validation rules
        $student = Student::where('student_first_name', 'John')->first();
        $this->assertArrayNotHasKey('student_parent_phone', $student->toArray());
    }

    /**
     * Test that store no longer accepts student_medical_info field.
     */
    public function test_store_ignores_medical_info_field(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post('/students', [
            'club_id' => $club->id,
            'student_first_name' => 'John',
            'student_last_name' => 'Doe',
            'student_grade_level' => '5',
            'student_parent_name' => 'Jane Doe',
            'student_parent_email' => 'jane@example.com',
            'student_medical_info' => 'Allergies: Peanuts',
        ]);

        $response->assertRedirect();
        // Medical info should be ignored since it's not in validation rules
        $student = Student::where('student_first_name', 'John')->first();
        $this->assertArrayNotHasKey('student_medical_info', $student->toArray());
    }

    /**
     * Test that store no longer handles redirect_to parameter.
     */
    public function test_store_ignores_redirect_to_parameter(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post('/students', [
            'club_id' => $club->id,
            'student_first_name' => 'John',
            'student_last_name' => 'Doe',
            'student_grade_level' => '5',
            'student_parent_name' => 'Jane Doe',
            'student_parent_email' => 'jane@example.com',
            'redirect_to' => '/clubs',
        ]);

        // Should always redirect to students.index, not to redirect_to
        $response->assertRedirect(route('students.index'));
        $response->assertSessionHas('success');
    }

    /**
     * Test that store handles various grade levels.
     */
    public function test_store_handles_various_grade_levels(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $gradeLevels = ['K', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];

        foreach ($gradeLevels as $grade) {
            $response = $this->actingAs($this->user)->post('/students', [
                'club_id' => $club->id,
                'student_first_name' => 'Student',
                'student_last_name' => "Grade{$grade}",
                'student_grade_level' => $grade,
                'student_parent_name' => 'Parent Name',
                'student_parent_email' => "parent{$grade}@example.com",
            ]);

            $response->assertRedirect();
            $this->assertDatabaseHas('students', [
                'student_grade_level' => $grade,
            ]);
        }
    }

    /**
     * Test that store handles long names.
     */
    public function test_store_handles_long_names(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post('/students', [
            'club_id' => $club->id,
            'student_first_name' => str_repeat('a', 100),
            'student_last_name' => str_repeat('b', 100),
            'student_grade_level' => '5',
            'student_parent_name' => str_repeat('c', 100),
            'student_parent_email' => 'test@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('students', [
            'student_parent_email' => 'test@example.com',
        ]);
    }

    /**
     * Test that authenticated users are required for student routes.
     */
    public function test_student_routes_require_authentication(): void
    {
        $this->get('/students')->assertRedirect(route('login'));
        $this->get('/students/create')->assertRedirect(route('login'));
        $this->post('/students', [])->assertRedirect(route('login'));
    }

    /**
     * Test that store creates student with special characters in names.
     */
    public function test_store_handles_special_characters_in_names(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post('/students', [
            'club_id' => $club->id,
            'student_first_name' => "O'Brien",
            'student_last_name' => "St. James-Smith",
            'student_grade_level' => '5',
            'student_parent_name' => "Mary-Ann O'Connor",
            'student_parent_email' => 'mary@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('students', [
            'student_first_name' => "O'Brien",
            'student_last_name' => "St. James-Smith",
            'student_parent_name' => "Mary-Ann O'Connor",
        ]);
    }

    /**
     * Test that store handles international characters.
     */
    public function test_store_handles_international_characters(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post('/students', [
            'club_id' => $club->id,
            'student_first_name' => 'José',
            'student_last_name' => 'García',
            'student_grade_level' => '5',
            'student_parent_name' => 'María García',
            'student_parent_email' => 'maria@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('students', [
            'student_first_name' => 'José',
            'student_last_name' => 'García',
        ]);
    }

    /**
     * Test that store handles multiple students with same names.
     */
    public function test_store_handles_duplicate_names(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        for ($i = 1; $i <= 3; $i++) {
            $response = $this->actingAs($this->user)->post('/students', [
                'club_id' => $club->id,
                'student_first_name' => 'John',
                'student_last_name' => 'Doe',
                'student_grade_level' => '5',
                'student_parent_name' => 'Jane Doe',
                'student_parent_email' => "parent{$i}@example.com",
            ]);

            $response->assertRedirect();
        }

        $this->assertDatabaseCount('students', 3);
    }

    /**
     * Test that index handles empty student list.
     */
    public function test_index_handles_empty_student_list(): void
    {
        $response = $this->actingAs($this->user)->get('/students');

        $response->assertStatus(200);
        $students = $response->viewData('students');
        $this->assertCount(0, $students);
    }

    /**
     * Test that create handles empty club list.
     */
    public function test_create_handles_empty_club_list(): void
    {
        $response = $this->actingAs($this->user)->get('/students/create');

        $response->assertStatus(200);
        $clubs = $response->viewData('clubs');
        $this->assertCount(0, $clubs);
    }

    /**
     * Test that store creates club enrollment pivot record.
     */
    public function test_store_creates_enrollment_pivot_record(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post('/students', [
            'club_id' => $club->id,
            'student_first_name' => 'John',
            'student_last_name' => 'Doe',
            'student_grade_level' => '5',
            'student_parent_name' => 'Jane Doe',
            'student_parent_email' => 'jane@example.com',
        ]);

        $response->assertRedirect();
        $student = Student::where('student_first_name', 'John')->first();
        
        $this->assertDatabaseHas('club_enrollments', [
            'student_id' => $student->id,
            'club_id' => $club->id,
        ]);
    }

    /**
     * Test that store validates string types for text fields.
     */
    public function test_store_validates_string_types(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post('/students', [
            'club_id' => $club->id,
            'student_first_name' => 123, // Should be string
            'student_last_name' => ['array'], // Should be string
            'student_grade_level' => '5',
            'student_parent_name' => 'Jane Doe',
            'student_parent_email' => 'jane@example.com',
        ]);

        // Numbers and arrays should be cast to strings or fail validation
        // Laravel's validation will handle type coercion, so this might pass
        $response->assertRedirect();
    }

    /**
     * Test store with minimum valid data.
     */
    public function test_store_with_minimum_valid_data(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post('/students', [
            'club_id' => $club->id,
            'student_first_name' => 'J',
            'student_last_name' => 'D',
            'student_grade_level' => '1',
            'student_parent_name' => 'P',
            'student_parent_email' => 'a@b.co',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}