<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeavesResource extends JsonResource
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
            'from_date' => date_format(date_create($this->from_date), 'd M, Y'),
            'to_date' => $this->to_date ? date_format(date_create($this->to_date), 'd M, Y') : '-',
            'no_of_days' => $this->no_of_days,
            'status' => $this->status,
            'leave_status' => $this->leave_status,
            'leave_slot' => $this->leave_slots->name ?? '-',
            'leave_type' => $this->leave_types->name ?? '-',
            'reason' => $this->reason ?? 'N/A',
            'is_supervisor_approved' => $this->is_supervisor_approved,
        ];
    }
}
