# Rollback

Phase 3 chỉ thêm source theme và reports; theme hiện tại, database, preview, plugins không đổi.

Chưa cần rollback activation vì chưa activation. Khi triển khai ở phase sau: chụp backup database/uploads/config theo quy trình staging được duyệt, lưu theme đang chạy và menu assignments, kiểm tra restore trước release. Không coi việc đổi lại theme là cách hoàn tác data migration.

