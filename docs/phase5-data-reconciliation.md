# Phase 5 — Data reconciliation

Ngày 11/09/2026. Local là môi trường development, không phải production data truth cuối cùng. Không import database này lên production.

| Hạng mục | LOCAL đã xác minh | PRODUCTION PENDING |
|---|---|---|
| Front page | Core page_on_front → 312; thêm riêng nc_* meta và references | Resolve front page và đối chiếu nội dung mới nhất, không áp ID312 mặc định |
| Outsource | Published Page1133, slug outsource | Resolve slug/type/status từ production mới nhất |
| Tư vấn / Cá nhân | danh-muc-dich-vu term74/75 | Resolve taxonomy+slug; không copy term IDs |
| Sản phẩm menu | danh-muc-dich-vu term81 | Resolve san-pham production |
| Blog categories menu | category kien-thuc1, cong-ty76 | Resolve taxonomy+slug production |
| Olympia | Published Post1510; slug truong-doanh-nhan-top-olympia | Xác minh object production, title/excerpt/image và URL core |
| Affiliate | Published Post1548; slug wordpress-plugin-affiliate | Xác minh object production, không suy từ ID local |
| Portal | Relationship rỗng, visual giữ nguyên; local1809 draft/slug rỗng không được chọn | Đối chiếu published object thật tại production rồi chọn relationship; không tạo Page/CPT giả hoặc lấy draft local |
| Contact | Published Page320, lien-he; G12 canonical address/email/phone đã seed | Resolve Page mới nhất; CONTACT-01 kiểm tra địa chỉ/map trong nội dung Contact cũ là task riêng |
| Team | 3 thành viên/ảnh thật/email/phone đúng yêu cầu Phase5 | Xác nhận thông tin hiện hành khi rollout; không tạo avatar mới |
| Testimonials | 4 full quotes nguyên bản và ảnh checksum trùng | Đối chiếu lời khách hàng hiện hành; không thêm rating |
| GM Solutions | Logo attachment1789, https://gm-group.vn/ | Resolve attachment tương đương và xác nhận link trước rollout |
| Social | Facebook/TikTok thật đã duyệt | Kiểm tra profile ownership khi rollout; không seed LinkedIn/YouTube |
| Media | 22 mapping: 10 reuse,12 import; G13 đủ8IDs | Recompute SHA256 trên production Media Library, reuse/import có kiểm soát. IDs1817–1828 chỉ là local |
| Menus | Desktop84/Mobile85, home_primary/home_mobile; menu69 không đổi | Tạo/map đúng menu objects trong production, giữ inner menus. Không copy menu IDs |
| Inner/footer menus | Không đổi current menu69; không gán primary/primary_mobile/footer locations cho Nextcore | Review assignment theo rollout inner site; footer dev fallback chỉ phục vụ preview an toàn |
| Blog mới hơn | Latest3 local:1732 (2025-03-26),1548 (2024-11-18),1510 (2024-11-11) | Production có thể có bài mới hơn. WP_Query tự chọn latest3 tại runtime; không seed danh sách card hoặc lấy local làm chuẩn |
| Translations | 134 nguồn đã soạn EN;15 nguồn có match không rỗng trong dictionary local | Người dùng duyệt proposal; đối chiếu dictionary production và fragments/attributes trước import. Không ghi trong Phase5 |
| Hero EN | VI H1 bị chia giữa “Phần”/“mềm”; proposal dịch nguyên cụm | QA block translation, red brand span, ngắt dòng sau khi duyệt; không dịch 2 fragment độc lập |
| Integrations | Owners config theme; public config rỗng; enabled=false | Xác minh provider config/ownership và QA dedupe trước bật Messenger/Zalo/Umami/floating cluster |
| Runtime | PHP7.4.33/ACF6.3.10 local; request-local theme QA | Cần baseline production PHP/plugins/cache/minify/CSP trước release; chưa kết luận production ready |

Chi tiết IDs/checksums/values ở [local write report](phase5-local-write-report.md). Core custom_logo/site_icon không được tự migrate trong Phase5; giữ consumer/fallback hiện hành, review core branding assignment khi activate rollout.

Trình tự tiếp theo sau người dùng review: xác minh snapshot production/staging → resolve các IDs trên môi trường đó → chuẩn bị diff migration → duyệt EN và kiểm tra translation UI → integration/inner-site/runtime QA. Không suy ra quyền deploy từ việc hoàn tất Phase5.
