<?php

namespace Tests\Feature;

use App\Models\OrganizationActivity;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ActivityGalleryDetailTest extends TestCase
{
    public function test_activity_detail_shows_gallery_images_as_downloadable_assets(): void
    {
        Schema::dropIfExists('organization_activities');
        Schema::dropIfExists('organization_profiles');

        Schema::create('organization_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('DEWAN DAKWAH RISALAH');
            $table->string('slug')->unique()->default('dewan-dakwah-risalah');
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('tagline')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->longText('about')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('organization_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('activity_date')->nullable();
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        $activity = OrganizationActivity::query()->create([
            'title' => 'Kajian Rutin',
            'description' => 'Dokumentasi kegiatan.',
            'activity_date' => now()->toDateString(),
            'location' => 'Masjid Pusat',
            'image' => 'mobilekit/img/sample/photo/wide1.jpg',
            'gallery_images' => [
                'mobilekit/img/sample/photo/wide2.jpg',
                'mobilekit/img/sample/photo/wide3.jpg',
            ],
            'is_published' => true,
        ]);

        $this->get(route('activities.show', $activity))
            ->assertOk()
            ->assertSee('download')
            ->assertSee('mobilekit/img/sample/photo/wide2.jpg')
            ->assertSee('mobilekit/img/sample/photo/wide3.jpg');
    }
}
