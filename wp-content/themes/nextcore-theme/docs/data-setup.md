# Phase 5 — Local data setup

Schema version-controlled: acf-json/group_nc_*.json. Source plan, exact values, IDs và write journal xem docs/phase5-acf-schema-report.md, docs/phase5-local-write-report.md của repository.

## Routine local đã thực hiện

Từ repository root dùng PHP CLI chạy docs/checks/phase5/setup.php (dry-run) hoặc thêm --apply-local. Routine nằm ngoài theme runtime, chỉ cho XAMPP loopback đã audit, không seed khi view/activate. Lần hai đã xác minh không query ghi.

75 field cấp cao được seed qua field keys; 22 asset map checksum (10 reuse,12 import), đủ G13 pairs. Page/term/project/contact resolve từ slugs/core frontpage. Portal vẫn pending và không link, không dùng draft1809.

Dữ liệu đã có được preserve; routine không overwrite chỉnh sửa admin hoặc append repeater rows. Media giữ nguyên full original, không duplicate checksum. Không auto-parent attachment cũ khi setup.

## Admin

Khi Nextcore ở context theme, sửa Page được core chọn làm front page cho G01–G10/G14; menu Nextcore cho G11/G12/G13/G15. Phase5 chưa activate theme; đã test acf_render_fields và ACF values trong CLI harness. Admin đang active flatsome-child chưa có Nextcore Settings.

Partner link dùng ACF link array. Technology labels sửa được. Social repeater rỗng render rỗng. Projects override rỗng fallback core title/excerpt/thumbnail. Blog luôn query latest3 published post.

## Menus

Local đã tạo Desktop84/Mobile85 và gán home_primary/home_mobile, đúng hierarchy Page/term/category. Giữ menu69 và theme mods cũ, không gán primary/primary_mobile/footer locations mới trong phase này. Footer development fallback giữ thứ tự preview.

## Review và rollout

docs/homepage-en-translation-review.md chứa proposal, chưa dictionary write. G15 config UI chưa bật providers. Production phải resolve lại media/object/menu IDs và đối chiếu data mới; không import local DB. Xem docs/phase5-data-reconciliation.md.
