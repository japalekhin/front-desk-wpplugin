<?php

namespace Alekhin\FrontDesk\Admin;

use Alekhin\WebsiteHelpers\ReturnObject;
use \Alekhin\FrontDesk\FrontDesk;

class Settings {

    const session_key_posted = __CLASS__ . '_posted';
    const option_key_users_can_register = 'users_can_register';
    const option_key_disable_default_login = 'frd_disable_default_login';
    const option_key_restrict_wp_admin = 'frd_restrict_wp_admin';
    const option_key_hide_admin_toolbar = 'frd_hide_admin_toolbar';

    static $p = NULL;

    static function users_can_register() {
        return intval(trim(get_option(self::option_key_users_can_register, 0))) === 1;
    }

    static function disable_default_login() {
        return intval(trim(get_option(self::option_key_disable_default_login, 0))) === 1;
    }

    static function restrict_wp_admin() {
        return intval(trim(get_option(self::option_key_restrict_wp_admin, 0))) === 1;
    }

    static function hide_admin_toolbar() {
        return intval(trim(get_option(self::option_key_hide_admin_toolbar, 0))) === 1;
    }

    static function save_changes() {
        $r = new ReturnObject();
        $r->data->users_can_register = intval(trim(filter_input(INPUT_POST, 'users_can_register'))) === 1;
        $r->data->disable_default_login = intval(trim(filter_input(INPUT_POST, 'disable_default_login'))) === 1;
        $r->data->restrict_wp_admin = intval(trim(filter_input(INPUT_POST, 'restrict_wp_admin'))) === 1;
        $r->data->hide_admin_toolbar = intval(trim(filter_input(INPUT_POST, 'hide_admin_toolbar'))) === 1;

        if (!wp_verify_nonce(trim(filter_input(INPUT_POST, 'front_desk_settings')), 'front_desk_settings')) {
            $r->message = 'Invalid request session!';
            return $r;
        }

        update_option(self::option_key_users_can_register, $r->data->users_can_register ? 1 : 0, TRUE);
        update_option(self::option_key_disable_default_login, $r->data->disable_default_login ? 1 : 0);
        update_option(self::option_key_restrict_wp_admin, $r->data->restrict_wp_admin ? 1 : 0);
        update_option(self::option_key_hide_admin_toolbar, $r->data->hide_admin_toolbar ? 1 : 0, TRUE);

        $r->success = TRUE;
        $r->message = 'Your changes have been saved!';
        return $r;
    }

    static function on_init() {
        // disable /wp-login.php page if:
        // - there's a login page set
        // - not logging out
        global $pagenow;
        if ('wp-login.php' === $pagenow && (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] !== 'logout')) && self::disable_default_login() && pages::get_pages('login') > 0) {
            wp_redirect(pages::get_page_url('login'));
            exit;
        }

        // hide admin toolbar
        if (self::hide_admin_toolbar() && !current_user_can('manage_options')) {
            add_filter('show_admin_bar', '__return_false');
        }

        if (isset($_SESSION[self::session_key_posted])) {
            self::$p = $_SESSION[self::session_key_posted];
            unset($_SESSION[self::session_key_posted]);
        }
    }

    static function on_admin_init() {
        // redirect to home if:
        // - /wp-admin is accessed
        // - option to restrict wp_admin is enabled
        // - user is not an admin
        // - this is not an ajax request
        if (self::restrict_wp_admin() && !current_user_can('manage_options') && !(defined('DOING_AJAX') && DOING_AJAX)) {
            wp_redirect(home_url());
            exit;
        }
    }

    static function on_admin_menu() {
        add_submenu_page('front-desk', 'Front  - Settings', 'Settings', 'manage_options', 'front-desk-settings', [__CLASS__, 'view_admin',]);
    }

    static function on_current_screen() {
        if (get_current_screen()->id !== 'front-desk_page_front-desk-settings') {
            return;
        }

        if (filter_input(INPUT_POST, 'save_changes') !== NULL) {
            self::$p = $_SESSION[self::session_key_posted] = self::save_changes();
            wp_redirect(self::$p->redirect);
            exit;
        }
    }

    static function on_admin_notices() {
        if (get_current_screen()->id !== 'front-desk_page_front-desk-settings') {
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
        include FrontDesk::get_dir('/views/admin/settings.php');
    }

    static function initialize() {
        add_action('init', [__CLASS__, 'on_init',]);
        add_action('admin_init', [__CLASS__, 'on_admin_init',]);
        add_action('admin_menu', [__CLASS__, 'on_admin_menu',]);
        add_action('current_screen', [__CLASS__, 'on_current_screen',]);
        add_action('admin_notices', [__CLASS__, 'on_admin_notices',]);
    }

}
