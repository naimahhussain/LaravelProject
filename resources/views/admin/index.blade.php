@extends('admin.adminbase')
@section('title', 'Dashboard')
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <small class="text-muted"><i class="fas fa-sync-alt mr-1"></i> Auto-refreshes every 15 seconds</small>
</div>

<!-- Stats Cards -->
<div class="row" id="statsRow">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Products</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-products">{{ $totalProducts }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-box fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Categories</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-categories">{{ $totalCategories }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-tags fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Orders</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-orders">{{ $totalOrders }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-shopping-cart fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Revenue</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-revenue">${{ number_format($totalRevenue, 2) }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="ordersTable">
                <thead class="thead-light">
                    <tr><th>#</th><th>Customer</th><th>Email</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr>
                </thead>
                <tbody id="ordersBody">
                    @forelse($recentOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->customer_email }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td><span class="badge badge-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : ($order->status === 'shipped' ? 'info' : 'warning')) }}">{{ ucfirst($order->status) }}</span></td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td><a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr id="noOrders"><td colspan="7" class="text-center">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function refreshDashboard() {
    $.getJSON('{{ route('admin.stats') }}', function(data) {
        $('#stat-products').text(data.totalProducts);
        $('#stat-categories').text(data.totalCategories);
        $('#stat-orders').text(data.totalOrders);
        $('#stat-revenue').text('$' + parseFloat(data.totalRevenue).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

        if (data.recentOrders.length === 0) return;

        const statusColors = { delivered: 'success', cancelled: 'danger', shipped: 'info', pending: 'warning', processing: 'warning' };
        let rows = '';
        data.recentOrders.forEach(function(o) {
            const color = statusColors[o.status] || 'warning';
            const date = new Date(o.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            rows += `<tr>
                <td>${o.id}</td>
                <td>${o.customer_name}</td>
                <td>${o.customer_email}</td>
                <td>$${parseFloat(o.total).toFixed(2)}</td>
                <td><span class="badge badge-${color}">${o.status.charAt(0).toUpperCase() + o.status.slice(1)}</span></td>
                <td>${date}</td>
                <td><a href="/admin/orders/${o.id}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a></td>
            </tr>`;
        });
        $('#ordersBody').html(rows);
    });
}

// Poll every 15 seconds
setInterval(refreshDashboard, 15000);
</script>
@endsection
