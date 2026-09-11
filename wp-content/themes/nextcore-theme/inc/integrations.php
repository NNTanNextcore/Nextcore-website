<?php
defined('ABSPATH') || exit;

/**
 * Phase 3: ownership contract only. No provider scripts or widgets.
 * Phase 4 must verify existing plugin/external owners before enabling.
 */
function nextcore_integration_ownership() {
    return array(
        'contact' => nextcore_option('nc_contact_integration_owner', 'theme'),
        'analytics' => nextcore_option('nc_analytics_owner', 'theme'),
        'enabled' => false,
    );
}

function nextcore_integrations_bootstrap() {
    do_action('nextcore_integrations_registered', nextcore_integration_ownership());
}
add_action('init', 'nextcore_integrations_bootstrap', 20);
