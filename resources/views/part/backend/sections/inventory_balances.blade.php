<!-- Section Inventory Balances -->
<div id="section-inventory-balances" class="spa-section">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">Tra Cứu Số Dư Tồn Kho</h3>
    </div>
    
    <!-- Filter bar -->
    <div class="card-body border-bottom">
      <form id="form-filter-balances" class="row">
        <div class="col-md-3">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Chế độ xem</label>
            <select id="filter-balance-scope" class="form-control form-control-sm">
              <option value="warehouse">Xem theo từng kho</option>
              <option value="system">Xem gộp toàn hệ thống</option>
            </select>
          </div>
        </div>
        <div class="col-md-3" id="filter-balance-warehouse-group">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Chọn Kho</label>
            <select id="filter-balance-warehouse-id" class="form-control form-control-sm">
              <option value="">-- Tất cả các kho --</option>
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Tìm kiếm sản phẩm</label>
            <input type="text" id="filter-balance-search" class="form-control form-control-sm" placeholder="Nhập mã SKU hoặc tên sản phẩm...">
          </div>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fas fa-search mr-1"></i> Lọc dữ liệu</button>
          <button type="button" id="btn-reset-balances" class="btn btn-secondary btn-sm"><i class="fas fa-undo mr-1"></i> Đặt lại</button>
        </div>
      </form>
    </div>

    <!-- Data Table -->
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-striped mb-0">
          <thead>
            <tr id="table-balances-header">
              <th>Kho</th>
              <th>Mã SKU</th>
              <th>Tên Sản Phẩm</th>
              <th>ĐVT</th>
              <th>Tồn Khả Dụng</th>
              <th>Đang Giữ Chỗ (Reserved)</th>
              <th>Đang Đi Đường (In-Transit)</th>
              <th>Tổng Tồn Kho</th>
            </tr>
          </thead>
          <tbody id="table-balances-body">
            <tr>
              <td colspan="8" class="text-center text-muted py-3">Chọn bộ lọc và nhấn Lọc dữ liệu...</td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Pagination placeholder -->
      <div class="card-footer clearfix bg-white" id="balances-pagination"></div>
    </div>
  </div>
</div>
