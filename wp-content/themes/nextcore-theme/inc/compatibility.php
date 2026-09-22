<?php
defined('ABSPATH') || exit;

function nextcore_is_legacy_content() {
    $legacy = get_post_meta(get_the_ID(), '_elementor_edit_mode', true) === 'builder';
    return (bool) apply_filters('nextcore_is_legacy_content', $legacy, get_the_ID());
}

function nextcore_render_content() {
    echo nextcore_is_legacy_content() ? '<div class="legacy-content">' : '<div class="nextcore-native-content">';
    the_content();
    wp_link_pages();
    echo '</div>';
}

/**
 * Render the small, audited About widget vocabulary without a theme shortcode
 * engine. Keep Elementor as the body renderer and leave authored data untouched.
 */
function nextcore_about_shortcode_markup($html) {
    $tags = array('section', 'row', 'col', 'ux_image', 'gap', 'button');
    return preg_replace_callback('/' . get_shortcode_regex($tags) . '/s', function ($match) {
        $tag = $match[2];
        $attributes = shortcode_parse_atts($match[3]);
        $attributes = is_array($attributes) ? $attributes : array();
        if (($attributes['visibility'] ?? '') === 'hidden') { return ''; }
        if ($tag === 'ux_image') { return wp_get_attachment_image(absint($attributes['id'] ?? 0), 'large', false, array('loading' => 'lazy')); }
        if ($tag === 'gap') { return '<div class="nextcore-about-gap" aria-hidden="true"></div>'; }
        if ($tag === 'button') {
            $label = esc_html($attributes['text'] ?? '');
            $url = esc_url($attributes['link'] ?? '');
            return $url ? '<a class="button" href="' . $url . '">' . $label . '</a>' : $label;
        }
        $class = array('section' => 'nextcore-about-section', 'row' => 'nextcore-about-row', 'col' => 'nextcore-about-col');
        return '<div class="' . $class[$tag] . '">' . nextcore_about_shortcode_markup($match[5] ?? '') . '</div>';
    }, $html);
}

function nextcore_about_widget_compatibility($content, $widget) {
    if (is_page('ve-chung-toi') && $widget->get_name() === 'shortcode' && strpos($content, '[section') !== false) {
        return nextcore_about_shortcode_markup($content);
    }
    return $content;
}
add_filter('elementor/widget/render_content', 'nextcore_about_widget_compatibility', 20, 2);

/** The timeline repeats each item's ID on its decorative icon wrapper. */
function nextcore_timeline_widget_compatibility($content, $widget) {
    if ($widget->get_name() !== 'eae-timeline' || !class_exists('WP_HTML_Tag_Processor')) {
        return $content;
    }
    // The add-on targets icons by class; preserve item IDs and all authored data.
    $html = new WP_HTML_Tag_Processor($content);
    while ($html->next_tag(array('class_name' => 'eae-tl-icon-wrapper'))) {
        $html->remove_attribute('id');
    }
    return $html->get_updated_html();
}
add_filter('elementor/widget/render_content', 'nextcore_timeline_widget_compatibility', 20, 2);

function nextcore_builder_cached_markup_compatibility($content) {
    if (!class_exists('WP_HTML_Tag_Processor')) {
        return $content;
    }
    // Process final markup too: Elementor's document cache can bypass widget filters.
    $html = new WP_HTML_Tag_Processor($content);
    $svg_ids = array();
    while ($html->next_tag()) {
        if ($html->has_class('eae-tl-icon-wrapper')) { $html->remove_attribute('id'); }
        if ($html->get_tag() !== 'SVG') { continue; }
        $id = $html->get_attribute('id');
        if ($id && isset($svg_ids[$id]) && strpos($content, '#' . $id) === false) {
            $html->remove_attribute('id');
        }
        if ($id) { $svg_ids[$id] = true; }
    }
    return $html->get_updated_html();
}
add_filter('elementor/frontend/the_content', 'nextcore_builder_cached_markup_compatibility', 20);

/** Rebase migrated same-host asset URLs to the active WordPress directory. */
function nextcore_rebase_migrated_asset_urls($content) {
    $current_host = strtolower((string) wp_parse_url(home_url('/'), PHP_URL_HOST));
    if (!$current_host || strpos($content, '/wp-content/') === false) { return $content; }
    return preg_replace_callback(
        '~https?://([^/"\'\s<>]+)(?:/[^/"\'\s<>]+)*/wp-content/(uploads|plugins)/([^"\'\s<>)]*)~i',
        function ($match) use ($current_host) {
            if (strtolower($match[1]) !== $current_host) { return $match[0]; }
            return $match[2] === 'plugins'
                ? plugins_url('/' . $match[3])
                : content_url('/uploads/' . $match[3]);
        },
        $content
    );
}
add_filter('the_content', 'nextcore_rebase_migrated_asset_urls', 30);
add_filter('elementor/frontend/the_content', 'nextcore_rebase_migrated_asset_urls', 30);

function nextcore_current_language() {
    global $TRP_LANGUAGE;
    if (!empty($TRP_LANGUAGE)) {
        return (string) $TRP_LANGUAGE;
    }
    return function_exists('get_locale') ? (string) get_locale() : '';
}

function nextcore_common_english_translations() {
    return array(
        'Nextcore — Trang chủ' => 'Nextcore — Home',
        'Chuyển đến nội dung' => 'Skip to content',
        'Điều hướng chính' => 'Primary navigation',
        'Điều hướng di động' => 'Mobile navigation',
        'Đóng tìm kiếm' => 'Close search',
        'Bạn đang tìm gì?' => 'What are you looking for?',
        'Tìm kiếm' => 'Search',
        '>Tìm<' => '>Search<',
        'Tìm hiểu thêm ↗' => 'Learn more ↗',
        'Tìm hiểu thêm' => 'Learn more',
        'Khám phá tổng quan' => 'Explore overview',
        'Cùng trao đổi về dự án của bạn' => 'Discuss your project with us',
        'Mở menu con:' => 'Open submenu:',
        'Ngôn ngữ' => 'Language',
        'Chế độ tối' => 'Dark mode',
        'Đường dẫn trang' => 'Breadcrumb',
        'Trang chủ' => 'Home',
        'Về chúng tôi' => 'About us',
        'Về Nextcore' => 'About Nextcore',
        'VỀ NEXTCORE' => 'ABOUT NEXTCORE',
        'Giới thiệu' => 'About',
        'DỊCH VỤ' => 'SERVICES',
        'Dịch vụ' => 'Services',
        'dịch vụ' => 'services',
        'Tư vấn doanh nghiệp' => 'Business consulting',
        'Tư vấn' => 'Consulting',
        'Chiến lược' => 'Strategy',
        'Tối ưu vận hành' => 'Operational optimization',
        'Khách hàng cá nhân' => 'Individual customers',
        'Khách hàng tin tưởng' => 'Trusted customers',
        'Sản phẩm / Dự án' => 'Products / Projects',
        'Sản phẩm' => 'Products',
        'Dự án' => 'Projects',
        'dự án' => 'project',
        'Công nghệ' => 'Technology',
        'Đối tác' => 'Partners',
        'Đánh giá' => 'Testimonials',
        'ĐỘI NGŨ' => 'TEAM',
        'Đội ngũ' => 'Team',
        'đội ngũ' => 'team',
        'Tin tức' => 'News',
        'Bài viết' => 'Posts',
        'Tất cả bài viết' => 'All posts',
        'Kiến thức' => 'Insights',
        'kiến thức' => 'insights',
        'Công ty' => 'Company',
        'Liên hệ ngay' => 'Contact now',
        'Liên hệ' => 'Contact',
        'liên hệ' => 'contact',
        'Hỗ trợ' => 'Support',
        'hỗ trợ' => 'support',
        'Khám phá' => 'Explore',
        'Kết nối' => 'Connect',
        'kết nối' => 'connect',
        'Công ty Cổ phần Phần mềm Nextcore' => 'Nextcore Software Joint Stock Company',
        'Danh mục dịch vụ' => 'Service categories',
        'Danh mục bài viết' => 'Post categories',
        'Không tìm thấy trang' => 'Page not found',
        'Không tìm thấy nội dung.' => 'No content found.',
        'Kết quả tìm kiếm:' => 'Search results:',
        'Trước' => 'Previous',
        'Tiếp' => 'Next',
        'Về trang chủ' => 'Back to home',
        'Trang bạn tìm không tồn tại hoặc đã được chuyển đi.' => 'The page you are looking for does not exist or has been moved.',
        'Bình luận' => 'Comments',
        'Xem thêm' => 'Read more',
        'Khách hàng &amp; đối tác' => 'Customers &amp; partners',
        'Đăng nhập WordPress ›' => 'WordPress login ›',
        'tổng quan' => 'overview',
        'Tổng quan' => 'Overview',
        'Nổi bật' => 'Featured',
        'nổi bật' => 'featured',
        'tìm kiếm' => 'search',
        'Lợi ích' => 'Benefits',
        'Tiết kiệm chi phí' => 'Cost optimization',
        'chuyên gia giàu kinh nghiệm' => 'experienced experts',
        'About &amp; Lợi ích Tiết kiệm chi phí Team chuyên gia giàu kinh nghiệm' => 'About &amp; Benefits Cost optimization Experienced expert team',
        'Projects triển khai' => 'Delivered projects',
        'Năng lực cốt lõi' => 'Core capabilities',
        'Trao đổi nhu cầu' => 'Discuss your needs',
        'Từ chiến lược đến triển khai, Nextcore đồng hành cùng doanh nghiệp xây dựng các giải pháp công nghệ hiệu quả, linh hoạt và phù hợp với từng giai đoạn phát triển.' => 'From strategy to delivery, Nextcore works alongside businesses to build effective, flexible technology solutions for every stage of growth.',
        'Cam kết dịch vụ' => 'Service commitments',
        'Tư vấn đúng nhu cầu' => 'Needs-focused consulting',
        'Giải pháp phù hợp mục tiêu và ngân sách.' => 'Solutions aligned with your goals and budget.',
        'Triển khai linh hoạt' => 'Flexible delivery',
        'Quy trình rõ ràng, thích ứng theo từng giai đoạn.' => 'A clear process that adapts to every stage.',
        'Đồng hành dài hạn' => 'Long-term partnership',
        'Hỗ trợ vận hành và cải tiến sau bàn giao.' => 'Ongoing support and improvement after handover.',
        'Giải pháp cá nhân' => 'Individual solutions',
        'Support các nhu cầu công nghệ linh hoạt cho khách hàng cá nhân.' => 'Support flexible technology needs for individual customers.',
        'Có ý tưởng lớn?' => 'Have a big idea?',
        'Cùng hiện thực hóa.' => 'Let us make it real.',
        'Trao đổi cùng Nextcore' => 'Talk with Nextcore',
        'Công ty cổ phần Phần mềm Nextcore' => 'Nextcore Software Joint Stock Company',
        'Cổ phần Phần mềm' => 'Software Joint Stock Company',
        'Giải pháp phần mềm cho doanh nghiệp hiện đại.' => 'Software solutions for modern businesses.',
        'Thành lập vào' => 'Founded on',
        'Chuyên thực hiện phát triển, bảo trì các dự án CNTT cho các đối tác outsource.' => 'Specialized in developing and maintaining IT projects for outsourcing partners.',
        'Phát triển và bảo trì' => 'Development and maintenance',
        'Bảo trì' => 'Maintenance',
        'Đồng hành tối ưu hoạt động và xây dựng giải pháp phù hợp.' => 'Helping optimize operations and build suitable solutions.',
        'Nền tảng tạo nên khác biệt' => 'The foundation that makes the difference',
        'Chúng tôi lựa chọn và làm chủ những công nghệ hiện đại để kiến tạo giải pháp bền vững, hiệu quả và sẵn sàng cho tương lai.' => 'We choose and master modern technologies to build sustainable, effective, future-ready solutions.',
        'Giải pháp được tin chọn' => 'Trusted solutions',
        'Hướng đến xây dựng môi trường cộng tác có tính kết nối giữa các thành viên.' => 'Aiming to build a connected collaborative environment among team members.',
        'Chúng tôi là một đội ngũ nhiệt huyết và chuyên nghiệp với động lực thấu hiểu công nghệ mạnh mẽ, luôn sẵn sàng đáp ứng nhu cầu của bạn!' => 'We are a passionate and professional team with a strong drive to understand technology, always ready to meet your needs!',
        'Nguyễn Văn Hiền' => 'Nguyen Van Hien',
        'Trần Đức Tú' => 'Tran Duc Tu',
        'Phan Đăng Lưu' => 'Phan Dang Luu',
        'Đăng Lưu, Hải Châu Đà Nẵng Việt' => 'Dang Luu, Hai Chau, Da Nang, Viet Nam',
        'Đăng Lưu' => 'Dang Luu',
        'Hải Châu' => 'Hai Chau',
        'Đà Nẵng' => 'Da Nang',
        'Việt Nam' => 'Viet Nam',
        'tiếng Anh-Nhật-Việt' => 'English-Japanese-Vietnamese markets',
        'Phan Đăng Lưu, Hải Châu Đà Nẵng Việt' => 'Phan Dang Luu, Hai Chau, Da Nang, Viet Nam',
        '63 Phan Đăng Lưu, Hải Châu Đà Nẵng Việt Nam' => '63 Phan Dang Luu, Hai Chau, Da Nang, Viet Nam',
        '63 Phan Đăng Lưu, Hải Châu Đà Nẵng, Việt Nam' => '63 Phan Dang Luu, Hai Chau, Da Nang, Viet Nam',
        'Company CPPM Nextcore tổ chức picnic tại Enjoy Camping Hòa Bắc, Đà Nẵng' => 'Nextcore Software JSC held a picnic at Enjoy Camping Hoa Bac, Da Nang',
        'Công ty CPPM Nextcore tổ chức picnic tại Enjoy Camping Hòa Bắc, Đà Nẵng' => 'Nextcore Software JSC held a picnic at Enjoy Camping Hoa Bac, Da Nang',
        'Company CPPM NextCore đón sinh nhật 2 tuổi tại Bạch Mã Village ở tỉnh Thừa Thiên Huế' => 'NextCore Software JSC celebrated its 2nd birthday at Bach Ma Village in Thua Thien Hue',
        'Công ty CPPM NextCore đón sinh nhật 2 tuổi tại Bạch Mã Village ở tỉnh Thừa Thiên Huế' => 'NextCore Software JSC celebrated its 2nd birthday at Bach Ma Village in Thua Thien Hue',
        'Trường Doanh nhân Top Olympia' => 'Top Olympia Business School',
        'Hệ thống chấm công bằng gương mặt - tích hợp với Lark' => 'Face attendance system integrated with Lark',
        'Viết Plugin WordPress Chuyên Nghiệp – Tùy Biến Theo Yêu Cầu' => 'Professional WordPress Plugin Development - Custom Built on Demand',
        'Viết Plugin WordPress Chuyên Nghiệp - Tùy Biến Theo Yêu Cầu' => 'Professional WordPress Plugin Development - Custom Built on Demand',
        'Viết Extension – Tăng Cường Hiệu Suất và Tính Năng Cho Trình Duyệt Của Bạn' => 'Extension Development - Enhance Your Browser Performance and Features',
        'Viết Extension - Tăng Cường Hiệu Suất và Tính Năng Cho Trình Duyệt Của Bạn' => 'Extension Development - Enhance Your Browser Performance and Features',
        'Ứng dụng đặt sân' => 'Court booking app',
    );
}

function nextcore_translate_common_markup($html) {
    if (!is_string($html) || $html === '' || nextcore_current_language() !== 'en_US') {
        return $html;
    }
    return strtr($html, nextcore_common_english_translations());
}
add_filter('trp_translated_html', 'nextcore_translate_common_markup', 10);
add_filter('elementor/frontend/the_content', 'nextcore_translate_common_markup', 35);
add_filter('the_content', 'nextcore_translate_common_markup', 35);

function nextcore_translate_mixed_english_markup($html) {
    if (!is_string($html) || $html === '' || nextcore_current_language() !== 'en_US') {
        return $html;
    }

    return strtr($html, array(
        'Company cổ phần Phần mềm Nextcore' => 'Nextcore Software Joint Stock Company',
        'Company Cổ phần Phần mềm Nextcore' => 'Nextcore Software Joint Stock Company',
        'Company cổ phần Phần mềm' => 'Nextcore Software Joint Stock Company',
        'Company Cổ phần Phần mềm' => 'Nextcore Software Joint Stock Company',
        'Founded on 15 thg 6, 2022' => 'Founded on June 15, 2022',
        'Projects triển khai' => 'Delivered projects',
        'Xem năng lực' => 'View capabilities',
        'Partners của Company là các Company outsource lớn-vừa-nhỏ ở Viet Nam ở cả 3 thị trường nói English-Japanese-Vietnamese markets.' => 'The company partners with outsourcing businesses of different sizes in Viet Nam and across English, Japanese, and Vietnamese speaking markets.',
        'Partners của Company là các Company outsource lớn-vừa-nhỏ ở Viet Nam ở cả 3 thị trường nói English-Japanese-Vietnamese markets' => 'The company partners with outsourcing businesses of different sizes in Viet Nam and across English, Japanese, and Vietnamese speaking markets',
        'Không ngừng nỗ lực để giải quyết các vấn đề là Nỗi đau và tạo giá trị hữu ích cho khách hàng để trở thành đối tác tin cậy và lâu dài.' => 'We continuously work to solve real pain points and create useful value for customers, becoming a reliable long-term partner.',
        'Không ngừng nỗ lực để giải quyết các vấn đề là Pain points và tạo giá trị useful cho khách hàng để trở thành đối tác tin cậy và lâu dài.' => 'We continuously work to solve real pain points and create useful value for customers, becoming a reliable long-term partner.',
        'Outsource CNTT' => 'IT Outsourcing',
        'Development and maintenance project CNTT cho đối tác outsource.' => 'Development and maintenance of IT projects for outsourcing partners.',
        'Support các nhu cầu công nghệ linh hoạt cho khách hàng cá nhân.' => 'Support flexible technology needs for individual customers.',
        'Tự động hóa' => 'Automation',
        'Linh hoạt' => 'Flexible',
        'Nền tảng tạo nên khác biệt' => 'The foundation that makes the difference',
        'Cùng nhau kiến tạo những giá trị bền vững.' => 'Creating sustainable value together.',
        'KHÁCH HÀNG NÓI ABOUT NEXTCORE' => 'CUSTOMERS ABOUT NEXTCORE',
        'Testimonials từ khách hàng' => 'Customer testimonials',
        'Những chia sẻ thực tế từ khách hàng đang đồng hành cùng Nextcore.' => 'Real feedback from customers working with Nextcore.',
        'Team chuyên môn cao, tận tâm và luôn sẵn sàng đồng hành.' => 'A highly skilled, dedicated team always ready to accompany you.',
        'Cập nhật mới nhất' => 'Latest updates',
        'Góc nhìn & insights' => 'Perspectives & insights',
        'Sẵn sàng bắt đầu project của bạn?' => 'Ready to start your project?',
        'Hãy để Nextcore đồng hành cùng bạn trên hành trình chuyển đổi số.' => 'Let Nextcore accompany you on your digital transformation journey.',
        'Trần Đức Anh' => 'Tran Duc Anh',
        'Phan Thanh Tú' => 'Phan Thanh Tu',
    ));
}
add_filter('trp_translated_html', 'nextcore_translate_mixed_english_markup', 11);
add_filter('elementor/frontend/the_content', 'nextcore_translate_mixed_english_markup', 36);
add_filter('the_content', 'nextcore_translate_mixed_english_markup', 36);

function nextcore_final_english_translations() {
    return array(
        'Đối tác chiến lược đồng hành cùng Nextcore' => 'A strategic partner working alongside Nextcore',
        'Partners chiến lược đồng hành cùng Nextcore' => 'A strategic partner working alongside Nextcore',
        'artners chiến lược đồng hành cùng Nextcore' => 'A strategic partner working alongside Nextcore',
        'Kết nối cùng GM Solutions để mở rộng năng lực, chia sẻ thế mạnh và cùng kiến tạo những giải pháp bền vững cho khách hàng doanh nghiệp.' => 'Connect with GM Solutions to expand capabilities, share strengths, and jointly create sustainable solutions for business customers.',
        'Connect cùng GM Solutions để mở rộng năng lực, chia sẻ thế mạnh và cùng kiến tạo những giải pháp bền vững cho customers doanh nghiệp.' => 'Connect with GM Solutions to expand capabilities, share strengths, and jointly create sustainable solutions for business customers.',
        'Tìm hiểu đối tác' => 'Learn about our partner',
        'Chúng tôi không chỉ tạo ra sản phẩm, mà còn xây dựng những mối quan hệ bền vững.' => 'We do not just create products; we build lasting relationships.',
        'We không chỉ tạo ra sản phẩm, mà còn xây dựng những mối quan hệ bền vững.' => 'We do not just create products; we build lasting relationships.',
        'Đội ngũ Nextcore' => 'Nextcore team',
        '<span class="company-line">Company cổ phần Phần</span> <span class="company-line">mềm <span class="company-name">Nextcore</span></span>' => '<span class="company-line">Nextcore Software</span> <span class="company-line"><span class="company-name">Joint Stock Company</span></span>',
        '<span>Company Cổ phần</span> <span>Phần mềm Nextcore</span>' => '<span>Nextcore Software</span> <span>Joint Stock Company</span>',
        '<time datetime="2022-06-15">15 thg 6, 2022</time>' => '<time datetime="2022-06-15">June 15, 2022</time>',
        'Không ngừng nỗ lực để giải quyết các vấn đề là <strong>Nỗi đau</strong> và tạo giá trị <strong>hữu ích</strong> cho khách hàng để trở thành đối tác tin cậy và lâu dài.' => 'We continuously work to solve real pain points and create useful value for customers, becoming a reliable long-term partner.',
        'Cùng nhau kiến tạo<br />' . "\n" . 'những giá trị bền vững.' => 'Creating sustainable<br />' . "\n" . 'value together.',
        'Góc nhìn &amp; insights' => 'Perspectives &amp; insights',
        'Góc nhìn & insights' => 'Perspectives & insights',
        'Testimonials từ customers' => 'Customer testimonials',
        'Những chia sẻ thực tế từ customers đang đồng hành cùng Nextcore.' => 'Real feedback from customers working with Nextcore.',
        'Băng chuyền' => 'Carousel',
        'Testimonials trước' => 'Previous testimonial',
        'Testimonials tiếp theo' => 'Next testimonial',
        'Nguyen Phuong Tra My' => 'Nguyễn Phương Trà My',
        'Business workflow conversion project' => 'Dự án Convert nghiệp vụ sang sơ đồ khối',
        'Projects Convert nghiệp vụ sang sơ đồ khối' => 'Dự án Convert nghiệp vụ sang sơ đồ khối',
        'Trở thành trusted partner, đem lại giá trị lớn và hữu ích cho các đối
      tác.' => 'Become a trusted partner that delivers significant and useful value to partners.',
        'Trở thành một phần không thể thiếu, là \'người nhà\' trong việc hiểu và
      giải quyết \'Nỗi đau\' của customers và đối tác.' => 'Become an indispensable partner who understands and solves the pain points of customers and partners.',
        'Flexible và dễ dàng mở rộng' => 'Flexible and easy to scale',
        'Chúng tôi cung cấp services phát triển phần mềm tùy chỉnh, đáp ứng nhu cầu riêng của từng khách hàng. Với team chuyên gia, chúng tôi cam kết mang đến giải pháp công nghệ hiệu quả.' => 'We provide custom software development services tailored to each customer’s needs. With an expert team, we are committed to delivering effective technology solutions.',
        'Maintenance và nâng cấp phần mềm' => 'Software maintenance and upgrades',
        'Duy trì và cập nhật phần mềm của bạn để đảm bảo hoạt động tối ưu, tăng cường tính năng và bảo mật. Chúng tôi cung cấp các services bảo trì và nâng cấp linh hoạt và hiệu quả.' => 'Maintain and update your software to ensure optimal operation, improved features, and better security. We provide flexible and effective maintenance and upgrade services.',
        'Cung cấp nguồn nhân lực chất lượng cao, đáp ứng nhu cầu của doanh nghiệp. Services cho thuê nhân sự linh hoạt, giúp tối ưu chi phí và nâng cao hiệu quả hoạt động.' => 'Provide high-quality talent to meet business needs. Flexible staff leasing services help optimize costs and improve operational efficiency.',
        'Next nhận yêu cầu' => 'Next receives the request',
        'Next nhận requirements' => 'Next receives the requirements',
        'Đảm bảo hiểu rõ yêu cầu và các hạn chế của project.' => 'Ensure a clear understanding of requirements and project constraints.',
        'Đảm bảo hiểu rõ requirements và các hạn chế của project.' => 'Ensure a clear understanding of requirements and project constraints.',
        'Bàn giao & support' => 'Handover & support',
        'Bàn giao &amp; support' => 'Handover &amp; support',
        'Bàn giao software cho customers hoặc người dùng cuối.' => 'Hand over the software to customers or end users.',
        'Cung cấp support và bảo trì sau khi bàn giao để giải quyết vấn đề phát sinh.' => 'Provide support and maintenance after handover to resolve arising issues.',
        'Nền tảngtạo nên khác biệt' => 'The foundation that makes the difference',
        'Nền tảng tạo nên khác biệt' => 'The foundation that makes the difference',
        'We lựa chọn và làm chủ những công nghệ hiện đại để kiến tạo giải pháp bền vững, hiệu quả và sẵn sàng cho tương lai.' => 'We choose and master modern technologies to build sustainable, effective, future-ready solutions.',
        'We là một team nhiệt huyết và chuyên nghiệp với động lực am hiểu công nghệ mạnh mẽ, luôn sẵn sàng đáp ứng nhu cầu của bạn!' => 'We are a passionate and professional team with strong technology expertise, always ready to meet your needs!',
        'Tập trung vào năng lượng cốt lõi' => 'Focus on core strengths',
        'Phát triển software tùy chỉnh' => 'Custom software development',
        'Xác định nhu cầu và mục tiêu customers' => 'Identify customer needs and goals',
        'Thu thập thông tin chi tiết về tính năng và requirements hệ thống' => 'Collect detailed information about features and system requirements',
        'Phân tích requirements kỹ thuật và chức năng.' => 'Analyze technical and functional requirements.',
        'Tiến hành software development dựa trên requirements đã phân tích.' => 'Proceed with software development based on the analyzed requirements.',
        'Thực hiện kiểm thử software (unit test, integration test) để đảm bảo chất lượng.' => 'Conduct software testing (unit tests and integration tests) to ensure quality.',
        'Cập nhật và sửa lỗi theo requirements trong quá trình phát triển.' => 'Update and fix issues as requested during the development process.',
        'Chất lượng sản phẩm của chúng tôi luôn được đảm bảo, đáp ứng các tiêu chuẩn nghiêm ngặt và mang lại sự hài lòng tối đa cho customers.' => 'Our product quality is always assured, meeting strict standards and delivering maximum customer satisfaction.',
        'We luôn nỗ lực search các phương án tiết kiệm chi phí, đồng thời đảm bảo chất lượng services vượt trội.' => 'We always seek cost-saving options while ensuring outstanding service quality.',
        'Hãy tin tưởng vào chúng tôi để đạt được mục tiêu tài chính của bạn một cách hiệu quả và bền vững.' => 'Trust us to help you achieve your financial goals effectively and sustainably.',
        'Đồng hành và support lâu dài' => 'Long-term partnership and support',
        'We hiểu rằng mỗi project không chỉ đơn thuần là một hợp đồng, mà là một phần của quá trình phát triển lâu dài và thành công của customers.' => 'We understand that every project is not merely a contract, but part of the customer’s long-term growth and success.',
        'Giải pháp software cho doanh nghiệp hiện đại.' => 'Software solutions for modern businesses.',
        'Nền tảng software connect đám mây, dữ liệu, phát triển và bảo mật' => 'A software platform connecting cloud, data, development, and security',
        'Các công nghệ' => 'Technologies',
        'Cung cấp services và sản phẩm software giá trị cao.' => 'Provide high-value software products and services.',
        'Hướng đến cung cấp giải pháp có giá trị gia tăng cao đối với customers và đối tác.' => 'Aim to provide high value-added solutions for customers and partners.',
        'Continuously striving to address challenges as <span style="font-weight: 700;">Pain points</span> and create <span style="font-weight: 700;">meaningful</span> cho customers để trở thành trusted partner và lâu dài.' => 'Continuously striving to address challenges as <span style="font-weight: 700;">Pain points</span> and create <span style="font-weight: 700;">meaningful value</span> for customers to become a long-term trusted partner.',
        'cho customers để trở thành trusted partner và lâu dài.' => 'for customers to become a long-term trusted partner.',
        'Hướng đến xây dựng môi trường cộng tác có tính connect cao giữa các thành viên.' => 'Aim to build a highly connected collaborative environment among team members.',
        'Tối ưu chi phí' => 'Cost optimization',
        'Tiết kiệm chi phí' => 'Cost savings',
        'Tối ưu vận hành' => 'Operational optimization',
        'đối tác tin cậy' => 'trusted partner',
        'khách hàng cá nhân' => 'individual customers',
        'các nhu cầu công nghệ flexible cho individual customers' => 'flexible technology needs for individual customers',
        'phát triển phần mềm' => 'software development',
        'phần mềm' => 'software',
        'khách hàng' => 'customers',
        'linh hoạt' => 'flexible',
        'yêu cầu' => 'requirements',
        'Chúng tôi' => 'We',
        'We cung cấp services software development tùy chỉnh, đáp ứng nhu cầu riêng của từng customers. Với team chuyên gia, chúng tôi cam kết mang đến giải pháp công nghệ hiệu quả.' => 'We provide custom software development services tailored to each customer’s needs. With an expert team, we are committed to delivering effective technology solutions.',
        'Maintenance và nâng cấp software' => 'Software maintenance and upgrades',
        'Duy trì và cập nhật software của bạn để đảm bảo hoạt động tối ưu, tăng cường tính năng và bảo mật. We cung cấp các services bảo trì và nâng cấp flexible và hiệu quả.' => 'Maintain and update your software to ensure optimal performance, enhanced features, and stronger security. We provide flexible and effective maintenance and upgrade services.',
        'Cung cấp nguồn nhân lực chất lượng cao, đáp ứng nhu cầu của doanh nghiệp. Services cho thuê nhân sự flexible, giúp tối ưu chi phí và nâng cao hiệu quả hoạt động.' => 'We provide high-quality professionals to meet business needs. Our flexible staff leasing service helps optimize costs and improve operational efficiency.',
    );
}

function nextcore_translate_final_english_html($html) {
    if (!is_string($html) || $html === '') {
        return $html;
    }
    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';
    $is_english_request = strpos($request_uri, '/en/') === 0 || $request_uri === '/en';
    if (!$is_english_request) {
        return str_replace(
            array('Nextcore. All rights reserved.', '>Contact<'),
            array('Nextcore. Bảo lưu mọi quyền.', '>Liên hệ<'),
            $html
        );
    }
    $protected_markup = array();
    $html = preg_replace_callback(
        '~<([a-z][a-z0-9]*)\b(?=[^>]*\bdata-nextcore-no-translate\b)[^>]*>.*?</\1>~isu',
        static function ($matches) use (&$protected_markup) {
            $placeholder = '___NEXTCORE_NOTRANSLATE_' . count($protected_markup) . '___';
            $protected_markup[$placeholder] = $matches[0];
            return $placeholder;
        },
        $html
    );
    $html = str_replace('Nextcore. Bảo lưu mọi quyền.', 'Nextcore. All rights reserved.', $html);
    $html = str_replace(
        'Nextcore đồng hành cùng doanh nghiệp bằng công nghệ, sáng tạo và tư duy chiến lược.',
        'Nextcore helps businesses grow through technology, creativity and strategic thinking.',
        $html
    );
    $html = strtr($html, nextcore_final_english_translations());

    $html = preg_replace(
        array(
            '~Trở thành\s+trusted partner,\s*đem lại giá trị lớn và hữu ích cho các đối\s+tác\.~u',
            '~>[^<]*services[^<]*software[^<]*gi[^<]*cao\.<~u',
            '~>[^<]*customers[^<]*đối tác\.<~u',
            '~Continuously striving to address challenges as\s*<span[^>]*>Pain points</span>\s*and create\s*<span[^>]*>meaningful</span>[^<]*~u',
            '~>[^<]*connect cao[^<]*<~u',
        ),
        array(
            'Become a trusted partner that delivers meaningful, lasting value to our partners.',
            '>Provide high-value software products and services.<',
            '>Aim to provide high value-added solutions for customers and partners.<',
            'Continuously striving to address challenges as <span style="font-weight: 700;">Pain points</span> and create <span style="font-weight: 700;">meaningful value</span> for customers to become a long-term trusted partner.',
            '>Aim to build a highly connected collaborative environment among team members.<',
        ),
        $html
    );

    $html = preg_replace(
        '~(<meta[^>]+content=")[^"]*We cung cấp services software development[^"]*("[^>]*>)~u',
        '$1Custom software development, maintenance, upgrades, and flexible staff leasing services tailored to your business needs.$2',
        $html
    );

    $html = preg_replace(
        '~(<h1\b[^>]*\bid="hero-title"[^>]*>).*?(</h1>)~su',
        '$1<span class="company-line">Nextcore Software</span> <span class="company-line"><span class="company-name">Joint Stock Company</span></span>$2',
        $html
    );

    return $protected_markup ? strtr($html, $protected_markup) : $html;
}
add_filter('trp_translated_html', 'nextcore_translate_final_english_html', 999);
add_filter('elementor/widget/render_content', 'nextcore_translate_final_english_html', 999);

function nextcore_translate_legacy_english_meta($description) {
    if (!is_string($description) || $description === '') {
        return $description;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';
    if (strpos($request_uri, '/en/') !== 0 && $request_uri !== '/en') {
        return $description;
    }

    if (strpos($description, 'We cung cấp services software development') !== false) {
        return 'Custom software development, maintenance, upgrades, and flexible staff leasing services tailored to your business needs.';
    }

    return wp_strip_all_tags(nextcore_translate_final_english_html($description));
}
add_filter('wpseo_metadesc', 'nextcore_translate_legacy_english_meta', 999);
add_filter('wpseo_opengraph_desc', 'nextcore_translate_legacy_english_meta', 999);
add_filter('wpseo_twitter_description', 'nextcore_translate_legacy_english_meta', 999);

function nextcore_start_english_output_cleanup() {
    if (is_admin()) {
        return;
    }
    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';
    if (strpos($request_uri, '/en/') !== 0 && $request_uri !== '/en') {
        return;
    }
    ob_start('nextcore_translate_final_english_html');
}
add_action('template_redirect', 'nextcore_start_english_output_cleanup', 0);

function nextcore_footer_locale_output($html) {
    if (!is_string($html) || $html === '') {
        return $html;
    }
    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';
    $is_english_request = strpos($request_uri, '/en/') === 0 || $request_uri === '/en';

    return $is_english_request
        ? str_replace('Nextcore. Bảo lưu mọi quyền.', 'Nextcore. All rights reserved.', $html)
        : str_replace('Nextcore. All rights reserved.', 'Nextcore. Bảo lưu mọi quyền.', $html);
}

function nextcore_start_footer_locale_output() {
    if (!is_admin()) {
        ob_start('nextcore_footer_locale_output');
    }
}
add_action('template_redirect', 'nextcore_start_footer_locale_output', 1);

function nextcore_translate_contact_page_markup($html) {
    if (!is_string($html) || $html === '') {
        return $html;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';
    $is_english_request = strpos($request_uri, '/en/') === 0 || $request_uri === '/en';
    if (nextcore_current_language() !== 'en_US' && !$is_english_request) {
        return $html;
    }

    if (strpos($request_uri, '/lien-he') === false && strpos($html, 'elementor-320') === false) {
        return $html;
    }

    $translations = array(
        'Bắt đầu bằng <br>' => 'Start with <br>',
        'Bắt đầu bằng' => 'Start with',
        'một cuộc trò chuyện.' => 'a conversation.',
        'Chia sẻ bài toán của bạn. Nextcore sẵn sàng lắng nghe, tư vấn hướng đi phù hợp và cùng bạn biến ý tưởng thành sản phẩm vận hành được.' => 'Share your challenge. Nextcore is ready to listen, recommend the right direction, and help turn your idea into a working product.',
        'Thông tin liên hệ' => 'Contact information',
        'Thông tin contact' => 'Contact information',
        'Điện thoại' => 'Phone',
        'Văn phòng' => 'Office',
        'Bạn muốn xây dựng điều gì?' => 'What would you like to build?',
        'Gửi thông tin ngắn gọn về nhu cầu, thời gian mong muốn và cách Nextcore có thể liên hệ lại với bạn.' => 'Send us a short note about your needs, timeline, and how Nextcore can get back to you.',
        'Send thông tin ngắn gọn về nhu cầu, thời gian mong muốn và cách Nextcore có thể contact lại với bạn.' => 'Send us a short note about your needs, timeline, and how Nextcore can get back to you.',
        'Cách Nextcore bắt đầu cùng bạn' => 'How Nextcore starts with you',
        'Lắng nghe nhu cầu' => 'Understand your needs',
        'Hiểu mục tiêu, bối cảnh vận hành và ràng buộc hiện tại của dự án.' => 'Understand the goals, operating context, and current constraints of the project.',
        'Hiểu mục tiêu, bối cảnh vận hành và ràng buộc hiện tại của project.' => 'Understand the goals, operating context, and current constraints of the project.',
        'Đề xuất hướng đi' => 'Recommend a direction',
        'Gợi ý phạm vi, mô hình triển khai và bước tiếp theo rõ ràng.' => 'Suggest the scope, delivery model, and clear next steps.',
        'Đồng hành thực thi' => 'Deliver together',
        'Bắt đầu bằng kế hoạch khả thi, minh bạch tiến độ và trách nhiệm.' => 'Start with a feasible plan, transparent progress, and clear ownership.',
        'Họ &amp; Tên' => 'Full name',
        'Họ & Tên' => 'Full name',
        'Địa chỉ email' => 'Email address',
        'Dịch vụ bạn quan tâm' => 'Service of interest',
        'Services bạn quan tâm' => 'Service of interest',
        'Thiết kế website' => 'Website design',
        'Lark - Phần mềm phối hợp &amp; truyền thông' => 'Lark - Collaboration and communication platform',
        'Lark - Phần mềm phối hợp & truyền thông' => 'Lark - Collaboration and communication platform',
        'NextAttendance - Hệ thống chấm công' => 'NextAttendance - Time attendance system',
        'Nextbooking - Hệ thống tích hợp thanh toán online' => 'Nextbooking - Online payment integration system',
        'NextLMS - Hệ thống quản lý giáo dục' => 'NextLMS - Education management system',
        'Nextcoin - Hệ thống khen thưởng nội bộ' => 'Nextcoin - Internal rewards system',
        'NextDevice - Hệ thống quản lý thiết bị' => 'NextDevice - Device management system',
        'Redmine - Phần mềm quản lý dự án' => 'Redmine - Project management software',
        'Redmine - Phần mềm quản lý project' => 'Redmine - Project management software',
        'Umami - Phần mềm phân tích lưu lượng truy cập' => 'Umami - Web analytics software',
        'Thuê outsource' => 'Outsourcing',
        'Khác' => 'Other',
        'Tiêu đề' => 'Subject',
        'Nội dung' => 'Message',
        'Gửi' => 'Send',
        '63 Phan Đăng Lưu, Hải Châu<br>Đà Nẵng 550000, Việt Nam' => '63 Phan Dang Luu, Hai Chau<br>Da Nang 550000, Viet Nam',
        '63 Phan Đăng Lưu, Hải ChâuĐà Nẵng 550000, Việt Nam' => '63 Phan Dang Luu, Hai Chau, Da Nang 550000, Viet Nam',
        '63 Phan Đăng Lưu, Hòa Cường, Đà Nẵng, Việt Nam' => '63 Phan Dang Luu, Hoa Cuong, Da Nang, Viet Nam',
    );

    return strtr($html, $translations);
}
add_filter('trp_translated_html', 'nextcore_translate_contact_page_markup', 20);
add_filter('elementor/frontend/the_content', 'nextcore_translate_contact_page_markup', 40);
add_filter('the_content', 'nextcore_translate_contact_page_markup', 40);

function nextcore_lark_static_markup($content) {
    if (!is_singular('dich-vu') || get_post_field('post_name', get_queried_object_id()) !== 'lark' || !class_exists('WP_HTML_Tag_Processor')) {
        return $content;
    }
    $html = new WP_HTML_Tag_Processor($content);
    while ($html->next_tag()) {
        if ($html->get_attribute('id') === '') { $html->remove_attribute('id'); }
        // Saved carousel snapshots now render as static, accessible content.
        if ($html->has_class('slick-slide')) { $html->remove_attribute('aria-hidden'); }
    }
    return $html->get_updated_html();
}
add_filter('the_content', 'nextcore_lark_static_markup', 20);
