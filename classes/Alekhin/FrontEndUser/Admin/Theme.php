<?php

namespace Alekhin\FrontEndUser\Admin;

class Theme {

    static function on_admin_menu() {
        add_submenu_page('front-end-user', 'Front-End User - Themes', 'Theme', 'manage_options', 'front-end-user', [__CLASS__, 'view_admin',]);
    }

    static function view_admin() {
        include \FrontEndUser\dir . '/views/admin/theme.php';
    }

    static function initialize() {
        add_action('admin_menu', [__CLASS__, 'on_admin_menu',]);
    }

}
