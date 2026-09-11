# Phase 5 — Local database write report

Ngày 11/09/2026. Tất cả ghi bên dưới chỉ ở database XAMPP local. Stored stylesheet vẫn `flatsome-child`; không thay active_plugins, home/siteurl, permalink, nội dung Elementor hoặc dictionary TranslatePress.

## Nhật ký thực thi

- [Dry-run trước seed](checks/phase5/dry-run.json): 75 field cấp cao, 22 asset; 10 tái sử dụng và 12 import.
- [Journal lần áp dụng](checks/phase5/local-write-20260911-023252.json): before/after từng record được tạo/cập nhật; bao gồm giá trị ACF đầy đủ.
- [Journal chạy lại](checks/phase5/local-write-20260911-023337.json): `database_diff=[]`, không có query ghi; 0 import.
- [Correction attachment](checks/phase5/attachment-parent-correction.json): khôi phục side effect của ACF trên attachment 1541. ACF tự gắn ảnh chưa có parent vào front page khi update_field; đã khôi phục chính xác post_parent=0, comment_status rỗng và hai timestamp cũ theo journal. Routine hiện chặn `acf/connect_attachment_to_post` trong lúc setup. Không thay file ảnh hoặc nội dung attachment cũ.

Kết quả ròng: **63 options mới**, **40 posts mới** (12 attachment + 28 nav_menu_item), **568 postmeta mới** (292 trên front page + 24 attachment metadata + 252 menu item metadata), **2 term nav_menu**, **2 term_taxonomy nav_menu**, **28 term_relationships**. Không xóa record. Attachment 1541 đã trả về trạng thái trước seed; không còn cập nhật ròng nào trên posts cũ.

Các query nội bộ lặp lại để cập nhật count/menu không đồng nghĩa số record mới; số record ở trên lấy diff trước/sau. Journal không xuất options hoặc dữ liệu không thay đổi, không chứa mật khẩu/API key/plugin secrets.

## Options created

Mỗi option bên dưới được tạo mới. Field references `_options_nc_*` là metadata ACF, không phải nguồn dữ liệu thứ hai.

| Option | Value |
|---|---|
| `options_nc_header_contact_label` | Liên hệ ngay |
| `_options_nc_header_contact_label` | field_nc_header_contact_label |
| `options_nc_contact_address` | 63 Phan Đăng Lưu Street, Hai Chau, Da Nang 550000, Viet Nam |
| `_options_nc_contact_address` | field_nc_contact_address |
| `options_nc_contact_email` | info@nextcore.vn |
| `_options_nc_contact_email` | field_nc_contact_email |
| `options_nc_contact_phone` | +84378962625 |
| `_options_nc_contact_phone` | field_nc_contact_phone |
| `options_nc_contact_page` | 320 |
| `_options_nc_contact_page` | field_nc_contact_page |
| `options_nc_footer_description` | Giải pháp công nghệ cho doanh nghiệp. |
| `_options_nc_footer_description` | field_nc_footer_description |
| `options_nc_footer_about_heading` | Về Nextcore |
| `_options_nc_footer_about_heading` | field_nc_footer_about_heading |
| `options_nc_footer_support_heading` | Hỗ trợ |
| `_options_nc_footer_support_heading` | field_nc_footer_support_heading |
| `options_nc_footer_contact_heading` | Liên hệ |
| `_options_nc_footer_contact_heading` | field_nc_footer_contact_heading |
| `options_nc_footer_social_heading` | Kết nối |
| `_options_nc_footer_social_heading` | field_nc_footer_social_heading |
| `options_nc_footer_motto` | Build a<br>better tomorrow |
| `_options_nc_footer_motto` | field_nc_footer_motto |
| `options_nc_social_links_0_platform` | facebook |
| `_options_nc_social_links_0_platform` | field_nc_social_links_platform |
| `options_nc_social_links_0_url` | https://www.facebook.com/nextcore.software.jsc |
| `_options_nc_social_links_0_url` | field_nc_social_links_url |
| `options_nc_social_links_1_platform` | tiktok |
| `_options_nc_social_links_1_platform` | field_nc_social_links_platform |
| `options_nc_social_links_1_url` | https://www.tiktok.com/@nextcore.software.jsc |
| `_options_nc_social_links_1_url` | field_nc_social_links_url |
| `options_nc_social_links` | 2 |
| `_options_nc_social_links` | field_nc_social_links |
| `options_nc_hero_image_dark` | 1821 |
| `_options_nc_hero_image_dark` | field_nc_hero_image_dark |
| `options_nc_hero_image_light` | 1822 |
| `_options_nc_hero_image_light` | field_nc_hero_image_light |
| `options_nc_about_video_dark` | 1823 |
| `_options_nc_about_video_dark` | field_nc_about_video_dark |
| `options_nc_about_video_light` | 1824 |
| `_options_nc_about_video_light` | field_nc_about_video_light |
| `options_nc_about_poster_dark` | 1825 |
| `_options_nc_about_poster_dark` | field_nc_about_poster_dark |
| `options_nc_about_poster_light` | 1826 |
| `_options_nc_about_poster_light` | field_nc_about_poster_light |
| `options_nc_cta_image_dark` | 1827 |
| `_options_nc_cta_image_dark` | field_nc_cta_image_dark |
| `options_nc_cta_image_light` | 1828 |
| `_options_nc_cta_image_light` | field_nc_cta_image_light |
| `options_nc_contact_integration_owner` | theme |
| `_options_nc_contact_integration_owner` | field_nc_contact_integration_owner |
| `options_nc_messenger_url` |  |
| `_options_nc_messenger_url` | field_nc_messenger_url |
| `options_nc_zalo_oa_id` |  |
| `_options_nc_zalo_oa_id` | field_nc_zalo_oa_id |
| `options_nc_zalo_welcome` |  |
| `_options_nc_zalo_welcome` | field_nc_zalo_welcome |
| `options_nc_analytics_owner` | theme |
| `_options_nc_analytics_owner` | field_nc_analytics_owner |
| `options_nc_umami_script_url` |  |
| `_options_nc_umami_script_url` | field_nc_umami_script_url |
| `options_nc_umami_website_id` |  |
| `_options_nc_umami_website_id` | field_nc_umami_website_id |
| `theme_mods_nextcore-theme` | a:1:{s:18:"nav_menu_locations";a:2:{s:12:"home_primary";i:84;s:11:"home_mobile";i:85;}} |

## Menu objects và assignments

Tạo Desktop term 84 và Mobile term 85. `theme_mods_nextcore-theme.nav_menu_locations` từ chưa tồn tại thành `{"home_primary":84,"home_mobile":85}`. Không thay menu 69, locations của Flatsome child, hoặc primary/primary_mobile/footer locations. Không duplicate menu 69.

### home_primary

| Item ID | Parent ID | Label | Type | Object ID | Destination |
|---|---|---|---|---|---|
| 1829 | 0 | Giới thiệu | custom | 1829 | #about |
| 1830 | 0 | Dịch vụ | custom | 1830 | #services |
| 1831 | 1830 | Outsource | post_type | 1133 | http://localhost/outsource/ |
| 1832 | 1830 | Tư vấn doanh nghiệp | taxonomy | 74 | http://localhost/danh-muc-dich-vu/tu-van-doanh-nghiep/ |
| 1833 | 1830 | Khách hàng cá nhân | taxonomy | 75 | http://localhost/danh-muc-dich-vu/khach-hang-ca-nhan/ |
| 1834 | 1830 | Sản phẩm | taxonomy | 81 | http://localhost/danh-muc-dich-vu/san-pham/ |
| 1835 | 0 | Công nghệ | custom | 1835 | #technology |
| 1836 | 0 | Sản phẩm / Dự án | custom | 1836 | #projects |
| 1837 | 0 | Đội ngũ | custom | 1837 | #team |
| 1838 | 0 | Tin tức | custom | 1838 | #blog |
| 1839 | 1838 | Kiến thức | taxonomy | 1 | http://localhost/category/kien-thuc/ |
| 1840 | 1838 | Công ty | taxonomy | 76 | http://localhost/category/cong-ty/ |
| 1841 | 0 | Liên hệ | custom | 1841 | #contact |

### home_mobile

| Item ID | Parent ID | Label | Type | Object ID | Destination |
|---|---|---|---|---|---|
| 1842 | 0 | Giới thiệu | custom | 1842 | #about |
| 1843 | 0 | Dịch vụ | custom | 1843 | #services |
| 1844 | 1843 | Outsource | post_type | 1133 | http://localhost/outsource/ |
| 1845 | 1843 | Tư vấn doanh nghiệp | taxonomy | 74 | http://localhost/danh-muc-dich-vu/tu-van-doanh-nghiep/ |
| 1846 | 1843 | Khách hàng cá nhân | taxonomy | 75 | http://localhost/danh-muc-dich-vu/khach-hang-ca-nhan/ |
| 1847 | 1843 | Sản phẩm | taxonomy | 81 | http://localhost/danh-muc-dich-vu/san-pham/ |
| 1848 | 0 | Công nghệ | custom | 1848 | #technology |
| 1849 | 0 | Sản phẩm / Dự án | custom | 1849 | #projects |
| 1850 | 0 | Đối tác | custom | 1850 | #partner |
| 1851 | 0 | Đánh giá | custom | 1851 | #testimonials |
| 1852 | 0 | Đội ngũ | custom | 1852 | #team |
| 1853 | 0 | Tin tức | custom | 1853 | #blog |
| 1854 | 1853 | Kiến thức | taxonomy | 1 | http://localhost/category/kien-thuc/ |
| 1855 | 1853 | Công ty | taxonomy | 76 | http://localhost/category/cong-ty/ |
| 1856 | 0 | Liên hệ | custom | 1856 | #contact |

## Media mapping

File nguồn thuộc `wp-content/themes/nextcore-theme/assets/`. Chỉ tái sử dụng attachment nếu checksum full file trùng; không dựa vào tên file hoặc ID candidate đơn lẻ. Original-only/scaled match không được tự import lại hoặc tự giảm chất lượng. 12 file mới được copy byte-identical và có metadata/sizes do WordPress tạo; không scale bản gốc. Không thay metadata 10 ảnh cũ.

| Asset | Action | Attachment ID | SHA-256 |
|---|---|---|---|
| images/gm-solutions.png | reuse | 1789 | `5a0b2b0e2777d59f61f3f57977f92cae3faf52a74c563071040561ae374fd1e9` |
| images/service-outsource.png | import | 1817 | `77b8de3f5b38cd737c220d2e58c899ccae2c2b65f6673021fa264ae5d8f5872e` |
| images/service-consulting.png | import | 1818 | `84d7b13490e34550b4276eef4c36310130f7f69af2c485e8783ff41f28ce18b8` |
| images/service-personal.png | import | 1819 | `141bd2a14537130f585b63cec11cd344a5f4126db5cf347f53f2c9106b9ed723` |
| images/project-portal.png | reuse | 1535 | `e3944995f225390c66f1e3e956ccf7b6f1c3a0ff0b9f9ec0f48b0a5387e66bff` |
| images/project-olympia.png | import | 1820 | `0982e0ecb7a86f313919078a8374bc7457629410528a4582df0dbcc0f1e2da6b` |
| images/project-affiliate.png | reuse | 1541 | `535675302269de041dac0336cc0c69e6de75ee864153e0edc16b8505dd8cf3bd` |
| images/testimonial-1.png | reuse | 1578 | `711d57fa008e20380f6f8474c6d7a391d32cefeba505bbd4e91e68e8439d054e` |
| images/testimonial-2.webp | reuse | 1580 | `ef314a553b01e0cf1befb611bca07bcd8d3b104c2e2a658bec643c0fe5ab56ad` |
| images/testimonial-3.jpg | reuse | 1581 | `0c552ff36ce3d04555b0dc42e9f6c5566898c5542634b869e582d39320101a67` |
| images/testimonial-4.png | reuse | 1579 | `d09c603f7d1de2bb6d75f30b13f81dc3228c3d64cb6addf9e2aaada6a299e463` |
| images/team-hien.jpg | reuse | 1799 | `3fbc912b13077a5dc3898ffb3e984c36e2ad414a850360b93799129a904b81b9` |
| images/team-anh.jpg | reuse | 527 | `de30c4093c19fa58d724f9d8e455af463cb58edc66cec3901cf8537c4575efc1` |
| images/team-tu.png | reuse | 526 | `6a873dadf5d51d22d144ad0dfa014579f2e80d19d381c88a238f6b3ce92f19c6` |
| images/hero-corporate-v2.png | import | 1821 | `19e8217e50c093387d3bef899f1229b14e3d078ae1dc80f59b92b119ab2eada2` |
| images/hero-corporate-light.png | import | 1822 | `cb5bb373cda6aae3cd0af6d365a0292e2e110e880e75c226ff6c97924f915964` |
| video/nextcore-danang.mp4 | import | 1823 | `d155e068ee37bf853c30f96dd1d1edc17a30f0a20f81d65633da7a173d6857a8` |
| video/caurongquay-light-video.mp4 | import | 1824 | `84e196ef6dd4320381f53e55030b49bd7dad7ca9bf71bc5a2e1802815bf4f450` |
| images/danang-poster.jpg | import | 1825 | `c27897a641aafeacff95e0758ef40728e658ab5c3c6970fa12767eba2cdb4fd0` |
| images/cauronglight.png | import | 1826 | `6100f6f0f23c82cfaea31e2afbdd244fb7e27c87402e5aa30aa552fbc3985df4` |
| images/cta.png | import | 1827 | `9b1f1acd83d2ac1a1899308a87bcd20665c20e06a80ba56fe205a739c42780ec` |
| images/cta-light.png | import | 1828 | `0e5333e23556309bbefe391f2b8deb4693a1718820e6fae1b5f1e1e510403dc5` |

## ACF values written

75 field cấp cao được ghi bằng field key; repeaters chỉ giữ subfield đã định nghĩa. Context `312` là front page được resolve từ core option; `option` là Nextcore Settings. ID bên dưới là **local**, không hard-code vào template hoặc dùng lại cho production. Ngày input `2022-06-15` được lưu raw `20220615`, ACF trả `Y-m-d`.

### nc_hero_eyebrow

Context: `312`; key: `field_nc_hero_eyebrow`; action: created.

```json
"Software development company"
```

### nc_hero_line_one

Context: `312`; key: `field_nc_hero_line_one`; action: created.

```json
"Công ty cổ phần Phần"
```

### nc_hero_line_two_prefix

Context: `312`; key: `field_nc_hero_line_two_prefix`; action: created.

```json
"mềm"
```

### nc_hero_brand

Context: `312`; key: `field_nc_hero_brand`; action: created.

```json
"Nextcore"
```

### nc_hero_slogan

Context: `312`; key: `field_nc_hero_slogan`; action: created.

```json
"BUILD TRUST, CREATE VALUE"
```

### nc_hero_description

Context: `312`; key: `field_nc_hero_description`; action: created.

```json
"Giải pháp phần mềm cho doanh nghiệp hiện đại."
```

### nc_hero_contact_label

Context: `312`; key: `field_nc_hero_contact_label`; action: created.

```json
"Liên hệ ngay"
```

### nc_hero_secondary_label

Context: `312`; key: `field_nc_hero_secondary_label`; action: created.

```json
"Xem năng lực"
```

### nc_about_eyebrow

Context: `312`; key: `field_nc_about_eyebrow`; action: created.

```json
"Về Nextcore"
```

### nc_about_heading_line_one

Context: `312`; key: `field_nc_about_heading_line_one`; action: created.

```json
"Công ty Cổ phần"
```

### nc_about_heading_line_two

Context: `312`; key: `field_nc_about_heading_line_two`; action: created.

```json
"Phần mềm Nextcore"
```

### nc_about_founded_date

Context: `312`; key: `field_nc_about_founded_date`; action: created.

```json
"2022-06-15"
```

### nc_about_body

Context: `312`; key: `field_nc_about_body`; action: created.

```json
"<p>Chuyên thực hiện phát triển, bảo trì các dự án CNTT cho các đối tác outsource.</p>\n            <p>Đối tác của Công ty là các Công ty outsource lớn-vừa-nhỏ ở Việt Nam ở cả 3 thị trường nói tiếng Anh-Nhật-Việt.</p>\n            <p>Không ngừng nỗ lực để giải quyết các vấn đề là <strong>Nỗi đau</strong> và tạo giá trị <strong>hữu ích</strong> cho khách hàng để trở thành đối tác tin cậy và lâu dài.</p>"
```

### nc_about_link_label

Context: `312`; key: `field_nc_about_link_label`; action: created.

```json
"Tìm hiểu thêm"
```

### nc_stats

Context: `312`; key: `field_nc_stats`; action: created.

```json
[
  {
    "metric": "projects",
    "value": 35,
    "suffix": "+",
    "label": "Dự án triển khai"
  },
  {
    "metric": "clients",
    "value": 25,
    "suffix": "+",
    "label": "Khách hàng tin tưởng"
  },
  {
    "metric": "employees",
    "value": 20,
    "suffix": "+",
    "label": "Nhân viên"
  }
]
```

### nc_services_eyebrow

Context: `312`; key: `field_nc_services_eyebrow`; action: created.

```json
"Dịch vụ"
```

### nc_services_heading

Context: `312`; key: `field_nc_services_heading`; action: created.

```json
"Năng lực cốt lõi"
```

### nc_services_contact_label

Context: `312`; key: `field_nc_services_contact_label`; action: created.

```json
"Trao đổi nhu cầu "
```

### nc_service_cards

Context: `312`; key: `field_nc_service_cards`; action: created.

```json
[
  {
    "title": "Outsource CNTT",
    "description": "Phát triển và bảo trì dự án CNTT cho đối tác outsource.",
    "image": 1817,
    "icon": "code",
    "micro_text": "CODE\nSOLVE\nTOGETHER",
    "target_kind": "page",
    "target_page": 1133,
    "target_term": ""
  },
  {
    "title": "Tư vấn doanh nghiệp",
    "description": "Đồng hành tối ưu hoạt động và xây dựng giải pháp phù hợp.",
    "image": 1818,
    "icon": "strategy",
    "micro_text": "PEOPLE\nSTRATEGY\nGROWTH",
    "target_kind": "term",
    "target_page": "",
    "target_term": 74
  },
  {
    "title": "Giải pháp cá nhân",
    "description": "Hỗ trợ các nhu cầu công nghệ linh hoạt cho khách hàng cá nhân.",
    "image": 1819,
    "icon": "user",
    "micro_text": "SIMPLE\nFLEXIBLE\nFOR YOU",
    "target_kind": "term",
    "target_page": "",
    "target_term": 75
  }
]
```

### nc_technology_eyebrow

Context: `312`; key: `field_nc_technology_eyebrow`; action: created.

```json
"Công nghệ"
```

### nc_technology_heading

Context: `312`; key: `field_nc_technology_heading`; action: created.

```json
"Nền tảng\n tạo nên khác biệt"
```

### nc_technology_items

Context: `312`; key: `field_nc_technology_items`; action: created.

```json
[
  {
    "mark": "react",
    "label": "React"
  },
  {
    "mark": "laravel",
    "label": "Laravel"
  },
  {
    "mark": "node",
    "label": "node.js"
  },
  {
    "mark": "aws",
    "label": "aws"
  },
  {
    "mark": "mysql",
    "label": "MySQL"
  },
  {
    "mark": "docker",
    "label": "docker"
  },
  {
    "mark": "figma",
    "label": "Figma"
  },
  {
    "mark": "cicd",
    "label": "CI/CD"
  }
]
```

### nc_projects_eyebrow

Context: `312`; key: `field_nc_projects_eyebrow`; action: created.

```json
"Sản phẩm nổi bật"
```

### nc_projects_heading

Context: `312`; key: `field_nc_projects_heading`; action: created.

```json
"Giải pháp được tin chọn"
```

### nc_projects_contact_label

Context: `312`; key: `field_nc_projects_contact_label`; action: created.

```json
"Trao đổi về dự án "
```

### nc_featured_projects

Context: `312`; key: `field_nc_featured_projects`; action: created.

```json
[
  {
    "object": "",
    "visual_variant": "portal",
    "card_label": "Nextcore Portal",
    "card_summary": "Hệ thống quản lý doanh nghiệp thông minh.",
    "card_image": 1535
  },
  {
    "object": 1510,
    "visual_variant": "olympia",
    "card_label": "Trường Doanh nhân Top Olympia",
    "card_summary": "Website đào tạo doanh nhân.",
    "card_image": 1820
  },
  {
    "object": 1548,
    "visual_variant": "affiliate",
    "card_label": "WordPress Plugin - Affiliate",
    "card_summary": "Giải pháp tiếp thị liên kết hiệu quả.",
    "card_image": 1541
  }
]
```

### nc_partner_eyebrow

Context: `312`; key: `field_nc_partner_eyebrow`; action: created.

```json
"Đối tác chiến lược"
```

### nc_partner_name

Context: `312`; key: `field_nc_partner_name`; action: created.

```json
"GM Solutions"
```

### nc_partner_logo

Context: `312`; key: `field_nc_partner_logo`; action: created.

```json
1789
```

### nc_partner_description

Context: `312`; key: `field_nc_partner_description`; action: created.

```json
"Cùng nhau kiến tạo\nnhững giá trị bền vững."
```

### nc_partner_motto

Context: `312`; key: `field_nc_partner_motto`; action: created.

```json
"Stronger\n together\n for a brighter\n tomorrow"
```

### nc_partner_link_label

Context: `312`; key: `field_nc_partner_link_label`; action: created.

```json
"Tìm hiểu thêm"
```

### nc_partner_link

Context: `312`; key: `field_nc_partner_link`; action: created.

```json
{
  "title": "Tìm hiểu thêm",
  "url": "https://gm-group.vn/",
  "target": ""
}
```

### nc_testimonials_eyebrow

Context: `312`; key: `field_nc_testimonials_eyebrow`; action: created.

```json
"KHÁCH HÀNG NÓI VỀ NEXTCORE"
```

### nc_testimonials_heading

Context: `312`; key: `field_nc_testimonials_heading`; action: created.

```json
"Đánh giá từ khách hàng"
```

### nc_testimonials_intro

Context: `312`; key: `field_nc_testimonials_intro`; action: created.

```json
"Những chia sẻ thực tế từ khách hàng đang đồng hành cùng Nextcore."
```

### nc_testimonials

Context: `312`; key: `field_nc_testimonials`; action: created.

```json
[
  {
    "name": "Công ty An Tâm Việt",
    "project_label": "Dự án Plugin Affiliate",
    "quote": "Nextcore để lại ấn tượng mạnh mẽ với đội ngũ hỗ trợ chuyên nghiệp, luôn đồng hành từ khâu tư vấn ban đầu đến việc sắp xếp kế hoạch và triển khai dự án. Phản hồi nhanh chóng, thông tin rõ ràng giúp chúng tôi hoàn toàn yên tâm khi làm việc cùng họ. Đặc biệt, sự uy tín và cam kết trách nhiệm với deadline của Nextcore là yếu tố nổi bật. Không thể không nhắc đến anh Tú, Project Manager của đội, người luôn tận tâm tư vấn và hỗ trợ nhiệt tình, mang đến trải nghiệm làm việc tuyệt vời.\nNextcore không chỉ là một đối tác, mà còn là một người bạn đồng hành đáng tin cậy trên hành trình phát triển của chúng tôi!",
    "image": 1578
  },
  {
    "name": "Phạm Việt Hùng",
    "project_label": "Dự án Tạo template ebook",
    "quote": "Hoàn thành công việc nhanh và chất lượng. Nhiệt tình hỗ trợ sau khi hoàn thành công việc",
    "image": 1580
  },
  {
    "name": "Nguyễn Phương Trà My",
    "project_label": "Dự án Convert nghiệp vụ sang sơ đồ khối",
    "quote": "Công ty Phần mềm Next Core làm dự án của tôi rất chuyên nghiệp, thực hiện đúng yêu cầu của khách hàng và hoàn thành công việc sớm hơn thời gian quy định, đảm bảo chất lượng. Các bạn dev của Công ty hỗ trợ tôi rất nhiệt tình trong thời gian thực hiện yêu cầu của tôi. Highly recommend cho các khách hàng trong lĩnh vực IT, phần mềm, design.",
    "image": 1581
  },
  {
    "name": "Bé khỏe bé vui",
    "project_label": "Dự án phục hồi website bị tấn công",
    "quote": "Highly appreciate your working spirit. I am pleased to choose you as partners. We will continue to cooperate in the future when there are new projects.",
    "image": 1579
  }
]
```

### nc_team_eyebrow

Context: `312`; key: `field_nc_team_eyebrow`; action: created.

```json
"ĐỘI NGŨ"
```

### nc_team_heading

Context: `312`; key: `field_nc_team_heading`; action: created.

```json
"Đội ngũ"
```

### nc_team_intro

Context: `312`; key: `field_nc_team_intro`; action: created.

```json
"Đội ngũ chuyên môn cao, tận tâm và luôn sẵn sàng đồng hành."
```

### nc_team_members

Context: `312`; key: `field_nc_team_members`; action: created.

```json
[
  {
    "name": "Nguyễn Văn Hiền",
    "role": "Chief Executive Officer",
    "image": 1799,
    "email": "hiennv@nextcore.vn",
    "phone": "+84378962625"
  },
  {
    "name": "Trần Đức Anh",
    "role": "Chief Technology Officer",
    "image": 527,
    "email": "anhtd@nextcore.vn",
    "phone": "+84976748059"
  },
  {
    "name": "Phan Thanh Tú",
    "role": "Project Manager",
    "image": 526,
    "email": "tupt@nextcore.vn",
    "phone": "+84979525694"
  }
]
```

### nc_cta_heading

Context: `312`; key: `field_nc_cta_heading`; action: created.

```json
"Sẵn sàng bắt đầu dự án của bạn?"
```

### nc_cta_description

Context: `312`; key: `field_nc_cta_description`; action: created.

```json
"Hãy để Nextcore đồng hành cùng bạn trên hành trình chuyển đổi số."
```

### nc_cta_button_label

Context: `312`; key: `field_nc_cta_button_label`; action: created.

```json
"Liên hệ ngay"
```

### nc_cta_motto

Context: `312`; key: `field_nc_cta_motto`; action: created.

```json
"New\nideas.\nHigher\npossibilities."
```

### nc_header_contact_label

Context: `option`; key: `field_nc_header_contact_label`; action: created.

```json
"Liên hệ ngay"
```

### nc_contact_address

Context: `option`; key: `field_nc_contact_address`; action: created.

```json
"63 Phan Đăng Lưu Street, Hai Chau, Da Nang 550000, Viet Nam"
```

### nc_contact_email

Context: `option`; key: `field_nc_contact_email`; action: created.

```json
"info@nextcore.vn"
```

### nc_contact_phone

Context: `option`; key: `field_nc_contact_phone`; action: created.

```json
"+84378962625"
```

### nc_contact_page

Context: `option`; key: `field_nc_contact_page`; action: created.

```json
320
```

### nc_footer_description

Context: `option`; key: `field_nc_footer_description`; action: created.

```json
"Giải pháp công nghệ cho doanh nghiệp."
```

### nc_footer_about_heading

Context: `option`; key: `field_nc_footer_about_heading`; action: created.

```json
"Về Nextcore"
```

### nc_footer_support_heading

Context: `option`; key: `field_nc_footer_support_heading`; action: created.

```json
"Hỗ trợ"
```

### nc_footer_contact_heading

Context: `option`; key: `field_nc_footer_contact_heading`; action: created.

```json
"Liên hệ"
```

### nc_footer_social_heading

Context: `option`; key: `field_nc_footer_social_heading`; action: created.

```json
"Kết nối"
```

### nc_footer_motto

Context: `option`; key: `field_nc_footer_motto`; action: created.

```json
"Build a\nbetter tomorrow"
```

### nc_social_links

Context: `option`; key: `field_nc_social_links`; action: created.

```json
[
  {
    "platform": "facebook",
    "url": "https://www.facebook.com/nextcore.software.jsc"
  },
  {
    "platform": "tiktok",
    "url": "https://www.tiktok.com/@nextcore.software.jsc"
  }
]
```

### nc_hero_image_dark

Context: `option`; key: `field_nc_hero_image_dark`; action: created.

```json
1821
```

### nc_hero_image_light

Context: `option`; key: `field_nc_hero_image_light`; action: created.

```json
1822
```

### nc_about_video_dark

Context: `option`; key: `field_nc_about_video_dark`; action: created.

```json
1823
```

### nc_about_video_light

Context: `option`; key: `field_nc_about_video_light`; action: created.

```json
1824
```

### nc_about_poster_dark

Context: `option`; key: `field_nc_about_poster_dark`; action: created.

```json
1825
```

### nc_about_poster_light

Context: `option`; key: `field_nc_about_poster_light`; action: created.

```json
1826
```

### nc_cta_image_dark

Context: `option`; key: `field_nc_cta_image_dark`; action: created.

```json
1827
```

### nc_cta_image_light

Context: `option`; key: `field_nc_cta_image_light`; action: created.

```json
1828
```

### nc_blog_eyebrow

Context: `312`; key: `field_nc_blog_eyebrow`; action: created.

```json
"Tin tức"
```

### nc_blog_heading

Context: `312`; key: `field_nc_blog_heading`; action: created.

```json
"Cập nhật mới nhất"
```

### nc_blog_note

Context: `312`; key: `field_nc_blog_note`; action: created.

```json
"Góc nhìn & kiến thức"
```

### nc_contact_integration_owner

Context: `option`; key: `field_nc_contact_integration_owner`; action: created.

```json
"theme"
```

### nc_messenger_url

Context: `option`; key: `field_nc_messenger_url`; action: created.

```json
""
```

### nc_zalo_oa_id

Context: `option`; key: `field_nc_zalo_oa_id`; action: created.

```json
""
```

### nc_zalo_welcome

Context: `option`; key: `field_nc_zalo_welcome`; action: created.

```json
""
```

### nc_analytics_owner

Context: `option`; key: `field_nc_analytics_owner`; action: created.

```json
"theme"
```

### nc_umami_script_url

Context: `option`; key: `field_nc_umami_script_url`; action: created.

```json
""
```

### nc_umami_website_id

Context: `option`; key: `field_nc_umami_website_id`; action: created.

```json
""
```

## Exact postmeta keys created

Bao gồm ACF references và các key core dùng cho media/menu. Giá trị dài có thể xuống dòng; bản JSON journal phía trên bảo toàn byte/string và toàn bộ metadata serialized.

| Meta ID | Post ID | Key | Value |
|---|---|---|---|
| 39129 | 1817 | `_wp_attached_file` | 2026/09/service-outsource.png |
| 39130 | 1817 | `_wp_attachment_metadata` | a:6:{s:5:"width";i:1122;s:6:"height";i:1402;s:4:"file";s:29:"2026/09/service-outsource.png";s:8:"filesize";i:1631173;s:5:"sizes";a:4:{s:6:"medium";a:5:{s:4:"file";s:29:"service-outsource-320x400.png";s:5:"width";i:320;s:6:"height";i:400;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:176354;}s:5:"large";a:5:{s:4:"file";s:29:"service-outsource-640x800.png";s:5:"width";i:640;s:6:"height";i:800;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:632905;}s:9:"thumbnail";a:5:{s:4:"file";s:29:"service-outsource-280x280.png";s:5:"width";i:280;s:6:"height";i:280;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:114939;}s:12:"medium_large";a:5:{s:4:"file";s:29:"service-outsource-768x960.png";s:5:"width";i:768;s:6:"height";i:960;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:880305;}}s:10:"image_meta";a:13:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}s:3:"alt";s:0:"";}} |
| 39131 | 1818 | `_wp_attached_file` | 2026/09/service-consulting.png |
| 39132 | 1818 | `_wp_attachment_metadata` | a:6:{s:5:"width";i:1122;s:6:"height";i:1402;s:4:"file";s:30:"2026/09/service-consulting.png";s:8:"filesize";i:1653199;s:5:"sizes";a:4:{s:6:"medium";a:5:{s:4:"file";s:30:"service-consulting-320x400.png";s:5:"width";i:320;s:6:"height";i:400;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:179027;}s:5:"large";a:5:{s:4:"file";s:30:"service-consulting-640x800.png";s:5:"width";i:640;s:6:"height";i:800;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:627723;}s:9:"thumbnail";a:5:{s:4:"file";s:30:"service-consulting-280x280.png";s:5:"width";i:280;s:6:"height";i:280;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:118453;}s:12:"medium_large";a:5:{s:4:"file";s:30:"service-consulting-768x960.png";s:5:"width";i:768;s:6:"height";i:960;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:872663;}}s:10:"image_meta";a:13:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}s:3:"alt";s:0:"";}} |
| 39133 | 1819 | `_wp_attached_file` | 2026/09/service-personal.png |
| 39134 | 1819 | `_wp_attachment_metadata` | a:6:{s:5:"width";i:1122;s:6:"height";i:1402;s:4:"file";s:28:"2026/09/service-personal.png";s:8:"filesize";i:1534097;s:5:"sizes";a:4:{s:6:"medium";a:5:{s:4:"file";s:28:"service-personal-320x400.png";s:5:"width";i:320;s:6:"height";i:400;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:145506;}s:5:"large";a:5:{s:4:"file";s:28:"service-personal-640x800.png";s:5:"width";i:640;s:6:"height";i:800;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:551193;}s:9:"thumbnail";a:5:{s:4:"file";s:28:"service-personal-280x280.png";s:5:"width";i:280;s:6:"height";i:280;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:94035;}s:12:"medium_large";a:5:{s:4:"file";s:28:"service-personal-768x960.png";s:5:"width";i:768;s:6:"height";i:960;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:778420;}}s:10:"image_meta";a:13:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}s:3:"alt";s:0:"";}} |
| 39135 | 1820 | `_wp_attached_file` | 2026/09/project-olympia.png |
| 39136 | 1820 | `_wp_attachment_metadata` | a:6:{s:5:"width";i:768;s:6:"height";i:364;s:4:"file";s:27:"2026/09/project-olympia.png";s:8:"filesize";i:157021;s:5:"sizes";a:1:{s:9:"thumbnail";a:5:{s:4:"file";s:27:"project-olympia-280x280.png";s:5:"width";i:280;s:6:"height";i:280;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:74474;}}s:10:"image_meta";a:13:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}s:3:"alt";s:0:"";}} |
| 39137 | 1821 | `_wp_attached_file` | 2026/09/hero-corporate-v2.png |
| 39138 | 1821 | `_wp_attachment_metadata` | a:6:{s:5:"width";i:1672;s:6:"height";i:941;s:4:"file";s:29:"2026/09/hero-corporate-v2.png";s:8:"filesize";i:1651748;s:5:"sizes";a:5:{s:6:"medium";a:5:{s:4:"file";s:29:"hero-corporate-v2-711x400.png";s:5:"width";i:711;s:6:"height";i:400;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:378573;}s:5:"large";a:5:{s:4:"file";s:30:"hero-corporate-v2-1400x788.png";s:5:"width";i:1400;s:6:"height";i:788;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:1275947;}s:9:"thumbnail";a:5:{s:4:"file";s:29:"hero-corporate-v2-280x280.png";s:5:"width";i:280;s:6:"height";i:280;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:114067;}s:12:"medium_large";a:5:{s:4:"file";s:29:"hero-corporate-v2-768x432.png";s:5:"width";i:768;s:6:"height";i:432;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:435558;}s:9:"1536x1536";a:5:{s:4:"file";s:30:"hero-corporate-v2-1536x864.png";s:5:"width";i:1536;s:6:"height";i:864;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:1492726;}}s:10:"image_meta";a:13:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}s:3:"alt";s:0:"";}} |
| 39139 | 1822 | `_wp_attached_file` | 2026/09/hero-corporate-light.png |
| 39140 | 1822 | `_wp_attachment_metadata` | a:6:{s:5:"width";i:1672;s:6:"height";i:941;s:4:"file";s:32:"2026/09/hero-corporate-light.png";s:8:"filesize";i:1635636;s:5:"sizes";a:5:{s:6:"medium";a:5:{s:4:"file";s:32:"hero-corporate-light-711x400.png";s:5:"width";i:711;s:6:"height";i:400;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:359501;}s:5:"large";a:5:{s:4:"file";s:33:"hero-corporate-light-1400x788.png";s:5:"width";i:1400;s:6:"height";i:788;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:1249468;}s:9:"thumbnail";a:5:{s:4:"file";s:32:"hero-corporate-light-280x280.png";s:5:"width";i:280;s:6:"height";i:280;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:109160;}s:12:"medium_large";a:5:{s:4:"file";s:32:"hero-corporate-light-768x432.png";s:5:"width";i:768;s:6:"height";i:432;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:415243;}s:9:"1536x1536";a:5:{s:4:"file";s:33:"hero-corporate-light-1536x864.png";s:5:"width";i:1536;s:6:"height";i:864;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:1466662;}}s:10:"image_meta";a:13:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}s:3:"alt";s:0:"";}} |
| 39141 | 1823 | `_wp_attached_file` | 2026/09/nextcore-danang.mp4 |
| 39142 | 1823 | `_wp_attachment_metadata` | a:11:{s:7:"bitrate";i:12354209;s:8:"filesize";i:4719804;s:9:"mime_type";s:15:"video/quicktime";s:6:"length";i:3;s:16:"length_formatted";s:4:"0:03";s:5:"width";i:1920;s:6:"height";i:1072;s:10:"fileformat";s:3:"mp4";s:10:"dataformat";s:9:"quicktime";s:5:"codec";s:16:"H.264/MPEG-4 AVC";s:17:"created_timestamp";i:-2082844800;} |
| 39143 | 1824 | `_wp_attached_file` | 2026/09/caurongquay-light-video.mp4 |
| 39144 | 1824 | `_wp_attachment_metadata` | a:11:{s:7:"bitrate";i:12939539;s:8:"filesize";i:4942376;s:9:"mime_type";s:15:"video/quicktime";s:6:"length";i:3;s:16:"length_formatted";s:4:"0:03";s:5:"width";i:1920;s:6:"height";i:1072;s:10:"fileformat";s:3:"mp4";s:10:"dataformat";s:9:"quicktime";s:5:"codec";s:16:"H.264/MPEG-4 AVC";s:17:"created_timestamp";i:-2082844800;} |
| 39145 | 1825 | `_wp_attached_file` | 2026/09/danang-poster.jpg |
| 39146 | 1825 | `_wp_attachment_metadata` | a:6:{s:5:"width";i:1920;s:6:"height";i:1072;s:4:"file";s:25:"2026/09/danang-poster.jpg";s:8:"filesize";i:372344;s:5:"sizes";a:5:{s:6:"medium";a:5:{s:4:"file";s:25:"danang-poster-716x400.jpg";s:5:"width";i:716;s:6:"height";i:400;s:9:"mime-type";s:10:"image/jpeg";s:8:"filesize";i:61883;}s:5:"large";a:5:{s:4:"file";s:26:"danang-poster-1400x782.jpg";s:5:"width";i:1400;s:6:"height";i:782;s:9:"mime-type";s:10:"image/jpeg";s:8:"filesize";i:171481;}s:9:"thumbnail";a:5:{s:4:"file";s:25:"danang-poster-280x280.jpg";s:5:"width";i:280;s:6:"height";i:280;s:9:"mime-type";s:10:"image/jpeg";s:8:"filesize";i:21584;}s:12:"medium_large";a:5:{s:4:"file";s:25:"danang-poster-768x429.jpg";s:5:"width";i:768;s:6:"height";i:429;s:9:"mime-type";s:10:"image/jpeg";s:8:"filesize";i:69408;}s:9:"1536x1536";a:5:{s:4:"file";s:26:"danang-poster-1536x858.jpg";s:5:"width";i:1536;s:6:"height";i:858;s:9:"mime-type";s:10:"image/jpeg";s:8:"filesize";i:194848;}}s:10:"image_meta";a:13:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}s:3:"alt";s:0:"";}} |
| 39147 | 1826 | `_wp_attached_file` | 2026/09/cauronglight.png |
| 39148 | 1826 | `_wp_attachment_metadata` | a:6:{s:5:"width";i:1672;s:6:"height";i:941;s:4:"file";s:24:"2026/09/cauronglight.png";s:8:"filesize";i:2801242;s:5:"sizes";a:5:{s:6:"medium";a:5:{s:4:"file";s:24:"cauronglight-711x400.png";s:5:"width";i:711;s:6:"height";i:400;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:612493;}s:5:"large";a:5:{s:4:"file";s:25:"cauronglight-1400x788.png";s:5:"width";i:1400;s:6:"height";i:788;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:2147611;}s:9:"thumbnail";a:5:{s:4:"file";s:24:"cauronglight-280x280.png";s:5:"width";i:280;s:6:"height";i:280;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:172073;}s:12:"medium_large";a:5:{s:4:"file";s:24:"cauronglight-768x432.png";s:5:"width";i:768;s:6:"height";i:432;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:708637;}s:9:"1536x1536";a:5:{s:4:"file";s:25:"cauronglight-1536x864.png";s:5:"width";i:1536;s:6:"height";i:864;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:2530984;}}s:10:"image_meta";a:13:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}s:3:"alt";s:0:"";}} |
| 39149 | 1827 | `_wp_attached_file` | 2026/09/cta.png |
| 39150 | 1827 | `_wp_attachment_metadata` | a:6:{s:5:"width";i:1536;s:6:"height";i:1024;s:4:"file";s:15:"2026/09/cta.png";s:8:"filesize";i:2404895;s:5:"sizes";a:4:{s:6:"medium";a:5:{s:4:"file";s:15:"cta-600x400.png";s:5:"width";i:600;s:6:"height";i:400;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:404121;}s:5:"large";a:5:{s:4:"file";s:16:"cta-1200x800.png";s:5:"width";i:1200;s:6:"height";i:800;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:1461774;}s:9:"thumbnail";a:5:{s:4:"file";s:15:"cta-280x280.png";s:5:"width";i:280;s:6:"height";i:280;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:141031;}s:12:"medium_large";a:5:{s:4:"file";s:15:"cta-768x512.png";s:5:"width";i:768;s:6:"height";i:512;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:665386;}}s:10:"image_meta";a:13:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}s:3:"alt";s:0:"";}} |
| 39151 | 1828 | `_wp_attached_file` | 2026/09/cta-light.png |
| 39152 | 1828 | `_wp_attachment_metadata` | a:6:{s:5:"width";i:1672;s:6:"height";i:941;s:4:"file";s:21:"2026/09/cta-light.png";s:8:"filesize";i:1929139;s:5:"sizes";a:5:{s:6:"medium";a:5:{s:4:"file";s:21:"cta-light-711x400.png";s:5:"width";i:711;s:6:"height";i:400;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:411246;}s:5:"large";a:5:{s:4:"file";s:22:"cta-light-1400x788.png";s:5:"width";i:1400;s:6:"height";i:788;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:1454600;}s:9:"thumbnail";a:5:{s:4:"file";s:21:"cta-light-280x280.png";s:5:"width";i:280;s:6:"height";i:280;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:106806;}s:12:"medium_large";a:5:{s:4:"file";s:21:"cta-light-768x432.png";s:5:"width";i:768;s:6:"height";i:432;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:476054;}s:9:"1536x1536";a:5:{s:4:"file";s:22:"cta-light-1536x864.png";s:5:"width";i:1536;s:6:"height";i:864;s:9:"mime-type";s:9:"image/png";s:8:"filesize";i:1715772;}}s:10:"image_meta";a:13:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}s:3:"alt";s:0:"";}} |
| 39153 | 312 | `nc_hero_eyebrow` | Software development company |
| 39154 | 312 | `_nc_hero_eyebrow` | field_nc_hero_eyebrow |
| 39155 | 312 | `nc_hero_line_one` | Công ty cổ phần Phần |
| 39156 | 312 | `_nc_hero_line_one` | field_nc_hero_line_one |
| 39157 | 312 | `nc_hero_line_two_prefix` | mềm |
| 39158 | 312 | `_nc_hero_line_two_prefix` | field_nc_hero_line_two_prefix |
| 39159 | 312 | `nc_hero_brand` | Nextcore |
| 39160 | 312 | `_nc_hero_brand` | field_nc_hero_brand |
| 39161 | 312 | `nc_hero_slogan` | BUILD TRUST, CREATE VALUE |
| 39162 | 312 | `_nc_hero_slogan` | field_nc_hero_slogan |
| 39163 | 312 | `nc_hero_description` | Giải pháp phần mềm cho doanh nghiệp hiện đại. |
| 39164 | 312 | `_nc_hero_description` | field_nc_hero_description |
| 39165 | 312 | `nc_hero_contact_label` | Liên hệ ngay |
| 39166 | 312 | `_nc_hero_contact_label` | field_nc_hero_contact_label |
| 39167 | 312 | `nc_hero_secondary_label` | Xem năng lực |
| 39168 | 312 | `_nc_hero_secondary_label` | field_nc_hero_secondary_label |
| 39169 | 312 | `nc_about_eyebrow` | Về Nextcore |
| 39170 | 312 | `_nc_about_eyebrow` | field_nc_about_eyebrow |
| 39171 | 312 | `nc_about_heading_line_one` | Công ty Cổ phần |
| 39172 | 312 | `_nc_about_heading_line_one` | field_nc_about_heading_line_one |
| 39173 | 312 | `nc_about_heading_line_two` | Phần mềm Nextcore |
| 39174 | 312 | `_nc_about_heading_line_two` | field_nc_about_heading_line_two |
| 39175 | 312 | `nc_about_founded_date` | 20220615 |
| 39176 | 312 | `_nc_about_founded_date` | field_nc_about_founded_date |
| 39177 | 312 | `nc_about_body` | &lt;p&gt;Chuyên thực hiện phát triển, bảo trì các dự án CNTT cho các đối tác outsource.&lt;/p&gt;&lt;br&gt;            &lt;p&gt;Đối tác của Công ty là các Công ty outsource lớn-vừa-nhỏ ở Việt Nam ở cả 3 thị trường nói tiếng Anh-Nhật-Việt.&lt;/p&gt;&lt;br&gt;            &lt;p&gt;Không ngừng nỗ lực để giải quyết các vấn đề là &lt;strong&gt;Nỗi đau&lt;/strong&gt; và tạo giá trị &lt;strong&gt;hữu ích&lt;/strong&gt; cho khách hàng để trở thành đối tác tin cậy và lâu dài.&lt;/p&gt; |
| 39178 | 312 | `_nc_about_body` | field_nc_about_body |
| 39179 | 312 | `nc_about_link_label` | Tìm hiểu thêm |
| 39180 | 312 | `_nc_about_link_label` | field_nc_about_link_label |
| 39181 | 312 | `nc_stats_0_metric` | projects |
| 39182 | 312 | `_nc_stats_0_metric` | field_nc_stats_metric |
| 39183 | 312 | `nc_stats_0_value` | 35 |
| 39184 | 312 | `_nc_stats_0_value` | field_nc_stats_value |
| 39185 | 312 | `nc_stats_0_suffix` | + |
| 39186 | 312 | `_nc_stats_0_suffix` | field_nc_stats_suffix |
| 39187 | 312 | `nc_stats_0_label` | Dự án triển khai |
| 39188 | 312 | `_nc_stats_0_label` | field_nc_stats_label |
| 39189 | 312 | `nc_stats_1_metric` | clients |
| 39190 | 312 | `_nc_stats_1_metric` | field_nc_stats_metric |
| 39191 | 312 | `nc_stats_1_value` | 25 |
| 39192 | 312 | `_nc_stats_1_value` | field_nc_stats_value |
| 39193 | 312 | `nc_stats_1_suffix` | + |
| 39194 | 312 | `_nc_stats_1_suffix` | field_nc_stats_suffix |
| 39195 | 312 | `nc_stats_1_label` | Khách hàng tin tưởng |
| 39196 | 312 | `_nc_stats_1_label` | field_nc_stats_label |
| 39197 | 312 | `nc_stats_2_metric` | employees |
| 39198 | 312 | `_nc_stats_2_metric` | field_nc_stats_metric |
| 39199 | 312 | `nc_stats_2_value` | 20 |
| 39200 | 312 | `_nc_stats_2_value` | field_nc_stats_value |
| 39201 | 312 | `nc_stats_2_suffix` | + |
| 39202 | 312 | `_nc_stats_2_suffix` | field_nc_stats_suffix |
| 39203 | 312 | `nc_stats_2_label` | Nhân viên |
| 39204 | 312 | `_nc_stats_2_label` | field_nc_stats_label |
| 39205 | 312 | `nc_stats` | 3 |
| 39206 | 312 | `_nc_stats` | field_nc_stats |
| 39207 | 312 | `nc_services_eyebrow` | Dịch vụ |
| 39208 | 312 | `_nc_services_eyebrow` | field_nc_services_eyebrow |
| 39209 | 312 | `nc_services_heading` | Năng lực cốt lõi |
| 39210 | 312 | `_nc_services_heading` | field_nc_services_heading |
| 39211 | 312 | `nc_services_contact_label` | Trao đổi nhu cầu  |
| 39212 | 312 | `_nc_services_contact_label` | field_nc_services_contact_label |
| 39213 | 312 | `nc_service_cards_0_title` | Outsource CNTT |
| 39214 | 312 | `_nc_service_cards_0_title` | field_nc_service_cards_title |
| 39215 | 312 | `nc_service_cards_0_description` | Phát triển và bảo trì dự án CNTT cho đối tác outsource. |
| 39216 | 312 | `_nc_service_cards_0_description` | field_nc_service_cards_description |
| 39217 | 312 | `nc_service_cards_0_image` | 1817 |
| 39218 | 312 | `_nc_service_cards_0_image` | field_nc_service_cards_image |
| 39219 | 312 | `nc_service_cards_0_icon` | code |
| 39220 | 312 | `_nc_service_cards_0_icon` | field_nc_service_cards_icon |
| 39221 | 312 | `nc_service_cards_0_micro_text` | CODE&lt;br&gt;SOLVE&lt;br&gt;TOGETHER |
| 39222 | 312 | `_nc_service_cards_0_micro_text` | field_nc_service_cards_micro_text |
| 39223 | 312 | `nc_service_cards_0_target_kind` | page |
| 39224 | 312 | `_nc_service_cards_0_target_kind` | field_nc_service_cards_target_kind |
| 39225 | 312 | `nc_service_cards_0_target_page` | 1133 |
| 39226 | 312 | `_nc_service_cards_0_target_page` | field_nc_service_cards_target_page |
| 39227 | 312 | `nc_service_cards_0_target_term` |  |
| 39228 | 312 | `_nc_service_cards_0_target_term` | field_nc_service_cards_target_term |
| 39229 | 312 | `nc_service_cards_1_title` | Tư vấn doanh nghiệp |
| 39230 | 312 | `_nc_service_cards_1_title` | field_nc_service_cards_title |
| 39231 | 312 | `nc_service_cards_1_description` | Đồng hành tối ưu hoạt động và xây dựng giải pháp phù hợp. |
| 39232 | 312 | `_nc_service_cards_1_description` | field_nc_service_cards_description |
| 39233 | 312 | `nc_service_cards_1_image` | 1818 |
| 39234 | 312 | `_nc_service_cards_1_image` | field_nc_service_cards_image |
| 39235 | 312 | `nc_service_cards_1_icon` | strategy |
| 39236 | 312 | `_nc_service_cards_1_icon` | field_nc_service_cards_icon |
| 39237 | 312 | `nc_service_cards_1_micro_text` | PEOPLE&lt;br&gt;STRATEGY&lt;br&gt;GROWTH |
| 39238 | 312 | `_nc_service_cards_1_micro_text` | field_nc_service_cards_micro_text |
| 39239 | 312 | `nc_service_cards_1_target_kind` | term |
| 39240 | 312 | `_nc_service_cards_1_target_kind` | field_nc_service_cards_target_kind |
| 39241 | 312 | `nc_service_cards_1_target_page` |  |
| 39242 | 312 | `_nc_service_cards_1_target_page` | field_nc_service_cards_target_page |
| 39243 | 312 | `nc_service_cards_1_target_term` | 74 |
| 39244 | 312 | `_nc_service_cards_1_target_term` | field_nc_service_cards_target_term |
| 39245 | 312 | `nc_service_cards_2_title` | Giải pháp cá nhân |
| 39246 | 312 | `_nc_service_cards_2_title` | field_nc_service_cards_title |
| 39247 | 312 | `nc_service_cards_2_description` | Hỗ trợ các nhu cầu công nghệ linh hoạt cho khách hàng cá nhân. |
| 39248 | 312 | `_nc_service_cards_2_description` | field_nc_service_cards_description |
| 39249 | 312 | `nc_service_cards_2_image` | 1819 |
| 39250 | 312 | `_nc_service_cards_2_image` | field_nc_service_cards_image |
| 39251 | 312 | `nc_service_cards_2_icon` | user |
| 39252 | 312 | `_nc_service_cards_2_icon` | field_nc_service_cards_icon |
| 39253 | 312 | `nc_service_cards_2_micro_text` | SIMPLE&lt;br&gt;FLEXIBLE&lt;br&gt;FOR YOU |
| 39254 | 312 | `_nc_service_cards_2_micro_text` | field_nc_service_cards_micro_text |
| 39255 | 312 | `nc_service_cards_2_target_kind` | term |
| 39256 | 312 | `_nc_service_cards_2_target_kind` | field_nc_service_cards_target_kind |
| 39257 | 312 | `nc_service_cards_2_target_page` |  |
| 39258 | 312 | `_nc_service_cards_2_target_page` | field_nc_service_cards_target_page |
| 39259 | 312 | `nc_service_cards_2_target_term` | 75 |
| 39260 | 312 | `_nc_service_cards_2_target_term` | field_nc_service_cards_target_term |
| 39261 | 312 | `nc_service_cards` | 3 |
| 39262 | 312 | `_nc_service_cards` | field_nc_service_cards |
| 39263 | 312 | `nc_technology_eyebrow` | Công nghệ |
| 39264 | 312 | `_nc_technology_eyebrow` | field_nc_technology_eyebrow |
| 39265 | 312 | `nc_technology_heading` | Nền tảng&lt;br&gt; tạo nên khác biệt |
| 39266 | 312 | `_nc_technology_heading` | field_nc_technology_heading |
| 39267 | 312 | `nc_technology_items_0_mark` | react |
| 39268 | 312 | `_nc_technology_items_0_mark` | field_nc_technology_items_mark |
| 39269 | 312 | `nc_technology_items_0_label` | React |
| 39270 | 312 | `_nc_technology_items_0_label` | field_nc_technology_items_label |
| 39271 | 312 | `nc_technology_items_1_mark` | laravel |
| 39272 | 312 | `_nc_technology_items_1_mark` | field_nc_technology_items_mark |
| 39273 | 312 | `nc_technology_items_1_label` | Laravel |
| 39274 | 312 | `_nc_technology_items_1_label` | field_nc_technology_items_label |
| 39275 | 312 | `nc_technology_items_2_mark` | node |
| 39276 | 312 | `_nc_technology_items_2_mark` | field_nc_technology_items_mark |
| 39277 | 312 | `nc_technology_items_2_label` | node.js |
| 39278 | 312 | `_nc_technology_items_2_label` | field_nc_technology_items_label |
| 39279 | 312 | `nc_technology_items_3_mark` | aws |
| 39280 | 312 | `_nc_technology_items_3_mark` | field_nc_technology_items_mark |
| 39281 | 312 | `nc_technology_items_3_label` | aws |
| 39282 | 312 | `_nc_technology_items_3_label` | field_nc_technology_items_label |
| 39283 | 312 | `nc_technology_items_4_mark` | mysql |
| 39284 | 312 | `_nc_technology_items_4_mark` | field_nc_technology_items_mark |
| 39285 | 312 | `nc_technology_items_4_label` | MySQL |
| 39286 | 312 | `_nc_technology_items_4_label` | field_nc_technology_items_label |
| 39287 | 312 | `nc_technology_items_5_mark` | docker |
| 39288 | 312 | `_nc_technology_items_5_mark` | field_nc_technology_items_mark |
| 39289 | 312 | `nc_technology_items_5_label` | docker |
| 39290 | 312 | `_nc_technology_items_5_label` | field_nc_technology_items_label |
| 39291 | 312 | `nc_technology_items_6_mark` | figma |
| 39292 | 312 | `_nc_technology_items_6_mark` | field_nc_technology_items_mark |
| 39293 | 312 | `nc_technology_items_6_label` | Figma |
| 39294 | 312 | `_nc_technology_items_6_label` | field_nc_technology_items_label |
| 39295 | 312 | `nc_technology_items_7_mark` | cicd |
| 39296 | 312 | `_nc_technology_items_7_mark` | field_nc_technology_items_mark |
| 39297 | 312 | `nc_technology_items_7_label` | CI/CD |
| 39298 | 312 | `_nc_technology_items_7_label` | field_nc_technology_items_label |
| 39299 | 312 | `nc_technology_items` | 8 |
| 39300 | 312 | `_nc_technology_items` | field_nc_technology_items |
| 39301 | 312 | `nc_projects_eyebrow` | Sản phẩm nổi bật |
| 39302 | 312 | `_nc_projects_eyebrow` | field_nc_projects_eyebrow |
| 39303 | 312 | `nc_projects_heading` | Giải pháp được tin chọn |
| 39304 | 312 | `_nc_projects_heading` | field_nc_projects_heading |
| 39305 | 312 | `nc_projects_contact_label` | Trao đổi về dự án  |
| 39306 | 312 | `_nc_projects_contact_label` | field_nc_projects_contact_label |
| 39307 | 312 | `nc_featured_projects_0_object` |  |
| 39308 | 312 | `_nc_featured_projects_0_object` | field_nc_featured_projects_object |
| 39309 | 312 | `nc_featured_projects_0_visual_variant` | portal |
| 39310 | 312 | `_nc_featured_projects_0_visual_variant` | field_nc_featured_projects_visual_variant |
| 39311 | 312 | `nc_featured_projects_0_card_label` | Nextcore Portal |
| 39312 | 312 | `_nc_featured_projects_0_card_label` | field_nc_featured_projects_card_label |
| 39313 | 312 | `nc_featured_projects_0_card_summary` | Hệ thống quản lý doanh nghiệp thông minh. |
| 39314 | 312 | `_nc_featured_projects_0_card_summary` | field_nc_featured_projects_card_summary |
| 39315 | 312 | `nc_featured_projects_0_card_image` | 1535 |
| 39316 | 312 | `_nc_featured_projects_0_card_image` | field_nc_featured_projects_card_image |
| 39317 | 312 | `nc_featured_projects_1_object` | 1510 |
| 39318 | 312 | `_nc_featured_projects_1_object` | field_nc_featured_projects_object |
| 39319 | 312 | `nc_featured_projects_1_visual_variant` | olympia |
| 39320 | 312 | `_nc_featured_projects_1_visual_variant` | field_nc_featured_projects_visual_variant |
| 39321 | 312 | `nc_featured_projects_1_card_label` | Trường Doanh nhân Top Olympia |
| 39322 | 312 | `_nc_featured_projects_1_card_label` | field_nc_featured_projects_card_label |
| 39323 | 312 | `nc_featured_projects_1_card_summary` | Website đào tạo doanh nhân. |
| 39324 | 312 | `_nc_featured_projects_1_card_summary` | field_nc_featured_projects_card_summary |
| 39325 | 312 | `nc_featured_projects_1_card_image` | 1820 |
| 39326 | 312 | `_nc_featured_projects_1_card_image` | field_nc_featured_projects_card_image |
| 39327 | 312 | `nc_featured_projects_2_object` | 1548 |
| 39328 | 312 | `_nc_featured_projects_2_object` | field_nc_featured_projects_object |
| 39329 | 312 | `nc_featured_projects_2_visual_variant` | affiliate |
| 39330 | 312 | `_nc_featured_projects_2_visual_variant` | field_nc_featured_projects_visual_variant |
| 39331 | 312 | `nc_featured_projects_2_card_label` | WordPress Plugin - Affiliate |
| 39332 | 312 | `_nc_featured_projects_2_card_label` | field_nc_featured_projects_card_label |
| 39333 | 312 | `nc_featured_projects_2_card_summary` | Giải pháp tiếp thị liên kết hiệu quả. |
| 39334 | 312 | `_nc_featured_projects_2_card_summary` | field_nc_featured_projects_card_summary |
| 39335 | 312 | `nc_featured_projects_2_card_image` | 1541 |
| 39336 | 312 | `_nc_featured_projects_2_card_image` | field_nc_featured_projects_card_image |
| 39337 | 312 | `nc_featured_projects` | 3 |
| 39338 | 312 | `_nc_featured_projects` | field_nc_featured_projects |
| 39339 | 312 | `nc_partner_eyebrow` | Đối tác chiến lược |
| 39340 | 312 | `_nc_partner_eyebrow` | field_nc_partner_eyebrow |
| 39341 | 312 | `nc_partner_name` | GM Solutions |
| 39342 | 312 | `_nc_partner_name` | field_nc_partner_name |
| 39343 | 312 | `nc_partner_logo` | 1789 |
| 39344 | 312 | `_nc_partner_logo` | field_nc_partner_logo |
| 39345 | 312 | `nc_partner_description` | Cùng nhau kiến tạo&lt;br&gt;những giá trị bền vững. |
| 39346 | 312 | `_nc_partner_description` | field_nc_partner_description |
| 39347 | 312 | `nc_partner_motto` | Stronger&lt;br&gt; together&lt;br&gt; for a brighter&lt;br&gt; tomorrow |
| 39348 | 312 | `_nc_partner_motto` | field_nc_partner_motto |
| 39349 | 312 | `nc_partner_link_label` | Tìm hiểu thêm |
| 39350 | 312 | `_nc_partner_link_label` | field_nc_partner_link_label |
| 39351 | 312 | `nc_partner_link` | a:3:{s:5:"title";s:17:"Tìm hiểu thêm";s:3:"url";s:20:"https://gm-group.vn/";s:6:"target";s:0:"";} |
| 39352 | 312 | `_nc_partner_link` | field_nc_partner_link |
| 39353 | 312 | `nc_testimonials_eyebrow` | KHÁCH HÀNG NÓI VỀ NEXTCORE |
| 39354 | 312 | `_nc_testimonials_eyebrow` | field_nc_testimonials_eyebrow |
| 39355 | 312 | `nc_testimonials_heading` | Đánh giá từ khách hàng |
| 39356 | 312 | `_nc_testimonials_heading` | field_nc_testimonials_heading |
| 39357 | 312 | `nc_testimonials_intro` | Những chia sẻ thực tế từ khách hàng đang đồng hành cùng Nextcore. |
| 39358 | 312 | `_nc_testimonials_intro` | field_nc_testimonials_intro |
| 39359 | 312 | `nc_testimonials_0_name` | Công ty An Tâm Việt |
| 39360 | 312 | `_nc_testimonials_0_name` | field_nc_testimonials_name |
| 39361 | 312 | `nc_testimonials_0_project_label` | Dự án Plugin Affiliate |
| 39362 | 312 | `_nc_testimonials_0_project_label` | field_nc_testimonials_project_label |
| 39363 | 312 | `nc_testimonials_0_quote` | Nextcore để lại ấn tượng mạnh mẽ với đội ngũ hỗ trợ chuyên nghiệp, luôn đồng hành từ khâu tư vấn ban đầu đến việc sắp xếp kế hoạch và triển khai dự án. Phản hồi nhanh chóng, thông tin rõ ràng giúp chúng tôi hoàn toàn yên tâm khi làm việc cùng họ. Đặc biệt, sự uy tín và cam kết trách nhiệm với deadline của Nextcore là yếu tố nổi bật. Không thể không nhắc đến anh Tú, Project Manager của đội, người luôn tận tâm tư vấn và hỗ trợ nhiệt tình, mang đến trải nghiệm làm việc tuyệt vời.&lt;br&gt;Nextcore không chỉ là một đối tác, mà còn là một người bạn đồng hành đáng tin cậy trên hành trình phát triển của chúng tôi! |
| 39364 | 312 | `_nc_testimonials_0_quote` | field_nc_testimonials_quote |
| 39365 | 312 | `nc_testimonials_0_image` | 1578 |
| 39366 | 312 | `_nc_testimonials_0_image` | field_nc_testimonials_image |
| 39367 | 312 | `nc_testimonials_1_name` | Phạm Việt Hùng |
| 39368 | 312 | `_nc_testimonials_1_name` | field_nc_testimonials_name |
| 39369 | 312 | `nc_testimonials_1_project_label` | Dự án Tạo template ebook |
| 39370 | 312 | `_nc_testimonials_1_project_label` | field_nc_testimonials_project_label |
| 39371 | 312 | `nc_testimonials_1_quote` | Hoàn thành công việc nhanh và chất lượng. Nhiệt tình hỗ trợ sau khi hoàn thành công việc |
| 39372 | 312 | `_nc_testimonials_1_quote` | field_nc_testimonials_quote |
| 39373 | 312 | `nc_testimonials_1_image` | 1580 |
| 39374 | 312 | `_nc_testimonials_1_image` | field_nc_testimonials_image |
| 39375 | 312 | `nc_testimonials_2_name` | Nguyễn Phương Trà My |
| 39376 | 312 | `_nc_testimonials_2_name` | field_nc_testimonials_name |
| 39377 | 312 | `nc_testimonials_2_project_label` | Dự án Convert nghiệp vụ sang sơ đồ khối |
| 39378 | 312 | `_nc_testimonials_2_project_label` | field_nc_testimonials_project_label |
| 39379 | 312 | `nc_testimonials_2_quote` | Công ty Phần mềm Next Core làm dự án của tôi rất chuyên nghiệp, thực hiện đúng yêu cầu của khách hàng và hoàn thành công việc sớm hơn thời gian quy định, đảm bảo chất lượng. Các bạn dev của Công ty hỗ trợ tôi rất nhiệt tình trong thời gian thực hiện yêu cầu của tôi. Highly recommend cho các khách hàng trong lĩnh vực IT, phần mềm, design. |
| 39380 | 312 | `_nc_testimonials_2_quote` | field_nc_testimonials_quote |
| 39381 | 312 | `nc_testimonials_2_image` | 1581 |
| 39382 | 312 | `_nc_testimonials_2_image` | field_nc_testimonials_image |
| 39383 | 312 | `nc_testimonials_3_name` | Bé khỏe bé vui |
| 39384 | 312 | `_nc_testimonials_3_name` | field_nc_testimonials_name |
| 39385 | 312 | `nc_testimonials_3_project_label` | Dự án phục hồi website bị tấn công |
| 39386 | 312 | `_nc_testimonials_3_project_label` | field_nc_testimonials_project_label |
| 39387 | 312 | `nc_testimonials_3_quote` | Highly appreciate your working spirit. I am pleased to choose you as partners. We will continue to cooperate in the future when there are new projects. |
| 39388 | 312 | `_nc_testimonials_3_quote` | field_nc_testimonials_quote |
| 39389 | 312 | `nc_testimonials_3_image` | 1579 |
| 39390 | 312 | `_nc_testimonials_3_image` | field_nc_testimonials_image |
| 39391 | 312 | `nc_testimonials` | 4 |
| 39392 | 312 | `_nc_testimonials` | field_nc_testimonials |
| 39393 | 312 | `nc_team_eyebrow` | ĐỘI NGŨ |
| 39394 | 312 | `_nc_team_eyebrow` | field_nc_team_eyebrow |
| 39395 | 312 | `nc_team_heading` | Đội ngũ |
| 39396 | 312 | `_nc_team_heading` | field_nc_team_heading |
| 39397 | 312 | `nc_team_intro` | Đội ngũ chuyên môn cao, tận tâm và luôn sẵn sàng đồng hành. |
| 39398 | 312 | `_nc_team_intro` | field_nc_team_intro |
| 39399 | 312 | `nc_team_members_0_name` | Nguyễn Văn Hiền |
| 39400 | 312 | `_nc_team_members_0_name` | field_nc_team_members_name |
| 39401 | 312 | `nc_team_members_0_role` | Chief Executive Officer |
| 39402 | 312 | `_nc_team_members_0_role` | field_nc_team_members_role |
| 39403 | 312 | `nc_team_members_0_image` | 1799 |
| 39404 | 312 | `_nc_team_members_0_image` | field_nc_team_members_image |
| 39405 | 312 | `nc_team_members_0_email` | hiennv@nextcore.vn |
| 39406 | 312 | `_nc_team_members_0_email` | field_nc_team_members_email |
| 39407 | 312 | `nc_team_members_0_phone` | +84378962625 |
| 39408 | 312 | `_nc_team_members_0_phone` | field_nc_team_members_phone |
| 39409 | 312 | `nc_team_members_1_name` | Trần Đức Anh |
| 39410 | 312 | `_nc_team_members_1_name` | field_nc_team_members_name |
| 39411 | 312 | `nc_team_members_1_role` | Chief Technology Officer |
| 39412 | 312 | `_nc_team_members_1_role` | field_nc_team_members_role |
| 39413 | 312 | `nc_team_members_1_image` | 527 |
| 39414 | 312 | `_nc_team_members_1_image` | field_nc_team_members_image |
| 39415 | 312 | `nc_team_members_1_email` | anhtd@nextcore.vn |
| 39416 | 312 | `_nc_team_members_1_email` | field_nc_team_members_email |
| 39417 | 312 | `nc_team_members_1_phone` | +84976748059 |
| 39418 | 312 | `_nc_team_members_1_phone` | field_nc_team_members_phone |
| 39419 | 312 | `nc_team_members_2_name` | Phan Thanh Tú |
| 39420 | 312 | `_nc_team_members_2_name` | field_nc_team_members_name |
| 39421 | 312 | `nc_team_members_2_role` | Project Manager |
| 39422 | 312 | `_nc_team_members_2_role` | field_nc_team_members_role |
| 39423 | 312 | `nc_team_members_2_image` | 526 |
| 39424 | 312 | `_nc_team_members_2_image` | field_nc_team_members_image |
| 39425 | 312 | `nc_team_members_2_email` | tupt@nextcore.vn |
| 39426 | 312 | `_nc_team_members_2_email` | field_nc_team_members_email |
| 39427 | 312 | `nc_team_members_2_phone` | +84979525694 |
| 39428 | 312 | `_nc_team_members_2_phone` | field_nc_team_members_phone |
| 39429 | 312 | `nc_team_members` | 3 |
| 39430 | 312 | `_nc_team_members` | field_nc_team_members |
| 39431 | 312 | `nc_cta_heading` | Sẵn sàng bắt đầu dự án của bạn? |
| 39432 | 312 | `_nc_cta_heading` | field_nc_cta_heading |
| 39433 | 312 | `nc_cta_description` | Hãy để Nextcore đồng hành cùng bạn trên hành trình chuyển đổi số. |
| 39434 | 312 | `_nc_cta_description` | field_nc_cta_description |
| 39435 | 312 | `nc_cta_button_label` | Liên hệ ngay |
| 39436 | 312 | `_nc_cta_button_label` | field_nc_cta_button_label |
| 39437 | 312 | `nc_cta_motto` | New&lt;br&gt;ideas.&lt;br&gt;Higher&lt;br&gt;possibilities. |
| 39438 | 312 | `_nc_cta_motto` | field_nc_cta_motto |
| 39439 | 312 | `nc_blog_eyebrow` | Tin tức |
| 39440 | 312 | `_nc_blog_eyebrow` | field_nc_blog_eyebrow |
| 39441 | 312 | `nc_blog_heading` | Cập nhật mới nhất |
| 39442 | 312 | `_nc_blog_heading` | field_nc_blog_heading |
| 39443 | 312 | `nc_blog_note` | Góc nhìn & kiến thức |
| 39444 | 312 | `_nc_blog_note` | field_nc_blog_note |
| 39445 | 1829 | `_menu_item_type` | custom |
| 39446 | 1829 | `_menu_item_menu_item_parent` | 0 |
| 39447 | 1829 | `_menu_item_object_id` | 1829 |
| 39448 | 1829 | `_menu_item_object` | custom |
| 39449 | 1829 | `_menu_item_target` |  |
| 39450 | 1829 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39451 | 1829 | `_menu_item_xfn` |  |
| 39452 | 1829 | `_menu_item_url` | #about |
| 39453 | 1829 | `_nextcore_setup_key` | about |
| 39454 | 1830 | `_menu_item_type` | custom |
| 39455 | 1830 | `_menu_item_menu_item_parent` | 0 |
| 39456 | 1830 | `_menu_item_object_id` | 1830 |
| 39457 | 1830 | `_menu_item_object` | custom |
| 39458 | 1830 | `_menu_item_target` |  |
| 39459 | 1830 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39460 | 1830 | `_menu_item_xfn` |  |
| 39461 | 1830 | `_menu_item_url` | #services |
| 39462 | 1830 | `_nextcore_setup_key` | services |
| 39463 | 1831 | `_menu_item_type` | post_type |
| 39464 | 1831 | `_menu_item_menu_item_parent` | 1830 |
| 39465 | 1831 | `_menu_item_object_id` | 1133 |
| 39466 | 1831 | `_menu_item_object` | page |
| 39467 | 1831 | `_menu_item_target` |  |
| 39468 | 1831 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39469 | 1831 | `_menu_item_xfn` |  |
| 39470 | 1831 | `_menu_item_url` |  |
| 39471 | 1831 | `_nextcore_setup_key` | outsource |
| 39472 | 1832 | `_menu_item_type` | taxonomy |
| 39473 | 1832 | `_menu_item_menu_item_parent` | 1830 |
| 39474 | 1832 | `_menu_item_object_id` | 74 |
| 39475 | 1832 | `_menu_item_object` | danh-muc-dich-vu |
| 39476 | 1832 | `_menu_item_target` |  |
| 39477 | 1832 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39478 | 1832 | `_menu_item_xfn` |  |
| 39479 | 1832 | `_menu_item_url` |  |
| 39480 | 1832 | `_nextcore_setup_key` | consulting |
| 39481 | 1833 | `_menu_item_type` | taxonomy |
| 39482 | 1833 | `_menu_item_menu_item_parent` | 1830 |
| 39483 | 1833 | `_menu_item_object_id` | 75 |
| 39484 | 1833 | `_menu_item_object` | danh-muc-dich-vu |
| 39485 | 1833 | `_menu_item_target` |  |
| 39486 | 1833 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39487 | 1833 | `_menu_item_xfn` |  |
| 39488 | 1833 | `_menu_item_url` |  |
| 39489 | 1833 | `_nextcore_setup_key` | personal |
| 39490 | 1834 | `_menu_item_type` | taxonomy |
| 39491 | 1834 | `_menu_item_menu_item_parent` | 1830 |
| 39492 | 1834 | `_menu_item_object_id` | 81 |
| 39493 | 1834 | `_menu_item_object` | danh-muc-dich-vu |
| 39494 | 1834 | `_menu_item_target` |  |
| 39495 | 1834 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39496 | 1834 | `_menu_item_xfn` |  |
| 39497 | 1834 | `_menu_item_url` |  |
| 39498 | 1834 | `_nextcore_setup_key` | products |
| 39499 | 1835 | `_menu_item_type` | custom |
| 39500 | 1835 | `_menu_item_menu_item_parent` | 0 |
| 39501 | 1835 | `_menu_item_object_id` | 1835 |
| 39502 | 1835 | `_menu_item_object` | custom |
| 39503 | 1835 | `_menu_item_target` |  |
| 39504 | 1835 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39505 | 1835 | `_menu_item_xfn` |  |
| 39506 | 1835 | `_menu_item_url` | #technology |
| 39507 | 1835 | `_nextcore_setup_key` | technology |
| 39508 | 1836 | `_menu_item_type` | custom |
| 39509 | 1836 | `_menu_item_menu_item_parent` | 0 |
| 39510 | 1836 | `_menu_item_object_id` | 1836 |
| 39511 | 1836 | `_menu_item_object` | custom |
| 39512 | 1836 | `_menu_item_target` |  |
| 39513 | 1836 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39514 | 1836 | `_menu_item_xfn` |  |
| 39515 | 1836 | `_menu_item_url` | #projects |
| 39516 | 1836 | `_nextcore_setup_key` | projects |
| 39517 | 1837 | `_menu_item_type` | custom |
| 39518 | 1837 | `_menu_item_menu_item_parent` | 0 |
| 39519 | 1837 | `_menu_item_object_id` | 1837 |
| 39520 | 1837 | `_menu_item_object` | custom |
| 39521 | 1837 | `_menu_item_target` |  |
| 39522 | 1837 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39523 | 1837 | `_menu_item_xfn` |  |
| 39524 | 1837 | `_menu_item_url` | #team |
| 39525 | 1837 | `_nextcore_setup_key` | team |
| 39526 | 1838 | `_menu_item_type` | custom |
| 39527 | 1838 | `_menu_item_menu_item_parent` | 0 |
| 39528 | 1838 | `_menu_item_object_id` | 1838 |
| 39529 | 1838 | `_menu_item_object` | custom |
| 39530 | 1838 | `_menu_item_target` |  |
| 39531 | 1838 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39532 | 1838 | `_menu_item_xfn` |  |
| 39533 | 1838 | `_menu_item_url` | #blog |
| 39534 | 1838 | `_nextcore_setup_key` | blog |
| 39535 | 1839 | `_menu_item_type` | taxonomy |
| 39536 | 1839 | `_menu_item_menu_item_parent` | 1838 |
| 39537 | 1839 | `_menu_item_object_id` | 1 |
| 39538 | 1839 | `_menu_item_object` | category |
| 39539 | 1839 | `_menu_item_target` |  |
| 39540 | 1839 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39541 | 1839 | `_menu_item_xfn` |  |
| 39542 | 1839 | `_menu_item_url` |  |
| 39543 | 1839 | `_nextcore_setup_key` | knowledge |
| 39544 | 1840 | `_menu_item_type` | taxonomy |
| 39545 | 1840 | `_menu_item_menu_item_parent` | 1838 |
| 39546 | 1840 | `_menu_item_object_id` | 76 |
| 39547 | 1840 | `_menu_item_object` | category |
| 39548 | 1840 | `_menu_item_target` |  |
| 39549 | 1840 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39550 | 1840 | `_menu_item_xfn` |  |
| 39551 | 1840 | `_menu_item_url` |  |
| 39552 | 1840 | `_nextcore_setup_key` | company |
| 39553 | 1841 | `_menu_item_type` | custom |
| 39554 | 1841 | `_menu_item_menu_item_parent` | 0 |
| 39555 | 1841 | `_menu_item_object_id` | 1841 |
| 39556 | 1841 | `_menu_item_object` | custom |
| 39557 | 1841 | `_menu_item_target` |  |
| 39558 | 1841 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39559 | 1841 | `_menu_item_xfn` |  |
| 39560 | 1841 | `_menu_item_url` | #contact |
| 39561 | 1841 | `_nextcore_setup_key` | contact |
| 39562 | 1842 | `_menu_item_type` | custom |
| 39563 | 1842 | `_menu_item_menu_item_parent` | 0 |
| 39564 | 1842 | `_menu_item_object_id` | 1842 |
| 39565 | 1842 | `_menu_item_object` | custom |
| 39566 | 1842 | `_menu_item_target` |  |
| 39567 | 1842 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39568 | 1842 | `_menu_item_xfn` |  |
| 39569 | 1842 | `_menu_item_url` | #about |
| 39570 | 1842 | `_nextcore_setup_key` | about |
| 39571 | 1843 | `_menu_item_type` | custom |
| 39572 | 1843 | `_menu_item_menu_item_parent` | 0 |
| 39573 | 1843 | `_menu_item_object_id` | 1843 |
| 39574 | 1843 | `_menu_item_object` | custom |
| 39575 | 1843 | `_menu_item_target` |  |
| 39576 | 1843 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39577 | 1843 | `_menu_item_xfn` |  |
| 39578 | 1843 | `_menu_item_url` | #services |
| 39579 | 1843 | `_nextcore_setup_key` | services |
| 39580 | 1844 | `_menu_item_type` | post_type |
| 39581 | 1844 | `_menu_item_menu_item_parent` | 1843 |
| 39582 | 1844 | `_menu_item_object_id` | 1133 |
| 39583 | 1844 | `_menu_item_object` | page |
| 39584 | 1844 | `_menu_item_target` |  |
| 39585 | 1844 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39586 | 1844 | `_menu_item_xfn` |  |
| 39587 | 1844 | `_menu_item_url` |  |
| 39588 | 1844 | `_nextcore_setup_key` | outsource |
| 39589 | 1845 | `_menu_item_type` | taxonomy |
| 39590 | 1845 | `_menu_item_menu_item_parent` | 1843 |
| 39591 | 1845 | `_menu_item_object_id` | 74 |
| 39592 | 1845 | `_menu_item_object` | danh-muc-dich-vu |
| 39593 | 1845 | `_menu_item_target` |  |
| 39594 | 1845 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39595 | 1845 | `_menu_item_xfn` |  |
| 39596 | 1845 | `_menu_item_url` |  |
| 39597 | 1845 | `_nextcore_setup_key` | consulting |
| 39598 | 1846 | `_menu_item_type` | taxonomy |
| 39599 | 1846 | `_menu_item_menu_item_parent` | 1843 |
| 39600 | 1846 | `_menu_item_object_id` | 75 |
| 39601 | 1846 | `_menu_item_object` | danh-muc-dich-vu |
| 39602 | 1846 | `_menu_item_target` |  |
| 39603 | 1846 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39604 | 1846 | `_menu_item_xfn` |  |
| 39605 | 1846 | `_menu_item_url` |  |
| 39606 | 1846 | `_nextcore_setup_key` | personal |
| 39607 | 1847 | `_menu_item_type` | taxonomy |
| 39608 | 1847 | `_menu_item_menu_item_parent` | 1843 |
| 39609 | 1847 | `_menu_item_object_id` | 81 |
| 39610 | 1847 | `_menu_item_object` | danh-muc-dich-vu |
| 39611 | 1847 | `_menu_item_target` |  |
| 39612 | 1847 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39613 | 1847 | `_menu_item_xfn` |  |
| 39614 | 1847 | `_menu_item_url` |  |
| 39615 | 1847 | `_nextcore_setup_key` | products |
| 39616 | 1848 | `_menu_item_type` | custom |
| 39617 | 1848 | `_menu_item_menu_item_parent` | 0 |
| 39618 | 1848 | `_menu_item_object_id` | 1848 |
| 39619 | 1848 | `_menu_item_object` | custom |
| 39620 | 1848 | `_menu_item_target` |  |
| 39621 | 1848 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39622 | 1848 | `_menu_item_xfn` |  |
| 39623 | 1848 | `_menu_item_url` | #technology |
| 39624 | 1848 | `_nextcore_setup_key` | technology |
| 39625 | 1849 | `_menu_item_type` | custom |
| 39626 | 1849 | `_menu_item_menu_item_parent` | 0 |
| 39627 | 1849 | `_menu_item_object_id` | 1849 |
| 39628 | 1849 | `_menu_item_object` | custom |
| 39629 | 1849 | `_menu_item_target` |  |
| 39630 | 1849 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39631 | 1849 | `_menu_item_xfn` |  |
| 39632 | 1849 | `_menu_item_url` | #projects |
| 39633 | 1849 | `_nextcore_setup_key` | projects |
| 39634 | 1850 | `_menu_item_type` | custom |
| 39635 | 1850 | `_menu_item_menu_item_parent` | 0 |
| 39636 | 1850 | `_menu_item_object_id` | 1850 |
| 39637 | 1850 | `_menu_item_object` | custom |
| 39638 | 1850 | `_menu_item_target` |  |
| 39639 | 1850 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39640 | 1850 | `_menu_item_xfn` |  |
| 39641 | 1850 | `_menu_item_url` | #partner |
| 39642 | 1850 | `_nextcore_setup_key` | partner |
| 39643 | 1851 | `_menu_item_type` | custom |
| 39644 | 1851 | `_menu_item_menu_item_parent` | 0 |
| 39645 | 1851 | `_menu_item_object_id` | 1851 |
| 39646 | 1851 | `_menu_item_object` | custom |
| 39647 | 1851 | `_menu_item_target` |  |
| 39648 | 1851 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39649 | 1851 | `_menu_item_xfn` |  |
| 39650 | 1851 | `_menu_item_url` | #testimonials |
| 39651 | 1851 | `_nextcore_setup_key` | testimonials |
| 39652 | 1852 | `_menu_item_type` | custom |
| 39653 | 1852 | `_menu_item_menu_item_parent` | 0 |
| 39654 | 1852 | `_menu_item_object_id` | 1852 |
| 39655 | 1852 | `_menu_item_object` | custom |
| 39656 | 1852 | `_menu_item_target` |  |
| 39657 | 1852 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39658 | 1852 | `_menu_item_xfn` |  |
| 39659 | 1852 | `_menu_item_url` | #team |
| 39660 | 1852 | `_nextcore_setup_key` | team |
| 39661 | 1853 | `_menu_item_type` | custom |
| 39662 | 1853 | `_menu_item_menu_item_parent` | 0 |
| 39663 | 1853 | `_menu_item_object_id` | 1853 |
| 39664 | 1853 | `_menu_item_object` | custom |
| 39665 | 1853 | `_menu_item_target` |  |
| 39666 | 1853 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39667 | 1853 | `_menu_item_xfn` |  |
| 39668 | 1853 | `_menu_item_url` | #blog |
| 39669 | 1853 | `_nextcore_setup_key` | blog |
| 39670 | 1854 | `_menu_item_type` | taxonomy |
| 39671 | 1854 | `_menu_item_menu_item_parent` | 1853 |
| 39672 | 1854 | `_menu_item_object_id` | 1 |
| 39673 | 1854 | `_menu_item_object` | category |
| 39674 | 1854 | `_menu_item_target` |  |
| 39675 | 1854 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39676 | 1854 | `_menu_item_xfn` |  |
| 39677 | 1854 | `_menu_item_url` |  |
| 39678 | 1854 | `_nextcore_setup_key` | knowledge |
| 39679 | 1855 | `_menu_item_type` | taxonomy |
| 39680 | 1855 | `_menu_item_menu_item_parent` | 1853 |
| 39681 | 1855 | `_menu_item_object_id` | 76 |
| 39682 | 1855 | `_menu_item_object` | category |
| 39683 | 1855 | `_menu_item_target` |  |
| 39684 | 1855 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39685 | 1855 | `_menu_item_xfn` |  |
| 39686 | 1855 | `_menu_item_url` |  |
| 39687 | 1855 | `_nextcore_setup_key` | company |
| 39688 | 1856 | `_menu_item_type` | custom |
| 39689 | 1856 | `_menu_item_menu_item_parent` | 0 |
| 39690 | 1856 | `_menu_item_object_id` | 1856 |
| 39691 | 1856 | `_menu_item_object` | custom |
| 39692 | 1856 | `_menu_item_target` |  |
| 39693 | 1856 | `_menu_item_classes` | a:1:{i:0;s:0:"";} |
| 39694 | 1856 | `_menu_item_xfn` |  |
| 39695 | 1856 | `_menu_item_url` | #contact |
| 39696 | 1856 | `_nextcore_setup_key` | contact |

## Bảo toàn và giới hạn

- Diff không có thay đổi record Elementor, Page/Post cũ, taxonomy nội dung, menu 69 hoặc cấu hình theme/plugin hiện hành.
- Script CLI kiểm tra DB_HOST loopback và đúng workspace local trước khi mở scope ghi. Theme/plugin chỉ đổi trong request kiểm tra; không gọi switch_theme.
- Setup không được include trong runtime theme. Không activation hook, page-load seeding, dictionary import hoặc integration rollout.
- Routine mặc định dry-run, chỉ tạo field còn thiếu; giá trị admin đã có được giữ nguyên. Menu items do routine tạo có `_nextcore_setup_key`, chạy lại không append trùng. Nếu menu trùng tên không do routine quản lý hoặc assignment khác thì dừng, không overwrite.
- Journal đủ để lập migration ngược có review: giữ dữ liệu admin mới, chỉ rollback các record Phase 5 đã xác minh còn nguyên. Không cung cấp lệnh xóa hàng loạt hoặc import database local lên production.
