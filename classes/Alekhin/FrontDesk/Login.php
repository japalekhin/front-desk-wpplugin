<?php

namespace Alekhin\FrontDesk;

use Alekhin\WebsiteHelpers\ReturnObject;
use Alekhin\FrontDesk\Admin\Pages;

class Login {

    const session_key_posted = __CLASS__ . '_posted';

    static $p = NULL;

    static function login() {
        $r = new ReturnObject();

        if (is_null($r->data->username = filter_input(INPUT_POST, 'login_username'))) {
            $r->data->username = '';
        }
        $r->data->username = trim($r->data->username);
        if (is_null($r->data->password = filter_input(INPUT_POST, 'login_password'))) {
            $r->data->password = '';
        }
        $r->data->remember = filter_input(INPUT_POST, 'login_remember') == 'yes';

        if (!wp_verify_nonce(trim(filter_input(INPUT_POST, 'front_desk_login')), 'front_desk_login')) {
            $r->message = 'Invalid request session!';
            return $r;
        }

        if ($r->data->username == '') {
            $r->message = 'Please enter your username or email!';
            return $r;
        }

        if ($r->data->password == '') {
            $r->message = 'Please enter your password!';
            return $r;
        }

        $creds = [
            'user_login' => $r->data->username,
            'user_password' => $r->data->password,
            'remember' => $r->data->remember,
        ];
        $user = wp_signon($creds, is_ssl());

        if (is_wp_error($user)) {
            $r->message = 'Invalid login credentials!';
            return $r;
        }

        //if (user_profile::get_two_step($user->ID) && system_pages::get_page_id(system_pages::page_auth_twostep) > 0) {
        //    wp_clear_auth_cookie();
        //    auth_twostep::set_twostep_data($user->ID, $r->data->remember);
        //    wp_redirect(system_pages::get_page_url(system_pages::page_auth_twostep));
        //    exit;
        //}

        $r->success = TRUE;
        $r->message = 'You are now logged in!';
        $r->redirect = home_url();
        return $r;
    }

    static function on_init() {
        if (isset($_SESSION[self::session_key_posted])) {
            self::$p = $_SESSION[self::session_key_posted];
            unset($_SESSION[self::session_key_posted]);
        }
    }

    static function on_template_redirect() {
        if (get_the_ID() !== Pages::get_pages('login')) {
            return;
        }

        if (is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }

        if (isset($_POST['login'])) {
            self::$p = $_SESSION[self::session_key_posted] = self::login();
            wp_redirect(self::$p->redirect);
            exit;
        }
    }

    static function filter_the_content($the_content) {
        if (get_the_ID() !== Pages::get_pages('login')) {
            return $the_content;
        }

        ob_start();
        include dir . '/views/login.php';
        return ob_get_clean();
    }

    static function initialize() {
        add_action('init', [__CLASS__, 'on_init',]);
        add_action('template_redirect', [__CLASS__, 'on_template_redirect',]);
        add_filter('the_content', [__CLASS__, 'filter_the_content',]);
    }

}
