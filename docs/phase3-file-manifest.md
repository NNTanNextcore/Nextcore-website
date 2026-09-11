# Phase 3 — File manifest

Ngày 10/09/2026. Thư mục gốc theme: `wp-content/themes/nextcore-theme/`. Tổng cộng **84 files** trong theme.

**implemented**: chức năng được triển khai ở phạm vi nền tảng Phase 3, không đồng nghĩa đã QA runtime production.
**skeleton**: có code/wrapper an toàn, còn phần hoàn thiện Phase 4.
**placeholder**: chỗ dành sẵn, không có tính năng hoàn chỉnh; assets placeholder không enqueue.

| File (tương đối với thư mục theme) | Purpose | Status |
|---|---|---|
| `404.php` | Native 404 without redirects | implemented |
| `acf-json/README.md` | Empty schema storage | placeholder |
| `archive.php` | Native main query and pagination | skeleton |
| `assets/css/base.css` | Scoped native base, focus and accessibility | implemented |
| `assets/css/compatibility.css` | Legacy browser control scheme; authored colors retained | implemented |
| `assets/css/content.css` | Scoped native content | implemented |
| `assets/css/footer.css` | Responsive five-region footer | implemented |
| `assets/css/layout.css` | Structural responsive header and gapless nested menus | implemented |
| `assets/css/services.css` | Reserved services CSS | placeholder |
| `assets/css/team.css` | Reserved team CSS | placeholder |
| `assets/css/testimonials.css` | Reserved testimonials CSS | placeholder |
| `assets/css/tokens.css` | Light/dark tokens with no-JS system fallback | implemented |
| `assets/fonts/README.md` | Reserved local fonts assets | placeholder |
| `assets/images/README.md` | Reserved local images assets | placeholder |
| `assets/js/home.js` | Reserved home JavaScript | placeholder |
| `assets/js/integrations.js` | Reserved integrations JavaScript | placeholder |
| `assets/js/media.js` | Reserved media JavaScript | placeholder |
| `assets/js/navigation.js` | WP hierarchy dropdown and mobile accordion | implemented |
| `assets/js/testimonials.js` | Reserved testimonials JavaScript | placeholder |
| `assets/js/theme-init.js` | Early mode resolution, storage guard, system and tab sync | implemented |
| `assets/js/theme-switch.js` | Native keyboard-capable buttons synchronized to mode | implemented |
| `assets/video/README.md` | Reserved local video assets | placeholder |
| `category.php` | Native main query and pagination | skeleton |
| `comments.php` | Standard password-aware comments | implemented |
| `docs/asset-licenses.md` | Asset provenance placeholder | placeholder |
| `docs/data-setup.md` | Approved menu and future data setup | implemented |
| `docs/dependencies.md` | Dependencies and public adapter references | implemented |
| `docs/installation.md` | Installation boundaries | implemented |
| `docs/migration-checklist.md` | Explicit outstanding implementation and QA | implemented |
| `docs/rollback.md` | Rollback boundary | implemented |
| `footer.php` | Native footer and standard hook | skeleton |
| `front-page.php` | Ordered native homepage parts | skeleton |
| `functions.php` | Bootstrap modules | implemented |
| `header.php` | Native header with desktop/mobile navigation | skeleton |
| `home.php` | Native main query and pagination | skeleton |
| `inc/acf.php` | ACF JSON load path and options page without fields or writes | skeleton |
| `inc/compatibility.php` | Standard content rendering with isolated legacy wrapper | skeleton |
| `inc/enqueue.php` | Local enqueue and early mode initialization | implemented |
| `inc/helpers.php` | Safe option, contact and WP menu helpers | implemented |
| `inc/integrations.php` | Disabled integration ownership contract | skeleton |
| `inc/multilingual.php` | Guarded TranslatePress adapter and localized anchors | implemented |
| `inc/setup.php` | Theme supports, six menu locations, sidebar | implemented |
| `index.php` | Native main query and pagination | skeleton |
| `languages/README.md` | Translation catalog work pending | placeholder |
| `page-custom.php` | Retain existing page template filename | skeleton |
| `page-outsource.php` | Retain existing page template filename | skeleton |
| `page-slide_left_custom.php` | Retain existing page template filename | skeleton |
| `page.php` | Safe singular main Loop | skeleton |
| `search.php` | Native main query and pagination | skeleton |
| `searchform.php` | Localized native WordPress search | implemented |
| `sidebar.php` | Optional registered sidebar | implemented |
| `single-dich-vu.php` | Safe singular main Loop | skeleton |
| `single.php` | Safe singular main Loop | skeleton |
| `style.css` | WordPress metadata | implemented |
| `taxonomy-danh-muc-dich-vu.php` | Native main query and pagination | skeleton |
| `template-parts/content/archive-heading.php` | Contextual listing heading | implemented |
| `template-parts/content/builder.php` | Reserved content/builder part | placeholder |
| `template-parts/content/card.php` | Real object card links | skeleton |
| `template-parts/content/gallery.php` | Reserved content/gallery part | placeholder |
| `template-parts/content/none.php` | Empty query state | implemented |
| `template-parts/content/page.php` | Singular title/content wrapper | skeleton |
| `template-parts/content/post.php` | Reserved content/post part | placeholder |
| `template-parts/global/breadcrumbs.php` | Reserved global/breadcrumbs part | placeholder |
| `template-parts/global/contact-details.php` | Approved contact fallbacks with ACF adapter | implemented |
| `template-parts/global/floating-contact.php` | Reserved global/floating-contact part | placeholder |
| `template-parts/global/footer-columns.php` | Five footer regions | skeleton |
| `template-parts/global/language-switch.php` | Public TranslatePress language links | implemented |
| `template-parts/global/navigation.php` | Reserved global/navigation part | placeholder |
| `template-parts/global/page-title.php` | Reserved global/page-title part | placeholder |
| `template-parts/global/theme-switch.php` | Accessible shared mode control | implemented |
| `template-parts/home/about-video.php` | Homepage about-video slot | placeholder |
| `template-parts/home/blog.php` | Homepage blog slot | placeholder |
| `template-parts/home/cta.php` | Homepage cta slot | placeholder |
| `template-parts/home/hero.php` | Homepage hero slot | placeholder |
| `template-parts/home/partner.php` | Homepage partner slot | placeholder |
| `template-parts/home/projects.php` | Homepage projects slot | placeholder |
| `template-parts/home/services.php` | Homepage services slot | placeholder |
| `template-parts/home/stats.php` | Homepage stats slot | placeholder |
| `template-parts/home/team.php` | Homepage team slot | placeholder |
| `template-parts/home/technology.php` | Homepage technology slot | placeholder |
| `template-parts/home/testimonials.php` | Homepage testimonials slot | placeholder |
| `template-parts/service/card.php` | Reserved service/card part | placeholder |
| `template-parts/service/detail.php` | Reserved service/detail part | placeholder |
| `template-parts/service/lark-content.php` | Reserved service/lark-content part | placeholder |

## Báo cáo ngoài theme

| File | Purpose | Status |
|---|---|---|
| docs/phase3-implementation-report.md | Kết quả triển khai, validation, giới hạn và TODO | implemented |
| docs/phase3-file-manifest.md | Danh mục từng file | implemented |
| docs/phase3-open-questions.md | Điểm môi trường chưa xác minh trước release | implemented |

Không tạo screenshot.png vì chưa có visual Phase 4. Các thư mục asset/ACF/languages dùng README để giữ cấu trúc, không tạo file ảnh/font/JSON giả.

