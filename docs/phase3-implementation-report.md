# Phase 3 — Implementation report

Ngày 10/09/2026. **Hoàn tất skeleton; dừng để review. Chưa activate theme.**

## Phạm vi và files

Tạo standalone Nextcore Theme 0.1.0 tại `wp-content/themes/nextcore-theme/`: **84 files**, gồm **56 PHP**, **10 CSS**, **7 JS**, **11 Markdown**. [Manifest](phase3-file-manifest.md) liệt kê từng file và trạng thái.

Chỉ thêm thư mục theme và ba báo cáo Phase 3. Không sửa preview, theme cũ, parent theme, plugins, database hay production; không seed/migrate, không ZIP, không đăng ký lại CPT/taxonomy. Các quyết định khóa trong yêu cầu Phase 3 thay các câu hỏi thiết kế cũ của Phase 2.

## Modules và kiến trúc đã triển khai

- functions.php chỉ bootstrap 7 modules: setup, helpers, multilingual, enqueue, acf, compatibility, integrations.
- Metadata standalone; textdomain nextcore-theme; title-tag, thumbnails, custom-logo, HTML5, responsive embeds; sidebar tùy chọn.
- Đăng ký đủ 6 menu locations; wp_nav_menu giữ hierarchy thật, fallback_cb=false, không tự gán hay tạo menu.
- Header có brand, desktop navigation/actions, search form, ngôn ngữ, mode switch, CTA; mobile panel có menu và switches riêng. Đây là shell chung cho hai modes, không duplicate homepage DOM.
- JS dùng submenu thật của WordPress; expand button riêng giữ top-level link, aria-expanded/controls, hover có delay đóng, click, ArrowDown, Escape, focus-out, click ngoài, mobile accordion. Không có submenu business HTML hardcode.
- Footer native 5 vùng brand/about/support/contact/social và copyright cuối. Contact đọc G12 với fallback được duyệt; social trống đến khi có links thật.
- Early initializer đồng bộ wp_head priority 0 trước theme CSS; data-theme, meta theme-color, system fallback, localStorage nextcore-theme-mode, guarded storage, system-change và cross-tab sync. Hai buttons đồng bộ aria-pressed. CSS vẫn có system fallback khi JS bị tắt. Media switching chưa triển khai.
- TranslatePress dùng adapter public, guard khi plugin missing; localized homepage anchors trên inner pages, search action theo ngôn ngữ; không hardcode prefix ngôn ngữ.
- Legacy Elementor đi qua the_content trong .legacy-content theo metadata, không gọi renderer riêng hoặc parent helpers. CSS native scoped, không invert nội dung builder. Lark/legacy shortcode/gallery để Phase 4.
- ACF bổ sung JSON load path và options-page registration; không field groups, values, save-path override hay tự đồng bộ.
- Integrations chỉ ownership contract và hook, enabled=false; không tải Messenger/Zalo/Umami, không tạo floating widget.
- Front page ghép 11 section parts đúng thứ tự preview. Contact anchor ở footer. Section chỉ có heading/slot, không phải homepage hoàn chỉnh.
- Singular có main Loop, nội dung và comments; listings dùng main query/pagination; search native, 404 không redirect.

## Validation thực hiện

| Kiểm tra | Kết quả |
|---|---|
| PHP CLI 7.4.33 lint toàn bộ 56 files | PASS, 0 lỗi |
| Node --check 7 JavaScript files | PASS |
| Required classic files | PASS, đủ 21/21 |
| Scan PHP/CSS/JS: parent dependency, localhost, language-prefix hardcode, activation/data writes | PASS, không match |
| Bootstrap với WordPress stubs, không ACF/TranslatePress | PASS, 10 assertions |
| Theme preference qua Node VM | PASS, 9 assertions |

PHP stub checks kiểm tra fallback option, localized URL/home/contact, missing language switcher, options-page khi thiếu ACF, giữ JSON load paths cũ, integrations disabled, inner anchor, non-anchor link và hooks. Không load wp-load.php, không kết nối database.

JS checks kiểm tra system dark, thay đổi system, override không bị system ghi đè, xóa storage quay về system, saved override, giá trị sai, storage bị chặn, đồng bộ tab và từ chối mode sai.

**Giới hạn:** lint và isolated tests không chứng minh runtime/plugin/visual compatibility. Chưa activate nên chưa QA render WordPress thực, menu trên trình duyệt, Elementor, TranslatePress EN, responsive hay CSP/cache production. Không tuyên bố pixel-perfect hoặc release-ready.

## TODO Phase 4

- Chuyển visual/assets đã duyệt từ preview; typography, layout, modes và media switching.
- Hoàn thiện ACF groups/data consumers; chỉ seed/migrate khi được phép.
- Latest 3 published Posts; project object links thật; GM CTA https://gm-group.vn/.
- Testimonial carousel/modal, hero/video, footer và social hoàn chỉnh.
- Các nội dung legacy/shortcodes/Lark CSS/gallery theo compatibility plan; bảo toàn authored colors.
- Cấu hình hierarchy/menu assignments theo theme docs/data-setup.md ở phase được phép.
- Runtime QA VI/EN, keyboard/mobile, no-flash, 1440/1200/1024/768/480/375px.
- Hoàn thiện catalogs/POT, asset licenses và screenshot.png.
- Reconcile production, xác minh PHP/plugin/cache/CSP và integration owner trước release.

Các quyết định đã khóa về contact, menu, GM, 404 cũ, EN SEO/search và Plety không được hỏi lại. Điểm môi trường chưa xác minh nằm ở [Open questions](phase3-open-questions.md).
