@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm - Dat Shop')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Chi tiết sản phẩm: {{ $product->name }}</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px; border-radius: 5px;">
                                    <span class="text-muted">Không có ảnh</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th>ID:</th>
                                    <td>{{ $product->id }}</td>
                                </tr>
                                <tr>
                                    <th>Tên sản phẩm:</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Slug:</th>
                                    <td>{{ $product->slug }}</td>
                                </tr>
                                <tr>
                                    <th>Danh mục:</th>
                                    <td>{{ $product->category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Mô tả:</th>
                                    <td>{{ $product->description ?: 'Không có mô tả' }}</td>
                                </tr>
                                <tr>
                                    <th>Số lượng:</th>
                                    <td>{{ $product->quantity }}</td>
                                </tr>
                                <tr>
                                    <th>Giá tiền:</th>
                                    <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo:</th>
                                    <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày cập nhật:</th>
                                    <td>{{ $product->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">Sửa</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
