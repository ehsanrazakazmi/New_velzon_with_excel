<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The shape every endpoint uses when it returns a user.
 *
 * Kept in one place so the TypeScript interface on the frontend has exactly one
 * thing to track. `permissions` is flattened from the roles because that is what
 * the UI actually gates on - it never needs to know which role granted what.
 */
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,

            'roles'       => $this->whenLoaded('roles', fn () => $this->roles->pluck('name')->all(), []),
            'permissions' => $this->when(
                $this->relationLoaded('roles'),
                fn () => $this->getAllPermissions()->pluck('name')->unique()->values()->all(),
                []
            ),

            // Drives the frontend's onboarding gate. `pending` is true for the
            // whole unproven window - see the Status column on the users list.
            'must_change_password' => (bool) $this->must_change_password,
            'pending'              => (bool) ($this->welcome_token || $this->must_change_password),

            'created_at' => optional($this->created_at)->toIso8601String(),
        ];
    }
}
