<!-- Section Suppliers -->
<div id="section-suppliers" class="spa-section">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">Danh sách Nhà Cung Cấp</h3>
      <div class="card-tools">
        <button class="btn btn-primary btn-sm" id="btn-add-supplier">
          <i class="fas fa-plus mr-1"></i> Thêm Nhà Cung Cấp
        </button>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-striped mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Mã Nhà Cung Cấp</th>
              <th>Tên Nhà Cung Cấp</th>
              <th>Số Điện Thoại</th>
              <th>Địa Chỉ</th>
              <th>Trạng Thái</th>
              <th>Hành Động</th>
            </tr>
          </thead>
          <tbody id="table-suppliers-body">
            <tr>
              <td colspan="7" class="text-center text-muted py-3">Đang tải dữ liệu...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Supplier -->
<div class="modal fade" id="modal-supplier" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="form-supplier">
        <input type="hidden" id="supplier-id">
        <div class="modal-header">
          <h5 class="modal-title font-weight-bold" id="modal-supplier-title">Thêm Mới Nhà Cung Cấp</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Mã Nhà Cung Cấp (Unique)</label>
            <input type="text" id="supplier-code" class="form-control form-control-sm" required>
          </div>
          <div class="form-group">
            <label>Tên Nhà Cung Cấp</label>
            <input type="text" id="supplier-name" class="form-control form-control-sm" required>
          </div>
          <div class="form-group">
            <label>Số Điện Thoại</label>
            <input type="text" id="supplier-phone" class="form-control form-control-sm">
          </div>
          <div class="form-group">
            <label>Địa Chỉ</label>
            <input type="text" id="supplier-address" class="form-control form-control-sm">
          </div>
          <div class="form-group">
            <label>Trạng Thái</label>
            <select id="supplier-status" class="form-control form-control-sm">
              <option value="1">Kích Hoạt (Active)</option>
              <option value="0">Tạm Ngưng (Inactive)</option>
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
