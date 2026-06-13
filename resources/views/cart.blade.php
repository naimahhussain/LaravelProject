<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cart - Naimah Electronics</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .navbar-brand { font-weight: 800; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background:#0f3460;">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}"><i class="fas fa-bolt text-warning"></i> Naimah Electronics</a>
        <ul class="navbar-nav ml-auto">
            @auth
                <li class="nav-item"><span class="nav-link text-white">{{ Auth::user()->name }}</span></li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                        <button type="submit" class="btn btn-link nav-link text-white p-0">Logout</button>
                    </form>
                </li>
            @else
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
            @endauth
        </ul>
    </div>
</nav>

<div class="container mt-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h2 class="font-weight-bold mb-4"><i class="fas fa-shopping-cart mr-2"></i>Your Cart</h2>

    @if(empty($products))
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Your cart is empty</h4>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Continue Shopping</a>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        @foreach($products as $item)
                        <div class="d-flex align-items-center border-bottom py-3">
                            <div class="mr-3" style="font-size:2rem;">
                                @php $icons=['Phones'=>'📱','Laptops'=>'💻','TVs'=>'📺','Tablets'=>'📟','Cameras'=>'📷','Audio'=>'🎧','Gaming'=>'🎮','Accessories'=>'⌨️']; echo $icons[$item['product']->category->name ?? ''] ?? '🔌'; @endphp
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 font-weight-bold">{{ $item['product']->name }}</h6>
                                <small class="text-muted">{{ $item['product']->category->name ?? '' }}</small>
                            </div>
                            <div class="mx-3">
                                <form method="POST" action="{{ route('cart.update', $item['product']->id) }}" class="d-inline-flex align-items-center">
                                    @csrf @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99"
                                        class="form-control form-control-sm" style="width:70px;">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary ml-1">Update</button>
                                </form>
                            </div>
                            <div class="mx-3 font-weight-bold text-primary">
                                ${{ number_format($item['product']->price * $item['quantity'], 2) }}
                            </div>
                            <a href="{{ route('cart.remove', $item['product']->id) }}" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="font-weight-bold">Order Summary</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span><span>${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span><span class="text-success">Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between font-weight-bold h5">
                            <span>Total</span><span class="text-primary">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="font-weight-bold">Checkout</h5>
                        <form method="POST" action="{{ route('checkout') }}">
                            @csrf
                            <div class="form-group">
                                <label class="small font-weight-bold">Full Name *</label>
                                <input type="text" name="customer_name" class="form-control form-control-sm @error('customer_name') is-invalid @enderror"
                                    value="{{ old('customer_name', Auth::user()->name ?? '') }}" required>
                                @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Email *</label>
                                <input type="email" name="customer_email" class="form-control form-control-sm @error('customer_email') is-invalid @enderror"
                                    value="{{ old('customer_email', Auth::user()->email ?? '') }}" required>
                                @error('customer_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Phone</label>
                                <input type="text" name="customer_phone" class="form-control form-control-sm"
                                    value="{{ old('customer_phone') }}">
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Delivery Address *</label>
                                <textarea name="address" class="form-control form-control-sm @error('address') is-invalid @enderror"
                                    rows="2" required>{{ old('address') }}</textarea>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check-circle mr-2"></i>Place Order — ${{ number_format($total, 2) }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
