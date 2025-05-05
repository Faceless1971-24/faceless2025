<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for campaign images
 */
class CampaignImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campaign_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size'
    ];

    /**
     * Get the campaign that owns the image.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}