<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login — MIT Sikkim</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,500;8..60,600;8..60,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
</head>
<body class="auth-body" style="display:flex; align-items:center; justify-content:center; min-height:100vh; background:var(--blue-50);">
    <div class="gate">
        <!-- Role Tabs -->
        <div style="display:flex; gap:4px; background:var(--blue-50); border:1px solid var(--line); border-radius:9px; padding:4px; margin-bottom:22px;">
            <a href="{{ route('login') }}" style="flex:1; text-align:center; padding:8px 0; border-radius:6px; font-size:13px; font-weight:600; color:{{ $role === 'student' ? '#fff' : 'var(--slate-600)' }}; background:{{ $role === 'student' ? 'var(--blue-600)' : 'transparent' }};">Student</a>
            <a href="{{ route('admin.login') }}" style="flex:1; text-align:center; padding:8px 0; border-radius:6px; font-size:13px; font-weight:600; color:{{ $role === 'admin' ? '#fff' : 'var(--slate-600)' }}; background:{{ $role === 'admin' ? 'var(--blue-600)' : 'transparent' }};">Admin</a>
        </div>

        @if($role === 'admin')
            <div class="mark" style="background:var(--blue-900); color:#fff;">AD</div>
            <h2>Admin Login</h2>
            <p>Sign in to manage module access and site settings.</p>
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <input type="text" name="email" placeholder="Admin Username" autocomplete="off" value="{{ old('email') }}">
                <input type="password" name="password" placeholder="Password" autocomplete="off">
                <button type="submit">Sign In</button>
            </form>
            @error('email') <div class="err">{{ $message }}</div> @enderror
        @else
            <div class="mark">TP</div>
            <h2>Student Login</h2>
            <p>Enter your enrollment number to access your resources.</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="text" name="enrollment" placeholder="Enrollment Number" autocomplete="off" value="{{ old('enrollment') }}">
                <button type="submit">Continue</button>
            </form>
            @error('enrollment') <div class="err">{{ $message }}</div> @enderror
        @endif
    </div>
</body>
</html>