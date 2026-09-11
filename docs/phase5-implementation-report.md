# Phase 5 — Implementation report

Ngày 11/09/2026. **Hoàn tất phạm vi Phase 5 trên local, chờ review.**

## Kết quả

- 15 ACF JSON groups G01–G15, 105 fields gồm 75 cấp cao và 30 subfield definitions. Labels tiếng Việt, media/relationship selectors, required/conditional/unique validation; không duplicate definition PHP.
- 75 giá trị cấp cao được seed có field references trên front page được resolve và Nextcore options. Giữ đủ 4 quote thật, 3 thành viên/contact, GM Solutions, contact và social đã duyệt. G13 đủ 8 media IDs.
- 22 asset map bằng checksum: reuse 10 attachment, import 12 local; ảnh cauronglight.png dùng đúng bản sắc nét đã cung cấp. Không tạo ảnh mới.
- Services map Page Outsource và 2 taxonomy terms thật. Olympia/Affiliate map published Posts; Portal vẫn không link, không tạo nội dung thay thế.
- 2 WP menu thật: Desktop 84 (13 items), Mobile 85 (15 items), đúng hierarchy và home_primary/home_mobile assignments. Menu 69 được giữ nguyên.
- [EN review](homepage-en-translation-review.md): 134 chuỗi nguồn, đề xuất EN và đối chiếu dictionary hiện có. Chưa ghi translations.
- Integration config có UI nhưng adapter vẫn enabled=false; không tải SDK/analytics/floating cluster.

## Thay đổi consumer để dùng ACF thật

Partner CTA đọc link array đã cấu hình, hỗ trợ target an toàn và bỏ nút khi optional link được xóa. Projects dùng core title/excerpt/featured image khi override rỗng; nhãn khung giả lập lấy registry visual_variant để giữ visual sau khi bỏ các fallback-only keys khỏi rows ACF. Technology label thực sự sửa được; optional social repeater rỗng không tái hiện các fallback rows.

CSS/JS và static preview không thay trong Phase 5. Không sửa WordPress core, plugin, Flatsome hoặc flatsome-child.

## Routine thủ công

Tại C:\xampp\htdocs:

```powershell
& C:\xampp\php\php.exe docs/checks/phase5/setup.php
& C:\xampp\php\php.exe docs/checks/phase5/setup.php --apply-local
```

Lệnh đầu chỉ đọc DB và ghi dry-run artifact; lệnh hai là ghi local có journal. Đã thực thi cả dry-run, apply và rerun. Rerun không query ghi, không thêm media/menu/repeater. Routine kiểm tra loopback DB_HOST và workspace XAMPP, chặn writes ngoài scope và mọi trp table, chỉ tạo field chưa tồn tại, không overwrite dữ liệu admin.

Routine nằm ngoài runtime theme; không activation hook hoặc page-load seeding. Request chỉ chọn Nextcore/ACF cho việc setup/render; stored active theme vẫn flatsome-child và plugin settings không đổi. Journal ghi rõ ACF đã tự re-parent attachment 1541 trong lần đầu; side effect này đã được khôi phục đầy đủ, routine nay chặn tự re-parent.

## QA

| Kiểm tra | Kết quả |
|---|---|
| PHP lint | PASS — 58 theme PHP và 7 harness PHP; inc/acf.php lint lại sau sửa cuối |
| JSON / ACF loading | 15 groups, 105 fields, không trùng keys; ACF Pro local tải được |
| Schema/Admin/data harness | 99/99 PASS; selectors thật render được, ACF references đúng, full quotes giữ nguyên |
| VI visual | 12/12 PASS: 1440,1200,1024,768,480,375 × dark/light |
| Geometry so với Phase 4 | Mỗi section width/height/y lệch không quá 2px; không tràn ngang |
| Images/JS | Không ảnh lỗi, không JS exceptions |
| Interactions | 30/30 PASS: WP menu, keyboard, mobile accordion, video mode/lifecycle, modal/carousel, VI/EN route/contact |
| Visual inspection | Đã xem ảnh About light, Services dark và footer light 375px; 9 ảnh chi tiết lưu kèm |
| Blog | WP_Query latest3 publish: 1732,1548,1510 tại thời điểm QA; không seed cards |
| Idempotency | Rerun database_diff=[], imports=0 |

Evidence: [validation](checks/phase5/validation-results.json), [browser](checks/phase5/browser-results.json), [interactions](checks/phase5/interaction-results.json), [write journal](phase5-local-write-report.md).

Browser QA dùng HTML snapshot được render bởi WordPress + ACF + TranslatePress thật, chỉ đọc DB. Chrome chỉ tải asset static local, không gửi form/AJAX hoặc request nghiệp vụ. Admin validation dùng acf_render_fields, chưa kiểm tra thao tác upload/save trong một phiên Admin đăng nhập của Nextcore. Sau các sửa consumer cuối, snapshot VI/EN và validation được tạo lại, ảnh chi tiết được chụp lại; 12-case và interaction suite đã chạy trên dữ liệu ACF cùng menu này.

## Cách review

Mở [homepage snapshot VI](http://localhost/docs/checks/phase5/rendered-plugins.html); dùng nút đổi màu để xem dark/light. Đây là snapshot review; nội dung không tự cập nhật sau khi sửa DB, cần chạy lại render.php --plugins. Link nghiệp vụ trỏ tới WordPress local đang chạy theme hiện hành.

ACF JSON sẵn sàng trong context Nextcore. **Theme hiện hành vẫn flatsome-child**, nên Admin hiện tại chưa có menu settings của Nextcore. Đã kiểm tra groups qua harness theo phạm vi cho phép; việc activate để kiểm tra Admin tương tác và inner-site compatibility thuộc bước được duyệt tiếp theo.

## Tài liệu bàn giao

1. [Schema report](phase5-acf-schema-report.md)
2. [Local write report](phase5-local-write-report.md)
3. [EN translation review](homepage-en-translation-review.md)
4. [Data reconciliation](phase5-data-reconciliation.md)
5. [Open questions](phase5-open-questions.md)

Dừng ở Phase 5. Không deploy, activate production, import database, ghi dictionary, bật integrations hoặc tạo ZIP.
