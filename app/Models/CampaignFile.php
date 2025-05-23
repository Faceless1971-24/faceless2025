<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignFile extends Model
{
    use HasFactory;

    protected $fillable = ['campaign_id', 'file_path', 'file_name', 'file_type', 'file_size'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}