<?php
defined('ABSPATH') || exit;

/** Approved preview fallbacks. Never written to ACF/database. */
function nextcore_home_defaults() {
    return array(
    'nc_stats' => array(
        array(
            'metric' => 'projects',
            'value' => 35,
            'suffix' => '+',
            'label' => 'Dự án triển khai',
        ),
        array(
            'metric' => 'clients',
            'value' => 25,
            'suffix' => '+',
            'label' => 'Khách hàng tin tưởng',
        ),
        array(
            'metric' => 'employees',
            'value' => 20,
            'suffix' => '+',
            'label' => 'Nhân viên',
        ),
    ),
    'nc_service_cards' => array(
        array(
            'title' => 'Outsource CNTT',
            'description' => 'Phát triển và bảo trì dự án CNTT cho đối tác outsource.',
            'image' => 'images/service-outsource.png',
            'icon' => 'code',
            'micro_text' => 'CODE
SOLVE
TOGETHER',
            'target_kind' => 'page',
            'target_slug' => 'outsource',
        ),
        array(
            'title' => 'Tư vấn doanh nghiệp',
            'description' => 'Đồng hành tối ưu hoạt động và xây dựng giải pháp phù hợp.',
            'image' => 'images/service-consulting.png',
            'icon' => 'strategy',
            'micro_text' => 'PEOPLE
STRATEGY
GROWTH',
            'target_kind' => 'term',
            'target_slug' => 'tu-van-doanh-nghiep',
        ),
        array(
            'title' => 'Giải pháp cá nhân',
            'description' => 'Hỗ trợ các nhu cầu công nghệ linh hoạt cho khách hàng cá nhân.',
            'image' => 'images/service-personal.png',
            'icon' => 'user',
            'micro_text' => 'SIMPLE
FLEXIBLE
FOR YOU',
            'target_kind' => 'term',
            'target_slug' => 'khach-hang-ca-nhan',
        ),
    ),
    'nc_featured_projects' => array(
        array(
            'object' => 0,
            'visual_variant' => 'portal',
            'card_label' => 'Nextcore Portal',
            'card_summary' => 'Hệ thống quản lý doanh nghiệp thông minh.',
            'card_image' => 'images/project-portal.png',
            'image_alt' => 'Giao diện báo cáo chấm công Nextcore Portal',
            'screen_label' => 'Nextcore Portal',
            'target_slug' => '',
        ),
        array(
            'object' => 0,
            'visual_variant' => 'olympia',
            'card_label' => 'Trường Doanh nhân Top Olympia',
            'card_summary' => 'Website đào tạo doanh nhân.',
            'card_image' => 'images/project-olympia.png',
            'image_alt' => 'Website Trường Doanh nhân Top Olympia',
            'screen_label' => 'Top Olympia',
            'target_slug' => 'truong-doanh-nhan-top-olympia',
        ),
        array(
            'object' => 0,
            'visual_variant' => 'affiliate',
            'card_label' => 'WordPress Plugin - Affiliate',
            'card_summary' => 'Giải pháp tiếp thị liên kết hiệu quả.',
            'card_image' => 'images/project-affiliate.png',
            'image_alt' => 'Trang cấu hình WordPress Plugin Affiliate',
            'screen_label' => 'Affiliate',
            'target_slug' => 'wordpress-plugin-affiliate',
        ),
    ),
    'nc_testimonials' => array(
        array(
            'name' => 'Công ty An Tâm Việt',
            'project_label' => 'Dự án Plugin Affiliate',
            'quote' => 'Nextcore để lại ấn tượng mạnh mẽ với đội ngũ hỗ trợ chuyên nghiệp, luôn đồng hành từ khâu tư vấn ban đầu đến việc sắp xếp kế hoạch và triển khai dự án. Phản hồi nhanh chóng, thông tin rõ ràng giúp chúng tôi hoàn toàn yên tâm khi làm việc cùng họ. Đặc biệt, sự uy tín và cam kết trách nhiệm với deadline của Nextcore là yếu tố nổi bật. Không thể không nhắc đến anh Tú, Project Manager của đội, người luôn tận tâm tư vấn và hỗ trợ nhiệt tình, mang đến trải nghiệm làm việc tuyệt vời.
Nextcore không chỉ là một đối tác, mà còn là một người bạn đồng hành đáng tin cậy trên hành trình phát triển của chúng tôi!',
            'image' => 'images/testimonial-1.png',
        ),
        array(
            'name' => 'Phạm Việt Hùng',
            'project_label' => 'Dự án Tạo template ebook',
            'quote' => 'Hoàn thành công việc nhanh và chất lượng. Nhiệt tình hỗ trợ sau khi hoàn thành công việc',
            'image' => 'images/testimonial-2.webp',
        ),
        array(
            'name' => 'Nguyễn Phương Trà My',
            'project_label' => 'Dự án Convert nghiệp vụ sang sơ đồ khối',
            'quote' => 'Công ty Phần mềm Next Core làm dự án của tôi rất chuyên nghiệp, thực hiện đúng yêu cầu của khách hàng và hoàn thành công việc sớm hơn thời gian quy định, đảm bảo chất lượng. Các bạn dev của Công ty hỗ trợ tôi rất nhiệt tình trong thời gian thực hiện yêu cầu của tôi. Highly recommend cho các khách hàng trong lĩnh vực IT, phần mềm, design.',
            'image' => 'images/testimonial-3.jpg',
        ),
        array(
            'name' => 'Bé khỏe bé vui',
            'project_label' => 'Dự án phục hồi website bị tấn công',
            'quote' => 'Highly appreciate your working spirit. I am pleased to choose you as partners. We will continue to cooperate in the future when there are new projects.',
            'image' => 'images/testimonial-4.png',
        ),
    ),
    'nc_team_members' => array(
        array(
            'name' => 'Nguyễn Văn Hiền',
            'role' => 'Chief Executive Officer',
            'image' => 'images/team-hien.jpg',
            'email' => 'hiennv@nextcore.vn',
            'phone' => '+84378962625',
        ),
        array(
            'name' => 'Trần Đức Anh',
            'role' => 'Chief Technology Officer',
            'image' => 'images/team-anh.jpg',
            'email' => 'anhtd@nextcore.vn',
            'phone' => '+84976748059',
        ),
        array(
            'name' => 'Phan Thanh Tú',
            'role' => 'Project Manager',
            'image' => 'images/team-tu.png',
            'email' => 'tupt@nextcore.vn',
            'phone' => '+84979525694',
        ),
    ),
    'nc_technology_items' => array(
        array(
            'mark' => 'react',
            'label' => 'React',
        ),
        array(
            'mark' => 'laravel',
            'label' => 'Laravel',
        ),
        array(
            'mark' => 'node',
            'label' => 'node.js',
        ),
        array(
            'mark' => 'aws',
            'label' => 'aws',
        ),
        array(
            'mark' => 'mysql',
            'label' => 'MySQL',
        ),
        array(
            'mark' => 'docker',
            'label' => 'docker',
        ),
        array(
            'mark' => 'figma',
            'label' => 'Figma',
        ),
        array(
            'mark' => 'cicd',
            'label' => 'CI/CD',
        ),
    ),
    'nc_social_links' => array(
        array('platform' => 'facebook', 'url' => 'https://www.facebook.com/nextcore.software.jsc'),
        array('platform' => 'tiktok', 'url' => 'https://www.tiktok.com/@nextcore.software.jsc'),
    ),
);
}
