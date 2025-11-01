@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-6">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
        </div>
    </div>

    {{-- ✅ Low Stock Notification Panel --}}
    @if ($lowStockProducts->count())
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong><span class="glyphicon glyphicon-bell"></span> Low Stock Alerts</strong>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            @foreach ($lowStockProducts as $item)
                                @if ($item->quantity <= 5)
                                    <li class="list-group-item list-group-item-danger">
                                        <strong>{{ $item->name }}</strong>
                                        - Only <b>{{ $item->quantity }}</b> sacks left! 🔴 Restock now!
                                    </li>
                                @else
                                    <li class="list-group-item list-group-item-warning">
                                        <strong>{{ $item->name }}</strong>
                                        - Low stock: <b>{{ $item->quantity }}</b> sacks remaining.
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ✅ Stats Panels --}}
    <div class="row">
        <a href="{{ route('users.index') }}" style="color:black;">
            <div class="col-md-3">
                <div class="panel panel-box clearfix">
                    <div class="panel-icon pull-left bg-secondary1">
                        <i class="glyphicon glyphicon-user"></i>
                    </div>
                    <div class="panel-value pull-right">
                        <h2 class="margin-top">{{ $usersCount }}</h2>
                        <p class="text-muted">Users</p>
                    </div>
                </div>
            </div>
        </a>

        <a href="{{ route('categories.index') }}" style="color:black;">
            <div class="col-md-3">
                <div class="panel panel-box clearfix">
                    <div class="panel-icon pull-left bg-red">
                        <i class="glyphicon glyphicon-th-large"></i>
                    </div>
                    <div class="panel-value pull-right">
                        <h2 class="margin-top">{{ $categoriesCount }}</h2>
                        <p class="text-muted">Categories</p>
                    </div>
                </div>
            </div>
        </a>

        <a href="{{ route('products.index') }}" style="color:black;">
            <div class="col-md-3">
                <div class="panel panel-box clearfix">
                    <div class="panel-icon pull-left bg-blue2">
                        <i class="glyphicon glyphicon-shopping-cart"></i>
                    </div>
                    <div class="panel-value pull-right">
                        <h2 class="margin-top">{{ $productsCount }}</h2>
                        <p class="text-muted">Products</p>
                    </div>
                </div>
            </div>
        </a>

        <a href="{{ route('sales.index') }}" style="color:black;">
            <div class="col-md-3">
                <div class="panel panel-box clearfix">
                    <div class="panel-icon pull-left bg-green">
                        <i class="glyphicon glyphicon-usd"></i>
                    </div>
                    <div class="panel-value pull-right">
                        <h2 class="margin-top">{{ $salesCount }}</h2>
                        <p class="text-muted">Sales</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

{{-- ✅ Recent Sales Table --}}
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><span class="glyphicon glyphicon-stats"></span> Recent Sales</strong>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Product Name</th>
                            <th>Qty</th>
                            <th>Total Sale</th>
                            <th>Admin</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentSales as $index => $sale)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $sale->product->name ?? $sale->name ?? 'N/A' }}</td>
                                <td>{{ $sale->quantity ?? 0 }}</td>
                                <td>₱{{ number_format(($sale->price * $sale->quantity), 2) }}</td>
                                <td>{{ $sale->user->name ?? 'N/A' }}</td>
                                <td>
                                    {{ $sale->created_at ? $sale->created_at->format('Y-m-d h:i A') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No recent sales found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


    {{-- ✅ Monthly Sales Chart --}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
                    <strong>Sales Overview</strong>
                    <div class="btn-group" role="group" aria-label="Select Range">
                        <button class="btn btn-default btn-xs" onclick="updateChart('daily')">Daily</button>
                        <button class="btn btn-default btn-xs" onclick="updateChart('weekly')">Weekly</button>
                        <button class="btn btn-default btn-xs" onclick="updateChart('monthly')">Monthly</button>
                        <button class="btn btn-default btn-xs" onclick="updateChart('yearly')">Yearly</button>
                    </div>
                </div>
                <div class="panel-body" style="height: 350px;">
                    <canvas id="salesChart" style="max-width: 100%; height: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Chart.js --}}
    <script src="{{ asset('libs/js/chart.umd.min.js') }}"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        let salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Sales (₱)',
                    data: [],
                    borderColor: '#FFA500',
                    backgroundColor: 'rgba(255,165,0,0.2)',
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#FF8C00',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        function updateChart(range) {
            fetch(`/chart-data?range=${range}`)
                .then(res => res.json())
                .then(res => {
                    salesChart.data.labels = res.labels;
                    salesChart.data.datasets[0].data = res.values;
                    salesChart.update();

                    document.querySelectorAll(".btn-group button").forEach(btn => btn.classList.remove("active"));
                    document.querySelector(`.btn-group button[onclick="updateChart('${range}')"]`).classList.add("active");
                })
                .catch(err => console.error("Error loading chart data:", err));
        }

        document.addEventListener("DOMContentLoaded", () => updateChart('monthly'));
    </script>
@endsection