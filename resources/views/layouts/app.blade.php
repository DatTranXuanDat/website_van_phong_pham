<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dat Shop')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 60px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            margin-left: 250px;
            padding-top: 60px;
            padding-bottom: 60px;
            min-height: calc(100vh - 100px);
        }
        .header {
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            height: 60px;
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            z-index: 1000;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 250px;
            right: 0;
            height: 40px;
            background-color: #28a745;
            color: white;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div></div>
        <div class="d-flex align-items-center">
            <span class="me-3">Xin chào, {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">Đăng xuất</button>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-3 text-center border-bottom">
            <h4 class="mb-0 fw-bold">Dat Shop</h4>
        </div>
        <a href="{{ url('/admin') }}">Dashboard</a>
        <a href="{{ url('/admin/users') }}">Quản lý người dùng</a>
        <a href="{{ url('/admin/categories') }}">Quản lý danh mục</a>
        <a href="{{ url('/admin/products') }}">Quản lý sản phẩm</a>
        <a href="{{ url('/admin/orders') }}">Quản lý đơn hàng</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Footer -->
    <div class="footer">
        Bản quyền thuộc về Dat Shop
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
