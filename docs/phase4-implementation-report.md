# Phase 4 — Implementation report

Ngày 11/09/2026. **Homepage đã port; dừng để review. Theme chưa activate.**

## Kết quả

Một homepage DOM, đủ 11 section đúng thứ tự preview: Hero → About Video → Stats → Services → Technology → Projects → Strategic Partner → Testimonials → Team → Blog → CTA. Footer native ngoài main.

Port layout/spacing/type/crop/motion từ hai preview, không redesign. Header cập nhật menu và controls theo quyết định mới. Nội dung đọc ACF nếu có, dùng approved fallback khi chưa cấu hình; không seed/ghi database.

Theme hiện có 114 files; 58 PHP và 7 JS đã kiểm tra cú pháp. **26 assets được copy, không move/xóa source**, SHA-256 khớp 26/26. Không copy preview/checks, scripts, browser profile hoặc mock Blog assets không dùng.

## Files changed / source manifest

Các đường dẫn dưới đây tương đối với wp-content/themes/nextcore-theme/.

| File | Nội dung thay đổi |
|---|---|
| `style.css` | Version 0.2.0 |
| `functions.php` | Bootstrap thêm home-data |
| `header.php` | Header visual, search, WP menus, switches |
| `footer.php` | Native five-region footer |
| `front-page.php` | 11 parts đúng thứ tự; footer ngoài main |
| `inc/home-data.php` | Mới: approved repeater fallbacks |
| `inc/helpers.php` | ACF consumers, media, published object/term resolution, development fallback |
| `inc/enqueue.php` | Scoped CSS + homepage-only JS, early media initialization |
| `inc/setup.php` | Menu expand controls và IDs riêng cho từng location |
| `template-parts/home/hero.php` | Port visual + data consumer cho hero |
| `template-parts/home/about-video.php` | Port visual + data consumer cho about-video |
| `template-parts/home/stats.php` | Port visual + data consumer cho stats |
| `template-parts/home/services.php` | Port visual + data consumer cho services |
| `template-parts/home/technology.php` | Port visual + data consumer cho technology |
| `template-parts/home/projects.php` | Port visual + data consumer cho projects |
| `template-parts/home/partner.php` | Port visual + data consumer cho partner |
| `template-parts/home/testimonials.php` | Port visual + data consumer cho testimonials |
| `template-parts/home/team.php` | Port visual + data consumer cho team |
| `template-parts/home/blog.php` | Port visual + data consumer cho blog |
| `template-parts/home/cta.php` | Port visual + data consumer cho cta |
| `template-parts/global/icons.php` | Mới: local SVG symbols từ preview |
| `template-parts/global/theme-switch.php` | Accessible compact mode control |
| `template-parts/global/contact-details.php` | G12 approved contact output |
| `assets/css/tokens.css` | Scoped visual/mode styles |
| `assets/css/base.css` | Scoped visual/mode styles |
| `assets/css/layout.css` | Scoped visual/mode styles |
| `assets/css/services.css` | Scoped visual/mode styles |
| `assets/css/team.css` | Scoped visual/mode styles |
| `assets/css/testimonials.css` | Scoped visual/mode styles |
| `assets/css/footer.css` | Scoped visual/mode styles |
| `assets/css/content.css` | Scoped visual/mode styles |
| `assets/css/light.css` | Mới: Scoped visual/mode styles |
| `assets/css/native.css` | Mới: Scoped visual/mode styles |
| `assets/js/navigation.js` | Null-guarded navigation lifecycle |
| `assets/js/home.js` | Null-guarded home lifecycle |
| `assets/js/media.js` | Null-guarded media lifecycle |
| `assets/js/testimonials.js` | Null-guarded testimonials lifecycle |

Asset manifest đầy đủ: [asset-licenses.md](../wp-content/themes/nextcore-theme/docs/asset-licenses.md).

Docs cập nhật trong theme: installation.md, data-setup.md, dependencies.md, migration-checklist.md, asset-licenses.md; languages/README.md và ba README trong assets/images, video, fonts.

Artifacts ngoài theme: docs/checks/phase4/ gồm CLI renderer, menu fixture chỉ trong RAM, browser scripts, JSON results, static HTML và screenshots. Không enqueue/đóng gói QA scripts vào theme.

## Features

- Light/Dark: system-first, localStorage nextcore-theme-mode, root data-theme; controls desktop header/mobile menu đồng bộ, không reload hoặc thay editorial DOM.
- Images được chọn khi markup tới bằng initializer đồng bộ đầu head. Video chỉ gắn src của mode active khi cần phát; hide frame cũ trước đổi src, pause offscreen/hidden, reduced-motion bỏ src và giữ poster. Không đổi về poster khác mode lúc buffering/loop.
- Hero giữ asset/crop, Be Vietnam Pro 700, hai dòng title, slogan/CTA/scroll cue.
- Services resolve Page/terms bằng core APIs. Khi không có published target hợp lệ, giữ card không tạo dead link.
- Technology giữ 8 marks/order, manual pause và reduced-motion wrap; không CDN.
- Projects: hai Posts Olympia/Affiliate resolve qua published objects; configured relationships được ưu tiên. **Portal chưa link** theo xác nhận người dùng; không dùng local draft ID 1809.
- GM CTA đi https://gm-group.vn/ trong cùng tab; không còn modal placeholder.
- Testimonials: 4 quote thật SSR đầy đủ; 3/2/1 card, prev/next/dots/swipe, clamp, dialog full quote và Escape.
- Team: ảnh thật/card trắng/tam giác đỏ; đã bật email/phone theo production được người dùng cho phép đối chiếu.
- Blog: WP_Query post/publish, posts_per_page=3, date DESC rồi ID DESC, ignore sticky, reset postdata. Không chèn fake Posts khi thiếu bài.
- CTA đi Contact Page published. Footer đúng contact đã duyệt; Facebook/TikTok là profile thật, không dùng share URLs hoặc fake LinkedIn/YouTube.
- Search là WordPress GET search với action theo ngôn ngữ. TranslatePress xử lý SSR; UI controls i18n.
- CSS giới hạn native areas; không đổi nội dung/paint Elementor.

## Menu và data chưa gán

Đã dùng wp_nav_menu hierarchy và core Walker; **không tạo/gán menu objects trong DB**. Fallback chỉ dùng ở development và chỉ có anchor links. Browser QA dùng menu fixture RAM để test đầy đủ Dịch vụ/Tin tức và 9 mục mobile; không được coi fixture là menu production đã cấu hình.

Các field groups/values ACF chưa đăng ký/seed. Chi tiết từng consumer ở [data status](phase4-data-status.md). Integrations tiếp tục disabled theo skeleton; Phase 4 không bật provider scripts.

## Validation

| Check | Kết quả |
|---|---|
| PHP lint toàn bộ 58 PHP theme, CLI 7.4.33 | PASS, 0 lỗi |
| Node --check toàn bộ 7 JS theme | PASS |
| Scan parent dependency, localhost/prefix EN hardcode, absolute production uploads, activation/data-write calls trong source PHP/CSS/JS | PASS, không match |
| 1440, 1200, 1024, 768, 480, 375px × dark/light | PASS 12/12, không document overflow, không broken images/JS exceptions |
| 11 section width/height so với preview cùng viewport và reduced-motion | Chênh lệch không quá 2px ở 12 cases |
| Menu/media/carousel/EN/DOM interaction assertions | PASS 30/30 |
| No-JS, desktop/mobile × system light/dark | PASS 4/4; ảnh đúng system, video không src, menu normal-flow |
| Asset SHA-256 | PASS 26/26 |
| Hai HTML + CSS/JS preview đã đọc trước port | Không thay đổi |
| Stored active stylesheet đọc trực tiếp DB sau QA | flatsome-child |

Evidence: [responsive JSON](checks/phase4/browser-results.json), [interaction JSON](checks/phase4/interaction-results.json), [no-JS JSON](checks/phase4/noscript-results.json), [visual parity report](phase4-visual-parity-report.md).

### QA mechanism

CLI renderer dùng request-local filters cho template/stylesheet, không switch_theme. Chỉ bật ACF/TranslatePress trong process kiểm thử; plugins khác không bootstrap. Query filter thay toàn bộ SQL không phải SELECT/SHOW/DESCRIBE/EXPLAIN bằng SELECT rỗng, nên các attempt cache-write của core/plugins không được thực hiện. Lượt cuối: 85 attempts bị chặn ở VI, 87 ở EN với menu fixture; 4 khi không plugin. Không chạy WP cron.

Có render không plugin để kiểm tra missing-plugin fallback, và render với plugin local cho VI/EN. TranslatePress output buffer được chạy trước lưu snapshot. Snapshot loại scripts không thuộc theme để review static không kích hoạt AJAX/telemetry plugin. Browser QA tiếp tục chặn dynamic/non-GET requests; không điều hướng vào WordPress live khi test.

## Known limitations / quyết định đã nhận

- Chưa activate, migrate/seed database, ZIP hoặc deploy. Theme active vẫn flatsome-child.
- Portal cần relationship published sau reconcile; hiện card không link theo duyệt.
- Menu assignments và ACF editor schema/values còn chờ phase được phép.
- Bản EN dùng dictionary hiện có; một số chuỗi mới chưa có bản dịch. **Người dùng đã chọn soạn bản dịch để duyệt ở bước tiếp theo, sau Phase 4**. Chưa ghi translation database.
- Không chứng nhận toàn bộ Elementor/legacy pages, production cache/CSP/plugin baseline hoặc integrations; chỉ QA homepage trong môi trường local được mô tả.
- Header có controls mới nên khác preview cũ ở số mục menu, dropdown, language/mode controls; dưới 1200px chuyển mobile menu để chứa đủ controls.
- Footer dùng hai social profile xác minh được; không giữ placeholder social buttons.
- QA thực hiện bằng Chrome headless; chưa có cross-browser Safari/Firefox QA.

## Review

Snapshot VI: [mở bản QA](checks/phase4/rendered-plugins-menu.html).
Snapshot EN: [mở bản QA EN](checks/phase4/rendered-plugins-en-menu.html).

Đây là HTML review kết xuất từ theme, không phải activation. Các link detail/contact vẫn trỏ objects WordPress local thật; các ngôn ngữ/menu assignments của site live không được thay đổi.

