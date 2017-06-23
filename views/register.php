<?php

use Alekhin\FrontDesk\Register;
use Alekhin\FrontDesk\Admin\Theme;
use Alekhin\FrontDesk\Admin\Pages;
?>
<div id="box_auth_register" class="frd-theme-<?php echo Theme::get_themes('register'); ?>">
    <?php if (!is_null(Register::$p)): ?>
        <p class="notice notice-<?php echo Register::$p->success ? 'success' : 'error'; ?>"><?php echo Register::$p->message; ?></p>
    <?php endif; ?>
    <form action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" method="POST">
        <?php wp_nonce_field('front_desk_register', 'front_desk_register'); ?>
        <p class="frd-form-field">
            <span class="frd-form-label"><label for="txt_firstname">First name</label></span>
            <span class="frd-form-control">
                <input type="text" id="txt_firstname" name="register_firstname" value="<?php echo (!is_null(Register::$p) && !Register::$p->success) ? Register::$p->get_data('firstname') : ''; ?>" placeholder="First name" class="input-wide" />
            </span>
        </p>
        <p class="frd-form-field">
            <span class="frd-form-label"><label for="txt_lastname">Last name</label></span>
            <span class="frd-form-control">
                <input type="text" id="txt_lastname" name="register_lastname" value="<?php echo (!is_null(Register::$p) && !Register::$p->success) ? Register::$p->get_data('lastname') : ''; ?>" placeholder="Last name" class="input-wide" />
            </span>
        </p>
        <p class="frd-form-field">
            <span class="frd-form-label"><label for="txt_email">Email</label></span>
            <span class="frd-form-control">
                <input type="email" id="txt_email" name="register_email" value="<?php echo (!is_null(Register::$p) && !Register::$p->success) ? Register::$p->get_data('email') : ''; ?>" placeholder="Email" class="input-wide" />
            </span>
        </p>
        <p class="frd-form-field">
            <span class="frd-form-label"><label for="txt_username">Username</label></span>
            <span class="frd-form-control">
                <input type="text" id="txt_username" name="register_username" value="<?php echo (!is_null(Register::$p) && !Register::$p->success) ? Register::$p->get_data('username') : ''; ?>" placeholder="Username" class="input-wide" />
            </span>
        </p>
        <p class="frd-form-field">
            <span class="frd-form-label"><label for="txt_password">Password</label></span>
            <span class="frd-form-control">
                <input type="password" id="txt_password" name="register_password" placeholder="Password" class="input-wide" />
            </span>
        </p>
        <p class="frd-form-field">
            <span class="frd-form-label"><label for="txt_password2">Confirm password</label></span>
            <span class="frd-form-control">
                <input type="password" id="txt_password2" name="register_password2" placeholder="Confirm password" class="input-wide" />
            </span>
        </p>
        <p class="frd-form-field">
            <span class="frd-form-label"></span>
            <span class="frd-form-control">
                <label for="chk_terms">
                    <input type="checkbox" id="chk_terms" name="register_terms" value="agree"<?php echo (!is_null(Register::$p) && !Register::$p->success && Register::$p->get_data('terms')) ? ' checked="checked"' : ''; ?> />
                    I agree to terms of use and have read and understood the privacy policy.
                </label>
            </span>
        </p>
        <p class="frd-form-submit">
            <button id="btn_register" name="register" class="button-primary input-wide">
                Sign Up
            </button>
        </p>
    </form>
</div>
<?php if (Pages::get_pages('login') > 0): ?>
    <div id="box_auth_register_info" class="frd-info">
        <p>Already have an account? <a href="<?php echo Pages::get_page_url('login'); ?>">Log In</a></p>
    </div>
<?php endif; ?>
<?php
