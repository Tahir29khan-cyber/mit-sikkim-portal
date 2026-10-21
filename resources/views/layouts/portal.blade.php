<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>MIT Sikkim Student Resource Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,500;8..60,600;8..60,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
</head>
<body>
<div class="shell">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="brand">
            <div class="mark">MS</div>
            <h1>MIT Sikkim<br>Resource Portal</h1>
            <p>IMTS Institute · Noida</p>
        </div>
        
        <nav>
            <a href="{{ route('syllabus') }}" class="nav-btn {{ request()->routeIs('syllabus') ? 'active' : '' }}">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                <span>Syllabus & eBooks</span>
            </a>
            <a href="{{ route('theory') }}" class="nav-btn {{ request()->routeIs('theory') ? 'active' : '' }}">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                <span>Theory Paper</span>
            </a>
            <a href="{{ route('mcq') }}" class="nav-btn {{ request()->routeIs('mcq') ? 'active' : '' }}">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M8 12h8M8 8h8M8 16h5"/></svg>
                <span>MCQ Practice</span>
            </a>
            <a href="{{ route('contact') }}" class="nav-btn {{ request()->routeIs('contact') ? 'active' : '' }}">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg>
                <span>Contact Us</span>
            </a>
            <a href="{{ route('about') }}" class="nav-btn {{ request()->routeIs('about') ? 'active' : '' }}">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-5M12 8h.01"/></svg>
                <span>About</span>
            </a>
        </nav>

        <!-- ADMIN TOGGLES (Only visible to Admin) -->
        @if(session('role') === 'admin')
        <div class="sidebar-foot">
            <div class="admin-label">ADMIN · MODULE ACCESS</div>
            <form action="{{ route('admin.settings.update') }}" method="POST" id="toggleForm">
                @csrf
                <div class="admin-toggle-row">
                    <span>Theory Paper</span>
                    <label class="switch">
                        <input type="checkbox" name="theory_open" {{ \App\Models\Setting::bool('theory_open') ? 'checked' : '' }} onchange="document.getElementById('toggleForm').submit()">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="admin-toggle-row">
                    <span>MCQ Practice</span>
                    <label class="switch">
                        <input type="checkbox" name="mcq_open" {{ \App\Models\Setting::bool('mcq_open') ? 'checked' : '' }} onchange="document.getElementById('toggleForm').submit()">
                        <span class="slider"></span>
                    </label>
                </div>
            </form>
            <form action="{{ route('admin.logout') }}" method="POST" style="margin-top: 10px;">
                @csrf
                <button class="logout" type="submit" style="width:100%;">Log out Admin</button>
            </form>
        </div>
        @else
        <div class="sidebar-foot">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="logout" type="submit" style="width:100%;">Log out Student</button>
            </form>
        </div>
        @endif
    </aside>

    <!-- MAIN CONTENT -->
    <main>
        @yield('content')
    </main>
</div>
</body>
</html>