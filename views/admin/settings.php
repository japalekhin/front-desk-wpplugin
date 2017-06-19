<?php

use FrontEndUser\Admin\settings;
?>
<div class="wrap">
    <h2>Front-End User &mdash; Settings</h2>

    <form action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" method="POST">
        <?php wp_nonce_field('front_end_user_settings', 'front_end_user_settings'); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">Membership</th>
                    <td>
                        <label for="chk_users_can_register">
                            <input type="checkbox" id="chk_users_can_register" name="users_can_register" value="1"<?php echo settings::users_can_register() ? ' checked="checked"' : ''; ?> />
                            Anyone can register
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Disable Default Login</th>
                    <td>
                        <label for="chk_disable_default_login">
                            <input type="checkbox" id="chk_disable_default_login" name="disable_default_login" value="1"<?php echo settings::disable_default_login() ? ' checked="checked"' : ''; ?> />
                            Disable <code>/wp-login.php</code>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Restrict WP-Admin</th>
                    <td>
                        <label for="chk_restrict_wp_admin">
                            <input type="checkbox" id="chk_restrict_wp_admin" name="restrict_wp_admin" value="1"<?php echo settings::restrict_wp_admin() ? ' checked="checked"' : ''; ?> />
                            Restrict <code>/wp-admin</code> for non-administrators
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Hide Admin Toolbar</th>
                    <td>
                        <label for="chk_hide_admin_toolbar">
                            <input type="checkbox" id="chk_hide_admin_toolbar" name="hide_admin_toolbar" value="1"<?php echo settings::hide_admin_toolbar() ? ' checked="checked"' : ''; ?> />
                            Hide <strong>admin toolbar</strong> for non-administrators
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="submit">
            <button name="save_changes" class="button-primary">
                Save Changes
            </button>
        </p>
    </form>
</div>