<?php

namespace Alekhin\FrontDesk\Admin;

use Alekhin\FrontDesk\Admin\Pages;
use Alekhin\FrontDesk\FrontDesk;
use Alekhin\WebsiteHelpers\ReturnObject;

class Theme {

    const session_key_posted = __CLASS__ . '_posted';
    const option_key_themes = 'frd_themes';
    const option_key_custom_css = 'frd_custom_css';
    const option_key_style_version = 'frd_style_version';

    static $p = NULL;

    static function save_theme_settings() {
        $r = new ReturnObject();
        $r->data->themes = [];
        foreach (Pages::get_system_pages() as $page_key => $page_title) {
            $r->data->themes[$page_key] = max(0, min(3, intval(trim(filter_input(INPUT_POST, 'theme_' . $page_key)))));
        }
        $r->data->custom_css = trim(filter_input(INPUT_POST, 'custom_css'));

        if (!wp_verify_nonce(trim(filter_input(INPUT_POST, 'front_desk_theme')), 'front_desk_theme')) {
            $r->message = 'Invalid request session!';
            return $r;
        }

        update_option(self::option_key_themes, $r->data->themes);
        update_option(self::option_key_custom_css, $r->data->custom_css);
        update_option(self::option_key_style_version, time(), TRUE);

        $r->success = TRUE;
        $r->message = 'Your theme settings have been saved!';
        return $r;
    }

    static function get_style_version() {
        return intval(trim(get_option(self::option_key_style_version, '0')));
    }

    static function get_themes($key = NULL) {
        $themes = get_option(self::option_key_themes, []);
        if ($key === NULL) {
            return $themes;
        }
        if (isset($themes[$key])) {
            return intval(trim($themes[$key]));
        }
        return 0;
    }

    static function get_custom_css() {
        return trim(get_option(self::option_key_custom_css, ''));
    }

    static function on_init() {
        if (isset($_SESSION[self::session_key_posted])) {
            self::$p = $_SESSION[self::session_key_posted];
            unset($_SESSION[self::session_key_posted]);
        }
    }

    static function on_admin_menu() {
        add_submenu_page('front-desk', 'Front Desk - Themes', 'Theme', 'manage_options', 'front-desk', [__CLASS__, 'view_admin',]);
    }

    static function on_current_screen() {
        if (get_current_screen()->id !== 'toplevel_page_front-desk') {
            return;
        }

        if (filter_input(INPUT_POST, 'save_changes') !== NULL) {
            self::$p = $_SESSION[self::session_key_posted] = self::save_theme_settings();
            wp_redirect(self::$p->redirect);
            exit;
        }
    }

    static function on_admin_notices() {
        if (get_current_screen()->id !== 'toplevel_page_front-desk') {
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

    static function view_admin() {
        include FrontDesk::get_dir('/views/admin/theme.php');
    }

    static function initialize() {
        add_action('init', [__CLASS__, 'on_init',]);
        add_action('admin_menu', [__CLASS__, 'on_admin_menu',]);
        add_action('current_screen', [__CLASS__, 'on_current_screen',]);
        add_action('admin_notices', [__CLASS__, 'on_admin_notices',]);
    }

}
