# ACF schema plan — Nextcore Theme

Ngày 10/09/2026. **Đặc tả field, chưa registration/JSON/PHP và chưa ghi values.** Dùng ACF Pro hiện hữu; không đổi schema CPT/taxonomy/gallery cũ. Homepage groups gắn `page_type=front_page`, global groups gắn options page đề xuất `nextcore-settings`, quyền manage_options. Không gắn bằng ID 312 hardcoded.

## Quy ước schema

- G01–G15 là IDs tài liệu. Group key lần lượt `group_nc_hero`, `group_nc_about_video`, `group_nc_stats`, `group_nc_services`, `group_nc_technology`, `group_nc_projects`, `group_nc_partner`, `group_nc_testimonials`, `group_nc_team`, `group_nc_cta`, `group_nc_header`, `group_nc_footer`, `group_nc_media`, `group_nc_blog`, `group_nc_integrations`.
- Cột **Field path** cho tên field chính xác: phần sau `[].` là subfield name thực trong repeater. Field key là `field_` + path thay `[].` bằng `_`, ví dụ `nc_team_members[].email` → `field_nc_team_members_email`. Không đổi keys khi đổi labels.
- **R**: bắt buộc lúc validate dữ liệu đã cấu hình; `C` là required khi điều kiện ghi ở row thỏa. Required không có nghĩa tự publish/seed hoặc tạo record khi view.
- **D/seed**: default literal được duyệt hoặc nguồn seed; `∅` = không default. `seed:` là migration proposal, **không ACF auto-default/import ngầm**. Required object/media chưa map thì báo thiếu trước release.
- **T**: `HTML` = text editorial dịch qua TranslatePress output; `No` = giá trị không dịch; `ALT` = alt/caption qua media metadata/output, cần QA khả năng dịch ở plugin hiện tại. UI controls cố định ở i18n, không phải ACF.
- **Shared**: `Yes` = cùng một giá trị/ID cho VI/EN; không copy record thật vì không có EN Page duplicate. Text `HTML` cũng có Shared=Yes: lưu nguồn một lần, bản dịch ở TranslatePress, không overwrite meta nguồn.
- **Mode**: `Both`, `Light`, `Dark`. Không tạo data song ngữ theo mode.
- Image/file/post_object/taxonomy trả **ID**, relationship multi trả array IDs. Link destinations nội bộ chọn object/term, không lưu cả permalink duplicate. Scalar URLs chỉ dùng link ngoài có thật.
- Existing production là nguồn data chuẩn, actual preview là nguồn hình ảnh/copy homepage đã duyệt. `Preview` dưới đây là file hiện tại, không phải mockup cũ.

## G01 — Homepage — Hero

Location front_page. Tên công ty/slogan là editorial homepage, khác core Page title “Trang chủ”. Ngắt dòng/cấu trúc span giữ ổn định.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_hero_eyebrow | text | Yes | Software development company | HTML | Yes | Both | Preview |
| nc_hero_line_one | text | Yes | Công ty cổ phần Phần | HTML | Yes | Both | Preview line 1 |
| nc_hero_line_two_prefix | text | Yes | mềm | HTML | Yes | Both | Preview line 2 prefix |
| nc_hero_brand | text | Yes | Nextcore | No | Yes | Both | Preview red brand |
| nc_hero_slogan | text | Yes | BUILD TRUST, CREATE VALUE | HTML | Yes | Both | Preview |
| nc_hero_description | textarea, plain | Yes | seed: preview description | HTML | Yes | Both | Preview |
| nc_hero_contact_label | text | Yes | Liên hệ ngay | HTML | Yes | Both | Preview; destination G12 |
| nc_hero_secondary_label | text | Yes | Xem năng lực | HTML | Yes | Both | Preview; fixed #services |

Không thêm headline font/color fields; Be Vietnam Pro 700 và màu đỏ thuộc design tokens. Không thêm field core post_title.

## G02 — Homepage — About Video

Location front_page. Media chuyển sang G13 để chỉ có một nơi chỉnh mỗi cặp.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_about_eyebrow | text | Yes | Về Nextcore | HTML | Yes | Both | Preview |
| nc_about_heading_line_one | text | Yes | Công ty Cổ phần | HTML | Yes | Both | Preview |
| nc_about_heading_line_two | text | Yes | Phần mềm Nextcore | HTML | Yes | Both | Preview |
| nc_about_founded_date | date_picker, return Y-m-d | Yes | 2022-06-15 | No | Yes | Both | Preview/live company data |
| nc_about_body | wysiwyg, basic, media off | Yes | seed: 3 approved paragraphs | HTML | Yes | Both | Preview; retain strong emphasis |
| nc_about_link_label | text | Yes | Tìm hiểu thêm | HTML | Yes | Both | Preview; fixed #team |

“Thành lập vào” và date display là i18n/localized formatting; lưu date machine-readable một lần. WYSIWYG chỉ cho p/strong/em/link được phép, không shortcode/script/layout builder. Không ép dịch ngày bằng sửa database date.

## G03 — Homepage — Stats

Location front_page. Repeater 3 rows, min=max=3; thứ tự projects → clients → employees.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_stats | repeater | Yes | seed: 3 rows | No | Yes | Both | Preview/Elementor counters |
| nc_stats[].metric | select projects/clients/employees | Yes | ∅; unique | No | Yes | Both | Mapping cố định |
| nc_stats[].value | number, integer >=0 | Yes | seed: 35 / 25 / 20 | No | Yes | Both | Approved/current counters |
| nc_stats[].suffix | text | Yes | + | No | Yes | Both | Preview |
| nc_stats[].label | text | Yes | seed: 3 preview labels | HTML | Yes | Both | Dự án triển khai / Khách hàng tin tưởng / Nhân viên |

Icon lấy từ metric registry, không thêm raw SVG field hoặc trường số riêng VI/EN.

## G04 — Homepage — Services

Location front_page. 3 overview cards, không phải duplicate CPT service content. Post/term destination tagged union chỉ được chọn một nhánh.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_services_eyebrow | text | Yes | Dịch vụ | HTML | Yes | Both | Preview |
| nc_services_heading | text | Yes | Năng lực cốt lõi | HTML | Yes | Both | Preview |
| nc_services_contact_label | text | Yes | Trao đổi nhu cầu | HTML | Yes | Both | Preview; G12 destination |
| nc_service_cards | repeater, exactly 3 | Yes | seed: approved order | No | Yes | Both | Preview |
| nc_service_cards[].title | text | Yes | seed: 3 card titles | HTML | Yes | Both | Preview overview labels |
| nc_service_cards[].description | textarea | Yes | seed: approved descriptions | HTML | Yes | Both | Preview |
| nc_service_cards[].image | image ID | Yes | seed: service-*.png | ALT | Yes | Both | Preview images |
| nc_service_cards[].icon | select code/strategy/user | Yes | seed: corresponding icon | No | Yes | Both | Local SVG registry |
| nc_service_cards[].micro_text | textarea | Yes | seed: CODE… / PEOPLE… / SIMPLE… | HTML | Yes | Both | Preview line breaks |
| nc_service_cards[].target_kind | select page/term | Yes | seed: page,term,term | No | Yes | Both | Existing links |
| nc_service_cards[].target_page | post_object page, ID | C: page | ∅; resolve Outsource | No | Yes | Both | Production Page /outsource/ |
| nc_service_cards[].target_term | taxonomy danh-muc-dich-vu, single ID | C: term | ∅; resolve two terms | No | Yes | Both | Tư vấn/cá nhân; no save_terms/load_terms |

Các title/description này phục vụ 3 nhóm năng lực tổng quan, không thay title/content của bài dịch vụ. Reject cả hai target branches cùng active hoặc invalid/unpublished Page.

## G05 — Homepage — Technology

Location front_page. List có thứ tự; 8 mục seed. Mark không cho nhập HTML tùy ý.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_technology_eyebrow | text | Yes | Công nghệ | HTML | Yes | Both | Preview |
| nc_technology_heading | textarea | Yes | Nền tảng\n tạo nên khác biệt (line break) | HTML | Yes | Both | Preview |
| nc_technology_items | repeater, min 1 | Yes | seed: 8 items | No | Yes | Both | Preview order |
| nc_technology_items[].mark | select react/laravel/node/aws/mysql/docker/figma/cicd | Yes | ∅; unique | No | Yes | Both | Registry local |
| nc_technology_items[].label | text | Yes | seed: brand label | No | Yes | Both | React, Laravel, node.js, aws, MySQL, docker, Figma, CI/CD |

Không cho đổi animation duration hoặc màu mark qua editorial fields. Pause/resume labels dùng UI i18n.

## G06 — Homepage — Featured Projects

Location front_page. **Curated relationship theo từng row** bằng post_object single hỗ trợ mixed post/dich-vu; repeater giữ thứ tự và presentation overrides, không thêm relationship thứ hai gây hai nguồn chọn projects.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_projects_eyebrow | text | Yes | Sản phẩm nổi bật | HTML | Yes | Both | Preview |
| nc_projects_heading | text | Yes | Giải pháp được tin chọn | HTML | Yes | Both | Preview |
| nc_projects_contact_label | text | Yes | Trao đổi về dự án | HTML | Yes | Both | Preview; G12 target |
| nc_featured_projects | repeater, exactly 3 | Yes | seed: Portal/Olympia/Affiliate | No | Yes | Both | Approved cards |
| nc_featured_projects[].object | post_object post+dich-vu, ID | Yes | ∅; resolve production | No | Yes | Both | 1809/1510/1548 candidates |
| nc_featured_projects[].visual_variant | select portal/olympia/affiliate | Yes | seed: approved variants | No | Yes | Both | Mock frames CSS |
| nc_featured_projects[].card_label | text | No | seed: short preview title | HTML | Yes | Both | Presentation only; fallback core title |
| nc_featured_projects[].card_summary | textarea | No | seed: preview short summary | HTML | Yes | Both | Presentation only; fallback core excerpt |
| nc_featured_projects[].card_image | image ID | No | seed: project-*.png | ALT | Yes | Both | Preview screenshot; fallback featured image |

Reject duplicate/unpublished/wrong-type objects. Không thêm permalink field: luôn resolve object URL. Card overrides không ghi ngược post_title/excerpt/thumbnail; cần vì preview mock screen/title ngắn khác bài dài production. Aria label link lấy từ title render + UI i18n, không JavaScript ghép chuỗi Việt.

## G07 — Homepage — Strategic Partner

Location front_page. Destination cần Q5; field optional ở schema nhưng activation gate không cho button “Tìm hiểu thêm” trỏ giả.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_partner_eyebrow | text | Yes | Đối tác chiến lược | HTML | Yes | Both | Preview |
| nc_partner_name | text | Yes | GM Solutions | No | Yes | Both | Current/preview |
| nc_partner_logo | image ID | Yes | seed: gm-solutions.png | ALT | Yes | Both | Existing/preview |
| nc_partner_description | textarea | Yes | seed: approved copy | HTML | Yes | Both | Preview |
| nc_partner_motto | textarea | Yes | seed: Stronger together… | HTML | Yes | Both | Preview line breaks |
| nc_partner_link_label | text | Yes | Tìm hiểu thêm | HTML | Yes | Both | Preview |
| nc_partner_link | link array | No | ∅; Q5 | HTML: title only | Yes | Both | Chưa có đích xác nhận; không seed # |

## G08 — Homepage — Testimonials

Location front_page. Min 1, seed đúng 4. Không tự thêm khách hàng để đủ carousel.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_testimonials_eyebrow | text | Yes | KHÁCH HÀNG NÓI VỀ NEXTCORE | HTML | Yes | Both | Preview |
| nc_testimonials_heading | text | Yes | Đánh giá từ khách hàng | HTML | Yes | Both | Preview |
| nc_testimonials_intro | textarea | Yes | seed: preview intro | HTML | Yes | Both | Preview |
| nc_testimonials | repeater | Yes | seed: 4 real slides | No | Yes | Both | Elementor homepage slides |
| nc_testimonials[].name | text | Yes | ∅; reuse | No | Yes | Both | Exact customer names |
| nc_testimonials[].project_label | text | Yes | ∅; reuse | HTML | Yes | Both | Existing slide title |
| nc_testimonials[].quote | textarea, newline preserved | Yes | ∅; full original | HTML | Yes | Both | Existing content, no rewrite |
| nc_testimonials[].image | image ID | Yes | ∅; map attachments | ALT | Yes | Both | Slide image/preview matching |

UI prev/next/Xem thêm/close/dots status ở i18n. Full quote SSR trong card, CSS clamp 4 dòng; modal dùng text đã dịch, không rút gọn quote lưu trong ACF. Names riêng giữ nguyên; nếu khách hàng có tên thương mại EN chính thức thì xử lý dịch đã duyệt, không tự phiên dịch tên người.

## G09 — Homepage — Team

Location front_page; 3 rows seed, số rows min 1. Tên/chức danh/ảnh theo approved roster.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_team_eyebrow | text | Yes | ĐỘI NGŨ | HTML | Yes | Both | Preview |
| nc_team_heading | text | Yes | Đội ngũ | HTML | Yes | Both | Preview |
| nc_team_intro | textarea | Yes | seed: preview intro | HTML | Yes | Both | Preview |
| nc_team_members | repeater | Yes | seed: Hiền/Anh/Tú | No | Yes | Both | Elementor link-in-bio |
| nc_team_members[].name | text | Yes | ∅; reuse | No | Yes | Both | Real full name |
| nc_team_members[].role | text | Yes | ∅; reuse approved role | HTML | Yes | Both | CEO/CTO/PM labels |
| nc_team_members[].image | image ID | Yes | ∅; real image | ALT | Yes | Both | Production + preview matching |
| nc_team_members[].email | email | No | ∅; reuse if present | No | Yes | Both | Existing real contact |
| nc_team_members[].phone | text, normalize E.164 | No | ∅; reuse if present | No | Yes | Both | Existing real contact |

Không tạo enable-contact booleans trái quyết định đã chốt: contact hợp lệ có thật thì link bật; rỗng thì không render dead button. Không tự dùng global phone cho thành viên thiếu phone.

## G10 — Homepage — CTA

Location front_page; mọi contact URL lấy G12, không lặp field URL.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_cta_heading | text | Yes | Sẵn sàng bắt đầu dự án của bạn? | HTML | Yes | Both | Preview |
| nc_cta_description | textarea | Yes | seed: preview copy | HTML | Yes | Both | Preview |
| nc_cta_button_label | text | Yes | Liên hệ ngay | HTML | Yes | Both | Preview → G12 contact_page |
| nc_cta_motto | textarea | Yes | seed: New ideas. Higher possibilities. | HTML | Yes | Both | Preview line breaks |

## G11 — Global — Header

Location options. Logo dùng core custom_logo; site icon dùng core site_icon. Không tạo logo/site title duplicate trong ACF.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_header_contact_label | text | Yes | Liên hệ ngay | HTML | Yes | Both | Preview; G12 target |

Menus qua WP menu locations. Switch labels, menu open/close, search, logo accessible label qua i18n/core branding. Không field chỉnh vị trí switch: placement là architecture đã chốt. Core custom_logo assignment phải migrate từ theme-mod logo cũ có review, không tự đổi global branding.

## G12 — Global — Footer / Contact / Social

Location options. Đây là global source cho tất cả contact components, không dùng lại địa chỉ từ Elementor cũ làm fallback.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_contact_address | textarea | Yes | 63 Phan Đăng Lưu Street, Hai Chau, Da Nang 550000, Viet Nam | No | Yes | Both | User confirmed canonical string |
| nc_contact_email | email | Yes | info@nextcore.vn | No | Yes | Both | User confirmed |
| nc_contact_phone | text E.164 | Yes | +84378962625 | No | Yes | Both | User confirmed |
| nc_contact_page | post_object page ID | Yes | ∅; resolve /lien-he/ | No | Yes | Both | Production Contact Page |
| nc_footer_description | textarea | Yes | Giải pháp công nghệ cho doanh nghiệp. | HTML | Yes | Both | Preview |
| nc_footer_about_heading | text | Yes | Về Nextcore | HTML | Yes | Both | Preview / footer_about menu |
| nc_footer_support_heading | text | Yes | Hỗ trợ | HTML | Yes | Both | Preview / footer_support menu |
| nc_footer_contact_heading | text | Yes | Liên hệ | HTML | Yes | Both | Preview |
| nc_footer_social_heading | text | Yes | Kết nối | HTML | Yes | Both | Preview |
| nc_footer_motto | textarea | Yes | seed: Build a better tomorrow | HTML | Yes | Both | Preview |
| nc_social_links | repeater, min 0 | No | ∅; approved real links only | No | Yes | Both | Production validated links |
| nc_social_links[].platform | select facebook/linkedin/youtube/tiktok | Yes | ∅ | No | Yes | Both | Existing verified platform |
| nc_social_links[].url | url | Yes | ∅; no placeholders | No | Yes | Both | Confirmed external URL |

Year tự lấy current year, copyright template qua UI i18n và brand; không tạo field hardcoded 2026. Social accessible names lấy platform registry đã i18n; không cần field icon HTML. Canonical address không dịch địa danh; nhãn Address/Địa chỉ được dịch qua UI. Chưa có map URL đúng địa chỉ mới thì không thêm guessed embed; CONTACT-01 riêng.

## G13 — Theme — Light/Dark Media Pairs

Location options; chỉ homepage consumers dùng các cặp này. Image/file IDs, không URLs duplicate. Render helper nhận ID rồi trả URL/size đúng mode; video không src thật trước active selection.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_hero_image_dark | image ID | Yes | seed: hero-corporate-v2.png | ALT | Yes | Dark | Approved preview |
| nc_hero_image_light | image ID | Yes | seed: hero-corporate-light.png | ALT | Yes | Light | Approved preview |
| nc_about_video_dark | file ID, mp4 | Yes | seed: nextcore-danang.mp4 | No | Yes | Dark | Approved video |
| nc_about_video_light | file ID, mp4 | Yes | seed: caurongquay-light-video.mp4 | No | Yes | Light | Approved video |
| nc_about_poster_dark | image ID | Yes | seed: danang-poster.jpg | No | Yes | Dark | Decorative video background |
| nc_about_poster_light | image ID | Yes | seed: cauronglight.png | No | Yes | Light | User supplied sharp poster |
| nc_cta_image_dark | image ID | Yes | seed: cta.png | ALT | Yes | Dark | Approved preview |
| nc_cta_image_light | image ID | Yes | seed: cta-light.png | ALT | Yes | Light | Approved preview |

Video/poster aria-hidden như preview; nội dung tương đương là copy HTML. Không cung cấp media theo EN riêng hoặc custom colors trong ACF. Required pairs là preflight requirement; thiếu asset phải báo admin, không tự thay dark media vào light.

## G14 — Homepage — Blog headings

Location front_page; chỉ editorial headings, không data của Posts.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_blog_eyebrow | text | Yes | Tin tức | HTML | Yes | Both | Preview |
| nc_blog_heading | text | Yes | Cập nhật mới nhất | HTML | Yes | Both | Preview |
| nc_blog_note | text | Yes | Góc nhìn & kiến thức | HTML | Yes | Both | Preview |

Query latest 3 là behavior cố định; không field count/order/selected_posts mâu thuẫn quyết định người dùng.

## G15 — Global — Integrations (ownership pending Q4)

Location options, admin only. Field configuration dưới đây dành cho theme-owned adapter nếu được duyệt; nếu plugin làm owner thì giữ config tại plugin, **không tạo hai nguồn settings cho cùng integration**. Nhận diện OA/analytics public IDs là config, không license/API secret; giá trị thực không chép vào tài liệu.

| Field path | Type | R | D/seed | T | Shared | Mode | Reuse source |
|---|---|---|---|---|---|---|---|
| nc_contact_integration_owner | select theme/plugin | Yes | ∅; Q4 | No | Yes | Both | Owner review |
| nc_messenger_url | url | C: theme owner | ∅; reuse verified existing destination | No | Yes | Both | Child footer vs production |
| nc_zalo_oa_id | text, digits only | C: theme owner | ∅; securely copy public config at migration | No | Yes | Both | Existing OA widget |
| nc_zalo_welcome | text | C: theme owner | seed: existing welcome | HTML* | Yes | Both | Existing widget; *provider iframe may not translate |
| nc_analytics_owner | select theme/external | Yes | ∅; Q4 | No | Yes | Both | Production script ownership |
| nc_umami_script_url | url | C: theme analytics | ∅; reuse existing script endpoint | No | Yes | Both | Existing integration |
| nc_umami_website_id | text | C: theme analytics | ∅; securely copy existing public config | No | Yes | Both | Existing integration |

Không thêm field global phone thứ hai, API token, password, license key hoặc arbitrary script HTML. Zalo autopopup=0 giữ behavior hiện tại; floating cluster controls từ i18n. Dịch nội dung trong iframe provider không nằm trong bảo đảm TranslatePress.

## Validation và triển khai schema sau duyệt

- Required groups/rows/targets/media có validation editor và preflight release, không public fatal nếu ACF tắt. Nếu thiếu ACF Pro, site phải degrade có thông báo admin, nhưng release không được coi là đạt homepage editable/CPT requirements.
- Tránh repeater field tự save_terms vào Page; relationships chỉ đọc object/term đích. Không đổi taxonomy relationships nội dung khi chọn overview card.
- Stable IDs riêng cho card/dialog lấy từ row identity trong renderer; không dùng tên đã dịch làm HTML IDs. Nếu cần persisted row identifier ở implementation thì tận dụng ACF row order + page context, không tạo business ID ngầm đổi permalink.
- Các field nguồn translation HTML render server-side; ALT behavior cần regression với SEO Pack hiện off; không đánh dấu “copy/translate” như WPML config vì plugin là TranslatePress.
- Các group mặc định không thêm section enable/disable hay reorder UI: section order đã khóa. Admin sửa nội dung không được làm menu dẫn anchor biến mất.
- Chưa đề xuất schema ACF cho toàn bộ Lark/Outsource vì rollout giữ content cũ có wrapper. Nếu sau này chuyển toàn native, lập schema page-specific từ production, không áp homepage groups lên các pages đó.
