@extends('layouts.admin')

@section('content')
    <div class="card"><div class="card-body">
        <h3>Edit Berita</h3>
        <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="article-editor-layout">
                <div>
                    <div class="form-group basic"><label class="label">Judul</label><input id="news-title" name="title" class="form-control" value="{{ old('title', $news->title) }}" required></div>
                    <div class="form-group basic"><label class="label">Slug (opsional)</label><input name="slug" class="form-control" value="{{ old('slug', $news->slug) }}"></div>
                    <div class="form-group basic"><label class="label">Upload Gambar Utama Baru</label><input id="news-image-file" type="file" name="image_file" class="form-control" accept="image/*"></div>
                    <div class="form-group basic"><label class="label">Path Gambar</label><input id="news-image-path" name="image" class="form-control" value="{{ old('image', $news->image) }}"></div>
                    <div class="form-group basic">
                        <label class="label">Upload Galeri Berita Baru</label>
                        <input type="file" name="gallery_files[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">Maksimal 10 foto galeri per berita dan maksimal 1 MB per foto.</small>
                    </div>
                    <div class="form-group basic"><label class="label">Tanggal Publish</label><input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', optional($news->published_at)->format('Y-m-d\TH:i')) }}"></div>
                    <div class="form-group basic"><label class="label">Excerpt</label><textarea id="news-excerpt" name="excerpt" class="form-control" rows="2">{{ old('excerpt', $news->excerpt) }}</textarea></div>
                    <div class="form-group basic">
                        <label class="label">Konten Berita</label>
                        <textarea id="news-content" name="content" class="form-control" rows="10">{{ old('content', $news->content) }}</textarea>
                    </div>
                </div>

                <div class="preview-surface">
                    <div id="news-preview-cover" class="preview-cover">
                        @if($news->image)
                            <img src="{{ asset(old('image', $news->image)) }}" alt="preview gambar" style="width:100%;height:100%;object-fit:contain;background:#f8f9fa;">
                        @else
                            Preview gambar berita
                        @endif
                    </div>
                    <div class="preview-content">
                        <h3 id="news-preview-title">{{ old('title', $news->title) ?: 'Judul berita' }}</h3>
                        <p id="news-preview-excerpt" class="text-secondary mb-3">{{ old('excerpt', $news->excerpt) ?: 'Excerpt berita akan tampil di sini.' }}</p>
                        <div id="news-preview-content">{!! old('content', $news->content) ?: 'Konten berita akan tampil live di panel ini.' !!}</div>
                    </div>
                </div>
            </div>
            <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published', $news->is_published) ? 'checked' : '' }}><label class="form-check-label">Publish</label></div>
            <button class="btn btn-primary btn-block" type="submit">Simpan Perubahan</button>
        </form>

        @if(!empty($news->gallery_images))
            <div class="form-group basic mt-3">
                <label class="label">Galeri Saat Ini</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($news->gallery_images as $galleryIndex => $galleryImage)
                        <div class="text-center">
                            <img src="{{ asset($galleryImage) }}" alt="galeri berita" style="width:120px; height:90px; object-fit:cover; border-radius:8px;">
                            <div class="mt-1 d-flex gap-2 justify-content-center">
                                <a href="{{ asset($galleryImage) }}" download class="btn btn-sm btn-outline-primary">Download</a>
                                <form method="POST" action="{{ route('admin.news.gallery.remove', $news) }}">
                                    @csrf
                                    <input type="hidden" name="index" value="{{ $galleryIndex }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div></div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    (function () {
        const titleInput = document.getElementById('news-title');
        const excerptInput = document.getElementById('news-excerpt');
        const imagePathInput = document.getElementById('news-image-path');
        const imageFileInput = document.getElementById('news-image-file');
        const previewTitle = document.getElementById('news-preview-title');
        const previewExcerpt = document.getElementById('news-preview-excerpt');
        const previewContent = document.getElementById('news-preview-content');
        const previewCover = document.getElementById('news-preview-cover');

        const updateTextPreview = () => {
            previewTitle.textContent = titleInput.value || 'Judul berita';
            previewExcerpt.textContent = excerptInput.value || 'Excerpt berita akan tampil di sini.';
        };

        const setCoverFromPath = () => {
            previewCover.innerHTML = imagePathInput.value
                ? '<img src="/' + imagePathInput.value.replace(/^\/+/, '') + '" alt="preview gambar" style="width:100%;height:100%;object-fit:contain;background:#f8f9fa;">'
                : 'Preview gambar berita';
        };

        const updateCoverPreview = () => {
            if (imageFileInput.files && imageFileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    previewCover.innerHTML = '<img src="' + event.target.result + '" alt="preview gambar" style="width:100%;height:100%;object-fit:contain;background:#f8f9fa;">';
                };
                reader.readAsDataURL(imageFileInput.files[0]);
                return;
            }

            setCoverFromPath();
        };

        titleInput.addEventListener('input', updateTextPreview);
        excerptInput.addEventListener('input', updateTextPreview);
        imageFileInput.addEventListener('change', updateCoverPreview);
        imagePathInput.addEventListener('input', setCoverFromPath);

        updateTextPreview();
        updateCoverPreview();

        const form = document.getElementById('news-content').closest('form');

        ClassicEditor
            .create(document.querySelector('#news-content'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'insertTable', 'undo', 'redo']
            })
            .then((editor) => {
                previewContent.innerHTML = editor.getData() || 'Konten berita akan tampil live di panel ini.';
                editor.model.document.on('change:data', () => {
                    previewContent.innerHTML = editor.getData() || 'Konten berita akan tampil live di panel ini.';
                });

                form.addEventListener('submit', (event) => {
                    if (!editor.getData().trim()) {
                        event.preventDefault();
                        alert('Konten berita tidak boleh kosong.');
                    }
                });
            })
            .catch((error) => {
                console.error(error);
            });
    })();
</script>
@endpush
