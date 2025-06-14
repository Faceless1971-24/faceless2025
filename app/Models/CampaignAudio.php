<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignAudio extends Model
{
    use HasFactory;
    protected $table = 'campaign_audios'; // <- Add this if needed


    protected $fillable = ['campaign_id', 'file_path', 'file_name', 'file_type', 'file_size', 'duration'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}