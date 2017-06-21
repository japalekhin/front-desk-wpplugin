<?php

namespace Alekhin\FrontEndUser\Admin;

use Alekhin\FrontEndUser\FrontEndUser;

class Admin {

    static function on_admin_menu() {
        add_menu_page('Front-End User Pages', 'Front-End User', 'manage_options', 'front-end-user', [__CLASS__, 'view_admin',], 'dashicons-id-alt', '80.0001');
    }

    static function on_admin_print_scripts() {
        if (stripos(get_current_screen()->base, 'front-end-user') !== FALSE) {
            wp_enqueue_style('feu-admin', FrontEndUser::get_url('/styles/admin.css'));
        }
    }

    static function view_admin() {
        echo '';
    }

    static function initialize() {
        add_action('admin_menu', [__CLASS__, 'on_admin_menu',]);
        add_action('admin_print_scripts', [__CLASS__, 'on_admin_print_scripts',]);
    }

}
