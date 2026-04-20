@extends('users.layout')

@section('title', 'Theo doi don hang - Dat Shop')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-1">Don hang cua ban</h3>
            <p class="text-muted mb-0">Theo doi ma don, san pham, tong tien va trang thai giao hang.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($orders->isEmpty())
                <div class="alert alert-info mb-0">Ban chua co don hang nao.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Ma don</th>
                                <th>San pham</th>
                                <th>So luong</th>
                                <th>Tong tien</th>
                                <th>Trang thai</th>
                                <th>Ngay dat</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                @php
                                    $statusLabels = [
                                        'pending' => 'Cho xu ly',
                                        'processing' => 'Dang xu ly',
                                        'shipping' => 'Dang giao',
                                        'delivered' => 'Da giao',
                                        'cancelled' => 'Da huy',
                                    ];
                                    $statusClasses = [
                                        'pending' => 'secondary',
                                        'processing' => 'warning',
                                        'shipping' => 'primary',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger',
                                    ];
                                @endphp
                                <tr>
                                    <td>{{ $order->order_code }}</td>
                                    <td>{{ $order->product->name ?? 'San pham khong ton tai' }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
                                    <td>
                                        <span class="badge bg-{{ $statusClasses[$order->delivery_status] ?? 'secondary' }}">
                                            {{ $statusLabels[$order->delivery_status] ?? $order->delivery_status }}
                                        </span>
                                    </td>
                                    <td>{{ optional($order->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Chi tiet</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @endif
        </div>
    </div>
</div>
@endsection
