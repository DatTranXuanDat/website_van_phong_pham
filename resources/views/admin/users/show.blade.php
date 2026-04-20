@extends('layouts.app')

@section('title', 'Chi tiết người dùng - Dat Shop')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Chi tiết người dùng: {{ $user->name }}</h2>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>ID:</th>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <th>Tên:</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Vai trò:</th>
                            <td>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                                    {{ $user->role === 'admin' ? 'Admin' : 'Customer' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Ngày tạo:</th>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Ngày cập nhật:</th>
                            <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Sửa</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
