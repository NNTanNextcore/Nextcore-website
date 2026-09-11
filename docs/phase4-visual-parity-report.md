# Phase 4 — Visual parity report

Ngày 11/09/2026. Nguồn chuẩn: preview/index.html và preview/index-light.html, không sửa hai file này.

## Phương pháp

Render PHP thật qua WordPress core + ACF/TranslatePress local bằng harness chỉ đọc. Browser Chrome headless 152, chiều cao viewport 900px, rộng 1440/1200/1024/768/480/375px. Mỗi width test cả dark/light; dùng reduced-motion để ổn định ảnh/video poster khi so geometry. Video playback/mode changes kiểm tra riêng trong interaction suite.

Kết quả 12/12: không document overflow, không ảnh hỏng, không lỗi JS. Width/height của 11 section so với source preview cùng mode/viewport đều lệch ≤2px. Đây là **geometry parity**, không phải pixel-diff hay chứng nhận mọi glyph/pixel tuyệt đối giống nhau.

## Theo section

| Section | Dark | Light | Responsive | Known differences |
|---|---|---|---|---|
| Header | Đạt structural/visual QA | Đạt | Mobile dưới 1200px | 7 mục desktop, submenu thật, language/mode/search; khác header preview cũ theo yêu cầu |
| Hero | Đúng hero-corporate-v2, crop/font/line breaks | Đúng hero-corporate-light | Đạt 6 widths | Contact CTA dùng permalink thật |
| About Video | Đúng nextcore-danang và danang-poster | Đúng caurongquay-light-video và cauronglight | Đạt crop/text, giữ 3 đoạn | Poster là img chọn theo mode thay CSS background; visual giữ như preview |
| Stats | 35+/25+/20+ | Cùng dữ liệu | Đạt | Không đếm số giả |
| Services | Dark photo cards | White content variant, không gradient trắng che ảnh | 3/2/1 đạt | Resolve Page/term link thật |
| Technology | Đúng 8 marks/order | Đúng light marks | Marquee/reduced wrap đạt | Không duplicate text; clone marquee chỉ aria-hidden |
| Projects | Đúng 3 visual cards | Đúng | Đạt | Olympia/Affiliate links thật; Portal không link/không nút giả trong khi chờ relationship |
| Strategic Partner | GM logo/copy/background | Đúng | Đạt | Link GM thật thay modal |
| Testimonials | Đúng 4 cards | Đúng | 3/2/1, controls đạt | Position relative trên card để nhãn screen-reader không gây document overflow |
| Team | Ảnh thật, card trắng, 2 tam giác đỏ | Cùng card | 3/2/1 đạt | Email/phone links thật thay disabled buttons |
| Blog | Giữ card geometry | Light không overlay trắng | Đạt | 3 Posts published thật, ảnh/title/category/excerpt khác editorial mock theo yêu cầu |
| CTA | cta.png | cta-light.png | Đạt | Link Contact Page thay dialog |
| Footer | 5 vùng và copyright cuối | Đúng màu light | Mobile 1 cột, contact links đạt | Menus thật khi assign; development fallback. Facebook/TikTok profiles thay 3 fake social buttons |

Đã xem ảnh viewport trực tiếp cho Hero, About, Team và footer mobile. Full screenshots cho cả 12 cases lưu trong checks/phase4; các ảnh detail bổ sung hỗ trợ review từng vùng.

## Evidence

- [Responsive measurements + reference](checks/phase4/browser-results.json)
- [30 interaction checks](checks/phase4/interaction-results.json)
- [No-JS 4 cases](checks/phase4/noscript-results.json)
- [Light 1440 full](checks/phase4/light-1440.png), [Dark 1440 full](checks/phase4/dark-1440.png)
- [Light 375 full](checks/phase4/light-375.png), [Dark 375 full](checks/phase4/dark-375.png)
- [About light](checks/phase4/detail-light-about.png), [Team dark](checks/phase4/detail-dark-team.png), [Footer mobile](checks/phase4/detail-light-footer-375.png)

## Giới hạn

Không tuyên bố pixel-perfect, full EN editorial translation hoặc production readiness. Chưa QA các Elementor legacy pages/cross-browser/production cache-CSP. No-JS cho menu normal-flow để vẫn truy cập nội dung, không giữ fixed overlay header như khi JS hoạt động. Menu QA là hierarchy trong RAM; menu production chưa assign.

