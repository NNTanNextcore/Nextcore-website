# Light/Dark architecture — một Nextcore Theme

Ngày 10/09/2026. Đặc tả đã dựa trên lựa chọn Phase 2, **chưa tạo CSS/JS**. Một DOM cho content, một theme, root `data-theme="light|dark"`. Không duplicate homepage markup, không tách hai themes hay hai bộ ACF text.

## 1. Preference và khởi tạo trước paint

Thứ tự resolve khóa:

1. Đọc localStorage key dự kiến **`nextcore-theme-mode`**; chỉ chấp nhận `light` hoặc `dark`.
2. Không có giá trị hợp lệ: dùng `prefers-color-scheme: dark`, còn lại light.
3. Đặt root data-theme và color-scheme trước stylesheet/render body; phản ánh active mode vào meta theme-color.

Một initializer rất nhỏ, first-party, chạy đồng bộ sớm trong head (không defer/async); source được quản lý tại assets/js/theme-init.js nhưng output inline ở vị trí head đã kiểm soát, trước CSS của theme. Không có nội dung kinh doanh/secret trong script. Code giao diện còn lại deferred. Không dựa vào PHP đoán localStorage, không nhúng dark src mặc định rồi chờ JS sửa.

Storage read/write bọc exception handling: trình duyệt có thể chặn storage; vẫn đổi mode trong memory cho lượt xem hiện tại. Dữ liệu không hợp lệ bỏ qua, không ghi đè tùy chọn người dùng cho đến khi họ click. localStorage có phạm vi origin nên VI root và `/en/` cùng origin dùng chung override; localhost và production không chia sẻ preference. [MDN localStorage](https://developer.mozilla.org/en-US/docs/Web/API/Window/localStorage).

System change cập nhật khi chưa có override; sau khi user chọn, system change không lấn quyền user. storage event đồng bộ tab khác; xóa key ngoài tab đưa về system mode. Không thêm lựa chọn UI thứ ba “System” trong rollout này vì chỉ yêu cầu switch Light/Dark; reset control có thể bổ sung sau nếu cần.

Không giấu toàn document để chống flash. Tokens có fallback system qua CSS khi JS không chạy. Nếu CSP hiện có cấm inline, cần nonce/hash hoặc first-party blocking script được policy cho phép; **không hứa no-flash nếu optimizer di chuyển/defer initializer**. CSP/cache/minify production cần xác minh Q3 và QA trên HTML response thực tế.

## 2. CSS tokens và ranh giới style

Tách tokens.css, base/layout/component CSS; root mode scoped rõ. Không load light.css hiện tại với `:root` overrides vô điều kiện vì sẽ ghi đè dark mode. Refactor chỉ thay cách chọn màu, không thay hình học/crop/typography đã duyệt.

| Token đề xuất | Dark | Light |
|---|---|---|
| --bg | #070b0d | #ffffff |
| --surface | #101618 | #f5f5f5 |
| --text | #f4f5f5 | #151515 |
| --muted | #b2b9bc | #5a5a5a |
| --border | rgba(255,255,255,.1) | #e8e8e8 |
| --accent | #df0000 | #df0000 |
| --accent-text | #ff4949 | #ce0010 |
| --brand-title | #ef272d | #ef272d |
| --focus | #ffffff | #b8000d |
| --header-surface | rgba(7,11,13,.95) | rgba(255,255,255,.96) |
| --footer-surface | #070b0d | #f7f7f7 |
| --testimonial-section | #080b0d | #fafafa |
| --testimonial-surface | #151719 | #ffffff |
| --testimonial-border | rgba(255,255,255,.12) | #e7e7e7 |
| --team-surface | #ffffff | #ffffff |
| --team-name | #101a3b | #101a3b |
| --team-role | #45516d | #45516d |
| --service-surface | #10171c | #ffffff |
| --technology-surface | #070b0d | #fafafa |
| --technology-ink | light ink theo preview | #202020 cho marks quy định |
| --video-text | #f4f5f5 | #f4f5f5 |

Giữ component accents nhỏ từ preview, không gộp tất cả sắc đỏ nếu làm sai thiết kế. Hero-overlay, service-overlay, CTA-overlay, partner-overlay, card-shadow là token component chứa gradient/shadow đã duyệt ở từng breakpoint. Dịch vụ light ảnh 166px và không gradient trắng; đây là mode-specific geometry đã có sẵn, không ép cùng crop dark. Icon currentColor theo control; brand technology colors giữ riêng. Team luôn trắng, video copy luôn trắng; không filter invert ảnh hoặc nội dung builder.

Hero/CTA/media URLs lấy G13, không chứa URL như business field trùng ở tokens. Browser chỉ được đưa active URL vào CSS background thực sự dùng. Mode root áp site shell/native templates; **legacy Elementor content giữ màu tự tác giả đặt và được scope**, không auto-invert widget CSS. Mức dark styling legacy chưa xác định là Q6.

## 3. Switcher state, placement và accessibility

- Desktop control ở header-actions, mobile control trong menu mobile như đã duyệt. Có thể có hai control placements responsive nhưng cùng một state controller, chỉ control ở viewport active focusable/hiển thị; không hai bản content DOM.
- Native button type=button, `aria-pressed=true` nghĩa **dark đang bật**, false nghĩa light. Accessible name cố định đã dịch “Chế độ tối” / “Dark mode”; không thay tên cùng lúc đổi nghĩa pressed.
- Icon mặt trời/mặt trăng decorative aria-hidden; state thể hiện bằng ARIA và hình thức, không chỉ màu.
- Space/Enter theo hành vi native; focus ring rõ; click đổi mode không đóng menu bất ngờ hoặc làm mất focus. Touch target mục tiêu ≥44px, không dùng icon nhỏ làm target duy nhất.
- Đồng bộ pressed state tất cả placements sau init/switch/storage/system event. Không cần live announcement dài gây đọc hai lần với aria-pressed.
- Không dùng ARIA role menu cho navigation links thông thường. Menu toggle riêng aria-expanded/aria-controls; đóng bằng Escape và trả focus.
- Không đổi active language khi đổi mode; language switch full page giữ override qua storage.

## 4. Media manifest và initial loading

G13 giữ 8 media refs: hero image pair, video pair, poster pair, CTA image pair. HTML chứa URLs hai modes ở inert data/config attributes đã escape, **không đưa hai videos vào src/source tags**. Một img hero, một video About, một img CTA. Metadata URL không làm browser tự tải.

Initial HTML không gán dark URL thật vào video/img trước khi biết mode. Image elements có reserved width/height/aspect ratio và placeholder trong suốt; active mode được initializer resolve trước khi media loader gán src/srcset. Một bootstrap media nhỏ tại lúc node xuất hiện có thể dùng state sớm, không chờ main.js lớn. Khi chưa decode xong, chỉ hiện nền/overlay đúng mode, không ảnh mode sai; text và layout vẫn hiện.

Hero load priority high: tạo preload cho **ảnh active duy nhất** từ mode đã resolve, dùng đúng URL/srcset/sizes như img để tránh tải hai bản. Không server-preload dark vô điều kiện. CTA lazy load khi gần viewport; src/source active duy nhất.

About video: initial không src thật và không autoplay attribute gây chạy ngoài lifecycle; JS chọn active source khi section sắp/đang vào view và user không reduced-motion. Poster active hiện trước. Khi cấp src dùng muted/playsinline/loop, preload metadata hoặc auto theo nhu cầu play **chỉ cho active mode**. Không tạo hidden second video hay prefetch inactive MP4. Lazy section means video có thể chưa tải dù mode đã chọn; đây không phải lỗi thiếu source.

No-JS: system-scoped CSS hiển thị ảnh hero/CTA/poster đúng hệ điều hành như background trên cùng section; video không autoplay, switch button không hoạt động phải ẩn bằng progressive enhancement. Không duplicate homepage trong noscript. Stored JS override không thể đọc khi JS tắt; system fallback là giới hạn được ghi rõ.

## 5. Chuyển mode không lóe media cũ

State machine cho video: `poster → loading → playing`, kèm `paused/error`; mỗi mode change tăng **generation token**. Mode và playback state tách nhau.

| Bước | Thao tác thiết kế |
|---|---|
| 1 | Đánh dấu generation mới, hủy callbacks/chuyển cảnh cũ; ẩn media surface cũ trước khi đổi tokens. Text không bị ẩn |
| 2 | Pause video, gỡ src/source cũ và reset load để bỏ frame cũ; xóa poster/background cũ trước khi state mới visible |
| 3 | Commit root mode, switch ARIA/meta-theme-color; đặt background neutral của mode mới trong lúc asset chưa sẵn sàng |
| 4 | Load/decode hero/CTA/poster **mode mới**; gán đúng URL và chỉ unmask image khi decode thành công cùng generation. Tài nguyên cached có thể sẵn ngay |
| 5 | Gán poster video và fallback CSS cùng active asset; nếu visible và không reduced-motion thì gán MP4 mới, play trong lifecycle |
| 6 | Chỉ reveal video khi generation/current source khớp và frame của video mới đã sẵn sàng, ưu tiên requestVideoFrameCallback nếu hỗ trợ; fallback playing/readyState + render tick |

Không dùng crossfade lẫn hai video. Sau switch có thể thấy poster mới hoặc nền neutral ngắn khi network chậm; **không được thấy poster/frame mode cũ**. No flash sai màu/ảnh không có nghĩa video hai file sẽ chuyển liền frame không buffer.

Nếu user switch rất nhanh, promise/event cũ không được mở visibility của media mới. Kiểm tra generation + source + playback condition trong mọi completion handler. Có thể còn network bytes in-flight của request đã bắt đầu trước click; không khởi tạo request mới cho mode inactive hoặc giữ nó playing sau switch.

Không preserve currentTime giữa hai video khác nhau khi chưa xác nhận cùng timeline; restart file mới từ đầu là hành vi kỹ thuật đề xuất để tránh seek ra ngoài duration. Nếu cần giữ vị trí camera xuyên mode thì phải có media chuẩn đồng bộ, hiện không có yêu cầu đó.

## 6. Playback lifecycle và lỗi

- In-view + visible tab + không reduced-motion: play active video. Ra khỏi viewport/tab hidden: pause, hiển thị poster **active**, giữ mode.
- `waiting` giữa playback/loop: giữ frame active, không remove visibility để lóe poster; quy tắc đã sửa trong preview.
- Media error/autoplay rejection: giữ poster active. Poster lỗi: nền neutral active + copy, không lấy poster dark làm fallback light.
- Reduced-motion: không tải/play video mới, dùng poster; dừng marquee/reveal như preview. Đổi mode vẫn đổi poster, không bỏ qua lựa chọn người dùng.
- Hero/CTA img alt được server-rendered và translation-safe; không JS ghi lại alt tiếng Việt sau khi TranslatePress đã dịch.

## 7. Cache, hiệu năng và kiểm thử phase triển khai

HTML cache chứa mode-neutral media data và initializer, không cache dữ liệu phụ thuộc override của một người cho mọi khách. Không tách cache theo mode nếu output chung; language vẫn theo TranslatePress URL/cache rules. Chỉ assets active tải; không duplicate font hoặc scripts theo mode.

Acceptance matrix: light/dark × VI/EN × system/no override/saved override/blocked storage; first paint cold cache/network throttled; desktop/mobile switch; tab sync; back/forward; rapid switch; video visible/offscreen/buffering/loop/error; reduced-motion; keyboard; normal/builder pages. Network log phải chứng minh không preload cả 2 MP4. Screenshot/filmstrip phải chứng minh không flash dark image khi active light. Chưa thực thi hoặc tạo script QA trong Phase 2.
