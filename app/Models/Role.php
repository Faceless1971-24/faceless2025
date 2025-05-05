<?php

namespace App\Models;

use App\Traits\CommonAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, CommonAttributes;
    protected $fillable = ['title', 'slug'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
