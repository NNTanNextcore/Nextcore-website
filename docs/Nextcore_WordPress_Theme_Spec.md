# Nextcore WordPress Theme Specification

## 1. Mục tiêu

Xây dựng một **WordPress theme hoàn chỉnh, độc lập** cho website Nextcore, thay thế hướng phụ thuộc vào Flatsome/Elementor trên homepage mới.

Theme phải đạt các mục tiêu:

- Giao diện corporate cao cấp, hiện đại, tối màu.
- Màu đỏ thương hiệu Nextcore là màu nhấn chính.
- Homepage cô đọng nội dung, ưu tiên visual lớn, tránh nhiều chữ.
- Dễ chỉnh sửa nội dung trong WordPress Admin, không phải sửa code cho các thay đổi content thông thường.
- Blog/tin tức tiếp tục dùng hệ thống Post chuẩn của WordPress.
- Responsive tốt trên desktop, tablet, mobile.
- Animation nhẹ, không làm website nặng hoặc rối mắt.
- Có thể đóng gói thành `nextcore-theme.zip` và cài từ **Appearance > Themes > Add New > Upload Theme**.

---

## 2. Design reference đã duyệt

Mockup homepage đã được duyệt nằm tại:

`./preview-homepage-approved.png`

Codex phải coi ảnh này là **visual target chính**, không cần pixel-perfect tuyệt đối nhưng phải giữ đúng:

- cảm giác dark premium;
- khoảng trắng thoáng;
- typography lớn, ít chữ;
- red accent;
- section ảnh lớn;
- project cards rõ ràng;
- bố cục corporate, không quá nhiều animation.

### Brand direction

- Primary background: gần đen, ví dụ `#07090B`, `#0B0D10`.
- Primary text: trắng / off-white.
- Accent: đỏ Nextcore, lấy trực tiếp từ logo thật nếu có thể.
- Border: trắng/đỏ opacity thấp.
- Không dùng gradient neon quá mạnh.
- Không biến website thành phong cách gaming hoặc AI startup.

---

## 3. Nội dung đã chốt

### Hero headline

**BUILD TRUST, CREATE VALUE**

Hero phải là visual lớn, ảnh corporate/office chiếm nhiều diện tích, nội dung ngắn gọn.

### About

Dùng ảnh corporate/office lớn.

### Services

4 card ngang trên desktop:

1. Phát triển phần mềm
2. Tư vấn công nghệ
3. Thiết kế UI/UX
4. Bảo trì & nâng cấp

### Featured Projects

3 project ngang bằng nhau:

1. Nextcore Portal
2. Trường Doanh nhân Top Olympia
3. WordPress Plugin - Affiliate

### Technology

Logo công nghệ chạy marquee chậm.

### Strategic Partner

GM Solutions trên nền abstract tối, logo lớn.

### Team

3 người hiện tại:

- Nguyễn Văn Hiền
- Trần Đức Anh
- Phan Thanh Tú

### CTA cuối trang

Ảnh landscape/corporate lớn, tối màu, CTA rõ ràng.

---

## 4. Nguyên tắc content

Homepage phải **cô đọng**.

Quy tắc:

- Mỗi section chỉ có 1 thông điệp chính.
- Heading khoảng 4-8 từ khi có thể.
- Mô tả 1-2 dòng.
- Service card: tên + 1 câu ngắn.
- Project card: tên + 1 câu + tag nếu cần.
- Team: tên + chức danh.
- Blog card: ngày + title, excerpt là optional.
- Không chèn các đoạn giới thiệu dài như brochure.

---

## 5. Homepage sections

### 5.1 Header

Yêu cầu:

- Sticky header.
- Transparent khi ở top.
- Khi scroll: dark background + blur nhẹ.
- Logo trái.
- Menu giữa/phải.
- CTA `Liên hệ ngay`.
- Mobile dùng hamburger.
- Menu phải dùng **WordPress Menu API**.

Editable:

- Menu qua WordPress Menus.
- CTA label + URL qua Theme Options / ACF Options Page.

---

### 5.2 Hero

Content gợi ý:

- Eyebrow: `SOFTWARE DEVELOPMENT COMPANY`
- Headline: `BUILD TRUST, CREATE VALUE`
- Subtext ngắn: giải pháp phần mềm cho doanh nghiệp hiện đại.
- CTA chính: `Liên hệ ngay`
- CTA phụ: có thể là `Xem năng lực` hoặc `Xem video` nếu sau này có video thật.

Visual:

- Ảnh corporate/office lớn.
- Không dùng stock có rủi ro bản quyền.
- Ưu tiên asset do OpenAI tạo hoặc asset Nextcore sở hữu.

Editable fields:

- eyebrow
- title
- description
- primary_cta_text
- primary_cta_url
- secondary_cta_text
- secondary_cta_url
- hero_image

Animation:

- Fade in nhẹ.
- Không parallax mạnh.

---

### 5.3 Company Stats

Dạng 4 cột desktop, 2x2 mobile.

Field cấu trúc:

- number
- label

Không hard-code số liệu nếu chưa được xác nhận.

---

### 5.4 About Nextcore

2 cột:

- text ngắn bên trái;
- ảnh corporate/office lớn bên phải.

Fields:

- eyebrow
- title
- description
- cta_text
- cta_url
- image

---

### 5.5 Services

Desktop: 4 cards ngang.

Mỗi service:

- icon
- title
- description ngắn
- link

Hover:

- border/red glow nhẹ;
- transform nhỏ, không quá nổi.

Data nên editable bằng ACF Repeater hoặc CPT nếu ACF Pro không khả dụng.

---

### 5.6 Featured Projects

3 card ngang bằng nhau.

Projects:

1. Nextcore Portal
2. Trường Doanh nhân Top Olympia
3. WordPress Plugin - Affiliate

Mỗi card:

- image
- title
- short_description
- tags optional
- url

Nên tạo **Custom Post Type `project`** để về sau dễ thêm project mới.

Homepage query 3 project được đánh dấu Featured.

---

### 5.7 Technology

- Heading ngắn.
- Danh sách logo công nghệ.
- Marquee chạy chậm, seamless.
- Pause hoặc giảm motion khi `prefers-reduced-motion`.

Data:

- logo
- name
- optional url

Không hard-code logo nếu có thể.

---

### 5.8 Strategic Partner

Current partner:

**GM Solutions**

Visual:

- nền abstract dark/red;
- logo GM Solutions lớn;
- text ngắn.

Fields:

- partner_name
- partner_logo
- partner_description
- partner_link
- background_image optional

---

### 5.9 Team

3 card hiện tại.

Mỗi member:

- photo
- name
- role
- linkedin_url optional

Nên dùng ACF repeater hoặc CPT `team_member`.

---

### 5.10 Blog / Insights

Không nhập tay homepage.

Query 3 bài mới nhất từ WordPress Posts.

Mỗi card:

- featured image
- date
- title
- permalink

Optional: excerpt 1 dòng.

---

### 5.11 Final CTA

Full-width visual.

Content ngắn:

- `Sẵn sàng bắt đầu dự án của bạn?`
- 1 dòng mô tả.
- Button `Liên hệ ngay`.

Fields:

- title
- description
- button_text
- button_url
- background_image

Visual phải dùng asset an toàn bản quyền / asset tự tạo.

---

### 5.12 Footer

4 nhóm chính:

- Brand / description
- Về Nextcore
- Hỗ trợ
- Kết nối

Phải dùng WordPress Menu API cho footer navigation.

Editable:

- company description
- social links
- copyright

---

## 6. WordPress architecture

Target structure:

```text
nextcore-theme/
├── style.css
├── functions.php
├── screenshot.png
├── front-page.php
├── index.php
├── page.php
├── single.php
├── archive.php
├── 404.php
├── header.php
├── footer.php
├── inc/
│   ├── theme-setup.php
│   ├── enqueue.php
│   ├── acf-fields.php
│   ├── custom-post-types.php
│   ├── theme-options.php
│   └── helpers.php
├── template-parts/
│   ├── home/
│   │   ├── hero.php
│   │   ├── stats.php
│   │   ├── about.php
│   │   ├── services.php
│   │   ├── projects.php
│   │   ├── technology.php
│   │   ├── partner.php
│   │   ├── team.php
│   │   ├── insights.php
│   │   └── cta.php
│   └── content/
│       ├── card-project.php
│       ├── card-post.php
│       └── card-team.php
└── assets/
    ├── css/
    │   ├── main.css
    │   ├── responsive.css
    │   └── editor.css
    ├── js/
    │   ├── main.js
    │   └── animations.js
    └── images/
```

Theme phải độc lập, không phụ thuộc Flatsome để render homepage.

---

## 7. ACF / Editable requirements

Mục tiêu: người quản trị **không cần sửa code** để thay content thường ngày.

Dùng ACF cho:

- Hero
- Stats
- About
- Services
- Technology
- Partner
- CTA
- Theme Options

Projects nên dùng CPT `project`.

Blog dùng Posts mặc định.

Team có thể dùng CPT `team_member` nếu muốn mở rộng lâu dài.

### Quan trọng

Nếu site chỉ có ACF Free và không có Repeater:

- không được silently phụ thuộc ACF Pro;
- Codex phải kiểm tra khả năng plugin;
- nếu thiếu Repeater, ưu tiên CPT hoặc field group đơn giản thay vì phá frontend.

---

## 8. Asset policy / bản quyền

Ưu tiên tuyệt đối:

1. Asset Nextcore tự sở hữu.
2. Logo/ảnh team do Nextcore cung cấp.
3. Hình do OpenAI tạo riêng cho dự án.
4. Icon open-source với license rõ ràng.

Không lấy ngẫu nhiên ảnh Google/Pinterest/web để đưa vào production.

Các background cần thiết nên tạo mới:

- Hero corporate office
- About corporate office
- Partner abstract red/dark
- CTA landscape/corporate
- Các visual phụ nếu cần

---

## 9. Animation

Direction đã duyệt: **Corporate cao cấp, ít animation**.

Cho phép:

- Fade in up nhẹ.
- Header blur on scroll.
- Hover card nhẹ.
- Tech marquee chậm.
- Button hover.

Không ưu tiên:

- Heavy 3D.
- Scroll pin phức tạp.
- Cursor effects.
- Aggressive parallax.
- Full-screen WebGL.

Nếu dùng GSAP:

- chỉ dùng cho reveal / marquee enhancement nếu cần;
- phải có graceful fallback;
- tôn trọng `prefers-reduced-motion`.

---

## 10. Responsive requirements

### Desktop

- max-width khoảng 1280-1400px.
- nhiều khoảng trắng.
- hero 2 cột.
- services 4 cột.
- projects 3 cột.
- team 3 cột.

### Tablet

- hero có thể 2 cột hoặc stack tùy breakpoint.
- services 2x2.
- projects 2 + 1 hoặc stack.

### Mobile

- ưu tiên readability.
- giảm motion.
- project cards stack.
- tech marquee chậm hơn hoặc static nếu cần.
- CTA full width.
- mobile menu dễ thao tác.

---

## 11. Performance

Mục tiêu:

- tránh JS nặng không cần thiết;
- lazy-load ảnh dưới fold;
- dùng WebP/AVIF khi phù hợp;
- preload hero image hợp lý;
- không load thư viện animation nếu section không cần;
- tránh layout shift;
- responsive image `srcset`;
- tối ưu Core Web Vitals.

---

## 12. Accessibility

- contrast đủ mạnh;
- focus visible;
- menu keyboard usable;
- semantic heading hierarchy;
- alt text editable;
- button/link phân biệt đúng ngữ nghĩa;
- marquee không gây khó chịu với reduced motion.

---

## 13. SEO

- giữ compatibility với Yoast SEO đang có.
- không hard-code meta title/description.
- heading hierarchy chuẩn.
- schema nếu có thì tránh conflict với Yoast.
- blog posts dùng template semantic.

---

## 14. Migration / compatibility

Website hiện tại đang sử dụng Flatsome Child và có Elementor/ACF/Yoast/WooCommerce trong admin.

Theme mới phải:

- không xóa dữ liệu hiện tại;
- không sửa trực tiếp core/plugin files;
- được test trên staging trước khi activate live;
- giữ post/page/media hiện có;
- menu/location có fallback;
- kiểm tra các shortcode cũ trước khi migrate các page khác ngoài homepage.

Homepage mới có thể triển khai trước, sau đó migrate About/Services/Projects từng phase.

---

## 15. Coding standards

- WordPress Coding Standards.
- Escape output: `esc_html`, `esc_url`, `wp_kses_post`, etc.
- Sanitize admin values.
- Nonce + capability checks cho custom admin actions.
- Không hard-code site URL.
- Không hard-code upload paths.
- Dùng `wp_enqueue_style` / `wp_enqueue_script`.
- Không nhúng script trực tiếp không cần thiết trong template.
- Prefix function/class names bằng `nextcore_`.

---

## 16. Deliverables bắt buộc cho Codex

Codex phải tạo:

1. Full theme source folder `nextcore-theme/`.
2. `nextcore-theme.zip` cài được từ WordPress Admin.
3. `screenshot.png` cho Appearance > Themes.
4. ACF field registration hoặc field JSON/export rõ ràng.
5. CPT registration nếu dùng.
6. Homepage hoàn chỉnh theo mockup đã duyệt.
7. Responsive desktop/tablet/mobile.
8. README cài đặt.
9. Danh sách plugin dependency rõ ràng.
10. Hướng dẫn migrate homepage từ theme cũ sang theme mới.

---

## 17. Acceptance criteria

Theme chỉ được coi là hoàn tất khi:

- Upload zip vào WordPress được.
- Activate không gây fatal error.
- Homepage render đúng các section.
- Admin sửa được Hero, About, Services, Technology, Partner, Team/Project data, CTA.
- Blog posts tự cập nhật trên homepage.
- Menu lấy từ WordPress.
- Mobile usable.
- Không phụ thuộc stock image không rõ license.
- Giao diện giữ đúng tinh thần mockup `preview-homepage-approved.png`.
- Không có content dài gây rối mắt.

---

## 18. Codex execution order

Codex nên thực hiện theo thứ tự:

1. Audit cấu trúc WordPress/theme hiện tại.
2. Tạo theme skeleton.
3. Register theme support/menu/assets.
4. Register ACF fields/CPT.
5. Dựng header/footer.
6. Dựng homepage section theo thứ tự.
7. Implement blog query.
8. Implement responsive.
9. Implement animation nhẹ.
10. Performance/accessibility pass.
11. Build screenshot.png.
12. Package `nextcore-theme.zip`.
13. Test install/activate trên staging.

Không tự ý thay đổi design direction nếu chưa có yêu cầu mới.

