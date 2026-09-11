# Phase 3 — Open questions

Ngày 10/09/2026. Không có câu hỏi chặn việc tạo skeleton. Không hỏi lại các quyết định đã khóa trong yêu cầu Phase 3.

## P3-Q1 — Production runtime và tối ưu HTML

**Question:** Production đang dùng PHP phiên bản nào, bộ plugin/version chuẩn nào và cơ chế cache/minify/CSP nào?

**Why it matters:** Cần đối chiếu tương thích trước release; no-flash đòi hỏi initializer chạy trước stylesheet/paint và được CSP cho phép.

**What you found:** Local PHP CLI 7.4.33 đã lint đạt. Chưa xác minh production runtime hoặc cách optimizer/CSP xử lý inline script. Local được duyệt làm development environment, không phải chứng nhận production.

**Options:** Cung cấp thông số/baseline production hoặc staging đã đồng bộ; hoặc giữ validation ở local và chưa xác nhận release readiness.

**Recommended option:** Cung cấp baseline trước QA release, giữ skeleton hiện tại ở trạng thái chưa activate. Không cần credentials trong chat. Không tự thay hosting, plugin hoặc CSP khi chưa có thông tin.

Snapshot production mới là điều kiện reconcile đã khóa trước release, không phải lựa chọn cần duyệt lại trong Phase 3. Các chức năng chưa làm nằm trong TODO Phase 4, không được coi là câu hỏi thiết kế.

