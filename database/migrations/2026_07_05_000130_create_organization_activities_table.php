<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('activity_date')->nullable();
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        DB::table('organization_activities')->insert([
            ['title' => 'Kajian Rutin Ahad Pagi', 'description' => 'Kajian tafsir tematik untuk jamaah umum.', 'activity_date' => now()->toDateString(), 'location' => 'Masjid Pusat Dakwah', 'image' => 'mobilekit/img/sample/photo/wide2.jpg', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Program Santunan Yatim', 'description' => 'Penyaluran santunan dan pembinaan akhlak untuk anak yatim.', 'activity_date' => now()->addDays(10)->toDateString(), 'location' => 'Aula Sosial DDR', 'image' => 'mobilekit/img/sample/photo/wide3.jpg', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_activities');
    }
};
