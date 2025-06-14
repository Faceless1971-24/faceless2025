<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDepartment extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function notices()
    {
        return $this->belongsToMany(NoticeBoard::class, 'notice_location', 'division_id', 'notice_board_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
