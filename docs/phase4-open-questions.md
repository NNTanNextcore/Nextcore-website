# Phase 4 — Open questions

Ngày 11/09/2026. Không có câu hỏi chặn việc port homepage hoặc review Phase 4.

## P4-Q1 — Production runtime/cache/CSP

**Question:** Production/staging chuẩn hiện dùng PHP phiên bản nào, bộ plugin/version nào và cơ chế cache/minify/CSP nào?

**Why it matters:** Cần xác nhận runtime compatibility và cho phép theme-init/media initializer chạy đồng bộ trước paint; local QA không thay thế kiểm tra response production.

**What you found:** Local PHP 7.4.33 lint đạt; WordPress + ACF/TranslatePress local render VI/EN đạt trong harness chỉ đọc. Chưa có baseline production mới được cung cấp. Đây là điểm còn mở từ Phase 3, không tự chọn hoặc sửa cấu hình.

**Options:** Cung cấp thông số/staging đã đồng bộ; hoặc giữ kết luận hiện tại ở mức local QA và hoãn production-readiness verification.

**Recommended option:** Cung cấp baseline trước release; không cần credentials trong chat. Không tự thay PHP/plugins/cache/CSP hoặc activate theme.

## Các điểm đã được trả lời — không còn là open questions

- Portal: người dùng duyệt giữ visual, chỉ bật link khi relationship published hợp lệ; không dùng local ID 1809.
- Team/social: người dùng duyệt đối chiếu production; đã xác minh và áp dụng thông tin thật.
- EN: người dùng chọn soạn bản dịch để duyệt **ở bước tiếp theo sau Phase 4**, không ghi dictionary trong phase này.
- Menu: hierarchy, order và vị trí switches đã khóa; chưa assign do ràng buộc không ghi database, không phải thiết kế còn mơ hồ.

