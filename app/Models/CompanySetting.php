<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = ['company_name', 'company_address', 'start_time', 'end_time', 'last_login_time'];

    /**
     * Get all the weekDays for the CompanySetting
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function weekDays()
    {
        return $this->hasMany(WeekDay::class,'company_id', 'id');
    }
    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function default_Supervisor()
    {
        return $this->belongsTo(User::class, 'ta_da_supervisor_id');
    }
}
