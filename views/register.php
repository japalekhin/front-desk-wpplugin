<?php

use FrontEndUser\register;
use FrontEndUser\Admin\pages;
?>
<div id="box_auth_register">
    <?php if (!is_null(register::$p)): ?>
        <p class="notice notice-<?php echo register::$p->success ? 'success' : 'error'; ?>"><?php echo register::$p->message; ?></p>
    <?php endif; ?>
    <form action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" method="POST">
        <?php wp_nonce_field('front_end_user_register', 'front_end_user_register'); ?>
        <p>
            <label for="txt_firstname">First name</label>
            <input type="text" id="txt_firstname" name="register_firstname" value="<?php echo (!is_null(register::$p) && !register::$p->success) ? register::$p->get_data('firstname') : ''; ?>" class="input-wide" />
        </p>
        <p>
            <label for="txt_lastname">Last name</label>
            <input type="text" id="txt_lastname" name="register_lastname" value="<?php echo (!is_null(register::$p) && !register::$p->success) ? register::$p->get_data('lastname') : ''; ?>" class="input-wide" />
        </p>
        <p>
            <label for="txt_email">Email</label>
            <input type="email" id="txt_email" name="register_email" value="<?php echo (!is_null(register::$p) && !register::$p->success) ? register::$p->get_data('email') : ''; ?>" class="input-wide" />
        </p>
        <p>
            <label for="txt_username">Username</label>
            <input type="text" id="txt_username" name="register_username" value="<?php echo (!is_null(register::$p) && !register::$p->success) ? register::$p->get_data('username') : ''; ?>" class="input-wide" />
        </p>
        <p>
            <label for="txt_password">Password</label>
            <input type="password" id="txt_password" name="register_password" class="input-wide" />
        </p>
        <p>
            <label for="txt_password2">Confirm password</label>
            <input type="password" id="txt_password2" name="register_password2" class="input-wide" />
        </p>
        <p>
            <label for="chk_terms">
                <input type="checkbox" id="chk_terms" name="register_terms" value="agree"<?php echo (!is_null(register::$p) && !register::$p->success && register::$p->get_data('terms')) ? ' checked="checked"' : ''; ?> />
                I agree to terms of use and have read and understood the privacy policy.
            </label>
        </p>
        <p>
            <button id="btn_register" name="register" class="button-primary input-wide">
                Sign Up
            </button>
        </p>
    </form>
</div>
<?php if (pages::get_pages('login') > 0): ?>
    <div id="box_auth_register_info">
        <p>Already have an account? <a href="<?php echo pages::get_page_url('login'); ?>">Log In</a></p>
    </div>
<?php endif; ?>
<?php
