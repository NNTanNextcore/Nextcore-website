# Nextcore Theme — kiến trúc Phase 2

Ngày 10/09/2026. Trạng thái: thiết kế kỹ thuật để review; **chưa tạo theme hoặc code**. Quyết định người dùng trong Phase 2 thay thế các lựa chọn chưa chốt ở tài liệu Phase 1. Những điểm còn mở có mã Q trong [phase2-open-questions.md](phase2-open-questions.md).

## 1. Các quyết định đã khóa

- Production là nguồn chuẩn về dữ liệu, object IDs, URL, publish status và nội dung hiện hành. Preview là nguồn chuẩn hình học/thiết kế homepage; data-driven Blog và các hành vi mới tuân theo quyết định Phase 2.
- Một classic standalone theme **Nextcore Theme**, slug/text domain `nextcore-theme`, không parent Flatsome.
- Homepage native WordPress + ACF Pro; các trang được liệt kê trong builder plan có thể giữ Elementor tạm thời. Không đưa Elementor vào nguồn render homepage.
- TranslatePress giữ VI root, EN prefix `/en/`, bản dịch hiện hữu và thêm bản dịch homepage mới. Không duplicate EN pages, không dùng WPML/Polylang.
- Một DOM, root `data-theme="light|dark"`, system preference lần đầu, localStorage lưu override. Theme switch desktop ở header actions, mobile trong menu.
- Blog latest 3 Posts; project cards đi detail thật; các CTA liên hệ đi Contact Page `/lien-he/` theo ngôn ngữ; testimonial carousel/modal; team contacts thật được bật; social chỉ links thật.
- Bảo toàn Messenger, Zalo, floating call, Umami và global contact đã chốt. `/demo/plety/` không nằm trong cam kết bảo toàn; không xóa record hoặc cố tạo 404 trong Phase 2.

## 2. Cấu trúc cuối đề xuất

Đây là cây thiết kế; các thư mục/file dưới đây **chưa được tạo**.

```text
nextcore-theme/
  style.css
  functions.php
  index.php
  header.php
  footer.php
  front-page.php
  page.php
  page-custom.php
  page-slide_left_custom.php
  page-outsource.php
  single.php
  single-dich-vu.php
  home.php
  archive.php
  category.php
  taxonomy-danh-muc-dich-vu.php
  search.php
  searchform.php
  sidebar.php
  comments.php
  404.php
  screenshot.png
  template-parts/
    home/
      hero.php
      about-video.php
      stats.php
      services.php
      technology.php
      projects.php
      partner.php
      testimonials.php
      team.php
      blog.php
      cta.php
    service/
      detail.php
      card.php
      lark-content.php
    content/
      page.php
      builder.php
      post.php
      card.php
      none.php
      gallery.php
      archive-heading.php
    global/
      navigation.php
      theme-switch.php
      language-switch.php
      page-title.php
      breadcrumbs.php
      footer-columns.php
      contact-details.php
      floating-contact.php
  inc/
    setup.php
    enqueue.php
    acf.php
    compatibility.php
    multilingual.php
    integrations.php
    helpers.php
  acf-json/
  assets/
    css/
      tokens.css
      base.css
      layout.css
      services.css
      team.css
      testimonials.css
      footer.css
      content.css
      compatibility.css
    js/
      theme-init.js
      theme-switch.js
      navigation.js
      home.js
      media.js
      testimonials.js
      integrations.js
    images/
    video/
    fonts/
  languages/
    nextcore-theme.pot
  docs/
    installation.md
    data-setup.md
    dependencies.md
    migration-checklist.md
    rollback.md
    asset-licenses.md
```

`index.php` bổ sung vì cần fallback classic theme. Ba page-template filenames cũ được giữ để giải quyết assignment mà không phụ thuộc parent. `sidebar.php`, `searchform.php`, `comments.php` bảo toàn hành vi content pages. Không tạo page-plety-sceneai.php. Không tạo archive-dich-vu.php để chiếm `/dich-vu/`; CPT hiện has_archive=false. `wpml-config.xml` **không cần** trong kiến trúc TranslatePress này; không thêm chỉ vì có custom fields.

`acf-json/` là nguồn định nghĩa schema mới duy nhất được version-control; `inc/acf.php` load definitions và options pages, validation, admin checks. Không khai báo lại cùng groups bằng cả JSON và PHP. Không export đè các CPT/taxonomy definitions ACF production hiện có.

## 3. Trách nhiệm file/module

| File/module | Trách nhiệm và ranh giới |
|---|---|
| style.css | Metadata theme, không Template parent; không nhét toàn bộ legacy CSS |
| functions.php | Bootstrap các inc modules; không migration content hoặc gọi cập nhật options trên request |
| setup.php | Theme supports, text domain, menus, sidebar; không đăng ký CPT trùng ACF |
| enqueue.php | Assets theo template/feature; head initialization chạy trước paint; không dequeue mù Elementor trên toàn site |
| acf.php / acf-json | Schema mới prefix nc_, options settings; không chứa giá trị production/credentials |
| compatibility.php | Builder wrapper, native replacements cho Flatsome helpers, page-template compatibility, scope legacy styles; không include parent theme |
| multilingual.php | Adapter lấy language URL/switcher qua TranslatePress, UI i18n; không viết dictionary/đổi locale routing |
| integrations.php | Một owner/hook rõ cho contact cluster và analytics; ownership pending Q4, không nhúng lặp trong footer và plugin |
| helpers.php | Resolve approved objects/terms/media, escape và format contact/date; không hardcode IDs production vào renderer |
| header/footer | Một site shell, wp_head/wp_body_open/wp_footer, native menu/footer; không call flatsome_* |
| front-page.php | Ghép đúng 11 section content từ ACF/core; không the_content() Elementor homepage để tránh render hai homepages |
| page*.php | Các page wrappers riêng, đi qua the_content() cho builder pages; banner/sidebar theo template |
| single.php | Posts, gallery/comments/meta; dữ liệu dự án là Posts vẫn đi route này |
| single-dich-vu.php | CPT detail; chọn builder/native/Lark component theo manifest đã xác minh, không đổi post type |
| home/archive/category/taxonomy | Query/context chuẩn, pagination; service taxonomy giữ cả post và dich-vu |
| search/searchform | GET search WordPress, URL có ngôn ngữ; không còn search chỉ section; mức tìm full EN strings là giới hạn plugin cần QA |
| content/gallery | Gallery theo attachment IDs của từng post, thay transient dùng chung của child |
| theme-init/theme-switch/media | Preference sớm; control state; media generation/race handling; chi tiết trong light-dark architecture |
| home.js | Reveal/marquee, chỉ homepage, null guards; không chứa business text |
| navigation.js | Menu, focus, mobile controls, active anchor theo section order |
| testimonials.js | Scroll/dots và mở dialog chứa text server-rendered đã dịch |
| compatibility.css | CSS có scope .legacy-content cho content cũ, không tác động home/global cards |

Không tạo PHP runtime migration hook, không tự seed dữ liệu trong activation. ZIP theme không mang menu objects, ACF values, TranslatePress DB hay plugin premium. Data setup là bước riêng sau khi được phép.

## 4. Template hierarchy và page routing

| Request | Template / renderer | Data |
|---|---|---|
| `/`, `/en/` | front-page.php | ACF trên front page hiện hữu + core posts query |
| Normal Page | Assigned template nếu có → page.php → index.php | Core content, giữ Elementor theo manifest |
| `/outsource/` | page-outsource.php | Elementor Page 1133, wrapper native |
| About/Contact | page-custom.php | About cần xử lý UX trước; Contact giữ builder + CF7 |
| `/dich-vu/` | Page 316, page-slide_left_custom.php | Listing Page Elementor posts widget |
| `/dich-vu/{slug}/` | single-dich-vu.php | Core CPT object; product detail không ép chung body layout |
| Lark | single-dich-vu + lark-content | Existing HTML + scoped compatibility CSS |
| `/tin-tuc/` | home.php | Posts page 318; không dùng page assignment để override blog index |
| Blog detail / project-story post | single.php | post_content, image, gallery, terms |
| `/category/{slug}/` | category.php | Main query category |
| `/danh-muc-dich-vu/{slug}/` | taxonomy-danh-muc-dich-vu.php | Main query, mixed objects |
| Tag/date/author archive | archive.php → index.php | Core archive query; không trả homepage |
| Search | search.php | Query `s`, pagination, matching content URL |
| Không tìm thấy | 404.php | HTTP 404, không soft-404 |
| `/en/...` | Cùng file/ID/context | TranslatePress thay output và URL, không page duplicate |

Phân biệt front-page.php và home.php theo [WordPress Template Hierarchy](https://developer.wordpress.org/themes/classic-themes/basics/template-hierarchy/). Giữ `/dich-vu/` là Page là constraint riêng đã kiểm chứng từ ACF/options, không thêm archive cùng slug.

## 5. Section order và homepage menu

Thứ tự khóa: **Header → Hero → About/video → Stats → Services → Technology → Projects → Partner → Testimonials → Team → Blog → CTA → Footer**.

| Menu đầy đủ đề xuất | Anchor | Thứ tự section |
|---|---|---|
| Giới thiệu | #about | 3 |
| Dịch vụ | #services | 5 |
| Công nghệ | #technology | 6 |
| Sản phẩm / Dự án | #projects | 7 |
| Đối tác | #partner | 8 |
| Đánh giá | #testimonials | 9 |
| Đội ngũ | #team | 10 |
| Tin tức | #blog | 11 |
| Liên hệ | #contact | 12 |

Logo về homepage/hero; không cần menu riêng cho Stats/Hero. Menu “Liên hệ” cuộn tới CTA section; **nút CTA** đi Contact Page thật — hai vai trò khác nhau được ghi rõ. Không giữ thứ tự preview cũ đưa Projects lên trước Technology hoặc Blog lên trước Team.

Menu 9 mục cộng switcher có thể quá chật, nhất là EN và 1024–1200px. Đề xuất desktop rút còn **Giới thiệu → Dịch vụ → Công nghệ → Sản phẩm → Đội ngũ → Tin tức → Liên hệ**, mobile đầy đủ 9 mục. Bỏ bớt anchor Đối tác/Đánh giá khỏi desktop không đổi thứ tự logic, hai section vẫn có trên trang. **Đề xuất này chờ Q1; chưa chọn hoặc sửa preview.** Không giảm font nhỏ bất hợp lý để ép 9 mục.

Locations dự kiến: `home_primary`, `home_mobile` cho homepage; giữ `primary`, `primary_mobile` map menu site hiện hữu cho inner pages; `footer_about`, `footer_support` cho hai cột footer preview. Navigation dùng wp_nav_menu(), order phản ánh menu objects được chuẩn bị có review; admin validation cảnh báo nếu menu homepage sai order, không lén sắp lại menu trên mọi request. TranslatePress language-switcher menu item được giữ logic plugin; vị trí chính xác cùng compact menu chờ Q1. Không duplicate switcher cùng viewport.

## 6. Release gates

Production snapshot và runtime xác nhận; dependency wrappers/UX conversion hoàn tất; ACF schema và values được review; translation homepage bổ sung; URL/canonical/hreflang trước-sau; 2 modes × VI/EN × các breakpoints và page types; integrations không duplicate; không missing parent functions/assets. Chưa có release gate nào được xem là pass chỉ vì tài liệu hoàn tất.

Tài liệu liên quan: [data mapping](final-data-mapping.md), [schema](acf-schema-plan.md), [mode](light-dark-architecture.md), [đa ngữ](multilingual-strategy.md), [builders](builder-compatibility-plan.md), [integrations](integrations-plan.md).
