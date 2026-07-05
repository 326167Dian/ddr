@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="mb-0">Sejarah Organisasi</h3>
        <div>
            <a href="{{ route('admin.histories.create') }}" class="btn btn-primary btn-sm">Tambah</a>
        </div>
    </div>

    <div class="mb-2">
        <input type="text" id="search-histories" class="form-control table-search" placeholder="Cari tahun atau judul...">
    </div>

    <div class="card"><div class="table-responsive"><table class="table">
        <thead><tr><th>Drag</th><th>Urut</th><th>Tahun</th><th>Judul</th><th>Aksi</th></tr></thead>
        <tbody id="sortable-histories">
        @forelse($items as $item)
            <tr data-id="{{ $item->id }}">
                <td class="drag-handle"><span draggable="true">↕</span></td><td class="sort-number">{{ $item->sort_order }}</td><td>{{ $item->year }}</td><td>{{ $item->title }}</td>
                <td>
                    <a href="{{ route('admin.histories.edit', $item) }}" class="btn btn-secondary btn-sm">Edit</a>
                    <form action="{{ route('admin.histories.destroy', $item) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">Hapus</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">Belum ada data.</td></tr>
        @endforelse
        </tbody>
    </table></div></div>
@endsection

@push('scripts')
<script>
    (function () {
        const tbody = document.getElementById('sortable-histories');
        const searchInput = document.getElementById('search-histories');
        if (!tbody) return;

        let draggingRow = null;
        let isDirty = false;

        const persistOrder = async () => {
            const order = Array.from(tbody.querySelectorAll('tr[data-id]')).map((row) => Number(row.dataset.id));
            const response = await fetch('{{ route('admin.histories.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ order })
            });
            if (response.ok) {
                isDirty = false;
            }
        };

        const refreshNumbers = () => {
            tbody.querySelectorAll('tr[data-id]').forEach((row, index) => {
                const cell = row.querySelector('.sort-number');
                if (cell) cell.textContent = String(index + 1);
            });
        };

        tbody.querySelectorAll('tr[data-id]').forEach((row) => {
            const handle = row.querySelector('.drag-handle span');
            if (!handle) return;

            handle.addEventListener('dragstart', () => { draggingRow = row; row.classList.add('table-active'); });
            handle.addEventListener('dragend', async () => {
                row.classList.remove('table-active');
                tbody.querySelectorAll('tr').forEach((tr) => tr.classList.remove('drag-over-top', 'drag-over-bottom'));
                draggingRow = null;
                refreshNumbers();
                if (isDirty) {
                    await persistOrder();
                    if (window.showAdminToast) window.showAdminToast('Urutan sejarah tersimpan');
                }
            });
            row.addEventListener('dragover', (event) => {
                event.preventDefault();
                if (!draggingRow || draggingRow === row) return;
                tbody.querySelectorAll('tr').forEach((tr) => tr.classList.remove('drag-over-top', 'drag-over-bottom'));
                const rect = row.getBoundingClientRect();
                const before = (event.clientY - rect.top) < rect.height / 2;
                if (before) {
                    row.classList.add('drag-over-top');
                    tbody.insertBefore(draggingRow, row);
                } else {
                    row.classList.add('drag-over-bottom');
                    tbody.insertBefore(draggingRow, row.nextSibling);
                }
                isDirty = true;
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', () => {
                const keyword = searchInput.value.toLowerCase();
                tbody.querySelectorAll('tr[data-id]').forEach((row) => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(keyword) ? '' : 'none';
                });
            });
        }
    })();
</script>
@endpush
