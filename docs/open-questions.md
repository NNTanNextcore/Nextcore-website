# Các câu hỏi chưa chắc chắn — Phase 1

Ngày 10/09/2026. Chỉ gồm vấn đề chưa xác minh hoặc lựa chọn chưa được duyệt. Các đề xuất dưới đây **chưa được áp dụng**. Cần trả lời trước phase liên quan; không có silent defaults.

## Q1 — Nguồn dữ liệu chuẩn để migration

**Question:** Dùng bản production hiện tại làm nguồn dữ liệu chuẩn, và bạn sẽ cung cấp/cho phép đồng bộ snapshot mới trước khi triển khai chứ?

**Why it matters:** Local khác production ở cả post type, published state và URLs; triển khai trên local rồi import đè có thể làm mất trang thật.

**What you found:** Local ID 1809 là draft `post` chưa có slug; public cùng ID là `dich-vu` với slug `phan-mem-cham-cong-nextcore-portal`, HTTP 200. Local còn published 1530 chấm công cũ, public URL đó 404. AddToAny có assets public nhưng không active trong local options.

**Options:** A. Cập nhật snapshot production vào staging riêng trước migration; B. Tiếp tục build bằng local nhưng chờ map/reconcile production trước release, không coi local là bản đầy đủ.

**Recommended option:** A. Production là nguồn chuẩn về data/URL; preview là nguồn chuẩn về thiết kế homepage. Không import ngược local đè production.

## Q2 — Scope chuyển các trang ngoài homepage

**Question:** Phase triển khai cần chuyển luôn Outsource/About/Contact và các product landing sang native + ACF, hay giữ Elementor ở một số trang trong giai đoạn đầu?

**Why it matters:** “Standalone theme” không đảm bảo các shortcodes và styles Flatsome còn hoạt động. Mức chuyển content quyết định khối lượng thực tế và khả năng giữ layout riêng.

**What you found:** About có UX shortcodes; footer chính là UX Block; Outsource dùng eae-timeline; đặt sân và Wordpress aff atv dùng Elementor; Lark chứa CSS custom lớn. Không có một layout chung phù hợp tất cả.

**Options:** A. Chuyển tất cả page types chính sang native/ACF trong một đợt; B. Homepage native/ACF, bảo toàn một số pages bằng Elementor, xử lý riêng dependency Flatsome trước activate.

**Recommended option:** B nếu cần rollout từng bước; phải duyệt rõ danh sách pages còn Elementor và cách xử lý UX dependencies. Chọn A nếu yêu cầu toàn frontend bỏ builders ngay lần release đầu.

## Q3 — Những URL/content phụ hoặc cũ cần giữ

**Question:** Có tiếp tục phục vụ `/demo/` và `/demo/plety/`, và URL chấm công cũ đang 404 có cần redirect sang Portal không?

**Why it matters:** Plety nằm hoàn toàn trong template, còn URL cũ có thể có backlinks. Không thể tự xóa demo hoặc tự thiết lập redirect có ý nghĩa kinh doanh.

**What you found:** `/demo/` có trong public sitemap; `/demo/plety/` có Page local 1813/template riêng nhưng chưa xác minh production requirement. `/dich-vu/he-thong-cham-cong-bang-guong-mat/` hiện HTTP 404; local không có `_wp_old_slug`/redirect_service có giá trị để giải thích redirect.

**Options:** A. Giữ demo/Plety nếu đang dùng và phê duyệt redirect URL cũ có đích tương đương; B. Chỉ giữ các routes production đã xác nhận, giữ nguyên 404 cũ; C. Cung cấp route manifest bổ sung cho các URL lịch sử.

**Recommended option:** Xác nhận C trước, giữ `/demo/` vì đang trong sitemap; chỉ thêm redirect khi bạn xác nhận Portal thay thế đúng nội dung cũ.

## Q4 — Mức hoàn thiện tiếng Anh

**Question:** Release mới chỉ cần giữ các bản dịch đã có, hay phải hoàn thiện toàn bộ EN cho homepage mới và các landing chính; ai duyệt bản dịch mới?

**Why it matters:** Giữ plugin và URL không có nghĩa text mới được dịch đầy đủ; không tự sáng tác bản dịch kinh doanh hoặc nhân đôi pages.

**What you found:** TranslatePress VI/EN đã xác minh. Dictionary local có 408 record có bản dịch/1.471 record, không phải tỷ lệ coverage toàn site. Public EN homepage còn review/blog tiếng Việt, EN Portal còn các heading Việt. Text/markup preview khác homepage cũ. Custom-field translation cho schema mới chưa tồn tại để xác minh.

**Options:** A. Giữ existing translations, bổ sung danh sách chuỗi mới để người dùng duyệt; B. Đưa hoàn thiện EN homepage/landing vào scope triển khai; C. Chỉ migration kỹ thuật và công bố rõ phần EN chưa dịch từ trước.

**Recommended option:** A, với kiểm tra regression VI/EN và người duyệt nội dung được xác định; chọn B nếu cần EN hoàn chỉnh ngay release đầu. Không bật thêm tiếng Nhật chỉ vì còn bảng cũ.

## Q5 — Nút Light/Dark và language switcher

**Question:** Mode mặc định lần đầu là light, dark hay theo hệ điều hành; nút đổi mode và ngôn ngữ sẽ đặt ở đâu trên header desktop/mobile?

**Why it matters:** Preview chưa có cả hai switcher. Header đã có nav/search/CTA; vị trí và mặc định ảnh hưởng pixel-close, bố cục mobile và tránh flash khi tải.

**What you found:** Hai preview là entrypoints riêng, chưa có preference storage hoặc prefers-color-scheme logic. Live language switcher nằm trong menu, floater tắt. Bạn đã yêu cầu một theme có switch, chưa duyệt vị trí/mode ban đầu.

**Options:** A. Theo hệ điều hành lần đầu, nhớ lựa chọn; B. Light mặc định, nhớ lựa chọn; C. Dark mặc định, nhớ lựa chọn. Vị trí có thể ở cụm header-actions desktop và menu mobile, hoặc giữ language switcher như live.

**Recommended option:** A về cơ chế nếu không có ưu tiên thương hiệu; header-actions/menu mobile là vị trí đề xuất cần duyệt trực quan, giữ các thành phần đã chốt của hero.

## Q6 — Hành vi production của các phần placeholder

**Question:** Blog dùng ba bài mới nhất hay ba bài do admin chọn; các nút dự án mở trang detail hay giữ modal; contact CTA dùng trang Liên hệ/form nào; social/team contacts nào được bật?

**Why it matters:** Đây là data/action mapping, không phải redesign. Không thể biến ví dụ preview thành bài published hoặc tự gắn liên kết chưa được duyệt.

**What you found:** Preview blog ghi rõ editorial examples; JS detail/contact/social còn placeholder. Team buttons aria-disabled dù Elementor có contacts. Spec cũ đề xuất ba bài mới nhất. Có CF7 trên Page 320, nhiều form trong DB; menu preview chỉ anchors khác menu live.

**Options:** A. Blog latest Posts, project links tới existing objects, contact về `/lien-he/` với form hiện tại, chỉ bật social/team links được duyệt; B. Curated Posts + giữ modal projects; C. Cung cấp bảng CTA/link riêng.

**Recommended option:** A làm đề xuất mapping để duyệt, tuyệt đối chưa thay nội dung/behavior preview. Nếu cần pixel-close card content cố định thì chọn curated thay latest.

## Q7 — Menu homepage so với menu live

**Question:** Menu homepage mới giữ 8 anchor như preview và các trang trong dùng menu live, hay dùng một menu thống nhất; footer navigation sẽ map sang menu mới nào?

**Why it matters:** Giữ menu 69 nguyên trạng không tạo ra header preview; đổi menu 69 thành anchors sẽ thay navigation toàn site và có thể mất đường tới pages/categories.

**What you found:** Menu 69/primary+mobile gồm Pages, taxonomy submenu và TranslatePress switcher. Preview có 8 anchor, Sản phẩm/Dự án cùng `#projects`. Footer hiện dùng UX links trong block 703, không phải menu location đã assign; preview footer có hai cột navigation khác.

**Options:** A. Menu homepage riêng theo preview, giữ menu nội dung/site hiện hữu; B. Một menu mới hỗ trợ cả anchor homepage và URL nội dung; C. Bảng mapping menu do người dùng cung cấp.

**Recommended option:** A để giữ homepage sát preview và không mất navigation cũ; mọi menu mới/assignment chỉ tạo sau khi được phép.

## Q8 — Runtime production và bộ plugin dùng để kiểm thử

**Question:** Production đang chạy PHP phiên bản nào, và bộ ACF Pro/TranslatePress Business/Elementor Pro chuẩn sẽ dùng cho staging có sẵn không?

**Why it matters:** Version header/minimum PHP không đủ chứng minh compatibility. ZIP theme không được đóng kèm premium plugins hay code thay đổi license state của source local.

**What you found:** CLI local 7.4.33; core và các dependency chính minimum 7.4. Chưa xác minh PHP Apache/production. Source local ACF Pro/TranslatePress Business có đoạn sửa license options/request handling; không xác minh được quyền sử dụng/source distribution qua audit này.

**Options:** A. Cung cấp thông số hosting và staging dùng plugin chính thức đã được cấp quyền; B. Kiểm thử tạm với local và ghi rõ chưa đủ điều kiện release production.

**Recommended option:** A trước compatibility/release QA. Không nâng/hạ PHP, cài/thay plugin hoặc thay license trong Phase 1.

## Q9 — Contact data ngoài homepage và integrations

**Question:** Có đưa việc đồng bộ contact page cũ, Messenger/Zalo/Umami và luồng login vào phạm vi theme mới, hay chỉ giữ contact homepage đã duyệt?

**Why it matters:** Các phần này nằm ngoài homepage preview nhưng phụ thuộc footer/plugin hiện tại; bỏ child theme sẽ làm mất code nhúng, giữ cả widget plugin và custom có thể bị trùng.

**What you found:** Footer 703 và preview dùng 63 Phan Đăng Lưu; contact page 320 local còn 384 2/9 Street. Child footer nhúng Messenger/phone/Zalo/Umami; plugin support-chat và Nextend/WooCommerce active. Chưa kiểm thử tác dụng của từng integration hoặc yêu cầu login thực tế.

**Options:** A. Bảo toàn integrations đang dùng sau khi xác nhận và đồng bộ contact trong scope riêng; B. Theme mới chỉ cung cấp homepage contact đã duyệt, integrations do plugin quản lý; C. Danh sách integrations giữ/bỏ do người dùng cung cấp.

**Recommended option:** C để map chính xác; giữ địa chỉ homepage đã duyệt, không hỏi lại địa chỉ đó và không tự sửa nội dung trang khác.

---

Sau khi tạo đủ bốn tài liệu, dừng ở Phase 1. Không có câu hỏi nào ở đây cho phép tự chọn phương án sau một khoảng thời gian chờ.
