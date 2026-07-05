<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('DEWAN DAKWAH RISALAH');
            $table->string('slug')->unique()->default('dewan-dakwah-risalah');
            $table->string('tagline')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('logo_path')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->longText('about')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        DB::table('organization_profiles')->insert([
            'name' => 'DEWAN DAKWAH RISALAH',
            'slug' => 'dewan-dakwah-risalah',
            'tagline' => 'Dewan Da\'wah Risalah Islamiyyah - Sulit Air',
            'hero_title' => 'Halal Bihalal Dewan Dakwah Risalah 1446 H',
            'hero_subtitle' => 'Menyatukan langkah dakwah dalam ukhuwah islamiyah',
            'hero_image' => 'mobilekit/img/halamandepan.png',
            'logo_path' => 'mobilekit/img/logo_ddr.png',
            'vision' => 'Menjadi Institusi Da\'wah Risalah Islamiyyah terkemuka dalam masyarakat Sulit Air.',
            'mission' => "1. Menjadi think tank yang memberi ide, gagasan, dan pemikiran dakwah.\n2. Menjalankan program kerja departemen sesuai kebutuhan dan kemampuan jamaah.\n3. Menata organisasi melalui musyawarah tahunan dan rapat berkala lintas bidang.",
            'about' => 'Dewan Dakwah Risalah adalah organisasi dakwah keislaman yang berorientasi pada penguatan nilai amanah, tabligh, pendidikan, sosial budaya, serta pembangunan SDM.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_profiles');
    }
};
