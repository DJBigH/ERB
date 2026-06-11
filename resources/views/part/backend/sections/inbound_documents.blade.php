<!-- Section Inbound Documents -->
<div id="section-inbound-documents" class="spa-section">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">Quản Lý Phiếu Nhập Kho</h3>
      <div class="card-tools">
        <button class="btn btn-primary btn-sm" id="btn-add-inbound">
          <i class="fas fa-plus mr-1"></i> Tạo Phiếu Nhập Kho
        </button>
      </div>
    </div>
    
    <!-- Filter Bar -->
    <div class="card-body border-bottom">
      <form id="form-filter-inbounds" class="row">
        <div class="col-md-3">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Kho Nhập</label>
            <select id="filter-inbound-warehouse-id" class="form-control form-control-sm">
              <option value="">-- Tất cả --</option>
            </select>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Trạng thái</label>
            <select id="filter-inbound-status" class="form-control form-control-sm">
              <option value="">-- Tất cả --</option>
              <option value="1">Nháp (Draft)</option>
              <option value="2">Chờ duyệt (Pending)</option>
              <option value="3">Đã duyệt (Approved)</option>
              <option value="4">Hoàn tất (Completed)</option>
              <option value="9">Đã hủy (Cancelled)</option>
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group mb-2 mb-md-0">
            <label class="text-sm">Mã chứng từ</label>
            <input type="text" id="filter-inbound-search" class="form-control form-control-sm" placeholder="Tìm kiếm theo mã phiếu...">
          </div>
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fas fa-search mr-1"></i> Lọc</button>
          <button type="button" id="btn-reset-inbounds" class="btn btn-secondary btn-sm"><i class="fas fa-undo mr-1"></i> Reset</button>
        </div>
      </form>
    </div>

    <!-- Data Table -->
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-striped mb-0 text-sm">
          <thead>
            <tr>
              <th>ID</th>
              <th>Mã Phiếu</th>
              <th>Kho Nhập</th>
              <th>Ghi Chú</th>
              <th>Trạng Thái</th>
              <th>Người Tạo</th>
              <th>Ngày Tạo</th>
              <th>Hành Động</th>
            </tr>
          </thead>
          <tbody id="table-inbounds-body">
            <tr>
              <td colspan="8" class="text-center text-muted py-3">Đang tải dữ liệu...</td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <div class="card-footer clearfix bg-white" id="inbounds-pagination"></div>
    </div>
  </div>
</div>

<!-- Modal Add/Edit Inbound Document -->
<div class="modal fade" id="modal-inbound" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <form id="form-inbound">
        <input type="hidden" id="inbound-id">
        <div class="modal-header">
          <h5 class="modal-title font-weight-bold" id="modal-inbound-title">Tạo Phiếu Nhập Kho Mới</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Kho Nhập Hàng <span class="text-danger">*</span></label>
                <select id="inbound-dest-warehouse-id" class="form-control form-control-sm" required></select>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label>Ghi Chú Phiếu Nhập</label>
                <input type="text" id="inbound-note" class="form-control form-control-sm" placeholder="Ví dụ: Nhập hàng từ nhà cung cấp A...">
              </div>
            </div>
          </div>
          
          <h6 class="font-weight-bold border-bottom pb-2 mb-3 mt-4">Danh sách sản phẩm chi tiết</h6>
          <div class="table-responsive">
            <table class="table table-bordered table-sm text-sm" id="table-inbound-lines">
              <thead class="thead-light">
                <tr>
                  <th style="width: 35%">Sản Phẩm (SKU) <span class="text-danger">*</span></th>
                  <th style="width: 15%">Số Lượng <span class="text-danger">*</span></th>
                  <th style="width: 20%">Đơn Giá Nhập</th>
                  <th style="width: 25%">Ghi Chú Dòng</th>
                  <th style="width: 5%" class="text-center">Xóa</th>
                </tr>
              </thead>
              <tbody>
                <!-- Lines appends dynamically here -->
              </tbody>
            </table>
          </div>
          <button type="button" class="btn btn-success btn-xs mt-2" id="btn-inbound-add-line">
            <i class="fas fa-plus mr-1"></i> Thêm Sản Phẩm
          </button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary btn-sm">Lưu Bản Nháp</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal View Detail Inbound Document -->
<div class="modal fade" id="modal-inbound-detail" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold">Chi Tiết Phiếu Nhập Kho: <span id="detail-inbound-code" class="text-primary"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Top Metadata Summary -->
        <div class="row bg-light p-3 rounded mb-4">
          <div class="col-md-3">
            <p class="mb-1 text-muted">Trạng Thái:</p>
            <h5 id="detail-inbound-status-badge"></h5>
          </div>
          <div class="col-md-3">
            <p class="mb-1 text-muted">Kho Nhập Hàng:</p>
            <h6 id="detail-inbound-warehouse" class="font-weight-bold"></h6>
          </div>
          <div class="col-md-3">
            <p class="mb-1 text-muted">Người Tạo Phiếu:</p>
            <h6 id="detail-inbound-creator" class="font-weight-bold"></h6>
          </div>
          <div class="col-md-3">
            <p class="mb-1 text-muted">Ngày Lập Phiếu:</p>
            <h6 id="detail-inbound-date" class="font-weight-bold"></h6>
          </div>
          
          <div class="col-md-12 mt-3 border-top pt-2">
            <p class="mb-1 text-muted">Ghi Chú Phiếu:</p>
            <p id="detail-inbound-note" class="mb-0 text-dark italic"></p>
          </div>
          
          <!-- Approver Metadata if approved/completed -->
          <div class="col-md-12 mt-2" id="detail-inbound-approval-meta" style="display:none;">
            <span class="text-sm text-muted">
              Được phê duyệt bởi: <b id="detail-inbound-approver"></b> lúc <span id="detail-inbound-approved-at"></span>
            </span>
          </div>
        </div>
        
        <!-- Lines list -->
        <h6 class="font-weight-bold border-bottom pb-2 mb-3">Chi tiết danh sách SKU thực nhập</h6>
        <div class="table-responsive">
          <table class="table table-bordered table-sm text-sm">
            <thead class="thead-light">
              <tr>
                <th>STT</th>
                <th>Mã SKU</th>
                <th>Tên Sản Phẩm</th>
                <th>Đơn Vị Tính</th>
                <th class="text-right">Số Lượng Thực Nhập</th>
                <th class="text-right">Đơn Giá Nhập</th>
                <th class="text-right">Thành Tiền</th>
                <th>Ghi Chú</th>
              </tr>
            </thead>
            <tbody id="detail-inbound-lines-body"></tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-right">Tổng cộng:</th>
                <th class="text-right" id="detail-inbound-total-qty">0</th>
                <th></th>
                <th class="text-right" id="detail-inbound-total-amount">0</th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="modal-footer" id="detail-inbound-actions-container">
        <!-- Action buttons load dynamically based on status -->
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>
