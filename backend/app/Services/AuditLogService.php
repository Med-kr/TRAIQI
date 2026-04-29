<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;

class AuditLogService
{
    public function record(?User $actor, string $action, ?string $description = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => $actor?->id,
            'action' => $action,
            'description' => $description,
        ]);
    }
}
