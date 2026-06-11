<!-- Section Categories -->
<div id="section-categories" class="spa-section">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">Danh sách Nhóm Sản Phẩm</h3>
      <div class="card-tools">
        <button class="btn btn-primary btn-sm" id="btn-add-category">
          <i class="fas fa-plus mr-1"></i> Thêm Nhóm Sản Phẩm
        </button>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-striped mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Mã Nhóm</th>
              <th>Tên Nhóm</th>
              <th>Nhóm Cha</th>
              <th>Sắp Xếp</th>
              <th>Trạng Thái</th>
              <th>Hành Động</th>
            </tr>
          </thead>
          <tbody id="table-categories-body">
            <tr>
              <td colspan="7" class="text-center text-muted py-3">Đang tải dữ liệu...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Category -->
<div class="modal fade" id="modal-category" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="form-category">
        <input type="hidden" id="category-id">
        <div class="modal-header">
          <h5 class="modal-title font-weight-bold" id="modal-category-title">Thêm Mới Nhóm Sản Phẩm</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Mã Nhóm Sản Phẩm (Unique)</label>
            <input type="text" id="category-code" class="form-control form-control-sm" required>
          </div>
          <div class="form-group">
            <label>Tên Nhóm Sản Phẩm</label>
            <input type="text" id="category-name" class="form-control form-control-sm" required>
          </div>
          <div class="form-group">
            <label>Nhóm Cấp Trên (Cha)</label>
            <select id="category-parent-id" class="form-control form-control-sm">
              <option value="">-- Là danh mục gốc (Cấp cao nhất) --</option>
            </select>
          </div>
          <div class="form-group">
            <label>Thứ tự sắp xếp</label>
            <input type="number" id="category-sort-order" class="form-control form-control-sm" value="0">
          </div>
          <div class="form-group">
            <label>Trạng Thái</label>
            <select id="category-status" class="form-control form-control-sm">
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
