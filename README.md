# Nextcore Website

Mã nguồn website WordPress của Nextcore.

## Cài trên máy mới

Xem hướng dẫn từng bước tại [SETUP.md](SETUP.md). Tóm tắt nhanh:

```powershell
git clone https://github.com/NNTanNextcore/Nextcore-website.git C:\xampp\htdocs\nextcore-website
cd C:\xampp\htdocs\nextcore-website
Copy-Item wp-config.example.php wp-config.php
```

Sau đó tạo/import database và điền thông tin MySQL vào `wp-config.php`.

> `wp-config.php` chứa mật khẩu và khóa bảo mật, đã được `.gitignore` chặn và không được commit.

