<!-- Section Stock Movements -->
<div id="section-stock-movements" class="spa-section">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">Thẻ Kho / Lịch Sử Biến Động Tồn Kho</h3>
    </div>
    
    <!-- Filter Bar -->
    <div class="card-body border-bottom">
      <form id="form-filter-movements" class="row">
        <div class="col-md-2">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Chọn Kho</label>
            <select id="filter-movement-warehouse-id" class="form-control form-control-sm">
              <option value="">-- Tất cả --</option>
            </select>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Chọn Sản Phẩm (SKU)</label>
            <select id="filter-movement-sku-id" class="form-control form-control-sm">
              <option value="">-- Tất cả --</option>
            </select>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Loại giao dịch</label>
            <select id="filter-movement-type" class="form-control form-control-sm">
              <option value="">-- Tất cả --</option>
              <option value="1">Nhập Kho</option>
              <option value="2">Xuất Kho</option>
              <option value="3">Điều Chuyển Xuất</option>
              <option value="4">Điều Chuyển Nhập</option>
              <option value="5">Điều Chỉnh</option>
            </select>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Từ ngày</label>
            <input type="date" id="filter-movement-from" class="form-control form-control-sm">
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Đến ngày</label>
            <input type="date" id="filter-movement-to" class="form-control form-control-sm">
          </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fas fa-search mr-1"></i> Lọc</button>
          <button type="button" id="btn-reset-movements" class="btn btn-secondary btn-sm"><i class="fas fa-undo mr-1"></i> Reset</button>
        </div>
      </form>
    </div>

    <!-- Data Table -->
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-striped mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Thời Gian</th>
              <th>Kho</th>
              <th>Sản Phẩm (SKU)</th>
              <th>Loại Biến Động</th>
              <th>Số Lượng</th>
              <th>Trước GD</th>
              <th>Sau GD</th>
              <th>Chứng Từ Nguồn</th>
              <th>Người Thực Hiện</th>
            </tr>
          </thead>
          <tbody id="table-movements-body">
            <tr>
              <td colspan="10" class="text-center text-muted py-3">Chọn bộ lọc và nhấn Lọc dữ liệu...</td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <div class="card-footer clearfix bg-white" id="movements-pagination"></div>
    </div>
  </div>
</div>
