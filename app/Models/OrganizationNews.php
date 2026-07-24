<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationNews extends Model
{
    use HasFactory;

    protected $table = 'organization_news';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'gallery_images',
        'published_at',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'gallery_images' => 'array',
        'is_published' => 'boolean',
    ];
}
