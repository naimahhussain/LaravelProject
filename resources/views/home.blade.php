<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Naimah Electronics Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .navbar-brand { font-weight: 800; font-size: 1.5rem; }
        .hero { background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460); color: white; padding: 80px 0; }
        .hero h1 { font-size: 3rem; font-weight: 800; }
        .category-card { transition: transform .2s, box-shadow .2s; cursor: pointer; border-radius: 12px; }
        .category-card:hover { transform: translateY(-5px); }
        .category-card.active { border: 2px solid #0f3460 !important; box-shadow: 0 4px 15px rgba(15,52,96,0.3) !important; }
        .category-card.active h6 { color: #0f3460; }
        .product-card { transition: transform .2s; border-radius: 12px; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important; }
        .product-img { height: 180px; display: flex; align-items: center; justify-content: center; font-size: 4rem; background: #f1f3f5; border-radius: 12px 12px 0 0; }
        .cart-badge { position: relative; }
        .cart-count { position: absolute; top: -8px; right: -8px; background: #e74c3c; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 11px; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        footer { background: #1a1a2e; color: #adb5bd; }
        .toast-container { position: fixed; bottom: 20px; right: 20px; z-index: 9999; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background:#0f3460;">
    <div class="container">
        <a class="navbar-brand" href="/"><i class="fas fa-bolt text-warning"></i> Naimah Electronics</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="#products">Products</a></li>
                <li class="nav-item mr-3">
                    <a class="nav-link cart-badge" href="{{ route('cart') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count" id="cartCount">{{ array_sum(session()->get('cart', [])) }}</span>
                    </a>
                </li>
                @auth
                    <li class="nav-item">
                        <span class="nav-link text-white-50"><i class="fas fa-user mr-1"></i>{{ Auth::user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item mr-2"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="btn btn-warning btn-sm" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Flash messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show m-0 rounded-0" role="alert">
    <div class="container">{{ session('success') }}</div>
    <button type="button" class="close pr-3" data-dismiss="alert">&times;</button>
</div>
@endif

<!-- Hero -->
<section class="hero text-center">
    <div class="container">
        <h1><i class="fas fa-bolt text-warning"></i> Naimah Electronics</h1>
        <p class="lead mt-3">Discover the latest phones, laptops, TVs, and more</p>
        <a href="#products" class="btn btn-warning btn-lg mt-3 font-weight-bold">
            <i class="fas fa-shopping-bag mr-2"></i>Shop Now
        </a>
    </div>
</section>

<!-- Categories -->
@if($categories->count())
<section id="categories" class="py-5">
    <div class="container">
        <h2 class="font-weight-bold mb-4">Shop by Category</h2>
        <div class="row">
            <div class="col-6 col-md-3 mb-4">
                <div class="card category-card border-0 shadow-sm text-center p-3" data-category="all" onclick="filterCategory('all', this)">
                    <div class="display-4 mb-2">🛒</div>
                    <h6 class="font-weight-bold">All</h6>
                    <small class="text-muted">{{ $products->count() }} products</small>
                </div>
            </div>
            @foreach($categories as $category)
            <div class="col-6 col-md-3 mb-4">
                <div class="card category-card border-0 shadow-sm text-center p-3" data-category="{{ $category->name }}" onclick="filterCategory('{{ $category->name }}', this)">
                    <div class="display-4 mb-2">
                        @php $icons=['Phones'=>'📱','Laptops'=>'💻','TVs'=>'📺','Tablets'=>'📟','Cameras'=>'📷','Audio'=>'🎧','Gaming'=>'🎮','Accessories'=>'⌨️']; echo $icons[$category->name] ?? '🔌'; @endphp
                    </div>
                    <h6 class="font-weight-bold">{{ $category->name }}</h6>
                    <small class="text-muted">{{ $category->products_count }} products</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Products -->
<section id="products" class="py-5 bg-white">
    <div class="container">
        <h2 class="font-weight-bold mb-4">All Products</h2>
        @if($products->count())
        <div class="row" id="productsGrid">
            @foreach($products as $product)
            <div class="col-md-4 col-lg-3 mb-4 product-item" data-category="{{ $product->category->name ?? '' }}">
                <div class="card product-card border-0 shadow-sm h-100">
                    <div class="product-img">
                        @php echo $icons[$product->category->name ?? ''] ?? '🔌'; @endphp
                    </div>
                    <div class="card-body d-flex flex-column">
                        <span class="badge badge-primary mb-2" style="font-size:.75rem;width:fit-content;">{{ $product->category->name ?? 'Uncategorized' }}</span>
                        <h6 class="font-weight-bold">{{ $product->name }}</h6>
                        <p class="text-muted small flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="h5 font-weight-bold text-primary mb-0">${{ number_format($product->price, 2) }}</span>
                            <small class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                            </small>
                        </div>
                        @if($product->stock > 0)
                        <button class="btn btn-outline-primary btn-sm mt-3 add-to-cart"
                            data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                            <i class="fas fa-cart-plus mr-1"></i> Add to Cart
                        </button>
                        @else
                        <button class="btn btn-outline-secondary btn-sm mt-3" disabled>Out of Stock</button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center py-5 text-muted d-none" id="noResults">
            <i class="fas fa-search fa-3x mb-3 d-block"></i>
            <p id="noResultsText">No products found in this category.</p>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
            <p>No products available yet.</p>
        </div>
        @endif
    </div>
</section>

<!-- Footer -->
<footer class="py-4 text-center">
    <div class="container">
        <p class="mb-0"><i class="fas fa-bolt text-warning"></i> Naimah Electronics &copy; {{ date('Y') }}</p>
    </div>
</footer>

<!-- Toast notification -->
<div class="toast-container">
    <div id="cartToast" class="toast" role="alert" data-delay="2500">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle mr-2"></i>
            <strong class="mr-auto">Cart Updated</strong>
            <button type="button" class="ml-2 close text-white" data-dismiss="toast">&times;</button>
        </div>
        <div class="toast-body" id="toastMessage"></div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script>
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

function filterCategory(category, el) {
    // Update active state on category cards
    $('.category-card').removeClass('active');
    $(el).addClass('active');

    const items = $('.product-item');
    let visible = 0;

    if (category === 'all') {
        items.show();
        visible = items.length;
    } else {
        items.each(function () {
            if ($(this).data('category') === category) {
                $(this).show();
                visible++;
            } else {
                $(this).hide();
            }
        });
    }

    // Show/hide "no results" message
    if (visible === 0) {
        $('#noResults').removeClass('d-none');
        $('#noResultsText').text('No products found in "' + category + '".');
    } else {
        $('#noResults').addClass('d-none');
    }

    // Scroll to products section
    $('html, body').animate({ scrollTop: $('#products').offset().top - 70 }, 400);
}

$('.add-to-cart').on('click', function () {
    const btn = $(this);
    const productId = btn.data('id');
    const productName = btn.data('name');

    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Adding...');

    $.post('/cart/add/' + productId)
        .done(function (res) {
            $('#cartCount').text(res.count);
            $('#toastMessage').text(res.message);
            $('#cartToast').toast('show');
            btn.html('<i class="fas fa-check mr-1"></i> Added!').addClass('btn-success').removeClass('btn-outline-primary');
            setTimeout(() => {
                btn.prop('disabled', false).html('<i class="fas fa-cart-plus mr-1"></i> Add to Cart')
                   .removeClass('btn-success').addClass('btn-outline-primary');
            }, 2000);
        })
        .fail(function () {
            btn.prop('disabled', false).html('<i class="fas fa-cart-plus mr-1"></i> Add to Cart');
            alert('Failed to add to cart. Please try again.');
        });
});
</script>
</body>
</html>
