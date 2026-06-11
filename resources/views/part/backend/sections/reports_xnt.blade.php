<!-- Section Reports XNT -->
<div id="section-reports-xnt" class="spa-section">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">Báo Cáo Xuất - Nhập - Tồn (XNT)</h3>
    </div>
    
    <!-- Filter Bar -->
    <div class="card-body border-bottom">
      <form id="form-filter-reports-xnt" class="row">
        <div class="col-md-3">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Phạm vi báo cáo</label>
            <select id="filter-report-scope" class="form-control form-control-sm">
              <option value="warehouse">Xem theo từng kho</option>
              <option value="system">Xem gộp toàn hệ thống</option>
            </select>
          </div>
        </div>
        <div class="col-md-3" id="filter-report-warehouse-group">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Chọn Kho</label>
            <select id="filter-report-warehouse-id" class="form-control form-control-sm">
              <option value="">-- Tất cả các kho --</option>
            </select>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Từ ngày <span class="text-danger">*</span></label>
            <input type="date" id="filter-report-from" class="form-control form-control-sm" required>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Đến ngày <span class="text-danger">*</span></label>
            <input type="date" id="filter-report-to" class="form-control form-control-sm" required>
          </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button type="submit" class="btn btn-primary btn-sm btn-block"><i class="fas fa-chart-bar mr-1"></i> Xem Báo Cáo</button>
        </div>
      </form>
    </div>

    <!-- Data Table -->
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-striped mb-0 text-sm">
          <thead>
            <tr>
              <th>Kho</th>
              <th>Mã SKU</th>
              <th>Tên Sản Phẩm</th>
              <th>ĐVT</th>
              <th class="text-right">Đầu Kỳ</th>
              <th class="text-right text-success">Nhập</th>
              <th class="text-right text-danger">Xuất</th>
              <th class="text-right">ĐC Xuất</th>
              <th class="text-right">ĐC Nhập</th>
              <th class="text-right">Điều Chỉnh</th>
              <th class="text-right font-weight-bold text-primary">Cuối Kỳ</th>
            </tr>
          </thead>
          <tbody id="table-reports-xnt-body">
            <tr>
              <td colspan="11" class="text-center text-muted py-3">Chọn bộ lọc ngày và nhấn Xem Báo Cáo...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
