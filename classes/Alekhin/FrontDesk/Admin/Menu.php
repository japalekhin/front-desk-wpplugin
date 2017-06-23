<?php

namespace Alekhin\FrontDesk\Admin;

use Alekhin\WebsiteHelpers\ReturnObject;
use \Alekhin\FrontDesk\FrontDesk;

class Menu {

    const session_key_posted = __CLASS__ . '_posted';
    const option_key_menu_settings = 'frd_menu_integration';

    static $p = NULL;

    static function get_settings () {
        $d = new \stdClass();
        $d->enabled = FALSE;
        $d->menu_type = 'none';
        $d->menu_key = '0';
        $d->show_wp_admin = FALSE;
        return get_option(self::option_key_menu_settings, $d);
    }

    static function save_settings () {
        $r = new ReturnObject();
        $r->data->enabled = intval(trim(filter_input(INPUT_POST, 'enable'))) === 1;
        $r->data->menu_type = 'none';
        $r->data->menu_key = '0';
        $r->data->show_wp_admin = intval(trim(filter_input(INPUT_POST, 'show_wp_admin'))) === 1;

        $selected = explode('-', trim(filter_input(INPUT_POST, 'menu')), 2);
        if (count($selected) > 1 && in_array($selected[0], ['location', 'menu',])) {
            $r->data->menu_type = $selected[0];
            $r->data->menu_key = trim($selected[1]);
            if($r->data->menu_type === 'menu'){
                $r->data->menu_key = intval($r->data->menu_key);
            }
        }

        if (!wp_verify_nonce(trim(filter_input(INPUT_POST, 'front_desk_menu')), 'front_desk_menu')) {
            $r->message = 'Invalid request session!';
            return $r;
        }

        update_option(self::option_key_menu_settings, $r->data, $r->data->enabled);

        $r->success = TRUE;
        $r->message = 'Your changes have been saved!';
        return $r;
    }

    static function on_init() {
        if (isset($_SESSION[self::session_key_posted])) {
            self::$p = $_SESSION[self::session_key_posted];
            unset($_SESSION[self::session_key_posted]);
        }
    }

    static function on_admin_menu () {
        add_submenu_page('front-desk', 'Front Desk - Menu Integration', 'Menu Integration', 'manage_options', 'front-desk-menu', [__CLASS__, 'view_admin',]);
    }

    static function on_current_screen() {
        if (get_current_screen()->id !== 'front-desk_page_front-desk-menu') {
            return;
        }

        if (filter_input(INPUT_POST, 'save_changes') !== NULL) {
            self::$p = $_SESSION[self::session_key_posted] = self::save_settings();
            wp_redirect(self::$p->redirect);
            exit;
        }
    }

    static function on_admin_notices() {
        if (get_current_screen()->id !== 'front-desk_page_front-desk-menu') {
            return;
        }

        if (self::$p === NULL) {
            return;
        }

        $classes = [];
        $classes[] = 'notice';
        $classes[] = 'is-dismissible';
        $classes[] = 'notice-' . (self::$p->success ? 'success' : 'error');

        echo '<div class="' . implode(' ', $classes) . '"><p>';
        echo self::$p->message;
        echo '</p></div>';
    }

    static function view_admin () {
        include FrontDesk::get_dir('/views/admin/menu.php');
    }

    static function initialize () {
        add_action('init', [__CLASS__, 'on_init',]);
        add_action('admin_menu', [__CLASS__, 'on_admin_menu',]);
        add_action('current_screen', [__CLASS__, 'on_current_screen',]);
        add_action('admin_notices', [__CLASS__, 'on_admin_notices',]);
    }
}
