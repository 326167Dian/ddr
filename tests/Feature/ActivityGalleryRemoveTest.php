<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\ActivityController;
use App\Models\OrganizationActivity;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ActivityGalleryRemoveTest extends TestCase
{
    public function test_activity_gallery_image_can_be_removed_from_the_activity(): void
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

        $request = Request::create('/admin/activities/'.$activity->id.'/gallery/remove', 'POST', [
            'index' => 0,
        ]);

        $response = (new ActivityController())->removeGalleryImage($request, $activity);

        $this->assertSame(302, $response->getStatusCode());

        $activity->refresh();
        $this->assertSame([
            'mobilekit/img/sample/photo/wide3.jpg',
        ], $activity->gallery_images);
    }
}
