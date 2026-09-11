# Audit homepage preview đã duyệt

Ngày 10/09/2026. `preview/index.html` là **source of truth** về homepage; `preview/index-light.html` là biến thể light đã duyệt. Đây là audit, không redesign, không chỉnh HTML/CSS/JS.

## 1. Thứ tự section thực tế

| Thứ tự | ID / thành phần | Nội dung và cấu trúc cần giữ |
|---|---|---|
| 0 | Header `site-header` | Logo trái; 8 nav links; search; CTA; mobile toggle |
| 1 | Hero `home` | Ảnh full-bleed, eyebrow, tên công ty 2 dòng, Nextcore đỏ, slogan nhỏ dưới tên, description, 2 CTA, scroll cue |
| 2 | Video `about` | Cầu Rồng, lớp phủ tối, giới thiệu công ty, ngày thành lập, 3 đoạn nội dung, CTA tới team |
| 3 | Stats, không có ID | 35+ dự án, 25+ khách hàng, 20+ nhân viên, 3 SVG icons |
| 4 | `services` | 3 cards: Outsource CNTT, Tư vấn doanh nghiệp, Giải pháp cá nhân |
| 5 | `technology` | Heading và marquee 8 nhãn công nghệ, nút pause |
| 6 | `projects` | Portal, Top Olympia, WordPress Plugin - Affiliate, mock browser frames và modal detail |
| 7 | `partner` | GM Solutions, logo, mô tả, CTA và motto |
| 8 | `testimonials` | 4 review thật, carousel thủ công, prev/next, dots, modal “Xem thêm” |
| 9 | `team` | Hiền — CEO; Anh — CTO; Tú — PM; ảnh thật tròn, card trắng, hai tam giác đỏ |
| 10 | `blog` | 3 cards minh họa editorial; chưa phải query Posts thật |
| 11 | CTA `contact` | Ảnh núi, heading, mô tả, nút mở contact dialog, motto góc ảnh |
| 12 | Footer | Brand, Về Nextcore, Hỗ trợ, Liên hệ, Kết nối; copyright cuối |

Testimonial dialog nằm trong main giữa testimonials và team nhưng không phải section. Ngoài main còn detail/contact/search dialogs và noscript note. Không đổi thứ tự Technology/Projects hoặc chuyển video về vị trí khác theo tài liệu spec cũ. Spec là tài liệu tham khảo thứ cấp khi khác HTML đã duyệt.

## 2. Header, hero, footer

Header fixed, desktop cao 82px, tablet 74px, mobile 68px. Dark ban đầu trong suốt, khi scrollY > 24 hoặc mở menu chuyển nền tối có blur. Light dùng nền trắng rgba(.96) ngay từ đầu và khi scroll/menu. Menu collapse dưới 1024px, 2 cột trên tablet, 1 cột dưới 640px. Mobile ẩn CTA header.

Nav hiện là anchor: about, services, projects (Sản phẩm và Dự án cùng đích), technology, blog, team, contact. Đây là hành vi preview, chưa có WordPress menu data hoặc language/theme switcher. Cần giữ hình học header; vị trí hai switcher mới phải duyệt, không tự chèn phá bố cục.

Hero dùng Be Vietnam Pro **700 local** chỉ cho company-title; body còn Arial/Helvetica. Font-size desktop clamp(44px, 4.5vw, 68px), line-height 1.18, letter-spacing -.02em; mobile line-height 1.22. Hai company-line là block và nowrap, với ngắt dòng thực tế “Công ty cổ phần Phần” / “mềm Nextcore”. Không tự sửa capitalization/ngắt dòng hay áp font mới toàn trang.

Hero/hero-inner min-height 100svh, copy max-width 850px, ảnh object-fit cover và object-position theo breakpoint. Có hai CTA và slogan dưới title; không dùng slogan làm heading chính.

Footer có 5 cột desktop; 640–1199px thành 3 cột, contact span 2; dưới 640px xếp 1 cột. Copyright span toàn grid, nằm cuối. Contact: 63 Phan Đăng Lưu Street, Hai Chau, Da Nang 550000, Viet Nam; `mailto:info@nextcore.vn`; `tel:+84378962625`. Social buttons hiện mở placeholder modal; không phải links chính thức đã hoàn thiện.

## 3. Media và asset dependencies

| Phần | Dark | Light |
|---|---|---|
| Hero | `hero-corporate-v2.png` | `hero-corporate-light.png` |
| Video | `nextcore-danang.mp4` | `caurongquay-light-video.mp4` |
| Video poster + CSS fallback | `danang-poster.jpg` | **`cauronglight.png` do người dùng cung cấp** |
| CTA | `cta.png` | `cta-light.png` |

Các tên ảnh ở bảng thuộc `preview/assets/images/`; video thuộc `preview/assets/video/`. Không thay poster light bằng ảnh trích video cũ `danang-light-poster.jpg`.

Assets dùng chung:

- Brand: nextcore-logo.png, nextcore-icon.png.
- Services: service-outsource.png, service-consulting.png, service-personal.png.
- Projects: project-portal.png, project-olympia.png, project-affiliate.png.
- Partner: gm-solutions.png và nền partner-waves.svg.
- Testimonials: testimonial-1.png, testimonial-2.webp, testimonial-3.jpg, testimonial-4.png.
- Team: team-hien.jpg, team-anh.jpg, team-tu.png.
- Blog minh họa: about.png, hero.png, cta.png; light cũng dùng các ảnh blog này, không tự đổi ảnh blog thứ ba theo CTA light.
- Font: `assets/fonts/BeVietnamPro-Bold.ttf` (140.300 bytes), giấy phép BeVietnamPro-OFL.txt. Inline SVG symbol library và SVG contact; technology marks gồm chữ/Unicode/CSS, không có icon library CDN.

Kiểm tra đường dẫn src/href assets của hai HTML và url() của 7 CSS: **37 file tham chiếu duy nhất, không có file thiếu**, tổng khoảng 32,37 MB trên đĩa. Đây là tổng union hai theme modes và assets tham chiếu, không phải dung lượng tải lần đầu hay toàn thư mục assets. Hero mỗi bản khoảng 1,65 MB; video dark 4,72 MB, light 4,94 MB; poster light 2,80 MB.

Preload chỉ ảnh hero theo từng mode; hero fetchpriority=high. Ảnh thấp hơn dùng loading=lazy. Video preload=metadata, autoplay/muted/loop/playsinline, không controls. Không có npm build hay thư viện JS frontend ngoài. Không đem `preview/checks/`, script add-*.py hoặc Chrome profile vào ZIP theme.

## 4. CSS/JS structure và hành vi

CSS load order dark: main.css → layout.css → services.css → team.css → testimonials.css → footer.css. Light thêm **light.css sau team.css, trước testimonials.css/footer.css**; hai file sau có light overrides riêng.

| File | Trách nhiệm |
|---|---|
| main.css | Tokens, base, header, component cơ bản, grids, dialogs, marquee/reveal, responsive/reduced-motion |
| layout.css | Bố cục thoáng đã duyệt, min-heights, hero typography, video, stats/technology thấp hơn, eyebrow |
| services.css | Photo cards, icon, micro text, hover/focus, responsive 3/2/1 |
| team.css | Cards trắng, ảnh tròn, tam giác đỏ, contacts, grid 3/2/1 |
| testimonials.css | Carousel native scrolling, cards 3/2/1, clamp 4 dòng, dots, modal và light overrides |
| footer.css | Grid/contact/copyright và responsive footer mới |
| light.css | Tokens light, nền/text/overlay/card overrides, video fallback riêng |
| main.js | Menu, header scroll state, active nav, reveal, video lifecycle, marquee, dialogs, search nội bộ section |
| testimonials.js | Prev/next, scroll position/dots, ResizeObserver, swipe native và modal đọc đủ nội dung |

`main.js` dùng IntersectionObserver cho nav/reveal/video, matchMedia reduced-motion và visibilitychange. Video pause/ẩn khi offscreen, tab hidden hoặc reduced-motion; hiện khi playing; error/pause quay về poster. **waiting không ẩn video**, giữ frame để tránh lóe poster ở điểm lặp. Theme switch tương lai phải đổi đồng bộ video source, poster và CSS background.

Reveal opacity + translateY(14px), .6s ease, chạy một lần theo IntersectionObserver; hover cards/images/buttons dùng transform/shadow. Marquee linear infinite 34s desktop, 48s mobile, nhân đôi list aria-hidden; có pause. Testimonials **không autoplay**, snap scrolling, nút đầu/cuối disabled, dots cập nhật theo scroll, modal dùng textContent của HTML thật. Reduced-motion tắt animation/transition và đổi sang poster/static technology list.

Search hiện chỉ normalize tiếng Việt và tìm text trong các section trang hiện tại. Contact/detail/social/blog dialogs còn nội dung minh họa; không gửi form hoặc gọi API. Không được gọi đây là search WordPress/form production hoàn chỉnh.

`main.js` chưa guard tất cả querySelector theo page type (menu/marquee/dialog/search). Khi tách template, không enqueue nguyên script này trên mọi page rồi giả định các node tồn tại. Chuỗi detail nằm trong JS cần đưa vào nguồn render có thể dịch ở phase triển khai.

## 5. Responsive behavior

- Container desktop tối đa 1280px; breakpoint điều chỉnh ở 1200px, 1023px, 639px; mobile container chừa 20px mỗi bên.
- Section chung min-height **92svh**, spacing clamp(72px, 9vh, 120px), không scroll snap toàn trang. Tablet spacing 80px, mobile 72px; nhiều section mobile bỏ minimum để nội dung tự tăng.
- Video khoảng 85svh desktop; mobile min-height max(600px, 90svh), copy sát phần dưới, không crop mất nội dung chữ.
- Stats/technology khoảng **45svh** desktop, mobile auto/padding 44px. Stats 3 item; không đổi thành counter số giả.
- Services, team, testimonials: 3 desktop / 2 tablet / 1 mobile. Cards không co chữ theo cách phá dấu tiếng Việt.
- Projects/blog: 3 desktop; mobile 1 dưới 640px. Không giả định mọi grid tablet đều 2 cột.
- Partner chuyển hàng/cột theo breakpoint; CTA mobile khoảng 80svh, motto ẩn theo CSS; light CTA motto không có ô nền trắng.
- Footer responsive như mục 2; cần QA trực quan riêng trong migration.

## 6. Light/Dark audit

| Token / component | Dark hiện tại | Light hiện tại |
|---|---|---|
| Background `--bg` | #070b0d | #ffffff |
| Panel `--panel` | #101618 | #f5f5f5 |
| Text `--text` | #f4f5f5 | #151515 |
| Muted `--muted` | #b2b9bc | #5a5a5a |
| Border `--border` | rgba(255,255,255,.1) | #e8e8e8 |
| Accent `--red` | #df0000 | kế thừa #df0000 |
| Accent text `--red-text` | #ff4949 | #ce0010 |
| Hero brand name | #ef272d | #ef272d |
| Icons | currentColor, line icons đỏ #ef151b và component đỏ riêng | currentColor tối cho control; giữ đỏ/brand marks |
| Header | Transparent → rgba(7,11,13,.95) khi scroll/menu | rgba(255,255,255,.96), border/shadow sáng |
| Footer | Nền kế thừa dark; contact #bfc5ca | #f7f7f7; contact #555 |
| Testimonial section/card | #080b0d / #151719; border trắng .12, text trắng/xám | #fafafa / #fff; border #e7e7e7, text #111/#555 |
| Team card | **Trắng**, name #101a3b, role #45516d, ảnh thật và tam giác đỏ | **Giữ trắng và cùng màu chữ**, section #fafafa |
| Services | Card #10171c, ảnh full-height với dark gradient | Card trắng, ảnh cao 166px, không overlay trắng, text tối |
| Projects | Card/screen mockup nền tối với màu từng dự án | Card trắng, border/shadow sáng, text tối; ảnh giữ nguyên |
| Technology | Nền dark, marquee masks, màu brand | #fafafa; node/aws/figma/cicd đổi ink #202020; giữ brand colors |
| Partner | Nền waves tối | Waves dưới lớp trắng .7; motto #5a5a5a |
| Blog cards | Gradient tối đỡ chữ trên ảnh | Không gradient trắng; vùng chữ nền trắng |
| CTA | Ảnh núi tối, chữ sáng và overlay tối | Ảnh ban ngày, title #151515/body #353f48, overlay sáng; motto trắng có text-shadow, nền trong suốt |
| Dragon Bridge | Media dark, poster dark, chữ trắng, overlay tối | Media light, cauronglight.png; **vẫn chữ trắng và overlay tối** |

Hiện light.css khai báo `:root` không scope mode và HTML có body.theme-light; hai file HTML là hai entrypoints. Chưa có theme switcher, persistence hoặc prefers-color-scheme routing.

### Kiến trúc đề xuất, chưa code

Một theme **Nextcore Theme**, một DOM/template set. Đưa color-scheme và token groups vào root mode `data-theme="light|dark"`; tách token nền, text, border, accent, focus và token component như testimonial-surface, team-surface, hero-overlay, cta-overlay, video-poster. Các màu được duyệt giữ nguyên; geometry/spacing/fonts/animation dùng chung. Team white cards là token cố định có chủ đích, không đảo tự động theo mode.

Ảnh hero/CTA/video là media configuration theo mode, không thể thay bằng mỗi đổi màu CSS. Switch cập nhật media và poster đồng bộ, chỉ tải video mode cần dùng; bảo toàn pause/reduced-motion state. Không preload cả hai video/hero vô điều kiện. Mode khởi tạo cần được xác định trước paint để tránh flash; thứ tự preference và vị trí nút chờ duyệt ở open questions. Không tạo hai themes hoặc nhân đôi toàn bộ nội dung theo mode.

## 7. Những phần phải pixel-close và giới hạn QA

Giữ section order, heights/spacing, hero font/ngắt dòng/slogan, crop/overlay ảnh đã duyệt, 3 services, stats 35/25/20, project browser frames, marquee, partner, vị trí testimonials ngay dưới partner, ảnh/team cards/tam giác, CTA và footer/contact/copyright. Giữ focus/keyboard/reduced-motion, không redesign thành page builder layout chung.

Reports có sẵn trong preview/checks là bằng chứng lịch sử, không phải QA mới trong Phase 1:

- hero-font-report.json ghi font loaded/700/fits ở 1440,1200,1024,768,480,375 cho cả light/dark.
- light-video-report.json xác minh playing/buffering/loop ở 1440/375 nhưng vẫn ghi poster cũ danang-light-poster.jpg; **không dùng report đó để chứng minh đã QA trực quan poster cauronglight.png**.
- Footer mới từng kiểm tra cấu trúc/link; chưa có bằng chứng QA trực quan đầy đủ các breakpoints.
- Không chạy lại add-*.py, không tạo screenshot/check script mới trong Phase 1. Kiểm tra asset existence và đọc CSS không tương đương kiểm tra browser toàn trang.

Các placeholder và lựa chọn behavior trước migration nằm trong [open-questions.md](open-questions.md).
