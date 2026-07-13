@extends('layouts.mobile')

@section('content')
    <div class="section mt-3">
        <div class="card ddr-soft-card">
            @if($activity->image)
                <img src="{{ asset($activity->image) }}" class="card-img-top" alt="kegiatan">
            @endif
            <div class="card-body">
                <h3 class="mb-1">{{ $activity->title }}</h3>
                <p class="text-secondary mb-2">{{ optional($activity->activity_date)->format('d M Y') }} · {{ $activity->location }}</p>
                <div style="white-space: pre-line">{{ $activity->description }}</div>
            </div>
        </div>

        @if(!empty($activity->gallery_images))
            <div class="card ddr-soft-card mt-3">
                <div class="card-body">
                    <h5 class="mb-3">Dokumentasi Foto</h5>
                    <div class="row g-2">
                        @foreach($activity->gallery_images as $galleryImage)
                            <div class="col-6 col-md-4">
                                <button type="button" class="btn btn-link p-0 border-0 w-100 text-start gallery-preview-trigger" data-gallery-image="{{ asset($galleryImage) }}" data-bs-toggle="modal" data-bs-target="#galleryPreviewModal">
                                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                                        <img src="{{ asset($galleryImage) }}" class="card-img-top" alt="Dokumentasi kegiatan" style="height:180px; object-fit:contain; padding:10px; background:#f5f8f3;">
                                        <div class="card-body p-2 text-center">
                                            <span class="btn btn-sm btn-primary w-100">Preview</span>
                                        </div>
                                    </div>
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center p-0 position-relative">
                    <button type="button" id="galleryPrevButton" class="btn btn-light rounded-circle position-absolute start-0 ms-3 d-flex align-items-center justify-content-center" style="width:46px; height:46px; z-index:2;" aria-label="Foto sebelumnya">‹</button>
                    <div class="d-flex align-items-center justify-content-center w-100 h-100 position-relative gallery-preview-viewport">
                        <img id="galleryPreviewImage" src="" alt="Preview foto kegiatan" class="gallery-preview-image" style="transition:transform .2s ease; transform:scale(1);">
                    </div>
                    <button type="button" id="galleryNextButton" class="btn btn-light rounded-circle position-absolute end-0 me-3 d-flex align-items-center justify-content-center" style="width:46px; height:46px; z-index:2;" aria-label="Foto berikutnya">›</button>
                    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3 d-flex gap-2" style="z-index:3;">
                        <button type="button" id="galleryZoomOutButton" class="btn btn-light rounded-circle" aria-label="Perkecil">−</button>
                        <button type="button" id="galleryZoomInButton" class="btn btn-light rounded-circle" aria-label="Perbesar">+</button>
                        <button type="button" id="galleryResetZoomButton" class="btn btn-light rounded-pill px-3" aria-label="Reset zoom">100%</button>
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
        .gallery-preview-viewport {
            padding: 24px;
            min-height: calc(100vh - 130px);
            overflow: auto;
        }

        .gallery-preview-image {
            max-width: calc(100vw - 120px);
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
                max-width: calc(100vw - 40px);
                max-height: calc(100vh - 220px);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const previewImage = document.getElementById('galleryPreviewImage');
            const downloadLink = document.getElementById('galleryDownloadLink');
            const prevButton = document.getElementById('galleryPrevButton');
            const nextButton = document.getElementById('galleryNextButton');
            const zoomInButton = document.getElementById('galleryZoomInButton');
            const zoomOutButton = document.getElementById('galleryZoomOutButton');
            const resetZoomButton = document.getElementById('galleryResetZoomButton');
            const galleryTriggers = Array.from(document.querySelectorAll('.gallery-preview-trigger'));
            const previewModal = document.getElementById('galleryPreviewModal');
            let currentIndex = 0;
            let touchStartX = 0;
            let touchEndX = 0;
            let currentScale = 1;

            function updatePreview(index) {
                if (galleryTriggers.length === 0) return;
                currentIndex = index;
                const imageUrl = galleryTriggers[index].dataset.galleryImage;
                previewImage.src = imageUrl;
                downloadLink.href = imageUrl;
                downloadLink.setAttribute('download', imageUrl.split('/').pop());
                currentScale = 1;
                previewImage.style.transform = 'scale(' + currentScale + ')';
            }

            function updateZoom(newScale) {
                currentScale = Math.max(1, Math.min(3, newScale));
                previewImage.style.transform = 'scale(' + currentScale + ')';
            }

            galleryTriggers.forEach(function (trigger, index) {
                trigger.addEventListener('click', function () {
                    updatePreview(index);
                });
            });

            prevButton.addEventListener('click', function () {
                if (galleryTriggers.length === 0) return;
                const nextIndex = (currentIndex - 1 + galleryTriggers.length) % galleryTriggers.length;
                updatePreview(nextIndex);
            });

            nextButton.addEventListener('click', function () {
                if (galleryTriggers.length === 0) return;
                const nextIndex = (currentIndex + 1) % galleryTriggers.length;
                updatePreview(nextIndex);
            });

            zoomInButton.addEventListener('click', function () {
                updateZoom(currentScale + 0.5);
            });

            zoomOutButton.addEventListener('click', function () {
                updateZoom(currentScale - 0.5);
            });

            resetZoomButton.addEventListener('click', function () {
                updateZoom(1);
            });

            previewModal.addEventListener('touchstart', function (event) {
                touchStartX = event.changedTouches[0].screenX;
            }, { passive: true });

            previewModal.addEventListener('touchend', function (event) {
                touchEndX = event.changedTouches[0].screenX;
                const delta = touchEndX - touchStartX;

                if (Math.abs(delta) < 50) return;

                if (delta < 0) {
                    const nextIndex = (currentIndex + 1) % galleryTriggers.length;
                    updatePreview(nextIndex);
                } else {
                    const nextIndex = (currentIndex - 1 + galleryTriggers.length) % galleryTriggers.length;
                    updatePreview(nextIndex);
                }
            }, { passive: true });

            previewModal.addEventListener('wheel', function (event) {
                if (! previewModal.classList.contains('show')) {
                    return;
                }

                event.preventDefault();

                if (event.deltaY < 0) {
                    updateZoom(currentScale + 0.2);
                } else {
                    updateZoom(currentScale - 0.2);
                }
            }, { passive: false });

            document.addEventListener('keydown', function (event) {
                if (! previewModal.classList.contains('show')) {
                    return;
                }

                if (event.key === 'ArrowRight') {
                    const nextIndex = (currentIndex + 1) % galleryTriggers.length;
                    updatePreview(nextIndex);
                }

                if (event.key === 'ArrowLeft') {
                    const nextIndex = (currentIndex - 1 + galleryTriggers.length) % galleryTriggers.length;
                    updatePreview(nextIndex);
                }

                if (event.key === '+') {
                    updateZoom(currentScale + 0.5);
                }

                if (event.key === '-') {
                    updateZoom(currentScale - 0.5);
                }

                if (event.key === 'Escape') {
                    const modal = bootstrap.Modal.getInstance(previewModal);
                    modal.hide();
                }
            });
        });
    </script>
@endpush
