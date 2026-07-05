<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function index(): View
    {
        $items = OrganizationHistory::query()->orderBy('sort_order')->get();

        return view('admin.histories.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.histories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'year' => ['required', 'string', 'max:20'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;

        OrganizationHistory::query()->create($data);

        return redirect()->route('admin.histories.index')->with('status', 'Data sejarah ditambahkan.');
    }

    public function edit(OrganizationHistory $history): View
    {
        return view('admin.histories.edit', compact('history'));
    }

    public function update(Request $request, OrganizationHistory $history): RedirectResponse
    {
        $data = $request->validate([
            'year' => ['required', 'string', 'max:20'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;

        $history->update($data);

        return back()->with('status', 'Data sejarah diperbarui.');
    }

    public function destroy(OrganizationHistory $history): RedirectResponse
    {
        $history->delete();

        return redirect()->route('admin.histories.index')->with('status', 'Data sejarah dihapus.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer', 'exists:organization_histories,id'],
        ]);

        foreach ($data['order'] as $index => $id) {
            OrganizationHistory::query()->whereKey($id)->update(['sort_order' => $index + 1]);
        }

        return back()->with('status', 'Urutan sejarah berhasil diperbarui.');
    }
}
