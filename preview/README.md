# Nextcore — Static homepage preview

Prototype độc lập bằng HTML, CSS và JavaScript thuần. Không sử dụng WordPress, ACF hay CDN.

## Mở preview

- XAMPP Apache đang chạy: mở **http://localhost/preview/index.html**.
- Không cần server: mở `C:\xampp\htdocs\preview\index.html` bằng Chrome/Edge/Firefox.
- Có thể sao chép toàn bộ thư mục `preview/` sang máy khác; giữ nguyên cấu trúc `assets/`.
- Không cần npm install, build, database hoặc đăng nhập WordPress.

## Cấu trúc

```text
preview/
├── index.html                 # Nội dung semantic, đủ 11 khối yêu cầu kể cả footer
├── assets/
│   ├── css/main.css           # Design tokens, components, breakpoints, reduced motion
│   ├── js/main.js             # Menu, reveal, marquee, tìm kiếm và native dialogs
│   └── images/                # Toàn bộ hình ảnh local, nền SVG tự viết
├── ASSETS.md                  # Nguồn asset và phạm vi sử dụng
├── checks/
│   ├── browser-check.mjs      # Kiểm tra Chrome qua DevTools Protocol, không dependency
│   ├── report.json            # Kết quả kiểm tra
│   ├── desktop.png
│   ├── tablet.png
│   └── mobile.png
└── README.md
```

## Thiết kế và tương tác

### Điều chỉnh đã chốt

- Section dịch vụ theo mockup mới ngày 10/09/2026: Outsource CNTT, Tư vấn doanh nghiệp, Giải pháp cá nhân. CSS riêng `assets/css/services.css` chỉ áp dụng cho `#services`; ba ảnh mới nằm trong `assets/images/service-*.png`.
- Card dịch vụ cao 500px desktop, bo góc 16px, gap 26px; desktop 3 cột, tablet 2 + 1, mobile 1 cột. Ảnh có overlay 35–40% phía trên, đậm dần xuống đáy; hover scale 1.04 và tăng sáng nhẹ. Nút card dẫn đến section liên hệ.
- Kiểm tra cập nhật riêng: `checks/services-report.json` và `checks/*-services.png`. Khi chuyển WordPress sau xác nhận, mỗi service cần ACF cho title, description, image, icon, link và micro-label. Chưa triển khai ACF.

- Giới thiệu công ty hiện nằm ngay sau Hero, trước Stats, dùng video local `assets/video/nextcore-danang.mp4` làm nền. Nội dung mới và ngày thành lập theo yêu cầu người dùng.
- Section video cao tối thiểu 85svh desktop; mobile tối thiểu 600px/90svh và có thể giãn theo nội dung. Overlay đen 30% → 70%, mobile 35% → 78%.
- Poster `assets/images/danang-poster.jpg` được lấy từ giây thứ 1 của video. Poster luôn nằm dưới video; chỉ hiện video khi đã phát thành công. Autoplay bị chặn, lỗi tải hoặc reduced motion sẽ hiển thị poster. Video tạm dừng khi ra khỏi viewport hoặc chuyển tab.
- Kiểm tra video riêng và ảnh mới nhất: `checks/video-report.json`, `checks/desktop-video.png`, `checks/mobile-video.png`, `checks/small-mobile-video.png`. Ảnh toàn trang ở vòng kiểm tra trước chưa phản ánh việc thay About bằng video.
- Khi được duyệt chuyển WordPress: video URL, poster, title, ngày thành lập và các đoạn mô tả cần editable bằng ACF. Hiện chưa tạo field hoặc sửa WordPress.

- Tên công ty là heading chính, đúng hai dòng: “Công ty cổ phần Phần” / “mềm Nextcore”. Slogan “BUILD TRUST, CREATE VALUE” nhỏ hơn và nằm dưới.
- `assets/css/layout.css` quản lý bố cục thoáng: Hero tối thiểu 100svh, section chính 92svh; Stats và Technology 45svh trên desktop/tablet.
- Cuộn tự do, không scroll snap. Mobile cho chiều cao theo nội dung (Hero 100svh, CTA 80svh), giữ khoảng cách lớn giữa các khối.
- Card, ảnh và heading được tăng kích thước phù hợp với khoảng trống mới. Screenshot và báo cáo trong `checks/` được cập nhật cho phiên bản này.

- Bám `docs/Nextcore_Homepage_Approved_Mockup.png` và phần visual/content trong đặc tả. Các chỉ dẫn tạo theme, CPT, ACF, ZIP trong đặc tả chưa áp dụng ở giai đoạn này.
- Nền gần đen, đỏ Nextcore, hero chữ lớn; ba dịch vụ, ba dự án và ba thành viên.
- Desktop: dịch vụ 3 cột, dự án 3 cột, Stats 3 cột. Tablet: dịch vụ và dự án 2 + 1. Mobile: Stats, dịch vụ, dự án và team xếp dọc.
- Header đổi nền khi cuộn; menu hỗ trợ bàn phím và Escape. Marquee có nút tạm dừng, dừng khi hover và tắt chuyển động theo `prefers-reduced-motion`.
- Nút kính lúp tìm nội dung ngay trên trang, hỗ trợ tiếng Việt không dấu. Nút dự án mở thông tin ngắn bằng hộp thoại native.
- Liên hệ/mạng xã hội mở thông báo xem trước; không gửi dữ liệu hoặc điều hướng đến địa chỉ chưa xác nhận.

## Khác biệt so với mockup

1. Hero, About, CTA dùng ảnh AI tạo riêng theo bố cục/mood; không phải cùng bức ảnh trong mockup. Background dịch vụ/blog tái sử dụng các ảnh này và ảnh dự án.
2. Logo Nextcore và GM Solutions dùng bản local có sẵn, khác cách dựng logo trong mockup. Partner dùng nền sóng SVG tự viết.
3. Team dùng ảnh local gắn với từng tên; không dùng khuôn mặt minh họa trong mockup. Chức danh theo mockup, cần kiểm chứng trước công bố.
4. Dự án dùng screenshot local trong khung trình duyệt CSS, không dùng ảnh laptop dựng sẵn của mockup.
5. Stats theo số liệu người dùng xác nhận: 35+ dự án triển khai, 25+ khách hàng tin tưởng, 20+ nhân viên. Đã bỏ mục năm kinh nghiệm và chuyển thành ba chỉ số.
6. Blog là ba thẻ nội dung minh họa; chưa truy vấn Posts và chưa gán ngày xuất bản giả. Dòng ngày được thay bằng chủ đề.
7. “Xem video” đổi thành “Xem năng lực” vì chưa có video thật. “Tuyển dụng” đổi thành “Đội ngũ”; các liên kết danh sách đổi thành liên kết nội trang có đích phù hợp.
8. Font Arial hệ thống; các nhãn công nghệ dùng chữ/biểu tượng CSS đơn giản, chưa phải toàn bộ logo SVG chính thức. Kích thước và crop ảnh được thích ứng cho màn hình nhỏ.

## Kiểm tra

Kiểm tra Chrome headless ở 1440, 768, 390 và 320 px: không tràn ngang ở chế độ thường; không ảnh lỗi, không liên kết neo lỗi, không yêu cầu asset ngoài origin, không lỗi JavaScript. Có kiểm tra menu mở/đóng bằng Escape, trả focus, dialog, tìm kiếm không dấu, reduced motion và mở trực tiếp file khi tắt JavaScript. Kết quả mới nhất tại `checks/report.json`.

Ảnh screenshot desktop/tablet/mobile nằm trong `checks/`. Chưa kiểm tra Safari/iOS trên thiết bị thật. Ảnh giữ bản gốc cho vòng duyệt; tối ưu WebP/srcset sẽ là công việc riêng trước triển khai chính thức.

## Giới hạn giai đoạn

Chỉ tạo file dưới `preview/`; không sửa core, plugin, theme, cấu hình hay dữ liệu WordPress. Không tạo theme mới, ACF hoặc gói ZIP. Chỉ chuyển sang WordPress sau khi người dùng xác nhận.
