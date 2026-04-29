<?php

use App\Models\School;
use App\Models\Subject;
use App\Models\User;
use Spatie\Permission\Models\Role;

it('blocks a school admin from updating a subject from another school', function () {
    $schoolA = School::create(['name' => 'School A']);
    $schoolB = School::create(['name' => 'School B']);
    $role = Role::findOrCreate('school_admin', 'web');

    $admin = User::factory()->create(['school_id' => $schoolA->id]);
    $admin->assignRole($role);

    $subject = Subject::create([
        'school_id' => $schoolB->id,
        'name' => 'Physics',
    ]);

    $this
        ->actingAs($admin)
        ->put(route('admin.subjects.update', $subject->id), [
            'name' => 'Updated Physics',
        ])
        ->assertNotFound();
});
