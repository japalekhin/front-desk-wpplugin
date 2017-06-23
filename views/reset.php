<?php

use FrontEndUser\reset;
use FrontEndUser\Admin\pages;
?>
<div id="box_auth_reset" class="feu-theme-<?php echo Theme::get_themes('reset'); ?>">
    <?php if (!is_null(reset::$p)): ?>
        <p class="notice notice-<?php echo reset::$p->success ? 'success' : 'error'; ?>"><?php echo reset::$p->message; ?></p>
    <?php endif; ?>
    <form action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" method="POST">
        <?php wp_nonce_field('front_end_user_reset', 'front_end_user_reset'); ?>
        <input type="hidden" name="reset_key" value="<?php echo is_null(reset::$p) ? '' : reset::$p->get_data('key'); ?>" />
        <input type="hidden" name="reset_username" value="<?php echo is_null(reset::$p) ? '' : reset::$p->get_data('username'); ?>" />
        <p class="feu-form-field">
            <span class="feu-form-label"><label for="txt_password">New password</label></span>
            <span class="feu-form-control">
                <input type="password" id="txt_password" name="reset_password1" class="input-wide" />
            </span>
        </p>
        <p class="feu-form-field">
            <span class="feu-form-label"><label for="txt_password2">Confirm new password</label></span>
            <span class="feu-form-control">
                <input type="password" id="txt_password2" name="reset_password2" class="input-wide" />
            </span>
        </p>
        <p class="feu-form-submit">
            <button id="btn_reset" name="reset_password" class="button-primary input-wide">
                Reset Password
            </button>
        </p>
    </form>
</div>
<?php if (pages::get_pages('register') + pages::get_pages('login') > 0): ?>
    <div id="box_auth_recover_info" class="feu-info">
        <?php if (pages::get_pages('register') > 0): ?>
            <p>Don't have an account? <a href="<?php echo pages::get_page_url('register'); ?>">Sign Up</a></p>
        <?php endif; ?>
        <?php if (pages::get_pages('login') > 0): ?>
            <p>Remembered your account? <a href="<?php echo pages::get_page_url('login'); ?>">Log In</a></p>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php
