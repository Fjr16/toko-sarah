{{-- Topbar --}}
<header class="pos-topbar bg-body p-2">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
          <button class="btn btn-outline-secondary no-print" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
            <i class="bi bi-bag"></i>
            <span class="d-none d-md-inline">Keranjang</span>
            <span class="badge text-bg-primary" id="cartCount">0</span>
          </button>
          <button class="btn btn-outline-secondary no-print" id="btnFullscreen" type="button"><i class="bi bi-arrows-fullscreen"></i></button>
        </div>

        <div class="d-flex align-items-center gap-2">
          @auth
            <div class="dropdown">
              <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="">Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Keluar</button>
                  </form>
                </li>
              </ul>
            </div>
          @endauth
        </div>
      </div>
    </div>
</header>