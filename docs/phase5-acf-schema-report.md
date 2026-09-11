# Phase 5 — ACF schema report

Ngày 11/09/2026. **15 nhóm JSON, 105 field gồm 75 field cấp cao và 30 subfield definitions**. ACF Pro local tải đủ nhóm bằng JSON; không acf_add_local_field_group hoặc registration PHP trùng.

| Group | Location | Top-level | Subfields | Definition |
|---|---|---:|---:|---|
| G01 — Trang chủ — Hero | front_page | 8 | 0 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_hero.json) |
| G02 — Trang chủ — Giới thiệu và video | front_page | 6 | 0 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_about_video.json) |
| G03 — Trang chủ — Số liệu | front_page | 1 | 4 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_stats.json) |
| G04 — Trang chủ — Dịch vụ | front_page | 4 | 8 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_services.json) |
| G05 — Trang chủ — Công nghệ | front_page | 3 | 2 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_technology.json) |
| G06 — Trang chủ — Dự án nổi bật | front_page | 4 | 5 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_projects.json) |
| G07 — Trang chủ — Đối tác chiến lược | front_page | 7 | 0 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_partner.json) |
| G08 — Trang chủ — Đánh giá khách hàng | front_page | 4 | 4 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_testimonials.json) |
| G09 — Trang chủ — Đội ngũ | front_page | 4 | 5 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_team.json) |
| G10 — Trang chủ — Lời mời liên hệ | front_page | 4 | 0 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_cta.json) |
| G11 — Toàn site — Header | nextcore-settings | 1 | 0 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_header.json) |
| G12 — Toàn site — Footer và liên hệ | nextcore-settings | 11 | 2 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_footer.json) |
| G13 — Toàn site — Media sáng/tối | nextcore-settings | 8 | 0 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_media.json) |
| G14 — Trang chủ — Tiêu đề tin tức | front_page | 3 | 0 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_blog.json) |
| G15 — Tích hợp — Chưa bật triển khai | nextcore-settings | 7 | 0 | [JSON](../wp-content/themes/nextcore-theme/acf-json/group_nc_integrations.json) |

## Admin UX và source ownership

Các group G01–G10/G14 ở Page được core chọn làm front page; G11/G12/G13/G15 ở options page Nextcore, quyền manage_options. Labels tiếng Việt, thứ tự G01–G15; image/file dùng Media Library, post_object/taxonomy trả ID đơn. Không save_terms/load_terms/add_term, không duplicate core titles/content/permalinks/featured image/categories/tags; project overrides chỉ là cách trình bày card.

`inc/acf.php` chỉ lo options page, JSON load/save paths và validation/helper. Save path chỉ đổi cho group_nc_*, không redirect nhóm legacy. JSON không tự import definition vào database hoặc tự ghi values. Khi Nextcore đang được chọn trong request, ACF đọc JSON và có thể render fields trực tiếp mà không cần sync definitions vào DB.

Theme hiện hành vẫn flatsome-child. Các field đã QA qua **Admin render harness trong context Nextcore**, chưa mở Nextcore Settings từ Admin của theme đang active. Khi tới bước kích hoạt Nextcore được duyệt, sửa Trang chủ để thấy homepage groups; mở menu Nextcore để sửa globals. Không thêm mu-plugin hoặc sửa theme cũ để ép load nhóm của theme chưa active.

## Validation

- Required fields, repeater min/max; Stats/Services/Projects đúng 3 rows.
- Reject duplicate metric/technology mark/project variant/social platform; duplicate project objects.
- Service tagged union: một trong Page published hoặc term danh-muc-dich-vu hợp lệ. Không ghi term relationship lên front page.
- Project object chỉ post/dich-vu published. Portal để trống theo ngoại lệ đã được người dùng duyệt; Olympia/Affiliate phải có object.
- Image/file attachment hợp lệ, video MP4; select chỉ nhận giá trị registry; số nguyên không âm; E.164 phone; OA digits; URL HTTP(S).
- WYSIWYG toolbar basic/media off; reject script/iframe/style/shortcode, update/render chỉ cho p/strong/em/a.
- Không có raw SVG/CSS/JS/credentials fields, section-order hoặc enable toggle.
- Optional social repeater đã cấu hình rỗng render rỗng; không khôi phục social fallback. Partner link lấy ACF array thật, rỗng thì bỏ CTA; custom technology label cũng hiển thị từ ACF.

## Hai ngoại lệ đã xử lý theo Phase 5

1. `nc_featured_projects[].object` required=false ở field để Portal có thể pending; group validation vẫn bắt buộc Olympia/Affiliate và reject mọi object invalid/draft/duplicate.
2. G15 owner selectors required; các public provider config chưa verified cho rollout vẫn optional/rỗng vì integration adapter `enabled=false`. Cấu hình UI không bật Messenger/Zalo/Umami/floating cluster. Không chép ID/plugin config nhạy cảm, không có enable control trái phase scope. Cần preflight required configs ở integration QA sau này.

## Evidence

[validation-results.json](checks/phase5/validation-results.json): 99 checks PASS, gồm 75 field-reference checks, field count, date format, quote integrity, pending Portal, invalid relationships/phones/HTML, media và relationship Admin controls, menus, latest3 query, empty social, editable technology label và partner link validation. [admin-fields.html](checks/phase5/admin-fields.html) là markup read-only, không phải trang Admin có thể submit. PHP lint đạt toàn bộ 58 PHP theme và 7 PHP harness ở thời điểm kiểm tra.
