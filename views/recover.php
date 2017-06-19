<?php

use FrontEndUser\recover;
use FrontEndUser\Admin\pages;
?>
<div id="box_auth_recover">
    <?php if (!is_null(recover::$p)): ?>
        <p class="notice notice-<?php echo recover::$p->success ? 'success' : 'error'; ?>"><?php echo recover::$p->message; ?></p>
    <?php endif; ?>
    <p>Enter your username or email below and click Request Password Reset. We will email you the instructions on how to reset your password if we find a match.</p>
    <form action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" method="POST">
        <?php wp_nonce_field('front_end_user_recover', 'front_end_user_recover'); ?>
        <p><input type="text" id="txt_username" name="recover_username" value="<?php echo (!is_null(recover::$p)) ? recover::$p->get_data('username') : ''; ?>" placeholder="Username or email" class="input-wide" /></p>
        <p>
            <button id="btn_recover" name="recover_password" class="button-primary input-wide">
                Request Password Reset
            </button>
        </p>
    </form>
</div>
<?php if (pages::get_pages('register') + pages::get_pages('login') > 0): ?>
    <div id="box_auth_recover_info">
        <?php if (pages::get_pages('register') > 0): ?>
            <p>Don't have an account? <a href="<?php echo pages::get_page_url('register'); ?>">Sign Up</a></p>
        <?php endif; ?>
        <?php if (pages::get_pages('login') > 0): ?>
            <p>Remembered your account? <a href="<?php echo pages::get_page_url('login'); ?>">Log In</a></p>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php
