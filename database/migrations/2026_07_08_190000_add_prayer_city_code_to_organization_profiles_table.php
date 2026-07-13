<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('organization_profiles', 'prayer_city_code')) {
            Schema::table('organization_profiles', function (Blueprint $table) {
                $table->string('prayer_city_code', 50)->default('solok')->after('logo_path');
            });
        }
    }

    public function down(): void
    {
        Schema::table('organization_profiles', function (Blueprint $table) {
            $table->dropColumn('prayer_city_code');
        });
    }
};
