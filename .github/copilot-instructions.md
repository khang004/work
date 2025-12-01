# thông tin dự án

1. Phân hệ Khách (Guest - Chưa đăng nhập)

 * Trang chủ (Landing Page):
Hiển thị thanh tìm kiếm (Từ khóa, địa điểm, ngành nghề).
Hiển thị danh sách "Việc làm nổi bật" hoặc "Việc làm mới nhất".
Hiển thị danh sách "Công ty hàng đầu".

 * Xem chi tiết việc làm:

  Thông tin: Tiêu đề, mức lương, mô tả công việc, yêu cầu, hạn nộp, kỹ năng cần thiết.

  Thông tin công ty: Logo, quy mô, địa chỉ.

  Nút "Ứng tuyển" (Yêu cầu đăng nhập).


* Đăng ký/Đăng nhập:

  Tách biệt đăng ký cho Ứng viên (Candidate) và Nhà tuyển dụng (Employer).
  Có thể tích hợp "Login with Google/Facebook" (Chức năng nâng cao, dùng Laravel Socialite).

2. Phân hệ Ứng Viên (Candidate)
* Quản lý Hồ sơ (Profile):

  Cập nhật thông tin cá nhân (Tên, SĐT, Địa chỉ).

Giới thiệu bản thân (Bio).

  Cập nhật kỹ năng (Tags: PHP, Laravel, MySQL...).
* Quản lý CV:
Upload CV đính kèm (PDF/Docx). Kỹ thuật: Sử dụng Storage của Laravel, validate định dạng và dung lượng file.

Xem trước/Xóa CV đã upload.

* Ứng tuyển (Apply):
Nộp đơn vào một công việc cụ thể (Hệ thống sẽ lưu bản ghi vào bảng trung gian applications).

Viết thư giới thiệu (Cover letter) khi ứng tuyển.

* Quản lý việc đã ứng tuyển:

Xem danh sách các việc đã nộp.

Xem trạng thái hồ sơ (VD: Đã nộp, Nhà tuyển dụng đã xem, Được mời phỏng vấn, Bị từ chối).

Lưu việc làm (Bookmark):

Lưu lại các tin tuyển dụng quan tâm để xem sau (Quan hệ Many-to-Many).

3. Phân hệ Nhà Tuyển Dụng (Employer)
* Quản lý thông tin công ty:

Cập nhật Logo, Tên công ty, Website, Quy mô nhân sự, Địa chỉ, Google Maps embed.

* Quản lý Tin tuyển dụng (Job Posting):
Tạo tin mới: Nhập tiêu đề, mức lương (min-max), chọn danh mục, mô tả (dùng CKEditor/TinyMCE), hạn nộp hồ sơ.

Danh sách tin: Xem tất cả tin đã đăng, trạng thái tin (Đang hiển thị, Đã đóng, Chờ duyệt).

Sửa/Xóa/Đóng tin: Ngừng nhận hồ sơ khi đã tuyển đủ người.

* Quản lý Ứng viên (Candidate Management) - Quan trọng nhất:

Xem danh sách ứng viên nộp vào từng tin đăng.

Xem chi tiết hồ sơ và download CV của ứng viên.

Cập nhật trạng thái ứng viên: Chuyển trạng thái từ "Pending" -> "Reviewing" -> "Interview" -> "Hired" / "Rejected". (Khi update trạng thái, có thể gửi email tự động cho ứng viên).

4. Phân hệ Quản Trị Viên (Admin)
* Dashboard (Thống kê):

Tổng số thành viên, tổng số việc làm, số lượng hồ sơ ứng tuyển trong ngày/tháng.

* Quản lý Danh mục (Categories/Industries):

CRUD các ngành nghề (IT, Marketing, Kế toán...) để nhà tuyển dụng chọn khi đăng tin.

* Quản lý Địa điểm (Locations):

CRUD tỉnh/thành phố.

Duyệt tin (Job Approval):

Tin đăng của nhà tuyển dụng mới có thể cần Admin duyệt trước khi hiển thị lên trang chủ (để tránh spam).

* Quản lý người dùng:

Khóa (Block) tài khoản ứng viên hoặc nhà tuyển dụng vi phạm.
5. Validation
 Validate tất cả các form đầu vào(bài viết, đăng ký, đăng nhập, tạo tin tuyển dụng, v.v.)
 thông báo lỗi bằng tiếng việt rõ ràng, dễ hiểu.

# quy tắc
- luôn phản hồi bằng tiếng việt.
- luôn tuân theo danh sách chức năng đã liệt kê ở trên.
- sử  dụng tiếng việt có dấu trong toàn bộ giao diện, thông báo lỗi.
- sử dụng bootstrap5 cho giao diện admin và user.
- tất cả file hướng dẫn .md đều được đặt trong thư mục .docs` trong dự án.
- Luôn kiểm tra lại tên model, tên bảng,tên biến,tên route, đã có trong dự án hay chưa trước khi hoàn chỉnh chức năng.Nếu tên cột chưa tồn tại,hãy tạo migration để thêm cột vào bảng.

# technical stack
- backend: Laravel 12.x.
- frontend: Blade template + Bootstrap 5.x.
- database: MySQL.

# tài liệu tham khảo
- https://laravel.com/docs/12.x
- https://getbootstrap.com/docs/5.3/getting-started/introduction/

