<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'userid' => $this->userid,
            'email' => $this->email,
            'gender' => $this->gender_text,
            'dob' => date_format(date_create($this->dob), 'd M, Y') ?? 'N/A',
            'address' => $this->address,
            'phone' => $this->phone,
            'joining_date' => date_format(date_create($this->joining_date), 'd M, Y') ?? 'N/A',
            'photo' => $this->photo_path,
            'designation' => $this->designation->name,
            'supervisor' => [
                'id' => $this->supervisor_of_user->id,
                'name' => $this->supervisor_of_user->name,
                'userid' => $this->supervisor_of_user->userid,
            ],
        ];
    }
}
