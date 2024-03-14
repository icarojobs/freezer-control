@if (isset($items))
    <ul>
        @foreach ($items as $key => $item)
            <li>
                - {{ $item['quantity'] }}x {{ $item['name'] ?? '' }} - R$ {{ number_format(floatval($item['sub_total']), 2, ',', '.') }}
            </li>
        @endforeach
    </ul>
@endif