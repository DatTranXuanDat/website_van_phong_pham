@extends('users.layout')

@section('title', 'Chi tiet don hang - Dat Shop')

@section('content')
<div class="py-4">
    <div class="mb-3">
        <a href="{{ route('customer.orders.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Quay lai danh sach don hang
        </a>
    </div>

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

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-lg-5">
                    @if(optional($order->product)->image)
                        <img src="{{ asset('storage/' . $order->product->image) }}" alt="{{ $order->product->name }}" class="img-fluid rounded order-product-image w-100">
                    @else
                        <div class="order-product-image bg-light rounded d-flex align-items-center justify-content-center text-muted w-100">
                            Khong co anh san pham
                        </div>
                    @endif
                </div>
                <div class="col-lg-7">
                    <h3 class="mb-3">{{ $order->product->name ?? 'San pham khong ton tai' }}</h3>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th>Ma don:</th>
                            <td>{{ $order->order_code }}</td>
                        </tr>
                        <tr>
                            <th>Nguoi dat:</th>
                            <td>{{ $order->name }}</td>
                        </tr>
                        <tr>
                            <th>So luong:</th>
                            <td>{{ $order->quantity }}</td>
                        </tr>
                        <tr>
                            <th>Tong tien:</th>
                            <td class="text-danger fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
                        </tr>
                        <tr>
                            <th>Trang thai giao hang:</th>
                            <td>
                                <span class="badge bg-{{ $statusClasses[$order->delivery_status] ?? 'secondary' }}">
                                    {{ $statusLabels[$order->delivery_status] ?? $order->delivery_status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Ngay dat:</th>
                            <td>{{ optional($order->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Cap nhat gan nhat:</th>
                            <td>{{ optional($order->updated_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .order-product-image {
        height: 360px;
        object-fit: cover;
    }
</style>
@endsection
