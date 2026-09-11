# Multilingual strategy — TranslatePress

Ngày 10/09/2026. Giữ kiến trúc thực tế TranslatePress, không chuyển plugin, không duplicate pages. Chưa tạo bản dịch/ghi dictionary trong Phase 2.

## 1. Baseline được giữ

TranslatePress Multilingual 2.8.7 + Business 1.4.6 trong local audit; default vi, EN en_US. VI ở root, EN prefix `/en/`. Menu language_switcher và URL converter do plugin quản lý; floating language switcher off. SEO Pack/navigation-by-language add-ons local off. Không tự bật add-ons, không tự dịch slugs hoặc tạo `/vi/` mới.

Production là data truth; phải kiểm tra snapshot mới trước reuse translations. Các record dictionary VI→EN cũ được giữ nguyên; không xóa khi theme activate, không thay meta ACF nguồn bằng bản EN. EN thiếu dịch sẵn có trên các page cũ không được “sửa” bằng phát minh bài EN duplicate. Scope đã chốt: thêm dịch **homepage mới**, giữ existing translations của phần còn lại.

## 2. Ownership cho text

| Loại | Nơi lưu / cách render | Dịch |
|---|---|---|
| UI cố định: menu toggle, search labels, mode switch, dialog close, carousel prev/next, empty state | Theme gettext với domain nextcore-theme, server-rendered | WordPress i18n, TranslatePress gettext integration; không ACF fields |
| Editorial homepage | Một bộ ACF values VI/nguồn trên existing front page/options; PHP render HTML | TranslatePress visual translation trên output |
| Post title/content/excerpt/categories | Core data qua WP APIs/the_content | Existing TranslatePress output translations |
| Proper names, email, phone, IDs, SVG keys, date value | Shared source, escape phù hợp | Không dịch IDs/URLs/contacts; date label/display được localize |
| Image alt/caption | Attachment metadata được renderer dùng | Kiểm tra output/SEO Pack capability; không giả định field copy setting giải quyết alt |
| Third-party iframe/widget text | Provider-controlled | Không bảo đảm TranslatePress dịch trong iframe; giữ provider config hợp lệ |

ACF schema column HTML nghĩa là **có điểm render để TranslatePress dịch**, không phải native ACF per-language metadata. Cột Shared/Copy trong schema không tạo thao tác copy giữa pages.

Các group G01/G02/G03 labels/G04/G05 heading/G06 card override/G07/G08 quote/project labels/G09 role/G10/G11 label/G12 footer copy/G14 headings đều có text output. G13 media IDs và G15 technical identifiers shared, không dịch. G12 canonical address giữ nguyên chuỗi địa chỉ đã chốt; nhãn xung quanh dịch.

## 3. Stable markup và dynamic text

- Hero giữ title fragments/span order ổn định; cung cấp translation units cho cả cụm nếu dấu/ngữ pháp EN cần gộp. Không đổi node boundaries chỉ để animate từng chữ. EN phải review line wrapping theo Be Vietnam Pro và chiều rộng thật, không giả định hai dòng VI phù hợp mọi bản EN.
- About giữ ba paragraphs/strong emphasis; date có datetime machine value và localized visible text. Services/project summaries giữ một node/cụm ổn định; không nối sentence từ nhiều JS fragments.
- Quote đầy đủ render ngay server-side trong testimonial card; CSS clamp chỉ che bớt hiển thị. Modal có thể đưa cùng **node đã dịch** vào dialog lúc mở rồi trả lại chỗ cũ, hoặc lấy text của node dịch tại thời điểm click. Không lấy raw Vietnamese JSON để overwrite dialog sau translate. Nếu clone cần bỏ IDs trùng và test plugin observer; ưu tiên tái sử dụng nội dung đã render.
- Dots/control labels server-rendered hoặc lấy name đã dịch từ DOM; JS chỉ điều khiển trạng thái, không hardcode “Xem thêm”, “Không tìm thấy…” hay mô tả dự án Việt.
- Blog/project cards link detail thật; xóa dependency business text trong object `details` của preview khi triển khai. Partner behavior pending Q5; nếu vẫn modal thì cũng cần text SSR.
- Mode change chỉ đổi colors/media. Không re-render text HTML theo mode, không ghi lại innerHTML business text; như vậy không làm mất dịch khi click switch.

TranslatePress nhận chuỗi thay đổi như một unit mới và hỗ trợ translation blocks cho cụm HTML; vì vậy reuse chữ cũ không đồng nghĩa tự động reuse mọi match sau đổi markup. [Translation Editor](https://translatepress.com/docs/translation-editor/). Việc tổ chức stable markup ở đây là thiết kế của theme, không cam kết plugin sẽ match 100% chuỗi cũ.

## 4. URL và language switcher

- Templates resolve links qua get_permalink()/get_term_link()/home_url() và TranslatePress converter/menu integration. Không nối `/en/` thủ công và không lưu URL English trong ACF bên cạnh URL Vietnamese.
- Switcher giữ URL object/ngữ cảnh trang đang xem do plugin sinh; homepage `/` ↔ `/en/`, detail `/dich-vu/{slug}/` ↔ `/en/dich-vu/{slug}/`, taxonomy tương tự.
- Giữ logic menu language_switcher hiện hữu. Có thể style wrapper phù hợp header mới; position/layout của language item cùng menu compact chờ Q1. Không tạo switcher thứ hai bằng danh sách URL hardcoded.
- Nếu cần custom wrapper, dùng interface switcher của plugin thay vì tự tính language mapping. [TranslatePress custom language switcher](https://translatepress.com/docs/developers/custom-language-switcher/). Implementation phải đối chiếu version đang cài; không dùng undocumented private method.
- Homepage anchors là stable IDs không dịch. Khi link từ inner page về homepage section, resolve homepage **đúng ngôn ngữ** rồi thêm fragment. Không dùng `#services` trên inner page không có section đó.
- Mode lưu storage cùng origin, không ảnh hưởng language. Language links có tên ngôn ngữ thật, focus/keyboard được giữ.

## 5. SEO, search và giới hạn

Theme gọi wp_head/wp_footer và hỗ trợ title-tag, không tự in canonical/hreflang song song Yoast/TranslatePress. Giữ current options/permalinks; SEO Pack đang off nên không tự bật slug/meta translation mới. Homepage SEO title/meta có nguồn Yoast riêng; nhu cầu hoàn thiện EN metadata/ALT khi add-on off là Q8, không hứa ngoài capability đã xác minh.

Search form giữ URL ngôn ngữ và WordPress query. Render results được TranslatePress dịch, nhưng **WordPress search mặc định tìm source post content, không mặc nhiên search mọi chuỗi EN trong dictionary**. Phase triển khai kiểm tra query EN tiêu biểu; nếu cần full translated-content search phải chốt extension/plugin strategy Q8, không giả lập bằng search DOM homepage.

Không dịch field reference/post IDs/taxonomy keys, không đổi `danh-muc-dich-vu` rewrite slug. Không bật Japanese từ bảng translation cũ. Không copy wpml-config.xml từ ví dụ WordPress khác.

## 6. Workflow thêm bản dịch homepage

1. Trên staging từ production snapshot, giữ dictionary/gettext/current settings; render homepage native đầy đủ.
2. Lập checklist source string → existing translation match → new/changed string → người duyệt. Scope gồm menu mới, hero/about, stats/services, technology headings, projects, partner, testimonials, team, blog headings, CTA/footer, switcher và controls.
3. Reuse dịch hiện có chỉ khi đúng ngữ cảnh/ý nghĩa; nội dung mới dịch qua TranslatePress, người duyệt EN ở Q2. Không ghi chuỗi cũ từ local đè bản production mới hơn.
4. Kiểm tra VI/EN cả hai modes; quote modal, menu mobile, date, latest Posts card, contact links, empty/error state. Không yêu cầu mọi bài detail EN hoàn thiện nếu ngoài scope, nhưng không được làm mất dịch cũ.
5. Kiểm tra sitemap/canonical/hreflang trước-sau và lấy baseline các phần chưa dịch; báo riêng regression và backlog translation.

Chưa có translation coverage test mới ở Phase 2; không dùng số record dictionary của Phase 1 như tỷ lệ hoàn tất dịch.
