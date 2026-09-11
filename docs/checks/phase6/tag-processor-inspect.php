<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__, 3) . '/wp-includes/html-api/class-wp-html-decoder.php';
require dirname(__DIR__, 3) . '/wp-includes/html-api/class-wp-html-attribute-token.php';
require dirname(__DIR__, 3) . '/wp-includes/html-api/class-wp-html-span.php';
require dirname(__DIR__, 3) . '/wp-includes/html-api/class-wp-html-text-replacement.php';
require dirname(__DIR__, 3) . '/wp-includes/html-api/class-wp-html-tag-processor.php';
$html = new WP_HTML_Tag_Processor('<span><svg id="test"><polygon /></svg></span>');
var_dump($html->next_tag('svg'), $html->get_tag(), $html->get_attribute('id'));
$html->remove_attribute('id');
echo $html->get_updated_html();
