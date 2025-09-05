<script>
  // Sidebar toggle with persistence
  const wrapper = document.querySelector('.pos-wrapper');
  const toggleBtn = document.getElementById('btnSidebarToggle');
  const collapsedKey = 'pos.sidebar.collapsed';
  function applySidebarState(){
    const collapsed = localStorage.getItem(collapsedKey) === '1';
    wrapper.classList.toggle('sidebar-collapsed', collapsed);
  }
  applySidebarState();
  toggleBtn?.addEventListener('click', () => {
    const now = !(localStorage.getItem(collapsedKey) === '1');
    localStorage.setItem(collapsedKey, now ? '1' : '0');
    applySidebarState();
  });

  // Theme toggle (light/dark/auto)
  const root = document.documentElement;
  const themeKey = 'pos.theme';
  function setTheme(mode){
    if (mode === 'auto') {
      root.removeAttribute('data-bs-theme');
    } else {
      root.setAttribute('data-bs-theme', mode);
    }
    localStorage.setItem(themeKey, mode);
  }
  function initTheme(){
    setTheme(localStorage.getItem(themeKey) || 'light');
  }
  initTheme();
  document.getElementById('btnLight')?.addEventListener('click', () => setTheme('light'));
  document.getElementById('btnDark')?.addEventListener('click', () => setTheme('dark'));
  document.getElementById('btnAuto')?.addEventListener('click', () => setTheme('auto'));

  // Fullscreen toggle
  document.getElementById('btnFullscreen')?.addEventListener('click', () => {
    if (!document.fullscreenElement) {
      document.documentElement.requestFullscreen?.();
    } else {
      document.exitFullscreen?.();
    }
  });

  // Currency helper (IDR)
  window.formatIDR = function(n){
    try { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(Number(n||0)); }
    catch { return 'Rp' + (n||0).toLocaleString('id-ID'); }
  }
</script>