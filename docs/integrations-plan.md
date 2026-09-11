# Integrations và global contact — Phase 2

Ngày 10/09/2026. Bảo toàn Messenger, Zalo, floating call button, Umami đã được người dùng yêu cầu. **Chưa đổi hooks/settings hoặc gửi thử tin nhắn/form.** Không đưa credentials/license keys vào tài liệu.

## 1. Bằng chứng source và render

- Child `wp-content/themes/flatsome-child/footer.php`: một cụm `.adminActions_flat_button`; link gắn icon Messenger tới Facebook page hiện hữu; link tel; `.zalo-chat-widget` với OA public configuration; script SDK Zalo và Umami cuối document.
- Plugin `support-chat` active trong local active_plugins, nhưng truy vấn chỉ đọc Phase 2 cho thấy **`wpsaio_enable_plugin=0`**. Config app keys có facebook-messenger, phone, zalo, custom-app; không xuất values/identifiers vào docs.
- Plugin source `src/WpSaioInit.class.php` đăng ký wp_footer; `wpFooter()` return sớm nếu `isActivePlugin()` false, method đó kiểm tra option enable=1. Vì vậy active plugin **không đồng nghĩa widget đang hiện**.
- HTTP GET HTML public ngày audit tại [homepage](https://nextcore.vn/), [Outsource](https://nextcore.vn/outsource/) và [EN home](https://nextcore.vn/en/): mỗi trang có 1 custom cluster, 1 Zalo container, 1 Zalo SDK script, 1 Umami script; không thấy script src từ support-chat. Đây là bằng chứng HTML ban đầu ở 3 URLs, không phải xác minh mọi injected script/route hoặc hoạt động provider.

## 2. Ownership và duplication

| Integration | Current owner | Trùng có thể có | Đích kiến trúc đề xuất | QA còn cần |
|---|---|---|---|---|
| Messenger | Child footer custom link/icon | Support-chat hỗ trợ cùng channel; footer social Facebook là navigation khác, không nhất thiết duplicate widget | Một floating cluster do global hook owner render; reuse chính xác link thật production | Link hiện là Facebook page, cần giữ đúng hành vi cũ; không tự đổi sang m.me/direct-chat URL nếu chưa xác minh; test target/app handoff |
| Zalo | Child widget markup + sp.zalo.me SDK | Support-chat có app zalo; SDK có thể enqueue thêm từ plugin khác | Cùng contact owner, một OA container/SDK; public config từ nguồn chuẩn | OA routing, mobile app/browser, iframe loading, autopopup=0, text provider và chồng lấn mobile |
| Floating call | Child `tel:` trong custom cluster | Support-chat phone app, contact links footer/team | Cluster dùng G12 phone duy nhất; footer/team links vẫn đúng vai trò riêng | E.164 +84378962625, tap mobile không mở hai handlers, keyboard/focus |
| Umami | Child script nhúng sau wp_footer | Plugin/global scripts/tag manager chưa được xác minh hết production | Một analytics owner, enqueue global qua một handle/hook; giữ endpoint/public site config | Một script và số pageview đúng mỗi navigation; không gắn manual track mode toggle mặc định; exclude staging theo deployment config |

Đề xuất rollout đầu **theme-owned global adapter trong inc/integrations.php**, vì behavior đang nằm ở child theme và mục tiêu output một theme ZIP. G15 lưu config không nhạy cảm; floating-contact.php render UI; wp_enqueue_scripts/wp_footer làm integration point. Footer.php không nhúng scripts lần nữa. **Ownership là phương án trình duyệt Q4, chưa áp dụng.**

Tách sang site functionality plugin là phương án dài hạn để đổi theme không mất integrations, nhưng là artifact/scope bổ sung, không âm thầm tạo plugin hoặc coi có sẵn. Nếu user chọn plugin ownership, theme không render same channels và không tạo ACF settings duplicate; vẫn có styles/wrapper theo contract nếu cần.

## 3. Quy tắc một owner

1. Mỗi channel/analytics có manifest owner rõ từ configuration đã duyệt, không phỏng đoán chỉ qua `is_plugin_active()`.
2. Với theme owner: xác minh plugin widget production đang off/không render trước activation; không tự toggle plugin settings. Khi chưa xác minh phải block release gate, không vừa render cả hai để “chắc có”.
3. Với plugin/external owner: theme không enqueue SDK, không tạo cluster duplicate. Public contact links thông thường trong footer vẫn tồn tại.
4. Một script handle cho SDK/analytics trong theme; WordPress handle dedup không tự phát hiện script hardcode bởi bên khác, nên cần kiểm tra page source/network thực tế.
5. Không nhúng lại legacy icon font CDN chỉ để có phone/close icon; dùng SVG local trong UI theme. Không gỡ global Font Awesome của builder pages khi chưa xác minh họ không cần.
6. Không thêm tracking events/cookie/SDK khác ngoài integrations được yêu cầu; không dùng Umami URL như API endpoint để gửi audit data.

## 4. Global contact source

G12 là nguồn chuẩn:

| Dữ liệu | Giá trị |
|---|---|
| Address | 63 Phan Đăng Lưu Street, Hai Chau, Da Nang 550000, Viet Nam |
| Email | info@nextcore.vn |
| Phone | +84378962625 |
| Contact target | Relationship tới production Page `/lien-he/`; EN URL do TranslatePress sinh |

Footer và floating call dùng cùng phone source; không giữ hardcoded local-format phone cạnh một số global khác. Team có phone riêng nếu thật, không overwrite bằng số công ty. Messenger social page và Messenger widget destination có thể cùng URL nhưng khác vị trí, không biến email/phone thành arbitrary script fields.

**CONTACT-01 — migration task riêng:** đối chiếu Contact Page production; local Page 320 còn 384 2/9 Street. Khi được triển khai, cập nhật nội dung contact page và bản dịch liên quan dùng global data hoặc binding đã test; xác minh bản đồ tương ứng địa chỉ mới. Không thay tọa độ theo suy đoán hoặc sửa Page ở Phase 2. Giữ CF7 form hiện hữu; xác nhận form source trên snapshot, không đổi mail recipients trong architecture.

## 5. Rendering, accessibility, mobile và modes

Cluster toggle là native button, aria-expanded/controls, focus rõ, Escape đóng; không dùng checkbox vô hình + anchor `#!` làm control production. Icon SVG decorative. Controls có tên được dịch; link phone/mail dùng schemes phù hợp. Desktop/mobile cluster phải không đè cookie controls/CTA/menu, tôn trọng safe-area và stacking của dialogs.

Theme light/dark chỉ đổi panel/icon/focus tokens của wrapper, không inject CSS vào iframe Zalo. Giữ provider behavior/URLs; mode switch không reload Umami hoặc nhân đôi SDK. UI text server i18n, welcome/provider strings cần kiểm tra riêng vì TranslatePress không tự dịch iframe cross-origin.

Không tải lại analytics sau mỗi theme toggle, không tạo view giả khi mở menu/modal. Phạm vi routes giữ như production đã xác minh; staging có thể không gửi analytics để tránh làm bẩn số liệu, cần xác nhận environment setup Q3/Q4 trước test endpoint thật.

## 6. QA trước release, chưa thực thi

- Đếm cluster/widget/script trên home, Contact, Outsource, service/blog detail và EN; network không duplicate SDK/Umami.
- Kiểm tra click/tap/link destinations, OA đúng, provider loads trên mobile. Không gửi tin nhắn hoặc đặt cuộc gọi thật chỉ để kiểm tra link.
- Kiểm tra keyboard/ARIA/focus khi cluster/menu/dialog mở cùng nhau; mode switch và translation không reset widget bất ngờ.
- Kiểm tra analytics với môi trường/site test thích hợp và owner xác nhận; audit HTML count không chứng minh ingest/pageviews đã đúng.
- Kiểm tra production config đồng bộ snapshot, plugin display state và global scripts; kết quả local enable=0 không thay thế xác nhận production.

Những gì còn phải quyết định nằm ở Q3/Q4 trong [phase2-open-questions.md](phase2-open-questions.md).
