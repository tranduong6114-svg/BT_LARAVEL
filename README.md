# HỆ THỐNG QUẢN LÝ NHÂN VIÊN - LARAVEL

## Mục tiêu
Xây dựng hệ thống quản lý nhân viên bằng Laravel, thay thế phiên bản PHP thuần trong thư mục `../staff-manager`.

## Yêu cầu kỹ thuật
- PHP 8
- MySQL 8
- Laravel Framework
- Áp dụng OOP (Controller, Model, Service, Request)
- Xử lý file CSV
- Phòng chống SQL Injection (Eloquent ORM)
- Xử lý Exception, Transaction, Rollback
- Eloquent relationships (Join, Group, Count, Max...)

## Database
- **departments**: id (PK), name, timestamps
- **positions**: id (PK), name, timestamps
- **employees**: emp_id (PK, VARCHAR), full_name, email, base_salary, actual_salary, birthday, department_id (FK), position_id (FK), timestamps

## Chức năng cần xây dựng

### Chức năng 1: Import CSV -> Insert/Update
- Đọc file CSV chứa danh sách nhân viên
- Mã NV chưa tồn tại → INSERT, đã tồn tại → UPDATE
- Validate: ngày sinh (yyyy-mm-dd), email format, họ tên (<=255 ký tự), lương cơ bản <= lương thực nhận
- Dùng Transaction để rollback nếu có lỗi

### Chức năng 2: Tính Bảo hiểm xã hội
- BHXH = Lương cơ bản × 8%, BHYT = × 1.5%, BHTN = × 1%
- **Tổng = Lương cơ bản × 10.5%**
- Export CSV: Mã NV, Họ tên, Lương cơ bản, Tổng BHXH

### Chức năng 3: Tính Thuế thu nhập cá nhân
- Thu nhập tính thuế = Lương thực nhận - 11,000,000
- Bảng thuế: <= 5M: 5%, <=10M: 10%, > 10M: 15%
- Export CSV 1: toàn bộ nhân viên
- Export CSV 2: top 3 nhân viên đóng thuế cao nhất

### Chức năng 4: Thống kê lương trung bình dưới 30 tuổi

### Chức năng 5: Danh sách Trưởng phòng và Phó phòng
- Export CSV: Mã NV, Họ tên, Phòng ban, Vị trí
