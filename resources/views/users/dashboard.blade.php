@extends('users.layout')

@section('title', 'Trang chủ - Dat Shop')

@section('content')
@php
    $selectedCategory = $selectedCategory ?? null;
    $products = $products ?? collect();
    $search = $search ?? request('q');
    $sort = $sort ?? request('sort');
@endphp
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-1">{{ $selectedCategory ? 'Danh mục: ' . $selectedCategory->name : 'Sản phẩm trang chủ' }}</h3>
            <p class="text-muted mb-0">
                Danh sach san pham
                @if(!empty($search))
                    cho từ khóa "{{ $search }}"
                @endif
            </p>
        </div>
        <form action="{{ route('customer.home') }}" method="GET" class="d-flex align-items-center gap-2">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if(request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
            <label for="sort" class="text-muted mb-0">Sắp xếp:</label>
            <select name="sort" id="sort" class="form-select" onchange="this.form.submit()">
                <option value="">Mặc định</option>
                <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
            </select>
        </form>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($products as $product)
            <div class="col">
                <a href="{{ route('customer.products.show', $product) }}" class="text-decoration-none text-reset d-block h-100">
                    <div class="card h-100 border-light hover-shadow">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                        @else
                            <div class="product-image product-placeholder d-flex align-items-center justify-content-center">
                                <span>Chưa có ảnh</span>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge text-bg-light border">{{ $product->category->name }}</span>
                            </div>
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted flex-grow-1">
                                {{ \Illuminate\Support\Str::limit($product->description ?: 'Chưa có mô tả cho sản phẩm này.', 80) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <strong class="text-danger">{{ number_format($product->price, 0, ',', '.') }} VND</strong>
                                <span class="text-muted">SL: {{ $product->quantity }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center mb-0">
                    Không có sản phẩm phù hợp.
                </div>
            </div>
        @endforelse
    </div>

    @if(method_exists($products, 'links'))
        <div class="home-pagination mt-4">
            {{ $products->links('vendor.pagination.user-home') }}
        </div>
    @endif

</div>

<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    .product-image {
        height: 220px;
        object-fit: cover;
    }
    .product-placeholder {
        background-color: #f1f3f5;
        color: #6c757d;
    }
    .home-pagination nav {
        overflow-x: auto;
    }
    .home-pagination .pagination {
        display: flex;
        flex-wrap: nowrap;
        gap: 12px;
        white-space: nowrap;
        margin-bottom: 0;
    }
    .home-pagination .page-link {
        min-width: 50px;
        height: 56px;
        border: 1px solid #ff2a1a;
        border-radius: 8px;
        color: #ff2a1a;
        background: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 14px;
        font-size: 32px;
        line-height: 1;
        box-shadow: none;
    }
    .home-pagination .page-item.active .page-link {
        background: #e90f00;
        border-color: #e90f00;
        color: #fff;
        font-weight: 600;
    }
    .home-pagination .page-item.disabled .page-link {
        background: #f2f2f2;
        border-color: #d5d5d5;
        color: #b9b9b9;
        opacity: 1;
    }
    .home-pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background: #fff5f4;
        border-color: #ff2a1a;
        color: #ff2a1a;
    }
</style>
@endsection
