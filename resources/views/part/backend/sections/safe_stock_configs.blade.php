<!-- Section Safe Stock Configs -->
<div id="section-safe-stock-configs" class="spa-section">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">Định Mức Tồn Kho An Toàn (Min/Max)</h3>
      <div class="card-tools">
        <button class="btn btn-primary btn-sm" id="btn-add-safe-stock">
          <i class="fas fa-plus mr-1"></i> Thiết Lập Định Mức
        </button>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-striped mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Sản Phẩm (SKU)</th>
              <th>Kho Áp Dụng</th>
              <th>Tồn Kho Tối Thiểu (Min)</th>
              <th>Tồn Kho Tối Đa (Max)</th>
              <th>Ngày Hiệu Lực</th>
              <th>Ngày Hết Hạn</th>
              <th>Ghi Chú</th>
              <th>Hành Động</th>
            </tr>
          </thead>
          <tbody id="table-safe-stock-body">
            <tr>
              <td colspan="9" class="text-center text-muted py-3">Đang tải dữ liệu...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Safe Stock -->
<div class="modal fade" id="modal-safe-stock" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="form-safe-stock">
        <input type="hidden" id="safe-stock-id">
        <div class="modal-header">
          <h5 class="modal-title font-weight-bold" id="modal-safe-stock-title">Thiết Lập Định Mức An Toàn</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group" id="safe-stock-sku-group">
            <label>Sản Phẩm (SKU)</label>
            <select id="safe-stock-sku-id" class="form-control form-control-sm" required>
              <option value="">-- Chọn sản phẩm --</option>
            </select>
          </div>
          <div class="form-group" id="safe-stock-warehouse-group">
            <label>Kho Áp Dụng</label>
            <select id="safe-stock-warehouse-id" class="form-control form-control-sm">
              <option value="">-- Áp dụng toàn hệ thống (Không chọn kho) --</option>
            </select>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label>Tồn Tối Thiểu (Min Qty)</label>
                <input type="number" id="safe-stock-min-qty" class="form-control form-control-sm" min="0" step="any">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label>Tồn Tối Đa (Max Qty)</label>
                <input type="number" id="safe-stock-max-qty" class="form-control form-control-sm" min="0" step="any">
              </div>
            </div>
          </div>
          <div class="row" id="safe-stock-dates-group">
            <div class="col-6">
              <div class="form-group">
                <label>Ngày Bắt Đầu Hiệu Lực</label>
                <input type="date" id="safe-stock-effective-from" class="form-control form-control-sm" required>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label>Ngày Hết Hạn (Tùy chọn)</label>
                <input type="date" id="safe-stock-effective-to" class="form-control form-control-sm">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Ghi Chú</label>
            <textarea id="safe-stock-note" class="form-control form-control-sm" rows="3" placeholder="Nhập ghi chú..."></textarea>
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
