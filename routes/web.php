<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_amount');
        $todayRevenue = Order::whereDate('created_at', now()->toDateString())->sum('total_amount');

        $revenueByDate = Order::selectRaw('DATE(created_at) as order_date, SUM(total_amount) as revenue')
            ->groupBy('order_date')
            ->orderBy('order_date')
            ->limit(7)
            ->get();

        $chartLabels = $revenueByDate
            ->pluck('order_date')
            ->map(fn ($date) => \Carbon\Carbon::parse($date)->format('d/m'))
            ->values();

        $chartData = $revenueByDate
            ->pluck('revenue')
            ->map(fn ($revenue) => (float) $revenue)
            ->values();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'totalRevenue',
            'todayRevenue',
            'chartLabels',
            'chartData'
        ));
    })->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/customer', function () {
        $categories = Category::orderBy('name')->get();
        $selectedCategoryId = request('category');
        $search = request('q');
        $sort = request('sort');

        $productsQuery = Product::with('category')
            ->when($selectedCategoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($search, function ($query, $keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            });

        if ($sort === 'price_asc') {
            $productsQuery->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $productsQuery->orderBy('price', 'desc');
        } else {
            $productsQuery->latest();
        }

        $products = $productsQuery
            ->paginate(9)
            ->appends(request()->query());

        $selectedCategory = $selectedCategoryId
            ? $categories->firstWhere('id', (int) $selectedCategoryId)
            : null;

        return view('users.dashboard', compact('categories', 'products', 'selectedCategory', 'search', 'sort'));
    })->name('customer.home');

    Route::get('/customer/products/{product}', function (Product $product) {
        $categories = Category::orderBy('name')->get();
        $product->load('category');

        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest()
            ->limit(3)
            ->get();

        return view('users.product-show', compact('categories', 'product', 'relatedProducts'));
    })->name('customer.products.show');

    Route::post('/customer/products/{product}/add-to-cart', function (Product $product) {
        $quantity = max(1, min((int) request('quantity', 1), max(1, $product->quantity)));
        $cart = session()->get('cart', []);
        $productId = (string) $product->id;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = min(
                $cart[$productId]['quantity'] + $quantity,
                max(1, $product->quantity)
            );
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity,
                'stock' => $product->quantity,
            ];
        }

        $cart[$productId]['stock'] = $product->quantity;
        session(['cart' => $cart]);

        return back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    })->name('customer.products.add-to-cart');

    Route::post('/customer/products/{product}/order', function (Product $product) {
        $product->refresh();

        if ($product->quantity < 1) {
            return redirect()
                ->route('customer.products.show', $product)
                ->with('error', 'Sản phẩm đã hết hàng.');
        }

        $quantity = max(1, min((int) request('quantity', 1), $product->quantity));

        DB::transaction(function () use ($product, $quantity) {
            Order::create([
                'user_id' => Auth::id(),
                'name' => Auth::user()->name,
                'order_code' => 'ORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6)),
                'product_id' => $product->id,
                'quantity' => $quantity,
                'total_amount' => $product->price * $quantity,
                'delivery_status' => 'pending',
            ]);

            $product->decrement('quantity', $quantity);
        });

        return redirect()
            ->route('customer.products.show', $product)
            ->with('success', 'Đặt hàng thành công cho ' . $quantity . ' sản phẩm "' . $product->name . '". Đơn đã xuất hiện trong quản trị.');
    })->name('customer.products.order');

    Route::get('/customer/cart', function () {
        $categories = Category::orderBy('name')->get();
        $cart = collect(session('cart', []))->map(function ($item) {
            $item['subtotal'] = $item['price'] * $item['quantity'];
            return $item;
        });
        $cartTotal = $cart->sum('subtotal');

        return view('users.cart', compact('categories', 'cart', 'cartTotal'));
    })->name('customer.cart');

    Route::post('/customer/cart/update', function () {
        $cart = session('cart', []);
        $quantities = request('quantities', []);

        foreach ($quantities as $productId => $quantity) {
            if (! isset($cart[$productId])) {
                continue;
            }

            $stock = max(1, (int) ($cart[$productId]['stock'] ?? 1));
            $quantity = (int) $quantity;

            if ($quantity <= 0) {
                unset($cart[$productId]);
                continue;
            }

            $cart[$productId]['quantity'] = min($quantity, $stock);
        }

        session(['cart' => $cart]);

        return redirect()->route('customer.cart')->with('success', 'Đã cập nhật giỏ hàng.');
    })->name('customer.cart.update');

    Route::post('/customer/cart/remove/{productId}', function ($productId) {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);

        return redirect()->route('customer.cart')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    })->name('customer.cart.remove');

    Route::post('/customer/cart/checkout', function () {
        $cart = collect(session('cart', []));

        if ($cart->isEmpty()) {
            return redirect()->route('customer.cart')->with('success', 'Giỏ hàng đang trống.');
        }

        $timestamp = now()->format('YmdHis');

        $products = Product::whereIn('id', $cart->pluck('id'))->get()->keyBy('id');

        foreach ($cart as $item) {
            $product = $products->get($item['id']);

            if (! $product) {
                return redirect()->route('customer.cart')->with('error', 'Có sản phẩm trong giỏ hàng không còn tồn tại.');
            }

            if ($product->quantity < $item['quantity']) {
                return redirect()->route('customer.cart')->with(
                    'error',
                    'Sản phẩm "' . $product->name . '" không đủ số lượng tồn kho. Chỉ còn ' . $product->quantity . '.'
                );
            }
        }

        DB::transaction(function () use ($cart, $products, $timestamp) {
            foreach ($cart as $index => $item) {
                $product = $products->get($item['id']);

                Order::create([
                    'user_id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'order_code' => 'ORD-' . $timestamp . '-' . ($index + 1) . '-' . Str::upper(Str::random(4)),
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'total_amount' => $product->price * $item['quantity'],
                    'delivery_status' => 'pending',
                ]);

                $product->decrement('quantity', $item['quantity']);
            }
        });

        session()->forget('cart');

        return redirect()->route('customer.home')->with('success', 'Đặt hàng thành công cho ' . $cart->sum('quantity') . ' sản phẩm. Các đơn đã được lưu trong quản trị.');
    })->name('customer.cart.checkout');

    Route::get('/customer/orders', function () {
        $categories = Category::orderBy('name')->get();
        $orders = Order::with('product')
            ->where(function ($query) {
                $query->where('user_id', Auth::id())
                    ->orWhere(function ($fallbackQuery) {
                        $fallbackQuery->whereNull('user_id')
                            ->where('name', Auth::user()->name);
                    });
            })
            ->latest()
            ->get();

        return view('users.orders.index', compact('categories', 'orders'));
    })->name('customer.orders.index');

    Route::get('/customer/orders/{order}', function (Order $order) {
        $categories = Category::orderBy('name')->get();
        $order->load('product');

        $isOwner = $order->user_id === Auth::id()
            || ($order->user_id === null && $order->name === Auth::user()->name);

        abort_unless($isOwner, 403);

        return view('users.orders.show', compact('categories', 'order'));
    })->name('customer.orders.show');
});
