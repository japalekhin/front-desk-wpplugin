<?php

use Alekhin\FrontDesk\Recover;
use Alekhin\FrontDesk\Admin\Theme;
use Alekhin\FrontDesk\Admin\Pages;
?>
<div id="box_auth_recover" class="frd-frd-theme-<?php echo Theme::get_themes('recover'); ?>">
    <?php if (!is_null(Recover::$p)): ?>
        <p class="notice notice-<?php echo Recover::$p->success ? 'success' : 'error'; ?>"><?php echo Recover::$p->message; ?></p>
    <?php endif; ?>
    <p>Enter your username or email below and click Request Password Reset. We will email you the instructions on how to reset your password if we find a match.</p>
    <form action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" method="POST">
        <?php wp_nonce_field('front_desk_recover', 'front_desk_recover'); ?>
        <p class="frd-form-field">
            <span class="frd-form-label"><label for="txt_username">Username or email</label></span>
            <span class="frd-form-control">
                <input type="text" id="txt_username" name="recover_username" value="<?php echo (!is_null(Recover::$p)) ? Recover::$p->get_data('username') : ''; ?>" placeholder="Username or email" class="input-wide" />
            </span>
        </p>
        <p class="frd-form-submit">
            <button id="btn_recover" name="recover_password" class="button-primary input-wide">
                Request Password Reset
            </button>
        </p>
    </form>
</div>
<?php if (Pages::get_pages('register') + Pages::get_pages('login') > 0): ?>
    <div id="box_auth_recover_info" class="frd-info">
        <?php if (Pages::get_pages('register') > 0): ?>
            <p>Don't have an account? <a href="<?php echo Pages::get_page_url('register'); ?>">Sign Up</a></p>
        <?php endif; ?>
        <?php if (Pages::get_pages('login') > 0): ?>
            <p>Remembered your account? <a href="<?php echo Pages::get_page_url('login'); ?>">Log In</a></p>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php
