# Builder compatibility — rollout đầu

Ngày 10/09/2026. Giữ Elementor tạm cho một số trang theo quyết định người dùng, nhưng **mọi wrapper của theme mới phải độc lập Flatsome trước activation**. Chưa sửa content, template hoặc plugin.

Nguồn: current-site-audit.md; đọc lại child templates; truy vấn chỉ đọc `_elementor_data` của About/Contact/Outsource/Lark/đặt sân/Affiliate trong Phase 2. ID dưới đây là candidates để đối chiếu production snapshot, không hardcode routing.

## 1. KEEP TEMPORARILY WITH ELEMENTOR

| Page / URL | Dependencies xác minh local | Risk khi thay theme | Required compatibility wrapper |
|---|---|---|---|
| Contact 320 `/lien-he/` | Elementor image/HTML/icon-box; CF7 shortcode trong nội dung; page-custom.php | CSS child cũ, icon/font, map và địa chỉ cũ; mất banner nếu template assignment không tồn tại | Native page-custom.php + page-title/breadcrumb + the_content(); enqueue plugin theo lifecycle, scoped legacy CSS; global header/footer native |
| Outsource 1133 `/outsource/` | Elementor/Pro animated-headline, icon-box; Elementor Addon Elements `eae-timeline`; page-outsource.php | Timeline/animations phụ thuộc add-on; CTA/sidebar/header gọi Flatsome trong wrapper cũ | Native page-outsource.php giữ banner/body/timeline/CTA/sidebar behavior; body Elementor qua the_content(); thay Flatsome breadcrumb/sticky/grid helpers |
| Ứng dụng đặt sân 1616 `/dich-vu/ung-dung-dat-san/` | Elementor/Pro image-carousel, gallery, animated-headline, counter, image-box | Lightbox/carousel CSS/JS, broad theme styles xung đột, title/body duplicated | single-dich-vu.php → content/builder; một title shell đúng layout, plugin render body, native footer/CTA |
| Wordpress aff atv 1761 `/dich-vu/wordpress-aff-atv/` | Elementor/Pro heading/text/image/toggle/media-carousel | Carousel/toggle dynamic dependencies; nhầm object với blog Affiliate 1548 | single-dich-vu.php → content/builder; scoped styles, preserve body và object ID/permalink |
| Services Page 316 `/dich-vu/` | Elementor Pro posts widget; page-slide_left_custom.php | Listing query/style, sidebar và pagination; bị archive cùng slug chiếm route | Native page-slide_left_custom.php + sidebar + the_content(); giữ là Page, CPT has_archive=false |
| About 314 `/ve-chung-toi/` — phần không UX | Elementor HTML/counters/link-in-bio/testimonial-carousel | Có dependency UX thật trong shortcode widget, nên không thể giữ **toàn trang nguyên trạng** | Chỉ giữ những widgets độc lập sau task ABOUT-01 dưới đây; native page-custom wrapper |

“Keep” nghĩa giữ data/body renderer của plugin, **không giữ PHP wrapper child cũ** và không đảm bảo layout khi chưa QA production content. Không thay all inner pages bằng một clone homepage.

## 2. MUST MIGRATE BEFORE ACTIVATING STANDALONE THEME

| Phạm vi | Vì sao không thể giữ nguyên | Task bắt buộc |
|---|---|---|
| Homepage `/` | Elementor + Revolution khác approved preview/native ACF target | HOME-01: front-page native, G01–G14 schema/values, no Elementor/Revolution render cho homepage |
| Header toàn site | Child gọi flatsome_html_classes/header_classes/main_classes và parent header-wrapper; theme mods Header Builder | SHELL-01: header native, logo/menu location mapping, switchers, preserve hooks |
| Footer toàn site | Child do_action flatsome_footer; block 703 có section/row/col/ux_image/ux_text/share/ux_menu | FOOTER-01: native footer theo preview, G12 + menus; không require blocks CPT/UX renderer để hiện footer |
| About `/ve-chung-toi/` | **Phase 2 xác minh shortcode widget trong active _elementor_data chứa `[section]`, `[row]`, `[col]`, `[ux_image]`, `[gap]`, `[button]`**; không chỉ stale post_content | ABOUT-01: chuyển đúng intro UX fragment sang native Elementor widgets hoặc HTML có scope, giữ text/media/layout; phần khác vẫn Elementor. Chưa chọn công cụ chuyển tự động hay ghi DB |
| Lark `/dich-vu/lark/` | Không có builder data hữu dụng; body HTML + custom_css khoảng 133k ký tự; single wrapper và styles phụ thuộc child/parent | LARK-01: native service wrapper, kiểm tra HTML/assets/fonts và audit scope CSS; tách style compatibility, giữ body. Đây không phải “giữ Elementor” |
| `single.php`, `single-dich-vu.php` | flatsome_option, template-parts/posts parent, gallery transient shared, sidebar helpers | DETAIL-01: native Loop/content/gallery/pagination/breadcrumb wrappers; không gọi hàm parent để tránh fatal |
| Taxonomy/archive/blog/sidebar | Parent grid/helpers/templates/sidebar styles | ARCHIVE-01: native taxonomy/category/home/archive/sidebar; mixed post types đúng query; giữ term URLs |
| Template assignments page-custom/page-slide_left_custom/page-outsource | Stored filenames sẽ mất nếu không có trong standalone | TEMPLATE-01: giữ các filenames như architecture và viết wrapper độc lập; không tự đổi postmeta tùy tiện |
| Contact data/integration cluster | Contact Page có thể còn 384 2/9; scripts/cluster đang nhúng trong child footer | CONTACT-01 và INTEGRATION-01 riêng: map canonical contact, xác minh map và một owner cho widget/script |

ABOUT-01 là thay renderer phần phụ thuộc UX, không redesign About hoặc yêu cầu chuyển toàn About sang ACF. Có thể giữ dữ liệu Elementor còn lại; snapshot trước/sau và translation QA bắt buộc. LARK-01 không tùy tiện “sanitize” toàn CSS bằng string prefix vì có global selectors/keyframes/media/font-face; phải phân loại rule cần dùng.

## 3. Các trang không cần Elementor để bảo toàn

- Posts 914/929/1510/1548/1732: core content + ACF gallery nếu có. Renderer native, giữ terms/permalink/attachments, không chuyển thành product CPT.
- Service 1550/1553 và Portal production: native single-dich-vu body theo core; xác minh data production mới trước chọn renderer. Portal không được seed từ draft post local cùng ID.
- Blog index Page 318: home.php theo posts-page, dù có Elementor meta; không có widget hữu dụng trong local audit.
- `/demo/plety/`: loại khỏi cam kết port theo user; không thêm template cũ hoặc dependencies Tailwind/SceneAI chỉ để giữ test này. Thiếu template không tự đảm bảo WordPress trả 404 nếu Page vẫn published; không lập activation hook xóa Page. User cho phép 404 chứ chưa yêu cầu thao tác xóa/unpublish.
- `/demo/`: vẫn là Page route trong production sitemap audit; không gộp vào việc loại Plety.

## 4. Wrapper contract

1. Giữ wp_head/wp_body_open/wp_footer/body_class/language_attributes và the_content đúng Loop, một lần cho mỗi body. Không lấy `_elementor_data` rồi tự parse thành HTML frontend.
2. Native header/footer ở ngoài `.legacy-content`; legacy styles không ghi đè home sections và global shell. Tránh broad h1/button/img resets vào builder body.
3. Bảo toàn template banner/sidebar/CTA có thật, không in thêm một title nếu builder đã có heading tương đương. Portal hiện có multi-H1 là baseline content cần task riêng nếu sửa, không tự rewrite bài trong migration theme.
4. Giữ plugin frontend hooks/assets cần cho từng widget. Homepage native không render Elementor body, nhưng phải audit trước khi dequeue plugin asset theo page; không blanket dequeue wp_head/wp_footer hooks hoặc jQuery.
5. Giữ core/block CSS cần cho core content. Các CSS child custom có giá trị phải scope/port local; không link `/flatsome-child/assets/...` từ standalone.
6. Breadcrumb thay bằng native/Yoast-compatible wrapper có điều kiện, không `[breadcrumb_page]` gọi Flatsome. Gallery ảnh theo post ID, không set transient chung trong lượt xem.
7. Nếu plugin bắt buộc thiếu, admin preflight báo rõ, public không fatal; fallback không được coi là đã giữ layout đầy đủ. Không tự activate/download plugin.

## 5. Light/Dark cho legacy content

Global shell và native templates dùng mode tokens. Elementor content có thể mang fixed light backgrounds/text/colors; **không tự invert hoặc đổi ảnh** để giả lập dark. Đề xuất rollout đầu giữ authored content colors trong `.legacy-content` ở cả modes, theme switch vẫn áp shell. Đây là giới hạn thiết kế cần Q6 duyệt; nếu yêu cầu full dark cho từng page builder thì cần page-by-page paint adaptation, không phải một CSS filter toàn cục.

## 6. Activation risks và gates

| Gate | Evidence cần trước khi activate |
|---|---|
| Production sync | Snapshot/config mới; resolve Portal/current service URLs; không local overwrite |
| UX removal | Không còn UX shortcode đang render ở các trang cam kết, hoặc renderer thay thế đã test; About intro/footer hiện đủ |
| Parent independence | Không undefined flatsome_* functions, get_template_part parent, URL asset parent, dependency Header Builder |
| Builders | Contact, Outsource, Services, About remaining, đặt sân, aff atv đều render cả desktop/mobile, widgets hoạt động |
| Lark | HTML/CSS/media đầy đủ, không style leak sang header/footer, page khác |
| Multilingual | Existing translations giữ, homepage mới thêm; menu/dialog/CTA EN đúng; không dynamic overwrite |
| Integrations | Một cluster/script owner, no duplicates, canonical phone/contact |
| URL/SEO | Same IDs/type/slugs, no new 404, canonical/hreflang/pagination tốt |

Không deactivate Elementor/Pro/add-ons/WooCommerce/TranslatePress trong rollout chỉ vì homepage không cần chúng. Không thể đồng thời yêu cầu parent-independent và retain runtime Flatsome PHP. Danh sách trên là scope rollout đề xuất cụ thể để review, không báo đã migrate.
