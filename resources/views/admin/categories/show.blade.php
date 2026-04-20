@extends('layouts.app')

@section('title', 'Chi tiết danh mục - Dat Shop')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Chi tiết danh mục: {{ $category->name }}</h2>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>ID:</th>
                            <td>{{ $category->id }}</td>
                        </tr>
                        <tr>
                            <th>Tên danh mục:</th>
                            <td>{{ $category->name }}</td>
                        </tr>
                        <tr>
                            <th>Mô tả:</th>
                            <td>{{ $category->description ?: 'Không có mô tả' }}</td>
                        </tr>
                        <tr>
                            <th>Ngày tạo:</th>
                            <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Ngày cập nhật:</th>
                            <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">Sửa</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
