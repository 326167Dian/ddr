<?php

namespace App\Http\Controllers;

use App\Models\OrganizationActivity;
use App\Models\OrganizationArticle;
use App\Models\OrganizationHistory;
use App\Models\OrganizationProfile;
use App\Models\OrganizationStructure;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Throwable;

class FrontendController extends Controller
{
    public function home(): View
    {
        $profile = OrganizationProfile::query()->first();
        $prayerTicker = $this->getPrayerTicker();
        $activities = OrganizationActivity::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->latest('activity_date')
            ->take(3)
            ->get();
        $articles = OrganizationArticle::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('frontend.home', compact('profile', 'activities', 'articles', 'prayerTicker'));
    }

    private function getPrayerTicker(): ?string
    {
        $profile = OrganizationProfile::query()->first();
        $city = $this->resolvePrayerCity((string) ($profile?->prayer_city_code ?? 'solok'));

        $timezone = 'Asia/Jakarta';
        $today = Carbon::now($timezone)->format('Y-m-d');
        $cacheKey = 'prayer_ticker_' . $city['code'] . '_' . $today;
        $failureKey = $cacheKey . '_failure_backoff';

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        if (Cache::has($failureKey)) {
            return null;
        }

        $ticker = $this->fetchPrayerTickerFromAladhan($today, $city);

        if ($ticker === null) {
            $ticker = $this->fetchPrayerTickerFromMyQuran($today, $city);
        }

        if ($ticker !== null) {
            Cache::put($cacheKey, $ticker, Carbon::now($timezone)->endOfDay());
            return $ticker;
        }

        // Backoff 10 menit agar saat provider down tidak memanggil API pada setiap request.
        Cache::put($failureKey, true, now()->addMinutes(10));

        return null;
    }

    private function fetchPrayerTickerFromAladhan(string $today, array $city): ?string
    {
        try {
            $response = $this->prayerHttpClient()
                ->acceptJson()
                ->get("https://api.aladhan.com/v1/timingsByCity/{$today}", [
                    'city' => $city['aladhan_city'],
                    'country' => 'Indonesia',
                    'method' => 11,
                ]);
        } catch (Throwable $e) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $timings = data_get($response->json(), 'data.timings', []);
        $dateReadable = data_get($response->json(), 'data.date.gregorian.date', $today);

        return $this->buildPrayerTicker($city['display'], $dateReadable, [
            'Subuh' => data_get($timings, 'Fajr'),
            'Dzuhur' => data_get($timings, 'Dhuhr'),
            'Ashar' => data_get($timings, 'Asr'),
            'Maghrib' => data_get($timings, 'Maghrib'),
            'Isya' => data_get($timings, 'Isha'),
        ]);
    }

    private function fetchPrayerTickerFromMyQuran(string $today, array $city): ?string
    {
        try {
            [$year, $month, $day] = explode('-', $today);

            $response = $this->prayerHttpClient()
                ->acceptJson()
                ->get("https://api.myquran.com/v2/sholat/jadwal/{$city['myquran_id']}/{$year}/{$month}/{$day}");
        } catch (Throwable $e) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $data = data_get($response->json(), 'data', []);
        $timings = data_get($data, 'jadwal', []);
        $dateReadable = data_get($timings, 'date', $today);
        $city = data_get($data, 'lokasi', 'Kota Solok');

        return $this->buildPrayerTicker($city, $dateReadable, [
            'Subuh' => data_get($timings, 'subuh'),
            'Dzuhur' => data_get($timings, 'dzuhur'),
            'Ashar' => data_get($timings, 'ashar'),
            'Maghrib' => data_get($timings, 'maghrib'),
            'Isya' => data_get($timings, 'isya'),
        ]);
    }

    private function buildPrayerTicker(string $city, string $dateReadable, array $timings): ?string
    {
        $parts = [];

        foreach ($timings as $label => $value) {
            if (empty($value)) {
                continue;
            }

            // Sebagian API mengirim suffix timezone seperti "05:04 (WIB)", ambil HH:MM.
            if (preg_match('/^\d{2}:\d{2}/', (string) $value, $matches) === 1) {
                $value = $matches[0];
            }

            $parts[] = $label . ' ' . $value;
        }

        if ($parts === []) {
            return null;
        }

        return 'Jadwal Sholat ' . $city . ' ' . $dateReadable . ' | ' . implode(' | ', $parts);
    }

    private function resolvePrayerCity(string $code): array
    {
        $cities = [
            'solok' => [
                'code' => 'solok',
                'display' => 'Kota Solok',
                'aladhan_city' => 'Solok',
                'myquran_id' => '0319',
            ],
            'padang' => [
                'code' => 'padang',
                'display' => 'Kota Padang',
                'aladhan_city' => 'Padang',
                'myquran_id' => '0071',
            ],
            'jakarta' => [
                'code' => 'jakarta',
                'display' => 'DKI Jakarta',
                'aladhan_city' => 'Jakarta',
                'myquran_id' => '1301',
            ],
            'bandung' => [
                'code' => 'bandung',
                'display' => 'Kota Bandung',
                'aladhan_city' => 'Bandung',
                'myquran_id' => '0327',
            ],
            'yogyakarta' => [
                'code' => 'yogyakarta',
                'display' => 'Kota Yogyakarta',
                'aladhan_city' => 'Yogyakarta',
                'myquran_id' => '0856',
            ],
            'surabaya' => [
                'code' => 'surabaya',
                'display' => 'Kota Surabaya',
                'aladhan_city' => 'Surabaya',
                'myquran_id' => '1634',
            ],
        ];

        return $cities[$code] ?? $cities['solok'];
    }

    private function prayerHttpClient(): PendingRequest
    {
        $request = Http::timeout(8);

        if (app()->environment('local')) {
            // Avoid local CA/certificate issues so ticker still works during development.
            $request = $request->withoutVerifying();
        }

        return $request;
    }

    public function structure(): View
    {
        $profile = OrganizationProfile::query()->first();
        $structures = OrganizationStructure::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('frontend.structure', compact('profile', 'structures'));
    }

    public function history(): View
    {
        $profile = OrganizationProfile::query()->first();
        $histories = OrganizationHistory::query()
            ->orderBy('sort_order')
            ->get();

        return view('frontend.history', compact('profile', 'histories'));
    }

    public function activities(): View
    {
        $profile = OrganizationProfile::query()->first();
        $activities = OrganizationActivity::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->latest('activity_date')
            ->get();

        return view('frontend.activities', compact('profile', 'activities'));
    }

    public function activityDetail(OrganizationActivity $activity): View
    {
        abort_if(! $activity->is_published, 404);

        $profile = OrganizationProfile::query()->first();

        return view('frontend.activity-detail', compact('profile', 'activity'));
    }

    public function articles(): View
    {
        $profile = OrganizationProfile::query()->first();
        $articles = OrganizationArticle::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->latest('published_at')
            ->get();

        return view('frontend.articles', compact('profile', 'articles'));
    }

    public function articleDetail(string $slug): View
    {
        $profile = OrganizationProfile::query()->first();
        $article = OrganizationArticle::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('frontend.article-detail', compact('profile', 'article'));
    }
}
