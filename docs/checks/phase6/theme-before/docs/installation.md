# Nextcore Theme — Phase 5 local review

Homepage port, ACF schema, local data và homepage menu objects đã hoàn tất. Theme **chưa activate**, chưa ZIP/deploy; stored stylesheet vẫn flatsome-child.

- Reports Phase 5 trong docs/ của repository là trạng thái mới nhất; reports Phase 4 giữ nguyên làm baseline.
- Snapshot VI: http://localhost/docs/checks/phase5/rendered-plugins.html; EN hiện tại: rendered-plugins-en.html. Dữ liệu ACF/menu thật, render trong context Nextcore với ACF/TranslatePress.
- Snapshot chỉ là artifact static, không tự phản ánh sửa Admin. Tạo lại bằng PHP CLI docs/checks/phase5/render.php --plugins (hoặc thêm --english).
- Harness chặn mọi SQL write; script setup.php riêng có dry-run/--apply-local và journal.
- ACF groups load từ JSON khi Nextcore ở context theme. Khi được duyệt activate, sửa Trang chủ cho homepage groups, mở Nextcore cho globals. Admin đang chạy flatsome-child chưa có Nextcore Settings.
- Production cần resolve IDs mới, review Portal/EN/integrations/inner-site compatibility/runtime/cache/CSP trước release. Không import local DB.
- Static review loại plugin scripts; link nghiệp vụ vẫn trỏ đến WordPress local dùng theme hiện hành.
- Assets enqueue trực tiếp, không build pipeline. Không sửa core, plugins hoặc parent/child theme cũ.
