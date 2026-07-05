<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'logo_path',
        'vision',
        'mission',
        'about',
        'contact_email',
        'contact_phone',
        'address',
    ];
}
