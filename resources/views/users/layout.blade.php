<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dat Shop')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 110px;
            background-color: #f8f9fa;
        }
        .user-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1000;
        }
        .category-navbar {
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            height: 40px;
            background: linear-gradient(to right, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            padding: 0 20px;
            z-index: 999;
            overflow-x: auto;
        }
        .category-navbar a {
            color: white;
            text-decoration: none;
            margin-right: 25px;
            font-weight: 500;
            font-size: 0.95rem;
            white-space: nowrap;
            transition: all 0.3s ease;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .category-navbar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }
        .category-navbar a.active {
            background-color: #fff;
            color: #212529;
        }
        .user-header .brand {
            font-size: 1.1rem;
            font-weight: 700;
        }
        .user-header .search-box {
            flex: 1;
            max-width: 560px;
            margin: 0 20px;
        }
        .user-footer {
            height: 50px;
            background-color: #28a745;
            color: white;
            border-top: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            font-size: 14px;
            margin-top: auto;
            flex-shrink: 0;
        }
        .user-content {
            flex: 1 0 auto;
            width: 100%;
            padding-bottom: 24px;
        }
        .cart-icon {
            font-size: 1.5rem;
            margin-right: 20px;
            cursor: pointer;
            text-decoration: none;
            color: #333;
        }
        .cart-icon:hover {
            color: #0d6efd;
        }
        .cart-badge {
            position: relative;
            display: inline-block;
        }
        .cart-badge .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 0.6rem;
            padding: 2px 4px;
            min-width: auto;
        }
    </style>
</head>
<body>
    @php
        $cartCount = collect(session('cart', []))->sum('quantity');
    @endphp

    <header class="user-header">
        <div class="brand">Dat Shop</div>
        <form action="{{ route('customer.home') }}" method="GET" class="d-flex search-box">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm kiếm..." value="{{ request('q') }}">
            <button type="submit" class="btn btn-sm btn-primary ms-2">Tìm</button>
        </form>
        <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-outline-primary me-3">Theo dõi đơn</a>
        <a href="{{ route('customer.cart') }}" class="cart-icon cart-badge">
            <i class="bi bi-cart3"></i>
            <span class="badge bg-danger">{{ $cartCount }}</span>
        </a>
        <div class="d-flex align-items-center">
            <span class="me-3 text-nowrap">Xin chào, {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">Đăng xuất</button>
            </form>
        </div>
    </header>

    <nav class="category-navbar">
        <a href="{{ route('customer.home', array_filter(['sort' => request('sort')])) }}"
           class="{{ !request()->filled('category') && !request()->filled('q') ? 'active' : '' }}">
            <i class="bi bi-house-fill me-1"></i>Trang chủ
        </a>
        @if($categories && $categories->count() > 0)
            @foreach($categories as $category)
                <a href="{{ route('customer.home', array_filter(['category' => $category->id, 'q' => request('q'), 'sort' => request('sort')])) }}"
                   class="{{ (string) request('category') === (string) $category->id ? 'active' : '' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        @endif
    </nav>

    <main class="container user-content">
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="user-footer">
        Bản quyền thuộc về Dat Shop
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
