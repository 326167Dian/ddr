<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'activity_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:6144'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        unset($data['image_file']);

        if ($request->hasFile('image_file')) {
            $imageFile = $request->file('image_file');
            $imageName = 'kegiatan_' . time() . '_' . mt_rand(100, 999) . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('mobilekit/img'), $imageName);
            $data['image'] = 'mobilekit/img/' . $imageName;
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
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'activity_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:6144'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        unset($data['image_file']);

        if ($request->hasFile('image_file')) {
            $imageFile = $request->file('image_file');
            $imageName = 'kegiatan_' . time() . '_' . mt_rand(100, 999) . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('mobilekit/img'), $imageName);
            $data['image'] = 'mobilekit/img/' . $imageName;
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
}
