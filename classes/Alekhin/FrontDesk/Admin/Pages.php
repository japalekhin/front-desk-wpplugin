<?php

namespace Alekhin\FrontDesk\Admin;

use \Alekhin\WebsiteHelpers\ReturnObject;
use \Alekhin\FrontDesk\FrontDesk;

class Pages {

    const session_key_posted = __CLASS__ . '_posted';
    const option_key_pages = 'frd_pages';

    static $p = NULL;

    static function get_wordpress_pages() {
        $attr = new \stdClass();
        $attr->posts_per_page = -1;
        $attr->orderby = 'title';
        $attr->order = 'ASC';
        $attr->post_type = 'page';
        return get_posts((array) $attr);
    }

    static function get_system_pages() {
        $pages = [];

        $pages['login'] = 'Login';
        $pages['register'] = 'Register';
        $pages['recover'] = 'Password Recovery';
        $pages['reset'] = 'Reset Password';
        $pages['profile'] = 'Profile';

        return $pages;
    }

    static function save_pages() {
        $r = new ReturnObject();
        $r->data->pages = [];
        foreach (self::get_system_pages() as $page_key => $page_title) {
            $r->data->pages[$page_key] = intval(trim(filter_input(INPUT_POST, 'page_' . $page_key)));
        }

        if (!wp_verify_nonce(trim(filter_input(INPUT_POST, 'front_desk_pages')), 'front_desk_pages')) {
            $r->message = 'Invalid request session!';
            return $r;
        }

        update_option(self::option_key_pages, $r->data->pages);

        $r->success = TRUE;
        $r->message = 'Your pages have been saved!';
        return $r;
    }

    static function get_pages($key = NULL) {
        $pages = get_option(self::option_key_pages, []);
        if ($key === NULL) {
            return $pages;
        }
        if (isset($pages[$key])) {
            return intval(trim($pages[$key]));
        }
        return 0;
    }

    static function get_page_url($key) {
        $page_id = self::get_pages($key);
        if ($page_id === 0) {
            return home_url();
        }
        return get_permalink($page_id);
    }

    static function on_init() {
        if (isset($_SESSION[self::session_key_posted])) {
            self::$p = $_SESSION[self::session_key_posted];
            unset($_SESSION[self::session_key_posted]);
        }
    }

    static function on_admin_menu() {
        add_submenu_page('front-desk', 'Front Desk - Pages', 'Pages', 'manage_options', 'front-desk-pages', [__CLASS__, 'view_admin',]);
    }

    static function on_current_screen() {
        if (get_current_screen()->id !== 'front-desk_page_front-desk-pages') {
            return;
        }

        if (filter_input(INPUT_POST, 'save_pages') !== NULL) {
            self::$p = $_SESSION[self::session_key_posted] = self::save_pages();
            wp_redirect(self::$p->redirect);
            exit;
        }
    }

    static function on_admin_notices() {
        if (get_current_screen()->id !== 'front-desk_page_front-desk-pages') {
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
        include FrontDesk::get_dir('/views/admin/pages.php');
    }

    static function initialize() {
        add_action('init', [__CLASS__, 'on_init',]);
        add_action('admin_menu', [__CLASS__, 'on_admin_menu',]);
        add_action('current_screen', [__CLASS__, 'on_current_screen',]);
        add_action('admin_notices', [__CLASS__, 'on_admin_notices',]);
    }

}
