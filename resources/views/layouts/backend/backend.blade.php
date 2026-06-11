?<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ERB Management System | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('backend/css/adminlte.min.css') }}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  
  <style>
    /* Sleek Glassmorphism Login Screen */
    #app-login {
      background: radial-gradient(circle at 10% 20%, rgb(85, 149, 232) 0%, rgb(183, 219, 247) 90%);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      width: 100vw;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 9999;
      transition: all 0.5s ease-in-out;
    }
    .login-glass-card {
      background: rgba(255, 255, 255, 0.25);
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-radius: 16px;
      border: 1px rgba(255, 255, 255, 0.3) solid;
      width: 400px;
      padding: 40px 30px;
      color: #1f2937;
    }
    .login-glass-card h2 {
      font-weight: 700;
      text-align: center;
      margin-bottom: 25px;
      letter-spacing: 1px;
      color: #0f172a;
    }
    .login-glass-card .form-control {
      background: rgba(255, 255, 255, 0.6);
      border: 1px rgba(255, 255, 255, 0.4) solid;
      border-radius: 8px;
      color: #1f2937;
    }
    .login-glass-card .form-control:focus {
      background: rgba(255, 255, 255, 0.9);
      box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
      border-color: #2563eb;
    }
    .login-glass-card .btn-primary {
      background: #1d4ed8;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      padding: 10px;
      transition: all 0.3s;
    }
    .login-glass-card .btn-primary:hover {
      background: #1e40af;
      transform: translateY(-2px);
    }
    .nav-tab.active {
      background-color: #007bff !important;
      color: white !important;
    }
    .spa-section {
      display: none;
    }
    .spa-section.active-section {
      display: block;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">

  <!-- ==================== LOGIN SECTION ==================== -->
  <div id="app-login">
    <div class="login-glass-card">
      <h2>HỆ THỐNG ERB</h2>
      <p class="text-center text-muted mb-4">Vui lòng đăng nhập tài khoản hệ thống của bạn</p>
      
      <form id="form-login">
        <div class="form-group mb-3">
          <label class="form-label font-weight-bold">Địa chỉ Email</label>
          <div class="input-group">
            <input type="email" id="login-email" class="form-control" placeholder="admin@erb.vn" required>
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            </div>
          </div>
        </div>
        <div class="form-group mb-4">
          <label class="form-label font-weight-bold">Mật khẩu</label>
          <div class="input-group">
            <input type="password" id="login-password" class="form-control" placeholder="••••••••" required>
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Đăng Nhập</button>
      </form>
    </div>
  </div>

  <!-- ==================== MAIN APP DASHBOARD SHELL ==================== -->
  <div id="app-wrapper" class="wrapper" style="display: none;">
    
    <!-- Navbar Header -->
    @include('part.backend.header')
    
    <!-- Sidebar Left -->
    @include('part.backend.sidebar')

    <!-- Main Content Panel -->
    <div class="content-wrapper">
      
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 id="page-title">Tổng Quan Dashboard</h1>
            </div>
          </div>
        </div>
      </section>

      <!-- Main content sections -->
      <section class="content">
        <div class="container-fluid">

          <!-- 1. DASHBOARD OVERVIEW SECTION -->
          <div id="section-dashboard" class="spa-section active-section">
            <div class="row">
              <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3 id="stat-companies">-</h3>
                    <p>Công Ty</p>
                  </div>
                  <div class="icon"><i class="fas fa-building"></i></div>
                </div>
              </div>
              <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3 id="stat-branches">-</h3>
                    <p>Chi Nhánh</p>
                  </div>
                  <div class="icon"><i class="fas fa-network-wired"></i></div>
                </div>
              </div>
              <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                  <div class="inner">
                    <h3 id="stat-departments">-</h3>
                    <p>Phòng Ban</p>
                  </div>
                  <div class="icon"><i class="fas fa-users-cog"></i></div>
                </div>
              </div>
              <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                  <div class="inner">
                    <h3 id="stat-users">-</h3>
                    <p>Nhân Viên</p>
                  </div>
                  <div class="icon"><i class="fas fa-users"></i></div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                  <div class="inner">
                    <h3 id="stat-warehouses">-</h3>
                    <p>Kho</p>
                  </div>
                  <div class="icon"><i class="fas fa-warehouse"></i></div>
                </div>
              </div>
              <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                  <div class="inner">
                    <h3 id="stat-skus">-</h3>
                    <p>Sản Phẩm (SKU)</p>
                  </div>
                  <div class="icon"><i class="fas fa-boxes"></i></div>
                </div>
              </div>
            </div>

            <!-- Welcome card -->
            <div class="card card-primary card-outline">
              <div class="card-body">
                <h5 class="card-title font-weight-bold">Chào mừng bạn trở lại hệ thống!</h5>
                <p class="card-text mt-2">
                  Đây là giao diện SPA quản trị tổ chức đồng bộ dữ liệu trực tiếp với hệ thống API của các Module. Bạn có thể sử dụng các chức năng quản lý bên menu trái để Thêm, Sửa, Xóa hay Phân quyền cho nhân sự của mình một cách dễ dàng.
                </p>
              </div>
            </div>
          </div>

          <!-- 2. COMPANIES CRUD SECTION -->
          <div id="section-companies" class="spa-section">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title font-weight-bold">Danh sách Công Ty</h3>
                <div class="card-tools">
                  <button class="btn btn-primary btn-sm" id="btn-add-company">
                    <i class="fas fa-plus mr-1"></i> Thêm Công Ty
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Mã Công Ty</th>
                      <th>Tên Công Ty</th>
                      <th>Phân cấp</th>
                      <th>Mã Số Thuế</th>
                      <th>Email</th>
                      <th>Số Điện Thoại</th>
                      <th>Trạng Thái</th>
                      <th>Hành Động</th>
                    </tr>
                  </thead>
                  <tbody id="table-companies-body"></tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- 3. BRANCHES CRUD SECTION -->
          <div id="section-branches" class="spa-section">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title font-weight-bold">Danh sách Chi Nhánh</h3>
                <div class="card-tools">
                  <button class="btn btn-primary btn-sm" id="btn-add-branch">
                    <i class="fas fa-plus mr-1"></i> Thêm Chi Nhánh
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Thuộc Công Ty</th>
                      <th>Mã Chi Nhánh</th>
                      <th>Tên Chi Nhánh</th>
                      <th>Mã Số Thuế</th>
                      <th>Email</th>
                      <th>Số Điện Thoại</th>
                      <th>Trạng Thái</th>
                      <th>Hành Động</th>
                    </tr>
                  </thead>
                  <tbody id="table-branches-body"></tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- 4. DEPARTMENTS CRUD SECTION -->
          <div id="section-departments" class="spa-section">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title font-weight-bold">Danh sách Phòng Ban</h3>
                <div class="card-tools">
                  <button class="btn btn-primary btn-sm" id="btn-add-department">
                    <i class="fas fa-plus mr-1"></i> Thêm Phòng Ban
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Thuộc Chi Nhánh</th>
                      <th>Phòng Ban Cha</th>
                      <th>Mã Phòng Ban</th>
                      <th>Tên Phòng Ban</th>
                      <th>Mô Tả</th>
                      <th>Trạng Thái</th>
                      <th>Hành Động</th>
                    </tr>
                  </thead>
                  <tbody id="table-departments-body"></tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- 5. POSITIONS CRUD SECTION -->
          <div id="section-positions" class="spa-section">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title font-weight-bold">Danh sách Chức Vụ</h3>
                <div class="card-tools">
                  <button class="btn btn-primary btn-sm" id="btn-add-position">
                    <i class="fas fa-plus mr-1"></i> Thêm Chức Vụ
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Tên Chức Vụ</th>
                      <th>Mô Tả Chức Vụ</th>
                      <th>Hành Động</th>
                    </tr>
                  </thead>
                  <tbody id="table-positions-body"></tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- 6. USERS CRUD SECTION -->
          <div id="section-users" class="spa-section">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title font-weight-bold">Danh sách Nhân Viên</h3>
                <div class="card-tools">
                  <button class="btn btn-primary btn-sm" id="btn-add-user">
                    <i class="fas fa-plus mr-1"></i> Thêm Nhân Viên
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover table-striped mb-0">
                    <thead>
                      <tr>
                        <th>Mã NV</th>
                        <th>Họ Tên</th>
                        <th>Email</th>
                        <th>Số Điện Thoại</th>
                        <th>Công Ty</th>
                        <th>Chi Nhánh</th>
                        <th>Phòng Ban</th>
                        <th>Chức Vụ</th>
                        <th>Vai Trò</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                      </tr>
                    </thead>
                    <tbody id="table-users-body"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- 7. ROLES & PERMISSIONS SECTION -->
          <div id="section-roles" class="spa-section">
            <div class="row">
              <div class="col-md-5">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title font-weight-bold">Danh sách Vai Trò</h3>
                  </div>
                  <div class="card-body">
                    <form id="form-role-add" class="mb-3">
                      <div class="input-group">
                        <input type="text" id="role-name-input" class="form-control form-control-sm" placeholder="Tên vai trò mới..." required>
                        <div class="input-group-append">
                          <button class="btn btn-primary btn-sm" type="submit">Thêm vai trò</button>
                        </div>
                      </div>
                    </form>
                    <table class="table table-bordered table-sm">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Vai Trò</th>
                          <th>Hành Động</th>
                        </tr>
                      </thead>
                      <tbody id="table-roles-body"></tbody>
                    </table>
                  </div>
                </div>
              </div>
              
              <div class="col-md-7">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title font-weight-bold">Gán Quyền Hệ Thống</h3>
                  </div>
                  <div class="card-body">
                    <div id="permission-assign-header" class="alert alert-info text-sm">
                      Chọn một vai trò bên trái để gán danh sách quyền hệ thống.
                    </div>
                    <form id="form-permission-assign" style="display: none;">
                      <input type="hidden" id="assign-role-id">
                      <div id="permission-checkboxes-container" class="mb-3">
                        <!-- Checkboxes load dynamically -->
                      </div>
                      <button class="btn btn-success btn-sm btn-block" type="submit">Cập nhật quyền cho Vai trò</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 8. WAREHOUSES CRUD SECTION -->
          <div id="section-warehouses" class="spa-section">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title font-weight-bold">Danh sách Kho</h3>
                <div class="card-tools">
                  <button class="btn btn-primary btn-sm" id="btn-add-warehouse">
                    <i class="fas fa-plus mr-1"></i> Thêm Kho
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover table-striped mb-0">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Mã Kho</th>
                        <th>Tên Kho</th>
                        <th>Loại</th>
                        <th>Kho Cha</th>
                        <th>Người Phụ Trách</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                      </tr>
                    </thead>
                    <tbody id="table-warehouses-body"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- 9. SKUS CRUD SECTION -->
          <div id="section-skus" class="spa-section">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title font-weight-bold">Danh sách Sản Phẩm (SKU)</h3>
                <div class="card-tools">
                  <button class="btn btn-primary btn-sm" id="btn-add-sku">
                    <i class="fas fa-plus mr-1"></i> Thêm SKU
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover table-striped mb-0">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Mã SKU</th>
                        <th>Tên Sản Phẩm</th>
                        <th>ĐVT</th>
                        <th>Nhóm</th>
                        <th>Serial</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                      </tr>
                    </thead>
                    <tbody id="table-skus-body"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- 10. PRODUCT CATEGORIES SECTION -->
          @include('part.backend.sections.categories')

          <!-- 11. SUPPLIERS SECTION -->
          @include('part.backend.sections.suppliers')

          <!-- 12. WAREHOUSE SCOPES SECTION -->
          @include('part.backend.sections.warehouse_scopes')

          <!-- 13. SAFE STOCK CONFIGS SECTION -->
          @include('part.backend.sections.safe_stock_configs')

          <!-- 14. INBOUND DOCUMENTS SECTION -->
          @include('part.backend.sections.inbound_documents')

          <!-- 15. OUTBOUND DOCUMENTS SECTION -->
          @include('part.backend.sections.outbound_documents')

          <!-- 16. TRANSFER ORDERS SECTION -->
          @include('part.backend.sections.transfer_orders')

          <!-- 17. INVENTORY BALANCES SECTION -->
          @include('part.backend.sections.inventory_balances')

          <!-- 18. STOCK MOVEMENTS SECTION -->
          @include('part.backend.sections.stock_movements')

          <!-- 19. REPORTS XNT SECTION -->
          @include('part.backend.sections.reports_xnt')
        </div>
      </section>
    </div>

    <!-- Footer -->
    @include('part.backend.footer')
  </div>

  <!-- ==================== MODALS ==================== -->

  <!-- Modal Company -->
  <div class="modal fade" id="modal-company" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="form-company">
          <input type="hidden" id="company-id">
          <div class="modal-header">
            <h5 class="modal-title font-weight-bold" id="modal-company-title">Thêm Mới Công Ty</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Tên Công Ty</label>
              <input type="text" id="company-name" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Mã Công Ty (Unique)</label>
              <input type="text" id="company-code" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Mã Số Thuế (Unique)</label>
              <input type="text" id="company-tax-code" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Số Điện Thoại</label>
              <input type="text" id="company-phone" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Email (Unique)</label>
              <input type="email" id="company-email" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Địa Chỉ</label>
              <input type="text" id="company-address" class="form-control form-control-sm">
            </div>
            <div class="form-group">
              <label>Trạng Thái</label>
              <select id="company-status" class="form-control form-control-sm">
                <option value="1">Kích Hoạt (Active)</option>
                <option value="0">Tạm Ngưng (Inactive)</option>
              </select>
            </div>
            <div class="form-group">
              <label>Công Ty Mẹ (Nếu có)</label>
              <select id="company-parent-id" class="form-control form-control-sm">
                <option value="">Không có - Là Công Ty Mẹ</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary btn-sm">Lưu Dữ Liệu</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Branch -->
  <div class="modal fade" id="modal-branch" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="form-branch">
          <input type="hidden" id="branch-id">
          <div class="modal-header">
            <h5 class="modal-title font-weight-bold" id="modal-branch-title">Thêm Mới Chi Nhánh</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Thuộc Công Ty</label>
              <select id="branch-company-id" class="form-control form-control-sm" required></select>
            </div>
            <div class="form-group">
              <label>Tên Chi Nhánh</label>
              <input type="text" id="branch-name" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Mã Chi Nhánh (Unique)</label>
              <input type="text" id="branch-code" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Mã Số Thuế (Chi Nhánh)</label>
              <input type="text" id="branch-tax-code" class="form-control form-control-sm">
            </div>
            <div class="form-group">
              <label>Số Điện Thoại</label>
              <input type="text" id="branch-phone" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Email (Unique)</label>
              <input type="email" id="branch-email" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Địa Chỉ</label>
              <input type="text" id="branch-address" class="form-control form-control-sm">
            </div>
            <div class="form-group">
              <label>Trạng Thái</label>
              <select id="branch-status" class="form-control form-control-sm">
                <option value="1">Kích Hoạt</option>
                <option value="0">Tạm Ngưng</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary btn-sm">Lưu Dữ Liệu</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Department -->
  <div class="modal fade" id="modal-department" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="form-department">
          <input type="hidden" id="department-id">
          <div class="modal-header">
            <h5 class="modal-title font-weight-bold" id="modal-department-title">Thêm Mới Phòng Ban</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Thuộc Chi Nhánh</label>
              <select id="department-branch-id" class="form-control form-control-sm" required></select>
            </div>
            <div class="form-group">
              <label>Tên Phòng Ban</label>
              <input type="text" id="department-name" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Mã Phòng Ban (Unique)</label>
              <input type="text" id="department-code" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Mô Tả Phòng Ban</label>
              <textarea id="department-description" class="form-control form-control-sm" rows="3"></textarea>
            </div>
            <div class="form-group">
              <label>Trạng Thái</label>
              <select id="department-status" class="form-control form-control-sm">
                <option value="1">Kích Hoạt</option>
                <option value="0">Tạm Ngưng</option>
              </select>
            </div>
            <div class="form-group">
              <label>Phòng Ban Cấp Trên (Nếu có)</label>
              <select id="department-parent-id" class="form-control form-control-sm">
                <option value="">Không có - Phòng ban cấp cao nhất</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary btn-sm">Lưu Dữ Liệu</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Position -->
  <div class="modal fade" id="modal-position" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="form-position">
          <input type="hidden" id="position-id">
          <div class="modal-header">
            <h5 class="modal-title font-weight-bold" id="modal-position-title">Thêm Mới Chức Vụ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Tên Chức Vụ (Unique)</label>
              <input type="text" id="position-name" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Mô Tả Chức Vụ</label>
              <textarea id="position-description" class="form-control form-control-sm" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary btn-sm">Lưu Dữ Liệu</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal User -->
  <div class="modal fade" id="modal-user" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form id="form-user">
          <input type="hidden" id="user-id">
          <div class="modal-header">
            <h5 class="modal-title font-weight-bold" id="modal-user-title">Thêm Mới Nhân Viên</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Mã Nhân Viên (Unique)</label>
                  <input type="text" id="user-code" class="form-control form-control-sm" required>
                </div>
                <div class="form-group">
                  <label>Họ Và Tên</label>
                  <input type="text" id="user-name" class="form-control form-control-sm" required>
                </div>
                <div class="form-group">
                  <label>Địa Chỉ Email (Unique)</label>
                  <input type="email" id="user-email" class="form-control form-control-sm" required>
                </div>
                <div class="form-group">
                  <label>Số Điện Thoại</label>
                  <input type="text" id="user-phone" class="form-control form-control-sm" required>
                </div>
                <div class="form-group">
                  <label>Mật Khẩu</label>
                  <input type="password" id="user-password" class="form-control form-control-sm" placeholder="Điền mật khẩu...">
                  <small class="text-muted" id="user-password-hint" style="display: none;">Để trống nếu không muốn đổi mật khẩu</small>
                </div>
                <div class="form-group">
                  <label>Giới Tính</label>
                  <select id="user-gender" class="form-control form-control-sm">
                    <option value="0">Nam</option>
                    <option value="1">Nữ</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Ngày Sinh</label>
                  <input type="date" id="user-birthday" class="form-control form-control-sm" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Công Ty Trực Thuộc</label>
                  <select id="user-company-id" class="form-control form-control-sm" required></select>
                </div>
                <div class="form-group">
                  <label>Chi Nhánh</label>
                  <select id="user-branch-id" class="form-control form-control-sm" required></select>
                </div>
                <div class="form-group">
                  <label>Phòng Ban</label>
                  <select id="user-department-id" class="form-control form-control-sm" required></select>
                </div>
                <div class="form-group">
                  <label>Chức Vụ</label>
                  <select id="user-position-id" class="form-control form-control-sm" required></select>
                </div>
                <div class="form-group">
                  <label>Vai Trò Hệ Thống</label>
                  <select id="user-role-id" class="form-control form-control-sm" required></select>
                </div>
                <div class="form-group">
                  <label>Địa Chỉ Thường Trú</label>
                  <input type="text" id="user-address" class="form-control form-control-sm">
                </div>
                <div class="form-group">
                  <label>Trạng Thái</label>
                  <select id="user-status" class="form-control form-control-sm">
                    <option value="1">Hoạt Động</option>
                    <option value="0">Ngưng Hoạt Động</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary btn-sm">Lưu Nhân Viên</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Warehouse -->
  <div class="modal fade" id="modal-warehouse" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="form-warehouse">
          <input type="hidden" id="warehouse-id">
          <div class="modal-header">
            <h5 class="modal-title font-weight-bold" id="modal-warehouse-title">Thêm Mới Kho</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Thuộc Công Ty</label>
              <select id="warehouse-company-id" class="form-control form-control-sm" required></select>
            </div>
            <div class="form-group">
              <label>Chi Nhánh (Nếu có)</label>
              <select id="warehouse-branch-id" class="form-control form-control-sm"></select>
            </div>
            <div class="form-group">
              <label>Mã Kho (Unique, không sửa được sau khi tạo)</label>
              <input type="text" id="warehouse-code" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Tên Kho</label>
              <input type="text" id="warehouse-name" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Loại Kho</label>
              <select id="warehouse-type" class="form-control form-control-sm">
                <option value="1">Kho Tổng</option>
                <option value="2">Kho Con</option>
              </select>
            </div>
            <div class="form-group">
              <label>Kho Cha (Nếu là kho con)</label>
              <select id="warehouse-parent-id" class="form-control form-control-sm">
                <option value="">-- Không có (Kho tổng) --</option>
              </select>
            </div>
            <div class="form-group">
              <label>Người Phụ Trách</label>
              <select id="warehouse-manager-id" class="form-control form-control-sm">
                <option value="">-- Chưa phân công --</option>
              </select>
            </div>
            <div class="form-group">
              <label>Địa Chỉ</label>
              <input type="text" id="warehouse-address" class="form-control form-control-sm">
            </div>
            <div class="form-group">
              <label>Trạng Thái</label>
              <select id="warehouse-status" class="form-control form-control-sm">
                <option value="1">Hoạt Động</option>
                <option value="0">Ngừng Hoạt Động</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary btn-sm">Lưu Dữ Liệu</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal SKU -->
  <div class="modal fade" id="modal-sku" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="form-sku">
          <input type="hidden" id="sku-id">
          <div class="modal-header">
            <h5 class="modal-title font-weight-bold" id="modal-sku-title">Thêm Mới SKU</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Mã SKU (Unique)</label>
              <input type="text" id="sku-code" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Tên Sản Phẩm</label>
              <input type="text" id="sku-name" class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
              <label>Đơn Vị Tính</label>
              <input type="text" id="sku-unit" class="form-control form-control-sm" placeholder="cái, hộp, kg, lít..." required>
            </div>
            <div class="form-group">
              <label>Nhóm Sản Phẩm</label>
              <input type="text" id="sku-category" class="form-control form-control-sm">
            </div>
            <div class="form-group">
              <label>Quản Lý Serial</label>
              <select id="sku-has-serial" class="form-control form-control-sm">
                <option value="0">Không</option>
                <option value="1">Có</option>
              </select>
            </div>
            <div class="form-group">
              <label>Trạng Thái</label>
              <select id="sku-status" class="form-control form-control-sm">
                <option value="1">Hoạt Động</option>
                <option value="0">Ngừng Hoạt Động</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary btn-sm">Lưu Dữ Liệu</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('backend/js/adminlte.js') }}"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- ==================== SPA MAIN APPLICATION LOGIC ==================== -->
  <script>
    $(document).ready(function() {
      // 1. GLOBAL AJAX HEADERS ENFORCEMENT
      function configureAjax() {
        const token = localStorage.getItem('jwt_token');
        $.ajaxSetup({
          headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          statusCode: {
            401: function() {
              localStorage.removeItem('jwt_token');
              checkAuthState();
            },
            403: function(xhr) {
              const err = xhr.responseJSON;
              showAlert('error', 'Bị từ chối', err && err.message ? err.message : 'Bạn không có quyền thực hiện hành động này.');
            }
          }
        });
      }

      // Check login state
      function checkAuthState() {
        const token = localStorage.getItem('jwt_token');
        if (!token) {
          $('#app-login').css('display', 'flex');
          $('#app-wrapper').css('display', 'none');
        } else {
          configureAjax();
          $('#app-login').css('display', 'none');
          $('#app-wrapper').css('display', 'block');
          loadUserProfile();
          switchTab('dashboard');
        }
      }

      checkAuthState();

      // Helper display alert
      function showAlert(type, title, text) {
        Swal.fire({
          icon: type,
          title: title,
          text: text,
          showConfirmButton: false,
          timer: 1500
        });
      }

      // 2. AUTHENTICATION HANDLERS
      $('#form-login').on('submit', function(e) {
        e.preventDefault();
        const email = $('#login-email').val();
        const password = $('#login-password').val();

        $.ajax({
          url: '/api/v1/auth/login',
          type: 'POST',
          contentType: 'application/json',
          headers: {
            'Accept': 'application/json'
          },
          data: JSON.stringify({ email, password }),
          success: function(response) {
            if (response.status === 'success') {
              localStorage.setItem('jwt_token', response.data.access_token);
              showAlert('success', 'Thành công', 'Đăng nhập hệ thống thành công');
              checkAuthState();
            }
          },
          error: function(xhr) {
            const err = xhr.responseJSON;
            showAlert('error', 'Thất bại', err ? err.message : 'Tài khoản hoặc mật khẩu không chính xác');
          }
        });
      });

      $('#btn-logout').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Bạn chắc chắn muốn Đăng xuất?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Đăng xuất!',
          cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '/api/v1/auth/logout',
              type: 'POST',
              complete: function() {
                localStorage.removeItem('jwt_token');
                checkAuthState();
              }
            });
          }
        });
      });

      function loadUserProfile() {
        $.ajax({
          url: '/api/v1/auth/me',
          type: 'GET',
          success: function(response) {
            if (response.status === 'success') {
              const u = response.data;
              $('#sidebar-user-name').text(u.name);
              $('#sidebar-user-role').text(u.role ? u.role.name : 'Nhân Viên');

              // Gather permissions list
              const permissions = [];
              if (u.role && u.role.permissions) {
                u.role.permissions.forEach(function(p) {
                  permissions.push(p.name);
                });
              }

              const isSuper = u.role && u.role.name === 'Super Admin';

              // Enforce client-side sidebar link visibility
              if (isSuper || permissions.includes('quan_ly_nhan_su')) {
                $('a[data-tab="companies"]').parent().show();
                $('a[data-tab="branches"]').parent().show();
                $('a[data-tab="departments"]').parent().show();
                $('a[data-tab="positions"]').parent().show();
                $('a[data-tab="users"]').parent().show();
              } else {
                $('a[data-tab="companies"]').parent().hide();
                $('a[data-tab="branches"]').parent().hide();
                $('a[data-tab="departments"]').parent().hide();
                $('a[data-tab="positions"]').parent().hide();
                $('a[data-tab="users"]').parent().hide();
              }

              if (isSuper || permissions.includes('quan_ly_he_thong')) {
                $('a[data-tab="roles"]').parent().show();
              } else {
                $('a[data-tab="roles"]').parent().hide();
              }
            }
          },
          error: function() {
            localStorage.removeItem('jwt_token');
            checkAuthState();
          }
        });
      }

      // 3. SPA DYNAMIC TAB SWITCHING
      $('.nav-tab').on('click', function(e) {
        e.preventDefault();
        $('.nav-tab').removeClass('active');
        $(this).addClass('active');

        const tab = $(this).data('tab');
        switchTab(tab);
      });

      function switchTab(tab) {
        $('.spa-section').removeClass('active-section');
        $(`#section-${tab}`).addClass('active-section');

        // Dynamic page title
        const titles = {
          'dashboard': 'Tổng Quan Dashboard',
          'companies': 'Quản Lý Công Ty',
          'branches': 'Quản Lý Chi Nhánh',
          'departments': 'Quản Lý Phòng Ban',
          'positions': 'Quản Lý Chức Vụ',
          'users': 'Quản Lý Nhân Viên',
          'warehouses': 'Quản Lý Kho',
          'skus': 'Quản Lý Sản Phẩm (SKU)',
          'categories': 'Quản Lý Nhóm Sản Phẩm',
          'suppliers': 'Quản Lý Nhà Cung Cấp',
          'warehouse-scopes': 'Phân Quyền Kho Nhân Viên',
          'safe-stock-configs': 'Định Mức Tồn Kho An Toàn',
          'inbound-documents': 'Quản Lý Nhập Kho',
          'outbound-documents': 'Quản Lý Xuất Kho',
          'transfer-orders': 'Điều Chuyển Kho Nội Bộ',
          'inventory-balances': 'Số Dư Tồn Kho',
          'stock-movements': 'Lịch Sử Biến Động (Thẻ Kho)',
          'reports-xnt': 'Báo Cáo Xuất - Nhập - Tồn',
          'roles': 'Vai Trò & Quyền Hạn'
        };
        $('#page-title').text(titles[tab] || 'ERB System');

        // Trigger dynamic loading functions
        if (tab === 'dashboard') loadDashboardStats();
        if (tab === 'companies') loadCompaniesList();
        if (tab === 'branches') loadBranchesList();
        if (tab === 'departments') loadDepartmentsList();
        if (tab === 'positions') loadPositionsList();
        if (tab === 'users') loadUsersList();
        if (tab === 'warehouses') loadWarehousesList();
        if (tab === 'skus') loadSkusList();
        if (tab === 'categories') loadCategoriesList();
        if (tab === 'suppliers') loadSuppliersList();
        if (tab === 'warehouse-scopes') loadWarehouseScopesList();
        if (tab === 'safe-stock-configs') loadSafeStockConfigsList();
        if (tab === 'inbound-documents') loadInboundDocumentsList();
        if (tab === 'outbound-documents') loadOutboundDocumentsList();
        if (tab === 'transfer-orders') loadTransferOrdersList();
        if (tab === 'inventory-balances') loadInventoryBalancesList();
        if (tab === 'stock-movements') loadStockMovementsList();
        if (tab === 'reports-xnt') loadReportsXntList();
        if (tab === 'roles') loadRolesAndPermissions();
      }

      // 4. BUSINESS LOGIC: DASHBOARD OVERVIEW
      function loadDashboardStats() {
        $.ajax({
          url: '/api/v1/companies',
          type: 'GET',
          success: function(res) { $('#stat-companies').text(res.data.total); }
        });
        $.ajax({
          url: '/api/v1/branches',
          type: 'GET',
          success: function(res) { $('#stat-branches').text(res.data.total); }
        });
        $.ajax({
          url: '/api/v1/departments',
          type: 'GET',
          success: function(res) { $('#stat-departments').text(res.data.total); }
        });
        $.ajax({
          url: '/api/v1/users',
          type: 'GET',
          success: function(res) { $('#stat-users').text(res.data.total); }
        });
        $.ajax({
          url: '/api/v1/warehouses',
          type: 'GET',
          success: function(res) { $('#stat-warehouses').text(res.data.total); }
        });
        $.ajax({
          url: '/api/v1/skus',
          type: 'GET',
          success: function(res) { $('#stat-skus').text(res.data.total); }
        });
      }

      // 5. BUSINESS LOGIC: COMPANIES CRUD
      function loadCompaniesList() {
        $.ajax({
          url: '/api/v1/companies?limit=100',
          type: 'GET',
          success: function(response) {
            const body = $('#table-companies-body');
            body.empty();
            const parentSelect = $('#company-parent-id');
            parentSelect.empty().append('<option value="">Không có - Là Công Ty Mẹ</option>');

            response.data.data.forEach(function(c) {
              parentSelect.append(`<option value="${c.id}">${c.name}</option>`);
              const statusBadge = c.status === 1 ? '<span class="badge badge-success">Kích hoạt</span>' : '<span class="badge badge-danger">Tạm ngưng</span>';
              const parentText = c.parent ? `<span class="badge badge-info">Con của: ${c.parent.name}</span>` : '<span class="badge badge-secondary">Công Ty Mẹ</span>';
              
              body.append(`
                <tr>
                  <td>${c.id}</td>
                  <td><b>${c.code}</b></td>
                  <td>${c.name}</td>
                  <td>${parentText}</td>
                  <td>${c.tax_code}</td>
                  <td>${c.email}</td>
                  <td>${c.phone}</td>
                  <td>${statusBadge}</td>
                  <td>
                    <button class="btn btn-info btn-xs btn-edit-company" data-id="${c.id}"><i class="fas fa-edit"></i> Sửa</button>
                    <button class="btn btn-danger btn-xs btn-delete-company" data-id="${c.id}"><i class="fas fa-trash"></i> Xóa</button>
                  </td>
                </tr>
              `);
            });
          }
        });
      }

      $('#btn-add-company').on('click', function() {
        $('#form-company')[0].reset();
        $('#company-id').val('');
        $('#modal-company-title').text('Thêm Mới Công Ty');
        $('#modal-company').modal('show');
      });

      $(document).on('click', '.btn-edit-company', function() {
        const id = $(this).data('id');
        $.ajax({
          url: '/api/v1/companies/' + id,
          type: 'GET',
          success: function(res) {
            const c = res.data;
            $('#company-id').val(c.id);
            $('#company-name').val(c.name);
            $('#company-code').val(c.code);
            $('#company-tax-code').val(c.tax_code);
            $('#company-phone').val(c.phone);
            $('#company-email').val(c.email);
            $('#company-address').val(c.address);
            $('#company-status').val(c.status);
            $('#company-parent-id').val(c.parent_id || '');

            $('#modal-company-title').text('Chỉnh Sửa Công Ty');
            $('#modal-company').modal('show');
          }
        });
      });

      $('#form-company').on('submit', function(e) {
        e.preventDefault();
        const id = $('#company-id').val();
        const url = id ? '/api/v1/companies/' + id : '/api/v1/companies';
        const method = id ? 'PUT' : 'POST';

        const data = {
          name: $('#company-name').val(),
          code: $('#company-code').val(),
          tax_code: $('#company-tax-code').val(),
          phone: $('#company-phone').val(),
          email: $('#company-email').val(),
          address: $('#company-address').val(),
          status: parseInt($('#company-status').val()),
          parent_id: $('#company-parent-id').val() ? parseInt($('#company-parent-id').val()) : null
        };

        $.ajax({
          url: url,
          type: method,
          data: JSON.stringify(data),
          success: function(response) {
            $('#modal-company').modal('hide');
            showAlert('success', 'Thành công', response.message);
            loadCompaniesList();
          },
          error: function(xhr) {
            showAlert('error', 'Thất bại', 'Vui lòng kiểm tra lại thông tin (Mã, Tax code hoặc Email có thể đã tồn tại)');
          }
        });
      });

      $(document).on('click', '.btn-delete-company', function() {
        const id = $(this).data('id');
        Swal.fire({
          title: 'Xác nhận xóa công ty này?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: 'Xóa!',
          cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '/api/v1/companies/' + id,
              type: 'DELETE',
              success: function(res) {
                showAlert('success', 'Thành công', res.message);
                loadCompaniesList();
              }
            });
          }
        });
      });

      // 6. BUSINESS LOGIC: BRANCHES CRUD
      function loadBranchesList() {
        // Load Company selection list first
        $.ajax({
          url: '/api/v1/companies?limit=100',
          type: 'GET',
          success: function(res) {
            const select = $('#branch-company-id');
            select.empty();
            res.data.data.forEach(function(c) {
              select.append(`<option value="${c.id}">${c.name}</option>`);
            });
          }
        });

        $.ajax({
          url: '/api/v1/branches?limit=100',
          type: 'GET',
          success: function(response) {
            const body = $('#table-branches-body');
            body.empty();

            response.data.data.forEach(function(b) {
              const statusBadge = b.status === 1 ? '<span class="badge badge-success">Kích hoạt</span>' : '<span class="badge badge-danger">Tạm ngưng</span>';
              const taxCode = b.tax_code ? b.tax_code : '<span class="text-muted">Không có</span>';
              body.append(`
                <tr>
                  <td>${b.id}</td>
                  <td>${b.company ? b.company.name : 'N/A'}</td>
                  <td><b>${b.code}</b></td>
                  <td>${b.name}</td>
                  <td>${taxCode}</td>
                  <td>${b.email}</td>
                  <td>${b.phone}</td>
                  <td>${statusBadge}</td>
                  <td>
                    <button class="btn btn-info btn-xs btn-edit-branch" data-id="${b.id}"><i class="fas fa-edit"></i> Sửa</button>
                    <button class="btn btn-danger btn-xs btn-delete-branch" data-id="${b.id}"><i class="fas fa-trash"></i> Xóa</button>
                  </td>
                </tr>
              `);
            });
          }
        });
      }

      $('#btn-add-branch').on('click', function() {
        $('#form-branch')[0].reset();
        $('#branch-id').val('');
        $('#modal-branch-title').text('Thêm Mới Chi Nhánh');
        $('#modal-branch').modal('show');
      });

      $(document).on('click', '.btn-edit-branch', function() {
        const id = $(this).data('id');
        $.ajax({
          url: '/api/v1/branches/' + id,
          type: 'GET',
          success: function(res) {
            const b = res.data;
            $('#branch-id').val(b.id);
            $('#branch-company-id').val(b.company_id);
            $('#branch-name').val(b.name);
            $('#branch-code').val(b.code);
            $('#branch-tax-code').val(b.tax_code || '');
            $('#branch-phone').val(b.phone);
            $('#branch-email').val(b.email);
            $('#branch-address').val(b.address);
            $('#branch-status').val(b.status);

            $('#modal-branch-title').text('Chỉnh Sửa Chi Nhánh');
            $('#modal-branch').modal('show');
          }
        });
      });

      $('#form-branch').on('submit', function(e) {
        e.preventDefault();
        const id = $('#branch-id').val();
        const url = id ? '/api/v1/branches/' + id : '/api/v1/branches';
        const method = id ? 'PUT' : 'POST';

        const data = {
          company_id: parseInt($('#branch-company-id').val()),
          name: $('#branch-name').val(),
          code: $('#branch-code').val(),
          tax_code: $('#branch-tax-code').val(),
          phone: $('#branch-phone').val(),
          email: $('#branch-email').val(),
          address: $('#branch-address').val(),
          status: parseInt($('#branch-status').val())
        };

        $.ajax({
          url: url,
          type: method,
          data: JSON.stringify(data),
          success: function(response) {
            $('#modal-branch').modal('hide');
            showAlert('success', 'Thành công', response.message);
            loadBranchesList();
          },
          error: function(xhr) {
            const err = xhr.responseJSON;
            showAlert('error', 'Thất bại', err && err.message ? err.message : 'Có lỗi xảy ra (Mã, MST hoặc Email chi nhánh đã tồn tại)');
          }
        });
      });

      $(document).on('click', '.btn-delete-branch', function() {
        const id = $(this).data('id');
        Swal.fire({
          title: 'Xác nhận xóa chi nhánh này?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: 'Xóa!',
          cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '/api/v1/branches/' + id,
              type: 'DELETE',
              success: function(res) {
                showAlert('success', 'Thành công', res.message);
                loadBranchesList();
              }
            });
          }
        });
      });

      // 7. BUSINESS LOGIC: DEPARTMENTS CRUD
      function loadDepartmentsList() {
        // Load branch selection list first
        $.ajax({
          url: '/api/v1/branches?limit=100',
          type: 'GET',
          success: function(res) {
            const select = $('#department-branch-id');
            select.empty();
            res.data.data.forEach(function(b) {
              select.append(`<option value="${b.id}">${b.name}</option>`);
            });
          }
        });

        // Load parents list
        $.ajax({
          url: '/api/v1/departments?limit=100',
          type: 'GET',
          success: function(res) {
            const select = $('#department-parent-id');
            select.empty().append('<option value="">Không có - Phòng ban cấp cao nhất</option>');
            res.data.data.forEach(function(d) {
              select.append(`<option value="${d.id}">${d.name}</option>`);
            });
          }
        });

        $.ajax({
          url: '/api/v1/departments?limit=100',
          type: 'GET',
          success: function(response) {
            const body = $('#table-departments-body');
            body.empty();

            response.data.data.forEach(function(d) {
              const statusBadge = d.status === 1 ? '<span class="badge badge-success">Kích hoạt</span>' : '<span class="badge badge-danger">Tạm ngưng</span>';
              body.append(`
                <tr>
                  <td>${d.id}</td>
                  <td>${d.branch ? d.branch.name : 'N/A'}</td>
                  <td>${d.parent ? d.parent.name : '<i>Không có</i>'}</td>
                  <td><b>${d.code}</b></td>
                  <td>${d.name}</td>
                  <td>${d.description || ''}</td>
                  <td>${statusBadge}</td>
                  <td>
                    <button class="btn btn-info btn-xs btn-edit-department" data-id="${d.id}"><i class="fas fa-edit"></i> Sửa</button>
                    <button class="btn btn-danger btn-xs btn-delete-department" data-id="${d.id}"><i class="fas fa-trash"></i> Xóa</button>
                  </td>
                </tr>
              `);
            });
          }
        });
      }

      $('#btn-add-department').on('click', function() {
        $('#form-department')[0].reset();
        $('#department-id').val('');
        $('#modal-department-title').text('Thêm Mới Phòng Ban');
        $('#modal-department').modal('show');
      });

      $(document).on('click', '.btn-edit-department', function() {
        const id = $(this).data('id');
        $.ajax({
          url: '/api/v1/departments/' + id,
          type: 'GET',
          success: function(res) {
            const d = res.data;
            $('#department-id').val(d.id);
            $('#department-branch-id').val(d.branch_id);
            $('#department-name').val(d.name);
            $('#department-code').val(d.code);
            $('#department-description').val(d.description);
            $('#department-status').val(d.status);
            $('#department-parent-id').val(d.parent_id || '');

            $('#modal-department-title').text('Chỉnh Sửa Phòng Ban');
            $('#modal-department').modal('show');
          }
        });
      });

      $('#form-department').on('submit', function(e) {
        e.preventDefault();
        const id = $('#department-id').val();
        const url = id ? '/api/v1/departments/' + id : '/api/v1/departments';
        const method = id ? 'PUT' : 'POST';

        const data = {
          branch_id: parseInt($('#department-branch-id').val()),
          name: $('#department-name').val(),
          code: $('#department-code').val(),
          description: $('#department-description').val(),
          status: parseInt($('#department-status').val()),
          parent_id: $('#department-parent-id').val() ? parseInt($('#department-parent-id').val()) : null
        };

        $.ajax({
          url: url,
          type: method,
          data: JSON.stringify(data),
          success: function(response) {
            $('#modal-department').modal('hide');
            showAlert('success', 'Thành công', response.message);
            loadDepartmentsList();
          },
          error: function() {
            showAlert('error', 'Thất bại', 'Có lỗi xảy ra (Mã phòng ban đã tồn tại)');
          }
        });
      });

      $(document).on('click', '.btn-delete-department', function() {
        const id = $(this).data('id');
        Swal.fire({
          title: 'Xác nhận xóa phòng ban này?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: 'Xóa!',
          cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '/api/v1/departments/' + id,
              type: 'DELETE',
              success: function(res) {
                showAlert('success', 'Thành công', res.message);
                loadDepartmentsList();
              }
            });
          }
        });
      });

      // 8. BUSINESS LOGIC: POSITIONS CRUD
      function loadPositionsList() {
        $.ajax({
          url: '/api/v1/positions?limit=100',
          type: 'GET',
          success: function(response) {
            const body = $('#table-positions-body');
            body.empty();

            response.data.data.forEach(function(p) {
              body.append(`
                <tr>
                  <td>${p.id}</td>
                  <td><b>${p.name}</b></td>
                  <td>${p.description || ''}</td>
                  <td>
                    <button class="btn btn-info btn-xs btn-edit-position" data-id="${p.id}"><i class="fas fa-edit"></i> Sửa</button>
                    <button class="btn btn-danger btn-xs btn-delete-position" data-id="${p.id}"><i class="fas fa-trash"></i> Xóa</button>
                  </td>
                </tr>
              `);
            });
          }
        });
      }

      $('#btn-add-position').on('click', function() {
        $('#form-position')[0].reset();
        $('#position-id').val('');
        $('#modal-position-title').text('Thêm Mới Chức Vụ');
        $('#modal-position').modal('show');
      });

      $(document).on('click', '.btn-edit-position', function() {
        const id = $(this).data('id');
        $.ajax({
          url: '/api/v1/positions/' + id,
          type: 'GET',
          success: function(res) {
            const p = res.data;
            $('#position-id').val(p.id);
            $('#position-name').val(p.name);
            $('#position-description').val(p.description);

            $('#modal-position-title').text('Chỉnh Sửa Chức Vụ');
            $('#modal-position').modal('show');
          }
        });
      });

      $('#form-position').on('submit', function(e) {
        e.preventDefault();
        const id = $('#position-id').val();
        const url = id ? '/api/v1/positions/' + id : '/api/v1/positions';
        const method = id ? 'PUT' : 'POST';

        const data = {
          name: $('#position-name').val(),
          description: $('#position-description').val()
        };

        $.ajax({
          url: url,
          type: method,
          data: JSON.stringify(data),
          success: function(response) {
            $('#modal-position').modal('hide');
            showAlert('success', 'Thành công', response.message);
            loadPositionsList();
          },
          error: function() {
            showAlert('error', 'Thất bại', 'Có lỗi xảy ra (Tên chức vụ đã tồn tại)');
          }
        });
      });

      $(document).on('click', '.btn-delete-position', function() {
        const id = $(this).data('id');
        Swal.fire({
          title: 'Xác nhận xóa chức vụ này?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: 'Xóa!',
          cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '/api/v1/positions/' + id,
              type: 'DELETE',
              success: function(res) {
                showAlert('success', 'Thành công', res.message);
                loadPositionsList();
              }
            });
          }
        });
      });

      // 9. BUSINESS LOGIC: USERS CRUD
      function loadUsersList() {
        // Load selector data
        $.ajax({
          url: '/api/v1/companies?limit=100',
          type: 'GET',
          success: function(res) {
            const select = $('#user-company-id');
            select.empty();
            res.data.data.forEach(function(item) { select.append(`<option value="${item.id}">${item.name}</option>`); });
          }
        });
        $.ajax({
          url: '/api/v1/branches?limit=100',
          type: 'GET',
          success: function(res) {
            const select = $('#user-branch-id');
            select.empty();
            res.data.data.forEach(function(item) { select.append(`<option value="${item.id}">${item.name}</option>`); });
          }
        });
        $.ajax({
          url: '/api/v1/departments?limit=100',
          type: 'GET',
          success: function(res) {
            const select = $('#user-department-id');
            select.empty();
            res.data.data.forEach(function(item) { select.append(`<option value="${item.id}">${item.name}</option>`); });
          }
        });
        $.ajax({
          url: '/api/v1/positions?limit=100',
          type: 'GET',
          success: function(res) {
            const select = $('#user-position-id');
            select.empty();
            res.data.data.forEach(function(item) { select.append(`<option value="${item.id}">${item.name}</option>`); });
          }
        });
        $.ajax({
          url: '/api/v1/roles',
          type: 'GET',
          success: function(res) {
            const select = $('#user-role-id');
            select.empty();
            res.data.forEach(function(item) { select.append(`<option value="${item.id}">${item.name}</option>`); });
          }
        });

        // Load users table
        $.ajax({
          url: '/api/v1/users?limit=100',
          type: 'GET',
          success: function(response) {
            const body = $('#table-users-body');
            body.empty();

            response.data.data.forEach(function(u) {
              const statusBadge = u.status === 1 ? '<span class="badge badge-success">Hoạt động</span>' : '<span class="badge badge-danger">Tạm khóa</span>';
              body.append(`
                <tr>
                  <td><b>${u.code}</b></td>
                  <td>${u.name}</td>
                  <td>${u.email}</td>
                  <td>${u.phone}</td>
                  <td>${u.company ? u.company.name : 'N/A'}</td>
                  <td>${u.branch ? u.branch.name : 'N/A'}</td>
                  <td>${u.department ? u.department.name : 'N/A'}</td>
                  <td>${u.position ? u.position.name : 'N/A'}</td>
                  <td>${u.role ? u.role.name : 'N/A'}</td>
                  <td>${statusBadge}</td>
                  <td>
                    <button class="btn btn-info btn-xs btn-edit-user" data-id="${u.id}"><i class="fas fa-edit"></i> Sửa</button>
                    <button class="btn btn-danger btn-xs btn-delete-user" data-id="${u.id}"><i class="fas fa-trash"></i> Xóa</button>
                  </td>
                </tr>
              `);
            });
          }
        });
      }

      $('#btn-add-user').on('click', function() {
        $('#form-user')[0].reset();
        $('#user-id').val('');
        $('#user-password-hint').hide();
        $('#user-password').attr('required', true);
        $('#modal-user-title').text('Thêm Mới Nhân Viên');
        $('#modal-user').modal('show');
      });

      $(document).on('click', '.btn-edit-user', function() {
        const id = $(this).data('id');
        $.ajax({
          url: '/api/v1/users/' + id,
          type: 'GET',
          success: function(res) {
            const u = res.data;
            $('#user-id').val(u.id);
            $('#user-code').val(u.code);
            $('#user-name').val(u.name);
            $('#user-email').val(u.email);
            $('#user-phone').val(u.phone);
            $('#user-password').val('').removeAttr('required');
            $('#user-password-hint').show();
            $('#user-gender').val(u.gender);
            
            // Format birthday to YYYY-MM-DD
            if (u.birthday) {
              const bday = new Date(u.birthday).toISOString().split('T')[0];
              $('#user-birthday').val(bday);
            }
            
            $('#user-company-id').val(u.company_id);
            $('#user-branch-id').val(u.branch_id);
            $('#user-department-id').val(u.department_id);
            $('#user-position-id').val(u.position_id);
            $('#user-role-id').val(u.role_id);
            $('#user-address').val(u.address);
            $('#user-status').val(u.status);

            $('#modal-user-title').text('Chỉnh Sửa Nhân Viên');
            $('#modal-user').modal('show');
          }
        });
      });

      $('#form-user').on('submit', function(e) {
        e.preventDefault();
        const id = $('#user-id').val();
        const url = id ? '/api/v1/users/' + id : '/api/v1/users';
        const method = id ? 'PUT' : 'POST';

        const data = {
          company_id: parseInt($('#user-company-id').val()),
          branch_id: parseInt($('#user-branch-id').val()),
          department_id: parseInt($('#user-department-id').val()),
          position_id: parseInt($('#user-position-id').val()),
          role_id: parseInt($('#user-role-id').val()),
          code: $('#user-code').val(),
          name: $('#user-name').val(),
          email: $('#user-email').val(),
          phone: $('#user-phone').val(),
          gender: parseInt($('#user-gender').val()),
          birthday: $('#user-birthday').val(),
          address: $('#user-address').val(),
          status: parseInt($('#user-status').val())
        };

        const pass = $('#user-password').val();
        if (pass && pass !== '') {
          data.password = pass;
        }

        $.ajax({
          url: url,
          type: method,
          data: JSON.stringify(data),
          success: function(response) {
            $('#modal-user').modal('hide');
            showAlert('success', 'Thành công', response.message);
            loadUsersList();
          },
          error: function() {
            showAlert('error', 'Thất bại', 'Vui lòng kiểm tra lại thông tin (Mã NV hoặc Email nhân viên có thể đã tồn tại)');
          }
        });
      });

      $(document).on('click', '.btn-delete-user', function() {
        const id = $(this).data('id');
        Swal.fire({
          title: 'Xác nhận xóa tài khoản nhân viên này?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: 'Xóa!',
          cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '/api/v1/users/' + id,
              type: 'DELETE',
              success: function(res) {
                showAlert('success', 'Thành công', res.message);
                loadUsersList();
              }
            });
          }
        });
      });

      // 10. BUSINESS LOGIC: ROLES & PERMISSIONS
      function loadRolesAndPermissions() {
        // Load System Permissions list first (for the assign checkboxes)
        $.ajax({
          url: '/api/v1/permissions',
          type: 'GET',
          success: function(res) {
            const container = $('#permission-checkboxes-container');
            container.empty();
            res.data.forEach(function(p) {
              container.append(`
                <div class="custom-control custom-checkbox mb-2">
                  <input class="custom-control-input checkbox-permission" type="checkbox" id="perm-${p.id}" value="${p.id}">
                  <label for="perm-${p.id}" class="custom-control-label font-weight-normal">
                    <code>${p.name}</code> (Quyền hạn hệ thống ID: ${p.id})
                  </label>
                </div>
              `);
            });
          }
        });

        // Load roles list
        $.ajax({
          url: '/api/v1/roles',
          type: 'GET',
          success: function(res) {
            const body = $('#table-roles-body');
            body.empty();
            res.data.forEach(function(r) {
              body.append(`
                <tr class="role-row" style="cursor:pointer;" data-id="${r.id}" data-name="${r.name}">
                  <td>${r.id}</td>
                  <td><span class="text-primary font-weight-bold">${r.name}</span></td>
                  <td>
                    <button class="btn btn-danger btn-xs btn-delete-role" data-id="${r.id}"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
              `);
            });
          }
        });

        // Reset the assign permissions card
        $('#form-permission-assign').hide();
        $('#permission-assign-header').show().text('Chọn một vai trò bên trái để gán danh sách quyền hệ thống.');
      }

      $('#form-role-add').on('submit', function(e) {
        e.preventDefault();
        const name = $('#role-name-input').val();
        $.ajax({
          url: '/api/v1/roles',
          type: 'POST',
          data: JSON.stringify({ name }),
          success: function(res) {
            $('#role-name-input').val('');
            showAlert('success', 'Thành công', res.message);
            loadRolesAndPermissions();
          },
          error: function() {
            showAlert('error', 'Thất bại', 'Tên vai trò đã tồn tại');
          }
        });
      });

      $(document).on('click', '.btn-delete-role', function(e) {
        e.stopPropagation(); // Avoid triggering row click
        const id = $(this).data('id');
        Swal.fire({
          title: 'Xác nhận xóa vai trò này?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: 'Xóa!',
          cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '/api/v1/roles/' + id,
              type: 'DELETE',
              success: function(res) {
                showAlert('success', 'Thành công', res.message);
                loadRolesAndPermissions();
              }
            });
          }
        });
      });

      // Role row click (to load assign panel)
      $(document).on('click', '.role-row', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        $('.role-row').removeClass('table-primary');
        $(this).addClass('table-primary');

        $.ajax({
          url: '/api/v1/roles/' + id,
          type: 'GET',
          success: function(res) {
            const role = res.data;
            $('#assign-role-id').val(role.id);
            $('#permission-assign-header').hide();
            $('#form-permission-assign').show();

            // Reset checkboxes
            $('.checkbox-permission').prop('checked', false);

            // Check permissions that this role already has
            role.permissions.forEach(function(p) {
              $(`#perm-${p.id}`).prop('checked', true);
            });

            showAlert('info', 'Thông báo', 'Đã tải quyền của vai trò: ' + name);
          }
        });
      });

      // Handle permission assign submit
      $('#form-permission-assign').on('submit', function(e) {
        e.preventDefault();
        const roleId = $('#assign-role-id').val();
        
        const permissionIds = [];
        $('.checkbox-permission:checked').each(function() {
          permissionIds.push(parseInt($(this).val()));
        });

        $.ajax({
          url: '/api/v1/roles/' + roleId + '/permissions',
          type: 'POST',
          data: JSON.stringify({ permissions_id: permissionIds }),
          success: function(res) {
            showAlert('success', 'Thành công', res.message);
            loadRolesAndPermissions();
          },
          error: function() {
            showAlert('error', 'Thất bại', 'Không thể gán quyền cho vai trò');
          }
        });
      });

      // ===================================================================
      // 10. BUSINESS LOGIC: WAREHOUSES CRUD
      // ===================================================================
      function loadWarehouseSelectors(cb) {
        $.ajax({ url: '/api/v1/companies?limit=100', type: 'GET', success: function(res) {
          const s = $('#warehouse-company-id'); s.empty();
          res.data.data.forEach(function(c) { s.append(`<option value="${c.id}">${c.name}</option>`); });
        }});
        $.ajax({ url: '/api/v1/branches?limit=100', type: 'GET', success: function(res) {
          const s = $('#warehouse-branch-id'); s.empty().append('<option value="">-- Không gắn chi nhánh --</option>');
          res.data.data.forEach(function(b) { s.append(`<option value="${b.id}">${b.name}</option>`); });
        }});
        $.ajax({ url: '/api/v1/warehouses?limit=100', type: 'GET', success: function(res) {
          const s = $('#warehouse-parent-id'); s.empty().append('<option value="">-- Không có (Kho tổng) --</option>');
          res.data.data.forEach(function(w) { s.append(`<option value="${w.id}">${w.name}</option>`); });
        }});
        $.ajax({ url: '/api/v1/users?limit=100', type: 'GET', success: function(res) {
          const s = $('#warehouse-manager-id'); s.empty().append('<option value="">-- Chưa phân công --</option>');
          res.data.data.forEach(function(u) { s.append(`<option value="${u.id}">${u.name}</option>`); });
          if (cb) cb();
        }});
      }

      function loadWarehousesList() {
        loadWarehouseSelectors();
        $.ajax({ url: '/api/v1/warehouses?limit=100', type: 'GET', success: function(response) {
          const body = $('#table-warehouses-body'); body.empty();
          response.data.data.forEach(function(w) {
            const type = w.warehouse_type === 1
              ? '<span class="badge badge-primary">Kho tổng</span>'
              : '<span class="badge badge-secondary">Kho con</span>';
            const statusBadge = w.status === 1
              ? '<span class="badge badge-success">Hoạt động</span>'
              : '<span class="badge badge-danger">Ngừng</span>';
            const toggle = w.status === 1
              ? `<button class="btn btn-warning btn-xs btn-toggle-warehouse" data-id="${w.id}" data-status="0"><i class="fas fa-ban"></i> Ngưng</button>`
              : `<button class="btn btn-success btn-xs btn-toggle-warehouse" data-id="${w.id}" data-status="1"><i class="fas fa-check"></i> Bật</button>`;
            body.append(`
              <tr>
                <td>${w.id}</td>
                <td><b>${w.code}</b></td>
                <td>${w.name}</td>
                <td>${type}</td>
                <td>${w.parent ? w.parent.name : '<i>—</i>'}</td>
                <td>${w.manager ? w.manager.name : '<i>—</i>'}</td>
                <td>${statusBadge}</td>
                <td>
                  <button class="btn btn-info btn-xs btn-edit-warehouse" data-id="${w.id}"><i class="fas fa-edit"></i></button>
                  ${toggle}
                  <button class="btn btn-danger btn-xs btn-delete-warehouse" data-id="${w.id}"><i class="fas fa-trash"></i></button>
                </td>
              </tr>
            `);
          });
        }});
      }

      $('#btn-add-warehouse').on('click', function() {
        loadWarehouseSelectors();
        $('#form-warehouse')[0].reset();
        $('#warehouse-id').val('');
        $('#warehouse-code').prop('readonly', false);
        $('#modal-warehouse-title').text('Thêm Mới Kho');
        $('#modal-warehouse').modal('show');
      });

      $(document).on('click', '.btn-edit-warehouse', function() {
        const id = $(this).data('id');
        loadWarehouseSelectors(function() {
          $.ajax({ url: '/api/v1/warehouses/' + id, type: 'GET', success: function(res) {
            const w = res.data;
            $('#warehouse-id').val(w.id);
            $('#warehouse-company-id').val(w.company_id);
            $('#warehouse-branch-id').val(w.branch_id || '');
            $('#warehouse-parent-id').val(w.parent_id || '');
            $('#warehouse-manager-id').val(w.manager_id || '');
            $('#warehouse-code').val(w.code).prop('readonly', true);
            $('#warehouse-name').val(w.name);
            $('#warehouse-type').val(w.warehouse_type);
            $('#warehouse-address').val(w.address || '');
            $('#warehouse-status').val(w.status);
            $('#modal-warehouse-title').text('Chỉnh Sửa Kho');
            $('#modal-warehouse').modal('show');
          }});
        });
      });

      $('#form-warehouse').on('submit', function(e) {
        e.preventDefault();
        const id = $('#warehouse-id').val();
        const url = id ? '/api/v1/warehouses/' + id : '/api/v1/warehouses';
        const method = id ? 'PUT' : 'POST';
        const data = {
          company_id: $('#warehouse-company-id').val(),
          branch_id: $('#warehouse-branch-id').val() || null,
          parent_id: $('#warehouse-parent-id').val() || null,
          manager_id: $('#warehouse-manager-id').val() || null,
          name: $('#warehouse-name').val(),
          warehouse_type: $('#warehouse-type').val(),
          address: $('#warehouse-address').val() || null,
          status: $('#warehouse-status').val()
        };
        if (!id) { data.code = $('#warehouse-code').val(); }

        $.ajax({ url: url, type: method, data: JSON.stringify(data),
          success: function(response) {
            $('#modal-warehouse').modal('hide');
            showAlert('success', 'Thành công', response.message);
            loadWarehousesList();
          },
          error: function(xhr) {
            const err = xhr.responseJSON;
            showAlert('error', 'Thất bại', err && err.message ? err.message : 'Có lỗi xảy ra');
          }
        });
      });

      $(document).on('click', '.btn-toggle-warehouse', function() {
        const id = $(this).data('id');
        const action = $(this).data('status') == 1 ? 'enable' : 'disable';
        $.ajax({ url: '/api/v1/warehouses/' + id + '/' + action, type: 'PATCH',
          success: function(res) { showAlert('success', 'Thành công', res.message); loadWarehousesList(); }
        });
      });

      $(document).on('click', '.btn-delete-warehouse', function() {
        const id = $(this).data('id');
        Swal.fire({ title: 'Xác nhận xóa kho này?', icon: 'warning', showCancelButton: true,
          confirmButtonColor: '#d33', confirmButtonText: 'Xóa!', cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({ url: '/api/v1/warehouses/' + id, type: 'DELETE',
              success: function(res) { showAlert('success', 'Thành công', res.message); loadWarehousesList(); },
              error: function(xhr) { const err = xhr.responseJSON; showAlert('error', 'Không thể xóa', err && err.message ? err.message : 'Có lỗi xảy ra'); }
            });
          }
        });
      });

      // ===================================================================
      // 11. BUSINESS LOGIC: SKUS CRUD
      // ===================================================================
      function loadSkusList() {
        $.ajax({ url: '/api/v1/skus?limit=100', type: 'GET', success: function(response) {
          const body = $('#table-skus-body'); body.empty();
          response.data.data.forEach(function(s) {
            const serial = s.has_serial
              ? '<span class="badge badge-info">Có</span>'
              : '<span class="badge badge-light">Không</span>';
            const statusBadge = s.status === 1
              ? '<span class="badge badge-success">Hoạt động</span>'
              : '<span class="badge badge-danger">Ngừng</span>';
            const toggle = s.status === 1
              ? `<button class="btn btn-warning btn-xs btn-toggle-sku" data-id="${s.id}" data-status="0"><i class="fas fa-ban"></i> Ngưng</button>`
              : `<button class="btn btn-success btn-xs btn-toggle-sku" data-id="${s.id}" data-status="1"><i class="fas fa-check"></i> Bật</button>`;
            body.append(`
              <tr>
                <td>${s.id}</td>
                <td><b>${s.code}</b></td>
                <td>${s.name}</td>
                <td>${s.unit}</td>
                <td>${s.category || '<i>—</i>'}</td>
                <td>${serial}</td>
                <td>${statusBadge}</td>
                <td>
                  <button class="btn btn-info btn-xs btn-edit-sku" data-id="${s.id}"><i class="fas fa-edit"></i></button>
                  ${toggle}
                  <button class="btn btn-danger btn-xs btn-delete-sku" data-id="${s.id}"><i class="fas fa-trash"></i></button>
                </td>
              </tr>
            `);
          });
        }});
      }

      $('#btn-add-sku').on('click', function() {
        $('#form-sku')[0].reset();
        $('#sku-id').val('');
        $('#modal-sku-title').text('Thêm Mới SKU');
        $('#modal-sku').modal('show');
      });

      $(document).on('click', '.btn-edit-sku', function() {
        const id = $(this).data('id');
        $.ajax({ url: '/api/v1/skus/' + id, type: 'GET', success: function(res) {
          const s = res.data;
          $('#sku-id').val(s.id);
          $('#sku-code').val(s.code);
          $('#sku-name').val(s.name);
          $('#sku-unit').val(s.unit);
          $('#sku-category').val(s.category || '');
          $('#sku-has-serial').val(s.has_serial ? '1' : '0');
          $('#sku-status').val(s.status);
          $('#modal-sku-title').text('Chỉnh Sửa SKU');
          $('#modal-sku').modal('show');
        }});
      });

      $('#form-sku').on('submit', function(e) {
        e.preventDefault();
        const id = $('#sku-id').val();
        const url = id ? '/api/v1/skus/' + id : '/api/v1/skus';
        const method = id ? 'PUT' : 'POST';
        const data = {
          code: $('#sku-code').val(),
          name: $('#sku-name').val(),
          unit: $('#sku-unit').val(),
          category: $('#sku-category').val() || null,
          has_serial: $('#sku-has-serial').val() === '1',
          status: $('#sku-status').val()
        };

        $.ajax({ url: url, type: method, data: JSON.stringify(data),
          success: function(response) {
            $('#modal-sku').modal('hide');
            showAlert('success', 'Thành công', response.message);
            loadSkusList();
          },
          error: function(xhr) {
            const err = xhr.responseJSON;
            showAlert('error', 'Thất bại', err && err.message ? err.message : 'Có lỗi xảy ra');
          }
        });
      });

      $(document).on('click', '.btn-toggle-sku', function() {
        const id = $(this).data('id');
        const action = $(this).data('status') == 1 ? 'enable' : 'disable';
        $.ajax({ url: '/api/v1/skus/' + id + '/' + action, type: 'PATCH',
          success: function(res) { showAlert('success', 'Thành công', res.message); loadSkusList(); }
        });
      });

      $(document).on('click', '.btn-delete-sku', function() {
        const id = $(this).data('id');
        Swal.fire({ title: 'Xác nhận xóa SKU này?', icon: 'warning', showCancelButton: true,
          confirmButtonColor: '#d33', confirmButtonText: 'Xóa!', cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({ url: '/api/v1/skus/' + id, type: 'DELETE',
              success: function(res) { showAlert('success', 'Thành công', res.message); loadSkusList(); },
              error: function(xhr) { const err = xhr.responseJSON; showAlert('error', 'Không thể xóa', err && err.message ? err.message : 'Có lỗi xảy ra'); }
            });
          }
        });
      });

      // ===================================================================
      // 12. BUSINESS LOGIC: PRODUCT CATEGORIES
      // ===================================================================
      function flattenCategories(treeNodes, depth = 0) {
        let result = [];
        treeNodes.forEach(function(node) {
          node.indentedName = '— '.repeat(depth) + node.name;
          result.push(node);
          if (node.children && node.children.length > 0) {
            result = result.concat(flattenCategories(node.children, depth + 1));
          }
        });
        return result;
      }

      function loadCategoriesList() {
        $.ajax({ url: '/api/v1/product-categories?tree=1', type: 'GET', success: function(response) {
          const body = $('#table-categories-body'); body.empty();
          const select = $('#category-parent-id');
          select.empty().append('<option value="">-- Là danh mục gốc (Cấp cao nhất) --</option>');

          const flatCats = flattenCategories(response.data);
          
          if (flatCats.length === 0) {
            body.append('<tr><td colspan="7" class="text-center text-muted">Không có nhóm sản phẩm nào</td></tr>');
            return;
          }

          flatCats.forEach(function(c) {
            select.append(`<option value="${c.id}">${c.indentedName}</option>`);
            const parentName = c.parent ? c.parent.name : '<i>Gốc</i>';
            const statusBadge = c.status === 1
              ? '<span class="badge badge-success">Kích hoạt</span>'
              : '<span class="badge badge-danger">Tạm ngưng</span>';

            body.append(`
              <tr>
                <td>${c.id}</td>
                <td><b>${c.code}</b></td>
                <td>${c.indentedName}</td>
                <td>${parentName}</td>
                <td>${c.sort_order}</td>
                <td>${statusBadge}</td>
                <td>
                  <button class="btn btn-info btn-xs btn-edit-category" data-id="${c.id}"><i class="fas fa-edit"></i> Sửa</button>
                  <button class="btn btn-danger btn-xs btn-delete-category" data-id="${c.id}"><i class="fas fa-trash"></i> Xóa</button>
                </td>
              </tr>
            `);
          });
        }});
      }

      $('#btn-add-category').on('click', function() {
        $('#form-category')[0].reset();
        $('#category-id').val('');
        $('#category-code').prop('readonly', false);
        $('#modal-category-title').text('Thêm Mới Nhóm Sản Phẩm');
        loadCategoriesList(); // Reload categories dropdown list
        $('#modal-category').modal('show');
      });

      $(document).on('click', '.btn-edit-category', function() {
        const id = $(this).data('id');
        $.ajax({ url: '/api/v1/product-categories/' + id, type: 'GET', success: function(res) {
          const c = res.data;
          $('#category-id').val(c.id);
          $('#category-code').val(c.code).prop('readonly', true);
          $('#category-name').val(c.name);
          $('#category-parent-id').val(c.parent_id || '');
          $('#category-sort-order').val(c.sort_order);
          $('#category-status').val(c.status);
          $('#modal-category-title').text('Chỉnh Sửa Nhóm Sản Phẩm');
          $('#modal-category').modal('show');
        }});
      });

      $('#form-category').on('submit', function(e) {
        e.preventDefault();
        const id = $('#category-id').val();
        const url = id ? '/api/v1/product-categories/' + id : '/api/v1/product-categories';
        const method = id ? 'PUT' : 'POST';
        const data = {
          code: $('#category-code').val(),
          name: $('#category-name').val(),
          parent_id: $('#category-parent-id').val() ? parseInt($('#category-parent-id').val()) : null,
          sort_order: parseInt($('#category-sort-order').val() || 0),
          status: parseInt($('#category-status').val())
        };

        $.ajax({ url: url, type: method, data: JSON.stringify(data),
          success: function(response) {
            $('#modal-category').modal('hide');
            showAlert('success', 'Thành công', response.message);
            loadCategoriesList();
          },
          error: function(xhr) {
            const err = xhr.responseJSON;
            showAlert('error', 'Thất bại', err && err.message ? err.message : 'Mã nhóm đã tồn tại hoặc bị lỗi vòng lặp.');
          }
        });
      });

      $(document).on('click', '.btn-delete-category', function() {
        const id = $(this).data('id');
        Swal.fire({ title: 'Xác nhận xóa nhóm sản phẩm này?', icon: 'warning', showCancelButton: true,
          confirmButtonColor: '#d33', confirmButtonText: 'Xóa!', cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({ url: '/api/v1/product-categories/' + id, type: 'DELETE',
              success: function(res) { showAlert('success', 'Thành công', res.message); loadCategoriesList(); },
              error: function(xhr) { const err = xhr.responseJSON; showAlert('error', 'Không thể xóa', err && err.message ? err.message : 'Có lỗi xảy ra'); }
            });
          }
        });
      });


      // ===================================================================
      // 13. BUSINESS LOGIC: SUPPLIERS
      // ===================================================================
      function loadSuppliersList() {
        $.ajax({ url: '/api/v1/suppliers?limit=100', type: 'GET', success: function(response) {
          const body = $('#table-suppliers-body'); body.empty();
          
          if (response.data.data.length === 0) {
            body.append('<tr><td colspan="7" class="text-center text-muted">Không có nhà cung cấp nào</td></tr>');
            return;
          }

          response.data.data.forEach(function(s) {
            const statusBadge = s.status === 1
              ? '<span class="badge badge-success">Hoạt động</span>'
              : '<span class="badge badge-danger">Ngừng</span>';
            const toggle = s.status === 1
              ? `<button class="btn btn-warning btn-xs btn-toggle-supplier" data-id="${s.id}" data-status="0"><i class="fas fa-ban"></i></button>`
              : `<button class="btn btn-success btn-xs btn-toggle-supplier" data-id="${s.id}" data-status="1"><i class="fas fa-check"></i></button>`;

            body.append(`
              <tr>
                <td>${s.id}</td>
                <td><b>${s.code}</b></td>
                <td>${s.name}</td>
                <td>${s.phone || '<i>—</i>'}</td>
                <td>${s.address || '<i>—</i>'}</td>
                <td>${statusBadge}</td>
                <td>
                  <button class="btn btn-info btn-xs btn-edit-supplier" data-id="${s.id}"><i class="fas fa-edit"></i> Sửa</button>
                  ${toggle}
                  <button class="btn btn-danger btn-xs btn-delete-supplier" data-id="${s.id}"><i class="fas fa-trash"></i> Xóa</button>
                </td>
              </tr>
            `);
          });
        }});
      }

      $('#btn-add-supplier').on('click', function() {
        $('#form-supplier')[0].reset();
        $('#supplier-id').val('');
        $('#supplier-code').prop('readonly', false);
        $('#modal-supplier-title').text('Thêm Mới Nhà Cung Cấp');
        $('#modal-supplier').modal('show');
      });

      $(document).on('click', '.btn-edit-supplier', function() {
        const id = $(this).data('id');
        $.ajax({ url: '/api/v1/suppliers/' + id, type: 'GET', success: function(res) {
          const s = res.data;
          $('#supplier-id').val(s.id);
          $('#supplier-code').val(s.code).prop('readonly', true);
          $('#supplier-name').val(s.name);
          $('#supplier-phone').val(s.phone || '');
          $('#supplier-address').val(s.address || '');
          $('#supplier-status').val(s.status);
          $('#modal-supplier-title').text('Chỉnh Sửa Nhà Cung Cấp');
          $('#modal-supplier').modal('show');
        }});
      });

      $('#form-supplier').on('submit', function(e) {
        e.preventDefault();
        const id = $('#supplier-id').val();
        const url = id ? '/api/v1/suppliers/' + id : '/api/v1/suppliers';
        const method = id ? 'PUT' : 'POST';
        const data = {
          code: $('#supplier-code').val(),
          name: $('#supplier-name').val(),
          phone: $('#supplier-phone').val() || null,
          address: $('#supplier-address').val() || null,
          status: parseInt($('#supplier-status').val())
        };

        $.ajax({ url: url, type: method, data: JSON.stringify(data),
          success: function(response) {
            $('#modal-supplier').modal('hide');
            showAlert('success', 'Thành công', response.message);
            loadSuppliersList();
          },
          error: function(xhr) {
            const err = xhr.responseJSON;
            showAlert('error', 'Thất bại', err && err.message ? err.message : 'Có lỗi xảy ra.');
          }
        });
      });

      $(document).on('click', '.btn-toggle-supplier', function() {
        const id = $(this).data('id');
        const action = $(this).data('status') == 1 ? 'enable' : 'disable';
        $.ajax({ url: '/api/v1/suppliers/' + id + '/' + action, type: 'PATCH',
          success: function(res) { showAlert('success', 'Thành công', res.message); loadSuppliersList(); }
        });
      });

      $(document).on('click', '.btn-delete-supplier', function() {
        const id = $(this).data('id');
        Swal.fire({ title: 'Xác nhận xóa nhà cung cấp này?', icon: 'warning', showCancelButton: true,
          confirmButtonColor: '#d33', confirmButtonText: 'Xóa!', cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({ url: '/api/v1/suppliers/' + id, type: 'DELETE',
              success: function(res) { showAlert('success', 'Thành công', res.message); loadSuppliersList(); },
              error: function(xhr) { const err = xhr.responseJSON; showAlert('error', 'Không thể xóa', err && err.message ? err.message : 'Có lỗi xảy ra'); }
            });
          }
        });
      });


      // ===================================================================
      // 14. BUSINESS LOGIC: WAREHOUSE SCOPES
      // ===================================================================
      function loadWarehouseScopesList() {
        // Load selector users
        $.ajax({ url: '/api/v1/users?limit=500', type: 'GET', success: function(res) {
          const selectForm = $('#scope-user-id'); selectForm.empty().append('<option value="">-- Chọn nhân viên --</option>');
          const selectFilter = $('#filter-scope-user-id'); selectFilter.empty().append('<option value="">-- Lọc theo nhân viên --</option>');
          res.data.data.forEach(function(u) {
            const label = `${u.name} (${u.code})`;
            selectForm.append(`<option value="${u.id}">${label}</option>`);
            selectFilter.append(`<option value="${u.id}">${label}</option>`);
          });
        }});

        // Load selector warehouses
        $.ajax({ url: '/api/v1/warehouses?limit=200', type: 'GET', success: function(res) {
          const container = $('#scope-warehouse-checkboxes'); container.empty();
          if (res.data.data.length === 0) {
            container.append('<p class="text-muted text-sm mb-0">Không có kho nào khả dụng</p>');
            return;
          }
          res.data.data.forEach(function(w) {
            container.append(`
              <div class="custom-control custom-checkbox mb-1">
                <input class="custom-control-input checkbox-scope-warehouse" type="checkbox" id="scope-wh-${w.id}" value="${w.id}">
                <label for="scope-wh-${w.id}" class="custom-control-label font-weight-normal">${w.name} (<code>${w.code}</code>)</label>
              </div>
            `);
          });
        }});

        fetchWarehouseScopes();
      }

      function fetchWarehouseScopes(userId = '') {
        let url = '/api/v1/warehouse-scopes?limit=200';
        if (userId) url += '&user_id=' + userId;

        $.ajax({ url: url, type: 'GET', success: function(response) {
          const body = $('#table-scopes-body'); body.empty();
          
          if (response.data.data.length === 0) {
            body.append('<tr><td colspan="4" class="text-center text-muted">Không có cấu hình phân quyền nào</td></tr>');
            return;
          }

          response.data.data.forEach(function(s) {
            body.append(`
              <tr>
                <td>${s.id}</td>
                <td><b>${s.user ? s.user.name : 'N/A'}</b> (Mã: ${s.user ? s.user.code : 'N/A'})</td>
                <td><span class="badge badge-info">${s.warehouse ? s.warehouse.name : 'N/A'} (<code>${s.warehouse ? s.warehouse.code : 'N/A'}</code>)</span></td>
                <td>
                  <button class="btn btn-danger btn-xs btn-delete-scope" data-id="${s.id}"><i class="fas fa-trash-alt mr-1"></i> Thu hồi</button>
                </td>
              </tr>
            `);
          });
        }});
      }

      $('#filter-scope-user-id').on('change', function() {
        fetchWarehouseScopes($(this).val());
      });

      $('#form-warehouse-scope').on('submit', function(e) {
        e.preventDefault();
        const userId = parseInt($('#scope-user-id').val());
        const warehouseIds = [];
        $('.checkbox-scope-warehouse:checked').each(function() {
          warehouseIds.push(parseInt($(this).val()));
        });

        if (warehouseIds.length === 0) {
          showAlert('warning', 'Cảnh báo', 'Vui lòng chọn ít nhất một kho');
          return;
        }

        const data = { user_id: userId, warehouse_ids: warehouseIds };

        $.ajax({
          url: '/api/v1/warehouse-scopes',
          type: 'POST',
          data: JSON.stringify(data),
          success: function(res) {
            showAlert('success', 'Thành công', res.message);
            $('.checkbox-scope-warehouse').prop('checked', false);
            $('#scope-user-id').val('');
            fetchWarehouseScopes();
          },
          error: function(xhr) {
            showAlert('error', 'Thất bại', 'Không thể gán quyền truy cập kho');
          }
        });
      });

      $(document).on('click', '.btn-delete-scope', function() {
        const id = $(this).data('id');
        Swal.fire({ title: 'Xác nhận thu hồi quyền truy cập kho này?', icon: 'warning', showCancelButton: true,
          confirmButtonColor: '#d33', confirmButtonText: 'Thu hồi!', cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({ url: '/api/v1/warehouse-scopes/' + id, type: 'DELETE',
              success: function(res) { showAlert('success', 'Thành công', res.message); fetchWarehouseScopes(); }
            });
          }
        });
      });


      // ===================================================================
      // 15. BUSINESS LOGIC: SAFE STOCK CONFIGS
      // ===================================================================
      function loadSafeStockConfigsList() {
        // Load SKU Selector
        $.ajax({ url: '/api/v1/skus?limit=500', type: 'GET', success: function(res) {
          const select = $('#safe-stock-sku-id'); select.empty().append('<option value="">-- Chọn sản phẩm --</option>');
          res.data.data.forEach(function(s) { select.append(`<option value="${s.id}">${s.name} (${s.code})</option>`); });
        }});

        // Load Warehouse Selector
        $.ajax({ url: '/api/v1/warehouses?limit=200', type: 'GET', success: function(res) {
          const select = $('#safe-stock-warehouse-id'); select.empty().append('<option value="">-- Áp dụng toàn hệ thống (Không chọn kho) --</option>');
          res.data.data.forEach(function(w) { select.append(`<option value="${w.id}">${w.name} (${w.code})</option>`); });
        }});

        $.ajax({ url: '/api/v1/safe-stock-configs?limit=100', type: 'GET', success: function(response) {
          const body = $('#table-safe-stock-body'); body.empty();
          
          if (response.data.data.length === 0) {
            body.append('<tr><td colspan="9" class="text-center text-muted">Không có cấu hình tồn kho an toàn nào</td></tr>');
            return;
          }

          response.data.data.forEach(function(c) {
            const whName = c.warehouse ? `<span class="badge badge-info">${c.warehouse.name}</span>` : '<span class="badge badge-secondary">Toàn hệ thống</span>';
            const effectiveFrom = c.effective_from ? c.effective_from.split('T')[0] : '—';
            const effectiveTo = c.effective_to ? c.effective_to.split('T')[0] : '<i>Không hết hạn</i>';
            body.append(`
              <tr>
                <td>${c.id}</td>
                <td><b>${c.sku ? c.sku.name : 'N/A'}</b> (<code>${c.sku ? c.sku.code : 'N/A'}</code>)</td>
                <td>${whName}</td>
                <td class="text-right font-weight-bold text-danger">${c.min_qty !== null ? c.min_qty : '—'}</td>
                <td class="text-right font-weight-bold text-success">${c.max_qty !== null ? c.max_qty : '—'}</td>
                <td>${effectiveFrom}</td>
                <td>${effectiveTo}</td>
                <td>${c.note || ''}</td>
                <td>
                  <button class="btn btn-info btn-xs btn-edit-safe-stock" data-id="${c.id}"><i class="fas fa-edit"></i></button>
                  <button class="btn btn-danger btn-xs btn-delete-safe-stock" data-id="${c.id}"><i class="fas fa-trash"></i></button>
                </td>
              </tr>
            `);
          });
        }});
      }

      $('#btn-add-safe-stock').on('click', function() {
        $('#form-safe-stock')[0].reset();
        $('#safe-stock-id').val('');
        $('#safe-stock-sku-id').prop('disabled', false);
        $('#safe-stock-warehouse-id').prop('disabled', false);
        $('#safe-stock-effective-from').prop('readonly', false);
        $('#modal-safe-stock-title').text('Thiết Lập Định Mức Tồn Kho An Toàn');
        $('#modal-safe-stock').modal('show');
      });

      $(document).on('click', '.btn-edit-safe-stock', function() {
        const id = $(this).data('id');
        $.ajax({ url: '/api/v1/safe-stock-configs/' + id, type: 'GET', success: function(res) {
          const c = res.data;
          $('#safe-stock-id').val(c.id);
          $('#safe-stock-sku-id').val(c.sku_id).prop('disabled', true);
          $('#safe-stock-warehouse-id').val(c.warehouse_id || '').prop('disabled', true);
          
          $('#safe-stock-min-qty').val(c.min_qty);
          $('#safe-stock-max-qty').val(c.max_qty);
          
          if (c.effective_from) {
            $('#safe-stock-effective-from').val(c.effective_from.split('T')[0]).prop('readonly', true);
          }
          if (c.effective_to) {
            $('#safe-stock-effective-to').val(c.effective_to.split('T')[0]);
          } else {
            $('#safe-stock-effective-to').val('');
          }
          
          $('#safe-stock-note').val(c.note || '');
          $('#modal-safe-stock-title').text('Chỉnh Sửa Định Mức An Toàn');
          $('#modal-safe-stock').modal('show');
        }});
      });

      $('#form-safe-stock').on('submit', function(e) {
        e.preventDefault();
        const id = $('#safe-stock-id').val();
        const url = id ? '/api/v1/safe-stock-configs/' + id : '/api/v1/safe-stock-configs';
        const method = id ? 'PUT' : 'POST';

        let data = {};
        if (id) {
          // Update allows min, max, effective_to, note
          data = {
            min_qty: $('#safe-stock-min-qty').val() !== '' ? parseFloat($('#safe-stock-min-qty').val()) : null,
            max_qty: $('#safe-stock-max-qty').val() !== '' ? parseFloat($('#safe-stock-max-qty').val()) : null,
            effective_to: $('#safe-stock-effective-to').val() || null,
            note: $('#safe-stock-note').val() || null
          };
        } else {
          // Create fields
          data = {
            sku_id: parseInt($('#safe-stock-sku-id').val()),
            warehouse_id: $('#safe-stock-warehouse-id').val() ? parseInt($('#safe-stock-warehouse-id').val()) : null,
            min_qty: $('#safe-stock-min-qty').val() !== '' ? parseFloat($('#safe-stock-min-qty').val()) : null,
            max_qty: $('#safe-stock-max-qty').val() !== '' ? parseFloat($('#safe-stock-max-qty').val()) : null,
            effective_from: $('#safe-stock-effective-from').val(),
            effective_to: $('#safe-stock-effective-to').val() || null,
            note: $('#safe-stock-note').val() || null
          };
        }

        $.ajax({ url: url, type: method, data: JSON.stringify(data),
          success: function(response) {
            $('#modal-safe-stock').modal('hide');
            showAlert('success', 'Thành công', response.message);
            loadSafeStockConfigsList();
          },
          error: function(xhr) {
            const err = xhr.responseJSON;
            showAlert('error', 'Thất bại', err && err.message ? err.message : 'Dữ liệu không hợp lệ.');
          }
        });
      });

      $(document).on('click', '.btn-delete-safe-stock', function() {
        const id = $(this).data('id');
        Swal.fire({ title: 'Xác nhận xóa cấu hình tồn an toàn này?', icon: 'warning', showCancelButton: true,
          confirmButtonColor: '#d33', confirmButtonText: 'Xóa!', cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({ url: '/api/v1/safe-stock-configs/' + id, type: 'DELETE',
              success: function(res) { showAlert('success', 'Thành công', res.message); loadSafeStockConfigsList(); }
            });
          }
        });
      });


      // ===================================================================
      // 16. BUSINESS LOGIC: INBOUND DOCUMENTS
      // ===================================================================
      let skusCacheList = [];
      let warehousesCacheList = [];

      function loadInboundSelectors(callback) {
        $.ajax({ url: '/api/v1/warehouses?limit=200', type: 'GET', success: function(res) {
          warehousesCacheList = res.data.data;
          const selectFilter = $('#filter-inbound-warehouse-id'); selectFilter.empty().append('<option value="">-- Tất cả --</option>');
          const selectForm = $('#inbound-dest-warehouse-id'); selectForm.empty().append('<option value="">-- Chọn kho nhập hàng --</option>');
          warehousesCacheList.forEach(function(w) {
            selectFilter.append(`<option value="${w.id}">${w.name}</option>`);
            selectForm.append(`<option value="${w.id}">${w.name} (Code: ${w.code})</option>`);
          });
        }});

        $.ajax({ url: '/api/v1/skus?limit=500', type: 'GET', success: function(res) {
          skusCacheList = res.data.data;
          if (callback) callback();
        }});
      }

      function loadInboundDocumentsList() {
        loadInboundSelectors();
        const warehouseId = $('#filter-inbound-warehouse-id').val();
        const status = $('#filter-inbound-status').val();
        const search = $('#filter-inbound-search').val();

        let url = '/api/v1/inbound-documents?limit=100';
        if (warehouseId) url += '&warehouse_id=' + warehouseId;
        if (status) url += '&status=' + status;
        if (search) url += '&search=' + search;

        $.ajax({ url: url, type: 'GET', success: function(response) {
          const body = $('#table-inbounds-body'); body.empty();
          
          if (response.data.data.length === 0) {
            body.append('<tr><td colspan="8" class="text-center text-muted py-3">Không có phiếu nhập kho nào khớp với điều kiện lọc</td></tr>');
            return;
          }

          const statusClasses = {
            1: 'badge-secondary', 2: 'badge-warning', 3: 'badge-info',
            4: 'badge-success', 9: 'badge-danger'
          };

          response.data.data.forEach(function(d) {
            const badgeClass = statusClasses[d.status] || 'badge-dark';
            const dateStr = d.created_at ? d.created_at.split('T')[0] : '—';
            body.append(`
              <tr>
                <td>${d.id}</td>
                <td><b class="text-primary">${d.code}</b></td>
                <td>${d.dest_warehouse ? d.dest_warehouse.name : 'N/A'}</td>
                <td>${d.note || '<i>—</i>'}</td>
                <td><span class="badge ${badgeClass}">${d.status_label}</span></td>
                <td>${d.creator ? d.creator.name : 'N/A'}</td>
                <td>${dateStr}</td>
                <td>
                  <button class="btn btn-primary btn-xs btn-view-inbound" data-id="${d.id}"><i class="fas fa-eye"></i> Xem</button>
                  ${d.status === 1 ? `<button class="btn btn-danger btn-xs btn-delete-inbound" data-id="${d.id}"><i class="fas fa-trash"></i> Xóa</button>` : ''}
                </td>
              </tr>
            `);
          });
        }});
      }

      $('#form-filter-inbounds').on('submit', function(e) {
        e.preventDefault();
        loadInboundDocumentsList();
      });

      $('#btn-reset-inbounds').on('click', function() {
        $('#form-filter-inbounds')[0].reset();
        loadInboundDocumentsList();
      });

      // Render line select options
      function getSkuOptionsHTML() {
        let html = '<option value="">-- Chọn sản phẩm (SKU) --</option>';
        skusCacheList.forEach(function(s) {
          html += `<option value="${s.id}">${s.name} (${s.code})</option>`;
        });
        return html;
      }

      function appendInboundLine() {
        const tbody = $('#table-inbound-lines tbody');
        const row = `
          <tr>
            <td>
              <select class="form-control form-control-sm inbound-line-sku" required>
                ${getSkuOptionsHTML()}
              </select>
            </td>
            <td>
              <input type="number" class="form-control form-control-sm inbound-line-qty" min="0.01" step="any" value="1" required>
            </td>
            <td>
              <input type="number" class="form-control form-control-sm inbound-line-price" min="0" step="any" value="0">
            </td>
            <td>
              <input type="text" class="form-control form-control-sm inbound-line-note" placeholder="Ghi chú dòng...">
            </td>
            <td class="text-center">
              <button type="button" class="btn btn-danger btn-xs btn-inbound-remove-line"><i class="fas fa-times"></i></button>
            </td>
          </tr>
        `;
        tbody.append(row);
      }

      $('#btn-add-inbound').on('click', function() {
        $('#form-inbound')[0].reset();
        $('#inbound-id').val('');
        $('#table-inbound-lines tbody').empty();
        $('#modal-inbound-title').text('Tạo Phiếu Nhập Kho Mới');
        loadInboundSelectors(function() {
          appendInboundLine(); // Start with 1 line
          $('#modal-inbound').modal('show');
        });
      });

      $('#btn-inbound-add-line').on('click', function() {
        appendInboundLine();
      });

      $(document).on('click', '.btn-inbound-remove-line', function() {
        $(this).closest('tr').remove();
      });

      $('#form-inbound').on('submit', function(e) {
        e.preventDefault();
        const destWarehouseId = $('#inbound-dest-warehouse-id').val();
        const note = $('#inbound-note').val();
        
        const lines = [];
        let hasError = false;
        $('#table-inbound-lines tbody tr').each(function() {
          const skuId = $(this).find('.inbound-line-sku').val();
          const qty = $(this).find('.inbound-line-qty').val();
          const price = $(this).find('.inbound-line-price').val();
          const lineNote = $(this).find('.inbound-line-note').val();

          if (!skuId || !qty) {
            hasError = true;
            return false;
          }

          lines.push({
            sku_id: parseInt(skuId),
            quantity: parseFloat(qty),
            unit_price: price !== '' ? parseFloat(price) : 0,
            note: lineNote || null
          });
        });

        if (hasError || lines.length === 0) {
          showAlert('warning', 'Cảnh báo', 'Vui lòng hoàn thành ít nhất 1 dòng sản phẩm.');
          return;
        }

        const data = {
          dest_warehouse_id: parseInt(destWarehouseId),
          note: note || null,
          lines: lines
        };

        $.ajax({
          url: '/api/v1/inbound-documents',
          type: 'POST',
          data: JSON.stringify(data),
          success: function(res) {
            $('#modal-inbound').modal('hide');
            showAlert('success', 'Thành công', res.message);
            loadInboundDocumentsList();
          },
          error: function(xhr) {
            const err = xhr.responseJSON;
            showAlert('error', 'Thất bại', err && err.message ? err.message : 'Không thể tạo phiếu nhập kho');
          }
        });
      });

      // Show detail
      $(document).on('click', '.btn-view-inbound', function() {
        const id = $(this).data('id');
        $.ajax({ url: '/api/v1/inbound-documents/' + id, type: 'GET', success: function(res) {
          const d = res.data;
          $('#detail-inbound-code').text(d.code);
          $('#detail-inbound-warehouse').text(d.dest_warehouse ? d.dest_warehouse.name : 'N/A');
          $('#detail-inbound-creator').text(d.creator ? d.creator.name : 'N/A');
          $('#detail-inbound-date').text(d.created_at ? d.created_at.replace('T', ' ').substring(0, 19) : '—');
          $('#detail-inbound-note').text(d.note || 'Không có ghi chú');

          // Status Badge
          const statusClasses = {
            1: 'badge-secondary', 2: 'badge-warning', 3: 'badge-info',
            4: 'badge-success', 9: 'badge-danger'
          };
          const badgeClass = statusClasses[d.status] || 'badge-dark';
          $('#detail-inbound-status-badge').html(`<span class="badge ${badgeClass} p-2">${d.status_label}</span>`);

          // Approval Metadata
          if (d.status === 3 || d.status === 4) {
            $('#detail-inbound-approver').text(d.approver ? d.approver.name : 'N/A');
            $('#detail-inbound-approved-at').text(d.approved_at ? d.approved_at.replace('T', ' ').substring(0, 19) : '—');
            $('#detail-inbound-approval-meta').show();
          } else {
            $('#detail-inbound-approval-meta').hide();
          }

          // Lines
          const tbody = $('#detail-inbound-lines-body'); tbody.empty();
          let totalQty = 0;
          let totalAmt = 0;
          d.lines.forEach(function(l, idx) {
            const qty = parseFloat(l.quantity);
            const price = parseFloat(l.unit_price || 0);
            const amount = qty * price;
            totalQty += qty;
            totalAmt += amount;

            tbody.append(`
              <tr>
                <td>${idx + 1}</td>
                <td><b>${l.sku ? l.sku.code : 'N/A'}</b></td>
                <td>${l.sku ? l.sku.name : 'N/A'}</td>
                <td>${l.sku ? l.sku.unit : '—'}</td>
                <td class="text-right font-weight-bold">${qty.toLocaleString()}</td>
                <td class="text-right">${price.toLocaleString()}đ</td>
                <td class="text-right font-weight-bold text-primary">${amount.toLocaleString()}đ</td>
                <td>${l.note || ''}</td>
              </tr>
            `);
          });
          $('#detail-inbound-total-qty').text(totalQty.toLocaleString());
          $('#detail-inbound-total-amount').text(totalAmt.toLocaleString() + 'đ');

          // Action Buttons Footer
          const actions = $('#detail-inbound-actions-container');
          actions.empty().append('<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>');

          // 1: Draft -> Show 'Submit' (Gửi duyệt)
          if (d.status === 1) {
            actions.append(`<button type="button" class="btn btn-warning btn-sm btn-inbound-action" data-id="${d.id}" data-action="submit"><i class="fas fa-paper-plane mr-1"></i> Gửi Duyệt</button>`);
          }
          // 2: Pending -> Show 'Approve' (Duyệt) & 'Cancel' (Hủy)
          if (d.status === 2) {
            actions.append(`<button type="button" class="btn btn-success btn-sm btn-inbound-action" data-id="${d.id}" data-action="approve"><i class="fas fa-check-circle mr-1"></i> Duyệt Phiếu</button>`);
            actions.append(`<button type="button" class="btn btn-danger btn-sm btn-inbound-cancel" data-id="${d.id}"><i class="fas fa-ban mr-1"></i> Từ Chối / Hủy</button>`);
          }
          // 3: Approved -> Show 'Confirm' (Xác nhận nhập kho) & 'Cancel' (Hủy)
          if (d.status === 3) {
            actions.append(`<button type="button" class="btn btn-primary btn-sm btn-inbound-action" data-id="${d.id}" data-action="confirm"><i class="fas fa-warehouse mr-1"></i> Xác Nhận Nhập Kho</button>`);
            actions.append(`<button type="button" class="btn btn-danger btn-sm btn-inbound-cancel" data-id="${d.id}"><i class="fas fa-ban mr-1"></i> Hủy Phiếu</button>`);
          }

          $('#modal-inbound-detail').modal('show');
        }});
      });

      // Actions execution
      $(document).on('click', '.btn-inbound-action', function() {
        const id = $(this).data('id');
        const action = $(this).data('action');
        
        let confirmText = "Bạn chắc chắn muốn gửi duyệt phiếu nhập này?";
        if (action === 'approve') confirmText = "Bạn đồng ý phê duyệt chứng từ nhập kho này?";
        if (action === 'confirm') confirmText = "Bạn xác nhận hàng hóa đã thực nhập vào kho? Tồn kho khả dụng sẽ tăng lên tương ứng.";

        Swal.fire({
          title: 'Xác nhận hành động',
          text: confirmText,
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Đồng ý',
          cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/api/v1/inbound-documents/${id}/${action}`,
              type: 'POST',
              success: function(res) {
                $('#modal-inbound-detail').modal('hide');
                showAlert('success', 'Thành công', res.message);
                loadInboundDocumentsList();
              },
              error: function(xhr) {
                const err = xhr.responseJSON;
                showAlert('error', 'Thất bại', err && err.message ? err.message : 'Không thể thực hiện hành động này.');
              }
            });
          }
        });
      });

      // Cancel action with reason prompt
      $(document).on('click', '.btn-inbound-cancel', function() {
        const id = $(this).data('id');
        Swal.fire({
          title: 'Hủy phiếu nhập kho',
          text: 'Vui lòng nhập lý do hủy phiếu:',
          input: 'text',
          inputPlaceholder: 'Lý do hủy...',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: 'Xác nhận hủy',
          cancelButtonText: 'Hủy bỏ',
          inputValidator: (value) => {
            if (!value) {
              return 'Bạn phải nhập lý do hủy!'
            }
          }
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/api/v1/inbound-documents/${id}/cancel`,
              type: 'POST',
              data: JSON.stringify({ reason: result.value }),
              success: function(res) {
                $('#modal-inbound-detail').modal('hide');
                showAlert('success', 'Thành công', res.message);
                loadInboundDocumentsList();
              },
              error: function(xhr) {
                const err = xhr.responseJSON;
                showAlert('error', 'Thất bại', err && err.message ? err.message : 'Không thể hủy phiếu.');
              }
            });
          }
        });
      });

      $(document).on('click', '.btn-delete-inbound', function() {
        const id = $(this).data('id');
        Swal.fire({ title: 'Xác nhận xóa phiếu bản nháp này?', icon: 'warning', showCancelButton: true,
          confirmButtonColor: '#d33', confirmButtonText: 'Xóa!', cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({ url: '/api/v1/inbound-documents/' + id, type: 'DELETE',
              success: function(res) { showAlert('success', 'Thành công', res.message); loadInboundDocumentsList(); }
            });
          }
        });
      });


      // ===================================================================
      // 17. BUSINESS LOGIC: OUTBOUND DOCUMENTS
      // ===================================================================
      function loadOutboundSelectors(callback) {
        $.ajax({ url: '/api/v1/warehouses?limit=200', type: 'GET', success: function(res) {
          warehousesCacheList = res.data.data;
          const selectFilter = $('#filter-outbound-warehouse-id'); selectFilter.empty().append('<option value="">-- Tất cả --</option>');
          const selectForm = $('#outbound-source-warehouse-id'); selectForm.empty().append('<option value="">-- Chọn kho xuất hàng --</option>');
          warehousesCacheList.forEach(function(w) {
            selectFilter.append(`<option value="${w.id}">${w.name}</option>`);
            selectForm.append(`<option value="${w.id}">${w.name} (Code: ${w.code})</option>`);
          });
        }});

        $.ajax({ url: '/api/v1/skus?limit=500', type: 'GET', success: function(res) {
          skusCacheList = res.data.data;
          if (callback) callback();
        }});
      }

      function loadOutboundDocumentsList() {
        loadOutboundSelectors();
        const warehouseId = $('#filter-outbound-warehouse-id').val();
        const status = $('#filter-outbound-status').val();
        const search = $('#filter-outbound-search').val();

        let url = '/api/v1/outbound-documents?limit=100';
        if (warehouseId) url += '&warehouse_id=' + warehouseId;
        if (status) url += '&status=' + status;
        if (search) url += '&search=' + search;

        $.ajax({ url: url, type: 'GET', success: function(response) {
          const body = $('#table-outbounds-body'); body.empty();
          
          if (response.data.data.length === 0) {
            body.append('<tr><td colspan="8" class="text-center text-muted py-3">Không có phiếu xuất kho nào khớp với lọc</td></tr>');
            return;
          }

          const statusClasses = {
            1: 'badge-secondary', 2: 'badge-warning', 3: 'badge-info',
            4: 'badge-success', 9: 'badge-danger'
          };

          response.data.data.forEach(function(d) {
            const badgeClass = statusClasses[d.status] || 'badge-dark';
            const dateStr = d.created_at ? d.created_at.split('T')[0] : '—';
            body.append(`
              <tr>
                <td>${d.id}</td>
                <td><b class="text-primary">${d.code}</b></td>
                <td>${d.source_warehouse ? d.source_warehouse.name : 'N/A'}</td>
                <td>${d.note || '<i>—</i>'}</td>
                <td><span class="badge ${badgeClass}">${d.status_label}</span></td>
                <td>${d.creator ? d.creator.name : 'N/A'}</td>
                <td>${dateStr}</td>
                <td>
                  <button class="btn btn-primary btn-xs btn-view-outbound" data-id="${d.id}"><i class="fas fa-eye"></i> Xem</button>
                  ${d.status === 1 ? `<button class="btn btn-danger btn-xs btn-delete-outbound" data-id="${d.id}"><i class="fas fa-trash"></i> Xóa</button>` : ''}
                </td>
              </tr>
            `);
          });
        }});
      }

      $('#form-filter-outbounds').on('submit', function(e) {
        e.preventDefault();
        loadOutboundDocumentsList();
      });

      $('#btn-reset-outbounds').on('click', function() {
        $('#form-filter-outbounds')[0].reset();
        loadOutboundDocumentsList();
      });

      function appendOutboundLine() {
        const tbody = $('#table-outbound-lines tbody');
        const row = `
          <tr>
            <td>
              <select class="form-control form-control-sm outbound-line-sku" required>
                ${getSkuOptionsHTML()}
              </select>
            </td>
            <td>
              <input type="number" class="form-control form-control-sm outbound-line-qty" min="0.01" step="any" value="1" required>
            </td>
            <td>
              <input type="text" class="form-control form-control-sm outbound-line-note" placeholder="Ghi chú dòng...">
            </td>
            <td class="text-center">
              <button type="button" class="btn btn-danger btn-xs btn-outbound-remove-line"><i class="fas fa-times"></i></button>
            </td>
          </tr>
        `;
        tbody.append(row);
      }

      $('#btn-add-outbound').on('click', function() {
        $('#form-outbound')[0].reset();
        $('#outbound-id').val('');
        $('#table-outbound-lines tbody').empty();
        $('#modal-outbound-title').text('Tạo Phiếu Xuất Kho Mới');
        loadOutboundSelectors(function() {
          appendOutboundLine(); // 1 line default
          $('#modal-outbound').modal('show');
        });
      });

      $('#btn-outbound-add-line').on('click', function() {
        appendOutboundLine();
      });

      $(document).on('click', '.btn-outbound-remove-line', function() {
        $(this).closest('tr').remove();
      });

      $('#form-outbound').on('submit', function(e) {
        e.preventDefault();
        const sourceWarehouseId = $('#outbound-source-warehouse-id').val();
        const note = $('#outbound-note').val();
        
        const lines = [];
        let hasError = false;
        $('#table-outbound-lines tbody tr').each(function() {
          const skuId = $(this).find('.outbound-line-sku').val();
          const qty = $(this).find('.outbound-line-qty').val();
          const lineNote = $(this).find('.outbound-line-note').val();

          if (!skuId || !qty) {
            hasError = true;
            return false;
          }

          lines.push({
            sku_id: parseInt(skuId),
            quantity: parseFloat(qty),
            note: lineNote || null
          });
        });

        if (hasError || lines.length === 0) {
          showAlert('warning', 'Cảnh báo', 'Vui lòng hoàn thành ít nhất 1 dòng sản phẩm.');
          return;
        }

        const data = {
          source_warehouse_id: parseInt(sourceWarehouseId),
          note: note || null,
          lines: lines
        };

        $.ajax({
          url: '/api/v1/outbound-documents',
          type: 'POST',
          data: JSON.stringify(data),
          success: function(res) {
            $('#modal-outbound').modal('hide');
            showAlert('success', 'Thành công', res.message);
            loadOutboundDocumentsList();
          },
          error: function(xhr) {
            const err = xhr.responseJSON;
            showAlert('error', 'Thất bại', err && err.message ? err.message : 'Không thể tạo phiếu xuất kho');
          }
        });
      });

      $(document).on('click', '.btn-view-outbound', function() {
        const id = $(this).data('id');
        $.ajax({ url: '/api/v1/outbound-documents/' + id, type: 'GET', success: function(res) {
          const d = res.data;
          $('#detail-outbound-code').text(d.code);
          $('#detail-outbound-warehouse').text(d.source_warehouse ? d.source_warehouse.name : 'N/A');
          $('#detail-outbound-creator').text(d.creator ? d.creator.name : 'N/A');
          $('#detail-outbound-date').text(d.created_at ? d.created_at.replace('T', ' ').substring(0, 19) : '—');
          $('#detail-outbound-note').text(d.note || 'Không có ghi chú');

          // Status Badge
          const statusClasses = {
            1: 'badge-secondary', 2: 'badge-warning', 3: 'badge-info',
            4: 'badge-success', 9: 'badge-danger'
          };
          const badgeClass = statusClasses[d.status] || 'badge-dark';
          $('#detail-outbound-status-badge').html(`<span class="badge ${badgeClass} p-2">${d.status_label}</span>`);

          if (d.status === 3 || d.status === 4) {
            $('#detail-outbound-approver').text(d.approver ? d.approver.name : 'N/A');
            $('#detail-outbound-approved-at').text(d.approved_at ? d.approved_at.replace('T', ' ').substring(0, 19) : '—');
            $('#detail-outbound-approval-meta').show();
          } else {
            $('#detail-outbound-approval-meta').hide();
          }

          // Lines
          const tbody = $('#detail-outbound-lines-body'); tbody.empty();
          let totalQty = 0;
          d.lines.forEach(function(l, idx) {
            const qty = parseFloat(l.quantity);
            totalQty += qty;

            tbody.append(`
              <tr>
                <td>${idx + 1}</td>
                <td><b>${l.sku ? l.sku.code : 'N/A'}</b></td>
                <td>${l.sku ? l.sku.name : 'N/A'}</td>
                <td>${l.sku ? l.sku.unit : '—'}</td>
                <td class="text-right font-weight-bold">${qty.toLocaleString()}</td>
                <td>${l.note || ''}</td>
              </tr>
            `);
          });
          $('#detail-outbound-total-qty').text(totalQty.toLocaleString());

          // Action buttons footer
          const actions = $('#detail-outbound-actions-container');
          actions.empty().append('<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>');

          if (d.status === 1) {
            actions.append(`<button type="button" class="btn btn-warning btn-sm btn-outbound-action" data-id="${d.id}" data-action="submit"><i class="fas fa-paper-plane mr-1"></i> Gửi Duyệt</button>`);
          }
          if (d.status === 2) {
            actions.append(`<button type="button" class="btn btn-success btn-sm btn-outbound-action" data-id="${d.id}" data-action="approve"><i class="fas fa-check-circle mr-1"></i> Duyệt Phiếu</button>`);
            actions.append(`<button type="button" class="btn btn-danger btn-sm btn-outbound-cancel" data-id="${d.id}"><i class="fas fa-ban mr-1"></i> Từ Chối / Hủy</button>`);
          }
          if (d.status === 3) {
            actions.append(`<button type="button" class="btn btn-primary btn-sm btn-outbound-action" data-id="${d.id}" data-action="confirm"><i class="fas fa-file-export mr-1"></i> Xác Nhận Xuất Kho</button>`);
            actions.append(`<button type="button" class="btn btn-danger btn-sm btn-outbound-cancel" data-id="${d.id}"><i class="fas fa-ban mr-1"></i> Hủy Phiếu</button>`);
          }

          $('#modal-outbound-detail').modal('show');
        }});
      });

      $(document).on('click', '.btn-outbound-action', function() {
        const id = $(this).data('id');
        const action = $(this).data('action');
        
        let confirmText = "Bạn chắc chắn muốn gửi duyệt phiếu xuất này?";
        if (action === 'approve') confirmText = "Bạn đồng ý phê duyệt chứng từ xuất kho này? Tồn kho khả dụng của sản phẩm sẽ được giữ chỗ.";
        if (action === 'confirm') confirmText = "Bạn xác nhận xuất hàng ra khỏi kho? Tồn kho sẽ giảm trực tiếp.";

        Swal.fire({
          title: 'Xác nhận hành động',
          text: confirmText,
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Đồng ý',
          cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/api/v1/outbound-documents/${id}/${action}`,
              type: 'POST',
              success: function(res) {
                $('#modal-outbound-detail').modal('hide');
                showAlert('success', 'Thành công', res.message);
                loadOutboundDocumentsList();
              },
              error: function(xhr) {
                const err = xhr.responseJSON;
                showAlert('error', 'Thất bại', err && err.message ? err.message : 'Không thể thực hiện hành động này.');
              }
            });
          }
        });
      });

      $(document).on('click', '.btn-outbound-cancel', function() {
        const id = $(this).data('id');
        Swal.fire({
          title: 'Hủy phiếu xuất kho',
          text: 'Vui lòng nhập lý do hủy phiếu:',
          input: 'text',
          inputPlaceholder: 'Lý do hủy...',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: 'Xác nhận hủy',
          cancelButtonText: 'Hủy bỏ',
          inputValidator: (value) => {
            if (!value) return 'Bạn phải nhập lý do hủy!'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/api/v1/outbound-documents/${id}/cancel`,
              type: 'POST',
              data: JSON.stringify({ reason: result.value }),
              success: function(res) {
                $('#modal-outbound-detail').modal('hide');
                showAlert('success', 'Thành công', res.message);
                loadOutboundDocumentsList();
              },
              error: function(xhr) {
                const err = xhr.responseJSON;
                showAlert('error', 'Thất bại', err && err.message ? err.message : 'Không thể hủy phiếu.');
              }
            });
          }
        });
      });

      $(document).on('click', '.btn-delete-outbound', function() {
        const id = $(this).data('id');
        Swal.fire({ title: 'Xác nhận xóa phiếu bản nháp này?', icon: 'warning', showCancelButton: true,
          confirmButtonColor: '#d33', confirmButtonText: 'Xóa!', cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({ url: '/api/v1/outbound-documents/' + id, type: 'DELETE',
              success: function(res) { showAlert('success', 'Thành công', res.message); loadOutboundDocumentsList(); }
            });
          }
        });
      });


      // ===================================================================
      // 18. BUSINESS LOGIC: TRANSFER ORDERS (ĐIỀU CHUYỂN KHO)
      // ===================================================================
      function loadTransferSelectors(callback) {
        $.ajax({ url: '/api/v1/warehouses?limit=200', type: 'GET', success: function(res) {
          warehousesCacheList = res.data.data;
          const selectFilter = $('#filter-transfer-warehouse-id'); selectFilter.empty().append('<option value="">-- Tất cả --</option>');
          const selectSource = $('#transfer-source-warehouse-id'); selectSource.empty().append('<option value="">-- Chọn kho xuất (nguồn) --</option>');
          const selectDest = $('#transfer-dest-warehouse-id'); selectDest.empty().append('<option value="">-- Chọn kho nhập (đích) --</option>');
          warehousesCacheList.forEach(function(w) {
            selectFilter.append(`<option value="${w.id}">${w.name}</option>`);
            selectSource.append(`<option value="${w.id}">${w.name} (${w.code})</option>`);
            selectDest.append(`<option value="${w.id}">${w.name} (${w.code})</option>`);
          });
        }});

        $.ajax({ url: '/api/v1/skus?limit=500', type: 'GET', success: function(res) {
          skusCacheList = res.data.data;
          if (callback) callback();
        }});
      }

      function loadTransferOrdersList() {
        loadTransferSelectors();
        const warehouseId = $('#filter-transfer-warehouse-id').val();
        const status = $('#filter-transfer-status').val();
        const search = $('#filter-transfer-search').val();

        let url = '/api/v1/transfer-orders?limit=100';
        if (warehouseId) url += '&warehouse_id=' + warehouseId;
        if (status) url += '&status=' + status;
        if (search) url += '&search=' + search;

        $.ajax({ url: url, type: 'GET', success: function(response) {
          const body = $('#table-transfers-body'); body.empty();
          
          if (response.data.data.length === 0) {
            body.append('<tr><td colspan="8" class="text-center text-muted py-3">Không có phiếu điều chuyển nào khớp với lọc</td></tr>');
            return;
          }

          const statusClasses = {
            1: 'badge-secondary', 2: 'badge-warning', 3: 'badge-info',
            5: 'badge-primary', 4: 'badge-success', 8: 'badge-orange', 9: 'badge-danger'
          };

          response.data.data.forEach(function(d) {
            const badgeClass = statusClasses[d.status] || 'badge-dark';
            const dateStr = d.created_at ? d.created_at.split('T')[0] : '—';
            body.append(`
              <tr>
                <td>${d.id}</td>
                <td><b class="text-primary">${d.code}</b></td>
                <td>${d.source_warehouse ? d.source_warehouse.name : 'N/A'}</td>
                <td>${d.dest_warehouse ? d.dest_warehouse.name : 'N/A'}</td>
                <td><span class="badge ${badgeClass}">${d.status_label}</span></td>
                <td>${d.creator ? d.creator.name : 'N/A'}</td>
                <td>${dateStr}</td>
                <td>
                  <button class="btn btn-primary btn-xs btn-view-transfer" data-id="${d.id}"><i class="fas fa-eye"></i> Xem</button>
                  ${d.status === 1 ? `<button class="btn btn-danger btn-xs btn-delete-transfer" data-id="${d.id}"><i class="fas fa-trash"></i> Xóa</button>` : ''}
                </td>
              </tr>
            `);
          });
        }});
      }

      $('#form-filter-transfers').on('submit', function(e) {
        e.preventDefault();
        loadTransferOrdersList();
      });

      $('#btn-reset-transfers').on('click', function() {
        $('#form-filter-transfers')[0].reset();
        loadTransferOrdersList();
      });

      function appendTransferLine() {
        const tbody = $('#table-transfer-lines tbody');
        const row = `
          <tr>
            <td>
              <select class="form-control form-control-sm transfer-line-sku" required>
                ${getSkuOptionsHTML()}
              </select>
            </td>
            <td>
              <input type="number" class="form-control form-control-sm transfer-line-qty" min="0.01" step="any" value="1" required>
            </td>
            <td>
              <input type="text" class="form-control form-control-sm transfer-line-note" placeholder="Ghi chú dòng...">
            </td>
            <td class="text-center">
              <button type="button" class="btn btn-danger btn-xs btn-transfer-remove-line"><i class="fas fa-times"></i></button>
            </td>
          </tr>
        `;
        tbody.append(row);
      }

      $('#btn-add-transfer').on('click', function() {
        $('#form-transfer')[0].reset();
        $('#transfer-id').val('');
        $('#table-transfer-lines tbody').empty();
        $('#modal-transfer-title').text('Tạo Phiếu Điều Chuyển Kho Mới');
        loadTransferSelectors(function() {
          appendTransferLine();
          $('#modal-transfer').modal('show');
        });
      });

      $('#btn-transfer-add-line').on('click', function() {
        appendTransferLine();
      });

      $(document).on('click', '.btn-transfer-remove-line', function() {
        $(this).closest('tr').remove();
      });

      $('#form-transfer').on('submit', function(e) {
        e.preventDefault();
        const sourceId = $('#transfer-source-warehouse-id').val();
        const destId = $('#transfer-dest-warehouse-id').val();
        const note = $('#transfer-note').val();
        
        if (sourceId === destId) {
          showAlert('error', 'Lỗi', 'Kho nguồn và kho đích không được trùng nhau');
          return;
        }

        const lines = [];
        let hasError = false;
        $('#table-transfer-lines tbody tr').each(function() {
          const skuId = $(this).find('.transfer-line-sku').val();
          const qty = $(this).find('.transfer-line-qty').val();
          const lineNote = $(this).find('.transfer-line-note').val();

          if (!skuId || !qty) {
            hasError = true;
            return false;
          }

          lines.push({
            sku_id: parseInt(skuId),
            quantity: parseFloat(qty),
            note: lineNote || null
          });
        });

        if (hasError || lines.length === 0) {
          showAlert('warning', 'Cảnh báo', 'Vui lòng hoàn thành ít nhất 1 dòng sản phẩm.');
          return;
        }

        const data = {
          source_warehouse_id: parseInt(sourceId),
          dest_warehouse_id: parseInt(destId),
          note: note || null,
          lines: lines
        };

        $.ajax({
          url: '/api/v1/transfer-orders',
          type: 'POST',
          data: JSON.stringify(data),
          success: function(res) {
            $('#modal-transfer').modal('hide');
            showAlert('success', 'Thành công', res.message);
            loadTransferOrdersList();
          },
          error: function(xhr) {
            const err = xhr.responseJSON;
            showAlert('error', 'Thất bại', err && err.message ? err.message : 'Không thể tạo phiếu điều chuyển');
          }
        });
      });

      $(document).on('click', '.btn-view-transfer', function() {
        const id = $(this).data('id');
        $.ajax({ url: '/api/v1/transfer-orders/' + id, type: 'GET', success: function(res) {
          const d = res.data;
          $('#detail-transfer-code').text(d.code);
          $('#detail-transfer-source-warehouse').text(d.source_warehouse ? d.source_warehouse.name : 'N/A');
          $('#detail-transfer-dest-warehouse').text(d.dest_warehouse ? d.dest_warehouse.name : 'N/A');
          $('#detail-transfer-creator').text(d.creator ? d.creator.name : 'N/A');
          $('#detail-transfer-note').text(d.note || 'Không có ghi chú');

          // Status badge
          const statusClasses = {
            1: 'badge-secondary', 2: 'badge-warning', 3: 'badge-info',
            5: 'badge-primary', 4: 'badge-success', 8: 'badge-orange', 9: 'badge-danger'
          };
          const badgeClass = statusClasses[d.status] || 'badge-dark';
          $('#detail-transfer-status-badge').html(`<span class="badge ${badgeClass} p-2">${d.status_label}</span>`);

          if (d.status === 3 || d.status === 4 || d.status === 5) {
            $('#detail-transfer-approver').text(d.approver ? d.approver.name : 'N/A');
            $('#detail-transfer-approved-at').text(d.approved_at ? d.approved_at.replace('T', ' ').substring(0, 19) : '—');
            $('#detail-transfer-approval-meta').show();
          } else {
            $('#detail-transfer-approval-meta').hide();
          }

          // Render lines (Display Received Qty if Completed or In-Transit)
          const tbody = $('#detail-transfer-lines-body'); tbody.empty();
          const showReceiveCol = (d.status === 5 || d.status === 4 || d.status === 8);
          
          if (showReceiveCol) {
            $('#th-received-qty').show();
            $('#detail-transfer-total-received-qty').show();
          } else {
            $('#th-received-qty').hide();
            $('#detail-transfer-total-received-qty').hide();
          }

          let totalQty = 0;
          let totalReceivedQty = 0;

          d.lines.forEach(function(l, idx) {
            const qty = parseFloat(l.quantity);
            const recQty = l.received_qty !== null ? parseFloat(l.received_qty) : qty;
            totalQty += qty;
            totalReceivedQty += recQty;

            let receivedColHTML = '';
            if (showReceiveCol) {
              if (d.status === 5) {
                // If In-Transit: Allow input for actual received quantities
                receivedColHTML = `<td class="text-right font-weight-bold" style="width:150px;">
                  <input type="number" class="form-control form-control-sm text-right font-weight-bold text-success detail-transfer-line-rec-qty" 
                    data-line-id="${l.id}" min="0" max="${qty * 2}" step="any" value="${qty}">
                </td>`;
              } else {
                // If Completed or Returned: Read-only display
                receivedColHTML = `<td class="text-right font-weight-bold text-success">${recQty.toLocaleString()}</td>`;
              }
            }

            tbody.append(`
              <tr>
                <td>${idx + 1}</td>
                <td><b>${l.sku ? l.sku.code : 'N/A'}</b></td>
                <td>${l.sku ? l.sku.name : 'N/A'}</td>
                <td>${l.sku ? l.sku.unit : '—'}</td>
                <td class="text-right font-weight-bold">${qty.toLocaleString()}</td>
                ${receivedColHTML}
                <td>${l.note || ''}</td>
              </tr>
            `);
          });
          $('#detail-transfer-total-qty').text(totalQty.toLocaleString());
          $('#detail-transfer-total-received-qty').text(totalReceivedQty.toLocaleString());

          // Action buttons footer logic
          const actions = $('#detail-transfer-actions-container');
          actions.empty().append('<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>');

          if (d.status === 1) {
            actions.append(`<button type="button" class="btn btn-warning btn-sm btn-transfer-action" data-id="${d.id}" data-action="submit"><i class="fas fa-paper-plane mr-1"></i> Gửi Duyệt</button>`);
          }
          if (d.status === 2) {
            actions.append(`<button type="button" class="btn btn-success btn-sm btn-transfer-action" data-id="${d.id}" data-action="approve"><i class="fas fa-check-circle mr-1"></i> Duyệt Phiếu</button>`);
            actions.append(`<button type="button" class="btn btn-danger btn-sm btn-transfer-cancel" data-id="${d.id}"><i class="fas fa-ban mr-1"></i> Từ Chối / Hủy</button>`);
          }
          if (d.status === 3) {
            actions.append(`<button type="button" class="btn btn-primary btn-sm btn-transfer-action" data-id="${d.id}" data-action="dispatch"><i class="fas fa-truck-moving mr-1"></i> Xuất Kho Đi (Vận Chuyển)</button>`);
            actions.append(`<button type="button" class="btn btn-danger btn-sm btn-transfer-cancel" data-id="${d.id}"><i class="fas fa-ban mr-1"></i> Hủy Phiếu</button>`);
          }
          if (d.status === 5) {
            // In Transit -> Show Receive (Nhận hàng) & Return (Trả lại)
            actions.append(`<button type="button" class="btn btn-success btn-sm btn-transfer-receive-submit" data-id="${d.id}"><i class="fas fa-clipboard-check mr-1"></i> Xác Nhận Nhận Hàng</button>`);
            actions.append(`<button type="button" class="btn btn-orange btn-sm text-white btn-transfer-return-submit" data-id="${d.id}"><i class="fas fa-undo-alt mr-1"></i> Trả Lại Hàng</button>`);
          }

          $('#modal-transfer-detail').modal('show');
        }});
      });

      $(document).on('click', '.btn-transfer-action', function() {
        const id = $(this).data('id');
        const action = $(this).data('action');
        
        let confirmText = "Bạn chắc chắn muốn gửi duyệt phiếu điều chuyển này?";
        if (action === 'approve') confirmText = "Phê duyệt phiếu điều chuyển này? Tồn kho khả dụng tại kho nguồn sẽ được giữ chỗ.";
        if (action === 'dispatch') confirmText = "Xác nhận xuất hàng khỏi kho đi và chuyển sang trạng thái Đang vận chuyển? Tồn kho của kho nguồn sẽ được giảm trực tiếp.";

        Swal.fire({
          title: 'Xác nhận hành động',
          text: confirmText,
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Đồng ý',
          cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/api/v1/transfer-orders/${id}/${action}`,
              type: 'POST',
              success: function(res) {
                $('#modal-transfer-detail').modal('hide');
                showAlert('success', 'Thành công', res.message);
                loadTransferOrdersList();
              },
              error: function(xhr) {
                const err = xhr.responseJSON;
                showAlert('error', 'Thất bại', err && err.message ? err.message : 'Không thể thực hiện hành động này.');
              }
            });
          }
        });
      });

      // Confirm Receive action (sends received quantities)
      $(document).on('click', '.btn-transfer-receive-submit', function() {
        const id = $(this).data('id');
        const lines = [];
        
        $('.detail-transfer-line-rec-qty').each(function() {
          lines.push({
            line_id: parseInt($(this).data('line-id')),
            received_qty: parseFloat($(this).val())
          });
        });

        Swal.fire({
          title: 'Xác nhận nhận hàng',
          text: 'Bạn xác nhận đã nhận đủ hàng điều chuyển theo số lượng đã nhập? Tồn kho của kho đích sẽ tăng lên tương ứng.',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Hủy',
          confirmButtonText: 'Xác nhận nhận hàng'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/api/v1/transfer-orders/${id}/receive`,
              type: 'POST',
              data: JSON.stringify({ lines: lines }),
              success: function(res) {
                $('#modal-transfer-detail').modal('hide');
                showAlert('success', 'Thành công', res.message);
                loadTransferOrdersList();
              },
              error: function(xhr) {
                const err = xhr.responseJSON;
                showAlert('error', 'Thất bại', err && err.message ? err.message : 'Không thể thực hiện.');
              }
            });
          }
        });
      });

      // Confirm Return action (sends return reason)
      $(document).on('click', '.btn-transfer-return-submit', function() {
        const id = $(this).data('id');
        Swal.fire({
          title: 'Trả lại hàng điều chuyển',
          text: 'Vui lòng nhập lý do trả lại hàng (hàng quay về kho nguồn):',
          input: 'text',
          inputPlaceholder: 'Nhập lý do trả lại...',
          showCancelButton: true,
          confirmButtonColor: '#fd7e14',
          confirmButtonText: 'Đồng ý trả lại',
          cancelButtonText: 'Hủy bỏ',
          inputValidator: (value) => {
            if (!value) return 'Bạn phải nhập lý do!'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/api/v1/transfer-orders/${id}/return`,
              type: 'POST',
              data: JSON.stringify({ reason: result.value }),
              success: function(res) {
                $('#modal-transfer-detail').modal('hide');
                showAlert('success', 'Thành công', res.message);
                loadTransferOrdersList();
              },
              error: function(xhr) {
                const err = xhr.responseJSON;
                showAlert('error', 'Thất bại', err && err.message ? err.message : 'Không thể trả hàng.');
              }
            });
          }
        });
      });

      $(document).on('click', '.btn-transfer-cancel', function() {
        const id = $(this).data('id');
        Swal.fire({
          title: 'Hủy phiếu điều chuyển',
          text: 'Vui lòng nhập lý do hủy phiếu:',
          input: 'text',
          inputPlaceholder: 'Lý do hủy...',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: 'Xác nhận hủy',
          cancelButtonText: 'Hủy bỏ',
          inputValidator: (value) => {
            if (!value) return 'Bạn phải nhập lý do hủy!'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/api/v1/transfer-orders/${id}/cancel`,
              type: 'POST',
              data: JSON.stringify({ reason: result.value }),
              success: function(res) {
                $('#modal-transfer-detail').modal('hide');
                showAlert('success', 'Thành công', res.message);
                loadTransferOrdersList();
              },
              error: function(xhr) {
                const err = xhr.responseJSON;
                showAlert('error', 'Thất bại', err && err.message ? err.message : 'Không thể hủy.');
              }
            });
          }
        });
      });

      $(document).on('click', '.btn-delete-transfer', function() {
        const id = $(this).data('id');
        Swal.fire({ title: 'Xác nhận xóa phiếu bản nháp này?', icon: 'warning', showCancelButton: true,
          confirmButtonColor: '#d33', confirmButtonText: 'Xóa!', cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({ url: '/api/v1/transfer-orders/' + id, type: 'DELETE',
              success: function(res) { showAlert('success', 'Thành công', res.message); loadTransferOrdersList(); }
            });
          }
        });
      });


      // ===================================================================
      // 19. BUSINESS LOGIC: INVENTORY BALANCES Tra Cứu Số Dư Tồn Kho
      // ===================================================================
      function loadInventoryBalancesList() {
        // Load Warehouses dropdown filter
        $.ajax({ url: '/api/v1/warehouses?limit=200', type: 'GET', success: function(res) {
          const select = $('#filter-balance-warehouse-id'); select.empty().append('<option value="">-- Tất cả các kho --</option>');
          res.data.data.forEach(function(w) { select.append(`<option value="${w.id}">${w.name}</option>`); });
        }});

        fetchInventoryBalances();
      }

      function fetchInventoryBalances() {
        const scope = $('#filter-balance-scope').val();
        const warehouseId = $('#filter-balance-warehouse-id').val();
        const search = $('#filter-balance-search').val();

        let url = `/api/v1/inventory/balances?limit=100&scope=${scope}`;
        if (scope === 'warehouse' && warehouseId) url += '&warehouse_id=' + warehouseId;
        if (search) url += '&search=' + search;

        $.ajax({ url: url, type: 'GET', success: function(response) {
          const body = $('#table-balances-body'); body.empty();
          const header = $('#table-balances-header');
          
          if (scope === 'system') {
            header.html(`
              <th>Mã SKU</th>
              <th>Tên Sản Phẩm</th>
              <th>ĐVT</th>
              <th class="text-right">Tồn Khả Dụng</th>
              <th class="text-right">Đang Giữ Chỗ (Reserved)</th>
              <th class="text-right">Đang Đi Đường (In-Transit)</th>
              <th class="text-right font-weight-bold text-primary">Tổng Tồn Hệ Thống</th>
            `);
          } else {
            header.html(`
              <th>Kho</th>
              <th>Mã SKU</th>
              <th>Tên Sản Phẩm</th>
              <th>ĐVT</th>
              <th class="text-right">Tồn Khả Dụng</th>
              <th class="text-right">Đang Giữ Chỗ (Reserved)</th>
              <th class="text-right">Đang Đi Đường (In-Transit)</th>
              <th class="text-right font-weight-bold text-primary">Tổng Tồn Kho</th>
            `);
          }

          const records = response.data.data || [];
          if (records.length === 0) {
            body.append(`<tr><td colspan="${scope === 'system' ? '7' : '8'}" class="text-center text-muted">Không có dữ liệu tồn kho nào</td></tr>`);
            return;
          }

          records.forEach(function(r) {
            const available = parseFloat(r.available_qty || 0);
            const reserved = parseFloat(r.reserved_qty || 0);
            const inTransit = parseFloat(r.in_transit_qty || 0);
            const total = available + reserved + inTransit;

            if (scope === 'system') {
              body.append(`
                <tr>
                  <td><b>${r.sku_code}</b></td>
                  <td>${r.sku_name}</td>
                  <td>${r.unit || '—'}</td>
                  <td class="text-right font-weight-bold">${available.toLocaleString()}</td>
                  <td class="text-right">${reserved.toLocaleString()}</td>
                  <td class="text-right">${inTransit.toLocaleString()}</td>
                  <td class="text-right font-weight-bold text-primary">${total.toLocaleString()}</td>
                </tr>
              `);
            } else {
              body.append(`
                <tr>
                  <td><span class="badge badge-info">${r.warehouse ? r.warehouse.name : 'N/A'}</span></td>
                  <td><b>${r.sku ? r.sku.code : 'N/A'}</b></td>
                  <td>${r.sku ? r.sku.name : 'N/A'}</td>
                  <td>${r.sku ? r.sku.unit : '—'}</td>
                  <td class="text-right font-weight-bold">${available.toLocaleString()}</td>
                  <td class="text-right">${reserved.toLocaleString()}</td>
                  <td class="text-right">${inTransit.toLocaleString()}</td>
                  <td class="text-right font-weight-bold text-primary">${total.toLocaleString()}</td>
                </tr>
              `);
            }
          });
        }});
      }

      $('#filter-balance-scope').on('change', function() {
        if ($(this).val() === 'system') {
          $('#filter-balance-warehouse-group').hide();
        } else {
          $('#filter-balance-warehouse-group').show();
        }
      });

      $('#form-filter-balances').on('submit', function(e) {
        e.preventDefault();
        fetchInventoryBalances();
      });

      $('#btn-reset-balances').on('click', function() {
        $('#form-filter-balances')[0].reset();
        $('#filter-balance-warehouse-group').show();
        fetchInventoryBalances();
      });


      // ===================================================================
      // 20. BUSINESS LOGIC: STOCK MOVEMENTS Lịch Sử Biến Động (Thẻ Kho)
      // ===================================================================
      function loadStockMovementsList() {
        // Load Warehouses dropdown
        $.ajax({ url: '/api/v1/warehouses?limit=200', type: 'GET', success: function(res) {
          const select = $('#filter-movement-warehouse-id'); select.empty().append('<option value="">-- Tất cả --</option>');
          res.data.data.forEach(function(w) { select.append(`<option value="${w.id}">${w.name}</option>`); });
        }});

        // Load SKUs dropdown
        $.ajax({ url: '/api/v1/skus?limit=500', type: 'GET', success: function(res) {
          const select = $('#filter-movement-sku-id'); select.empty().append('<option value="">-- Tất cả --</option>');
          res.data.data.forEach(function(s) { select.append(`<option value="${s.id}">${s.name} (${s.code})</option>`); });
        }});

        // Default dates: start of month to today
        const today = new Date().toISOString().split('T')[0];
        const firstDay = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
        $('#filter-movement-from').val(firstDay);
        $('#filter-movement-to').val(today);

        fetchStockMovements();
      }

      function fetchStockMovements() {
        const warehouseId = $('#filter-movement-warehouse-id').val();
        const skuId = $('#filter-movement-sku-id').val();
        const type = $('#filter-movement-type').val();
        const from = $('#filter-movement-from').val();
        const to = $('#filter-movement-to').val();

        let url = '/api/v1/stock-movements?limit=100';
        if (warehouseId) url += '&warehouse_id=' + warehouseId;
        if (skuId) url += '&sku_id=' + skuId;
        if (type) url += '&movement_type=' + type;
        if (from) url += '&from=' + from;
        if (to) url += '&to=' + to;

        $.ajax({ url: url, type: 'GET', success: function(response) {
          const body = $('#table-movements-body'); body.empty();
          
          const records = response.data.data || [];
          if (records.length === 0) {
            body.append('<tr><td colspan="10" class="text-center text-muted">Không có lịch sử biến động nào khớp với bộ lọc</td></tr>');
            return;
          }

          const types = {
            1: { label: 'Nhập Kho', badge: 'badge-success', prefix: '+' },
            2: { label: 'Xuất Kho', badge: 'badge-danger', prefix: '-' },
            3: { label: 'Điều Chuyển Xuất', badge: 'badge-warning', prefix: '-' },
            4: { label: 'Điều Chuyển Nhập', badge: 'badge-success', prefix: '+' },
            5: { label: 'Điều Chỉnh', badge: 'badge-info', prefix: '±' }
          };

          records.forEach(function(m) {
            const dateStr = m.created_at ? m.created_at.replace('T', ' ').substring(0, 19) : '—';
            const t = types[m.movement_type] || { label: 'Không rõ', badge: 'badge-dark', prefix: '' };
            
            const qty = parseFloat(m.quantity || 0);
            const before = m.qty_before !== null ? parseFloat(m.qty_before).toLocaleString() : '—';
            const after = m.qty_after !== null ? parseFloat(m.qty_after).toLocaleString() : '—';
            
            let docRef = '—';
            if (m.source_document_id) {
              docRef = `<code>ID: ${m.source_document_id}</code>`;
            }

            body.append(`
              <tr>
                <td>${m.id}</td>
                <td>${dateStr}</td>
                <td><span class="badge badge-light">${m.warehouse ? m.warehouse.name : 'N/A'}</span></td>
                <td><b>${m.sku ? m.sku.code : 'N/A'}</b><br><small>${m.sku ? m.sku.name : 'N/A'}</small></td>
                <td><span class="badge ${t.badge}">${t.label}</span></td>
                <td class="text-right font-weight-bold ${t.prefix === '+' ? 'text-success' : (t.prefix === '-' ? 'text-danger' : 'text-info')}">
                  ${t.prefix}${qty.toLocaleString()}
                </td>
                <td class="text-right">${before}</td>
                <td class="text-right font-weight-bold">${after}</td>
                <td>${docRef}</td>
                <td>${m.performer ? m.performer.name : 'Hệ thống'}</td>
              </tr>
            `);
          });
        }});
      }

      $('#form-filter-movements').on('submit', function(e) {
        e.preventDefault();
        fetchStockMovements();
      });

      $('#btn-reset-movements').on('click', function() {
        $('#form-filter-movements')[0].reset();
        const today = new Date().toISOString().split('T')[0];
        const firstDay = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
        $('#filter-movement-from').val(firstDay);
        $('#filter-movement-to').val(today);
        fetchStockMovements();
      });


      // ===================================================================
      // 21. BUSINESS LOGIC: REPORTS XNT (BÁO CÁO XUẤT NHẬP TỒN)
      // ===================================================================
      function loadReportsXntList() {
        // Load Warehouses dropdown
        $.ajax({ url: '/api/v1/warehouses?limit=200', type: 'GET', success: function(res) {
          const select = $('#filter-report-warehouse-id'); select.empty().append('<option value="">-- Tất cả các kho --</option>');
          res.data.data.forEach(function(w) { select.append(`<option value="${w.id}">${w.name}</option>`); });
        }});

        // Default dates: start of month to today
        const today = new Date().toISOString().split('T')[0];
        const firstDay = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
        $('#filter-report-from').val(firstDay);
        $('#filter-report-to').val(today);

        fetchReportsXnt();
      }

      function fetchReportsXnt() {
        const scope = $('#filter-report-scope').val();
        const warehouseId = $('#filter-report-warehouse-id').val();
        const from = $('#filter-report-from').val();
        const to = $('#filter-report-to').val();

        if (!from || !to) {
          showAlert('warning', 'Cảnh báo', 'Vui lòng chọn từ ngày và đến ngày');
          return;
        }

        let url = `/api/v1/reports/inventory-xnt?from=${from}&to=${to}&scope=${scope}`;
        if (scope === 'warehouse' && warehouseId) url += '&warehouse_id=' + warehouseId;

        $.ajax({ url: url, type: 'GET', success: function(response) {
          const body = $('#table-reports-xnt-body'); body.empty();
          
          const records = response.data || [];
          if (records.length === 0) {
            body.append('<tr><td colspan="11" class="text-center text-muted">Không có dữ liệu báo cáo trong kỳ</td></tr>');
            return;
          }

          records.forEach(function(r) {
            const wh = r.warehouse_name ? `<span class="badge badge-info">${r.warehouse_name}</span>` : '<span class="badge badge-secondary">Toàn hệ thống</span>';
            const opening = parseFloat(r.opening || 0);
            const nhap = parseFloat(r.nhap || 0);
            const xuat = parseFloat(r.xuat || 0);
            const dc_out = parseFloat(r.dc_out || 0);
            const dc_in = parseFloat(r.dc_in || 0);
            const adjust = parseFloat(r.adjust || 0);
            const closing = parseFloat(r.closing || 0);

            body.append(`
              <tr>
                <td>${wh}</td>
                <td><b>${r.sku_code}</b></td>
                <td>${r.sku_name}</td>
                <td>${r.unit || '—'}</td>
                <td class="text-right">${opening.toLocaleString()}</td>
                <td class="text-right text-success font-weight-bold">${nhap > 0 ? '+' : ''}${nhap.toLocaleString()}</td>
                <td class="text-right text-danger font-weight-bold">${xuat < 0 ? '' : '-'}${xuat.toLocaleString()}</td>
                <td class="text-right text-muted">${dc_out < 0 ? '' : '-'}${dc_out.toLocaleString()}</td>
                <td class="text-right text-muted">${dc_in > 0 ? '+' : ''}${dc_in.toLocaleString()}</td>
                <td class="text-right ${adjust > 0 ? 'text-success' : (adjust < 0 ? 'text-danger' : '')}">
                  ${adjust > 0 ? '+' : ''}${adjust.toLocaleString()}
                </td>
                <td class="text-right font-weight-bold text-primary">${closing.toLocaleString()}</td>
              </tr>
            `);
          });
        },
        error: function(xhr) {
          const err = xhr.responseJSON;
          showAlert('error', 'Lỗi tải báo cáo', err && err.message ? err.message : 'Khoảng thời gian không hợp lệ.');
        }});
      }

      $('#filter-report-scope').on('change', function() {
        if ($(this).val() === 'system') {
          $('#filter-report-warehouse-group').hide();
        } else {
          $('#filter-report-warehouse-group').show();
        }
      });

      $('#form-filter-reports-xnt').on('submit', function(e) {
        e.preventDefault();
        fetchReportsXnt();
      });

    });
  </script>
</body>
</html>