<?php defined('ABSPATH') || exit;
$email = sanitize_email(nextcore_option('nc_contact_email', 'info@nextcore.vn'));
$phone = preg_replace('/[^+0-9]/', '', (string) nextcore_option('nc_contact_phone', '+84378962625'));
?>
<address>
<p><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg><span><?php echo esc_html(nextcore_option('nc_contact_address', '63 Phan Đăng Lưu Street, Hai Chau, Da Nang 550000, Viet Nam')); ?></span></p>
<a href="<?php echo esc_url('mailto:' . $email); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 6 9 7 9-7"/></svg><span><?php echo esc_html($email); ?></span></a>
<a href="<?php echo esc_url('tel:' . $phone); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m7 3 3 5-3 3a16 16 0 0 0 6 6l3-3 5 3-1 4C10 23 1 14 3 4Z"/></svg><span><?php echo esc_html($phone); ?></span></a>
</address>

