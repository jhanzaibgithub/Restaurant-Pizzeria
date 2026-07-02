@php
    $steps = [
        1 => 'Welcome',
        2 => 'Requirements',
        3 => 'Environment',
        4 => 'Database',
        5 => 'Migration',
        6 => 'Admin',
        7 => 'Finish',
    ];
    $percent = (($active - 1) / (count($steps) - 1)) * 100;
@endphp

<div class="mb-4">
    <div class="progress" role="progressbar" aria-valuenow="{{ (int) $percent }}" aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar" style="width: {{ $percent }}%"></div>
    </div>
    <div class="d-flex justify-content-between gap-2 mt-3 flex-wrap small text-white">
        @foreach($steps as $index => $label)
            <span class="{{ $index <= $active ? 'fw-bold' : 'opacity-75' }}">{{ $label }}</span>
        @endforeach
    </div>
</div>
