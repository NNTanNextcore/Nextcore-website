# Homepage — EN translation review

Ngày 11/09/2026. **DRAFT — chờ người dùng duyệt. Chưa ghi TranslatePress dictionary.**

134 chuỗi nguồn duy nhất, gộp các vị trí dùng lại cùng nội dung. Đã đối chiếu chỉ đọc `trp_dictionary_vi_en_us` và `trp_gettext_en_us` local: 15 chuỗi có bản dịch không rỗng trùng chính xác hoặc sau chuẩn hóa HTML/khoảng trắng; các chuỗi còn lại không tìm thấy theo cách đối chiếu này. “Không tìm thấy” không khẳng định chưa có bản dịch ở từng fragment bên trong một block HTML. Dữ liệu production cần đối chiếu lại khi import.

Hero cần review nguyên cụm tên công ty vì HTML VI đang chia giữa “Phần” và “mềm”. Không import hai mảnh độc lập làm câu EN sai. Tên riêng khách hàng, thành viên, Nextcore và sản phẩm được giữ nguyên. Blog cards dùng nội dung Post động; file này bao phủ headings mới, không tự dịch lại toàn bộ kho bài viết.

Control mở/đóng menu và chế độ màu dùng trạng thái ARIA; dots dùng tên khách hàng có sẵn. Các số thống kê, ngày nguồn, email, số điện thoại, URL, ID, mã icon/variant không phải nội dung cần dịch. Contact hiện hiển thị trực tiếp địa chỉ/email/phone, không có nhãn “Địa chỉ/Điện thoại” riêng để import. Logo/ảnh nhóm/ảnh khách hàng dùng tên riêng đã có ở các mục tương ứng. Bản dịch alt phải kiểm tra khả năng xử lý attribute của TranslatePress khi rollout.

## EN-001

VI:

> Software development company

EN proposed:

> Software Development Company

Context: `nc_hero_eyebrow`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-002

VI:

> Công ty cổ phần Phần

EN proposed:

> Nextcore Software Joint Stock Company

Context: `nc_hero_line_one`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Hai mảnh VI cùng một H1. Dịch cả cụm “Công ty cổ phần Phần mềm Nextcore” thành câu EN này, không dịch riêng chữ “mềm”. Bước import sau duyệt phải kiểm tra block TranslatePress, vị trí span Nextcore màu đỏ và ngắt dòng; không ghi EN vào ACF nguồn. Đây là cách diễn đạt tiếng Anh đề xuất, chưa khẳng định là tên pháp lý EN đã đăng ký.

## EN-003

VI:

> mềm

EN proposed:

> Nextcore Software Joint Stock Company

Context: `nc_hero_line_two_prefix`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Hai mảnh VI cùng một H1. Dịch cả cụm “Công ty cổ phần Phần mềm Nextcore” thành câu EN này, không dịch riêng chữ “mềm”. Bước import sau duyệt phải kiểm tra block TranslatePress, vị trí span Nextcore màu đỏ và ngắt dòng; không ghi EN vào ACF nguồn. Đây là cách diễn đạt tiếng Anh đề xuất, chưa khẳng định là tên pháp lý EN đã đăng ký.

## EN-004

VI:

> Nextcore

EN proposed:

> Nextcore

Context: `nc_hero_brand`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-005

VI:

> BUILD TRUST, CREATE VALUE

EN proposed:

> BUILD TRUST, CREATE VALUE

Context: `nc_hero_slogan`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-006

VI:

> Giải pháp phần mềm cho doanh nghiệp hiện đại.

EN proposed:

> Software solutions for modern businesses.

Context: `nc_hero_description`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-007

VI:

> Liên hệ ngay

EN proposed:

> Get in touch

Context: `nc_hero_contact_label`, `nc_cta_button_label`, `nc_header_contact_label`

Existing translation found?: Có — exact, status 2: "Contact now" (`nxt_trp_dictionary_vi_en_us`)

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-008

VI:

> Xem năng lực

EN proposed:

> Explore our expertise

Context: `nc_hero_secondary_label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-009

VI:

> Về Nextcore

EN proposed:

> About Nextcore

Context: `nc_about_eyebrow`, `nc_footer_about_heading`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-010

VI:

> Công ty Cổ phần

EN proposed:

> Nextcore Software

Context: `nc_about_heading_line_one`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Hai dòng ghép thành “Nextcore Software Joint Stock Company”. Duyệt cùng nhau; giữ thương hiệu Nextcore.

## EN-011

VI:

> Phần mềm Nextcore

EN proposed:

> Joint Stock Company

Context: `nc_about_heading_line_two`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Hai dòng ghép thành “Nextcore Software Joint Stock Company”. Duyệt cùng nhau; giữ thương hiệu Nextcore.

## EN-012

VI:

> <p>Chuyên thực hiện phát triển, bảo trì các dự án CNTT cho các đối tác outsource.</p>
>             <p>Đối tác của Công ty là các Công ty outsource lớn-vừa-nhỏ ở Việt Nam ở cả 3 thị trường nói tiếng Anh-Nhật-Việt.</p>
>             <p>Không ngừng nỗ lực để giải quyết các vấn đề là <strong>Nỗi đau</strong> và tạo giá trị <strong>hữu ích</strong> cho khách hàng để trở thành đối tác tin cậy và lâu dài.</p>

EN proposed:

> <p>We develop and maintain IT projects for outsourcing partners.</p>
> <p>We work with outsourcing companies of all sizes in Vietnam, serving English-, Japanese- and Vietnamese-speaking markets.</p>
> <p>We continuously work to address customer <strong>pain points</strong> and deliver <strong>meaningful value</strong>, building trusted, long-term partnerships.</p>

Context: `nc_about_body`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Dịch theo ba đoạn và các text node quanh strong trong TranslatePress; giữ nhấn mạnh, không ghi đè HTML VI.

## EN-013

VI:

> Tìm hiểu thêm

EN proposed:

> Learn more

Context: `nc_about_link_label`, `nc_partner_link_label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-014

VI:

> Dự án triển khai

EN proposed:

> Projects delivered

Context: `nc_stats[0].label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-015

VI:

> Khách hàng tin tưởng

EN proposed:

> Clients who trust us

Context: `nc_stats[1].label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-016

VI:

> Nhân viên

EN proposed:

> Employees

Context: `nc_stats[2].label`

Existing translation found?: Có — exact, status 2: "Employees" (`nxt_trp_dictionary_vi_en_us`)

Notes: Đề xuất chờ duyệt; chưa ghi dictionary. Có bản dịch cũ ở bên dưới; đề xuất có thể đổi wording/capitalization, chỉ áp dụng sau duyệt.

## EN-017

VI:

> Dịch vụ

EN proposed:

> Services

Context: `nc_services_eyebrow`, `home_primary`, `home_mobile`

Existing translation found?: Có — exact, status 2: "Service" (`nxt_trp_dictionary_vi_en_us`)

Notes: Đề xuất chờ duyệt; chưa ghi dictionary. Có bản dịch cũ ở bên dưới; đề xuất có thể đổi wording/capitalization, chỉ áp dụng sau duyệt.

## EN-018

VI:

> Năng lực cốt lõi

EN proposed:

> Our core expertise

Context: `nc_services_heading`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-019

VI:

> Trao đổi nhu cầu 

EN proposed:

> Let's discuss your needs 

Context: `nc_services_contact_label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-020

VI:

> Outsource CNTT

EN proposed:

> IT Outsourcing

Context: `nc_service_cards[0].title`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-021

VI:

> Phát triển và bảo trì dự án CNTT cho đối tác outsource.

EN proposed:

> IT project development and maintenance for outsourcing partners.

Context: `nc_service_cards[0].description`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-022

VI:

> CODE
> SOLVE
> TOGETHER

EN proposed:

> CODE
> SOLVE
> TOGETHER

Context: `nc_service_cards[0].micro_text`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-023

VI:

> Tư vấn doanh nghiệp

EN proposed:

> Business Consulting

Context: `nc_service_cards[1].title`, `home_primary`, `home_mobile`

Existing translation found?: Có — exact, status 2: "Business consulting" (`nxt_trp_dictionary_vi_en_us`)

Notes: Đề xuất chờ duyệt; chưa ghi dictionary. Có bản dịch cũ ở bên dưới; đề xuất có thể đổi wording/capitalization, chỉ áp dụng sau duyệt.

## EN-024

VI:

> Đồng hành tối ưu hoạt động và xây dựng giải pháp phù hợp.

EN proposed:

> Helping you optimize operations and develop solutions that fit your business.

Context: `nc_service_cards[1].description`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-025

VI:

> PEOPLE
> STRATEGY
> GROWTH

EN proposed:

> PEOPLE
> STRATEGY
> GROWTH

Context: `nc_service_cards[1].micro_text`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-026

VI:

> Giải pháp cá nhân

EN proposed:

> Solutions for Individuals

Context: `nc_service_cards[2].title`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-027

VI:

> Hỗ trợ các nhu cầu công nghệ linh hoạt cho khách hàng cá nhân.

EN proposed:

> Flexible technology support for individual customers.

Context: `nc_service_cards[2].description`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-028

VI:

> SIMPLE
> FLEXIBLE
> FOR YOU

EN proposed:

> SIMPLE
> FLEXIBLE
> FOR YOU

Context: `nc_service_cards[2].micro_text`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-029

VI:

> Công nghệ

EN proposed:

> Technology

Context: `nc_technology_eyebrow`, `home_primary`, `home_mobile`

Existing translation found?: Có — exact, status 2: "Technology" (`nxt_trp_dictionary_vi_en_us`)

Notes: Đề xuất chờ duyệt; chưa ghi dictionary. Có bản dịch cũ ở bên dưới; đề xuất có thể đổi wording/capitalization, chỉ áp dụng sau duyệt.

## EN-030

VI:

> Nền tảng
>  tạo nên khác biệt

EN proposed:

> The foundation
>  that sets us apart

Context: `nc_technology_heading`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-031

VI:

> React

EN proposed:

> React

Context: `nc_technology_items[0].label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-032

VI:

> Laravel

EN proposed:

> Laravel

Context: `nc_technology_items[1].label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-033

VI:

> node.js

EN proposed:

> node.js

Context: `nc_technology_items[2].label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-034

VI:

> aws

EN proposed:

> aws

Context: `nc_technology_items[3].label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-035

VI:

> MySQL

EN proposed:

> MySQL

Context: `nc_technology_items[4].label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-036

VI:

> docker

EN proposed:

> docker

Context: `nc_technology_items[5].label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-037

VI:

> Figma

EN proposed:

> Figma

Context: `nc_technology_items[6].label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-038

VI:

> CI/CD

EN proposed:

> CI/CD

Context: `nc_technology_items[7].label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-039

VI:

> Sản phẩm nổi bật

EN proposed:

> Featured Products

Context: `nc_projects_eyebrow`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-040

VI:

> Giải pháp được tin chọn

EN proposed:

> Solutions our clients trust

Context: `nc_projects_heading`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-041

VI:

> Trao đổi về dự án 

EN proposed:

> Let's discuss your project 

Context: `nc_projects_contact_label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-042

VI:

> Nextcore Portal

EN proposed:

> Nextcore Portal

Context: `nc_featured_projects[0].card_label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-043

VI:

> Hệ thống quản lý doanh nghiệp thông minh.

EN proposed:

> An intelligent business management system.

Context: `nc_featured_projects[0].card_summary`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-044

VI:

> Trường Doanh nhân Top Olympia

EN proposed:

> Trường Doanh nhân Top Olympia

Context: `nc_featured_projects[1].card_label`

Existing translation found?: Có — exact, status 2: "Top Olympia Business School" (`nxt_trp_dictionary_vi_en_us`); Có — normalized text, status 2: "<span class=\"custom-title-page\">T</span>op Olympia Business School" (`nxt_trp_dictionary_vi_en_us`)

Notes: Giữ nguyên tên riêng Trường Doanh nhân Top Olympia theo yêu cầu. Dictionary cũ có “Top Olympia Business School”; cần người dùng duyệt tên EN chính thức trước khi dùng lại.

## EN-045

VI:

> Website đào tạo doanh nhân.

EN proposed:

> A website for business education.

Context: `nc_featured_projects[1].card_summary`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-046

VI:

> WordPress Plugin - Affiliate

EN proposed:

> WordPress Plugin - Affiliate

Context: `nc_featured_projects[2].card_label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-047

VI:

> Giải pháp tiếp thị liên kết hiệu quả.

EN proposed:

> An effective affiliate marketing solution.

Context: `nc_featured_projects[2].card_summary`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-048

VI:

> Đối tác chiến lược

EN proposed:

> Strategic Partner

Context: `nc_partner_eyebrow`

Existing translation found?: Có — exact, status 2: "Strategic Partner" (`nxt_trp_dictionary_vi_en_us`)

Notes: Đề xuất chờ duyệt; chưa ghi dictionary. Có bản dịch cũ ở bên dưới; đề xuất có thể đổi wording/capitalization, chỉ áp dụng sau duyệt.

## EN-049

VI:

> GM Solutions

EN proposed:

> GM Solutions

Context: `nc_partner_name`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-050

VI:

> Cùng nhau kiến tạo
> những giá trị bền vững.

EN proposed:

> Creating lasting
> value together.

Context: `nc_partner_description`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-051

VI:

> Stronger
>  together
>  for a brighter
>  tomorrow

EN proposed:

> Stronger
>  together
>  for a brighter
>  tomorrow

Context: `nc_partner_motto`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-052

VI:

> KHÁCH HÀNG NÓI VỀ NEXTCORE

EN proposed:

> WHAT OUR CLIENTS SAY ABOUT NEXTCORE

Context: `nc_testimonials_eyebrow`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-053

VI:

> Đánh giá từ khách hàng

EN proposed:

> Client testimonials

Context: `nc_testimonials_heading`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-054

VI:

> Những chia sẻ thực tế từ khách hàng đang đồng hành cùng Nextcore.

EN proposed:

> Real experiences from clients working with Nextcore.

Context: `nc_testimonials_intro`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-055

VI:

> Công ty An Tâm Việt

EN proposed:

> Công ty An Tâm Việt

Context: `nc_testimonials[0].name`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-056

VI:

> Dự án Plugin Affiliate

EN proposed:

> Affiliate Plugin Project

Context: `nc_testimonials[0].project_label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-057

VI:

> Nextcore để lại ấn tượng mạnh mẽ với đội ngũ hỗ trợ chuyên nghiệp, luôn đồng hành từ khâu tư vấn ban đầu đến việc sắp xếp kế hoạch và triển khai dự án. Phản hồi nhanh chóng, thông tin rõ ràng giúp chúng tôi hoàn toàn yên tâm khi làm việc cùng họ. Đặc biệt, sự uy tín và cam kết trách nhiệm với deadline của Nextcore là yếu tố nổi bật. Không thể không nhắc đến anh Tú, Project Manager của đội, người luôn tận tâm tư vấn và hỗ trợ nhiệt tình, mang đến trải nghiệm làm việc tuyệt vời.
> Nextcore không chỉ là một đối tác, mà còn là một người bạn đồng hành đáng tin cậy trên hành trình phát triển của chúng tôi!

EN proposed:

> Nextcore made a strong impression with its professional support team, guiding us from the initial consultation through planning and project delivery. Their prompt responses and clear communication gave us complete confidence in working with them. Their reliability and commitment to meeting deadlines stood out in particular. We must also mention Tú, the team’s Project Manager, whose thoughtful advice and dedicated support made the experience excellent.
> Nextcore is more than a partner — they are a trusted companion on our journey of growth!

Context: `nc_testimonials[0].quote`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Bản dịch đầy đủ lời khách hàng để duyệt; giữ ý và xuống dòng, không thêm rating hoặc cam kết. Bản gốc ACF không đổi.

## EN-058

VI:

> Phạm Việt Hùng

EN proposed:

> Phạm Việt Hùng

Context: `nc_testimonials[1].name`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-059

VI:

> Dự án Tạo template ebook

EN proposed:

> Ebook Template Project

Context: `nc_testimonials[1].project_label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-060

VI:

> Hoàn thành công việc nhanh và chất lượng. Nhiệt tình hỗ trợ sau khi hoàn thành công việc

EN proposed:

> The work was completed quickly and to a high standard, with dedicated support even after completion.

Context: `nc_testimonials[1].quote`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Bản dịch đầy đủ lời khách hàng để duyệt; giữ ý và xuống dòng, không thêm rating hoặc cam kết. Bản gốc ACF không đổi.

## EN-061

VI:

> Nguyễn Phương Trà My

EN proposed:

> Nguyễn Phương Trà My

Context: `nc_testimonials[2].name`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-062

VI:

> Dự án Convert nghiệp vụ sang sơ đồ khối

EN proposed:

> Business Process Flowchart Project

Context: `nc_testimonials[2].project_label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-063

VI:

> Công ty Phần mềm Next Core làm dự án của tôi rất chuyên nghiệp, thực hiện đúng yêu cầu của khách hàng và hoàn thành công việc sớm hơn thời gian quy định, đảm bảo chất lượng. Các bạn dev của Công ty hỗ trợ tôi rất nhiệt tình trong thời gian thực hiện yêu cầu của tôi. Highly recommend cho các khách hàng trong lĩnh vực IT, phần mềm, design.

EN proposed:

> Next Core handled my project very professionally, met my requirements and completed the work ahead of schedule while maintaining quality. The company’s developers were very helpful throughout the project. I highly recommend them to clients looking for IT, software and design services.

Context: `nc_testimonials[2].quote`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Bản dịch đầy đủ lời khách hàng để duyệt; giữ ý và xuống dòng, không thêm rating hoặc cam kết. Bản gốc ACF không đổi.

## EN-064

VI:

> Bé khỏe bé vui

EN proposed:

> Bé khỏe bé vui

Context: `nc_testimonials[3].name`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-065

VI:

> Dự án phục hồi website bị tấn công

EN proposed:

> Hacked Website Recovery Project

Context: `nc_testimonials[3].project_label`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-066

VI:

> Highly appreciate your working spirit. I am pleased to choose you as partners. We will continue to cooperate in the future when there are new projects.

EN proposed:

> Highly appreciate your working spirit. I am pleased to choose you as partners. We will continue to cooperate in the future when there are new projects.

Context: `nc_testimonials[3].quote`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đánh giá gốc đã bằng tiếng Anh; giữ nguyên từng chữ, không biên tập lời khách hàng.

## EN-067

VI:

> ĐỘI NGŨ

EN proposed:

> OUR TEAM

Context: `nc_team_eyebrow`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-068

VI:

> Đội ngũ

EN proposed:

> Our team

Context: `nc_team_heading`, `home_primary`, `home_mobile`

Existing translation found?: Có — exact, status 2: "Team" (`nxt_trp_dictionary_vi_en_us`)

Notes: Đề xuất chờ duyệt; chưa ghi dictionary. Có bản dịch cũ ở bên dưới; đề xuất có thể đổi wording/capitalization, chỉ áp dụng sau duyệt.

## EN-069

VI:

> Đội ngũ chuyên môn cao, tận tâm và luôn sẵn sàng đồng hành.

EN proposed:

> A skilled, dedicated team ready to work alongside you.

Context: `nc_team_intro`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-070

VI:

> Nguyễn Văn Hiền

EN proposed:

> Nguyễn Văn Hiền

Context: `nc_team_members[0].name`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-071

VI:

> Chief Executive Officer

EN proposed:

> Chief Executive Officer

Context: `nc_team_members[0].role`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-072

VI:

> Trần Đức Anh

EN proposed:

> Trần Đức Anh

Context: `nc_team_members[1].name`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-073

VI:

> Chief Technology Officer

EN proposed:

> Chief Technology Officer

Context: `nc_team_members[1].role`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-074

VI:

> Phan Thanh Tú

EN proposed:

> Phan Thanh Tú

Context: `nc_team_members[2].name`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-075

VI:

> Project Manager

EN proposed:

> Project Manager

Context: `nc_team_members[2].role`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-076

VI:

> Sẵn sàng bắt đầu dự án của bạn?

EN proposed:

> Ready to start your project?

Context: `nc_cta_heading`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-077

VI:

> Hãy để Nextcore đồng hành cùng bạn trên hành trình chuyển đổi số.

EN proposed:

> Let Nextcore support you on your digital transformation journey.

Context: `nc_cta_description`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-078

VI:

> New
> ideas.
> Higher
> possibilities.

EN proposed:

> New
> ideas.
> Higher
> possibilities.

Context: `nc_cta_motto`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-079

VI:

> 63 Phan Đăng Lưu Street, Hai Chau, Da Nang 550000, Viet Nam

EN proposed:

> 63 Phan Đăng Lưu Street, Hai Chau, Da Nang 550000, Viet Nam

Context: `nc_contact_address`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Địa chỉ canonical đã duyệt; giữ nguyên địa danh và định dạng.

## EN-080

VI:

> Giải pháp công nghệ cho doanh nghiệp.

EN proposed:

> Technology solutions for businesses.

Context: `nc_footer_description`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-081

VI:

> Hỗ trợ

EN proposed:

> Support

Context: `nc_footer_support_heading`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-082

VI:

> Liên hệ

EN proposed:

> Contact

Context: `nc_footer_contact_heading`, `home_primary`, `home_mobile`

Existing translation found?: Có — exact, status 2: "Contact" (`nxt_trp_dictionary_vi_en_us`); Có — normalized text, status 2: "<span class=\"custom-title-page\">C</span>ontact" (`nxt_trp_dictionary_vi_en_us`)

Notes: Đề xuất chờ duyệt; chưa ghi dictionary. Có bản dịch cũ ở bên dưới; đề xuất có thể đổi wording/capitalization, chỉ áp dụng sau duyệt.

## EN-083

VI:

> Kết nối

EN proposed:

> Connect

Context: `nc_footer_social_heading`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-084

VI:

> Build a
> better tomorrow

EN proposed:

> Build a
> better tomorrow

Context: `nc_footer_motto`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-085

VI:

> Tin tức

EN proposed:

> News

Context: `nc_blog_eyebrow`, `home_primary`, `home_mobile`

Existing translation found?: Có — exact, status 2: "Blog" (`nxt_trp_dictionary_vi_en_us`); Có — normalized text, status 2: "<span class=\"custom-title-page\">B</span>log" (`nxt_trp_dictionary_vi_en_us`)

Notes: Đề xuất chờ duyệt; chưa ghi dictionary. Có bản dịch cũ ở bên dưới; đề xuất có thể đổi wording/capitalization, chỉ áp dụng sau duyệt.

## EN-086

VI:

> Cập nhật mới nhất

EN proposed:

> Latest updates

Context: `nc_blog_heading`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-087

VI:

> Góc nhìn & kiến thức

EN proposed:

> Insights & knowledge

Context: `nc_blog_note`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-088

VI:

> Giới thiệu

EN proposed:

> About us

Context: `home_primary`, `home_mobile`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-089

VI:

> Outsource

EN proposed:

> IT Outsourcing

Context: `home_primary`, `home_mobile`

Existing translation found?: Có — exact, status 2: "Outsource" (`nxt_trp_dictionary_vi_en_us`)

Notes: Đề xuất chờ duyệt; chưa ghi dictionary. Có bản dịch cũ ở bên dưới; đề xuất có thể đổi wording/capitalization, chỉ áp dụng sau duyệt.

## EN-090

VI:

> Khách hàng cá nhân

EN proposed:

> Individual Customers

Context: `home_primary`, `home_mobile`

Existing translation found?: Có — exact, status 2: "Individual customers" (`nxt_trp_dictionary_vi_en_us`)

Notes: Đề xuất chờ duyệt; chưa ghi dictionary. Có bản dịch cũ ở bên dưới; đề xuất có thể đổi wording/capitalization, chỉ áp dụng sau duyệt.

## EN-091

VI:

> Sản phẩm

EN proposed:

> Products

Context: `home_primary`, `home_mobile`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-092

VI:

> Sản phẩm / Dự án

EN proposed:

> Products / Projects

Context: `home_primary`, `home_mobile`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-093

VI:

> Kiến thức

EN proposed:

> Knowledge

Context: `home_primary`, `home_mobile`

Existing translation found?: Có — exact, status 2: "Knowledge" (`nxt_trp_dictionary_vi_en_us`)

Notes: Đề xuất chờ duyệt; chưa ghi dictionary. Có bản dịch cũ ở bên dưới; đề xuất có thể đổi wording/capitalization, chỉ áp dụng sau duyệt.

## EN-094

VI:

> Công ty

EN proposed:

> Company

Context: `home_primary`, `home_mobile`

Existing translation found?: Có — exact, status 2: "Company" (`nxt_trp_dictionary_vi_en_us`)

Notes: Đề xuất chờ duyệt; chưa ghi dictionary. Có bản dịch cũ ở bên dưới; đề xuất có thể đổi wording/capitalization, chỉ áp dụng sau duyệt.

## EN-095

VI:

> Đối tác

EN proposed:

> Partners

Context: `home_mobile`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-096

VI:

> Đánh giá

EN proposed:

> Testimonials

Context: `home_mobile`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-097

VI:

> Chuyển đến nội dung

EN proposed:

> Skip to content

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-098

VI:

> Nextcore — Trang chủ

EN proposed:

> Nextcore — Home

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-099

VI:

> Điều hướng chính

EN proposed:

> Main navigation

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-100

VI:

> Tìm kiếm

EN proposed:

> Search

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-101

VI:

> Menu

EN proposed:

> Menu

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-102

VI:

> Điều hướng di động

EN proposed:

> Mobile navigation

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-103

VI:

> Đóng tìm kiếm

EN proposed:

> Close search

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-104

VI:

> Bạn đang tìm gì?

EN proposed:

> What are you looking for?

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-105

VI:

> Tìm

EN proposed:

> Search

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-106

VI:

> Ngôn ngữ

EN proposed:

> Language

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-107

VI:

> Chế độ tối

EN proposed:

> Dark mode

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Nút toggle hiện dùng aria-pressed để biểu thị trạng thái; không có chuỗi “Chế độ sáng” riêng trong code.

## EN-108

VI:

> Mở menu con: %s

EN proposed:

> Open submenu: %s

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên placeholder %s; tên menu hoặc tên người được chèn bằng PHP, không ghép câu tiếng Việt trong JS.

## EN-109

VI:

> Thành lập vào

EN proposed:

> Founded on

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-110

VI:

> j \t\h\g n, Y

EN proposed:

> F j, Y

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Định dạng wp_date/gettext: hiển thị June 15, 2022. Không dịch hoặc sửa giá trị ngày nguồn 2022-06-15.

## EN-111

VI:

> Khám phá

EN proposed:

> Explore

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-112

VI:

> Các công nghệ

EN proposed:

> Technologies

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-113

VI:

> Tạm dừng chuyển động công nghệ

EN proposed:

> Pause technology animation

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Trạng thái dừng dùng aria-pressed; không có chuỗi tiếp tục riêng trong code hiện tại.

## EN-114

VI:

> Băng chuyền

EN proposed:

> Carousel

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-115

VI:

> Đánh giá trước

EN proposed:

> Previous testimonial

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-116

VI:

> Vuốt để xem các đánh giá

EN proposed:

> Swipe to browse testimonials

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-117

VI:

> Xem thêm

EN proposed:

> Read more

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-118

VI:

> Đánh giá tiếp theo

EN proposed:

> Next testimonial

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-119

VI:

> Chọn nhóm đánh giá

EN proposed:

> Choose a testimonial group

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-120

VI:

> Đóng đánh giá

EN proposed:

> Close testimonial

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-121

VI:

> Email %s

EN proposed:

> Email %s

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên placeholder %s; tên menu hoặc tên người được chèn bằng PHP, không ghép câu tiếng Việt trong JS.

## EN-122

VI:

> Gọi %s

EN proposed:

> Call %s

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên placeholder %s; tên menu hoặc tên người được chèn bằng PHP, không ghép câu tiếng Việt trong JS.

## EN-123

VI:

> Nextcore. All rights reserved.

EN proposed:

> Nextcore. All rights reserved.

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-124

VI:

> Dự án

EN proposed:

> Projects

Context: `UI / accessibility`

Existing translation found?: Có — exact, status 2: "Projects" (`nxt_trp_dictionary_vi_en_us`)

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-125

VI:

> Facebook

EN proposed:

> Facebook

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-126

VI:

> TikTok

EN proposed:

> TikTok

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-127

VI:

> VI

EN proposed:

> VI

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-128

VI:

> EN

EN proposed:

> EN

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Giữ nguyên tên riêng/thương hiệu hoặc nội dung EN đã duyệt; không tự đổi cách viết.

## EN-129

VI:

> Ba đồng nghiệp mặc polo đen có logo N đỏ cùng làm việc bên laptop trong văn phòng hiện đại

EN proposed:

> Three colleagues wearing black polo shirts with a red N logo work together at a laptop in a modern office

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-130

VI:

> Người chinh phục đỉnh núi nhìn về chân trời

EN proposed:

> A mountaineer looks toward the horizon from a summit

Context: `UI / accessibility`

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Đề xuất chờ duyệt; chưa ghi dictionary.

## EN-131

VI:

> Vietnamese

EN proposed:

> Vietnamese

Context: Language switch: TranslatePress language_name

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Tên ngôn ngữ do TranslatePress language_name cung cấp, hiện đã bằng tiếng Anh. Giữ nguyên; VI/EN ở mục trước là mã ngôn ngữ, không phải nhãn hiện tại.

## EN-132

VI:

> English

EN proposed:

> English

Context: Language switch: TranslatePress language_name

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Tên ngôn ngữ do TranslatePress language_name cung cấp, hiện đã bằng tiếng Anh. Giữ nguyên; VI/EN ở mục trước là mã ngôn ngữ, không phải nhãn hiện tại.

## EN-133

VI:

> Top Olympia

EN proposed:

> Top Olympia

Context: Project screen label registry: olympia

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Tên riêng ở nhãn khung project; giữ nguyên, không tự dịch thương hiệu.

## EN-134

VI:

> Affiliate

EN proposed:

> Affiliate

Context: Project screen label registry: affiliate

Existing translation found?: Không tìm thấy bản dịch EN không rỗng trùng chuỗi trong hai bảng local đã kiểm tra.

Notes: Tên riêng ở nhãn khung project; giữ nguyên, không tự dịch thương hiệu.
