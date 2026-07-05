<?php

namespace Database\Seeders;

use App\Models\OrganizationStructure;
use Illuminate\Database\Seeder;

class OrganizationStructureSeeder extends Seeder
{
    public function run(): void
    {
        OrganizationStructure::query()->delete();

        $rows = [
            ['name' => 'RW Dt. Rajo Lenggang / Alex Suryadi', 'position' => 'Dewan Pembina', 'bio' => 'Pembinaan kebijakan strategis organisasi.', 'sort_order' => 1, 'is_active' => true],
            ['name' => 'Dr. M. Akhyar Adnan, MA', 'position' => 'Ketua Umum', 'bio' => 'Memimpin arah dakwah dan sinergi program.', 'sort_order' => 2, 'is_active' => true],
            ['name' => 'Fauzul / Yondri / Dwindra / Ahmad Nurdiansyah', 'position' => 'Sekretaris Umum', 'bio' => 'Administrasi, koordinasi lintas departemen, dan dokumentasi.', 'sort_order' => 3, 'is_active' => true],
            ['name' => 'Edwaren Liun / Nita Zainal / Andrizal / Edwin Nazar / Ipsen Hardi', 'position' => 'Bendahara Umum', 'bio' => 'Pengelolaan keuangan organisasi secara amanah.', 'sort_order' => 4, 'is_active' => true],
            ['name' => 'Azzam Manan / Nablur Rahman / Jaka Setiawan / Arief Adi Wibawa', 'position' => 'Dakwah / Tabligh', 'bio' => 'Program dakwah, tabligh akbar, dan pembinaan jamaah.', 'sort_order' => 5, 'is_active' => true],
            ['name' => 'Syamsuardi Rusli / Hidayatullah / Hery Gani', 'position' => 'Pendidikan & SDM', 'bio' => 'Peningkatan kualitas pendidikan dan pengembangan SDM dakwah.', 'sort_order' => 6, 'is_active' => true],
            ['name' => 'Rosi Yulita Rusli / Refian Erlinda / Respalito Alwie / R. Putrawan / Eddy Piliang / Azwardi Azwir', 'position' => 'Sosial, Budaya & Lingkungan', 'bio' => 'Program sosial kemasyarakatan, budaya, dan kepedulian lingkungan.', 'sort_order' => 7, 'is_active' => true],
            ['name' => 'Prof. Dr. M. Amin Nurdin, MA / Budiarman Bahar / Jusna JA Amin', 'position' => 'Ekonomi & Pembangunan', 'bio' => 'Pemberdayaan ekonomi umat dan penguatan pembangunan komunitas.', 'sort_order' => 8, 'is_active' => true],
            ['name' => 'Prof. Jurnalis Uddin / Ali Ridwan Liun / Zafrullah Salim / Sjahril Syam / Dt Polong Kayo / dll', 'position' => 'Dewan Pakar', 'bio' => 'Pertimbangan ilmiah, ide, dan penguatan arah pemikiran organisasi.', 'sort_order' => 9, 'is_active' => true],
        ];

        foreach ($rows as $row) {
            OrganizationStructure::query()->create($row);
        }
    }
}
