@extends('admin.adminbase')
@section('title', 'Order #' . $order->id)
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Order #{{ $order->id }}</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Back</a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header font-weight-bold">Customer Info</div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                <p><strong>Phone:</strong> {{ $order->customer_phone ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $order->address }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header font-weight-bold">Order Status</div>
            <div class="card-body">
                <p>Current: <span class="badge badge-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }} badge-lg">{{ ucfirst($order->status) }}</span></p>
                <div class="btn-group flex-wrap">
                    @foreach(['pending','processing','shipped','delivered','cancelled'] as $status)
                        <a href="{{ route('admin.orders.status', [$order, $status]) }}"
                            class="btn btn-sm {{ $order->status === $status ? 'btn-primary' : 'btn-outline-secondary' }} mb-1">
                            {{ ucfirst($status) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-header font-weight-bold">Order Items</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr><th>Product</th><th>Category</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                    <td>{{ $item->product->category->name ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
                <tr class="table-active">
                    <td colspan="4" class="text-right font-weight-bold">Total</td>
                    <td class="font-weight-bold text-primary">${{ number_format($order->total, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
