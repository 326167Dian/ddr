<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'activity_date',
        'location',
        'image',
        'gallery_images',
        'youtube_url',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'gallery_images' => 'array',
        'is_published' => 'boolean',
    ];

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (! $this->youtube_url) {
            return null;
        }

        $pattern = '/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';

        if (! preg_match($pattern, $this->youtube_url, $matches)) {
            return null;
        }

        return 'https://www.youtube.com/embed/' . $matches[1];
    }
}
