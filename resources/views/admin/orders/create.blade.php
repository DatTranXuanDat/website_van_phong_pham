@extends('layouts.app')

@section('title', 'Them don hang - Dat Shop')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Them don hang moi</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Ten nguoi dat</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="order_code" class="form-label">Ma don</label>
                            <input type="text" class="form-control @error('order_code') is-invalid @enderror" id="order_code" name="order_code" value="{{ old('order_code') }}" required>
                            @error('order_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="product_id" class="form-label">San pham</label>
                            <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                <option value="">Chon san pham</option>
                                @foreach($products as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-price="{{ $product->price }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}
                                    >
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">So luong</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Tong tien</label>
                            <input type="number" class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" value="{{ old('total_amount') }}" min="0" step="0.01" required>
                            @error('total_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="delivery_status" class="form-label">Trang thai giao hang</label>
                            <select class="form-select @error('delivery_status') is-invalid @enderror" id="delivery_status" name="delivery_status" required>
                                <option value="pending" {{ old('delivery_status', 'pending') === 'pending' ? 'selected' : '' }}>Cho xu ly</option>
                                <option value="processing" {{ old('delivery_status') === 'processing' ? 'selected' : '' }}>Dang xu ly</option>
                                <option value="shipping" {{ old('delivery_status') === 'shipping' ? 'selected' : '' }}>Dang giao</option>
                                <option value="delivered" {{ old('delivery_status') === 'delivered' ? 'selected' : '' }}>Da giao</option>
                                <option value="cancelled" {{ old('delivery_status') === 'cancelled' ? 'selected' : '' }}>Da huy</option>
                            </select>
                            @error('delivery_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Tao don hang</button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Huy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const totalInput = document.getElementById('total_amount');

        function updateTotal() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const price = Number(selectedOption?.dataset?.price || 0);
            const quantity = Number(quantityInput.value || 0);

            if (!totalInput.value || document.activeElement !== totalInput) {
                totalInput.value = price * quantity;
            }
        }

        productSelect.addEventListener('change', updateTotal);
        quantityInput.addEventListener('input', updateTotal);
        updateTotal();
    });
</script>
@endsection
