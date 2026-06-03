# 📖 Tài liệu hướng dẫn sử dụng RESTful API - Hệ thống ERB

Tất cả các API endpoint dưới đây sử dụng định dạng trao đổi dữ liệu **JSON**.
- **Base URL:** `http://localhost:8000/api/v1`
- **Headers bắt buộc (Các API cần bảo mật):**
  - `Content-Type: application/json`
  - `Accept: application/json`
  - `Authorization: Bearer <JWT_ACCESS_TOKEN>`

---

## 🔑 1. Xác thực hệ thống (Authentication)

### 1.1 Đăng nhập (Login)
- **Đường dẫn:** `/auth/login`
- **Phương thức:** `POST`
- **Bảo mật:** Không
- **Đầu vào (Request Body):**
```json
{
  "email": "admin@erb.vn",
  "password": "12345678"
}
```
- **Đầu ra thành công (200 OK):**
```json
{
  "status": "success",
  "message": "Đăng nhập thành công",
  "data": {
    "access_token": "eyJhbGciOiJIUzI1NiIsIn...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```
- **Đầu ra thất bại (400 Bad Request - Thiếu thông tin):**
```json
{
  "status": "error",
  "message": "Email là bắt buộc.",
  "errors": {
    "email": [
      "Email là bắt buộc."
    ]
  }
}
```
- **Đầu ra thất bại (401 Unauthorized - Sai tài khoản / mật khẩu):**
```json
{
  "status": "error",
  "message": "Tài khoản hoặc mật khẩu không chính xác"
}
```

### 1.2 Thông tin cá nhân (Profile Me)
- **Đường dẫn:** `/auth/me`
- **Phương thức:** `GET`
- **Bảo mật:** Có
- **Đầu ra thành công (200 OK):**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "company_id": 1,
    "branch_id": 1,
    "department_id": 1,
    "position_id": 1,
    "role_id": 1,
    "code": "ADMIN01",
    "name": "Super Admin",
    "email": "admin@erb.vn",
    "phone": "0987654321",
    "gender": 0,
    "birthday": "1995-10-15T00:00:00.000000Z",
    "address": "Tòa nhà ERB, Cầu Giấy, Hà Nội",
    "status": 1,
    "company": {
      "id": 1,
      "name": "Tổng Công Ty ERB",
      "code": "ERB_CORP",
      "tax_code": "0101234567"
    },
    "branch": {
      "id": 1,
      "name": "Chi Nhánh Hà Nội",
      "code": "CN_HN",
      "tax_code": "0101234567-001"
    },
    "department": {
      "id": 1,
      "name": "Ban Giám Đốc",
      "code": "BGD"
    },
    "position": {
      "id": 1,
      "name": "Giám Đốc"
    },
    "role": {
      "id": 1,
      "name": "Super Admin",
      "permissions": [
        { "id": 1, "name": "quan_ly_he_thong" },
        { "id": 2, "name": "quan_ly_nhan_su" },
        { "id": 3, "name": "xem_bao_cao" }
      ]
    }
  }
}
```

### 1.3 Đăng xuất (Logout)
- **Đường dẫn:** `/auth/logout`
- **Phương thức:** `POST`
- **Bảo mật:** Có
- **Đầu ra thành công (200 OK):**
```json
{
  "status": "success",
  "message": "Đăng xuất thành công"
}
```

---

## 🏢 2. Quản lý Công ty (Companies)

### 2.1 Lấy danh sách công ty
- **Đường dẫn:** `/companies`
- **Phương thức:** `GET`
- **Bảo mật:** Có (Quyền: `quan_ly_nhan_su`)
- **Query Parameters (Tùy chọn):**
  - `page`: Số trang (Mặc định: `1`)
  - `limit`: Số bản ghi mỗi trang (Mặc định: `15`)
  - `search`: Từ khóa tìm kiếm theo Tên, Mã, hoặc Mã số thuế
  - `status`: Lọc theo trạng thái (`1`: Kích hoạt, `0`: Tạm ngưng)
- **Đầu ra thành công (200 OK):**
```json
{
  "status": "success",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "Tổng Công Ty ERB",
        "code": "ERB_CORP",
        "tax_code": "0101234567",
        "phone": "0243999999",
        "email": "info@erb.vn",
        "address": "Tòa nhà ERB, Cầu Giấy, Hà Nội",
        "status": 1,
        "parent_id": null,
        "parent": null
      },
      {
        "id": 2,
        "name": "Công Ty Con ERB Tech",
        "code": "ERB_TECH",
        "tax_code": "0101234568",
        "phone": "0243888888",
        "email": "tech@erb.vn",
        "address": "Cầu Giấy, Hà Nội",
        "status": 1,
        "parent_id": 1,
        "parent": {
          "id": 1,
          "name": "Tổng Công Ty ERB",
          "code": "ERB_CORP"
        }
      }
    ],
    "total": 2
  }
}
```

### 2.2 Tạo mới công ty
- **Đường dẫn:** `/companies`
- **Phương thức:** `POST`
- **Bảo mật:** Có (Quyền: `quan_ly_nhan_su`)
- **Đầu vào (Request Body):**
```json
{
  "name": "Công Ty Cổ Phần Sáng Tạo Mới",
  "code": "INNO_CORP",
  "tax_code": "0109999999",
  "phone": "0901234567",
  "email": "contact@inno.vn",
  "address": "Cầu Giấy, Hà Nội",
  "status": 1,
  "parent_id": null
}
```
- **Đầu ra thành công (201 Created):**
```json
{
  "status": "success",
  "message": "Tạo công ty thành công",
  "data": {
    "id": 3,
    "name": "Công Ty Cổ Phần Sáng Tạo Mới",
    "code": "INNO_CORP",
    "tax_code": "0109999999",
    "phone": "0901234567",
    "email": "contact@inno.vn",
    "address": "Cầu Giấy, Hà Nội",
    "status": 1,
    "parent_id": null,
    "updated_at": "2026-06-03T02:20:00.000000Z",
    "created_at": "2026-06-03T02:20:00.000000Z"
  }
}
```

### 2.3 Xem chi tiết một công ty
- **Đường dẫn:** `/companies/{id}` (Ví dụ: `/companies/1`)
- **Phương thức:** `GET`
- **Bảo mật:** Có (Quyền: `quan_ly_nhan_su`)
- **Đầu ra thành công (200 OK):**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "Tổng Công Ty ERB",
    "code": "ERB_CORP",
    "tax_code": "0101234567",
    "phone": "0243999999",
    "email": "info@erb.vn",
    "address": "Tòa nhà ERB, Cầu Giấy, Hà Nội",
    "status": 1,
    "parent_id": null,
    "branches": [
      {
        "id": 1,
        "company_id": 1,
        "name": "Chi Nhánh Hà Nội",
        "code": "CN_HN"
      }
    ]
  }
}
```

### 2.4 Cập nhật thông tin công ty
- **Đường dẫn:** `/companies/{id}` (Ví dụ: `/companies/1`)
- **Phương thức:** `PUT`
- **Bảo mật:** Có (Quyền: `quan_ly_nhan_su`)
- **Đầu vào (Request Body):**
```json
{
  "name": "Tổng Công Ty ERB Cập Nhật",
  "phone": "0243999988",
  "address": "Số 1 Duy Tân, Cầu Giấy, Hà Nội"
}
```
- **Đầu ra thành công (200 OK):**
```json
{
  "status": "success",
  "message": "Cập nhật công ty thành công",
  "data": {
    "id": 1,
    "name": "Tổng Công Ty ERB Cập Nhật",
    "code": "ERB_CORP",
    "tax_code": "0101234567",
    "phone": "0243999988",
    "email": "info@erb.vn",
    "address": "Số 1 Duy Tân, Cầu Giấy, Hà Nội",
    "status": 1,
    "parent_id": null
  }
}
```

### 2.5 Xóa một công ty
- **Đường dẫn:** `/companies/{id}` (Ví dụ: `/companies/3`)
- **Phương thức:** `DELETE`
- **Bảo mật:** Có (Quyền: `quan_ly_nhan_su`)
- **Đầu ra thành công (200 OK):**
```json
{
  "status": "success",
  "message": "Xóa công ty thành công"
}
```

---

## 🌿 3. Quản lý Chi nhánh (Branches)

### 3.1 Lấy danh sách chi nhánh
- **Đường dẫn:** `/branches`
- **Phương thức:** `GET`
- **Bảo mật:** Có (Quyền: `quan_ly_nhan_su`)
- **Query Parameters (Tùy chọn):**
  - `page`, `limit`, `search` (Tìm theo Tên, Mã, Mã số thuế chi nhánh)
  - `company_id`: Lọc chi nhánh thuộc công ty cụ thể
- **Đầu ra thành công (200 OK):**
```json
{
  "status": "success",
  "data": {
    "data": [
      {
        "id": 1,
        "company_id": 1,
        "name": "Chi Nhánh Hà Nội",
        "code": "CN_HN",
        "tax_code": "0101234567-001",
        "phone": "0243999111",
        "email": "hn@erb.vn",
        "address": "Cầu Giấy, Hà Nội",
        "status": 1,
        "company": {
          "id": 1,
          "name": "Tổng Công Ty ERB"
        }
      }
    ]
  }
}
```

### 3.2 Tạo mới chi nhánh
- **Đường dẫn:** `/branches`
- **Phương thức:** `POST`
- **Bảo mật:** Có (Quyền: `quan_ly_nhan_su`)
- **Đầu vào (Request Body):**
```json
{
  "company_id": 1,
  "name": "Chi Nhánh Hải Phòng",
  "code": "CN_HP",
  "tax_code": "0101234567-003",
  "phone": "02253999999",
  "email": "hp@erb.vn",
  "address": "Lê Chân, Hải Phòng",
  "status": 1
}
```
- **Đầu ra thành công (201 Created):**
```json
{
  "status": "success",
  "message": "Tạo chi nhánh thành công",
  "data": {
    "id": 4,
    "company_id": 1,
    "name": "Chi Nhánh Hải Phòng",
    "code": "CN_HP",
    "tax_code": "0101234567-003",
    "phone": "02253999999",
    "email": "hp@erb.vn",
    "address": "Lê Chân, Hải Phòng",
    "status": 1
  }
}
```

---

## 📂 4. Quản lý Phòng ban (Departments)

### 4.1 Lấy danh sách phòng ban
- **Đường dẫn:** `/departments`
- **Phương thức:** `GET`
- **Bảo mật:** Có (Quyền: `quan_ly_nhan_su`)
- **Đầu ra thành công (200 OK):**
```json
{
  "status": "success",
  "data": {
    "data": [
      {
        "id": 1,
        "branch_id": 1,
        "parent_id": null,
        "name": "Ban Giám Đốc",
        "code": "BGD",
        "description": "Ban điều hành công ty",
        "status": 1,
        "branch": {
          "id": 1,
          "name": "Chi Nhánh Hà Nội"
        },
        "parent": null
      }
    ]
  }
}
```

### 4.2 Tạo phòng ban mới
- **Đường dẫn:** `/departments`
- **Phương thức:** `POST`
- **Bảo mật:** Có (Quyền: `quan_ly_nhan_su`)
- **Đầu vào (Request Body - Tạo phòng ban con):**
```json
{
  "branch_id": 1,
  "parent_id": 1, 
  "name": "Tổ Nghiên Cứu AI",
  "code": "TEAM_AI",
  "description": "Trực thuộc phòng công nghệ",
  "status": 1
}
```
- **Đầu ra thành công (201 Created):**
```json
{
  "status": "success",
  "message": "Tạo phòng ban thành công",
  "data": {
    "id": 5,
    "branch_id": 1,
    "parent_id": 1,
    "name": "Tổ Nghiên Cứu AI",
    "code": "TEAM_AI",
    "description": "Trực thuộc phòng công nghệ",
    "status": 1
  }
}
```

---

## 👥 5. Quản lý Nhân viên (Users)

### 5.1 Lấy danh sách nhân viên
- **Đường dẫn:** `/users`
- **Phương thức:** `GET`
- **Bảo mật:** Có (Quyền: `quan_ly_nhan_su`)
- **Query Parameters (Tùy chọn):**
  - `search`: Tìm theo Tên, Mã nhân viên, Email, Số điện thoại
  - `company_id`, `branch_id`, `department_id`, `role_id`
- **Đầu ra thành công (200 OK):**
```json
{
  "status": "success",
  "data": {
    "data": [
      {
        "id": 1,
        "company_id": 1,
        "branch_id": 1,
        "department_id": 1,
        "position_id": 1,
        "role_id": 1,
        "code": "ADMIN01",
        "name": "Super Admin",
        "email": "admin@erb.vn",
        "phone": "0987654321",
        "gender": 0,
        "birthday": "1995-10-15",
        "address": "Hà Nội",
        "status": 1,
        "company": { "name": "Tổng Công Ty ERB" },
        "branch": { "name": "Chi Nhánh Hà Nội" },
        "department": { "name": "Ban Giám Đốc" },
        "position": { "name": "Giám Đốc" },
        "role": { "name": "Super Admin" }
      }
    ]
  }
}
```

### 5.2 Tạo mới nhân viên
- **Đường dẫn:** `/users`
- **Phương thức:** `POST`
- **Bảo mật:** Có (Quyền: `quan_ly_nhan_su`)
- **Đầu vào (Request Body):**
```json
{
  "company_id": 1,
  "branch_id": 1,
  "department_id": 1,
  "position_id": 1,
  "role_id": 3,
  "code": "NV005",
  "name": "Trần Thị C",
  "email": "c.tran@erb.vn",
  "phone": "0912111222",
  "password": "hashed_password_or_raw_12345678",
  "gender": 1,
  "birthday": "1998-02-28",
  "address": "Ba Đình, Hà Nội",
  "status": 1
}
```
- **Đầu ra thành công (201 Created):**
```json
{
  "status": "success",
  "message": "Tạo nhân viên thành công",
  "data": {
    "id": 3,
    "name": "Trần Thị C",
    "email": "c.tran@erb.vn",
    "code": "NV005",
    "role_id": 3
  }
}
```

---

## 🔐 6. Phân quyền & Vai trò (Roles & Permissions)

### 6.1 Lấy danh sách vai trò
- **Đường dẫn:** `/roles`
- **Phương thức:** `GET`
- **Bảo mật:** Có (Tất cả tài khoản đăng nhập đều có quyền xem danh sách vai trò để phục vụ Dropdown nhân viên)
- **Đầu ra thành công (200 OK):**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Super Admin"
    },
    {
      "id": 2,
      "name": "Quản Lý"
    },
    {
      "id": 3,
      "name": "Nhân Viên"
    }
  ]
}
```

### 6.2 Lấy chi tiết một vai trò cùng danh sách quyền hiện tại
- **Đường dẫn:** `/roles/{id}` (Ví dụ: `/roles/2`)
- **Phương thức:** `GET`
- **Bảo mật:** Có (Quyền: `quan_ly_he_thong`)
- **Đầu ra thành công (200 OK):**
```json
{
  "status": "success",
  "data": {
    "id": 2,
    "name": "Quản Lý",
    "permissions": [
      {
        "id": 2,
        "name": "quan_ly_nhan_su"
      },
      {
        "id": 3,
        "name": "xem_bao_cao"
      }
    ]
  }
}
```

### 6.3 Tạo mới vai trò
- **Đường dẫn:** `/roles`
- **Phương thức:** `POST`
- **Bảo mật:** Có (Quyền: `quan_ly_he_thong`)
- **Đầu vào (Request Body):**
```json
{
  "name": "Trưởng Bộ Phận"
}
```
- **Đầu ra thành công (201 Created):**
```json
{
  "status": "success",
  "message": "Tạo vai trò thành công",
  "data": {
    "id": 4,
    "name": "Trưởng Bộ Phận"
  }
}
```

### 6.4 Lấy danh sách tất cả các Quyền hệ thống có sẵn
- **Đường dẫn:** `/permissions`
- **Phương thức:** `GET`
- **Bảo mật:** Có (Quyền: `quan_ly_he_thong`)
- **Đầu ra thành công (200 OK):**
```json
{
  "status": "success",
  "data": [
    { "id": 1, "name": "quan_ly_he_thong" },
    { "id": 2, "name": "quan_ly_nhan_su" },
    { "id": 3, "name": "xem_bao_cao" }
  ]
}
```

### 6.5 Gán (Đồng bộ) Quyền hạn cho một Vai trò
- **Đường dẫn:** `/roles/{id}/permissions` (Ví dụ: `/roles/2/permissions`)
- **Phương thức:** `POST`
- **Bảo mật:** Có (Quyền: `quan_ly_he_thong`)
- **Đầu vào (Request Body):**
```json
{
  "permissions_id": [2, 3]
}
```
- **Đầu ra thành công (200 OK):**
```json
{
  "status": "success",
  "message": "Gán quyền cho vai trò thành công"
}
```

---

## 🚫 7. Phản hồi lỗi Phân quyền (RBAC Errors)

Khi gọi bất kỳ API nào mà tài khoản đăng nhập không sở hữu quyền tương ứng:
- **Mã phản hồi HTTP:** `403 Forbidden`
- **Đầu ra JSON:**
```json
{
  "status": "error",
  "message": "Bạn không có quyền thực hiện hành động này (Yêu cầu quyền: quan_ly_he_thong)"
}
```
