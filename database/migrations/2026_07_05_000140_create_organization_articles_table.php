<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('cover_image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        DB::table('organization_articles')->insert([
            [
                'title' => 'Makna Halal Bihalal dalam Ukhuwah Islamiyah',
                'slug' => 'makna-halal-bihalal-dalam-ukhuwah-islamiyah',
                'excerpt' => 'Refleksi singkat tentang pentingnya saling memaafkan dan menguatkan persaudaraan.',
                'content' => 'Halal bihalal bukan sekadar tradisi, tetapi momentum untuk membersihkan hati, memperbaiki hubungan, dan memperkuat kerja-kerja dakwah bersama.',
                'cover_image' => 'mobilekit/img/sample/photo/wide1.jpg',
                'published_at' => now(),
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_articles');
    }
};
