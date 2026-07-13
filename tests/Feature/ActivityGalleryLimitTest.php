<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\ActivityController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ActivityGalleryLimitTest extends TestCase
{
    public function test_activity_gallery_upload_is_limited_to_ten_images(): void
    {
        $this->expectException(ValidationException::class);

        Schema::dropIfExists('organization_activities');

        Schema::create('organization_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('activity_date')->nullable();
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        $files = [];
        for ($index = 0; $index < 11; $index++) {
            $files['gallery_files'][] = UploadedFile::fake()->image('gallery-' . $index . '.jpg', 1024, 768);
        }

        $request = Request::create('/admin/activities', 'POST', [
            'title' => 'Kajian Rutin',
            'description' => 'Dokumentasi kegiatan.',
            'activity_date' => now()->toDateString(),
            'location' => 'Masjid Pusat',
            'is_published' => true,
        ], [], $files);

        (new ActivityController())->store($request);
    }
}
