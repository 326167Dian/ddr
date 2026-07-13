<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationStructure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StructureController extends Controller
{
    public function index(): View
    {
        $items = OrganizationStructure::query()->orderBy('sort_order')->get();

        return view('admin.structures.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.structures.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'string', 'max:255'],
            'photo_file' => ['nullable', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        unset($data['photo_file']);

        if ($request->hasFile('photo_file')) {
            $photoFile = $request->file('photo_file');
            $photoName = 'struktur_' . time() . '_' . mt_rand(100, 999) . '.' . $photoFile->getClientOriginalExtension();
            $targetDir = rtrim($_SERVER['DOCUMENT_ROOT'] ?? public_path(), DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR . 'mobilekit'
                . DIRECTORY_SEPARATOR . 'img';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $photoFile->move($targetDir, $photoName);
            $data['photo'] = 'mobilekit/img/' . $photoName;
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        OrganizationStructure::query()->create($data);

        return redirect()->route('admin.structures.index')->with('status', 'Data struktur ditambahkan.');
    }

    public function edit(OrganizationStructure $structure): View
    {
        return view('admin.structures.edit', compact('structure'));
    }

    public function update(Request $request, OrganizationStructure $structure): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'string', 'max:255'],
            'photo_file' => ['nullable', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        unset($data['photo_file']);

        if ($request->hasFile('photo_file')) {
            $photoFile = $request->file('photo_file');
            $photoName = 'struktur_' . time() . '_' . mt_rand(100, 999) . '.' . $photoFile->getClientOriginalExtension();
            $targetDir = rtrim($_SERVER['DOCUMENT_ROOT'] ?? public_path(), DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR . 'mobilekit'
                . DIRECTORY_SEPARATOR . 'img';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $photoFile->move($targetDir, $photoName);
            $data['photo'] = 'mobilekit/img/' . $photoName;
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $structure->update($data);

        return back()->with('status', 'Data struktur diperbarui.');
    }

    public function destroy(OrganizationStructure $structure): RedirectResponse
    {
        $structure->delete();

        return redirect()->route('admin.structures.index')->with('status', 'Data struktur dihapus.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer', 'exists:organization_structures,id'],
        ]);

        foreach ($data['order'] as $index => $id) {
            OrganizationStructure::query()->whereKey($id)->update(['sort_order' => $index + 1]);
        }

        return back()->with('status', 'Urutan struktur berhasil diperbarui.');
    }
}
