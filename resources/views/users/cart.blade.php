@extends('users.layout')

@section('title', 'Giỏ hàng - Dat Shop')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-1">Giỏ hàng của bạn</h3>
            <p class="text-muted mb-0">Kiểm tra sản phẩm đã thêm và đặt hàng.</p>
        </div>
        <a href="{{ route('customer.home') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Tiếp tục mua hàng
        </a>
    </div>

    @if($cart->isEmpty())
        <div class="alert alert-info mb-0">
            Giỏ hàng đang trống.
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        @foreach($cart as $item)
                            <div class="cart-row p-3 border-bottom">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-2">
                                        @if(!empty($item['image']))
                                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="img-fluid rounded cart-image">
                                        @else
                                            <div class="cart-image bg-light rounded d-flex align-items-center justify-content-center text-muted">
                                                No image
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <h5 class="mb-1">{{ $item['name'] }}</h5>
                                        <div class="text-muted">Đơn giá: {{ number_format($item['price'], 0, ',', '.') }} VND</div>
                                    </div>
                                    <div class="col-md-2">
                                        <form action="{{ route('customer.cart.update') }}" method="POST">
                                            @csrf
                                            <label class="form-label small mb-1">Số lượng</label>
                                            <input
                                                type="number"
                                                class="form-control"
                                                name="quantities[{{ $item['id'] }}]"
                                                min="1"
                                                max="{{ max(1, $item['stock'] ?? 1) }}"
                                                value="{{ $item['quantity'] }}"
                                            >
                                            <button type="submit" class="btn btn-primary btn-sm mt-2 w-100">Cập nhật</button>
                                        </form>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="small text-muted mb-1">Tạm tính</div>
                                        <div class="fw-bold text-danger">{{ number_format($item['subtotal'], 0, ',', '.') }} VND</div>
                                    </div>
                                    <div class="col-md-2">
                                        <form action="{{ route('customer.cart.remove', $item['id']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger w-100">Xóa</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Tóm tắt đơn hàng</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Số mặt hàng</span>
                            <strong>{{ $cart->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tổng số lượng</span>
                            <strong>{{ $cart->sum('quantity') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between fs-5 border-top pt-3">
                            <span>Tổng tiền</span>
                            <strong class="text-danger">{{ number_format($cartTotal, 0, ',', '.') }} VND</strong>
                        </div>

                        <form action="{{ route('customer.cart.checkout') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100 btn-lg">
                                <i class="bi bi-bag-check"></i> Đặt hàng
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .cart-image {
        width: 100%;
        height: 90px;
        object-fit: cover;
    }
</style>
@endsection
