<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_activities', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('image');
        });

        Schema::table('organization_articles', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('cover_image');
        });

        $activityOrder = 1;
        DB::table('organization_activities')->orderBy('id')->get(['id'])->each(function ($row) use (&$activityOrder) {
            DB::table('organization_activities')->where('id', $row->id)->update(['sort_order' => $activityOrder++]);
        });

        $articleOrder = 1;
        DB::table('organization_articles')->orderBy('id')->get(['id'])->each(function ($row) use (&$articleOrder) {
            DB::table('organization_articles')->where('id', $row->id)->update(['sort_order' => $articleOrder++]);
        });
    }

    public function down(): void
    {
        Schema::table('organization_activities', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });

        Schema::table('organization_articles', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
