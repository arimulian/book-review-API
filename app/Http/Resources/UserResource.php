<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
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
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->when($request->getPassword() ,$this->password),
            'token' => $this->whenNotNull($this->token),
        ];
    }
    public function with(Request $request)
    {
        if ($request->input('password') && $request->input('name')) {
            return [
               'message' => 'password & name is Changed',
            ];
        }else if ($request->input('password')) {
            return [
               'message' => 'password is updated',
            ];
        }

        return [
           'message' => 'Name is updated',
        ];
    }

}
