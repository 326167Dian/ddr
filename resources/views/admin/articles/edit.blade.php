@extends('layouts.admin')

@section('content')
    <div class="card"><div class="card-body">
        <h3>Edit Artikel</h3>
        <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="article-editor-layout">
                <div>
                    <div class="form-group basic"><label class="label">Judul</label><input id="article-title" name="title" class="form-control" value="{{ old('title', $article->title) }}" required></div>
                    <div class="form-group basic"><label class="label">Slug (opsional)</label><input name="slug" class="form-control" value="{{ old('slug', $article->slug) }}"></div>
                    <div class="form-group basic"><label class="label">Upload Cover Baru</label><input id="article-cover-file" type="file" name="cover_file" class="form-control" accept="image/*"></div>
                    <div class="form-group basic"><label class="label">Path Cover</label><input id="article-cover-path" name="cover_image" class="form-control" value="{{ old('cover_image', $article->cover_image) }}"></div>
                    <div class="form-group basic"><label class="label">Tanggal Publish</label><input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', optional($article->published_at)->format('Y-m-d\TH:i')) }}"></div>
                    <div class="form-group basic"><label class="label">Excerpt</label><textarea id="article-excerpt" name="excerpt" class="form-control" rows="2">{{ old('excerpt', $article->excerpt) }}</textarea></div>
                    <div class="form-group basic">
                        <label class="label">Konten Artikel</label>
                        <textarea id="article-content" name="content" class="form-control" rows="10">{{ old('content', $article->content) }}</textarea>
                    </div>
                </div>

                <div class="preview-surface">
                    <div id="article-preview-cover" class="preview-cover">
                        @if($article->cover_image)
                            <img src="{{ asset(old('cover_image', $article->cover_image)) }}" alt="cover preview" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            Preview cover artikel
                        @endif
                    </div>
                    <div class="preview-content">
                        <h3 id="article-preview-title">{{ old('title', $article->title) ?: 'Judul artikel' }}</h3>
                        <p id="article-preview-excerpt" class="text-secondary mb-3">{{ old('excerpt', $article->excerpt) ?: 'Excerpt artikel akan tampil di sini.' }}</p>
                        <div id="article-preview-content">{!! old('content', $article->content) ?: 'Konten artikel akan tampil live di panel ini.' !!}</div>
                    </div>
                </div>
            </div>
            <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }}><label class="form-check-label">Publish</label></div>
            <button class="btn btn-primary btn-block" type="submit">Simpan Perubahan</button>
        </form>
    </div></div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    (function () {
        const titleInput = document.getElementById('article-title');
        const excerptInput = document.getElementById('article-excerpt');
        const coverPathInput = document.getElementById('article-cover-path');
        const coverFileInput = document.getElementById('article-cover-file');
        const previewTitle = document.getElementById('article-preview-title');
        const previewExcerpt = document.getElementById('article-preview-excerpt');
        const previewContent = document.getElementById('article-preview-content');
        const previewCover = document.getElementById('article-preview-cover');

        const updateTextPreview = () => {
            previewTitle.textContent = titleInput.value || 'Judul artikel';
            previewExcerpt.textContent = excerptInput.value || 'Excerpt artikel akan tampil di sini.';
        };

        const setCoverFromPath = () => {
            previewCover.innerHTML = coverPathInput.value
                ? '<img src="/' + coverPathInput.value.replace(/^\/+/, '') + '" alt="cover preview" style="width:100%;height:100%;object-fit:cover;">'
                : 'Preview cover artikel';
        };

        const updateCoverPreview = () => {
            if (coverFileInput.files && coverFileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    previewCover.innerHTML = '<img src="' + event.target.result + '" alt="cover preview" style="width:100%;height:100%;object-fit:cover;">';
                };
                reader.readAsDataURL(coverFileInput.files[0]);
                return;
            }

            setCoverFromPath();
        };

        titleInput.addEventListener('input', updateTextPreview);
        excerptInput.addEventListener('input', updateTextPreview);
        coverFileInput.addEventListener('change', updateCoverPreview);
        coverPathInput.addEventListener('input', setCoverFromPath);

        updateTextPreview();
        updateCoverPreview();

        const form = document.getElementById('article-content').closest('form');

        ClassicEditor
            .create(document.querySelector('#article-content'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'insertTable', 'undo', 'redo']
            })
            .then((editor) => {
                previewContent.innerHTML = editor.getData() || 'Konten artikel akan tampil live di panel ini.';
                editor.model.document.on('change:data', () => {
                    previewContent.innerHTML = editor.getData() || 'Konten artikel akan tampil live di panel ini.';
                });

                form.addEventListener('submit', (event) => {
                    if (!editor.getData().trim()) {
                        event.preventDefault();
                        alert('Konten artikel tidak boleh kosong.');
                    }
                });
            })
            .catch((error) => {
                console.error(error);
            });
    })();
</script>
@endpush
