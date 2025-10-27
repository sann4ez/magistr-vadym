  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <a href="{{ url('admin/users'/*config('lte3.dashboard_slug')*/) }}" class="brand-link text-sm">
      <img src="/vendor/adminlte/dist/img/AdminLTELogo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{!! config('lte3.logo') !!}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        @include('admin.layouts.inc.sidebar-menu.shop')

        @include('admin.layouts.inc.sidebar-menu.system')

    </div>
    <!-- /.sidebar -->
  </aside>
