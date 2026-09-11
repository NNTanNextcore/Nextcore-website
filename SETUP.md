# Cài đặt Nextcore Website trên máy mới

## Chuẩn bị

- Cài XAMPP có Apache, PHP và MySQL.
- Clone repository vào `C:\xampp\htdocs\nextcore-website`.
- Bật Apache và MySQL trong XAMPP Control Panel.

## 1. Tạo database

1. Mở `http://localhost/phpmyadmin`.
2. Chọn **New** và tạo database, ví dụ `nextcore_website`.
3. Nếu có file database được chia sẻ riêng, chọn database vừa tạo, bấm **Import** và nhập file `.sql`.

Database không được lưu trên GitHub vì có thể chứa tài khoản, email và cấu hình nhạy cảm. Hãy trao đổi database qua một kênh riêng an toàn.

## 2. Tạo file cấu hình local

Mở PowerShell tại thư mục dự án và chạy:

```powershell
Copy-Item wp-config.example.php wp-config.php
```

Mở `wp-config.php` rồi sửa bốn giá trị sau cho đúng với MySQL trên máy:

```php
define( 'DB_NAME', 'nextcore_website' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );
```

Với cấu hình XAMPP mặc định, user thường là `root` và password để trống.

## 3. Tạo khóa bảo mật riêng

1. Mở `https://api.wordpress.org/secret-key/1.1/salt/`.
2. Copy tám dòng `AUTH_KEY` đến `NONCE_SALT` được tạo trên trang đó.
3. Thay tám dòng tương ứng trong `wp-config.php`.
4. Thay `DUPLICATOR_AUTH_KEY` bằng một chuỗi ngẫu nhiên mới, hoặc giữ dòng này chỉ khi plugin Duplicator cần dùng.

Mỗi máy có thể dùng bộ khóa riêng. Không gửi hoặc commit `wp-config.php` lên GitHub.

## 4. Kiểm tra địa chỉ website

Mở:

```text
http://localhost/nextcore-website/
```

Nếu website chuyển hướng sang domain hoặc URL cũ, mở phpMyAdmin và kiểm tra hai giá trị `siteurl` và `home` trong bảng `wp_options`. Tên bảng có thể khác nếu `$table_prefix` trong `wp-config.php` không phải `wp_`.

Giá trị local thường là:

```text
http://localhost/nextcore-website
```

## 5. Kiểm tra trước mỗi lần commit

Chạy:

```powershell
git status
git check-ignore -v wp-config.php
```

`wp-config.php` phải được báo là đang bị ignore và không được xuất hiện trong danh sách file chuẩn bị commit.

Thư mục `wp-content/uploads` được lưu trên GitHub theo chủ ý của dự án. Riêng log WooCommerce trong `wp-content/uploads/wc-logs` vẫn bị bỏ qua vì log có thể chứa thông tin đơn hàng hoặc khách hàng.

