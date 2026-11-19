<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <span class="brand-image img-circle elevation-3 bg-warning d-flex align-items-center justify-content-center font-weight-bold text-dark" style="width: 33px; height: 33px; margin-left: .8rem;">
            <i class="fas fa-bread-slice"></i>
        </span>
        <span class="brand-text font-weight-bold ml-2">HPP Roti</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">MASTER DATA</li>

                <li class="nav-item">
                    <a href="{{ route('satuan.index') }}" class="nav-link {{ request()->routeIs('satuan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-balance-scale"></i>
                        <p>Master Satuan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('bahan.index') }}" class="nav-link {{ request()->routeIs('bahan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Master Bahan Baku</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('supplier.index') }}" class="nav-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Master Supplier</p>
                    </a>
                </li>

                <li class="nav-header">TRANSAKSI & INVENTORI</li>

                <li class="nav-item">
                    <a href="{{ route('stok-bahan.index') }}" class="nav-link {{ request()->routeIs('stok-bahan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>Stok Bahan Masuk</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>