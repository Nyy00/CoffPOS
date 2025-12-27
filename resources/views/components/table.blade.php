@props(['headers' => [], 'sortable' => true, 'striped' => true])

<div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-300']) }}>
        @if(!empty($headers))
        <thead class="bg-gray-50">
            <tr>
                @foreach($headers as $header)
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider {{ $sortable && isset($header['sortable']) && $header['sortable'] ? 'cursor-pointer hover:bg-gray-100' : '' }}"
                    @if($sortable && isset($header['sortable']) && $header['sortable'])
                        onclick="sortTable('{{ $header['key'] ?? '' }}')"
                    @endif
                >
                    <div class="flex items-center space-x-1">
                        <span>{{ $header['label'] ?? $header }}</span>
                        @if($sortable && isset($header['sortable']) && $header['sortable'])
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                        @endif
                    </div>
                </th>
                @endforeach
            </tr>
        </thead>
        @endif
        
        <tbody class="{{ $striped ? 'divide-y divide-gray-200 bg-white' : 'bg-white' }}">
            {{ $slot }}
        </tbody>
    </table>
</div>

@if($sortable)
<script>
let currentSort = { column: null, direction: 'asc' };

function sortTable(column) {
    // Toggle direction if same column, otherwise default to asc
    if (currentSort.column === column) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.direction = 'asc';
    }
    currentSort.column = column;
    
    // Update URL with sort parameters
    const url = new URL(window.location);
    url.searchParams.set('sort', column);
    url.searchParams.set('direction', currentSort.direction);
    
    // Reload page with new sort parameters
    window.location.href = url.toString();
}

// Update sort indicators based on current URL parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const sortColumn = urlParams.get('sort');
    const sortDirection = urlParams.get('direction') || 'asc';
    
    if (sortColumn) {
        currentSort = { column: sortColumn, direction: sortDirection };
        
        // Update visual indicators
        const headers = document.querySelectorAll('th[onclick*="sortTable"]');
        headers.forEach(header => {
            const column = header.getAttribute('onclick').match(/'([^']+)'/)[1];
            const icon = header.querySelector('svg');
            
            if (column === sortColumn) {
                header.classList.add('bg-gray-100');
                if (icon) {
                    icon.classList.remove('text-gray-400');
                    icon.classList.add(sortDirection === 'asc' ? 'text-blue-500' : 'text-blue-500');
                    if (sortDirection === 'desc') {
                        icon.style.transform = 'rotate(180deg)';
                    }
                }
            }
        });
    }
});
</script>
@endif