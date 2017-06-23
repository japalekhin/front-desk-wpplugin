<?php

namespace Alekhin\FrontDesk\Admin;

use Alekhin\FrontDesk\FrontDesk;

class Admin {

    static function on_admin_menu() {
        add_menu_page('Front Desk', 'Front Desk', 'manage_options', 'front-desk', [__CLASS__, 'view_admin',], 'dashicons-id-alt', '80.0001');
    }

    static function on_admin_print_scripts() {
        if (stripos(get_current_screen()->base, 'front-desk') !== FALSE) {
            wp_enqueue_style('frd-admin', FrontDesk::get_url('/styles/admin.css'));
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
