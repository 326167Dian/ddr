<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_histories', function (Blueprint $table) {
            $table->id();
            $table->string('year', 20);
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('organization_histories')->insert([
            ['year' => '2018', 'title' => 'Inisiasi Komunitas', 'description' => 'Gerakan kajian kecil dimulai dari halaqah rutin pekanan.', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['year' => '2021', 'title' => 'Pembentukan Organisasi', 'description' => 'Resmi dibentuk dengan nama DEWAN DAKWAH RISALAH sebagai wadah dakwah terstruktur.', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['year' => '2025', 'title' => 'Ekspansi Program Umat', 'description' => 'Program sosial, pendidikan, dan publikasi digital diperluas untuk menjangkau masyarakat lebih luas.', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_histories');
    }
};
