<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'fullName' => $this->fullName,
            'username' => $this->username,
            'password' => $this->when($request->getPassword() ,$this->password),
            'token' => $this->whenNotNull($this->token),
        ];
    }
    public function with(Request $request)
    {
        if($request->getMethod() == 'PATCH') {
            if ($request->input('password') && $request->input('fullName')) {
                return [
                    'message' => 'password & Name is updated successfully',
                ];
            }else if ($request->input('password')) {
                return [
                    'message' => 'password is updated successfully',
                ];
            }else {
                return [
                    'message' => 'Name is updated successfully',
                ];
            }

        }else if ($request->path() == 'api/users/login'){
            return [
                'message' => 'User is logged in successfully',
            ];
        }

        return [
            'message' => 'User is created successfully',
        ];
    }

}
