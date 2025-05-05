<?php

namespace App\Models;

use App\Traits\CommonAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory, CommonAttributes;

    protected $fillable = ['title', 'route_name', 'menu_icon', 'menu_order', 'parent_menu_id', 'is_active'];

    public function parent_menu()
    {
        return $this->belongsTo(Menu::class, 'parent_menu_id', 'id');
    }

    public function sub_menus()
    {
        return $this->hasMany(Menu::class, 'parent_menu_id', 'id');
    }

    public function scopeActive($query)
    {
        $query->where('is_active', 1);
    }

}
