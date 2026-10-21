<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard — MIT Sikkim Resource Portal</title>
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
</head>
<body>
<div class="shell">
    <!-- Admin Sidebar -->
    <aside class="sidebar">
        <div class="brand">
            <div class="mark admin-mark">AD</div>
            <h1>Admin<br>Dashboard</h1>
            <p>IMTS Institute · Noida</p>
        </div>
        <nav>
            <span class="nav-btn active">Module Access</span>
        </nav>
        <div class="sidebar-foot">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="logout" type="submit" style="width:100%;">Log out</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main>
        <div class="topline">
            <div>
                <h2>Module Access</h2>
                <div class="sub">Control which modules students can currently use — changes apply instantly.</div>
            </div>
        </div>

        @if(session('status'))
            <div class="banner" style="background:#e9f7ef; color:var(--good); border:1px solid #c7ead6;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            <div class="doc-grid" style="grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); margin-bottom:22px;">
                
                <!-- Theory Paper Toggle -->
                <div class="doc-card">
                    <div class="tag">MODULE</div>
                    <h3>Theory Paper</h3>
                    <div class="meta">Online exam paper access for students</div>
                    <label class="toggle-row">
                        <span>Currently <b>{{ old('theory_open', $theoryOpen) ? 'open' : 'closed' }}</b></span>
                        <input type="checkbox" name="theory_open" value="1" {{ old('theory_open', $theoryOpen) ? 'checked' : '' }}>
                    </label>
                </div>

                <!-- MCQ Practice Toggle -->
                <div class="doc-card">
                    <div class="tag">MODULE</div>
                    <h3>MCQ Practice</h3>
                    <div class="meta">Self-practice MCQ sets for students</div>
                    <label class="toggle-row">
                        <span>Currently <b>{{ old('mcq_open', $mcqOpen) ? 'open' : 'closed' }}</b></span>
                        <input type="checkbox" name="mcq_open" value="1" {{ old('mcq_open', $mcqOpen) ? 'checked' : '' }}>
                    </label>
                </div>

            </div>
            <button class="dl" type="submit" style="padding:10px 22px; font-size:14px;">Save Changes</button>
        </form>
    </main>
</div>
</body>
</html>