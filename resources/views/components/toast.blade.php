@props([
    'type' => 'success', // success, error, warning, info
    'title' => '',
    'message' => '',
    'duration' => 5000
])

@php
    $icons = [
        'success' => 'ph-check-circle',
        'error' => 'ph-x-circle',
        'warning' => 'ph-warning-circle',
        'info' => 'ph-info'
    ];
    
    $colors = [
        'success' => 'success',
        'error' => 'danger',
        'warning' => 'warning',
        'info' => 'info'
    ];
    
    $icon = $icons[$type] ?? $icons['info'];
    $color = $colors[$type] ?? $colors['info'];
@endphp

<div class="toast align-items-center border-0 fade" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="{{ $duration }}">
    <div class="d-flex">
        <div class="toast-body d-flex align-items-center">
            <i class="{{ $icon }} ph-lg text-{{ $color }} me-3"></i>
            <div class="flex-fill">
                @if($title)
                    <div class="fw-semibold mb-1">{{ $title }}</div>
                @endif
                <div>{{ $message }}</div>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>