@extends('layouts.mobile')

@section('content')
    <div class="section mt-3">
        <div class="card ddr-soft-card">
            @if ($activity->image)
                <img src="{{ asset($activity->image) }}" class="card-img-top" alt="kegiatan">
            @endif
            <div class="card-body">
                <h3 class="mb-1">{{ $activity->title }}</h3>
                <p class="text-secondary mb-2">{{ optional($activity->activity_date)->format('d M Y') }} ·
                    {{ $activity->location }}</p>
                <div style="white-space: pre-line">{{ $activity->description }}</div>
            </div>
        </div>

        @if (!empty($activity->gallery_images))
            <div class="card ddr-soft-card mt-3">
                <div class="card-body">
                    <h5 class="mb-3">Dokumentasi Foto</h5>
                    <div class="row g-2">
                        @foreach ($activity->gallery_images as $galleryImage)
                            <div class="col-6">
                                <button type="button" class="gallery-preview-trigger"
                                    data-gallery-image="{{ asset($galleryImage) }}">
                                    <span class="gallery-item">
                                        <img src="{{ asset($galleryImage) }}" class="gallery-thumb"
                                            alt="Dokumentasi kegiatan" loading="lazy">
                                    </span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade gallery-preview-modal" id="galleryPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-centered">
            <div class="modal-content bg-dark border-0">
                <div class="modal-header border-0 bg-dark text-white">
                    <h5 class="modal-title">Preview Foto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center p-0">
                    <div class="d-flex align-items-center justify-content-center w-100 h-100 gallery-preview-viewport">
                        <img id="galleryPreviewImage" src="" alt="Preview foto kegiatan"
                            class="gallery-preview-image">
                    </div>
                </div>
                <div class="modal-footer border-0 bg-dark justify-content-center gap-2">
                    <a id="galleryDownloadLink" href="#" class="btn btn-light" download>Download</a>
                </div>
            </div>
        </div>
    </div>

    <div class="section mt-3 mb-5">
        <a href="{{ route('activities') }}" class="btn btn-primary btn-block">Kembali ke Daftar Kegiatan</a>
    </div>
@endsection

@push('styles')
    <style>
        .gallery-preview-trigger {
            display: block;
            width: 100%;
            padding: 0;
            border-radius: 8px;
            overflow: hidden;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
        }

        .gallery-item {
            /* Kotak pembungkus dibuat rasio 4:3 lewat padding-top agar didukung semua browser. */
            display: block;
            position: relative;
            width: 100%;
            padding-top: 75%;
            background: #f8f9fa;
        }

        .gallery-thumb {
            /* object-fit: contain agar foto tampil utuh, tidak terpotong. */
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        /* Tampilan Modal Viewport */
        .gallery-preview-viewport {
            padding: 24px;
            min-height: calc(100vh - 130px);
        }

        .gallery-preview-image {
            max-width: calc(100vw - 40px);
            max-height: calc(100vh - 180px);
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 12px;
            display: block;
            background: rgba(255, 255, 255, 0.04);
        }

        @media (max-width: 768px) {
            .gallery-preview-viewport {
                padding: 14px;
            }

            .gallery-preview-image {
                max-width: calc(100vw - 20px);
                max-height: calc(100vh - 200px);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const previewImage = document.getElementById('galleryPreviewImage');
            const downloadLink = document.getElementById('galleryDownloadLink');
            const galleryTriggers = Array.from(document.querySelectorAll('.gallery-preview-trigger'));
            const previewModalEl = document.getElementById('galleryPreviewModal');

            if (galleryTriggers.length === 0 || !previewModalEl) {
                return;
            }

            const previewModal = new bootstrap.Modal(previewModalEl);

            function openPreview(imageUrl) {
                previewImage.src = imageUrl;
                downloadLink.href = imageUrl;
                downloadLink.dataset.filename = imageUrl.split('/').pop().split('?')[0];
                previewModal.show();
            }

            galleryTriggers.forEach(function (trigger) {
                trigger.addEventListener('click', function () {
                    openPreview(trigger.dataset.galleryImage);
                });
            });

            downloadLink.addEventListener('click', function (event) {
                event.preventDefault();

                const imageUrl = downloadLink.href;
                const filename = downloadLink.dataset.filename || 'foto-kegiatan.jpg';

                fetch(imageUrl)
                    .then(function (response) {
                        return response.blob();
                    })
                    .then(function (blob) {
                        const blobUrl = URL.createObjectURL(blob);
                        const tempLink = document.createElement('a');
                        tempLink.href = blobUrl;
                        tempLink.download = filename;
                        document.body.appendChild(tempLink);
                        tempLink.click();
                        tempLink.remove();
                        URL.revokeObjectURL(blobUrl);
                    })
                    .catch(function () {
                        window.open(imageUrl, '_blank');
                    });
            });
        });
    </script>
@endpush
