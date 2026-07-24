<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationNews;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $items = OrganizationNews::query()
            ->orderBy('sort_order')
            ->latest('published_at')
            ->get();

        return view('admin.news.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureGalleryUploadLimit($request);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:organization_news,slug'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:6144'],
            'gallery_files' => ['nullable', 'array'],
            'gallery_files.*' => ['image', 'max:1024'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ], [
            'gallery_files.*.max' => 'Maksimal ukuran tiap foto galeri adalah 1 MB.',
        ]);

        unset($data['image_file'], $data['gallery_files']);

        if ($request->hasFile('image_file')) {
            $data['image'] = $this->moveUploadedAssetToPublic($request->file('image_file'), 'berita');
        }

        $galleryImages = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $galleryFile) {
                $galleryImages[] = $this->moveUploadedAssetToPublic($galleryFile, 'berita_galeri');
            }
        }

        $data['gallery_images'] = $galleryImages ?: null;
        $data['sort_order'] = $data['sort_order'] ?? ((int) OrganizationNews::query()->max('sort_order') + 1);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');

        OrganizationNews::query()->create($data);

        return redirect()->route('admin.news.index')->with('status', 'Berita ditambahkan.');
    }

    public function edit(OrganizationNews $news): View
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, OrganizationNews $news): RedirectResponse
    {
        $this->ensureGalleryUploadLimit($request);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('organization_news', 'slug')->ignore($news->id)],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:6144'],
            'gallery_files' => ['nullable', 'array'],
            'gallery_files.*' => ['image', 'max:1024'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ], [
            'gallery_files.*.max' => 'Maksimal ukuran tiap foto galeri adalah 1 MB.',
        ]);

        unset($data['image_file'], $data['gallery_files']);

        if ($request->hasFile('image_file')) {
            $data['image'] = $this->moveUploadedAssetToPublic($request->file('image_file'), 'berita');
        }

        $galleryImages = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $galleryFile) {
                $galleryImages[] = $this->moveUploadedAssetToPublic($galleryFile, 'berita_galeri');
            }
        } elseif (is_array($news->gallery_images)) {
            $galleryImages = $news->gallery_images;
        }

        $data['gallery_images'] = $galleryImages ?: null;
        $data['sort_order'] = $data['sort_order'] ?? $news->sort_order;
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');

        $news->update($data);

        return back()->with('status', 'Berita diperbarui.');
    }

    public function destroy(OrganizationNews $news): RedirectResponse
    {
        $news->delete();

        return redirect()->route('admin.news.index')->with('status', 'Berita dihapus.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer', 'exists:organization_news,id'],
        ]);

        foreach ($data['order'] as $index => $id) {
            OrganizationNews::query()->whereKey($id)->update(['sort_order' => $index + 1]);
        }

        return back()->with('status', 'Urutan berita berhasil diperbarui.');
    }

    public function removeGalleryImage(Request $request, OrganizationNews $news): RedirectResponse
    {
        $data = $request->validate([
            'index' => ['required', 'integer', 'min:0'],
        ]);

        $galleryImages = is_array($news->gallery_images) ? $news->gallery_images : [];

        if (isset($galleryImages[$data['index']])) {
            unset($galleryImages[$data['index']]);
            $news->gallery_images = array_values($galleryImages);
            $news->save();
        }

        return back()->with('status', 'Foto galeri berhasil dihapus.');
    }

    private function ensureGalleryUploadLimit(Request $request): void
    {
        $galleryFiles = $request->file('gallery_files', []);

        if (is_array($galleryFiles) && count($galleryFiles) > 10) {
            throw ValidationException::withMessages([
                'gallery_files' => ['Maksimal 10 foto galeri per berita.'],
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
