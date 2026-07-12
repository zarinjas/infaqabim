<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'target_amount',
        'collected_amount',
        'type',
        'is_active',
        'image',
    ];

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(CampaignGallery::class);
    }
}
