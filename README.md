# Minh Lý Đạo – Tam Tông Miếu — WordPress Theme

Theme WordPress tùy chỉnh cho website chính thức của **Minh Lý Đạo – Tam Tông Miếu**.

- **Site đang chạy theme này:** https://tamtongmieu.minhlydao.org.vn
- **Site tham chiếu (nội dung/giao diện gốc):** https://minhlydao.org.vn
- **Phiên bản hiện tại:** xem hằng số `MLD_VER` trong `functions.php`

## 1. Cấu trúc thư mục

```
minhlydao-theme-git/
├── functions.php              # Khai báo theme, custom post types, hook, form liên hệ...
├── header.php                 # Banner + menu chính
├── footer.php                 # Chân trang
├── front-page.php             # Trang chủ
├── index.php                  # Template fallback
├── page-lich-tam-tong-mieu.php  # Trang Lịch (âm dương, Tiết khí, Trực/Hoàng đạo)
├── archive.php, archive-*.php # Trang danh sách cho từng custom post type
├── style.css                  # Toàn bộ CSS của theme (không dùng file CSS riêng khác)
├── assets/
│   ├── main.js, lich.js       # JS cho menu mobile, lịch âm dương...
│   └── images/                # Ảnh banner, logo, nền...
└── inc/                       # Các file PHP phụ trợ (nếu có)
```

Các Custom Post Type chính: `post` (Tin tức chung), `sach`, `kinh`, `thanh_ngon`, `giao_ly`, `tu_tinh`,
`tin_tuc`, `su_kien`, `media_tt`, và `page` chuẩn của WordPress.

## 2. Quy trình build & deploy theme lên site

Theme được deploy thủ công qua trang quản trị WordPress (không dùng FTP/SSH):

1. **Chỉnh sửa code** trực tiếp trong thư mục này (`minhlydao-theme-git/`).
2. **Tăng số phiên bản** trong `functions.php`:
   ```php
   define( 'MLD_VER', 'x.y.z' );
   ```
   Việc này giúp trình duyệt tải lại đúng CSS/JS mới (cache-busting) và giúp nhận diện bản build khi kiểm tra "Đang cài giao diện từ file được tải lên".
3. **Kiểm tra cú pháp PHP** trước khi đóng gói (không được để lỗi PHP làm sập site).
4. **Đóng gói thành file .zip** — **quan trọng**: phải nén kèm theo thư mục cha tên `theme`, không nén từ bên trong thư mục:
   ```bash
   # Chạy lệnh này từ thư mục CHỨA thư mục theme (không phải từ bên trong theme/)
   zip -qr minhlydao-theme-vX.Y.Z.zip theme
   ```
   Nếu nén sai cách (không có thư mục bọc ngoài `theme/`), WordPress sẽ cài thành một theme MỚI trùng lặp thay vì ghi đè theme hiện tại.
5. **Tải lên qua trang quản trị**: Giao diện → Thêm mới → Tải giao diện lên → chọn file .zip → Cài đặt ngay.
6. WordPress sẽ báo **"Thư mục đích đã tồn tại...Giao diện này đã được cài"** — bấm **"Thay thế cài đặt bằng tải lên"** để ghi đè bản cũ.
7. Xác nhận thông báo **"Giao diện cập nhật thành công"**.
8. Hard-refresh trình duyệt (Ctrl+Shift+R) để kiểm tra kết quả trên site, vì CSS/JS có thể bị cache.

## 3. Lưu ý kỹ thuật quan trọng

- **Banner (`.hero-banner`) trong `header.php`**: logo và khối chữ "MINH LÝ ĐẠO – TAM TÔNG MIẾU" được định vị bằng `position: absolute` với đơn vị `%` (tính theo kích thước banner), copy chính xác từ tỉ lệ của site tham chiếu `minhlydao.org.vn`. Khối chữ hiện dùng ảnh tĩnh `assets/images/banner/minh_ly_dao_1.png` (không còn là text CSS) để đảm bảo khớp 100% với bản gốc.
- **Menu điều hướng**: các mục Custom Link trong menu (Giao diện → Menus) lưu URL tuyệt đối — nếu đổi domain, phải sửa thủ công từng mục, không tự động cập nhật.
- **Đa ngôn ngữ (VI/EN/FR)**: cơ chế dùng `mld_get_translation()` và `mld_localize_url()` trong `functions.php` để liên kết bản dịch giữa các custom post type. Nút EN/FR trên menu hiện đang tắt (comment trong `header.php`) cho đến khi bản dịch đầy đủ và chính xác.
- Không để lại các **endpoint tạm thời** (`admin_post_...`) dùng để chạy tác vụ hàng loạt (sideload ảnh, tạo bài viết...) trong bản deploy cuối — luôn gỡ bỏ và deploy lại bản sạch sau khi dùng xong.

## 4. Lịch sử thay đổi gần đây (tóm tắt)

| Phiên bản | Nội dung chính |
|---|---|
| 2.89.0 | Gỡ endpoint tạm bulk-fix ảnh IP cũ (103.56.162.236) |
| 2.90.0 | Dời banner text về vị trí trái |
| 2.91.0 | Tăng cỡ chữ banner, giữ vị trí trái |
| 2.92.0 | Sửa ký tự Hán "㆔"→"三" trong banner |
| 2.93.0 | Banner dùng ảnh gốc từ site tham chiếu thay vì dựng bằng CSS text |
| 2.94.0 | Dời banner text sát logo hơn, thu nhỏ kích thước |

Xem chi tiết đầy đủ qua `git log`.

## 5. Việc còn tồn đọng (chưa hoàn thành)

- Dịch Thánh Ngôn sang tiếng Anh/Pháp
- Kiểm tra/dịch: Giáo lý, Tu tịnh, Lễ, Cảm ứng luận, Người Minh Lý môn sanh
- Kiểm tra/dịch các trang Page: Giới thiệu, Hiến chương, Lịch sử thành lập
- Hoàn thiện hệ thống đa ngôn ngữ VI/EN/FR đầy đủ trên toàn site rồi bật lại nút chuyển ngôn ngữ EN/FR trên menu
