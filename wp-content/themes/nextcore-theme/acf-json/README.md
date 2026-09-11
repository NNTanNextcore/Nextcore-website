# ACF JSON — Phase 5

15 nhóm G01–G15 chứa 105 field definitions. ACF tự load JSON trong context Nextcore; inc/acf.php không khai báo nhóm PHP trùng. Save path chỉ áp dụng group_nc_*, không thay nhóm legacy.

Định nghĩa JSON không tự ghi values/database. Routine thủ công local ở docs/checks/phase5/setup.php (repository); mặc định dry-run, --apply-local để seed theo scope đã duyệt. Xem docs/phase5-acf-schema-report.md và docs/phase5-local-write-report.md ở repository.

Không sync/import local IDs lên production. Portal relationship để trống khi chưa có published object được xác minh. G15 rollout disabled.
