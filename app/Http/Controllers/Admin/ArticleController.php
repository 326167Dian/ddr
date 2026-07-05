<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $items = OrganizationArticle::query()
            ->orderBy('sort_order')
            ->latest('published_at')
            ->get();

        return view('admin.articles.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.articles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:organization_articles,slug'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'cover_file' => ['nullable', 'image', 'max:6144'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        unset($data['cover_file']);

        if ($request->hasFile('cover_file')) {
            $coverFile = $request->file('cover_file');
            $coverName = 'artikel_' . time() . '_' . mt_rand(100, 999) . '.' . $coverFile->getClientOriginalExtension();
            $coverFile->move(public_path('mobilekit/img'), $coverName);
            $data['cover_image'] = 'mobilekit/img/' . $coverName;
        }

        $data['sort_order'] = $data['sort_order'] ?? ((int) OrganizationArticle::query()->max('sort_order') + 1);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');

        OrganizationArticle::query()->create($data);

        return redirect()->route('admin.articles.index')->with('status', 'Artikel ditambahkan.');
    }

    public function edit(OrganizationArticle $article): View
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, OrganizationArticle $article): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('organization_articles', 'slug')->ignore($article->id)],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'cover_file' => ['nullable', 'image', 'max:6144'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        unset($data['cover_file']);

        if ($request->hasFile('cover_file')) {
            $coverFile = $request->file('cover_file');
            $coverName = 'artikel_' . time() . '_' . mt_rand(100, 999) . '.' . $coverFile->getClientOriginalExtension();
            $coverFile->move(public_path('mobilekit/img'), $coverName);
            $data['cover_image'] = 'mobilekit/img/' . $coverName;
        }

        $data['sort_order'] = $data['sort_order'] ?? $article->sort_order;

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');

        $article->update($data);

        return back()->with('status', 'Artikel diperbarui.');
    }

    public function destroy(OrganizationArticle $article): RedirectResponse
    {
        $article->delete();

        return redirect()->route('admin.articles.index')->with('status', 'Artikel dihapus.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer', 'exists:organization_articles,id'],
        ]);

        foreach ($data['order'] as $index => $id) {
            OrganizationArticle::query()->whereKey($id)->update(['sort_order' => $index + 1]);
        }

        return back()->with('status', 'Urutan artikel berhasil diperbarui.');
    }
}
