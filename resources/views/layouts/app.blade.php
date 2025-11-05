<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GrainHub Admin')</title>

    {{-- ✅ Bootstrap Core CSS --}}
    <link rel="stylesheet" href="{{ asset('libs/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css">

    {{-- ✅ Font Awesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- ✅ Custom GrainHub Styles --}}
    <link rel="stylesheet" href="{{ asset('libs/css/main.css') }}">

    {{-- ✅ JS Core Libraries --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

    {{-- ✅ Header --}}
    <header id="header">
        <div class="logo pull-left">
            <img class="grainhub-logo" src="{{ asset('libs/images/grainhub-logo.png') }}" alt="GrainHub Logo">
            <span class="logo-text">GrainHub IMS</span>
        </div>

        <div class="header-content">
            <div class="header-date pull-left">
                <strong id="currentDateTime">{{ now()->setTimezone('Asia/Manila')->format('F j, Y - g:i A') }}</strong>
            </div>

            <div class="pull-right clearfix">
                <ul class="info-menu list-inline list-unstyled">
                    <li class="dropdown profile" style="list-style:none;">

    @php
        $authUser = Auth::user();
        $userImage = $authUser && $authUser->image
            ? asset('storage/' . $authUser->image)
            : asset('uploads/users/default.png');
    @endphp

    {{-- ✅ Profile Toggle --}}
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"
       style="display:flex; align-items:center; gap:6px; padding:4px 8px; text-decoration:none;">
       
        <img 
            src="{{ $userImage }}" 
            alt="User Photo" 
            style="
                width:32px !important;
                height:32px !important;
                border-radius:50% !important;
                object-fit:cover !important;
                display:inline-block !important;
            "
        >

        <span style="font-size:13px; color:white;">
            {{ $authUser->name ?? 'Admin' }} <i class="caret"></i>
        </span>
    </a>

    {{-- ✅ Dropdown Menu --}}
<ul class="dropdown-menu dropdown-menu-right" 
    style="
        min-width: 200px;
        padding: 0;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        right: 0;
        left: auto;
    ">

    {{-- 🧍 Profile Header --}}
    <li style="background: #122030; color: white; text-align: center; padding: 15px;">
        <img 
            src="{{ $userImage }}" 
            alt="user image" 
            style="
                width: 60px;
                height: 60px;
                border-radius: 50%;
                object-fit: cover;
                margin-bottom: 6px;
            "
        >
        <p style="margin: 0; font-size: 14px;">{{ ucfirst($authUser->name) }}</p>
        <small>{{ '@' . $authUser->username }}</small>
    </li>

    <li class="divider" style="margin: 0;"></li>

    {{-- ⚙️ Profile & Settings --}}
    <li>
        <a href="{{ route('profile.show') }}" 
           class="dropdown-item-link"
           style="padding: 10px 20px; display: block;">
            <i class="glyphicon glyphicon-user"></i>
            Profile
        </a>
    </li>
    <li>
        <a href="{{ route('profile.edit') }}" 
           class="dropdown-item-link"
           style="padding: 10px 20px; display: block;">
            <i class="glyphicon glyphicon-cog"></i>
            Settings
        </a>
    </li>

    <li class="divider" style="margin: 0;"></li>

    {{-- 🔒 Logout --}}
    <li style="padding: 0;">
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button 
                type="submit" 
                class="dropdown-logout-btn"
                aria-label="Logout"
                style="
                    width: 100%;
                    text-align: left;
                    padding: 10px 20px; /* ✅ aligns perfectly with other items */
                    border: none;
                    background: transparent;
                    color: #333;
                    font-size: 14px;
                    cursor: pointer;
                ">
                <i class="glyphicon glyphicon-log-out"></i>
                Logout
            </button>
        </form>
    </li>
</ul>






</ul>

    </header>

    {{-- ✅ Sidebar --}}
    <div class="sidebar">
        <ul class="nav nav-pills nav-stacked">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="glyphicon glyphicon-home"></i> <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="#" class="submenu-toggle">
                    <i class="glyphicon glyphicon-user"></i> <span>User Management</span>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('groups.index') }}">Manage Groups</a></li>
                    <li><a href="{{ route('users.index') }}">Manage Users</a></li>
                </ul>
            </li>

            <li>
                <a href="{{ route('categories.index') }}">
                    <i class="glyphicon glyphicon-indent-left"></i> <span>Categories</span>
                </a>
            </li>

            <li>
                <a href="#" class="submenu-toggle">
                    <i class="glyphicon glyphicon-th-large"></i> <span>Products</span>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('products.index') }}">Manage Products</a></li>
                    <li><a href="{{ route('products.create') }}">Add Product</a></li>
                </ul>
            </li>

            <li>
                <a href="#" class="submenu-toggle">
                    <i class="glyphicon glyphicon-briefcase"></i> <span>Suppliers</span>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('suppliers.index') }}">Manage Suppliers</a></li>
                    <li><a href="{{ route('suppliers.create') }}">Add Supplier</a></li>
                </ul>
            </li>

            <li>
                <a href="{{ route('sales.index') }}">
                    <i class="glyphicon glyphicon-credit-card"></i> <span>Sales</span>
                </a>
            </li>

            <li>
                <a href="#" class="submenu-toggle">
                    <i class="glyphicon glyphicon-duplicate"></i> <span>Sales Reports</span>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('reports.byDates') }}">Sales by Dates</a></li>
                    <li><a href="{{ route('reports.daily') }}">Daily Sales</a></li>
                    <li><a href="{{ route('reports.monthly') }}">Monthly Sales</a></li>
                    
                </ul>
            </li>
        </ul>
    </div>

    {{-- ✅ Main Content --}}
    <div class="page">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>

    {{-- ✅ JS: Sidebar toggle & live clock --}}
    <script>
        $(document).ready(function () {
            // Sidebar submenu
            $('.submenu-toggle').on('click', function (e) {
                e.preventDefault();
                $(this).next('.submenu').slideToggle(200);
            });

            // Live time updater
            function updateDateTime() {
                const now = new Date();
                const options = {
                    weekday: 'long', year: 'numeric', month: 'long',
                    day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true
                };
                document.getElementById('currentDateTime').textContent =
                    now.toLocaleString('en-US', options);
            }
            setInterval(updateDateTime, 60000); // update every 1 minute
        });
    </script>
@stack('scripts')

</body>
</html>
