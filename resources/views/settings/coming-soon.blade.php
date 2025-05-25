@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h1 class="display-4 text-primary fw-bold">Settings</h1>
    <p class="lead text-muted">This feature is under development. Stay tuned for updates!</p>
    <div class="mt-4">
        <i class="fas fa-cog fa-7x text-primary"></i>
    </div>
    <div class="mt-5">
        <a href="{{ route('user.dashboard') }}" class="btn btn-lg btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>
</div>
@endsection
