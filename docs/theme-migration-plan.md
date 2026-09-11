# Kế hoạch migration sang Nextcore Theme — đề xuất Phase 1

Ngày 10/09/2026. **Chỉ là kế hoạch**, chưa tạo theme/PHP/ZIP, chưa migrate content, chưa chỉnh active theme hoặc database. Các lựa chọn chưa chốt phải được trả lời trong [open-questions.md](open-questions.md) trước phase thực thi.

## 1. Nguyên tắc kiến trúc

1. Một standalone theme `Nextcore Theme`, dự kiến slug `nextcore-theme`; không khai báo parent Flatsome; Light/Dark trong cùng theme, một bộ content.
2. Homepage render theo `preview/index.html` và mode light đã duyệt, không tái thiết kế hoặc dùng live homepage cũ làm layout thay thế.
3. Giữ nguyên object IDs, post types, post_name, taxonomy keys, term slugs, menu objects, permalink options và TranslatePress database của nguồn production đã xác nhận.
4. Không đồng nhất sản phẩm/dự án với WooCommerce. Dữ liệu hiện trộn post/dich-vu; layout có thể riêng mà không đổi URL hoặc post type.
5. ACF Pro là dependency thực tế cho schema hiện tại và schema homepage đề xuất. Theme độc lập với parent không có nghĩa ZIP được chứa ACF Pro/TranslatePress/Elementor hoặc có thể tự tái tạo database.
6. Trong quá trình triển khai sau duyệt, giữ WordPress hooks chuẩn (head/body/footer/content), WordPress menu/link/query APIs, Yoast và TranslatePress. Không sao chép hàm Flatsome gọi trực tiếp vào theme standalone.

## 2. CURRENT WORDPRESS DATA → NEW NEXTCORE THEME

Các tên file dưới đây là **mapping đề xuất**, không phải file đã tạo.

| Dữ liệu hiện tại | Đích render đề xuất | Cách bảo toàn |
|---|---|---|
| Front Page ID 312, Elementor/Revolution | `front-page.php` + section template parts | Giữ Page ID và URL `/`; xuất DOM preview từ ACF. Giữ dữ liệu builder cũ để rollback; không xóa post_content/meta tự động |
| Normal Pages | `page.php` | Tiêu đề/body riêng, `the_content()` và featured image khi layout yêu cầu; không nhét các section homepage vào tất cả pages |
| About 314, Contact 320 | Templates riêng tương thích `page-custom.php` / content components | Giữ template assignment hoặc có mapping minh bạch; giữ content/forms và migration UX shortcode đã duyệt |
| Outsource Page 1133 | `page-outsource.php` | Giữ `/outsource/`, banner, benefits, services, process, strengths, CTA; ACF schema riêng nếu chuyển khỏi Elementor |
| Services Page 316 | Template riêng, có thể giữ tên `page-slide_left_custom.php` cho assignment hiện tại | `/dich-vu/` tiếp tục là Page listing, không bật CPT archive cùng slug |
| CPT `dich-vu` | `single-dich-vu.php` | Giữ CPT registration do ACF, title/body/excerpt/thumbnail/meta; chọn detail component theo layout đã duyệt |
| Product details đang là `dich-vu` | Product-detail component được chọn trong `single-dich-vu.php` | Portal/Lark/đặt sân/Affiliate có thể khác layout, vẫn `/dich-vu/{slug}/` |
| Product/project stories đang là `post` (1510/1548) | `single.php` và/hoặc content component phù hợp | Giữ root post permalink; không đổi thành `/product/` hoặc `/du-an/` |
| `danh-muc-dich-vu` terms | `taxonomy-danh-muc-dich-vu.php` | Giữ taxonomy gắn post + dich-vu; main query/pagination/thumbnail/excerpt |
| CPT service archive | Không tạo route `/dich-vu/` mới | has_archive đang false; Page dịch vụ và taxonomy archives đáp ứng nhu cầu listing |
| Blog posts | `single.php` | Giữ nội dung, categories/tags, ảnh đại diện, ACF gallery, comments nếu hiện có |
| Posts page 318 `/tin-tuc/` | `home.php` | WordPress posts query/pagination, không dựa vào Elementor meta rỗng |
| Blog categories | `category.php` | category slugs hiện tại; heading/archive/list/sidebar riêng |
| Other archives/tags | `archive.php`, `tag.php` nếu cần, fallback `index.php` | Không làm các route phụ biến thành homepage; giữ pagination/empty state |
| WordPress Search | `search.php` + search form | Search toàn site và kết quả có permalink; modal section-search preview cần được nối đúng hành vi được duyệt |
| Not found | `404.php` | HTTP 404 và nội dung 404 thực; không soft-404 bằng homepage |
| TranslatePress VI/EN | Cùng templates + plugin hiện hữu | Dùng URL/switcher do TranslatePress sinh; không nhân đôi posts hoặc tạo theme tiếng Anh |
| Nav menu 69 / locations | `wp_nav_menu()` | Giữ menu objects; preserve location names khi phù hợp; assignment là theme-specific nên phải map explicit |
| Footer UX Block 703 | Footer native theo preview + global ACF/menu | Reuse contact/brand, chuyển các link UX thành WP menu đã duyệt; không phụ thuộc UX shortcode renderer |
| Team Elementor link-in-bio | ACF repeater có cấu trúc + team component | Reuse tên/chức vụ/attachment IDs; không dùng ảnh avatar thay ảnh thật; contacts theo quyết định QA |
| Testimonials Elementor slides | ACF repeater + carousel hiện tại | Reuse 4 review, project labels, ảnh; chỉ đổi nguồn quản trị, giữ full text + modal thủ công |
| Homepage featured projects | ACF relationship/curated entries tới post/dich-vu | Giữ thứ tự 3 card và ảnh preview; lấy permalink object thực; Portal phải xác nhận dữ liệu production trước |
| Blog cards preview | Posts query/relationship theo lựa chọn được duyệt | Preview đang có 3 ví dụ không phải bài thật; không publish chúng như bài chính thức |
| Gallery `anh_chi_tiet` | Gallery component độc lập | Reuse array attachment IDs; bỏ phụ thuộc transient chung ghi trong page view |
| `custom_css` của Lark | Layout/style compatibility có scope | Đánh giá CSS thực tế trước khi tách, không bỏ 133k ký tự CSS rồi coi page vẫn tương đương |
| `redirect_service` | Giữ meta, chưa thêm redirect behavior | Field hiện rỗng và chưa thấy consumer; không phát minh redirect mới |
| Plety Page 1813 | Template landing riêng nếu được xác nhận trong scope | Nội dung hardcoded trong template, không thể giữ bằng `the_content()`; URL phân cấp cần bảo toàn nếu tiếp tục dùng |
| CF7 Contact | Giữ form đã dùng và shortcode/API render phù hợp | Không migrate submissions, không thay bằng form gửi mail giả; kiểm thử gửi chỉ khi được phép |

## 3. Schema ACF mới cần có

Chỉ đề xuất data model; chưa chọn field keys, chưa ghi database. Định nghĩa có thể đóng gói bằng ACF local JSON/registration ở phase sau; **định nghĩa field không mang theo field values hoặc translated strings**.

| Group đề xuất | Dữ liệu | Nguồn để reuse |
|---|---|---|
| Homepage hero | Eyebrow, title fragments, slogan, description, CTA labels/links, hero image theo mode | Preview đã duyệt; không copy headline sai từ spec cũ |
| Company video | Video + poster light/dark, heading, date, body, CTA | Preview, ảnh cauronglight.png, media local |
| Stats | 3 giá trị/labels/icons theo schema giới hạn | Preview và Elementor counter hiện tại cùng 35/25/20 |
| Services overview | 3 card title/description/icon/image/link | Preview + link mapping Page/term đã xác nhận |
| Technology | Labels/marks và thứ tự | Preview; không tạo CPT công nghệ |
| Featured projects | Curated object relationship, display title/excerpt/image nếu cần, detail behavior | Portal production; Posts Olympia/Affiliate; ảnh card preview |
| Strategic partner | Tên, logo, copy, motto, link/detail | GM Solutions trong preview và homepage hiện tại |
| Testimonials | Name, project, quote đầy đủ, image | Elementor slides homepage 312 |
| Team | Name, role, image, contacts theo scope | Elementor link-in-bio + ảnh thật local |
| Blog selection | Query hoặc curated post references, heading | Posts hiện tại; cách chọn cần xác nhận |
| Global header/footer/CTA | Logo, CTA, contact, social, motto, ảnh CTA từng mode | Preview và footer 703; navigation dùng WP menus |
| Outsource riêng | Intro/benefits/services/process/strengths/CTA | Elementor data 1133 nếu chọn chuyển ACF |
| Service/product layout | Layout selector và field bổ sung chỉ nơi cần thiết | Existing editor body vẫn reuse; schema chi tiết cần dựa vào content từng sản phẩm |

**Không bắt buộc ACF mới** cho post title, content, excerpt, thumbnail, categories/tags, menu items và permalink: WordPress đã có nguồn chuẩn. Không nhân bản mọi bài viết vào repeater.

Tách dữ liệu màu/media mode khỏi dữ liệu ngôn ngữ: đổi Light/Dark không đổi content record; TranslatePress xử lý ngôn ngữ. Các chuỗi text ACF render server-side theo units ổn định, không giấu toàn bộ text trong JavaScript rồi trông chờ plugin tự dịch đầy đủ.

## 4. Light/Dark trong một theme

- Dùng root data-theme và CSS variables cho tokens; geometry và responsive chung theo [index-html-audit.md](index-html-audit.md).
- Không giữ light.css với `:root` override toàn cục cùng dark tokens mà không scope; otherwise không thể đổi mode đúng.
- Media pairs là field/config riêng: hero, CTA, video/poster. Chữ trắng của video và card trắng của team là ngoại lệ đã duyệt, không auto-invert.
- Nút switch có label/accessibility state, keyboard support; lưu preference theo lựa chọn được duyệt và khởi tạo trước paint. Chưa chốt mode lần đầu hoặc vị trí nút.
- Nếu đổi mode khi video đang tải/playing, không để fallback của mode cũ xuất hiện. Poster HTML và CSS background luôn cùng mode; respect reduced-motion và tab visibility.
- Bổ sung switcher là yêu cầu mới bên cạnh preview; cần review đúng vị trí/spacing, không redesign phần còn lại.

## 5. Chiến lược builder compatibility — chưa chọn thay người dùng

Homepage mới sẽ native + ACF như mục tiêu. Ngoài homepage có hai hướng cần duyệt:

1. Chuyển từng landing quan trọng sang native/ACF trong phạm vi migration, giữ content/object IDs và bổ sung renderer không phụ thuộc Flatsome.
2. Giữ Elementor cho những page cần bảo toàn trước, cung cấp wrapper WordPress chuẩn; vẫn phải xử lý riêng UX shortcodes trong About/footer vì Elementor active không render được shortcode Flatsome khi parent không active.

Không thể hứa “standalone theme, activate là mọi trang giữ nguyên” bằng cách bê child templates: chúng gọi hàm Flatsome và đường dẫn parent. Cũng không tự deactivate Elementor/Pro, WooCommerce hoặc add-ons vì chưa audit xong mọi trang production.

## 6. Multilingual và URL safety

### Trước thực thi

- Xác nhận snapshot/source production chuẩn; reconcile Portal 1809 và chấm công cũ 1530; không chạy import đè database production từ local.
- Chốt route manifest từ sitemap + menus + taxonomy + routes không trong sitemap. Audit hiện đã có danh sách nền, chưa tuyên bố exhaustive crawl mọi URL lịch sử/backlink.
- Ghi baseline status/canonical/hreflang/title, content ownership, template assignment và translations. Xác nhận các lỗi sẵn có (old URL 404, EN thiếu dịch, multi-H1 Portal) không tự coi là lỗi theme mới.
- Giữ ACF registration `dich-vu`, `danh-muc-dich-vu` và flags hiện tại; tránh đăng ký trùng. Không flush rewrites trong mọi request; nếu cần refresh sau activation phải là bước triển khai được duyệt.

### Trong chuyển nguồn content sau duyệt

- Giữ nguyên post_type/post_name/parent/term relationships và ID. Với menu, giữ object links, không thay permalink bằng chuỗi hardcoded theo ngôn ngữ.
- Giữ TranslatePress options/tables, VI root và EN prefix; không bật SEO Pack hoặc dịch slugs mới trong migration mặc định.
- Map text cũ và text preview, ghi các chuỗi mới cần dịch. Giữ unit phrase hợp lý, nhất là hero có span, testimonial modal và nội dung từ JS.
- Tách translated UI strings của theme khỏi ACF editorial text, đưa chúng qua WordPress i18n/TranslatePress đúng nguồn. Không dùng hai ACF sets VI/EN mà chưa có quyết định đổi mô hình dịch.
- Không thêm canonical/hreflang song song với Yoast/TranslatePress; gọi wp_head/wp_footer để plugin tiếp tục cung cấp output.

### Kiểm tra trước release ở phase sau

| Nhóm | Điều kiện đạt |
|---|---|
| Homepage | Light/dark pixel-close tại 1440,1200,1024,768,480,375; font VI/EN đúng; không overflow/cắt dấu |
| Media | Hero/CTA đúng mode; video lần đầu, buffering, loop, tab hidden, reduced-motion; không flash media cũ |
| Controls | Theme/language switch, menu desktop/mobile, search, contact, carousel/dots/swipe/dialog; keyboard/focus/console |
| Page types | Home, Page, Outsource, service detail, product detail khác layout, taxonomy, single post, blog index/category, search, 404 và EN |
| URL/SEO | Status, redirect, trailing slash, pagination, canonical, hreflang, sitemap/robots trước-sau; không 404 mới |
| Data | ACF fields edit được; IDs/relationships/slugs giữ; menu assignments và gallery đúng post |
| Dependencies | Không hàm/asset Flatsome còn bắt buộc; builder pages được xử lý theo scope; plugin versions staging xác nhận |

Audit Phase 1 không chạy các kiểm thử gửi form/login hay thay đổi trạng thái nêu trên.

## 7. Đóng gói và rollback sau khi được phép

Đích cuối là `nextcore-theme.zip` upload được qua WordPress Admin, có đúng root theme directory, stylesheet header, templates/assets/schema và hướng dẫn dependencies. **Upload ZIP chỉ cài theme; không tự mang theo database, ACF values, media uploads, menus hoặc TranslatePress translations.** Việc seed/map các giá trị homepage phải là bước triển khai cụ thể, reviewable và được cho phép; không dùng activation hook ghi đè nội dung hiện hữu không hỏi.

Không đóng gói parent theme, premium plugins, license data, backups, wp-config, preview/checks, Chrome profile hoặc audit tooling. Ảnh/font dùng trong theme giữ source/giấy phép liên quan; không đưa assets không dùng vào ZIP.

Rollback cần giữ theme cũ, DB snapshot và bảng mapping trước khi thay content. Chỉ đổi lại theme chưa chắc phục hồi data đã migrate nên cần kế hoạch dữ liệu riêng. Chưa backup, chưa tạo ZIP hay đổi theme trong Phase 1.

## 8. Điểm dừng

Hoàn tất bốn tài liệu audit là điểm dừng. Chờ người dùng review và trả lời [open-questions.md](open-questions.md), đặc biệt nguồn production và scope builder pages. Không tự bước sang tạo theme.
