<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeLocation extends Model
{
    protected $table = 'notice_location';

    protected $fillable = [
        'notice_board_id',
        'division_id',
        'district_id',
        'upazila_id',
        'union_id',
    ];

    // 👇 The Notice it belongs to
    public function notice()
    {
        return $this->belongsTo(NoticeBoard::class, 'notice_board_id');
    }

    // 👇 New Relations (Division, District, Upazila, Union)

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function upazila()
    {
        return $this->belongsTo(Upazila::class, 'upazila_id');
    }

    public function union()
    {
        return $this->belongsTo(Union::class, 'union_id');
    }
}
