<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <span class="brand-image img-circle elevation-3 bg-warning d-flex align-items-center justify-content-center font-weight-bold text-dark" style="width: 33px; height: 33px; margin-left: .8rem;">
            <i class="fas fa-bread-slice"></i>
        </span>
        <span class="brand-text font-weight-bold ml-2">HPP {{ config('app.instansi') }}</span>
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

                {{-- Cek otomatis apakah user sedang membuka salah satu halaman Master --}}
                @php
                    $isMasterActive = request()->routeIs('satuan.*', 'bahan.*', 'supplier.*');
                @endphp

                {{-- Menu Induk: Master (Dropdown Treeview) --}}
                <li class="nav-item {{ $isMasterActive ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $isMasterActive ? 'active' : '' }}">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Master
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('satuan.index') }}" class="nav-link {{ request()->routeIs('satuan.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Satuan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bahan.index') }}" class="nav-link {{ request()->routeIs('bahan.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bahan Baku</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('supplier.index') }}" class="nav-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Supplier</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Bagian Transaksi --}}
                <li class="nav-header">TRANSAKSI</li>

                <li class="nav-item">
                    <a href="{{ route('stok-bahan.index') }}" class="nav-link {{ request()->routeIs('stok-bahan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Stok Bahan Masuk</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>