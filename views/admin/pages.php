<?php

use Alekhin\FrontDesk\Admin\Pages;

$system_pages = Pages::get_system_pages();
$wp_pages = Pages::get_wordpress_pages();
?>
<div class="wrap">
    <h2>Front Desk &mdash; Pages</h2>

    <form action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" method="POST">
        <?php wp_nonce_field('front_desk_pages', 'front_desk_pages'); ?>
        <table class="form-table">
            <tbody>
                <?php foreach ($system_pages as $sp_key => $sp_label): ?>
                    <tr>
                        <th scope="row"><?php echo $sp_label; ?></th>
                        <td>
                            <select name="page_<?php echo $sp_key; ?>" class="widefat">
                                <option value="0">(no page selected)</option>
                                <?php foreach ($wp_pages as $page): ?>
                                    <option value="<?php echo $page->ID; ?>"<?php echo $page->ID == Pages::get_pages($sp_key) ? ' selected="selected"' : ''; ?>><?php echo $page->post_title; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="submit">
            <button name="save_pages" class="button-primary">
                Save Changes
            </button>
        </p>
    </form>
</div>
