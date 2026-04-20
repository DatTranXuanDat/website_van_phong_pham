@extends('layouts.app')

@section('title', 'Quan ly don hang - Dat Shop')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Quan ly don hang</h1>
        <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">Them don hang moi</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ten</th>
                            <th>Ma don</th>
                            <th>San pham</th>
                            <th>So luong</th>
                            <th>Tong tien</th>
                            <th>Trang thai giao hang</th>
                            <th>Hanh dong</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->name }}</td>
                                <td>{{ $order->order_code }}</td>
                                <td>{{ $order->product->name ?? $order->product_name ?? 'Khong co san pham' }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
                                <td>
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
                                    <span class="badge bg-{{ $statusClasses[$order->delivery_status] ?? 'secondary' }}">
                                        {{ $statusLabels[$order->delivery_status] ?? $order->delivery_status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">Xem</a>
                                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-warning">Sua</a>
                                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Ban co chac muon xoa don hang nay?')">Xoa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Chua co don hang nao</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($orders, 'links'))
                {{ $orders->links() }}
            @endif
        </div>
    </div>
</div>
@endsection
