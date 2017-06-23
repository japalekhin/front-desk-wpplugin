<?php

use Alekhin\FrontEndUser\Profile;
use Alekhin\FrontEndUser\Admin\Theme;

$profile = Profile::get_current_user_data();
?>
<div id="box_user_profile" class="feu-theme-<?php echo Theme::get_themes('profile'); ?>">
    <?php if (!is_null(Profile::$p)): ?>
        <p class="notice notice-<?php echo Profile::$p->success ? 'success' : 'error'; ?>"><?php echo Profile::$p->message; ?></p>
    <?php endif; ?>
    <form action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" method="POST">
        <?php wp_nonce_field('front_end_user_profile', 'front_end_user_profile'); ?>
        <p class="feu-form-field">
            <span class="feu-form-label"><label for="txt_firstname">First name</label></span>
            <span class="feu-form-control">
                <input type="text" id="txt_firstname" name="firstname" value="<?php echo (!is_null(Profile::$p) && !Profile::$p->success) ? Profile::$p->get_data('firstname') : $profile->meta['first_name'][0]; ?>" placeholder="First name" class="input-wide" />
            </span>
        </p>
        <p class="feu-form-field">
            <span class="feu-form-label"><label for="txt_lastname">Last name</label></span>
            <span class="feu-form-control">
                <input type="text" id="txt_lastname" name="lastname" value="<?php echo (!is_null(Profile::$p) && !Profile::$p->success) ? Profile::$p->get_data('lastname') : $profile->meta['last_name'][0]; ?>" placeholder="Last name" class="input-wide" />
            </span>
        </p>
        <p class="feu-form-field">
            <span class="feu-form-label"><label for="txt_email">Email</label></span>
            <span class="feu-form-control">
                <input type="email" id="txt_email" name="email" value="<?php echo (!is_null(Profile::$p) && !Profile::$p->success) ? Profile::$p->get_data('email') : $profile->user_email; ?>" placeholder="Email" class="input-wide" />
            </span>
        </p>
        <p class="feu-form-field">
            <span class="feu-form-label"><label for="txt_username">Username</label></span>
            <span class="feu-form-control">
                <input type="text" id="txt_username" name="username" value="<?php echo (!is_null(Profile::$p) && !Profile::$p->success) ? Profile::$p->get_data('username') : $profile->user_login; ?>" placeholder="Username" class="input-wide" />
            </span>
        </p>
        <h2>Change Password</h2>
        <p>Please leave the fields below blank if you don't want to change your password.</p>
        <p class="feu-form-field">
            <span class="feu-form-label"><label for="txt_password">Password</label></span>
            <span class="feu-form-control">
                <input type="password" id="txt_password" name="password" placeholder="Password" class="input-wide" />
            </span>
        </p>
        <p class="feu-form-field">
            <span class="feu-form-label"><label for="txt_password2">Confirm password</label></span>
            <span class="feu-form-control">
                <input type="password" id="txt_password2" name="password2" placeholder="Confirm password" class="input-wide" />
            </span>
        </p>
        <p class="feu-form-submit">
            <button id="btn_save_profile" name="save_changes" class="button-primary input-wide">
                Save Changes
            </button>
        </p>
    </form>
</div>
