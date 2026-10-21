@extends('layouts.portal')

@section('content')
<div class="topline">
    <div><h2>Theory Paper</h2><div class="sub">Online examination papers available for you</div></div>
</div>

<div class="session-bar">
    <span>Logged in as enrollment <b>{{ session('student_enrollment') }}</b> · Currently in <b>Semester {{ session('student_semester') }}</b></span>
</div>

<!-- ADDED FILTERS HERE -->
<form method="GET" action="{{ route('theory') }}" class="filters">
    <select name="programme" onchange="this.form.submit()">
        <option value="">All programmes</option>
        @foreach($programmes as $p)
            <option value="{{ $p }}" {{ request('programme') == $p ? 'selected' : '' }}>{{ $p }}</option>
        @endforeach
    </select>

    <select name="specialisation" onchange="this.form.submit()">
        <option value="">All specialisations</option>
        @foreach($specialisations as $s)
            <option value="{{ $s }}" {{ request('specialisation') == $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>

    <select name="semester" onchange="this.form.submit()">
        <option value="">All semesters</option>
        @foreach($semesters as $sem)
            <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>Semester {{ $sem }}</option>
        @endforeach
    </select>
</form>

<!-- The rest of your theory loop -->
@foreach($docs as $d)
    @php $locked = $d['locked'] ?? false; @endphp
    <div class="exam-card {{ $locked ? 'locked' : '' }}">
        <div>
            <h3>{{ $d['subject'] ?? 'Question Paper' }}</h3>
            <div class="meta">
                {{ $d['programme'] ?? '' }} · {{ $d['specialisation'] ?? '' }} 
                @if(!empty($d['semester'])) · Sem {{ $d['semester'] }} @endif
                @if(!empty($d['subjectCode'])) · {{ $d['subjectCode'] }} @endif
            </div>
            @if($locked)
                <div class="access-err">You're not able to access this yet — it unlocks in Semester {{ $d['semester'] }}.</div>
            @endif
        </div>
        @if(!$locked)
            <a href="{{ $d['link'] ?? '#' }}" target="_blank" class="start-btn">Start Exam</a>
        @endif
    </div>
@endforeach
@endsection