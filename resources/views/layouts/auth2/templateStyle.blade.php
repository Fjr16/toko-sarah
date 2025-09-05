<style>
    :root {
      --pos-sidebar-width: 300px;
    }
    html, body { height: 100%; }
    body.pos-layout { overflow: hidden; }

    /* Layout */
    .pos-wrapper { display: grid; grid-template-columns: var(--pos-sidebar-width) 1fr; grid-template-rows: auto 1fr auto; height: 100vh; }
    .pos-sidebar { grid-row: 1 / span 3; grid-column: 1; border-right: 1px solid var(--bs-border-color); overflow: auto; }
    .pos-topbar { grid-row: 1; grid-column: 2; border-bottom: 1px solid var(--bs-border-color); }
    .pos-content { grid-row: 2; grid-column: 2; overflow: auto; }
    .pos-checkout { grid-row: 3; grid-column: 2; border-top: 1px solid var(--bs-border-color); }

    /* Sidebar collapse */
    .pos-wrapper.sidebar-collapsed { grid-template-columns: 72px 1fr; }
    .pos-wrapper.sidebar-collapsed .pos-sidebar .nav-text { display: none; }
    .pos-wrapper.sidebar-collapsed .pos-sidebar { width: 72px; }

    /* Helpful utilities */
    .scroll-y { overflow-y: auto; }
    .product-card { border: 1px solid var(--bs-border-color); border-radius: .75rem; }
    .product-card:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.075); }

    .pos-sidebar .nav-link {
        font-weight: 500;
        color: var(--bs-body-color);
        transition: color .2s ease, background-color .2s ease;
        padding: .65rem .9rem;
    }
    .pos-sidebar .nav-link:hover {
        background-color: var(--bs-primary-bg-subtle);
        color: var(--bs-primary);
    }
    .pos-sidebar .nav-link.active {
        background-color: var(--bs-primary);
        color: #fff;
        box-shadow: 0 .25rem .5rem rgba(0,0,0,.1);
    }

    /* Saat sidebar collapsed */
    .pos-wrapper.sidebar-collapsed .pos-sidebar .sidebar-section .nav-text,
    .pos-wrapper.sidebar-collapsed .pos-sidebar .sidebar-section .form-control {
    display: none !important;
    }

    .pos-wrapper.sidebar-collapsed .pos-sidebar .input-group-text {
    justify-content: center;
    width: 100%;
    }

    .pos-wrapper.sidebar-collapsed .pos-sidebar .btn-group {
    flex-direction: column;
    width: 100%;
    gap: .25rem;
    }

    .pos-wrapper.sidebar-collapsed .pos-sidebar .btn-group .btn {
    border-radius: 50% !important;
    padding: .5rem;
    }

    .pos-sidebar .collapse .nav-link {
    font-size: .9rem;
    padding: .5rem 1rem;
    border-radius: .25rem;
    opacity: 0;
    transform: translateY(-3px);
    transition: opacity .2s ease, transform .2s ease;
    }
    .pos-sidebar .collapse .nav-link:hover {
    background: var(--bs-primary-bg-subtle);
    color: var(--bs-primary);
    }
    .pos-sidebar .collapse.show .nav-link {
    opacity: 1;
    transform: translateY(0);
    }


    /* Receipt/print mode */
    @media print {
      .pos-sidebar, .pos-topbar, .pos-checkout, .no-print { display: none !important; }
      .pos-content { grid-column: 1 / span 2; padding: 0 !important; }
    }
    @media (max-width: 768px) {
    .pos-wrapper {
        grid-template-columns: 1fr;
        grid-template-rows: auto 1fr auto;
    }

    .pos-sidebar {
        position: fixed;
        top: 0;
        left: -100%;
        height: 100%;
        z-index: 1050;
        width: var(--pos-sidebar-width);
        background: var(--bs-body-bg);
        transition: left .3s ease;
    }

    .pos-wrapper.sidebar-open .pos-sidebar {
        left: 0;
    }

    .pos-topbar, .pos-content, .pos-checkout {
        grid-column: 1;
    }
    }

</style>