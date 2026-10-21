@extends('layouts.portal')

@section('content')
<div class="topline">
    <div><h2>Syllabus & eBooks</h2><div class="sub">Browse and download the latest syllabus copies and reference eBooks.</div></div>
    <span class="pill">{{ count($docs) }} documents</span>
</div>

<div class="session-bar">
    <span>Logged in as enrollment <b>{{ session('student_enrollment') }}</b> · Currently in <b>Semester {{ session('student_semester') }}</b></span>
</div>

<form method="GET" action="{{ route('syllabus') }}" class="filters">
    <!-- Programme Filter -->
    <select name="programme" onchange="this.form.submit()">
        <option value="">All programmes</option>
        @foreach($programmes as $p)
            <option value="{{ $p }}" {{ request('programme') == $p ? 'selected' : '' }}>{{ $p }}</option>
        @endforeach
    </select>

    <!-- Specialisation Filter -->
    <select name="specialisation" onchange="this.form.submit()">
        <option value="">All specialisations</option>
        @foreach($specialisations as $s)
            <option value="{{ $s }}" {{ request('specialisation') == $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>

    <!-- Semester Filter -->
    <select name="semester" onchange="this.form.submit()">
        <option value="">All semesters</option>
        @foreach($semesters as $sem)
            <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>Semester {{ $sem }}</option>
        @endforeach
    </select>
</form>

<div class="doc-grid">
    @forelse($docs as $d)
        @php $locked = $d['locked'] ?? false; @endphp
        <div class="doc-card {{ $locked ? 'locked' : '' }}">
            <div class="tag">
                {{ $d['programme'] ?? '' }} · {{ $d['specialisation'] ?? '' }} 
                @if(!empty($d['semester'])) · Sem {{ $d['semester'] }} @endif
                @if($locked) · <span class="lock">LOCKED</span> @endif
            </div>
            <h3>{{ $d['subject'] ?? $d['subject_name'] ?? 'Document' }}</h3>
            <div class="meta">{{ basename($d['file'] ?? 'file.pdf') }}</div>
            
            @if($locked)
                <div class="access-err">You're not able to see this yet — it unlocks in Semester {{ $d['semester'] }}.</div>
            @else
                <a href="{{ $d['link'] ?? '#' }}" target="_blank" class="dl">Download PDF</a>
            @endif
        </div>
    @empty
        <div class="empty">No documents found.</div>
    @endforelse
</div>
@endsection