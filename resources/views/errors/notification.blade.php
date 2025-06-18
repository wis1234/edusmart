@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="card">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="fas fa-exclamation-circle fa-4x text-warning"></i>
            </div>
            <h3 class="mb-3">Notification Link Not Available</h3>
            <p class="text-muted mb-4">{{ $message }}</p>
            
            @if($notification)
            <div class="card bg-light mb-4">
                <div class="card-body">
                    <h5 class="card-title">{{ $notification->title }}</h5>
                    <p class="card-text">{{ $notification->message }}</p>
                    <small class="text-muted">Received {{ $notification->created_at->diffForHumans() }}</small>
                </div>
            </div>
            @endif

            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i>Back to Dashboard
                </a>
                <a href="{{ route('notifications.index') }}" class="btn btn-light">
                    <i class="fas fa-bell me-2"></i>View All Notifications
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 