<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_structures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('organization_structures')->insert([
            ['name' => 'Dr. M. Akhyar Adnan, MA', 'position' => 'Ketua Umum', 'bio' => 'Memimpin arah strategi dakwah dan kolaborasi lintas bidang.', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'RW Dt. Rajo Lenggang / Alex Suryadi', 'position' => 'Dewan Pembina', 'bio' => 'Memberikan pembinaan, arahan, dan penguatan nilai organisasi.', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prof. Dr. M. Amin Nurdin, MA dkk', 'position' => 'Dewan Pakar', 'bio' => 'Memberi pertimbangan ilmiah untuk program dakwah dan pendidikan.', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fauzul / Yondri / Dwindra / Ahmad Nurdiansyah', 'position' => 'Sekretaris Umum', 'bio' => 'Mengelola administrasi, koordinasi program, dan dokumentasi kegiatan.', 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Edwaren Liun / Nita Zainal / Andrizal', 'position' => 'Bendahara Umum', 'bio' => 'Menata keuangan organisasi secara amanah dan transparan.', 'sort_order' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_structures');
    }
};
