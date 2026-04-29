<?php

use App\Models\Notification;
use App\Models\School;
use App\Models\User;
use Spatie\Permission\Models\Role;

it('allows an admin to send a notification to a user in the same school', function () {
    $school = School::create(['name' => 'Pilot School']);
    $adminRole = Role::findOrCreate('school_admin', 'web');

    $admin = User::factory()->create(['school_id' => $school->id]);
    $admin->assignRole($adminRole);

    $recipient = User::factory()->create(['school_id' => $school->id]);

    $response = $this
        ->actingAs($admin)
        ->post(route('notifications.store'), [
            'user_id' => $recipient->id,
            'title' => 'Message',
            'body' => 'Important update',
            'type' => 'school_message',
        ]);

    $response->assertSessionHas('status', 'Notification sent successfully.');

    expect(Notification::where('user_id', $recipient->id)->count())->toBe(1);
});

it('prevents marking another users notification as read', function () {
    $firstUser = User::factory()->create();
    $secondUser = User::factory()->create();

    $notification = Notification::create([
        'user_id' => $secondUser->id,
        'type' => 'system',
        'title' => 'Hidden',
        'body' => 'Private',
    ]);

    $this
        ->actingAs($firstUser)
        ->patch(route('notifications.read', $notification->id))
        ->assertNotFound();
});
