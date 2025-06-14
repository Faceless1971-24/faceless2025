<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeBoard extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'file_paths', 'status', 'email_send', 'pinned'];


    
    public function locations()
    {
        return $this->hasMany(NoticeLocation::class, 'notice_board_id');
    }
    

}
