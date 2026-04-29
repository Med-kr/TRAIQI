<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    public function sendToUser(
        User $recipient,
        string $title,
        string $body,
        string $type = 'system',
        ?string $actionUrl = null,
        array $data = []
    ): Notification {
        return Notification::create([
            'user_id' => $recipient->id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'action_url' => $actionUrl,
            'data' => $data === [] ? null : $data,
        ]);
    }

    public function sendToUsers(iterable $recipients, string $title, string $body, string $type = 'system', ?string $actionUrl = null, array $data = []): void
    {
        collect($recipients)
            ->filter(fn ($recipient) => $recipient instanceof User)
            ->unique('id')
            ->each(fn (User $recipient) => $this->sendToUser($recipient, $title, $body, $type, $actionUrl, $data));
    }

    public function sendSchoolMessage(User $actor, Collection $recipients, string $title, string $body, ?string $actionUrl = null): void
    {
        $this->sendToUsers(
            $recipients,
            $title,
            $body,
            'school_message',
            $actionUrl,
            ['sent_by' => $actor->id]
        );
    }
}
