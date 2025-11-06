<ul class="sidebar-menu">

    {{-- ✅ Common for all users --}}
    <li>
        <a href="{{ route('dashboard') }}">
            <i class="glyphicon glyphicon-home"></i>
            <span>Dashboard</span>
        </a>
    </li>

    {{-- ✅ ADMIN ONLY --}}
    @if(Auth::check() && Auth::user()->user_level == 1)
        <li>
            <a href="{{ route('users.index') }}">
                <i class="glyphicon glyphicon-user"></i>
                <span>User Management</span>
            </a>
        </li>

        <li>
            <a href="{{ route('categories.index') }}">
                <i class="glyphicon glyphicon-tags"></i>
                <span>Categories</span>
            </a>
        </li>

        <li>
            <a href="{{ route('suppliers.index') }}">
                <i class="glyphicon glyphicon-briefcase"></i>
                <span>Suppliers</span>
            </a>
        </li>

        <li>
            <a href="{{ route('products.index') }}">
                <i class="glyphicon glyphicon-list-alt"></i>
                <span>Products</span>
            </a>
        </li>

        <li>
            <a href="{{ route('sales.index') }}">
                <i class="glyphicon glyphicon-credit-card"></i>
                <span>Sales</span>
            </a>
        </li>

        <li class="submenu">
            <a href="#">
                <i class="glyphicon glyphicon-signal"></i>
                <span>Sales Reports</span>
            </a>
            <ul class="submenu-list">
                <li><a href="{{ route('sales.report') }}">Sales by Dates</a></li>
                <li><a href="{{ route('sales.monthly') }}">Monthly Sales</a></li>
                <li><a href="{{ route('sales.daily') }}">Daily Sales</a></li>
            </ul>
        </li>
    @endif


    {{-- ✅ CASHIER ONLY --}}
    @if(Auth::check() && Auth::user()->user_level == 2)
        <li>
            <a href="{{ route('products.index') }}">
                <i class="glyphicon glyphicon-list-alt"></i>
                <span>Products</span>
            </a>
        </li>

        <li>
            <a href="{{ route('sales.index') }}">
                <i class="glyphicon glyphicon-credit-card"></i>
                <span>Sales</span>
            </a>
        </li>

        <li class="submenu">
            <a href="#">
                <i class="glyphicon glyphicon-signal"></i>
                <span>Sales Reports</span>
            </a>
            <ul class="submenu-list">
                <li><a href="{{ route('sales.report') }}">Sales by Dates</a></li>
                <li><a href="{{ route('sales.monthly') }}">Monthly Sales</a></li>
                <li><a href="{{ route('sales.daily') }}">Daily Sales</a></li>
            </ul>
        </li>
    @endif

</ul>
