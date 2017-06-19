<?php

use FrontEndUser\Admin\pages;

$wp_pages = pages::get_wordpress_pages();
?>
<div class="wrap">
    <h2>Front-End User &mdash; Pages</h2>

    <form action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" method="POST">
        <?php wp_nonce_field('front_end_user_pages', 'front_end_user_pages'); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">Login</th>
                    <td>
                        <select name="page_login" class="widefat">
                            <option value="0">(no page selected)</option>
                            <?php foreach ($wp_pages as $page): ?>
                                <option value="<?php echo $page->ID; ?>"<?php echo $page->ID == pages::get_pages('login') ? ' selected="selected"' : ''; ?>><?php echo $page->post_title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Register</th>
                    <td>
                        <select name="page_register" class="widefat">
                            <option value="0">(no page selected)</option>
                            <?php foreach ($wp_pages as $page): ?>
                                <option value="<?php echo $page->ID; ?>"<?php echo $page->ID == pages::get_pages('register') ? ' selected="selected"' : ''; ?>><?php echo $page->post_title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Password Recovery</th>
                    <td>
                        <select name="page_recover" class="widefat">
                            <option value="0">(no page selected)</option>
                            <?php foreach ($wp_pages as $page): ?>
                                <option value="<?php echo $page->ID; ?>"<?php echo $page->ID == pages::get_pages('recover') ? ' selected="selected"' : ''; ?>><?php echo $page->post_title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Reset Password</th>
                    <td>
                        <select name="page_reset" class="widefat">
                            <option value="0">(no page selected)</option>
                            <?php foreach ($wp_pages as $page): ?>
                                <option value="<?php echo $page->ID; ?>"<?php echo $page->ID == pages::get_pages('reset') ? ' selected="selected"' : ''; ?>><?php echo $page->post_title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <!--tr>
                    <th scope="row">2-Step Authentication</th>
                    <td>
                        <select name="page_verify" class="widefat">
                            <option value="0">(no page selected)</option>
                <?php foreach ($pages as $page): ?>
            <option value="<?php echo $page->ID; ?>"<?php echo $page->ID == pages::get_pages('verify') ? ' selected="selected"' : ''; ?>><?php echo $page->post_title; ?></option>
                <?php endforeach; ?>
                        </select>
                    </td>
                </tr-->
            </tbody>
        </table>
        <p class="submit">
            <button name="save_pages" class="button-primary">
                Save Changes
            </button>
        </p>
    </form>
</div>
