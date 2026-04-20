@extends('users.layout')

@section('title', $product->name . ' - Dat Shop')

@section('content')
<div class="py-4">
    <div class="mb-3">
        <a href="{{ route('customer.home', array_filter(['category' => $product->category_id])) }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="row g-4 align-items-start">
                <div class="col-lg-5">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded product-detail-image w-100">
                    @else
                        <div class="product-detail-image bg-light rounded d-flex align-items-center justify-content-center text-muted w-100">
                            Chưa có ảnh sản phẩm
                        </div>
                    @endif
                </div>

                <div class="col-lg-7">
                    <span class="badge text-bg-light border mb-2">{{ $product->category->name }}</span>
                    <h2 class="mb-3">{{ $product->name }}</h2>

                    <div class="product-meta mb-3">
                        <div><strong>Mã sản phẩm:</strong> {{ $product->id }}</div>
                        <div><strong>Slug:</strong> {{ $product->slug }}</div>
                        <div><strong>Số lượng còn:</strong> {{ $product->quantity }}</div>
                    </div>

                    <div class="fs-3 fw-bold text-danger mb-3">
                        {{ number_format($product->price, 0, ',', '.') }} VND
                    </div>

                    <div class="mb-4">
                        <h5>Mô tả sản phẩm</h5>
                        <div class="text-muted description-box">
                            {{ $product->description ?: 'Sản phẩm hiện chưa có mô tả chi tiết.' }}
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="quantity" class="form-label">Số lượng</label>
                            <input form="add-to-cart-form" type="number" min="1" max="{{ max(1, $product->quantity) }}" value="1" class="form-control" id="quantity" name="quantity">
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <form id="add-to-cart-form" action="{{ route('customer.products.add-to-cart', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-cart-plus"></i> Thêm giỏ hàng
                            </button>
                        </form>

                        <form action="{{ route('customer.products.order', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1" class="order-quantity">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="bi bi-bag-check"></i> Đặt hàng
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($relatedProducts->isNotEmpty())
        <div class="mt-5">
            <h4 class="mb-3">Sản phẩm cùng danh mục</h4>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col">
                        <a href="{{ route('customer.products.show', $relatedProduct) }}" class="text-decoration-none text-reset d-block h-100">
                            <div class="card h-100 border-light hover-shadow">
                                @if($relatedProduct->image)
                                    <img src="{{ asset('storage/' . $relatedProduct->image) }}" class="card-img-top related-product-image" alt="{{ $relatedProduct->name }}">
                                @else
                                    <div class="related-product-image bg-light d-flex align-items-center justify-content-center text-muted">
                                        Chưa có ảnh
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                                    <div class="text-danger fw-bold">{{ number_format($relatedProduct->price, 0, ',', '.') }} VND</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
    .product-detail-image {
        height: 420px;
        object-fit: cover;
    }
    .description-box {
        line-height: 1.7;
        white-space: pre-line;
    }
    .product-meta {
        display: grid;
        gap: 8px;
    }
    .related-product-image {
        height: 200px;
        object-fit: cover;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quantityInput = document.getElementById('quantity');
        const orderQuantityInput = document.querySelector('.order-quantity');

        if (quantityInput && orderQuantityInput) {
            const syncQuantity = function () {
                orderQuantityInput.value = quantityInput.value;
            };

            quantityInput.addEventListener('input', syncQuantity);
            syncQuantity();
        }
    });
</script>
@endsection
