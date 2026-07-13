<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\ActivityController;
use App\Models\OrganizationActivity;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ActivityGalleryMissingColumnTest extends TestCase
{
    public function test_activity_update_is_safe_when_gallery_images_column_is_missing(): void
    {
        Schema::dropIfExists('organization_activities');

        Schema::create('organization_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('activity_date')->nullable();
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        $activity = OrganizationActivity::query()->create([
            'title' => 'Kajian Rutin',
            'description' => 'Dokumentasi kegiatan.',
            'activity_date' => now()->toDateString(),
            'location' => 'Masjid Pusat',
            'image' => 'mobilekit/img/sample/photo/wide1.jpg',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $request = Request::create('/admin/activities/'.$activity->id, 'PUT', [
            'title' => 'Kajian Rutin Baru',
            'description' => 'Dokumentasi kegiatan diperbarui.',
            'activity_date' => now()->toDateString(),
            'location' => 'Masjid Pusat',
            'image' => 'mobilekit/img/sample/photo/wide1.jpg',
            'is_published' => true,
        ]);

        $response = (new ActivityController())->update($request, $activity);

        $this->assertSame(302, $response->getStatusCode());

        $activity->refresh();
        $this->assertSame('Kajian Rutin Baru', $activity->title);
        $this->assertSame('mobilekit/img/sample/photo/wide1.jpg', $activity->image);
    }

    public function test_activity_gallery_removal_is_safe_when_gallery_images_column_is_missing(): void
    {
        Schema::dropIfExists('organization_activities');

        Schema::create('organization_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('activity_date')->nullable();
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        $activity = OrganizationActivity::query()->create([
            'title' => 'Kajian Rutin',
            'description' => 'Dokumentasi kegiatan.',
            'activity_date' => now()->toDateString(),
            'location' => 'Masjid Pusat',
            'image' => 'mobilekit/img/sample/photo/wide1.jpg',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $request = Request::create('/admin/activities/'.$activity->id.'/gallery/remove', 'POST', [
            'index' => 0,
        ]);

        $response = (new ActivityController())->removeGalleryImage($request, $activity);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('Foto galeri berhasil dihapus.', session('status'));
    }
}
