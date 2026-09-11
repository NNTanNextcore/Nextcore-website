<?php
defined('ABSPATH') || exit;

foreach (array('setup', 'home-data', 'helpers', 'multilingual', 'enqueue', 'acf', 'compatibility', 'integrations', 'inner') as $nextcore_module) {
    require_once __DIR__ . '/inc/' . $nextcore_module . '.php';
}
unset($nextcore_module);
