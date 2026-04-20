@extends('layouts.app')

@section('title', 'Chi tiet don hang - Dat Shop')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Chi tiet don hang: {{ $order->order_code }}</h2>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>ID:</th>
                            <td>{{ $order->id }}</td>
                        </tr>
                        <tr>
                            <th>Ten nguoi dat:</th>
                            <td>{{ $order->name }}</td>
                        </tr>
                        <tr>
                            <th>Ma don:</th>
                            <td>{{ $order->order_code }}</td>
                        </tr>
                        <tr>
                            <th>San pham:</th>
                            <td>{{ $order->product->name ?? $order->product_name ?? 'Khong co san pham' }}</td>
                        </tr>
                        <tr>
                            <th>So luong:</th>
                            <td>{{ $order->quantity }}</td>
                        </tr>
                        <tr>
                            <th>Tong tien:</th>
                            <td>{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
                        </tr>
                        <tr>
                            <th>Trang thai giao hang:</th>
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
                        </tr>
                        <tr>
                            <th>Ngay tao:</th>
                            <td>{{ optional($order->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Ngay cap nhat:</th>
                            <td>{{ optional($order->updated_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Quay lai</a>
                        <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning">Sua</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
