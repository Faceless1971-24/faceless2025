<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Union extends Model
{
    use HasFactory;

    // Union.php

    public function users()
    {
        return $this->hasMany(User::class);
    }


    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

}
