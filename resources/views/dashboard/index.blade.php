@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

{{-- ✅ Safe Debug Section (optional) --}}
@if(isset($recentSales))
    {{-- <pre>{{ print_r($recentSales->toArray(), true) }}</pre> --}}
@else
    <div style="background: #f8d7da; padding: 8px; border: 1px solid #f5c6cb; margin-bottom: 10px;">
        ⚠️ No $recentSales data found — check controller binding.
    </div>
@endif

{{-- ✅ Status Message --}}
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

{{-- ✅ Balanced Stats Panels --}}
<div class="row text-center">

    {{-- Users --}}
    <a href="{{ route('users.index') }}" style="color:black;">
        <div class="col-md-3">
            <div class="panel panel-box clearfix" style="display: flex; align-items: center; height: 110px;">
                <div class="panel-icon bg-secondary1 text-center"
                     style="width: 50%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <i class="glyphicon glyphicon-user" style="font-size: 40px; color: #FFA500;"></i>
                    <p style="color: white; font-size: 16px; margin: 5px 0 0;">Users</p>
                </div>
                <div class="panel-value text-center"
                     style="width: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <h2 style="margin: 0; font-size: 35px;">{{ $usersCount }}</h2>
                </div>
            </div>
        </div>
    </a>

    {{-- Categories --}}
    <a href="{{ route('categories.index') }}" style="color:black;">
        <div class="col-md-3">
            <div class="panel panel-box clearfix" style="display: flex; align-items: center; height: 110px;">
                <div class="panel-icon bg-red text-center"
                     style="width: 50%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <i class="glyphicon glyphicon-th-large" style="font-size: 40px; color: #FFA500;"></i>
                    <p style="color: white; font-size: 16px; margin: 5px 0 0;">Categories</p>
                </div>
                <div class="panel-value text-center"
                     style="width: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <h2 style="margin: 0; font-size: 35px;">{{ $categoriesCount }}</h2>
                </div>
            </div>
        </div>
    </a>

    {{-- Products --}}
    <a href="{{ route('products.index') }}" style="color:black;">
        <div class="col-md-3">
            <div class="panel panel-box clearfix" style="display: flex; align-items: center; height: 110px;">
                <div class="panel-icon bg-blue2 text-center"
                     style="width: 50%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <i class="glyphicon glyphicon-shopping-cart" style="font-size: 40px; color: #FFA500;"></i>
                    <p style="color: white; font-size: 16px; margin: 5px 0 0;">Products</p>
                </div>
                <div class="panel-value text-center"
                     style="width: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <h2 style="margin: 0; font-size: 35px;">{{ $productsCount }}</h2>
                </div>
            </div>
        </div>
    </a>

    {{-- Sales --}}
    <a href="{{ route('sales.index') }}" style="color:black;">
        <div class="col-md-3">
            <div class="panel panel-box clearfix" style="display: flex; align-items: center; height: 110px;">
                <div class="panel-icon bg-green text-center"
                     style="width: 50%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <i class="glyphicon glyphicon-usd" style="font-size: 40px; color: #FFA500;"></i>
                    <p style="color: white; font-size: 16px; margin: 5px 0 0;">Sales</p>
                </div>
                <div class="panel-value text-center"
                     style="width: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <h2 style="margin: 0; font-size: 35px;">{{ $salesCount }}</h2>
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
                        <th class="text-center" style="width: 1%;">#</th>
                        <th class="text-left" style="width: 8%;">Product Name</th>
                        <th class="text-center" style="width: 2%;">Qty</th>
                        <th class="text-center" style="width: 7%;">Total Sales</th>
                        <th class="text-center" style="width: 5%;">Admin/Cashier</th>
                        <th class="text-center" style="width: 8%;">Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sortedSales = $recentSales->sortByDesc('date'); @endphp
                    @forelse($sortedSales as $index => $sale)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td style="text-align: left; padding-left: 15px;">{{ $sale->product->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $sale->qty }}</td>
                            <td class="text-center">₱{{ number_format($sale->price, 2) }}</td>
                            <td class="text-center">{{ $sale->user->name ?? $sale->admin_name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $sale->date ? \Carbon\Carbon::parse($sale->date)->format('Y-m-d h:i A') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">No recent sales recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ✅ Chart + Summary + PDF --}}
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
                <strong><span class="glyphicon glyphicon-stats"></span> Sales Overview</strong>
                <div class="btn-group" role="group" aria-label="Select Range">
                    <button class="btn btn-default btn-xs" onclick="updateChart('daily')">Daily</button>
                    <button class="btn btn-default btn-xs" onclick="updateChart('weekly')">Weekly</button>
                    <button class="btn btn-default btn-xs" onclick="updateChart('monthly')">Monthly</button>
                </div>
            </div>

            <div class="panel-body" id="chart-section" style="height: 350px;">
                <canvas id="salesChart" style="max-width: 100%; height: 100%;"></canvas>
            </div>

            {{-- ✅ Sales Summary + PDF Button --}}
            <div class="panel-footer bg-light" id="sales-summary"
                 style="padding: 15px; display: flex; justify-content: space-around; text-align: center;">
                <div>
                    <h4>₱<span id="totalSales">0.00</span></h4>
                    <p class="text-muted">Total Sales</p>
                </div>
                <div>
                    <h4>₱<span id="averageSale">0.00</span></h4>
                    <p class="text-muted">Average Sale</p>
                </div>
                <div>
                    <h4>₱<span id="highestSale">0.00</span></h4>
                    <p class="text-muted">Highest Sale</p>
                </div>
                <div>
                    <h4>₱<span id="lowestSale">0.00</span></h4>
                    <p class="text-muted">Lowest Sale</p>
                </div>
                <div>
                    <button class="btn btn-primary btn-sm" onclick="downloadPDF()">
                        <i class="glyphicon glyphicon-download"></i> Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ Scripts --}}
<script src="{{ asset('libs/js/chart.umd.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
const ctx = document.getElementById('salesChart').getContext('2d');
let salesChart = new Chart(ctx, {
    type: 'line',
    data: { labels: [], datasets: [{
        label: 'Sales (₱)',
        data: [],
        borderColor: '#FFA500',
        backgroundColor: 'rgba(255,165,0,0.2)',
        fill: true,
        tension: 0.3
    }]},
    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
});

// ✅ Fixed Summary Calculations
function updateSalesSummary(values) {
    if (!values.length) return;
    const nums = values.map(v => parseFloat(v) || 0);
    const total = nums.reduce((a, b) => a + b, 0);
    const avg = total / nums.length;
    const max = Math.max(...nums);
    const min = Math.min(...nums);
    document.getElementById('totalSales').textContent = total.toLocaleString(undefined,{minimumFractionDigits:2});
    document.getElementById('averageSale').textContent = avg.toLocaleString(undefined,{minimumFractionDigits:2});
    document.getElementById('highestSale').textContent = max.toLocaleString(undefined,{minimumFractionDigits:2});
    document.getElementById('lowestSale').textContent = min.toLocaleString(undefined,{minimumFractionDigits:2});
}

// ✅ PDF Export
function downloadPDF() {
    const chartSection = document.getElementById('chart-section');
    html2canvas(chartSection).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jspdf.jsPDF('landscape', 'mm', 'a4');
        const imgWidth = 280;
        const imgHeight = canvas.height * imgWidth / canvas.width;
        pdf.text("Sales Overview Report", 14, 15);
        pdf.addImage(imgData, 'PNG', 10, 25, imgWidth, imgHeight);
        pdf.save('sales_overview.pdf');
    });
}

// ✅ Fetch + Update Chart
function updateChart(range) {
    fetch(`/chart-data?range=${range}`)
        .then(res => res.json())
        .then(res => {
            const nums = res.values.map(v => parseFloat(v) || 0);
            salesChart.data.labels = res.labels;
            salesChart.data.datasets[0].data = nums;
            salesChart.update();
            updateSalesSummary(nums);
        });
}

document.addEventListener("DOMContentLoaded", () => updateChart('monthly'));
</script>
@endsection
