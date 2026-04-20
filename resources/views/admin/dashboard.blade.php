@extends('layouts.app')

@section('title', 'Dashboard - Dat Shop')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h1 class="mb-1">Dashboard</h1>
            <p class="text-muted mb-0">Tong quan doanh thu va du lieu cua cua hang.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm stat-card stat-card-blue h-100">
                <div class="card-body">
                    <div class="stat-label">Tong san pham</div>
                    <div class="stat-value">{{ number_format($totalProducts, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm stat-card stat-card-green h-100">
                <div class="card-body">
                    <div class="stat-label">Tong danh muc</div>
                    <div class="stat-value">{{ number_format($totalCategories, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm stat-card stat-card-orange h-100">
                <div class="card-body">
                    <div class="stat-label">Tong don hang</div>
                    <div class="stat-value">{{ number_format($totalOrders, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm stat-card stat-card-red h-100">
                <div class="card-body">
                    <div class="stat-label">Tong doanh thu</div>
                    <div class="stat-value">{{ number_format($totalRevenue, 0, ',', '.') }} VND</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h4 class="mb-1">Bieu do doanh thu</h4>
                    <p class="text-muted mb-0">Doanh thu theo ngay tu cac don hang da tao.</p>
                </div>
                <div class="card-body">
                    @if($chartLabels->isEmpty())
                        <div class="alert alert-info mb-0">Chua co du lieu doanh thu de hien thi bieu do.</div>
                    @else
                        <canvas id="revenueChart" height="120"></canvas>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h4 class="mb-1">Thong ke nhanh</h4>
                    <p class="text-muted mb-0">Tong hop cac chi so quan trong.</p>
                </div>
                <div class="card-body">
                    <div class="quick-stat">
                        <span>Doanh thu trung binh / don</span>
                        <strong>{{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 0, ',', '.') : 0 }} VND</strong>
                    </div>
                    <div class="quick-stat">
                        <span>Mat hang / danh muc</span>
                        <strong>{{ $totalCategories > 0 ? number_format($totalProducts / $totalCategories, 1, ',', '.') : 0 }}</strong>
                    </div>
                    <div class="quick-stat border-bottom-0 pb-0">
                        <span>Doanh thu hom nay</span>
                        <strong>{{ number_format($todayRevenue, 0, ',', '.') }} VND</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card {
        color: #fff;
        border-radius: 18px;
    }
    .stat-card-blue {
        background: linear-gradient(135deg, #1d4ed8, #60a5fa);
    }
    .stat-card-green {
        background: linear-gradient(135deg, #15803d, #4ade80);
    }
    .stat-card-orange {
        background: linear-gradient(135deg, #c2410c, #fb923c);
    }
    .stat-card-red {
        background: linear-gradient(135deg, #be123c, #fb7185);
    }
    .stat-label {
        font-size: 0.95rem;
        opacity: 0.9;
        margin-bottom: 10px;
    }
    .stat-value {
        font-size: 1.9rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .quick-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #e9ecef;
        gap: 16px;
    }
</style>

@if($chartLabels->isNotEmpty())
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart');

        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Doanh thu',
                        data: @json($chartData),
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.15)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 3,
                        pointBackgroundColor: '#0d6efd',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
@endif
@endsection
