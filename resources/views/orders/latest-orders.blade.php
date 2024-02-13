@if ($orders->count() > 0)
    <ul>
        @foreach ($orders->take(10) as $order)
            <li>R$ {{ number_format($order->total, 2, ',', '.') }} em {{ $order->created_at->format('d/m/Y H:i') }}
            </li>
        @endforeach
    </ul>
@else
    <p>Nenhuma compra realizada até o momento...</p>
@endif
