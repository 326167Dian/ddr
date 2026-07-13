<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin DDR' }}</title>
    <link rel="stylesheet" href="{{ asset('mobilekit/css/style.css') }}">
    <style>
        :root {
            --bs-primary: #0b5d37;
            --bs-primary-rgb: 11, 93, 55;
        }

        body { background: #f5f7f6; }
        .admin-wrap { max-width: 980px; margin: 0 auto; padding: 82px 14px 80px; }
        .admin-nav a { margin-right: 8px; margin-bottom: 8px; }
        .editor-toolbar { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 8px; }
        .editor-toolbar button { border: 1px solid #d2d8d4; background: #fff; border-radius: 6px; padding: 4px 10px; font-size: 12px; }
        .drag-handle { cursor: move; font-weight: 700; color: #0b5d37; user-select: none; }
        .drag-handle span { display: inline-flex; align-items: center; justify-content: center; min-width: 24px; }
        tr.drag-over-top { box-shadow: inset 0 2px 0 #0b5d37; }
        tr.drag-over-bottom { box-shadow: inset 0 -2px 0 #0b5d37; }
        .table-search { max-width: 260px; }
        .admin-toast {
            position: fixed;
            right: 16px;
            bottom: 24px;
            z-index: 1080;
            min-width: 220px;
            max-width: 320px;
            padding: 12px 14px;
            border-radius: 12px;
            background: #0b5d37;
            color: #fff;
            box-shadow: 0 12px 24px rgba(11, 93, 55, 0.24);
            opacity: 0;
            pointer-events: none;
            transform: translateY(10px);
            transition: opacity .2s ease, transform .2s ease;
        }
        .admin-toast.show {
            opacity: 1;
            transform: translateY(0);
        }
        .stat-card {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 28px rgba(11, 93, 55, 0.08);
            background: linear-gradient(135deg, #0b5d37, #87a03d);
            color: #fff;
        }
        .stat-card.soft {
            background: linear-gradient(135deg, #ffffff, #eef7ea);
            color: #113a24;
            border: 1px solid rgba(11, 93, 55, 0.08);
        }
        .stat-number {
            font-size: 34px;
            font-weight: 800;
            line-height: 1;
        }
        .stat-meta {
            font-size: 12px;
            opacity: .85;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        .dashboard-panels {
            display: grid;
            grid-template-columns: 1.2fr .8fr;
            gap: 12px;
            margin-top: 12px;
        }
        .panel-card {
            border-radius: 18px;
            border: 1px solid rgba(11, 93, 55, 0.08);
            box-shadow: 0 12px 28px rgba(11, 93, 55, 0.06);
        }
        .mini-progress {
            height: 10px;
            border-radius: 999px;
            background: #e7efe8;
            overflow: hidden;
        }
        .mini-progress span {
            display: block;
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #0b5d37, #87a03d);
        }
        .member-photo-preview {
            width: min(56px, 18vw);
            height: min(56px, 18vw);
            object-fit: cover;
            border-radius: 14px;
            border: 2px solid #fff;
            box-shadow: 0 4px 12px rgba(11, 93, 55, 0.15);
        }
        .form-image-preview {
            width: min(96px, 28vw);
            height: min(96px, 28vw);
            object-fit: cover;
            border-radius: 18px;
            border: 1px solid rgba(11, 93, 55, 0.1);
            box-shadow: 0 8px 18px rgba(11, 93, 55, 0.08);
        }
        .article-editor-layout {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 14px;
            align-items: start;
        }
        .preview-surface {
            min-height: 420px;
            border-radius: 18px;
            border: 1px solid rgba(11, 93, 55, 0.1);
            background: linear-gradient(180deg, #ffffff, #f7fbf5);
            box-shadow: 0 10px 24px rgba(11, 93, 55, 0.06);
            overflow: hidden;
        }
        .preview-cover {
            height: 180px;
            background: linear-gradient(135deg, #dceecf, #f7fbf4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7f71;
            font-size: 13px;
        }
        .preview-content {
            padding: 18px;
        }
        .preview-content h1,
        .preview-content h2,
        .preview-content h3 {
            color: #113a24;
        }
        .preview-content img {
            max-width: 100%;
            border-radius: 12px;
        }
        @media (max-width: 768px) {
            .dashboard-grid,
            .dashboard-panels,
            .article-editor-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="{{ route('admin.dashboard') }}" class="headerButton text-light"><ion-icon name="home-outline"></ion-icon></a>
        </div>
        <div class="pageTitle">Admin DDR</div>
        <div class="right">
            @auth
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="headerButton text-light" style="border:0;background:transparent">
                        <ion-icon name="log-out-outline"></ion-icon>
                    </button>
                </form>
            @endauth
        </div>
    </div>

    <div class="admin-wrap">
        @auth
            <div class="admin-nav mb-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary">Dashboard</a>
                <a href="{{ route('admin.profile.edit') }}" class="btn btn-sm btn-primary">Profil</a>
                <a href="{{ route('admin.structures.index') }}" class="btn btn-sm btn-primary">Struktur</a>
                <a href="{{ route('admin.histories.index') }}" class="btn btn-sm btn-primary">Sejarah</a>
                <a href="{{ route('admin.activities.index') }}" class="btn btn-sm btn-primary">Kegiatan</a>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-sm btn-primary">Artikel</a>
            </div>
        @endauth

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>

    <div id="admin-toast" class="admin-toast" aria-live="polite"></div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
        window.showAdminToast = function (message) {
            const toast = document.getElementById('admin-toast');
            if (!toast) return;

            toast.textContent = message;
            toast.classList.add('show');

            clearTimeout(window.__adminToastTimer);
            window.__adminToastTimer = setTimeout(() => {
                toast.classList.remove('show');
            }, 1800);
        };
    </script>
    @stack('scripts')
</body>

</html>
