# Phase 5 — Open questions

Ngày 11/09/2026. **None — không có câu hỏi chặn việc hoàn tất Phase5 local.**

Các điểm pending sau Phase5 được ghi rõ để review, không được tự đoán:

1. **Portal production relationship:** Local không có object published đủ bằng chứng. Giữ trống theo quyết định người dùng; cần resolve published object trên snapshot production mới trước bật link.
2. **EN proposal:** Người dùng review [134 chuỗi](homepage-en-translation-review.md) trước mọi dictionary write. Hero phải dịch nguyên cụm, không riêng “Phần”/“mềm”. Tên Trường Doanh nhân Top Olympia giữ nguyên theo yêu cầu; dictionary cũ có “Top Olympia Business School” nhưng chưa coi đó là tên EN chính thức được duyệt cho homepage mới.
3. **Production runtime/cache/CSP:** Pending từ Phase3/4. Cần baseline PHP/plugin versions/cache/minify/CSP trước release; local QA không chứng minh production readiness.
4. **Admin tương tác / activation:** Nextcore chưa active; field groups đã kiểm tra qua harness. Trước rollout cần kiểm tra phiên Admin save/upload và compatibility inner pages trong context theme được kích hoạt theo bước được duyệt.
5. **Integrations:** Public provider config chưa populate, rollout vẫn disabled. Xác minh ở integration QA riêng; không yêu cầu gửi secrets trong chat.

Không tạo content thay thế, không chọn ID theo phỏng đoán. Xem [reconciliation](phase5-data-reconciliation.md) để phân biệt local đã làm và production còn pending.
