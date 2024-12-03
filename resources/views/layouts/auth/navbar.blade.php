<nav class="layout-navbar mb-3 container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-menu-theme" id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <!-- Search -->
    <div class="navbar-nav align-items-center">
      <div class="nav-item d-flex align-items-center">
        {{-- <h5 class="m-0 text-white text-uppercase">{{ Auth::user()->name ?? '' }}</h5> --}}
      </div>
    </div>
    <!-- /Search -->

    @auth     
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <!-- User -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div>
              <i class="bx bx-user"></i> 
              <span class="fst-italic ">{{ Auth::user()->name ?? '' }}</span>
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="dropdown-item">
                <i class="bx bx-power-off me-2"></i>
                <span class="align-middle">Log Out</span>
              </button>
            </form>
          </li>
        </ul>
      </li>
      <!--/ User -->
    </ul>
    @endauth
  </div>
</nav>
