<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'theme_dark' => $this->theme_dark,
            'role' => $this->roles->first() ? $this->roles->first()->id : null,
            'roleName' => $this->roles->first() ? $this->roles->first()->name : null,
            'roleData' => RoleResource::collection($this->roles),
        ];
    }
}
