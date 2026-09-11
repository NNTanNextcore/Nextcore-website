# Phase 4 — Data status

Ngày 11/09/2026. **Không register/seed/migrate values, không ghi DB.** Schema gốc giữ trong acf-schema-plan.md. inc/home-data.php là approved fallback source, không phải import script.

## Consumer mapping

| Group / consumer | WordPress / ACF source | Fallback source | Pending |
|---|---|---|---|
| G01 Hero | nc_hero_eyebrow, line_one, line_two_prefix, brand, slogan, description, contact_label, secondary_label | Exact preview copy/line breaks | Schema và values |
| G02 About | nc_about_eyebrow, heading_line_one/two, founded_date, body, link_label | 3 đoạn preview, 2022-06-15; allowed p/strong/em/a | Schema và values |
| G03 Stats | nc_stats: metric/value/suffix/label | 35+/25+/20+, registry icons | Schema và values |
| G04 Services | nc_services_* headings; nc_service_cards | 3 preview cards; get_page_by_path/get_term_by hoặc configured IDs | Schema/relationships; local links đã resolve, reconcile production trước release |
| G05 Technology | nc_technology_eyebrow/heading/items | 8 approved local marks/order | Schema/values; mark HTML chỉ từ registry |
| G06 Projects | nc_projects_*; nc_featured_projects object/visual_variant/card_label/summary/image | 3 visual cards preview; Olympia/Affiliate hai audited slugs | Portal relationship; schema/values/reconcile |
| G07 Partner | nc_partner_eyebrow/name/logo/description/motto/link_label | GM visual/copy preview | Schema/values; nc_partner_link chưa consumer vì destination khóa https://gm-group.vn/ |
| G08 Testimonials | nc_testimonials_*; nc_testimonials name/project_label/quote/image | 4 full quotes từ preview đã duyệt | Schema/attachment mapping |
| G09 Team | nc_team_eyebrow/heading/intro/members | 3 portraits/name/role preview; contacts đối chiếu production | Schema/attachments, không tạo CPT team |
| G10 CTA | nc_cta_heading/description/button_label/motto | Preview; URL từ global Contact helper | Schema/values |
| G11 Header | Core custom_logo; nc_header_contact_label; WP menu locations | Local approved logo, development-only anchor menu | Gán logo/menus ở phase được phép; không duplicate logo ACF |
| G12 Footer | nc_contact_address/email/phone/page; footer description/headings/motto; nc_social_links | Contact đã khóa; preview copy; verified Facebook/TikTok | Schema/values, menu assignment |
| G13 Media | nc_hero_image_dark/light, nc_about_video_dark/light, nc_about_poster_dark/light, nc_cta_image_dark/light | 8 approved local media assets | Attachment IDs / schema |
| G14 Blog | nc_blog_eyebrow/heading/note + WP_Query published post | Heading preview; **không fallback bài viết giả** | Schema headings; Posts dùng nguồn core |
| G15 Integrations | Giữ skeleton contract | enabled=false | Chưa bật provider render; không thay owner/plugin settings |

Tất cả G01–G14 chưa có final schema/values do Phase 3 chỉ tạo loader; Phase 4 chỉ consumer layer. Core Posts/Pages/terms tồn tại local được đọc, không copy thành fields hoặc tạo nội dung mới. Safe fallbacks phục vụ development/theme safety, không chứng nhận release data đã hoàn tất.

## Project resolution

- Configured object: chỉ nhận published post hoặc dich-vu; invalid/unpublished/duplicate object không tạo link.
- Portal: giữ ảnh/copy/card; không link khi thiếu relationship. Người dùng đã duyệt lựa chọn này trong Phase 4; không dùng ID draft local hoặc đoán URL.
- Olympia/Affiliate: resolve đúng hai audited Post slugs, rồi get_permalink; không hardcode absolute production URL hay ID.
- Không có project placeholder modal.

## Contact và social đã đối chiếu

Người dùng cho phép dùng thông tin [homepage production](https://nextcore.vn/) sau đối chiếu ngày 10/09/2026. Source HTML có contact theo từng team card; các tel được chuẩn hóa sang +84, không đổi số:

| Member | Email | Phone |
|---|---|---|
| Nguyễn Văn Hiền | hiennv@nextcore.vn | +84378962625 |
| Trần Đức Anh | anhtd@nextcore.vn | +84976748059 |
| Phan Thanh Tú | tupt@nextcore.vn | +84979525694 |

Profile thật: [Facebook](https://www.facebook.com/nextcore.software.jsc), [TikTok](https://www.tiktok.com/@nextcore.software.jsc). Không dùng Facebook sharer/LinkedIn shareArticle làm profile; không tự tạo LinkedIn/YouTube link.

Global contact giữ nguyên address/email/phone đã khóa; không lấy địa chỉ legacy khác làm fallback.

## Menus và multilingual

- 6 locations đăng ký; chưa assign/write menus.
- Development fallback chỉ top-level anchors. Menu fixture trong checks chỉ nằm trong RAM, qua wp_nav_menu/core Walker để QA.
- VI/EN renderer đã test với TranslatePress local. Editorial HTML SSR; mode switching không thay text DOM.
- Một số chuỗi mới chưa có dictionary translation; người dùng đã yêu cầu **soạn bản dịch để duyệt ở bước tiếp theo**. Chưa soạn/import bản dịch trong Phase 4.
- Giữ native WP search, không SEO Pack mới, không WPML/Polylang hoặc duplicate EN Page.

## Gates trước release

Hoàn thiện schema/values, menu assignments, Portal relationship, bản dịch EN được duyệt; reconcile production mới nhất và xác minh baseline runtime/cache/CSP. Chưa có activation/DB migration nào trong deliverable này.

