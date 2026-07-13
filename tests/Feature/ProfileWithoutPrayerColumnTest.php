<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\ProfileController;
use App\Models\OrganizationProfile;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProfileWithoutPrayerColumnTest extends TestCase
{
    public function test_profile_update_is_safe_when_prayer_city_code_column_is_missing(): void
    {
        Schema::dropIfExists('organization_profiles');

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

        $profile = OrganizationProfile::query()->create([
            'name' => 'DEWAN DAKWAH RISALAH',
            'slug' => 'dewan-dakwah-risalah',
            'hero_image' => 'mobilekit/img/halamandepan.png',
            'logo_path' => 'mobilekit/img/logo_ddr.png',
        ]);

        $request = Request::create('/admin/profile', 'PUT', [
            'name' => 'DEWAN DAKWAH RISALAH',
            'tagline' => 'Dewan Da\'wah Risalah Islamiyyah - Sulit Air',
            'hero_title' => 'Halal Bihalal Dewan Dakwah Risalah 1446 H',
            'hero_subtitle' => 'Menyatukan langkah dakwah dalam ukhuwah islamiyah',
            'hero_image' => 'mobilekit/img/halamandepan_123.jpeg',
            'logo_path' => 'mobilekit/img/logo_ddr.png',
            'prayer_city_code' => 'solok',
            'vision' => 'Visi test',
            'mission' => 'Misi test',
            'about' => 'Tentang test',
            'contact_email' => null,
            'contact_phone' => null,
            'address' => null,
        ]);

        $response = (new ProfileController())->update($request);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertFalse(Schema::hasColumn('organization_profiles', 'prayer_city_code'));

        $profile->refresh();
        $this->assertSame('mobilekit/img/halamandepan_123.jpeg', $profile->hero_image);
    }
}
