<?php

use Alekhin\FrontDesk\Admin\Theme;
use Alekhin\FrontDesk\Admin\Pages;

$system_pages = Pages::get_system_pages();
?>
<div id="page_admin_theme" class="wrap">
    <h2>Front Desk &mdash; Theme</h2>

    <form action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" method="POST">
        <?php wp_nonce_field('front_desk_theme', 'front_desk_theme'); ?>
        <table class="form-table">
            <tbody>
                <?php foreach ($system_pages as $sp_key => $sp_label): ?>
                    <tr class="theme-row">
                        <th scope="row"><?php echo $sp_label; ?> Form</th>
                        <td>
                            <input type="radio" id="rad_<?php echo $sp_key; ?>_style_0" name="theme_<?php echo $sp_key; ?>" value="0"<?php echo Theme::get_themes($sp_key) == 0 ? ' checked="checked"' : ''; ?> /><!--
                            --><label for="rad_<?php echo $sp_key; ?>_style_0" class="theme-0">
                                None
                            </label><!--
                            <?php for ($counter = 1; $counter < 4; $counter++): ?>
                                --><input type="radio" id="rad_<?php echo $sp_key; ?>_style_<?php echo $counter; ?>" name="theme_<?php echo $sp_key; ?>" value="<?php echo $counter; ?>"<?php echo Theme::get_themes($sp_key) == $counter ? ' checked="checked"' : ''; ?> /><!--
                                --><label for="rad_<?php echo $sp_key; ?>_style_<?php echo $counter; ?>" class="theme-<?php echo $counter; ?>">
                                    Style #<?php echo $counter; ?>
                                </label><!--
                            <?php endfor; ?>
                            -->
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th scope="row">Custom CSS</th>
                    <td>
                        <textarea id="txt_custom_css" name="custom_css" class="widefat" rows="10" placeholder="/* insert custom CSS code here */"><?php echo Theme::get_custom_css(); ?></textarea>
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