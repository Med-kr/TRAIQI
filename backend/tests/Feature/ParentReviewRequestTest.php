<?php

use App\Models\AuditLog;
use App\Models\Classroom;
use App\Models\Evaluation;
use App\Models\Grade;
use App\Models\Level;
use App\Models\Notification;
use App\Models\ReviewRequest;
use App\Models\School;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\User;
use Spatie\Permission\Models\Role;

it('allows a parent to create a review request for their child grade', function () {
    $school = School::create(['name' => 'Traiqi Pilot School']);
    $level = Level::create(['code' => '6A', 'name' => '6eme', 'school_id' => $school->id]);
    $classroom = Classroom::create([
        'name' => '6A-1',
        'school_id' => $school->id,
        'level_id' => $level->id,
    ]);
    $subject = Subject::create([
        'name' => 'Mathematics',
        'school_id' => $school->id,
    ]);

    $teacherRole = Role::findOrCreate('teacher', 'web');
    $parentRole = Role::findOrCreate('parent', 'web');
    $adminRole = Role::findOrCreate('school_admin', 'web');

    $teacher = User::factory()->create(['school_id' => $school->id]);
    $teacher->assignRole($teacherRole);

    $parent = User::factory()->create(['school_id' => $school->id]);
    $parent->assignRole($parentRole);

    $admin = User::factory()->create(['school_id' => $school->id]);
    $admin->assignRole($adminRole);

    $student = User::factory()->create(['school_id' => $school->id, 'level_id' => $level->id]);
    StudentProfile::create([
        'user_id' => $student->id,
        'classroom_id' => $classroom->id,
    ]);

    $parent->children()->attach($student->id);

    $evaluation = Evaluation::create([
        'title' => 'Devoir 1',
        'school_id' => $school->id,
        'classroom_id' => $classroom->id,
        'subject_id' => $subject->id,
        'teacher_id' => $teacher->id,
        'date' => '2026-04-26',
    ]);

    $grade = Grade::create([
        'school_id' => $school->id,
        'evaluation_id' => $evaluation->id,
        'student_id' => $student->id,
        'value' => 12.5,
    ]);

    $response = $this
        ->actingAs($parent)
        ->post(route('parent.review-requests.store'), [
            'student_id' => $student->id,
            'grade_id' => $grade->id,
            'reason' => 'I would like to discuss this result and understand how to improve.',
        ]);

    $response
        ->assertRedirect(route('parent.dashboard'))
        ->assertSessionHas('status', 'Review request submitted successfully.');

    expect(ReviewRequest::count())->toBe(1);
    expect(Notification::where('user_id', $teacher->id)->count())->toBe(1);
    expect(Notification::where('user_id', $admin->id)->count())->toBe(1);
    expect(AuditLog::where('action', 'review_request_created')->count())->toBe(1);
});

it('prevents a parent from creating duplicate pending review requests for the same grade', function () {
    $school = School::create(['name' => 'Traiqi Pilot School']);
    $parentRole = Role::findOrCreate('parent', 'web');

    $parent = User::factory()->create(['school_id' => $school->id]);
    $parent->assignRole($parentRole);

    $student = User::factory()->create(['school_id' => $school->id]);
    $parent->children()->attach($student->id);

    $grade = Grade::create([
        'school_id' => $school->id,
        'evaluation_id' => Evaluation::create([
            'title' => 'Control',
            'school_id' => $school->id,
            'classroom_id' => Classroom::create([
                'name' => 'A1',
                'school_id' => $school->id,
                'level_id' => Level::create(['code' => 'A1', 'name' => 'A1', 'school_id' => $school->id])->id,
            ])->id,
            'subject_id' => Subject::create([
                'name' => 'French',
                'school_id' => $school->id,
            ])->id,
            'date' => '2026-04-26',
        ])->id,
        'student_id' => $student->id,
        'value' => 10,
    ]);

    ReviewRequest::create([
        'grade_id' => $grade->id,
        'student_id' => $student->id,
        'reason' => 'Existing request',
        'status' => 'pending',
    ]);

    $response = $this
        ->actingAs($parent)
        ->post(route('parent.review-requests.store'), [
            'student_id' => $student->id,
            'grade_id' => $grade->id,
            'reason' => 'Second request',
        ]);

    $response
        ->assertRedirect(route('parent.dashboard'))
        ->assertSessionHas('status', 'A pending review request already exists for this grade.');

    expect(ReviewRequest::count())->toBe(1);
});
