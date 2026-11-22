<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ url('/') }}" class="nav-link font-weight-bold text-primary">{{ config('app.name') }} {{ config('app.instansi') }}</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        @auth
        <li class="nav-item d-flex align-items-center mr-3">
            <span class="text-muted small">
                <i class="fas fa-user-circle mr-1"></i>
                {{ auth()->user()->name }}
                <span class="badge badge-info text-uppercase ml-1">{{ auth()->user()->role }}</span>
            </span>
        </li>
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Keluar">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </li>
        @endauth
    </ul>
</nav>