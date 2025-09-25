{{-- Sidebar --}}
<aside class="pos-sidebar p-3 d-flex flex-column">
    {{-- Brand / Logo --}}
    <div class="d-flex align-items-center mb-4">
        <button id="btnSidebarToggle" class="btn btn-outline-secondary me-2" type="button" aria-label="Toggle sidebar">
            <i class="bi bi-layout-sidebar-inset"></i>
        </button>
        <a href="{{ url('/') }}" class="text-decoration-none d-flex align-items-center">
            <i class="bi bi-shop fs-4 text-primary me-2"></i>
            <span class="fs-5 fw-bold nav-text">{{ setting('company_name', 'Company Name') }}</span>
        </a>
    </div>

    {{-- Search --}}
    <form class="mb-3 sidebar-section" action="" method="get">
    <div class="input-group input-group-sm rounded-pill border overflow-hidden">
        <span class="input-group-text bg-transparent border-0">
        <i class="bi bi-search"></i>
        </span>
        <input name="q" type="search" class="form-control border-0" placeholder="Cari produk..." autocomplete="off">
    </div>
    </form>

    {{-- Navigation --}}
    <nav class="nav flex-column gap-1 flex-grow-1" id="sidebarMenu">
        <a class="nav-link d-flex align-items-center rounded {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-speedometer2 me-2"></i><span class="nav-text">Dashboard</span>
        </a>
        {{-- produk --}}
        <a class="nav-link d-flex align-items-center rounded" data-bs-toggle="collapse" href="#submenuProduk" role="button" aria-expanded="false" aria-controls="submenuProduk">
            <i class="bi bi-box-seam me-2"></i>
            <span class="nav-text">Produk</span>
            <i class="bi bi-caret-down ms-auto small"></i>
        </a>
         <div class="{{ $menu == 'item' ? 'expand' : 'collapse' }} ps-4" id="submenuProduk" data-bs-parent="#sidebarMenu">
            <a class="nav-link {{ Route::is('kategori/barang.*') ? 'active' : '' }}" href="{{ route('kategori/barang.index') }}">Kategori</a>
            <a class="nav-link {{ Route::is('barang.create') ? 'active' : '' }}" href="{{ route('barang.create') }}">Tambah Produk</a>
            <a class="nav-link {{ Route::is(['barang.index', 'barang.edit', 'barang.show']) == 'Produk' ? 'active' : '' }}" href="{{ route('barang.index') }}">All Produk</a>
        </div>
        {{-- end produk --}}
        {{-- pencatatan stok --}}
        <a class="nav-link d-flex align-items-center rounded" data-bs-toggle="collapse" href="#submenuCatatanStok" role="button" aria-expanded="false" aria-controls="submenuCatatanStok">
            <i class="bi bi-card-checklist me-2"></i>
            <span class="nav-text">Pencatatan Stok</span>
            <i class="bi bi-caret-down ms-auto small"></i>
        </a>
         <div class="{{ $menu == 'stok' ? 'expand' : 'collapse' }} ps-4" id="submenuCatatanStok" data-bs-parent="#sidebarMenu">
            <a class="nav-link {{ Route::is('stok/barang.index') ? 'active' : '' }}" href="{{ route('stok/barang.index') }}">Stok Produk</a>
            <a class="nav-link {{ Route::is('stok/barang.create') ? 'active' : '' }}" href="{{ route('stok/barang.create') }}">Adjustment Stok</a>
        </div>
        {{-- end pencatatan stok --}}

        <a class="nav-link d-flex align-items-center rounded {{ Route::is('user*') ? 'active' : '' }}" href="{{ route('user.index') }}">
            <i class="bi bi-person me-2"></i><span class="nav-text">Manajemen user</span>
        </a>
        <a class="nav-link d-flex align-items-center rounded {{ request()->is('sales*') ? 'active' : '' }}" href="{{ route('sales.create') }}">
            <i class="bi bi-cash-coin me-2"></i><span class="nav-text">Penjualan</span>
        </a>
        <a class="nav-link d-flex align-items-center rounded {{ Route::is('pembelian*') ? 'active' : '' }}" href="{{ route('pembelian.create') }}">
            <i class="bi bi-graph-up me-2"></i><span class="nav-text">Pembelian</span>
        </a>

        {{-- Laporan --}}
        <a class="nav-link d-flex align-items-center rounded" data-bs-toggle="collapse" href="#submenuLaporan" role="button" aria-expanded="false" aria-controls="submenuLaporan">
            <i class="bi bi-grid-3x3-gap me-2"></i>
            <span class="nav-text">Extras</span>
            <i class="bi bi-caret-down ms-auto small"></i>
        </a>
        <div class="{{ $menu == 'extras' ? 'expand' : 'collapse' }} ps-4" id="submenuLaporan" data-bs-parent="#sidebarMenu">
            <a class="nav-link" href="">Pembelian</a>
            <a class="nav-link" href="">Return Pembelian</a>
            <a class="nav-link {{ Route::is('sales/riwayat.*') ? 'active' : '' }}" href="{{ route('sales/riwayat.index') }}">Penjualan</a>
            <a class="nav-link" href="">Return Penjualan</a>
            <a class="nav-link" href="">Keuntungan / Kerugian</a>
            <a class="nav-link {{ Route::is('inventory/movement*') ? 'active' : '' }}" href="{{ route('inventory/movement.index') }}">Inventory Movements</a>
        </div>
        {{-- end Laporan --}}

        {{-- Pengaturan sistem --}}
        <a class="nav-link d-flex align-items-center rounded" data-bs-toggle="collapse" href="#submenuPengaturan" role="button" aria-expanded="false" aria-controls="submenuPengaturan">
            <i class="bi bi-gear me-2"></i>
            <span class="nav-text">Pengaturan</span>
            <i class="bi bi-caret-down ms-auto small"></i>
        </a>
        <div class="{{ $menu == 'settings' ? 'expand' : 'collapse' }} ps-4" id="submenuPengaturan" data-bs-parent="#sidebarMenu">
            <a class="nav-link {{ Route::is('supplier.*') ? 'active' : '' }}" href="{{ route('supplier.index') }}">Supplier</a>
            <a class="nav-link {{ Route::is('customer.*') ? 'active' : '' }}" href="{{ route('customer.index') }}">Pelanggan</a>
            <a class="nav-link {{ Route::is('unit.*') ? 'active' : '' }}" href="{{ route('unit.index') }}">Satuan</a>
            <a class="nav-link {{ Route::is('pengaturan/sistem.*') ? 'active' : '' }}" href="{{ route('pengaturan/sistem.index') }}">Pengaturan Sistem</a>
        </div>
        {{-- end Pengaturan sistem --}}

    </nav>

   {{-- Theme Switcher --}}
    <div class="mt-3 small text-body-secondary sidebar-section">
    <div class="d-flex align-items-center justify-content-between">
        <span class="nav-text fw-semibold">Tema</span>
        <div class="btn-group btn-group-sm" role="group">
        <button class="btn btn-outline-secondary" id="btnLight" type="button" title="Light"><i class="bi bi-brightness-high"></i></button>
        <button class="btn btn-outline-secondary" id="btnDark" type="button" title="Dark"><i class="bi bi-moon"></i></button>
        <button class="btn btn-outline-secondary" id="btnAuto" type="button" title="Auto"><i class="bi bi-circle-half"></i></button>
        </div>
    </div>
    </div>
</aside>
