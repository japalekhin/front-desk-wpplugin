<?php

use Alekhin\FrontDesk\Admin\Menu;

$locations = get_registered_nav_menus();
$menus = get_terms('nav_menu');
$settings = Menu::get_settings();
?>
<div class="wrap">
    <h2>Front Desk &mdash; Menu Integration</h2>

    <form action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" method="POST">
        <?php wp_nonce_field('front_desk_menu', 'front_desk_menu'); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">Enable Menu Integration</th>
                    <td>
                        <label for="chk_enable_menu_integration">
                            <input type="checkbox" id="chk_enable_menu_integration" name="enable" value="1"<?php echo $settings->enabled ? ' checked="checked"' : ''; ?> />
                            Yes
                        </label>
                        <p class="description">None of the options below matter if this option is not enabled.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Select Menu</th>
                    <td>
                        <select name="menu" class="widefat">
                            <option value="0">(no menu selected)</option>
                            <optgroup label="Menu Locations">
                                <?php if (count($locations) === 0): ?>
                                    <option value="0">(no menu locations found)</option>
                                <?php else: ?>
                                    <?php foreach ($locations as $location_key => $location_name): ?>
                                        <option value="location-<?php echo $location_key; ?>"<?php echo $settings->menu_type === 'location' && $settings->menu_key === $location_key ? ' selected="selected"' : ''; ?>><?php echo $location_name; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </optgroup>
                            <optgroup label="Defined Menus">
                                <?php if (count($menus) === 0): ?>
                                    <option value="0">(no menus defined)</option>
                                <?php else: ?>
                                    <?php foreach ($menus as $menu): ?>
                                        <option value="menu-<?php echo $menu->term_id; ?>"<?php echo $settings->menu_type === 'menu' && $settings->menu_key === $menu->term_id ? ' selected="selected"' : ''; ?>><?php echo $menu->name; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </optgroup>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Show a WP-Admin item</th>
                    <td>
                        <label for="chk_show_wp_admin">
                            <input type="checkbox" id="chk_show_wp_admin" name="show_wp_admin" value="1"<?php echo $settings->show_wp_admin ? ' checked="checked"' : ''; ?> />
                            Yes
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
