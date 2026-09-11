# Data mapping cuối — Phase 2

Ngày 10/09/2026. Mapping có thể review, chưa migration. Các ID dưới đây là **evidence/candidates từ audit**, không constants nhúng vào theme. Resolve trên snapshot production chuẩn và ghi mapping trước khi ghi dữ liệu.

## 1. Quy tắc nguồn chuẩn

| Loại thông tin | Nguồn chuẩn |
|---|---|
| Object ID, type, publish status, URL, editor content, bản dịch cũ | Production |
| Hình học homepage, thứ tự section, ảnh/media light/dark đã duyệt | Actual preview hiện tại |
| Hành vi links, blog selection, contacts, menu order, mode preference | Quyết định Phase 2 của người dùng |
| Source local/DB local | Bằng chứng audit và staging candidate, không ghi đè production |

Không tự hợp nhất draft local 1809 với published production bằng title matching. Xác minh permalink + post type + ID + publish state trên nguồn chuẩn; nếu object mất/rỗng phải báo thiếu mapping, không tự lấy bài bất kỳ. Các field required không làm phát sinh auto-seed trong page load.

## 2. Homepage section → current → new → reuse

| Section | Current source | New source / renderer | Migration/reuse rule |
|---|---|---|---|
| Header | Preview layout; theme_mods logo; menu 69 live | WP custom logo + WP menus + global Header ACF | Lấy logo thật đã duyệt; home menu mới đúng section order; inner menu giữ object links; không sửa menu 69 thành anchor toàn site |
| Hero | Preview assets/copy; live hero là Revolution | G01 + G13 → home/hero | Seed copy đã duyệt và media pairs; không render shortcode Revolution hoặc nhân đôi DOM |
| About/video | Preview copy/video/poster; live Elementor HTML | G02 + G13 → home/about-video | Giữ date 2022-06-15, ba đoạn, white copy overlay; poster light dùng cauronglight.png, không tạo ảnh khác |
| Stats | Preview 35/25/20; Elementor counters homepage 312 khớp | G03 repeater → home/stats | Giữ thứ tự dự án/khách hàng/nhân viên; không số animation placeholder |
| Services | Preview 3 cards; Page Outsource và service terms | G04 → home/services | Cards presentation giữ preview; target refs: Outsource Page; term tư vấn; term cá nhân; URL qua APIs/plugin |
| Technology | Preview marks/labels/ordering | G05 → home/technology | Reuse inline/local marks, không tải CDN icon pack; cùng list cho cả modes |
| Projects | Preview 3 card visuals; mixed post + dich-vu | G06 curated object repeater → home/projects | Giữ order Portal/Olympia/Affiliate; chọn object thật, get_permalink(); bỏ detail placeholder modal cho project |
| Partner | Preview GM Solutions; current homepage partner data | G07 → home/partner | Reuse name/logo/copy; link “Tìm hiểu thêm” chưa xác minh đích (Q5), không tạo URL giả |
| Testimonials | Elementor testimonial-carousel slides homepage 312 | G08 repeater → home/testimonials | Giữ names/images/project labels/full quotes; full text SSR, clamp chỉ CSS, carousel thủ công + modal |
| Team | Elementor link-in-bio homepage 312, ảnh thật preview | G09 repeater → home/team | Reuse name/role/photo/email/phone từ production; chỉ bật contact có giá trị hợp lệ; không avatar giả |
| Blog | Core Posts; preview là editorial examples | WP_Query latest 3 + G14 headings → home/blog | post_type=post, publish, date DESC + ID DESC tie-break, ignore sticky promotion; title/excerpt/thumbnail/permalink từ core |
| CTA | Preview 2 media/copy; Contact Page thật | G10 + G13 + G12 contact_page → home/cta | Button là link, không contact placeholder dialog; localized `/lien-he/` |
| Footer | UX Block 703 + preview contact layout | G12 + WP menus + custom logo → footer | Thay UX renderer bằng native; giữ global contact chuẩn, social links thật, copyright cuối |
| Floating integrations | Child footer + plugin support-chat | G15 + integrations adapter nếu owner theme được duyệt | Không cùng lúc render theme và plugin; xem integrations plan/Q4 |

G01–G15 tham chiếu [acf-schema-plan.md](acf-schema-plan.md). Native homepage không render post_content Elementor của front page; giữ dữ liệu cũ để rollback, không delete metadata.

## 3. Object và link mapping cụ thể

| Vị trí | Production route / type cần resolve | Evidence ID | Điều kiện |
|---|---|---|---|
| Outsource service card | `/outsource/`, page | 1133 | Published, giữ custom wrapper |
| Tư vấn service card | `/danh-muc-dich-vu/tu-van-doanh-nghiep/`, taxonomy | term 74 | Resolve đúng taxonomy, không Page giả |
| Cá nhân service card | `/danh-muc-dich-vu/khach-hang-ca-nhan/` | term 75 | Dùng term_link; label card vẫn “Giải pháp cá nhân” |
| Portal project | `/dich-vu/phan-mem-cham-cong-nextcore-portal/`, dich-vu | public 1809 | Local 1809 draft post không dùng để seed |
| Olympia project | `/truong-doanh-nhan-top-olympia/`, post | 1510 | Không chuyển CPT |
| Affiliate project | `/wordpress-plugin-affiliate/`, post | 1548 | Không nhầm với dich-vu 1761 “Wordpress aff atv” |
| Contact CTA toàn homepage | `/lien-he/`, page | 320 | Lấy từ global contact_page; plugin tạo URL EN |
| Homepage menu anchors | #about/services/technology/projects/partner/testimonials/team/blog/contact | Không post IDs | Luôn tăng theo section order; section IDs không dịch |
| About “Tìm hiểu thêm” | #team | Preview | Giữ anchor vì là CTA khám phá team, không phải CTA liên hệ |
| Hero “Xem năng lực” | #services | Preview | Giữ anchor |

Blog Query không tạo field title/body/thumbnail/permalink duplicate. Nếu ít hơn 3 published posts, hiển thị số có thật; không đưa draft/test/example bù đủ. Không lọc ra Olympia/Affiliate nếu chúng thuộc 3 bài mới nhất vì người dùng đã chốt latest Posts. Không query attachments/dich-vu làm blog. EN dùng cùng query IDs, TranslatePress dịch output, không query category language giả.

Project `card_image`, `card_summary`, `card_label` chỉ là **presentation override cho card homepage đã duyệt** khi khác featured image/excerpt/title bài dài; không ghi ngược vào core data. Rỗng thì dùng core equivalents; 3 card seed theo preview để giữ hiển thị ngắn gọn.

## 4. Reuse team/testimonials/media

Team image candidates: Hiền 1799, Anh 527, Tú 526. Testimonial candidates: An Tâm Việt 1578, Phạm Việt Hùng 1580, Nguyễn Phương Trà My 1581, Bé khỏe bé vui 1579. Attachment IDs phải kiểm tra production và kích thước/crop; không suy ra ID từ tên file khi có nhiều bản.

Ảnh preview local chưa chắc có trong Media Library production. Ở phase migration, lập asset manifest: file gốc → checksum → attachment tồn tại hoặc import có review → ACF field. Không nhập trùng mỗi activation. Media fallback bundled của theme chỉ dùng assets đã duyệt, không generated replacement. Font Be Vietnam Pro 700 local + license, body font giữ như preview.

Không thêm EN records cho repeater. Một bộ ảnh, IDs và contacts dùng chung; dịch editorial text qua output. Quote full giữ nguyên ngôn ngữ nguồn (review cuối vốn tiếng Anh), không viết lại lời khách hàng để “đồng nhất”.

## 5. Global contact và integrations

Nguồn duy nhất G12:

- Address: **63 Phan Đăng Lưu Street, Hai Chau, Da Nang 550000, Viet Nam**.
- Email: **info@nextcore.vn**.
- Phone E.164: **+84378962625**.
- Contact Page relationship: production `/lien-he/`.

Footer, CTA destinations và floating phone dùng chung nguồn. Task migration riêng **CONTACT-01**: kiểm tra Contact Page production, thay địa chỉ cũ nếu còn và map/embed bản đồ đúng địa điểm sau xác minh; cập nhật text nodes liên quan bản dịch. Không lấy tọa độ cũ 384 2/9 làm tọa độ địa chỉ mới. Chưa sửa Page/Elementor data ở Phase 2.

Messenger link candidate đã có trong child source; Zalo/Umami public configuration được tham chiếu từ nguồn chuẩn, không lưu credentials vào docs. Không tự thêm LinkedIn/YouTube vì preview có icon placeholder. Kiểm chứng public URLs trong source trước migration và chỉ bật entries có link thật.

## 6. Bảo toàn dữ liệu không phải homepage

- Giữ post/page content/meta/terms, published status, parent hierarchy, ACF gallery/custom_css/redirect_service, menu objects và TranslatePress tables.
- Giữ CPT `dich-vu`, taxonomy `danh-muc-dich-vu` gắn post + dich-vu, has_archive=false, with_front/rewrite hiện tại; ACF plugin vẫn là owner registration.
- `/dich-vu/` là Page, `/tin-tuc/` là posts page; template choices không đổi URL.
- `/demo/plety/` không port; không tự xóa Page hoặc migration toàn parent `/demo/`.
- URL chấm công cũ public 404: không tự redirect nếu chưa chốt Q7. Production trạng thái hiện tại là baseline.
- Lark HTML/style và About UX là các task riêng trước activation; không coi data tồn tại là layout đã tương thích.

## 7. Quy trình migration đề xuất sau review

1. Snapshot production trên staging, kiểm tra manifest IDs/URLs/attachments/plugin versions. Chưa có snapshot mới thì block seed/activation, không giả định local đã mới.
2. Review diff nguồn → schema values, bao gồm mọi presentation overrides; lập danh sách chuỗi homepage cần thêm EN.
3. Tạo schema, import assets/map refs và seed **một lần có kiểm soát**, không khi view/activate và không overwrite giá trị admin đã chỉnh. Chưa thực hiện ở Phase 2.
4. Map WP menus theo location; giữ existing site menu, homepage menu riêng đúng order; review menu layout Q1.
5. Hoàn tất builder compatibility tasks/CONTACT-01/integration ownership, kiểm thử staging; sau đó mới đề nghị release/activation theo phase được cho phép.
6. Giữ backup/diff để rollback dữ liệu lẫn theme. ZIP chỉ theme không thay thế data migration.
