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
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'gallery_images' => 'array',
        'is_published' => 'boolean',
    ];
}
