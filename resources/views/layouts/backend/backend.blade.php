<!DOCTYPE html>
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

    });
  </script>
</body>
</html>