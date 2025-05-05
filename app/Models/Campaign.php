<?php

namespace App\Models;

use App\Models\CampaignComment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title', 
        'description', 
        'campaign_type', 
        'is_nationwide', 
        'start_date', 
        'end_date', 
        'status', 
        'notification_send', 
        'featured', 
        'created_by'
    ];
    
    protected $casts = [
        'is_nationwide' => 'boolean',
        'featured' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    
    /**
     * Get the user who created the campaign
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Get the divisions associated with the campaign
     */
    public function divisions()
    {
        return $this->belongsToMany(Division::class, 'campaign_division');
    }
    
    /**
     * Get the districts associated with the campaign
     */
    public function districts()
    {
        return $this->belongsToMany(District::class, 'campaign_district');
    }
    
    /**
     * Get the upazilas associated with the campaign
     */
    public function upazilas()
    {
        return $this->belongsToMany(Upazila::class, 'campaign_upazila');
    }
    
    /**
     * Get the unions associated with the campaign
     */
    public function unions()
    {
        return $this->belongsToMany(Union::class, 'campaign_union');
    }
    
    /**
     * Get the images for the campaign
     */
    public function images()
    {
        return $this->hasMany(CampaignImage::class);
    }
    
    /**
     * Get the audio for the campaign
     */
    public function audio()
    {
        return $this->hasOne(CampaignAudio::class);
    }
    
    /**
     * Get the video for the campaign
     */
    public function video()
    {
        return $this->hasOne(CampaignVideo::class);
    }
    
    /**
     * Get the files/documents for the campaign
     */
    public function files()
    {
        return $this->hasMany(CampaignFile::class);
    }
    
    /**
     * Get the analytics for the campaign
     */
    public function analytics()
    {
        return $this->hasOne(CampaignAnalytics::class);
    }
    
    protected static function booted()
    {
        static::created(function ($campaign) {
            $campaign->analytics()->create();
        });
    }


    /**
     * Get the supporters for the campaign
     */
    public function supporters()
    {
        return $this->hasMany(CampaignSupporter::class);
    }
    
    /**
     * Scope a query to only include active campaigns
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'publish')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }
    
    /**
     * Scope a query to only include featured campaigns
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
    
    /**
     * Scope a query to find campaigns by type and location
     */
    public function scopeByLocation($query, $type, $locationId)
    {
        if ($type === 'division') {
            return $query->whereHas('divisions', function($q) use ($locationId) {
                $q->where('division_id', $locationId);
            });
        } elseif ($type === 'district') {
            return $query->whereHas('districts', function($q) use ($locationId) {
                $q->where('district_id', $locationId);
            });
        } elseif ($type === 'upazila') {
            return $query->whereHas('upazilas', function($q) use ($locationId) {
                $q->where('upazila_id', $locationId);
            });
        } elseif ($type === 'union') {
            return $query->whereHas('unions', function($q) use ($locationId) {
                $q->where('union_id', $locationId);
            });
        }
        
        return $query;
    }

    // in App\Models\Campaign.php
    public function comments()
    {
        return $this->hasMany(CampaignComment::class);
    }

}