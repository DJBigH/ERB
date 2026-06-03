# TÀI LIỆU ĐẶC TẢ CHI TIẾT RESTFUL API - HỆ THỐNG QUẢN TRỊ ERB
*(Phù hợp sao chép và dán trực tiếp vào Microsoft Word)*

---

## I. THÔNG TIN CHUNG (GENERAL INFORMATION)

*   **Base URL:** `http://localhost:8000/api/v1`
*   **Định dạng dữ liệu:** JSON (UTF-8)
*   **Request Headers bắt buộc:**
    *   `Content-Type: application/json`
    *   `Accept: application/json`
    *   `Authorization: Bearer <JWT_ACCESS_TOKEN>` (Không yêu cầu với API đăng nhập)

---

## II. DANH SÁCH CHI TIẾT ENDPOINTS

---

### 1. MODULE XÁC THỰC (AUTHENTICATION)

#### 1.1. Đăng nhập hệ thống (Login)
*   **Đường dẫn (URL):** `/auth/login`
*   **Phương thức (Method):** `POST`
*   **Mô tả chức năng:** Xác thực tài khoản người dùng và cấp mã JWT token để truy cập các API khác.
*   **Danh sách tham số đầu vào (Request Body):**

| STT | Tên tham số | Kiểu dữ liệu | Bắt buộc | Mô tả | Dữ liệu mẫu |
| :--- | :--- | :---: | :---: | :--- | :--- |
| 1 | `email` | String | Có | Email tài khoản nhân viên | `admin@erb.vn` |
| 2 | `password` | String | Có | Mật khẩu tài khoản (tối thiểu 6 ký tự) | `12345678` |

*   **Dữ liệu mẫu gửi đi (JSON Request):**
```json
{
  "email": "admin@erb.vn",
  "password": "12345678"
}
```

*   **Danh sách tham số đầu ra (JSON Response - Thành công):**

| STT | Tên trường | Kiểu dữ liệu | Mô tả |
| :--- | :--- | :---: | :--- |
| 1 | `status` | String | Trạng thái phản hồi (`success`) |
| 2 | `message` | String | Thông báo thành công |
| 3 | `data.access_token` | String | Chuỗi mã hóa JWT Access Token |
| 4 | `data.token_type` | String | Loại token (Mặc định: `bearer`) |
| 5 | `data.expires_in` | Integer | Thời gian hết hạn của token (giây) |

*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
```json
{
  "status": "success",
  "message": "Đăng nhập thành công",
  "data": {
    "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

*   **Dữ liệu phản hồi mẫu khi lỗi (JSON Response - Lỗi Validation 400):**
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

---

#### 1.2. Lấy thông tin tài khoản hiện tại (Get Profile Me)
*   **Đường dẫn (URL):** `/auth/me`
*   **Phương thức (Method):** `GET`
*   **Mô tả chức năng:** Lấy thông tin hồ sơ chi tiết của nhân sự đang đăng nhập kèm công ty, chi nhánh, phòng ban, chức vụ, vai trò và toàn bộ quyền.
*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
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

---

#### 1.3. Đăng xuất hệ thống (Logout)
*   **Đường dẫn (URL):** `/auth/logout`
*   **Phương thức (Method):** `POST`
*   **Mô tả chức năng:** Vô hiệu hóa JWT Access Token hiện tại trên hệ thống.
*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
```json
{
  "status": "success",
  "message": "Đăng xuất thành công"
}
```

---

### 2. MODULE CÔNG TY (COMPANIES)
*(Yêu cầu Header Token hợp lệ & Tài khoản sở hữu quyền: `quan_ly_nhan_su`)*

#### 2.1. Lấy danh sách công ty (Index)
*   **Đường dẫn (URL):** `/companies`
*   **Phương thức (Method):** `GET`
*   **Tham số trên đường dẫn (Query Parameters - Tùy chọn):**

| STT | Tên tham số | Kiểu dữ liệu | Mô tả | Ví dụ |
| :--- | :--- | :---: | :--- | :--- |
| 1 | `page` | Integer | Số thứ tự trang cần lấy dữ liệu | `1` |
| 2 | `limit` | Integer | Số lượng bản ghi trên một trang | `15` |
| 3 | `search` | String | Từ khóa tìm kiếm theo tên, mã công ty, mã số thuế | `ERB` |
| 4 | `status` | Integer | Trạng thái hoạt động (`1`: Kích hoạt, `0`: Tạm khóa) | `1` |

*   **Hướng dẫn test lọc dữ liệu (Query Filter Examples):**
    *   **Tìm kiếm theo từ khóa:** `GET /companies?search=Tổng` (Tìm các công ty chứa chữ "Tổng")
    *   **Lọc công ty đang kích hoạt:** `GET /companies?status=1`
    *   **Lọc công ty tạm ngưng:** `GET /companies?status=0`
    *   **Kết hợp tìm kiếm và phân trang:** `GET /companies?search=ERB&status=1&limit=5&page=1`

*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
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

---

#### 2.2. Tạo mới công ty (Store)
*   **Đường dẫn (URL):** `/companies`
*   **Phương thức (Method):** `POST`
*   **Danh sách tham số đầu vào (Request Body):**

| STT | Tên tham số | Kiểu dữ liệu | Bắt buộc | Mô tả | Ví dụ |
| :--- | :--- | :---: | :---: | :--- | :--- |
| 1 | `name` | String | Có | Tên công ty đầy đủ | `Công Ty Cổ Phần Sáng Tạo` |
| 2 | `code` | String | Có | Mã định danh công ty (Duy nhất) | `CREATIVE_CORP` |
| 3 | `tax_code` | String | Có | Mã số thuế doanh nghiệp (Duy nhất) | `0109999999` |
| 4 | `phone` | String | Có | Số điện thoại liên hệ | `0901234567` |
| 5 | `email` | String | Có | Email liên hệ (Duy nhất) | `contact@creative.vn` |
| 6 | `address` | String | Không | Địa chỉ trụ sở | `Cầu Giấy, Hà Nội` |
| 7 | `status` | Integer | Không | Trạng thái (`1`: Kích hoạt, `0`: Khóa) | `1` |
| 8 | `parent_id` | Integer | Không | ID của công ty mẹ (nếu là công ty con) | `1` |

*   **Dữ liệu mẫu gửi đi (JSON Request):**
```json
{
  "name": "Công Ty Cổ Phần Sáng Tạo",
  "code": "CREATIVE_CORP",
  "tax_code": "0109999999",
  "phone": "0901234567",
  "email": "contact@creative.vn",
  "address": "Cầu Giấy, Hà Nội",
  "status": 1,
  "parent_id": null
}
```

*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 201 Created):**
```json
{
  "status": "success",
  "message": "Tạo công ty thành công",
  "data": {
    "id": 3,
    "name": "Công Ty Cổ Phần Sáng Tạo",
    "code": "CREATIVE_CORP",
    "tax_code": "0109999999",
    "phone": "0901234567",
    "email": "contact@creative.vn",
    "address": "Cầu Giấy, Hà Nội",
    "status": 1,
    "parent_id": null,
    "updated_at": "2026-06-03T09:20:00.000000Z",
    "created_at": "2026-06-03T09:20:00.000000Z"
  }
}
```

---

#### 2.3. Xem chi tiết công ty (Show)
*   **Đường dẫn (URL):** `/companies/{id}` (Ví dụ: `/companies/1`)
*   **Phương thức (Method):** `GET`
*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
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

---

#### 2.4. Cập nhật thông tin công ty (Update)
*   **Đường dẫn (URL):** `/companies/{id}` (Ví dụ: `/companies/1`)
*   **Phương thức (Method):** `PUT`
*   **Dữ liệu mẫu gửi đi (JSON Request):**
```json
{
  "name": "Tổng Công Ty ERB Cập Nhật",
  "phone": "0243999988",
  "address": "Số 1 Duy Tân, Cầu Giấy, Hà Nội"
}
```
*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
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

---

#### 2.5. Xóa công ty (Delete)
*   **Đường dẫn (URL):** `/companies/{id}` (Ví dụ: `/companies/3`)
*   **Phương thức (Method):** `DELETE`
*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
```json
{
  "status": "success",
  "message": "Xóa công ty thành công"
}
```

---

### 3. MODULE CHI NHÁNH (BRANCHES)
*(Yêu cầu Header Token hợp lệ & Tài khoản sở hữu quyền: `quan_ly_nhan_su`)*

#### 3.1. Lấy danh sách chi nhánh (Index)
*   **Đường dẫn (URL):** `/branches`
*   **Phương thức (Method):** `GET`
*   **Tham số trên đường dẫn (Query Parameters - Tùy chọn):**

| STT | Tên tham số | Kiểu dữ liệu | Mô tả | Ví dụ |
| :--- | :--- | :---: | :--- | :--- |
| 1 | `page` | Integer | Số thứ tự trang cần lấy dữ liệu | `1` |
| 2 | `limit` | Integer | Số lượng bản ghi trên một trang | `15` |
| 3 | `search` | String | Từ khóa tìm kiếm theo tên, mã chi nhánh, MST chi nhánh | `Hà Nội` |
| 4 | `company_id` | Integer | Lọc các chi nhánh thuộc một Công ty nhất định | `1` |
| 5 | `status` | Integer | Trạng thái hoạt động (`1`: Kích hoạt, `0`: Tạm ngưng) | `1` |

*   **Hướng dẫn test lọc dữ liệu (Query Filter Examples):**
    *   **Tìm kiếm theo từ khóa:** `GET /branches?search=Hải%20Phòng` (Tìm các chi nhánh có tên/mã/MST chứa "Hải Phòng")
    *   **Lọc chi nhánh theo Công ty:** `GET /branches?company_id=1` (Lọc toàn bộ chi nhánh của công ty có ID là 1)
    *   **Lọc chi nhánh hoạt động:** `GET /branches?status=1`
    *   **Kết hợp lọc, tìm kiếm và phân trang:** `GET /branches?company_id=1&status=1&search=HN&limit=10&page=1`

*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
```json
{
  "status": "success",
  "data": {
    "current_page": 1,
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
    ],
    "total": 1
  }
}
```

---

#### 3.2. Tạo mới chi nhánh (Store)
*   **Đường dẫn (URL):** `/branches`
*   **Phương thức (Method):** `POST`
*   **Danh sách tham số đầu vào (Request Body):**

| STT | Tên tham số | Kiểu dữ liệu | Bắt buộc | Mô tả | Ví dụ |
| :--- | :--- | :---: | :---: | :--- | :--- |
| 1 | `company_id` | Integer | Có | ID của công ty sở hữu chi nhánh | `1` |
| 2 | `name` | String | Có | Tên chi nhánh | `Chi Nhánh Hải Phòng` |
| 3 | `code` | String | Có | Mã chi nhánh (Duy nhất) | `CN_HP` |
| 4 | `tax_code` | String | Không | Mã số thuế chi nhánh (13 số - Duy nhất) | `0101234567-003` |
| 5 | `phone` | String | Có | Số điện thoại chi nhánh | `02253999999` |
| 6 | `email` | String | Có | Email chi nhánh (Duy nhất) | `hp@erb.vn` |
| 7 | `address` | String | Không | Địa chỉ chi nhánh | `Lê Chân, Hải Phòng` |
| 8 | `status` | Integer | Không | Trạng thái (`1`: Active, `0`: Inactive) | `1` |

*   **Dữ liệu mẫu gửi đi (JSON Request):**
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

*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 201 Created):**
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

#### 3.3. Xóa chi nhánh (Delete)
*   **Đường dẫn (URL):** `/branches/{id}` (Ví dụ: `/branches/4`)
*   **Phương thức (Method):** `DELETE`
*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
```json
{
  "status": "success",
  "message": "Xóa chi nhánh thành công"
}
```

---

### 4. MODULE PHÒNG BAN (DEPARTMENTS)
*(Yêu cầu Header Token hợp lệ & Tài khoản sở hữu quyền: `quan_ly_nhan_su`)*

#### 4.1. Lấy danh sách phòng ban (Index)
*   **Đường dẫn (URL):** `/departments`
*   **Phương thức (Method):** `GET`
*   **Tham số trên đường dẫn (Query Parameters - Tùy chọn):**

| STT | Tên tham số | Kiểu dữ liệu | Mô tả | Ví dụ |
| :--- | :--- | :---: | :--- | :--- |
| 1 | `page` | Integer | Số thứ tự trang cần lấy dữ liệu | `1` |
| 2 | `limit` | Integer | Số lượng bản ghi trên một trang | `15` |
| 3 | `search` | String | Từ khóa tìm kiếm theo tên hoặc mã phòng ban | `BGD` |
| 4 | `branch_id` | Integer | Lọc các phòng ban thuộc một Chi nhánh nhất định | `1` |
| 5 | `status` | Integer | Trạng thái hoạt động (`1`: Kích hoạt, `0`: Tạm ngưng) | `1` |

*   **Hướng dẫn test lọc dữ liệu (Query Filter Examples):**
    *   **Tìm kiếm theo từ khóa:** `GET /departments?search=Công%20Nghệ` (Tìm phòng ban tên/mã chứa "Công Nghệ")
    *   **Lọc phòng ban theo Chi nhánh:** `GET /departments?branch_id=1` (Lọc các phòng ban của chi nhánh ID là 1)
    *   **Lọc phòng ban hoạt động:** `GET /departments?status=1`
    *   **Kết hợp lọc, tìm kiếm và phân trang:** `GET /departments?branch_id=1&status=1&search=AI&limit=5&page=1`

*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
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
    ],
    "total": 1
  }
}
```

---

#### 4.2. Tạo mới phòng ban (Store)
*   **Đường dẫn (URL):** `/departments`
*   **Phương thức (Method):** `POST`
*   **Danh sách tham số đầu vào (Request Body):**

| STT | Tên tham số | Kiểu dữ liệu | Bắt buộc | Mô tả | Ví dụ |
| :--- | :--- | :---: | :---: | :--- | :--- |
| 1 | `branch_id` | Integer | Có | ID của Chi nhánh trực thuộc | `1` |
| 2 | `parent_id` | Integer | Không | ID phòng ban cấp trên (nếu là phòng ban con) | `1` |
| 3 | `name` | String | Có | Tên phòng ban | `Tổ Nghiên Cứu AI` |
| 4 | `code` | String | Có | Mã phòng ban (Duy nhất) | `TEAM_AI` |
| 5 | `description` | String | Không | Mô tả chức năng nhiệm vụ | `Trực thuộc phòng công nghệ` |
| 6 | `status` | Integer | Không | Trạng thái (`1`: Kích hoạt, `0`: Khóa) | `1` |

*   **Dữ liệu mẫu gửi đi (JSON Request):**
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

*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 201 Created):**
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

### 5. MODULE CHỨC VỤ (POSITIONS)
*(Yêu cầu Header Token hợp lệ & Tài khoản sở hữu quyền: `quan_ly_nhan_su`)*

#### 5.1. Lấy danh sách chức vụ (Index)
*   **Đường dẫn (URL):** `/positions`
*   **Phương thức (Method):** `GET`
*   **Tham số trên đường dẫn (Query Parameters - Tùy chọn):**

| STT | Tên tham số | Kiểu dữ liệu | Mô tả | Ví dụ |
| :--- | :--- | :---: | :--- | :--- |
| 1 | `page` | Integer | Số thứ tự trang cần lấy dữ liệu | `1` |
| 2 | `limit` | Integer | Số lượng bản ghi trên một trang | `15` |
| 3 | `search` | String | Từ khóa tìm kiếm theo tên chức vụ | `Trưởng` |

*   **Hướng dẫn test lọc dữ liệu (Query Filter Examples):**
    *   **Tìm kiếm chức vụ:** `GET /positions?search=Chuyên%20Viên` (Tìm các chức vụ chứa chữ "Chuyên Viên")
    *   **Tìm kiếm kết hợp phân trang:** `GET /positions?search=Trưởng&limit=10&page=1`

*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
```json
{
  "status": "success",
  "data": {
    "data": [
      {
        "id": 1,
        "name": "Giám Đốc",
        "description": "Quản lý điều hành toàn diện"
      },
      {
        "id": 2,
        "name": "Trưởng Phòng",
        "description": "Quản lý và điều hành cấp phòng ban"
      }
    ],
    "total": 2
  }
}
```

---

#### 5.2. Tạo mới chức vụ (Store)
*   **Đường dẫn (URL):** `/positions`
*   **Phương thức (Method):** `POST`
*   **Danh sách tham số đầu vào (Request Body):**

| STT | Tên tham số | Kiểu dữ liệu | Bắt buộc | Mô tả | Ví dụ |
| :--- | :--- | :---: | :---: | :--- | :--- |
| 1 | `name` | String | Có | Tên chức vụ chuyên môn | `Chuyên Viên Kỹ Thuật` |
| 2 | `description` | String | Không | Mô tả chức danh | `Chịu trách nhiệm bảo trì hệ thống` |

*   **Dữ liệu mẫu gửi đi (JSON Request):**
```json
{
  "name": "Chuyên Viên Kỹ Thuật",
  "description": "Chịu trách nhiệm bảo trì hệ thống"
}
```

*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 201 Created):**
```json
{
  "status": "success",
  "message": "Tạo chức vụ thành công",
  "data": {
    "id": 4,
    "name": "Chuyên Viên Kỹ Thuật",
    "description": "Chịu trách nhiệm bảo trì hệ thống"
  }
}
```

---

### 6. MODULE NHÂN VIÊN (USERS)
*(Yêu cầu Header Token hợp lệ & Tài khoản sở hữu quyền: `quan_ly_nhan_su`)*

#### 6.1. Lấy danh sách nhân viên (Index)
*   **Đường dẫn (URL):** `/users`
*   **Phương thức (Method):** `GET`
*   **Tham số trên đường dẫn (Query Parameters - Tùy chọn):**

| STT | Tên tham số | Kiểu dữ liệu | Mô tả | Ví dụ |
| :--- | :--- | :---: | :--- | :--- |
| 1 | `page` | Integer | Số thứ tự trang cần lấy dữ liệu | `1` |
| 2 | `limit` | Integer | Số lượng bản ghi trên một trang | `15` |
| 3 | `search` | String | Từ khóa tìm kiếm theo tên, mã, email nhân sự | `NV005` |
| 4 | `company_id` | Integer | Lọc nhân sự thuộc một Công ty nhất định | `1` |
| 5 | `branch_id` | Integer | Lọc nhân sự thuộc một Chi nhánh nhất định | `1` |
| 6 | `department_id` | Integer | Lọc nhân sự thuộc một Phòng ban nhất định | `1` |
| 7 | `status` | Integer | Trạng thái (`1`: Đang làm việc, `0`: Đã nghỉ việc) | `1` |

*   **Hướng dẫn test lọc dữ liệu (Query Filter Examples):**
    *   **Tìm kiếm theo từ khóa:** `GET /users?search=Trần%20Thị` (Tìm nhân sự tên/mã/email chứa "Trần Thị")
    *   **Lọc nhân sự theo Chi nhánh và Phòng ban:** `GET /users?branch_id=1&department_id=2`
    *   **Lọc nhân sự hoạt động:** `GET /users?status=1`
    *   **Kết hợp lọc nhiều trường và phân trang:** `GET /users?company_id=1&branch_id=1&department_id=1&status=1&search=Admin&limit=15&page=1`

*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
```json
{
  "status": "success",
  "data": {
    "current_page": 1,
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
    ],
    "total": 1
  }
}
```

---

#### 6.2. Tạo mới nhân viên (Store)
*   **Đường dẫn (URL):** `/users`
*   **Phương thức (Method):** `POST`
*   **Danh sách tham số đầu vào (Request Body):**

| STT | Tên tham số | Kiểu dữ liệu | Bắt buộc | Mô tả | Ví dụ |
| :--- | :--- | :---: | :---: | :--- | :--- |
| 1 | `company_id` | Integer | Có | ID của Công ty | `1` |
| 2 | `branch_id` | Integer | Có | ID của Chi nhánh | `1` |
| 3 | `department_id`| Integer | Có | ID của Phòng ban | `1` |
| 4 | `position_id` | Integer | Có | ID của Chức vụ chuyên môn | `1` |
| 5 | `role_id` | Integer | Có | ID của Vai trò phân quyền hệ thống | `3` |
| 6 | `code` | String | Có | Mã nhân viên (Duy nhất) | `NV005` |
| 7 | `name` | String | Có | Họ và tên đầy đủ | `Trần Thị C` |
| 8 | `email` | String | Có | Email cá nhân (Duy nhất) | `c.tran@erb.vn` |
| 9 | `phone` | String | Có | Số điện thoại liên hệ (Duy nhất) | `0912111222` |
| 10 | `password` | String | Có | Mật khẩu truy cập hệ thống | `12345678` |
| 11 | `gender` | Integer | Có | Giới tính (`0`: Nam, `1`: Nữ, `2`: Khác) | `1` |
| 12 | `birthday` | Date | Có | Ngày sinh (Định dạng: YYYY-MM-DD) | `1998-02-28` |
| 13 | `address` | String | Không | Địa chỉ thường trú | `Ba Đình, Hà Nội` |
| 14 | `status` | Integer | Không | Trạng thái (`1`: Đang làm việc, `0`: Đã nghỉ) | `1` |

*   **Dữ liệu mẫu gửi đi (JSON Request):**
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
  "password": "my_password_123",
  "gender": 1,
  "birthday": "1998-02-28",
  "address": "Ba Đình, Hà Nội",
  "status": 1
}
```

*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 201 Created):**
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

### 7. MODULE VAI TRÒ & QUYỀN HẠN (ROLES & PERMISSIONS)
*(Yêu cầu Header Token hợp lệ & Tài khoản sở hữu quyền: `quan_ly_he_thong` hoặc `quan_ly_nhan_su` tùy API)*

#### 7.1. Lấy danh sách Vai trò (Index Roles)
*   **Đường dẫn (URL):** `/roles`
*   **Phương thức (Method):** `GET`
*   **Mô tả:** Lấy danh sách toàn bộ các vai trò trên hệ thống (không yêu cầu quyền quản trị hệ thống để điền vào dropdown tạo nhân viên).
*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Super Admin",
      "permissions": [
        { "id": 1, "name": "quan_ly_he_thong" },
        { "id": 2, "name": "quan_ly_nhan_su" }
      ]
    },
    {
      "id": 2,
      "name": "Quản Lý Nhân Sự",
      "permissions": [
        { "id": 2, "name": "quan_ly_nhan_su" }
      ]
    }
  ]
}
```

---

#### 7.2. Tạo mới Vai trò (Store Role)
*   **Đường dẫn (URL):** `/roles`
*   **Phương thức (Method):** `POST`
*   **Mô tả:** Thêm mới một vai trò (Yêu cầu quyền: `quan_ly_he_thong`).
*   **Danh sách tham số đầu vào (Request Body):**

| STT | Tên tham số | Kiểu dữ liệu | Bắt buộc | Mô tả | Ví dụ |
| :--- | :--- | :---: | :---: | :--- | :--- |
| 1 | `name` | String | Có | Tên vai trò duy nhất | `Trưởng Phòng HR` |

*   **Dữ liệu mẫu gửi đi (JSON Request):**
```json
{
  "name": "Trưởng Phòng HR"
}
```
*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 201 Created):**
```json
{
  "status": "success",
  "message": "Tạo vai trò thành công",
  "data": {
    "id": 3,
    "name": "Trưởng Phòng HR",
    "updated_at": "2026-06-03T13:00:00.000000Z",
    "created_at": "2026-06-03T13:00:00.000000Z"
  }
}
```

---

#### 7.3. Xem chi tiết Vai trò (Show Role)
*   **Đường dẫn (URL):** `/roles/{id}` (Ví dụ: `/roles/1`)
*   **Phương thức (Method):** `GET`
*   **Mô tả:** Lấy thông tin chi tiết một vai trò và các quyền tương ứng đang có (Yêu cầu quyền: `quan_ly_he_thong`).
*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "Super Admin",
    "permissions": [
      { "id": 1, "name": "quan_ly_he_thong" },
      { "id": 2, "name": "quan_ly_nhan_su" }
    ]
  }
}
```

---

#### 7.4. Xóa Vai trò (Delete Role)
*   **Đường dẫn (URL):** `/roles/{id}` (Ví dụ: `/roles/3`)
*   **Phương thức (Method):** `DELETE`
*   **Mô tả:** Xóa vai trò khỏi hệ thống (Yêu cầu quyền: `quan_ly_he_thong`).
*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
```json
{
  "status": "success",
  "message": "Xóa vai trò thành công"
}
```

---

#### 7.5. Lấy danh sách toàn bộ quyền hệ thống có sẵn (Get Permissions)
*   **Đường dẫn (URL):** `/permissions`
*   **Phương thức (Method):** `GET`
*   **Mô tả:** Lấy danh sách tất cả các quyền hệ thống hỗ trợ (Yêu cầu quyền: `quan_ly_he_thong`).
*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
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

---

#### 7.6. Gán (Đồng bộ) danh sách quyền cho một Vai trò (Assign Permissions)
*   **Đường dẫn (URL):** `/roles/{id}/permissions` (Ví dụ: `/roles/2/permissions`)
*   **Phương thức (Method):** `POST`
*   **Mô tả:** Cập nhật đồng bộ các quyền hệ thống cho vai trò (Yêu cầu quyền: `quan_ly_he_thong`).
*   **Danh sách tham số đầu vào (Request Body):**

| STT | Tên tham số | Kiểu dữ liệu | Bắt buộc | Mô tả | Ví dụ |
| :--- | :--- | :---: | :---: | :--- | :--- |
| 1 | `permissions_id`| Array (Integer) | Có | Mảng chứa các ID quyền cần gán | `[2, 3]` |

*   **Dữ liệu mẫu gửi đi (JSON Request):**
```json
{
  "permissions_id": [2, 3]
}
```

*   **Dữ liệu phản hồi mẫu (JSON Response - Thành công 200 OK):**
```json
{
  "status": "success",
  "message": "Gán quyền cho vai trò thành công"
}
```

---

## III. CHI TIẾT CÁC LỖI HỆ THỐNG THƯỜNG GẶP (ERROR CODES)

### 1. Lỗi phân quyền API (403 Forbidden)
Xảy ra khi người dùng đã đăng nhập hợp lệ nhưng vai trò của tài khoản này không có quyền truy cập vào chức năng/API tương ứng.
*   **HTTP Status Code:** `403 Forbidden`
*   **Định dạng JSON lỗi:**
```json
{
  "status": "error",
  "message": "Bạn không có quyền thực hiện hành động này (Yêu cầu quyền: quan_ly_he_thong)"
}
```

### 2. Lỗi chưa xác thực (401 Unauthorized)
Xảy ra khi người dùng chưa đăng nhập hoặc Token JWT gửi lên trong Header bị hết hạn hoặc không hợp lệ.
*   **HTTP Status Code:** `401 Unauthorized`
*   **Định dạng JSON lỗi:**
```json
{
  "status": "error",
  "message": "Chưa xác thực"
}
```

### 3. Lỗi dữ liệu đầu vào không hợp lệ (400 Bad Request / Validation)
Xảy ra khi dữ liệu gửi lên bị thiếu các trường bắt buộc, sai định dạng (ví dụ sai định dạng Email, chuỗi quá dài) hoặc bị trùng các trường yêu cầu Duy nhất (`unique`) như mã, email, mã số thuế.
*   **HTTP Status Code:** `400 Bad Request`
*   **Định dạng JSON lỗi:**
```json
{
  "status": "error",
  "message": "Email đã tồn tại.",
  "errors": {
    "email": [
      "Email đã tồn tại."
    ]
  }
}
```

### 4. Lỗi dữ liệu không tồn tại (404 Not Found)
Xảy ra khi ID truyền lên trên đường dẫn URL (ví dụ: `/companies/{id}`, `/users/{id}`) không tồn tại trong cơ sở dữ liệu.
*   **HTTP Status Code:** `404 Not Found`
*   **Định dạng JSON lỗi:**
```json
{
  "status": "error",
  "message": "Không tìm thấy chi nhánh"
}
```
