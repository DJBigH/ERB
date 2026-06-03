<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{asset('backend/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">ERB System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('backend/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block" id="sidebar-user-name">Đang tải...</a>
          <small class="text-muted" id="sidebar-user-role">Đang tải...</small>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab active" data-tab="dashboard">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Tổng Quan Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="companies">
              <i class="nav-icon fas fa-building"></i>
              <p>Quản Lý Công Ty</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="branches">
              <i class="nav-icon fas fa-network-wired"></i>
              <p>Quản Lý Chi Nhánh</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="departments">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>Quản Lý Phòng Ban</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="positions">
              <i class="nav-icon fas fa-user-tag"></i>
              <p>Quản Lý Chức Vụ</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="users">
              <i class="nav-icon fas fa-users"></i>
              <p>Quản Lý Nhân Viên</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="roles">
              <i class="nav-icon fas fa-shield-alt"></i>
              <p>Vai Trò & Quyền Hạn</p>
            </a>
          </li>
          <li class="nav-header">TÀI KHOẢN</li>
          <li class="nav-item">
            <a href="#" class="nav-link text-warning" id="btn-logout">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Đăng Xuất (Logout)</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>