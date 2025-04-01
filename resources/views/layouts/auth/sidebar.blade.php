<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/dashboard" class="app-brand-link">
            <span class="app-brand-logo demo">
            </span>
            <span class="app-brand-text menu-text fw-bolder fs-4 ms-2 mt-1">TOKO SARAH</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none ">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">

        {{-- Main --}}
        {{-- <li class="menu-header small text-uppercase">
            <span class="menu-header-text text-secondary">Main</span>
        </li> --}}
        <!-- Dashboard -->
        <li class="menu-item {{ $title === 'Dashboard' ? 'active' : '' }}">
            <a href="/dashboard" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        {{-- <li class="menu-header small text-uppercase">
            <span class="menu-header-text text-white">Master Data</span>
        </li> --}}
        {{-- <li class="menu-item {{ $menu == 'data' ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bx-box'></i>
                <div>Data</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ $title == 'alternative' ? 'active' : '' }}">
                    <a href="{{ route('spk/destinasi/alternative.index') }}" class="menu-link">
                        <div>Barang</div>
                    </a>
                </li>
                <li class="menu-item {{ $title == 'kriteria' ? 'active' : '' }}">
                    <a href="{{ route('spk/destinasi/kriteria.index') }}" class="menu-link">
                        <div>Kriteria (C)</div>
                    </a>
                </li>
                <li class="menu-item {{ $title == 'penilaian' ? 'active' : '' }}">
                    <a href="{{ route('spk/destinasi/penilaian.index') }}" class="menu-link">
                        <div>Penilaian (R)</div>
                    </a>
                </li>
            </ul>
        </li> --}}

        {{-- @can('admin')       --}}
            <li class="menu-header small text-muted">
                <span class="menu-header-text text-uppercase">Administrator</span>
            </li> 
            <li class="menu-item {{ $menu == 'item' ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bx-box'></i>
                    <div>Product</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ $title == 'Kategori Produk' ? 'active' : '' }}">
                        <a href="{{ route('kategori/barang.index') }}" class="menu-link">
                            <div>Kategori</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title == 'add-item' ? 'active' : '' }}">
                        <a href="{{ route('barang.create') }}" class="menu-link">
                            <div>Tambah Product</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title == 'Item' ? 'active' : '' }}">
                        <a href="{{ route('barang.index') }}" class="menu-link">
                            <div>All Product</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title == 'Item-barcode' ? 'active' : '' }}">
                        <a href="" class="menu-link">
                            <div>Print Barcode</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item {{ $menu == 'stok' ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bx-trending-up'></i>
                    <div>Penambahan Stok</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ $title == 'add-stok' ? 'active' : '' }}">
                        <a href="" class="menu-link">
                            <div>Tambah Stok</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title == 'all-stok' ? 'active' : '' }}">
                        <a href="" class="menu-link">
                            <div>Riwayat Penambahan</div>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- <li class="menu-item {{ $title === 'Supplier' ? 'active' : '' }}">
                <a href="{{ route('supplier.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-store-alt"></i>
                    <div>Supplier</div>
                </a>
            </li> --}}
            <li class="menu-item {{ $title === 'User' ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>Manajemen user</div>
                </a>
            </li>
            <li class="menu-item {{ $title === 'Penjualan' ? 'active' : '' }}">
                <a href="{{ route('sales.create') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart"></i>
                    <div>Penjualan</div>
                </a>
            </li>
            <li class="menu-item {{ $title === 'Pembelian' ? 'active' : '' }}">
                <a href="{{ route('pembelian.create') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-store"></i>
                    <div>Pembelian</div>
                </a>
            </li>
            <li class="menu-item {{ $menu == 'Riwayat' ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bxs-report'></i>
                    <div>Laporan</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ $title == 'Riwayat Pembelian' ? 'active' : '' }}">
                        <a href="" class="menu-link">
                            <div>Pembelian</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title == 'Return Pembelian' ? 'active' : '' }}">
                        <a href="" class="menu-link">
                            <div>Return Pembelian</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title == 'Riwayat Penjualan' ? 'active' : '' }}">
                        <a href="{{ route('sales/riwayat.index') }}" class="menu-link">
                            <div>Penjualan</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title == 'Return Penjualan' ? 'active' : '' }}">
                        <a href="" class="menu-link">
                            <div>Return Penjualan</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title == 'Return Penjualan' ? 'active' : '' }}">
                        <a href="" class="menu-link">
                            <div>Keuntungan / Kerugian</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item {{ $menu == 'settings' ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bx-cog'></i>
                    <div>Pengaturan</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ $title == 'Supplier' ? 'active' : '' }}">
                        <a href="{{ route('supplier.index') }}" class="menu-link">
                            <div>Supplier</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title == 'Pelanggan' ? 'active' : '' }}">
                        <a href="{{ route('customer.index') }}" class="menu-link">
                            <div>Pelanggan</div>
                        </a>
                    </li>
                    <li class="menu-item disabled {{ $title == 'Satuan' ? 'active' : '' }}" style="pointer-events: none;">
                        <a href="{{ route('unit.index') }}" class="menu-link">
                            <div>Satuan </div>
                            <span class="badge bg-danger text-bg-warning ms-2">Pro</span>
                        </a>
                    </li>
                    <li class="menu-item {{ $title == 'Mata Uang' ? 'active' : '' }}">
                        <a href="{{ route('currency.index') }}" class="menu-link">
                            <div>Mata Uang</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title == 'Pengaturan Sistem' ? 'active' : '' }}">
                        <a href="{{ route('pengaturan/sistem.index') }}" class="menu-link">
                            <div>Pengaturan Sistem</div>
                        </a>
                    </li>
                </ul>
            </li>
        {{-- @endcan --}}
        
    </ul>
</aside>
