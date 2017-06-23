<?php

namespace Alekhin\FrontDesk;

use Alekhin\WebsiteHelpers\ReturnObject;
use Alekhin\FrontDesk\Admin\Pages;

class Reset {

    const session_key_posted = __CLASS__ . '_posted';

    static $p = NULL;

    static function get_reset_cookie_name() {
        return 'cbm_reset_password_' . COOKIEHASH;
    }

    static function get_reset_username_from_cookie() {
        $login = '';
        $cookie_name = self::get_reset_cookie_name();
        if (isset($_COOKIE[$cookie_name]) && 0 < strpos($_COOKIE[$cookie_name], ':')) {
            $cookie_data = explode(':', wp_unslash($_COOKIE[$cookie_name]), 2);
            $login = $cookie_data[0];
        }
        return $login;
    }

    static function get_reset_key_from_cookie() {
        $key = '';
        $cookie_name = self::get_reset_cookie_name();
        if (isset($_COOKIE[$cookie_name]) && 0 < strpos($_COOKIE[$cookie_name], ':')) {
            $cookie_data = explode(':', wp_unslash($_COOKIE[$cookie_name]), 2);
            $key = $cookie_data[1];
        }
        return $key;
    }

    static function reset_preparation() {
        $r = new ReturnObject();
        $r->data->key = isset($_GET['key']) ? $_GET['key'] : NULL;
        $r->data->username = isset($_GET['login']) ? $_GET['login'] : NULL;

        $cookie_name = self::get_reset_cookie_name();
        if (empty($r->data->key) || empty($r->data->username)) {
            $r->message = 'Invalid password reset link!';
            return $r;
        }

        $r->data->user = check_password_reset_key($r->data->key, $r->data->username);
        if (!$r->data->user || is_wp_error($r->data->user)) {
            setcookie($cookie_name, ' ', time() - YEAR_IN_SECONDS, Pages::get_page_url('reset'), COOKIE_DOMAIN, is_ssl(), TRUE);
            if ($r->data->user && $r->data->user->get_error_code() === 'expired_key') {
                $r->message = 'The reset password window has already expired!';
            } else {
                $r->message = 'The reset password key is invalid!';
            }
            return $r;
        }

        $value = sprintf('%s:%s', wp_unslash($r->data->username), wp_unslash($r->data->key));
        setcookie($cookie_name, $value, 0, Pages::get_page_url('reset'), COOKIE_DOMAIN, is_ssl(), TRUE);

        $r->success = TRUE;
        $r->message = 'Reset password link validated!';
        $r->redirect = Pages::get_page_url('reset');
        return $r;
    }

    static function reset_password() {
        $r = new ReturnObject();
        $r->data->key = isset($_POST['reset_key']) ? $_POST['reset_key'] : NULL;
        $r->data->username = isset($_POST['reset_username']) ? $_POST['reset_username'] : NULL;
        $r->data->password1 = isset($_POST['reset_password1']) ? $_POST['reset_password1'] : NULL;
        $r->data->password2 = isset($_POST['reset_password2']) ? $_POST['reset_password2'] : NULL;

        $cookie_name = self::get_reset_cookie_name();

        $r->message = 'Invalid password reset attempt!';
        if (empty($r->data->key) || empty($r->data->username)) {
            return $r;
        }
        if (!hash_equals(self::get_reset_key_from_cookie(), $r->data->key)) {
            return $r;
        }
        if (!hash_equals(self::get_reset_username_from_cookie(), $r->data->username)) {
            return $r;
        }

        if (!wp_verify_nonce(trim(filter_input(INPUT_POST, 'front_desk_reset')), 'front_desk_reset')) {
            $r->message = 'Invalid request session!';
            return $r;
        }

        $r->data->user = check_password_reset_key($r->data->key, $r->data->username);
        if (!$r->data->user || is_wp_error($r->data->user)) {
            setcookie($cookie_name, ' ', time() - YEAR_IN_SECONDS, Pages::get_page_url('reset'), COOKIE_DOMAIN, is_ssl(), TRUE);
            if ($r->data->user && $r->data->user->get_error_code() === 'expired_key') {
                $r->message = 'The reset password window has already expired!';
            }
            return $r;
        }

        if (empty($r->data->password1)) {
            $r->message = 'Please enter your new desired password.';
            return $r;
        }
        if (strlen($r->data->password1) < 8) {
            $r->message = 'Your password must be at least eight characters.';
            return $r;
        }
        if ($r->data->password1 != $r->data->password2) {
            $r->message = 'The passwords you enter do not match. They must be the same.';
            return $r;
        }

        reset_password($r->data->user, $r->data->password1);
        setcookie($cookie_name, ' ', time() - YEAR_IN_SECONDS, Pages::get_page_url('reset'), COOKIE_DOMAIN, is_ssl(), TRUE);

        $r->success = TRUE;
        $r->message = 'Your password has been changed successfully!';
        return $r;
    }

    static function on_init() {
        if (isset($_SESSION[self::session_key_posted])) {
            self::$p = $_SESSION[self::session_key_posted];
            unset($_SESSION[self::session_key_posted]);
        }
    }

    static function on_template_redirect() {
        if (get_the_ID() !== Pages::get_pages('reset')) {
            return;
        }

        if (is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }

        if (isset($_GET['login']) || isset($_GET['key'])) {
            self::$p = $_SESSION[self::session_key_posted] = self::reset_preparation();
            wp_redirect(self::$p->redirect);
            exit;
        }

        if (isset($_POST['reset_password'])) {
            self::$p = $_SESSION[self::session_key_posted] = self::reset_password();
            wp_redirect(self::$p->redirect);
            exit;
        }

        if (self::$p === NULL) {
            wp_redirect(home_url());
            exit;
        }
    }

    static function filter_the_content($the_content) {
        if (get_the_ID() !== Pages::get_pages('reset')) {
            return $the_content;
        }

        ob_start();
        include dir . '/views/reset.php';
        return ob_get_clean();
    }

    static function initialize() {
        add_action('init', [__CLASS__, 'on_init',]);
        add_action('template_redirect', [__CLASS__, 'on_template_redirect',]);
        add_filter('the_content', [__CLASS__, 'filter_the_content',]);
    }

}
