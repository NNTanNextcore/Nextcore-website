# Sau review Phase 5

Đã làm local: homepage visual, ACF JSON G01–G15, 75 values/105 field definitions, 22 media mappings, hai homepage menu objects/assignments, real WP_Query, EN proposal134chuỗi, QA và exact write report.

Còn lại trước release:

- Người dùng review Phase5 và duyệt bản dịch EN; chưa ghi TranslatePress dictionary.
- Reconcile production snapshot mới: front page/objects/terms/media/menu IDs; Portal phải published và được xác minh.
- Review core branding và primary/primary_mobile/footer menu assignments theo rollout; giữ menu cũ.
- Xác minh PHP/plugins/cache/minify/CSP production; initializer theme-init/media chạy đúng trước paint.
- Kiểm tra Admin save/upload trong context Nextcore được activate theo bước được duyệt; harness Phase5 đã kiểm tra markup/values/validation.
- Hoàn thiện inner-page Elementor/shortcodes/Lark/gallery compatibility và CONTACT-01 theo phạm vi riêng.
- Integration ownership/provider configs/duplicate scripts QA trước bật; adapter hiện disabled.
- Backup/staging/rollback và quyền triển khai trước activation/deployment. Không tự nâng plugin, tạo redirects hoặc import local database.
