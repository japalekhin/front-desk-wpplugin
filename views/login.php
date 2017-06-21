<?php

use Alekhin\FrontEndUser\Login;
use Alekhin\FrontEndUser\Admin\Pages;
?>

<div id="box_auth_login">
    <?php if (!is_null(Login::$p)): ?>
        <p class="notice notice-<?php echo Login::$p->success ? 'success' : 'error'; ?>"><?php echo Login::$p->message; ?></p>
    <?php endif; ?>
    <form action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" method="POST">
        <?php wp_nonce_field('front_end_user_login', 'front_end_user_login'); ?>
        <p><input type="text" id="txt_username" name="login_username" value="<?php echo (!is_null(Login::$p)) ? Login::$p->get_data('username') : ''; ?>" placeholder="Username or email" class="input-wide" /></p>
        <p>
            <input type="password" id="txt_password" name="login_password" placeholder="Password" class="input-wide" />
            <?php if (Pages::get_pages('recover') > 0): ?>
                <a href="<?php echo Pages::get_page_url('recover'); ?>" class="button-link">Forgot password?</a>
            <?php endif; ?>
        </p>
        <p>
            <label for="chk_remember">
                <input type="checkbox" id="chk_remember" name="login_remember" value="yes"<?php echo (!is_null(Login::$p) && Login::$p->get_data('remember')) ? ' checked="checked"' : ''; ?> />
                Remember me
            </label>
        </p>
        <p>
            <button id="btn_login" name="login" class="button-primary input-wide">
                Log In
            </button>
        </p>
    </form>
</div>
<?php if (Pages::get_pages('register') > 0): ?>
    <div id="box_login_info">
        <p>Don't have an account? <a href="<?php echo Pages::get_page_url('register'); ?>">Sign Up</a></p>
    </div>
<?php endif; ?>
<?php
