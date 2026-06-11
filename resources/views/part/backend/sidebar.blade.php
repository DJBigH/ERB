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
          <li class="nav-header">CẤU HÌNH & THIẾT LẬP KHO</li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="warehouses">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>Quản Lý Kho</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="skus">
              <i class="nav-icon fas fa-boxes"></i>
              <p>Quản Lý Sản Phẩm (SKU)</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="categories">
              <i class="nav-icon fas fa-tags"></i>
              <p>Nhóm Sản Phẩm</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="suppliers">
              <i class="nav-icon fas fa-truck"></i>
              <p>Nhà Cung Cấp</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="warehouse-scopes">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>Phân Quyền Kho</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="safe-stock-configs">
              <i class="nav-icon fas fa-shield-alt"></i>
              <p>Tồn Kho An Toàn</p>
            </a>
          </li>
          <li class="nav-header">NGHIỆP VỤ KHO</li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="inbound-documents">
              <i class="nav-icon fas fa-file-import"></i>
              <p>Nhập Kho (Inbound)</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="outbound-documents">
              <i class="nav-icon fas fa-file-export"></i>
              <p>Xuất Kho (Outbound)</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="transfer-orders">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p>Điều Chuyển Kho</p>
            </a>
          </li>
          <li class="nav-header">BÁO CÁO & TỒN KHO</li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="inventory-balances">
              <i class="nav-icon fas fa-calculator"></i>
              <p>Số Dư Tồn Kho</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="stock-movements">
              <i class="nav-icon fas fa-history"></i>
              <p>Lịch Sử Biến Động (Thẻ Kho)</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-tab" data-tab="reports-xnt">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>Báo Cáo Xuất Nhập Tồn</p>
            </a>
          </li>
          <li class="nav-header">HỆ THỐNG</li>
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