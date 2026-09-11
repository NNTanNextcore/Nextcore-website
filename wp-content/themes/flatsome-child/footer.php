<?php
/**
 * The template for displaying the footer.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

global $flatsome_opt;
?>

</main>

<footer id="footer" class="footer-wrapper">

	<?php do_action('flatsome_footer'); ?>

</footer>

<div class="adminActions_flat_button">
        <input type="checkbox" name="adminToggle" class="adminToggle" />
        <a class="adminButton_flat_button" href="#!">
          <i class="fa fa-phone"></i>
          <i class="fa fa-times"></i>
        </a>
        <div class="adminButtons_flat_button">
          <a target="_blank" href="https://www.facebook.com/nextcore.software.jsc" class="message" title="message">
            <img src="/wp-content/uploads/2024/11/facebook-messenger.svg" alt="messenger">
          </a>
          <a target="_blank" class="phone_flat" href="tel:0378962625" title="Phone"><i class="fa fa-phone"></i></a>
          <div
            class="zalo-chat-widget zalo-chat-widget-js active"
            data-oaid="2492703464998285192"
            data-welcome-message="Rất vui khi được hỗ trợ bạn!"
            data-autopopup="0"
            data-width="400"
            data-height="500"
          ></div>
        </div>
      </div>



</div>

<?php wp_footer(); ?>

<script src="https://sp.zalo.me/plugins/sdk.js"></script>

<script defer src="https://umami.nextcore.vn/script.js" data-website-id="bbd47551-d052-4402-9ed3-88ae53959e64"></script>

</body>
</html>
