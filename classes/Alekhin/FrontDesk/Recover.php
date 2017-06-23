<?php

namespace Alekhin\FrontDesk;

use Alekhin\WebsiteHelpers\ReturnObject;
use Alekhin\WebsiteHelpers\Address;
use Alekhin\WordPressHelpers\Emailer;
use Alekhin\FrontDesk\Admin\Pages;

class Recover {

    const session_key_posted = __CLASS__ . '_posted';

    static $p = NULL;

    static function create_reset_link($username, $key) {
        if (Pages::get_pages('reset') === 0) {
            return home_url();
        }
        $a = new Address(Pages::get_page_url('reset'));
        $a->query['login'] = $username;
        $a->query['key'] = $key;
        return $a->url();
    }

    static function recover_password() {
        $r = new ReturnObject();
        $r->data->username = trim(filter_input(INPUT_POST, 'recover_username'));
        $r->data->user = null;

        if (!wp_verify_nonce(trim(filter_input(INPUT_POST, 'front_desk_recover')), 'front_desk_recover')) {
            $r->message = 'Invalid request session!';
            return $r;
        }

        if (empty($r->data->username)) {
            $r->message = 'Please enter your username or email address.';
            return $r;
        }

        $r->data->user = get_user_by((strpos($r->data->username, '@') ? 'email' : 'login'), $r->data->username);
        if ($r->data->user === FALSE) {
            $r->message = 'There is no user with that ' . (strpos($r->data->username, '@') ? 'email address' : 'username') . '.';
            return $r;
        }

        if (is_wp_error($key = get_password_reset_key($r->data->user))) {
            $r->message = 'Some weird error occurred! This is server side so there\'s nothing wrong on your end.';
            return $r;
        }
        $reset_url = self::create_reset_link($r->data->user->data->user_login, $key);

        $html = '';
        $html .= '<p>Someone has requested to reset your account password in ' . get_bloginfo() . '.</p>';
        $html .= '<p>' . (strpos($r->data->username, '@') ? 'Email address' : 'Username') . ': <strong>' . $r->data->username . '</strong></p>';
        $html .= '<p>&nbsp;</p>';
        $html .= '<p>If this was a mistake, just ignore this email and nothing will happen.</p>';
        $html .= '<p>';
        $html .= 'To reset your password, visit the following address:<br />';
        $html .= '<a href="' . $reset_url . '">' . $reset_url . '</a>';
        $html .= '</p>';

        if (!Emailer::send($r->data->user->data->user_email, 'Password reset link', $html)) {
            $r->message = 'The email could not be sent.';
            // $r->redirect = $reset_url; // testing only
            return $r;
        }

        $r->success = TRUE;
        $r->message = 'A password reset link has been sent to your email address!';
        return $r;
    }

    static function on_init() {
        if (isset($_SESSION[self::session_key_posted])) {
            self::$p = $_SESSION[self::session_key_posted];
            unset($_SESSION[self::session_key_posted]);
        }
    }

    static function on_template_redirect() {
        if (get_the_ID() !== Pages::get_pages('recover')) {
            return;
        }

        if (is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }

        if (isset($_POST['recover_password'])) {
            self::$p = $_SESSION[self::session_key_posted] = self::recover_password();
            wp_redirect(self::$p->redirect);
            exit;
        }
    }

    static function filter_the_content($the_content) {
        if (get_the_ID() !== Pages::get_pages('recover')) {
            return $the_content;
        }

        ob_start();
        include dir . '/views/recover.php';
        return ob_get_clean();
    }

    static function initialize() {
        add_action('init', [__CLASS__, 'on_init',]);
        add_action('template_redirect', [__CLASS__, 'on_template_redirect',]);
        add_filter('the_content', [__CLASS__, 'filter_the_content',]);
    }

}
