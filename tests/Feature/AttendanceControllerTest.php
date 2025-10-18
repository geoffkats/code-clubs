<?php

namespace Tests\Feature;

use App\Models\AttendanceRecord;
use App\Models\Club;
use App\Models\School;
use App\Models\SessionSchedule;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceControllerTest extends TestCase
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
     * Test that show_grid displays the attendance grid for a club.
     */
    public function test_show_grid_displays_attendance_view_with_club_and_students(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $students = Student::factory()->count(3)->forSchool($this->school)->create();
        $club->students()->attach($students->pluck('id'));

        $response = $this->actingAs($this->user)->get("/clubs/{$club->id}/attendance");

        $response->assertStatus(200);
        $response->assertViewIs('attendance.grid');
        $response->assertViewHas('club');
        $response->assertViewHas('week', 1);
    }

    /**
     * Test that show_grid loads students relationship correctly.
     */
    public function test_show_grid_loads_students_relationship(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $students = Student::factory()->count(5)->forSchool($this->school)->create();
        $club->students()->attach($students->pluck('id'));

        $response = $this->actingAs($this->user)->get("/clubs/{$club->id}/attendance");

        $response->assertStatus(200);
        $club = $response->viewData('club');
        $this->assertTrue($club->relationLoaded('students'));
        $this->assertCount(5, $club->students);
    }

    /**
     * Test that show_grid does not load school relationship (after refactor).
     */
    public function test_show_grid_does_not_load_school_relationship(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->get("/clubs/{$club->id}/attendance");

        $response->assertStatus(200);
        $club = $response->viewData('club');
        $this->assertFalse($club->relationLoaded('school'));
    }

    /**
     * Test that show_grid accepts week parameter.
     */
    public function test_show_grid_accepts_week_parameter(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->get("/clubs/{$club->id}/attendance?week=5");

        $response->assertStatus(200);
        $response->assertViewHas('week', 5);
    }

    /**
     * Test that show_grid defaults to week 1 when no week parameter provided.
     */
    public function test_show_grid_defaults_to_week_one(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->get("/clubs/{$club->id}/attendance");

        $response->assertStatus(200);
        $response->assertViewHas('week', 1);
    }

    /**
     * Test that show_grid retrieves existing session for the week.
     */
    public function test_show_grid_retrieves_existing_session_for_week(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $session = SessionSchedule::factory()
            ->forClub($club)
            ->forWeek(3)
            ->create();

        $response = $this->actingAs($this->user)->get("/clubs/{$club->id}/attendance?week=3");

        $response->assertStatus(200);
        $response->assertViewHas('session');
        $sessionFromView = $response->viewData('session');
        $this->assertEquals($session->id, $sessionFromView->id);
    }

    /**
     * Test that show_grid handles non-existent session gracefully.
     */
    public function test_show_grid_handles_non_existent_session(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->get("/clubs/{$club->id}/attendance?week=99");

        $response->assertStatus(200);
        $response->assertViewHas('session', null);
    }

    /**
     * Test that show_grid returns 404 for non-existent club.
     */
    public function test_show_grid_returns_404_for_non_existent_club(): void
    {
        $response = $this->actingAs($this->user)->get('/clubs/99999/attendance');

        $response->assertStatus(404);
    }

    /**
     * Test that index displays all clubs with pagination.
     */
    public function test_index_displays_clubs_with_pagination(): void
    {
        Club::factory()->count(25)->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->get('/attendance');

        $response->assertStatus(200);
        $response->assertViewIs('attendance.index');
        $response->assertViewHas('clubs');
        $clubs = $response->viewData('clubs');
        $this->assertEquals(20, $clubs->count());
    }

    /**
     * Test that index loads necessary relationships.
     */
    public function test_index_loads_school_students_and_counts(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $students = Student::factory()->count(3)->forSchool($this->school)->create();
        $club->students()->attach($students->pluck('id'));
        SessionSchedule::factory()->count(2)->forClub($club)->create();

        $response = $this->actingAs($this->user)->get('/attendance');

        $response->assertStatus(200);
        $clubs = $response->viewData('clubs');
        $firstClub = $clubs->first();
        $this->assertTrue($firstClub->relationLoaded('school'));
        $this->assertTrue($firstClub->relationLoaded('students'));
        $this->assertArrayHasKey('students_count', $firstClub->toArray());
        $this->assertArrayHasKey('sessions_count', $firstClub->toArray());
    }

    /**
     * Test that store creates attendance record successfully.
     */
    public function test_store_creates_attendance_record(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $student = Student::factory()->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();
        $club->students()->attach($student->id);

        $response = $this->actingAs($this->user)->post("/attendance/{$club->id}", [
            'student_id' => $student->id,
            'session_id' => $session->id,
            'status' => 'present',
            'notes' => 'Student attended on time',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Attendance recorded successfully!');
        $this->assertDatabaseHas('attendance_records', [
            'student_id' => $student->id,
            'session_id' => $session->id,
            'attendance_status' => 'present',
            'attendance_notes' => 'Student attended on time',
        ]);
    }

    /**
     * Test that store updates existing attendance record.
     */
    public function test_store_updates_existing_attendance_record(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $student = Student::factory()->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();
        
        $existingRecord = AttendanceRecord::factory()
            ->forSessionAndStudent($session, $student)
            ->absent()
            ->create();

        $response = $this->actingAs($this->user)->post("/attendance/{$club->id}", [
            'student_id' => $student->id,
            'session_id' => $session->id,
            'status' => 'present',
            'notes' => 'Marked present on correction',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('attendance_records', [
            'id' => $existingRecord->id,
            'attendance_status' => 'present',
            'attendance_notes' => 'Marked present on correction',
        ]);
        $this->assertDatabaseCount('attendance_records', 1);
    }

    /**
     * Test that store validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post("/attendance/{$club->id}", []);

        $response->assertSessionHasErrors(['student_id', 'session_id', 'status']);
    }

    /**
     * Test that store validates status enum values.
     */
    public function test_store_validates_status_enum_values(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $student = Student::factory()->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();

        $response = $this->actingAs($this->user)->post("/attendance/{$club->id}", [
            'student_id' => $student->id,
            'session_id' => $session->id,
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors('status');
    }

    /**
     * Test that store accepts all valid status values.
     */
    public function test_store_accepts_all_valid_status_values(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $student = Student::factory()->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();

        $validStatuses = ['present', 'absent', 'late', 'excused'];

        foreach ($validStatuses as $status) {
            $response = $this->actingAs($this->user)->post("/attendance/{$club->id}", [
                'student_id' => $student->id,
                'session_id' => $session->id,
                'status' => $status,
            ]);

            $response->assertRedirect();
            $this->assertDatabaseHas('attendance_records', [
                'student_id' => $student->id,
                'session_id' => $session->id,
                'attendance_status' => $status,
            ]);
        }
    }

    /**
     * Test that store validates notes max length.
     */
    public function test_store_validates_notes_max_length(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $student = Student::factory()->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();

        $response = $this->actingAs($this->user)->post("/attendance/{$club->id}", [
            'student_id' => $student->id,
            'session_id' => $session->id,
            'status' => 'present',
            'notes' => str_repeat('a', 501),
        ]);

        $response->assertSessionHasErrors('notes');
    }

    /**
     * Test that store accepts optional notes field.
     */
    public function test_store_accepts_optional_notes(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $student = Student::factory()->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();

        $response = $this->actingAs($this->user)->post("/attendance/{$club->id}", [
            'student_id' => $student->id,
            'session_id' => $session->id,
            'status' => 'present',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('attendance_records', [
            'student_id' => $student->id,
            'session_id' => $session->id,
        ]);
    }

    /**
     * Test that update modifies existing attendance record.
     */
    public function test_update_modifies_attendance_record(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $student = Student::factory()->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();
        $attendance = AttendanceRecord::factory()
            ->forSessionAndStudent($session, $student)
            ->present()
            ->create();

        $response = $this->actingAs($this->user)->put("/attendance/{$attendance->id}", [
            'status' => 'late',
            'notes' => 'Arrived 10 minutes late',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Attendance updated successfully!');
        $this->assertDatabaseHas('attendance_records', [
            'id' => $attendance->id,
            'attendance_status' => 'late',
            'attendance_notes' => 'Arrived 10 minutes late',
        ]);
    }

    /**
     * Test that update validates required fields.
     */
    public function test_update_validates_required_fields(): void
    {
        $attendance = AttendanceRecord::factory()->create();

        $response = $this->actingAs($this->user)->put("/attendance/{$attendance->id}", []);

        $response->assertSessionHasErrors('status');
    }

    /**
     * Test that update validates status values.
     */
    public function test_update_validates_status_values(): void
    {
        $attendance = AttendanceRecord::factory()->create();

        $response = $this->actingAs($this->user)->put("/attendance/{$attendance->id}", [
            'status' => 'invalid',
        ]);

        $response->assertSessionHasErrors('status');
    }

    /**
     * Test that update returns 404 for non-existent record.
     */
    public function test_update_returns_404_for_non_existent_record(): void
    {
        $response = $this->actingAs($this->user)->put('/attendance/99999', [
            'status' => 'present',
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test that destroy deletes attendance record.
     */
    public function test_destroy_deletes_attendance_record(): void
    {
        $attendance = AttendanceRecord::factory()->create();

        $response = $this->actingAs($this->user)->delete("/attendance/{$attendance->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Attendance record deleted successfully!');
        $this->assertDatabaseMissing('attendance_records', [
            'id' => $attendance->id,
        ]);
    }

    /**
     * Test that destroy returns 404 for non-existent record.
     */
    public function test_destroy_returns_404_for_non_existent_record(): void
    {
        $response = $this->actingAs($this->user)->delete('/attendance/99999');

        $response->assertStatus(404);
    }

    /**
     * Test that bulk_update creates multiple attendance records.
     */
    public function test_bulk_update_creates_multiple_attendance_records(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $students = Student::factory()->count(3)->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();
        $club->students()->attach($students->pluck('id'));

        $attendanceData = $students->map(fn($student, $index) => [
            'student_id' => $student->id,
            'status' => ['present', 'absent', 'late'][$index],
            'notes' => "Note for student {$index}",
        ])->toArray();

        $response = $this->actingAs($this->user)->post("/attendance/bulk/{$club->id}", [
            'session_id' => $session->id,
            'attendance' => $attendanceData,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Bulk attendance updated successfully!');
        
        foreach ($attendanceData as $record) {
            $this->assertDatabaseHas('attendance_records', [
                'student_id' => $record['student_id'],
                'session_id' => $session->id,
                'attendance_status' => $record['status'],
            ]);
        }
    }

    /**
     * Test that bulk_update updates existing records.
     */
    public function test_bulk_update_updates_existing_records(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $student = Student::factory()->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();
        
        $existingRecord = AttendanceRecord::factory()
            ->forSessionAndStudent($session, $student)
            ->absent()
            ->create();

        $response = $this->actingAs($this->user)->post("/attendance/bulk/{$club->id}", [
            'session_id' => $session->id,
            'attendance' => [
                [
                    'student_id' => $student->id,
                    'status' => 'present',
                    'notes' => 'Updated to present',
                ],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('attendance_records', [
            'id' => $existingRecord->id,
            'attendance_status' => 'present',
            'attendance_notes' => 'Updated to present',
        ]);
        $this->assertDatabaseCount('attendance_records', 1);
    }

    /**
     * Test that bulk_update validates required fields.
     */
    public function test_bulk_update_validates_required_fields(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post("/attendance/bulk/{$club->id}", []);

        $response->assertSessionHasErrors(['session_id', 'attendance']);
    }

    /**
     * Test that bulk_update validates attendance array structure.
     */
    public function test_bulk_update_validates_attendance_array_structure(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();

        $response = $this->actingAs($this->user)->post("/attendance/bulk/{$club->id}", [
            'session_id' => $session->id,
            'attendance' => [
                ['status' => 'present'], // Missing student_id
            ],
        ]);

        $response->assertSessionHasErrors('attendance.0.student_id');
    }

    /**
     * Test that bulk_update handles empty attendance array.
     */
    public function test_bulk_update_handles_empty_attendance_array(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();

        $response = $this->actingAs($this->user)->post("/attendance/bulk/{$club->id}", [
            'session_id' => $session->id,
            'attendance' => [],
        ]);

        // Should fail validation since array is required
        $response->assertSessionHasErrors('attendance');
    }

    /**
     * Test that bulk_update validates each status value.
     */
    public function test_bulk_update_validates_each_status_value(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $student = Student::factory()->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();

        $response = $this->actingAs($this->user)->post("/attendance/bulk/{$club->id}", [
            'session_id' => $session->id,
            'attendance' => [
                [
                    'student_id' => $student->id,
                    'status' => 'invalid_status',
                ],
            ],
        ]);

        $response->assertSessionHasErrors('attendance.0.status');
    }

    /**
     * Test that bulk_update validates notes length for each record.
     */
    public function test_bulk_update_validates_notes_length_for_each_record(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $student = Student::factory()->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();

        $response = $this->actingAs($this->user)->post("/attendance/bulk/{$club->id}", [
            'session_id' => $session->id,
            'attendance' => [
                [
                    'student_id' => $student->id,
                    'status' => 'present',
                    'notes' => str_repeat('a', 501),
                ],
            ],
        ]);

        $response->assertSessionHasErrors('attendance.0.notes');
    }

    /**
     * Test that update_attendance route no longer exists (was removed).
     */
    public function test_update_attendance_route_does_not_exist(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post("/attendance/update/{$club->id}", [
            'student_id' => 1,
            'session_id' => 1,
            'status' => 'present',
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test that authenticated users are required for all attendance routes.
     */
    public function test_attendance_routes_require_authentication(): void
    {
        $club = Club::factory()->create();
        $attendance = AttendanceRecord::factory()->create();

        $this->get('/attendance')->assertRedirect(route('login'));
        $this->get("/clubs/{$club->id}/attendance")->assertRedirect(route('login'));
        $this->post("/attendance/{$club->id}", [])->assertRedirect(route('login'));
        $this->put("/attendance/{$attendance->id}", [])->assertRedirect(route('login'));
        $this->delete("/attendance/{$attendance->id}")->assertRedirect(route('login'));
        $this->post("/attendance/bulk/{$club->id}", [])->assertRedirect(route('login'));
    }

    /**
     * Test that show_grid handles clubs with no students.
     */
    public function test_show_grid_handles_clubs_with_no_students(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->get("/clubs/{$club->id}/attendance");

        $response->assertStatus(200);
        $club = $response->viewData('club');
        $this->assertCount(0, $club->students);
    }

    /**
     * Test that show_grid with invalid week parameter still works.
     */
    public function test_show_grid_with_invalid_week_parameter(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->get("/clubs/{$club->id}/attendance?week=abc");

        $response->assertStatus(200);
        // Invalid week should be cast to 0
        $response->assertViewHas('week', 0);
    }

    /**
     * Test that show_grid with negative week parameter.
     */
    public function test_show_grid_with_negative_week_parameter(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->get("/clubs/{$club->id}/attendance?week=-5");

        $response->assertStatus(200);
        $response->assertViewHas('week', -5);
    }

    /**
     * Test that store validates student exists in database.
     */
    public function test_store_validates_student_exists(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();

        $response = $this->actingAs($this->user)->post("/attendance/{$club->id}", [
            'student_id' => 99999,
            'session_id' => $session->id,
            'status' => 'present',
        ]);

        $response->assertSessionHasErrors('student_id');
    }

    /**
     * Test that store validates session exists in database.
     */
    public function test_store_validates_session_exists(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $student = Student::factory()->forSchool($this->school)->create();

        $response = $this->actingAs($this->user)->post("/attendance/{$club->id}", [
            'student_id' => $student->id,
            'session_id' => 99999,
            'status' => 'present',
        ]);

        $response->assertSessionHasErrors('session_id');
    }

    /**
     * Test bulk_update with mixed valid and invalid data handles validation properly.
     */
    public function test_bulk_update_with_large_dataset(): void
    {
        $club = Club::factory()->forSchool($this->school)->create();
        $students = Student::factory()->count(50)->forSchool($this->school)->create();
        $session = SessionSchedule::factory()->forClub($club)->create();
        $club->students()->attach($students->pluck('id'));

        $attendanceData = $students->map(fn($student) => [
            'student_id' => $student->id,
            'status' => fake()->randomElement(['present', 'absent', 'late', 'excused']),
            'notes' => fake()->optional()->sentence(),
        ])->toArray();

        $response = $this->actingAs($this->user)->post("/attendance/bulk/{$club->id}", [
            'session_id' => $session->id,
            'attendance' => $attendanceData,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('attendance_records', 50);
    }
}