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
                    <li class="profile">
                        <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
                            <img src="{{ asset('uploads/users/default.png') }}" alt="user image"
                                class="img-circle img-inline">
                            <span>{{ Auth::user()->name ?? 'Admin' }} <i class="caret"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#"><i class="glyphicon glyphicon-user"></i> Profile</a></li>
                            <li><a href="#"><i class="glyphicon glyphicon-cog"></i> Settings</a></li>
                            <li class="last">
                                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-link" style="color:black; text-decoration:none;">
                                        <i class="glyphicon glyphicon-log-out"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
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
                    <li><a href="{{ route('reports.daily') }}">Daily Sales</a></li>
                    <li><a href="#">Monthly Sales</a></li>
                    <li><a href="#">Sales by Dates</a></li>
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

</body>
</html>
