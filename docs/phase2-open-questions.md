# Phase 2 — các điểm còn mở

Ngày 10/09/2026. Chỉ liệt kê thông tin còn thiếu hoặc đề xuất cần duyệt; **không hỏi lại các quyết định Phase 2 đã chốt**. Production data truth, rollout hybrid, loại Plety, TranslatePress VI/EN, system-first mode, switch placement, latest 3 blog, project links thật, contact chuẩn và yêu cầu giữ integrations đều đã khóa.

Đề xuất dưới đây chưa được áp dụng. Không có thao tác code/database/activation phát sinh từ việc tạo tài liệu. Q1/Q4/Q5/Q6 là các lựa chọn thiết kế/ownership cần review; Q2/Q3 là điều kiện nguồn dữ liệu và môi trường trước triển khai.

## Q1 — Menu desktop và vị trí language item

**Question:** Bạn duyệt desktop menu rút gọn 7 mục “Giới thiệu → Dịch vụ → Công nghệ → Sản phẩm → Đội ngũ → Tin tức → Liên hệ”, mobile giữ 9 mục đầy đủ gồm Đối tác/Đánh giá, cùng language item của TranslatePress ở cuối navigation chứ?

**Why it matters:** Header phải chứa thêm mode switch, language switch, search và CTA; 9 mục đầy đủ có thể không vừa desktop hẹp/EN. Cần duyệt việc rút mục, không tự làm chữ nhỏ hoặc đổi thứ tự section.

**What you found:** Actual preview có Technology trước Projects và Team trước Blog; menu preview cũ lệch thứ tự. Full proposed order đã khóa trong final-theme-architecture.md. Chưa có bản visual QA header mới để chứng minh 9 mục vừa mọi breakpoint.

**Options:** A. Desktop 7 mục, mobile 9 mục như câu hỏi; B. Giữ 9 mục desktop và chuyển sang menu collapse sớm khi thiếu chiều rộng; C. Bạn chỉ định các mục rút khác nhưng vẫn theo section order.

**Recommended option:** A, giữ đủ section trong page, không đổi vị trí mode switch đã duyệt. Final breakpoint/spacing phải QA VI/EN ở phase code, không sửa preview Phase 2.

## Q2 — Snapshot production để seed và kiểm thử

**Question:** Snapshot database/uploads và cấu hình production mới nhất sẽ được cung cấp qua đâu, hoặc có staging nào đã đồng bộ để dùng ở phase triển khai?

**Why it matters:** Nguồn chuẩn đã được chốt là production, nhưng bản local đang có khác biệt nên chưa đủ để seed object relationships/ACF values.

**What you found:** Portal production là CPT published ID 1809; local cùng ID là draft post. Plugin assets và service list cũng có khác biệt. Chưa thấy bằng chứng có snapshot mới thay local sau audit.

**Options:** A. Dùng staging đã đồng bộ production; B. Cung cấp snapshot/export mới để phục hồi vào staging riêng; C. Chưa có snapshot, chỉ tiếp tục phần triển khai không ghi/migrate dữ liệu cho đến khi có nguồn chuẩn.

**Recommended option:** A hoặc B trước seed/compatibility QA. Không yêu cầu credentials trong chat/docs, không dùng local import đè production.

## Q3 — Runtime, cache/CSP và plugin baseline

**Question:** PHP production, cơ chế cache/minify/CSP và bộ plugin chuẩn dùng ở staging hiện là gì?

**Why it matters:** No-flash cần initializer chạy trước paint; cache/optimizer có thể defer nó. Compatibility Elementor/ACF cần đúng runtime và versions thực tế.

**What you found:** Local CLI 7.4.33; PHP server production chưa xác minh. Source local một số premium plugins có modifications đã ghi trong Phase 1, không thể lấy đó làm chứng nhận baseline. Chưa kiểm tra chính sách CSP/optimizer của production.

**Options:** A. Cung cấp thông số hosting/staging và plugin versions/bộ cài chính thức được sử dụng; B. Chỉ test local và ghi rõ production compatibility chưa đủ để release.

**Recommended option:** A trước release; không tự nâng/hạ PHP hoặc sửa/cài plugin trong Phase 2. Nếu CSP cấm inline thì chọn cơ chế hash/nonce hoặc script blocking phù hợp policy đã xác minh.

## Q4 — Owner integrations ở rollout đầu

**Question:** Bạn duyệt theme-owned global adapter cho cụm Messenger/Zalo/gọi điện và Umami trong rollout đầu, hay đã có site plugin/global hook production cần giữ làm owner?

**Why it matters:** Bảo toàn integrations đã được yêu cầu, nhưng vẫn cần một nơi render duy nhất; không tự chuyển ownership hoặc tạo plugin mới ngoài scope.

**What you found:** Child footer hiện chứa cluster/SDK/analytics. Support-chat active local nhưng enable=0. Ba HTML public đã kiểm tra có một cluster, một SDK Zalo, một Umami script; chưa chứng minh mọi route/config production không có owner khác.

**Options:** A. Theme-owned adapter qua inc/integrations.php, giữ plugin widget không render sau xác minh; B. Site functionality plugin/global hook hiện hữu làm owner, theme không nhúng lại; C. Tách plugin mới như deliverable bổ sung được duyệt riêng.

**Recommended option:** A cho rollout đầu nếu xác nhận production không có owner khác. Không tự deactivate plugin hoặc đổi enable option. Endpoint/OA/site identifier thật được map từ source chuẩn, không chép secret vào docs. Giữ link Messenger hiện hữu; nếu muốn đổi Facebook page link thành direct chat, cần đích được xác nhận.

## Q5 — CTA “Tìm hiểu thêm” của GM Solutions

**Question:** Nút GM Solutions cần đi tới URL thật nào, hay giữ modal thông tin hiện tại?

**Why it matters:** Quyết định project cards đi detail và CTA liên hệ đi /lien-he/ chưa xác định đích của nút khám phá đối tác. Không được tự gắn URL phỏng đoán hoặc biến nó thành nút liên hệ.

**What you found:** Preview partner button dùng data-detail=partner và mô tả ngắn trong main.js; audit chưa có object/page/URL đối tác xác nhận cho button này.

**Options:** A. Cung cấp URL đối tác/detail thật; B. Giữ modal thông tin với text render server-side để dịch ổn định.

**Recommended option:** A nếu có đích chính thức; nếu không, B giữ behavior preview. Field nc_partner_link hiện chưa seed, không fallback `#` và không tự ẩn nút đã duyệt.

## Q6 — Phạm vi màu Light/Dark của trang Elementor cũ

**Question:** Bạn duyệt giữ màu nội dung do Elementor hiện tại thiết kế ở các page cũ, chỉ đổi Light/Dark cho shell/native parts trong rollout đầu chứ?

**Why it matters:** Một theme có switch không thể tự chuyển tất cả inline widget colors/ảnh legacy thành dark mà vẫn giữ layout và độ tương phản. Full mode adaptation cho từng legacy page là scope thêm.

**What you found:** Contact/Outsource/đặt sân/aff atv và phần About giữ Elementor tạm. Lark có CSS custom lớn. Homepage modes đã có ảnh và tokens riêng, các legacy pages chưa có cặp thiết kế được duyệt.

**Options:** A. Giữ authored content colors trong .legacy-content, đổi shell/native components; B. Làm light/dark paint adaptation riêng cho các trang legacy trước release, cần QA thêm từng page.

**Recommended option:** A phù hợp rollout giảm rủi ro đã chốt. Không filter invert toàn trang. Homepage vẫn phải đúng cả hai modes đầy đủ; đây chỉ là giới hạn nội dung legacy cần xác nhận.

## Q7 — URL chấm công cũ đang 404

**Question:** Giữ nguyên 404 hiện tại của `/dich-vu/he-thong-cham-cong-bang-guong-mat/`, hay bạn muốn redirect sang Portal như một task riêng?

**Why it matters:** Chưa có quyết định URL cũ có cùng nội dung/ngữ nghĩa với Portal. Theme migration không nên phát minh redirect kinh doanh.

**What you found:** Public URL cũ trả 404, Portal mới trả 200; local vẫn còn bài chấm công cũ published. Đây là vấn đề đã có trước, không phải lỗi mới do switch theme. Việc bỏ bảo toàn `/demo/plety/` đã chốt riêng, không giải quyết URL này.

**Options:** A. Giữ production baseline 404, không thay trong migration theme; B. Tạo redirect được duyệt sau khi xác nhận Portal là đích thay thế phù hợp.

**Recommended option:** A trong phạm vi theme nếu chưa có yêu cầu phục hồi URL; B là task riêng có kiểm tra backlinks/ngữ nghĩa. Không thực hiện redirect ở Phase 2.

## Q8 — EN metadata/ALT ngoài visible homepage text

**Question:** Ngoài bản dịch nội dung homepage hiển thị đã chốt, release đầu có bắt buộc bổ sung EN SEO title/meta description và toàn bộ alt ảnh không?

**Why it matters:** Cần tách scope dịch nội dung HTML khỏi capability SEO translation của plugin đang dùng; không tự bật add-on hoặc hứa coverage chưa kiểm chứng.

**What you found:** TranslatePress SEO Pack local đang off; production baseline canonical/hreflang hoạt động, nhưng chưa xác minh khả năng dịch metadata/ALT với cấu hình triển khai cuối. New homepage text sẽ được thêm dịch theo yêu cầu, không phải vấn đề cần duyệt lại.

**Options:** A. Giữ SEO config/metadata hiện hành, hoàn thiện visible homepage EN và QA alt coverage trước release; B. Bổ sung đầy đủ EN metadata/ALT như acceptance requirement, kiểm tra add-on/cấu hình cần thiết trước triển khai.

**Recommended option:** B nếu EN SEO là yêu cầu release; không bật SEO Pack hay đổi slugs chỉ vì chọn thêm bản dịch metadata. Bản dịch mới cần bạn review trước đưa vào production.

## Q9 — Search bằng nội dung bản dịch EN

**Question:** Search ở bản EN có cần tìm theo toàn bộ chuỗi nội dung tiếng Anh đã dịch, hay dùng WordPress search nguồn hiện hữu và dịch phần kết quả hiển thị?

**Why it matters:** Giữ TranslatePress không mặc nhiên mở rộng SQL search của WordPress sang dictionary. Search UI có thể hiển thị EN nhưng tìm không ra từ chỉ tồn tại trong bản dịch.

**What you found:** Preview search chỉ tìm text trong sections. Kiến trúc mới dùng WordPress search; chưa xác minh một translated-content search extension production. Không có dữ liệu đủ để cam kết full EN search.

**Options:** A. WordPress search hiện hữu, giữ ngôn ngữ URL và translated results; B. Thêm translated-content search support như task compatibility riêng có acceptance queries EN.

**Recommended option:** A cho rollout tối thiểu nếu chấp nhận giới hạn; B nếu full EN search là điều kiện bắt buộc. Không tự cài search plugin hoặc giả lập bằng DOM homepage.

---

Tám tài liệu Phase 2 đã được tạo. Dừng để người dùng review, chưa tạo theme folder, PHP/CSS/JS, ACF registration hoặc ZIP, chưa sửa preview/database/plugin/theme.
