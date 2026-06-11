<!-- Section Warehouse Scopes -->
<div id="section-warehouse-scopes" class="spa-section">
  <div class="row">
    <!-- Left column: Add Assignment Form -->
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title font-weight-bold">Gán Quyền Kho</h3>
        </div>
        <div class="card-body">
          <form id="form-warehouse-scope">
            <div class="form-group">
              <label>Chọn Nhân Viên</label>
              <select id="scope-user-id" class="form-control form-control-sm" required>
                <option value="">-- Đang tải danh sách nhân viên --</option>
              </select>
            </div>
            
            <div class="form-group">
              <label>Chọn Kho Được Phép Truy Cập</label>
              <div id="scope-warehouse-checkboxes" style="max-height: 250px; overflow-y: auto; border: 1px solid #ced4da; padding: 10px; border-radius: 4px;">
                <!-- Warehouses checklist loads dynamically -->
                <p class="text-muted text-sm mb-0">Đang tải danh sách kho...</p>
              </div>
            </div>
            
            <button class="btn btn-primary btn-sm btn-block" type="submit">
              <i class="fas fa-save mr-1"></i> Gán Quyền Kho
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Right column: List of current assignments -->
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title font-weight-bold">Danh sách Phân Quyền Kho</h3>
          <div class="card-tools">
            <div class="input-group input-group-sm" style="width: 200px;">
              <select id="filter-scope-user-id" class="form-control form-control-sm">
                <option value="">-- Lọc theo nhân viên --</option>
              </select>
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nhân Viên</th>
                  <th>Kho Được Truy Cập</th>
                  <th>Hành Động</th>
                </tr>
              </thead>
              <tbody id="table-scopes-body">
                <tr>
                  <td colspan="4" class="text-center text-muted py-3">Đang tải dữ liệu...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
