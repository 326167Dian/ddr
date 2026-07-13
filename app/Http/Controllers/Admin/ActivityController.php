<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(): View
    {
        $items = OrganizationActivity::query()
            ->orderBy('sort_order')
            ->latest('activity_date')
            ->get();

        return view('admin.activities.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.activities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureGalleryUploadLimit($request);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'activity_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:6144'],
            'gallery_files' => ['nullable', 'array'],
            'gallery_files.*' => ['image', 'max:1024'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ], [
            'gallery_files.*.max' => 'Maksimal ukuran tiap foto galeri adalah 1 MB.',
        ]);

        unset($data['image_file'], $data['gallery_files']);

        if ($request->hasFile('image_file')) {
            $data['image'] = $this->moveUploadedAssetToPublic($request->file('image_file'), 'kegiatan');
        }

        $galleryImages = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $galleryFile) {
                $galleryImages[] = $this->moveUploadedAssetToPublic($galleryFile, 'kegiatan_galeri');
            }
        }

        if (Schema::hasColumn('organization_activities', 'gallery_images')) {
            $data['gallery_images'] = $galleryImages ?: null;
        }
        $data['sort_order'] = $data['sort_order'] ?? ((int) OrganizationActivity::query()->max('sort_order') + 1);

        $data['is_published'] = $request->boolean('is_published');

        OrganizationActivity::query()->create($data);

        return redirect()->route('admin.activities.index')->with('status', 'Kegiatan ditambahkan.');
    }

    public function edit(OrganizationActivity $activity): View
    {
        return view('admin.activities.edit', compact('activity'));
    }

    public function update(Request $request, OrganizationActivity $activity): RedirectResponse
    {
        $this->ensureGalleryUploadLimit($request);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'activity_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:6144'],
            'gallery_files' => ['nullable', 'array'],
            'gallery_files.*' => ['image', 'max:1024'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ], [
            'gallery_files.*.max' => 'Maksimal ukuran tiap foto galeri adalah 1 MB.',
        ]);

        unset($data['image_file'], $data['gallery_files']);

        if ($request->hasFile('image_file')) {
            $data['image'] = $this->moveUploadedAssetToPublic($request->file('image_file'), 'kegiatan');
        }

        $galleryImages = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $galleryFile) {
                $galleryImages[] = $this->moveUploadedAssetToPublic($galleryFile, 'kegiatan_galeri');
            }
        } elseif (is_array($activity->gallery_images)) {
            $galleryImages = $activity->gallery_images;
        }

        if (Schema::hasColumn('organization_activities', 'gallery_images')) {
            $data['gallery_images'] = $galleryImages ?: null;
        }
        $data['sort_order'] = $data['sort_order'] ?? $activity->sort_order;

        $data['is_published'] = $request->boolean('is_published');

        $activity->update($data);

        return back()->with('status', 'Kegiatan diperbarui.');
    }

    public function destroy(OrganizationActivity $activity): RedirectResponse
    {
        $activity->delete();

        return redirect()->route('admin.activities.index')->with('status', 'Kegiatan dihapus.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer', 'exists:organization_activities,id'],
        ]);

        foreach ($data['order'] as $index => $id) {
            OrganizationActivity::query()->whereKey($id)->update(['sort_order' => $index + 1]);
        }

        return back()->with('status', 'Urutan kegiatan berhasil diperbarui.');
    }

    public function removeGalleryImage(Request $request, OrganizationActivity $activity): RedirectResponse
    {
        $data = $request->validate([
            'index' => ['required', 'integer', 'min:0'],
        ]);

        $galleryImages = is_array($activity->gallery_images) ? $activity->gallery_images : [];

        if (isset($galleryImages[$data['index']])) {
            unset($galleryImages[$data['index']]);
            $activity->gallery_images = array_values($galleryImages);
            $activity->save();
        }

        return back()->with('status', 'Foto galeri berhasil dihapus.');
    }

    private function ensureGalleryUploadLimit(Request $request): void
    {
        $galleryFiles = $request->file('gallery_files', []);

        if (is_array($galleryFiles) && count($galleryFiles) > 10) {
            throw ValidationException::withMessages([
                'gallery_files' => ['Maksimal 10 foto galeri per kegiatan.'],
            ]);
        }
    }

    private function moveUploadedAssetToPublic($uploadedFile, string $prefix): string
    {
        $targetDir = rtrim($_SERVER['DOCUMENT_ROOT'] ?? public_path(), DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR . 'mobilekit'
            . DIRECTORY_SEPARATOR . 'img';

        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = $prefix . '_' . time() . '_' . mt_rand(100, 999) . '.' . $uploadedFile->getClientOriginalExtension();
        $uploadedFile->move($targetDir, $fileName);

        return 'mobilekit/img/' . $fileName;
    }
}
