<?php

namespace Alekhin\FrontEndUser;

use Alekhin\WebsiteHelpers\ReturnObject;
use Alekhin\FrontEndUser\Admin\Pages;

class Profile {

    const session_key_posted = __CLASS__ . '_posted';

    static $p = NULL;

    static function save_profile() {
        $r = new ReturnObject();

        return $r;
    }

    static function on_init() {
        if (isset($_SESSION[self::session_key_posted])) {
            self::$p = $_SESSION[self::session_key_posted];
            unset($_SESSION[self::session_key_posted]);
        }
    }

    static function on_template_redirect() {
        if (get_the_ID() !== Pages::get_pages('profile')) {
            return;
        }

        if (!is_user_logged_in()) {
            wp_redirect(Pages::get_pages('login') > 0 ? Pages::get_page_url('login') : home_url());
            exit;
        }

        if (isset($_POST['save_changes'])) {
            self::$p = $_SESSION[self::session_key_posted] = self::save_profile();
            wp_redirect(self::$p->redirect);
            exit;
        }
    }

    static function filter_the_content($the_content) {
        if (get_the_ID() !== Pages::get_pages('profile')) {
            return $the_content;
        }

        ob_start();
        include dir . '/views/profile.php';
        return ob_get_clean();
    }

    static function initialize() {
        add_action('init', [__CLASS__, 'on_init',]);
        add_action('template_redirect', [__CLASS__, 'on_template_redirect',]);
        add_filter('the_content', [__CLASS__, 'filter_the_content',]);
    }

}
