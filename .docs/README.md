# Hệ thống Tuyển Dụng Việc Làm

Dự án hệ thống tuyển dụng việc làm được xây dựng bằng Laravel 12 và Bootstrap 5.

## Yêu cầu hệ thống

- PHP >= 8.2
- MySQL >= 5.7
- Composer
- XAMPP hoặc WAMP (cho môi trường phát triển Windows)

## Cài đặt

### 1. Clone dự án

```bash
cd d:\xampp\htdocs\QLCV\work
```

### 2. Cài đặt dependencies

```bash
composer install
```

### 3. Cấu hình môi trường

Sao chép file `.env.example` thành `.env`:

```bash
copy .env.example .env
```

Cập nhật thông tin database trong file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_recruitment
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Tạo database

Tạo database mới tên `job_recruitment` trong MySQL:

```sql
CREATE DATABASE job_recruitment CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Chạy migrations và seeders

```bash
php artisan migrate --seed
```

Lệnh này sẽ:
- Tạo tất cả các bảng trong database
- Tạo dữ liệu mẫu cho categories, locations, skills
- Tạo 3 tài khoản test:
  - Admin: `admin@example.com` / `password`
  - Employer: `employer@example.com` / `password`
  - Candidate: `candidate@example.com` / `password`

### 7. Tạo symbolic link cho storage

```bash
php artisan storage:link
```

### 8. Chạy development server

```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## Cấu trúc dự án

### Models

- **User**: Người dùng (admin, employer, candidate)
- **Category**: Danh mục ngành nghề
- **Location**: Địa điểm (tỉnh/thành phố)
- **Company**: Công ty (thuộc về employer)
- **Job**: Tin tuyển dụng
- **Skill**: Kỹ năng
- **Application**: Đơn ứng tuyển
- **SavedJob**: Việc làm đã lưu (bookmark)

### Controllers

#### Guest (Khách)
- `HomeController`: Trang chủ
- `JobController`: Tìm kiếm và xem chi tiết việc làm

#### Auth
- `LoginController`: Đăng nhập
- `RegisterController`: Đăng ký

#### Candidate (Ứng viên)
- `DashboardController`: Dashboard ứng viên
- (Sẽ thêm các controller khác)

#### Employer (Nhà tuyển dụng)
- `DashboardController`: Dashboard nhà tuyển dụng
- (Sẽ thêm các controller khác)

#### Admin (Quản trị viên)
- `DashboardController`: Dashboard admin
- (Sẽ thêm các controller khác)

### Views

#### Layouts
- `guest.blade.php`: Layout cho khách
- `candidate.blade.php`: Layout cho ứng viên
- `employer.blade.php`: Layout cho nhà tuyển dụng
- `admin.blade.php`: Layout cho admin

#### Views chính
- `guest/home.blade.php`: Trang chủ
- `admin/dashboard.blade.php`: Dashboard admin
- `candidate/dashboard.blade.php`: Dashboard ứng viên
- `employer/dashboard.blade.php`: Dashboard nhà tuyển dụng

### Routes

Routes được tổ chức theo các nhóm:

- **Guest routes**: `/` (trang chủ), `/jobs/search`, `/jobs/{slug}`
- **Auth routes**: `/login`, `/register`, `/logout`
- **Candidate routes**: `/candidate/*` (yêu cầu đăng nhập)
- **Employer routes**: `/employer/*` (yêu cầu đăng nhập)
- **Admin routes**: `/admin/*` (yêu cầu đăng nhập)

## Database Schema

### Bảng users
- Lưu thông tin người dùng
- Role: admin, employer, candidate
- Quan hệ: 1-1 với Company (employer), 1-n với Applications (candidate)

### Bảng companies
- Thông tin công ty của employer
- Quan hệ: n-1 với User, 1-n với Jobs

### Bảng jobs
- Tin tuyển dụng
- Trạng thái: pending (chờ duyệt), approved (đã duyệt), closed (đã đóng)
- Quan hệ: n-1 với Company, Category, Location; n-n với Skills

### Bảng applications
- Đơn ứng tuyển
- Trạng thái: pending, reviewing, interview, hired, rejected
- Quan hệ: n-1 với User, Job

### Bảng saved_jobs
- Bảng trung gian: User n-n Job (việc làm đã lưu)

### Các bảng khác
- `categories`: Danh mục ngành nghề
- `locations`: Địa điểm
- `skills`: Kỹ năng
- `job_skill`: Bảng trung gian Job n-n Skill
- `skill_user`: Bảng trung gian Skill n-n User

## Chức năng chính

### Phân hệ Khách (Guest)
- [x] Xem trang chủ với việc làm nổi bật, mới nhất
- [x] Tìm kiếm việc làm (theo từ khóa, địa điểm, ngành nghề)
- [x] Xem chi tiết việc làm
- [ ] Đăng ký/Đăng nhập

### Phân hệ Ứng viên (Candidate)
- [x] Dashboard với thống kê
- [ ] Quản lý hồ sơ cá nhân
- [ ] Upload/Quản lý CV
- [ ] Ứng tuyển việc làm
- [ ] Xem trạng thái hồ sơ
- [ ] Lưu việc làm yêu thích

### Phân hệ Nhà tuyển dụng (Employer)
- [x] Dashboard với thống kê
- [ ] Quản lý thông tin công ty
- [ ] Tạo/Sửa/Xóa tin tuyển dụng
- [ ] Xem danh sách ứng viên
- [ ] Cập nhật trạng thái ứng viên

### Phân hệ Admin
- [x] Dashboard với thống kê tổng quan
- [ ] Quản lý người dùng
- [ ] Duyệt tin tuyển dụng
- [ ] Quản lý danh mục, địa điểm
- [ ] Quản lý kỹ năng

## Các bước tiếp theo

1. Tạo middleware để kiểm tra role (admin, employer, candidate)
2. Hoàn thiện các controller và view cho từng chức năng
3. Thêm validation cho các form
4. Implement upload file CV
5. Gửi email thông báo khi có hồ sơ mới/cập nhật trạng thái
6. Thêm tính năng search, filter nâng cao
7. Thêm phân trang cho danh sách

## Lưu ý

- Tất cả form validation đều có thông báo lỗi bằng tiếng Việt
- Sử dụng Bootstrap 5 cho giao diện
- Code-first approach (tạo migration trước rồi mới tạo bảng)
- Dự án sử dụng Blade template engine

## Hỗ trợ

Nếu gặp vấn đề trong quá trình cài đặt hoặc phát triển, vui lòng kiểm tra:
- File `.env` đã được cấu hình đúng chưa
- Database đã được tạo chưa
- Composer dependencies đã được cài đặt đầy đủ chưa
- PHP version >= 8.2

## License

Dự án này được phát triển cho mục đích học tập và nghiên cứu.
