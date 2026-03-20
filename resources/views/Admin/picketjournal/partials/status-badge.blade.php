@php
    $statusColors = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'done' => 'bg-green-100 text-green-800',
        'approved' => 'bg-blue-100 text-blue-800',
        'rejected' => 'bg-red-100 text-red-800',
        'in_progress' => 'bg-purple-100 text-purple-800',
    ];
    $defaultColor = 'bg-gray-100 text-gray-800';
    $color = $statusColors[$status] ?? $defaultColor;
    
    $statusLabels = [
        'pending' => 'Pending',
        'done' => 'Done',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'in_progress' => 'In Progress',
    ];
    $label = $statusLabels[$status] ?? ucfirst($status);
@endphp

<span class="px-3 py-1 rounded-full text-xs font-medium {{ $color }}">
    {{ $label }}
</span>