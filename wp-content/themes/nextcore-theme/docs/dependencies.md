# Runtime dependencies — Phase 4

- Standalone classic theme, PHP >=7.4, WordPress >=5.8. Lint local PHP 7.4.33; runtime QA WordPress core local.
- Không có remote font/CSS/icon CDN; Be Vietnam Pro 700 + OFL local.
- ACF Pro: đọc values nếu có, an toàn khi plugin thiếu. Loader/options page giữ nguyên, chưa có final field groups/values.
- TranslatePress: public get_trp_instance → get_component('url_converter') → get_url_for_language; guarded missing plugin. Language links qua trp_custom_language_switcher, data-no-translation wrapper.
- Public references: [Internal URL](https://translatepress.com/docs/developers/translating-an-internal-url/), [Translated URL](https://translatepress.com/docs/developers/get-the-translated-url-for-a-particular-language/), [Custom switcher](https://translatepress.com/docs/developers/custom-language-switcher/).
- QA đã render với ACF/TranslatePress local trong process riêng; SQL writes bị chặn, active theme không đổi. Không phải chứng nhận baseline production.
- Elementor đi qua standard the_content trong .legacy-content. Homepage CSS scoped .nextcore-native; không invert/override authored colors. Chưa QA legacy pages đầy đủ trong Phase 4.
- theme-init.js và media.js chạy inline đồng bộ đầu head. CSP/optimizer production cần xác minh và giữ vị trí này; chưa có cấu hình nonce/hash theo production.
- Integrations vẫn contract enabled=false; không render Messenger/Zalo/Umami hoặc floating widget ở phase này.

