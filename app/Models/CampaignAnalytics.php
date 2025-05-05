<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignAnalytics extends Model
{
    use HasFactory;
    
    protected $fillable = ['campaign_id', 'views', 'engagements', 'shares', 'supporters_count'];
    
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
    
    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
    
    /**
     * Increment engagement count
     */
    public function incrementEngagements()
    {
        $this->increment('engagements');
    }
    
    /**
     * Increment share count
     */
    public function incrementShares()
    {
        $this->increment('shares');
    }
    
    /**
     * Update supporters count
     */
    public function updateSupportersCount()
    {
        $this->update([
            'supporters_count' => $this->campaign->supporters()->count()
        ]);
    }
}
