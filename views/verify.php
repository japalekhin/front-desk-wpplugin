<div id="box_auth_twostep">
    <?php if (!is_null(auth_twostep::$ro)): ?>
        <p class="notice notice-<?php echo auth_twostep::$ro->success ? 'success' : 'error'; ?>"><?php echo auth_twostep::$ro->message; ?></p>
    <?php endif; ?>
    <?php $twostep_data = auth_twostep::get_twostep_data(); ?>
    <?php $user_data = get_userdata($twostep_data->user_id); ?>
    <p>Hi <em><?php echo $user_data->data->user_login; ?></em>! Because you opted in to 2-step authentication, you must verify that you own this account using a code we sent to your email.</p>
    <?php if (!is_null($twostep_data) && isset($twostep_data->code)): ?>
        <p class="notice">This is a debug notice that will only show up during development, it's to make testing the system easier. The code is: <strong><?php echo $twostep_data->code; ?></strong></p>
    <?php endif; ?>
    <form action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" method="POST">
        <p>
            <input type="text" id="txt_twostep_code" name="twostep_code" value="<?php echo (!is_null(auth_twostep::$ro)) ? auth_twostep::$ro->get_data('code') : ''; ?>" placeholder="Verification code" class="input-wide" />
            <a href="<?php echo auth_twostep::link_resend_code(); ?>">Get a new code</a>
        </p>
        <p>
            <button id="btn_twostep_verify" name="twostep_verify" class="button-primary input-wide">
                Verify Code
            </button>
        </p>
    </form>
</div>
<?php if (system_pages::get_page_id(system_pages::page_auth_register) > 0 || system_pages::get_page_id(system_pages::page_auth_login) > 0): ?>
    <div id="box_auth_recover_info">
        <?php if (system_pages::get_page_id(system_pages::page_auth_login) > 0): ?>
            <p>Not your account? <a href="<?php echo auth_twostep::link_login_again(); ?>">Log In Again</a></p>
        <?php endif; ?>
        <?php if (system_pages::get_page_id(system_pages::page_auth_register) > 0): ?>
            <p>Don't have an account? <a href="<?php echo system_pages::get_page_url(system_pages::page_auth_register); ?>">Sign Up</a></p>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php
