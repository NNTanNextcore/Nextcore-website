# Audit WordPress hiện tại — Phase 1

Ngày audit: 10/09/2026. Phạm vi: source local, database local chỉ đọc và HTML public. Chưa triển khai theme, chưa sửa database, chưa activate/deactivate plugin/theme.

## 1. Cách xác minh và giới hạn

- Đọc source trong `wp-content`, `wp-includes/version.php`, `.htaccess`; đọc database bằng SELECT/SHOW trong transaction `READ ONLY`, kết thúc bằng rollback.
- Không bootstrap WordPress hoặc gọi WP-CLI: việc load plugin có thể ghi options/transients; source ACF/TranslatePress Business local thực tế có lệnh cập nhật option ngay khi load.
- Không đọc dữ liệu submissions, tài khoản, đơn hàng, API keys hoặc xuất credentials vào tài liệu.
- **Database local không đồng bộ hoàn toàn với production.** Post ID 1809 local là draft `post`, chưa có slug; public là published `dich-vu` tại URL Nextcore Portal. Không được tự chuyển loại bài hoặc publish bản nháp để lấp khoảng trống này.
- Public được đọc qua web và HTTP GET trực tiếp, không đăng nhập. Status/canonical/body class là bằng chứng render; không phải quyền truy cập database production hay xác minh PHP production.
- Các nhận định bố cục public dựa trên HTML và template; chưa thực hiện QA trực quan toàn site ở Phase 1.

## 2. WordPress, PHP, theme

| Hạng mục | Kết quả và bằng chứng |
|---|---|
| WordPress local | **7.1**, DB revision **61833**, `wp-includes/version.php:19` |
| WordPress public | Generator HTML ghi **7.1**; không dùng generator để chứng thực tính nguyên bản của core |
| PHP requirement | Core yêu cầu **7.4**; ACF Pro, Elementor/Pro, WooCommerce, CF7 đang cài cũng yêu cầu tối thiểu 7.4. Parent Flatsome khai báo 5.6, không hạ được yêu cầu của toàn stack |
| PHP local | CLI XAMPP **7.4.33**. PHP Apache và PHP production chưa xác minh |
| Database requirement của core | MySQL tối thiểu 5.5.5; extensions PHP `json`, `hash` |
| Theme active local | `stylesheet=flatsome-child`, `template=flatsome` trong options |
| Child | **Flatsome Child 3.0**, `Template: flatsome`, `wp-content/themes/flatsome-child/style.css` |
| Parent | **Flatsome 3.18.6**, `wp-content/themes/flatsome/style.css` |
| Public theme | Body class `wp-theme-flatsome wp-child-theme-flatsome-child`, asset paths của cả parent và child |
| Theme khác đã cài | envo-royal, twentytwentyfour, twentytwentyfive; không active |
| Homepage / posts page | Static front page **312**; posts page **318**; `show_on_front=page` |
| Local site URLs | `home` và `siteurl` là `http://localhost`; public là `https://nextcore.vn` |

Nguồn public: [homepage](https://nextcore.vn/). `.htaccess` ghi được Duplicator cập nhật 09/09/2026, dùng front controller WordPress tiêu chuẩn, không thấy redirect tùy chỉnh trong file này.

## 3. Plugins và tác động frontend

Trạng thái dưới đây lấy từ `active_plugins` local, version từ plugin header/readme. Có 18 plugin active duy nhất; Duplicator được ghi trùng trong option, không suy ra chạy hai lần.

| Plugin | Version | Trạng thái local / tác động |
|---|---|---|
| Advanced Custom Fields PRO | 6.3.10 | Active; đăng ký CPT/taxonomy dịch vụ và 3 field groups |
| Elementor | 3.25.3 | Active; nguồn layout/content nhiều page và 2 dịch vụ |
| Elementor Pro | 3.21.2 | Active; testimonial-carousel, posts, gallery, animated headline, media carousel |
| Elementor Addon Elements | 1.13.9 | Active; `eae-timeline` trên Outsource |
| Card Elements for Elementor | 1.2.4 | Active; `post-card-elementor-widget` trên homepage |
| Slider Revolution | 6.7.20 | Active; homepage gọi `[rev_slider alias="slider-1"]`; DB có slider-1 và slider-2 |
| TranslatePress Multilingual | 2.8.7 | Active; dịch nội dung, URLs, switcher |
| TranslatePress Business | 1.4.6 | Active; cung cấp các add-on, nhưng không phải mọi add-on đều bật |
| Yoast SEO | 23.7 | Active; metadata, canonical, sitemap, cấu hình indexation |
| Contact Form 7 | 5.9.8 | Active; trang Liên hệ chứa shortcode form, 5 form published trong DB |
| WooCommerce | 9.3.6 | Active; assets, tài khoản/login, routes và taxonomy; **không có post `product` trong inventory local** |
| Nextend Social Login | 3.1.15 | Active; luồng đăng nhập mạng xã hội; không kiểm thử đăng nhập |
| Click to Chat / WP Support All-in-One | 2.3.2 | Active; có thể thêm widget liên hệ, ngoài widget custom trong footer |
| WP Categories Widget | 2.5 | Active; sidebar danh mục dịch vụ |
| Classic Editor | 1.6.5 | Active; giao diện biên tập, không phải nguồn template homepage |
| Advanced Editor Tools | 5.9.2 | Active; công cụ editor |
| Admin Menu Editor | 1.12.4 | Active; menu quản trị |
| Duplicator Pro | 4.5.19.3 | Active; backup/migration, không dùng trong audit |

`add-to-any` và `blogger-importer` có thư mục nhưng không nằm trong active_plugins local. **Public có tải asset `add-to-any`**, do đó không được đồng nhất danh sách active local với production; asset public chưa đủ để kết luận cách plugin được kích hoạt. Không thấy thư mục `mu-plugins` hoặc drop-in ở root `wp-content` trong lần liệt kê này.

Source ACF Pro và TranslatePress Business local có đoạn thay đổi trạng thái license/chặn một số request kiểm tra. Đây là bằng chứng source đã được tùy biến, **không phải bằng chứng license hợp lệ**. Không sao chép các đoạn này hay plugin vào theme ZIP; cần xác nhận bộ plugin chuẩn dùng cho staging/production. Không ghi khóa license vào báo cáo.

## 4. ACF: định nghĩa và dữ liệu thực tế

Không thấy khai báo field group/CPT bằng PHP trong child theme hoặc thư mục ACF JSON tại child theme. Định nghĩa hiện nằm trong các record ACF của database.

| Group ID / key | Field / key | Kiểu, location | Đang dùng |
|---|---|---|---|
| 871 / `group_67287130343e5` — Ảnh chi tiết | `anh_chi_tiet` / `field_6728713034cff` | Gallery; post_type=post | Bài 914: 13 ảnh; 929: 10 ảnh; 1510: 6 ảnh. 1548 và 1732 rỗng |
| 979 / `group_6729cee663f31` — Custom page | `custom_css` / `field_6729cee6141f8` | Textarea; post_type=dich-vu | Lark 994 có 133.095 ký tự CSS; các dịch vụ còn lại có meta rỗng |
| 1527 / `group_6733226bcacc5` — Redirect service | `redirect_service` / `field_67332248bb94c` | Text; post_type=dich-vu | Meta đã thấy đều rỗng; không tìm thấy consumer PHP trong child theme; chưa có bằng chứng redirect hoạt động |

`functions.php:78` đọc `custom_css` và chèn style trên single. `single.php` và `single-dich-vu.php` đọc `anh_chi_tiet`, dù location gallery chỉ cấu hình cho `post`. Không được hiểu có field trong template nghĩa là mọi dịch vụ đã có gallery.

Gallery hiện phụ thuộc `get_image_list()`, một transient chung `post_image_data`, AJAX `get_image_detai_post`, LightGallery và slider-custom.js. Transient không gắn post ID nên có nguy cơ trả gallery của bài khác; renderer cũ còn ghi transient trong lượt xem. Theme mới cần cách truyền gallery theo bài, không bê nguyên cơ chế này.

Không có field groups dành riêng cho hero, stats, services homepage, technology, projects selection, team, testimonials, CTA hoặc global contact. Muốn các phần này editable bằng ACF thì **cần bổ sung schema**, không thể chỉ đổi template rồi gọi field chưa tồn tại.

## 5. CPT và taxonomy

### Dịch vụ

- ACF post-type record **552**, key `post_type_6722e3794b45b`: CPT **`dich-vu`**.
- `public=true`, `publicly_queryable=true`, `hierarchical=false`, `show_in_rest=true`.
- Supports: title, editor, excerpt, thumbnail, custom-fields.
- **`has_archive=false`**. Rewrite theo key `dich-vu`, with_front=true, pages=true, feeds=false. Stored rewrite rules xác nhận `/dich-vu/{slug}/`.
- `/dich-vu/` là **Page ID 316**, không phải archive CPT. Không được bật archive cùng slug làm chiếm URL này.
- `single-dich-vu.php` là template detail riêng; `page-outsource.php` phục vụ Page Outsource, không phải CPT.

### Danh mục dịch vụ

- ACF taxonomy record **553**, key `taxonomy_6722e39e5b246`: **`danh-muc-dich-vu`**.
- Gắn vào **cả `dich-vu` và `post`**; public, hierarchical, REST enabled.
- Rewrite theo taxonomy key; with_front=true; rewrite_hierarchical=false.
- Terms: 73 `outsource`, 74 `tu-van-doanh-nghiep`, 75 `khach-hang-ca-nhan`, 81 `san-pham`.
- Template `taxonomy-danh-muc-dich-vu.php`: banner/title/breadcrumb, grid 2 cột, excerpt, thumbnail, pagination, sidebar trái. Phải giữ main query gồm đúng các post types; không tự giới hạn chỉ dịch vụ.

### Các loại khác

- Core content: page, post, attachment, revision, nav_menu_item, custom_css; các record nội bộ wp_navigation/wp_global_styles.
- Flatsome đăng ký `blocks`, `featured_item`; UX Builder có `ux_template`. Local có 4 UX Blocks, **không có featured_item hoặc ux_template content** trong inventory. Portfolio được bật bằng theme mod `fl_portfolio=1`.
- Elementor: `elementor_library` (8 published, 1 draft); CF7: `wpcf7_contact_form` (5 published); TranslatePress: `language_switcher` (4 record).
- WooCommerce đăng ký loại sản phẩm và các routes thương mại qua plugin; không có sản phẩm local. Không coi các bảng WooCommerce là bằng chứng site đang bán sản phẩm.
- Taxonomy có dữ liệu: category, post_tag, post_format, danh-muc-dich-vu, nav_menu, elementor_library_type, featured_item_category, product_cat, product_tag, product_type, product_visibility, wp_theme. Parent còn đăng ký featured_item_tag và block_categories; taxonomy được đăng ký có thể không có term.
- Đây là inventory source + DB, không phải dump registry runtime của mọi post type nội bộ plugin.

## 6. Page builders và page types

| Local ID / URL | Dữ liệu / template hiện tại | Layout cần được hỗ trợ |
|---|---|---|
| 312 `/` | Elementor `_elementor_data`; template default, Flatsome `pages_template=blank`; hero Revolution | Homepage riêng, source of truth mới là preview |
| 314 `/ve-chung-toi/` | Elementor + UX shortcodes; `page-custom.php` | Normal/about page với banner riêng |
| 316 `/dich-vu/` | Elementor `posts` widget; `page-slide_left_custom.php` | Landing/listing Page dịch vụ có sidebar |
| 318 `/tin-tuc/` | Được chỉ định posts page; Elementor metadata tồn tại nhưng không có widgets | Blog index theo WP hierarchy; không ép render page builder chỉ vì có metadata |
| 320 `/lien-he/` | Elementor, HTML và CF7; `page-custom.php` | Contact page |
| 1133 `/outsource/` | Elementor, eae-timeline; `page-outsource.php` | Landing: lợi ích → dịch vụ → quy trình → điểm mạnh → CTA |
| 339 `/demo/` | Page default, content rỗng | Normal Page, cần xác nhận có phục vụ tiếp |
| 1813 `/demo/plety/` | `page-plety-sceneai.php`, content DB rỗng | Standalone landing hardcoded, không thể dựa vào the_content() |
| 994 `/dich-vu/lark/` | HTML trong post_content, CSS riêng rất lớn; không thấy builder data dù có template metadata Elementor | Product/service landing riêng |
| 1530 dịch vụ chấm công cũ | HTML/content thường | Detail local; public URL đã 404 |
| 1550, 1553 | Nội dung thường + template single-dich-vu | Service detail |
| 1616 `/dich-vu/ung-dung-dat-san/` | Elementor builder, carousel/gallery/animated-headline | Product detail riêng |
| 1761 `/dich-vu/wordpress-aff-atv/` | Elementor builder, toggle/media-carousel | Product detail riêng |
| 914, 929, 1510, 1548, 1732 | Posts thường, một số có ACF gallery; `single.php` | Blog detail, có thể đồng thời là nội dung giới thiệu dự án |
| Taxonomy danh-muc-dich-vu | `taxonomy-danh-muc-dich-vu.php` | Service category/archive; term san-pham có post types trộn |
| Category blog | category `kien-thuc`, `cong-ty`; child index.php / parent hierarchy | Blog category/archive có sidebar, phân trang |
| Search | Dùng template fallback của theme/parent và archive renderer | Search riêng; không dùng search section-only của preview |
| 404 | Child `404.php`, có banner “Lỗi 404” | Trang 404 riêng, status 404 |
| `/en/...` | Cùng Page/Post/term, TranslatePress dịch output | Các biến thể ngôn ngữ của từng layout trên |

Page drafts Elementor 414/416 không phải trang public. Elementor Footer 638 là draft; không có bằng chứng đang override footer toàn site. Các loop item 584/594/774 và 1589/1591/1593 là template data, không phải page URL độc lập.

UX Builder dùng thật trong **About 314**, **footer block 703**, **block 961**. Homepage chủ yếu Elementor/Revolution, không được gọi chung là “homepage UX Builder”. Block 613 là Elementor footer cũ, block 419 là HTML testimonial cũ; existence không chứng minh hai block này đang được render.

## 7. Products, projects, testimonials, team

### Products/projects

Không có CPT `project`, `testimonial`, `team_member` hoặc product content riêng trong DB local. Term `san-pham` hiện gắn:

| ID | Post type | Nội dung |
|---|---|---|
| 1510 | post | Trường Doanh nhân Top Olympia |
| 1548 | post | Wordpress Plugin - Affiliate |
| 1530 | dich-vu | Hệ thống chấm công bằng gương mặt |
| 1616 | dich-vu | Ứng dụng đặt sân |
| 1761 | dich-vu | Wordpress aff atv |

Nextcore Portal public là `single-dich-vu postid-1809`, nhưng local cùng ID vẫn draft post. Đây là dữ liệu lệch, không phải lý do tạo CPT product mới. Preview có 3 card curated: Portal, Olympia, Affiliate; cần map relationship/link tới đúng object production.

### Testimonials

Nguồn chính đã xác minh: Elementor `testimonial-carousel.settings.slides` của homepage **312**, gồm 4 khách hàng đã duyệt. Ảnh attachment lần lượt 1578, 1580, 1581, 1579. Có cả trường content, name, title dự án, image. About 314 cũng có testimonial-carousel; chưa quyết định hợp nhất thành một nguồn toàn site.

Preview đã sao chép nội dung vào HTML. Có thể reuse tên, nội dung, dự án, media; muốn quản trị bằng ACF cần schema mới, không cần CPT testimonial chỉ để giữ 4 card homepage.

### Team

Nguồn chính: 3 widget Elementor **link-in-bio** trên homepage 312, gồm tên/chức vụ/ảnh/email/điện thoại. Ảnh: Hiền 1799, Anh 527, Tú 526. Không có CPT team. Preview dùng ảnh thật local và các nút liên hệ đang aria-disabled; cần xác nhận hành vi liên hệ cho theme cuối, không tự bật từ dữ liệu cũ.

## 8. Header, footer và menus

- Child `header.php` gọi các hàm Flatsome và `template-parts/header/header-wrapper` của parent. Dữ liệu từ `theme_mods_flatsome-child`: logo attachment **270**, Header Builder, typography Nunito, menu locations.
- Parent đăng ký 7 locations tại `inc/functions/function-setup.php:88`: `primary`, `primary_mobile`, `secondary`, `footer`, `top_bar_nav`, `my_account`, `vertical`.
- `primary` và `primary_mobile` cùng menu **69 — Menu main**, 12 items. Footer/top_bar_nav lưu 0; các location còn lại chưa thấy assignment trong option đã đọc.
- Menu 69: Trang chủ → Về chúng tôi → Dịch vụ (Outsource Page, tư vấn/cá nhân/sản phẩm taxonomy) → Blog (2 categories) → Liên hệ → language switcher.
- Menu 71 “menu fooer” có 7 items; menu duplicate 79/80 không active ở primary. Không có bằng chứng đây là bộ menu EN riêng.
- Switcher menu item 1522 trỏ object `language_switcher` 1466. `topbar_right` có `[language-switcher]`, nhưng `topbar_show=false`; không được kết luận shortcode topbar đang hiện.
- Child `footer.php` gọi `do_action('flatsome_footer')`; theme mod `footer_block=footer` trỏ UX Block **703**. Block này chứa UX shortcodes, 5 link tự khai báo bằng `ux_menu_link`, contact, social và CTA. Không phải footer menu location 71 đang tạo toàn bộ footer này.
- Copyright từ theme mod `footer_left_text`, có `[ux_current_year]`. Widget footer 1/2 được tắt; sidebar-main đang dùng WP Categories Widget, category, recent posts, tags.
- Footer child còn hardcode Messenger, phone, Zalo SDK và Umami script. Không gửi thông tin hay kiểm thử tương tác với các dịch vụ này trong audit.
- Local footer 703 đã là **63 Phan Đăng Lưu**, nhưng contact page 320 vẫn chứa **384 2/9 Street**. Preview đã chốt 63 Phan Đăng Lưu; việc đồng bộ trang liên hệ cũ cần scope riêng.

## 9. Multilingual thực tế

**TranslatePress**, không phải WPML/Polylang. Bằng chứng: active_plugins, trp_settings, trp tables và body class public.

- Default `vi`; published languages `vi`, `en_US`; URL slugs `vi`, `en`; `add-subdirectory-to-default-language=no`.
- Vietnamese dùng root `/...`; English dùng `/en/...`. `force-language-to-custom-links=yes`.
- Local dictionary `trp_dictionary_vi_en_us`: **1.471 rows**, 408 có translated value (status 2), 1.063 chưa có (status 0). Đây là tỷ lệ record trong dictionary, **không phải tỷ lệ hoàn tất dịch toàn website**.
- Có bảng dictionary/gettext tiếng Nhật cũ, nhưng `ja` không thuộc published languages. Không khôi phục `/ja/` chỉ vì còn bảng.
- Bản dịch chính là chuỗi/output HTML trong dictionary và gettext tables; public VI/EN homepage cùng Page ID 312, Outsource cùng 1133, Portal cùng 1809. Không có cơ sở tạo Page EN duplicate như mô hình WPML.
- Menu labels dịch qua TranslatePress trên menu hiện hữu; navigation-based-on-language add-on **off**, không thấy menu EN riêng được assign.
- Floater switcher **off**; switcher trong menu có thật. Shortcode/menu options đều flags-full-names.
- Business add-ons local: Browse as role, DeepL, Extra Languages **on**; SEO Pack, automatic language detection, navigation by language, translator accounts **off**. Bật add-on DeepL không chứng minh dịch máy đang chạy/đã được cấu hình đầy đủ.
- SEO Pack off: không tự đặt translated slugs mới. Các URL EN đã xem giữ slug Việt. EN homepage vẫn có title, blog snippets và testimonials chưa dịch hết; EN Portal còn nhiều heading Việt.
- Custom fields: hiện các field CSS/gallery không phải bộ nội dung song ngữ riêng. TranslatePress có renderer dịch HTML (`includes/class-translation-render.php`); field text mới render ra HTML có thể đưa vào luồng dịch, nhưng **chưa có bằng chứng toàn bộ chuỗi ACF mới sẽ reuse đúng bản dịch**. Chia lại text nodes, đổi câu, đổi markup hoặc JS tạo text có thể đổi đơn vị dịch.
- Taxonomy/CPT: bản EN của term tư vấn và Portal tồn tại; slug giữ nguyên, labels/content có mức dịch khác nhau. Không đánh đồng có URL EN với nội dung dịch hoàn chỉnh.

## 10. URL, permalink và public đối chiếu

`permalink_structure=/%postname%/`; category_base/tag_base rỗng (core dùng category/tag mặc định). Không thấy meta `_wp_old_slug`, custom Yoast canonical hoặc robots-noindex override không rỗng trong truy vấn local.

| URL public được kiểm tra | HTTP | Kết quả |
|---|---|---|
| [Homepage](https://nextcore.vn/) | 200 | Elementor/Revolution; Page 312; canonical tự trỏ |
| [Outsource](https://nextcore.vn/outsource/) | 200 | Page 1133, page-outsource; lợi ích/dịch vụ/timeline/điểm mạnh/CTA |
| [Nextcore Portal](https://nextcore.vn/dich-vu/phan-mem-cham-cong-nextcore-portal/) | 200 | CPT dich-vu, ID 1809; bài dài, nhiều ảnh workflow, CTA; có nhiều H1 gồm title lặp |
| [Tư vấn doanh nghiệp](https://nextcore.vn/danh-muc-dich-vu/tu-van-doanh-nghiep/) | 200 | Taxonomy term 74; listing Portal, Extension, Plugin, Lark |
| Bốn URL trên với prefix `/en/` | 200 | Canonical từng URL EN; cùng object IDs/term; mức hoàn thiện bản dịch khác nhau |
| [Chấm công cũ](https://nextcore.vn/dich-vu/he-thong-cham-cong-bang-guong-mat/) | **404** | Không thấy redirect sang Portal trong request thực tế |

Web reader từng lỗi khi mở trực tiếp Portal và EN taxonomy; sau đó link từ category và HTTP GET trực tiếp đã xác minh thành công. Không còn URL bắt buộc nào chưa mở được. Không kết luận lỗi web reader là lỗi website.

### Danh sách bảo toàn URL

Production service sitemap có 6 URLs dưới prefix `/dich-vu/`:

- `lark/`
- `viet-plugin-wordpress-chuyen-nghiep-tuy-bien-theo-yeu-cau/`
- `viet-extension-tang-cuong-hieu-suat-va-tinh-nang-cho-trinh-duyet-cua-ban/`
- `ung-dung-dat-san/`
- `wordpress-aff-atv/`
- `phan-mem-cham-cong-nextcore-portal/`

Nguồn: [service sitemap](https://nextcore.vn/dich-vu-sitemap.xml). Local thiếu published Portal và còn chấm công cũ; cần reconcile trước migration.

Pages/routes: `/`, `/ve-chung-toi/`, `/dich-vu/`, `/outsource/`, `/lien-he/`, `/tin-tuc/`, `/demo/`. `/demo/plety/` là Page phân cấp local, chưa xác nhận cần giữ public. `/trang-chu/` là slug Page front local, không tự tạo landing mới tại slug đó.

Posts cần giữ nguyên slug:

- `/cong-ty-cppm-nextcore-to-chuc-picnic-tai-enjoy-camping-hoa-bac-da-nang/`
- `/cong-ty-cppm-nextcore-don-sinh-nhat-2-tuoi-tai-bach-ma-village-o-tinh-thua-thien-hue/`
- `/truong-doanh-nhan-top-olympia/`
- `/wordpress-plugin-affiliate/`
- `/huong-dan-cach-debug-springboot-voi-visual-code-mot-cach-de-dang/`

Nguồn: [page sitemap](https://nextcore.vn/page-sitemap.xml), [post sitemap](https://nextcore.vn/post-sitemap.xml).

Taxonomies: `/danh-muc-dich-vu/{outsource,tu-van-doanh-nghiep,khach-hang-ca-nhan,san-pham}/`; `/category/{kien-thuc,cong-ty}/`; `/tag/wordpress-plugin/` cùng những tag cũ nếu còn được sử dụng. Giữ pagination/feed theo rewrite hiện tại. Giữ các bản `/en/` theo TranslatePress; chỉ các route EN nêu trong bảng đã được GET riêng, không tuyên bố đã crawl toàn bộ EN.

## 11. SEO và rủi ro migration

- Yoast active, settings cho post/page/dich-vu và taxonomy dịch vụ không noindex; breadcrumbs enabled trong Yoast, nhưng child shortcode breadcrumb hiện gọi Flatsome.
- Các trang public kiểm tra có canonical tự trỏ và hreflang `vi`, `en-US`, `en`; không thấy x-default trên các trang này. Không thêm canonical thứ hai trong theme mới.
- Chuyển theme không tự đổi post_name, nhưng mất registration CPT, thay rewrite/has_archive, sai taxonomy object_type hoặc menu assignments có thể làm 404/thiếu listing.
- Không chuyển Olympia/Affiliate từ post sang product/CPT mới chỉ để hiển thị product card: sẽ đổi permalink và quan hệ hiện tại.
- Khi bỏ Flatsome, mất UX Blocks/shortcodes, Header Builder, theme mods, sidebar helpers và asset đường dẫn parent. About và footer là dependency thật, không chỉ homepage.
- Elementor cần `the_content()` và hooks chuẩn; CSS toàn cục của theme mới không được vô tình phá builder pages. JS preview giả định luôn có menu, marquee và dialog: phải scope/enqueue theo page type trước khi reuse trên trang nội dung.
- Lark có lượng CSS riêng lớn; Plety là template tự render HTML/Tailwind/video ngoài, không có nội dung DB để tự phục hồi bằng fallback.
- Thay markup/text có nguy cơ làm mất match dịch cũ. Dữ liệu EN hiện chưa hoàn chỉnh; cần phân biệt regression migration với phần chưa dịch từ trước.
- Plugin versions cũ và một số source tùy biến; minimum PHP 7.4 không chứng minh tương thích production runtime mới. Không nâng plugin/PHP trong Phase 1.
- Asset public add-to-any khác active list local; Portal và URL cũ khác DB local. **Chặn thực thi migration cho tới khi xác nhận nguồn dữ liệu chuẩn**.

Các quyết định còn thiếu được liệt kê trong [open-questions.md](open-questions.md). Mapping đề xuất nằm trong [theme-migration-plan.md](theme-migration-plan.md).
