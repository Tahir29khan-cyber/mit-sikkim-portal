@extends('layouts.portal')

@section('content')
<div class="gate">
    <div class="mark">⏸</div>
    <h2>{{ $name }} is currently closed</h2>
    <p>This module only opens during a window set by the institute admin. Please check back later, or contact the counselling desk.</p>
</div>
@endsection