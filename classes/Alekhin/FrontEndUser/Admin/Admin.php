<?php

namespace Alekhin\FrontEndUser\Admin;

class Admin {

    static function on_after_setup_theme() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    static function on_admin_menu() {
        add_menu_page('Front-End User Pages', 'Front-End User', 'manage_options', 'front-end-user', [__CLASS__, 'view_admin',], 'dashicons-id-alt', '80.0001');
    }

    static function view_admin(){
        echo '';
    }

    static function initialize() {
        add_action('after_setup_theme', [__CLASS__, 'on_after_setup_theme',]);
        add_action('admin_menu', [__CLASS__, 'on_admin_menu',]);
    }

}
