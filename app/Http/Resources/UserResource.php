<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'branch' => null,
            'branch_account' => null,
            'name' => $this->name,
            'fname' => $this->fname,
            'mname' => $this->mname,
            'lname' => $this->lname,
            'ext_name' => $this->ext_name,
            'email' => $this->email,
            'permissions' => $this->getAllPermissions()->pluck('name')->toArray(),
            'user_type' => \App\Services\Users\User::class
        ];
    }
}
