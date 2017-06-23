<?php

namespace Alekhin\FrontDesk;

use Alekhin\WebsiteHelpers\ReturnObject;
use Alekhin\FrontDesk\Admin\Pages;

class Profile {

    const session_key_posted = __CLASS__ . '_posted';

    static $p = NULL;

    static function get_current_user_data() {
        $cu = wp_get_current_user();
        $cu->meta = get_user_meta($cu->ID);
        return $cu;
    }

    static function save_profile() {
        $r = new ReturnObject();
        $r->data->user_ID = get_current_user_id();
        $r->data->firstname = trim(filter_input(INPUT_POST, 'firstname'));
        $r->data->lastname = trim(filter_input(INPUT_POST, 'lastname'));
        $r->data->email = trim(filter_input(INPUT_POST, 'email'));
        $r->data->username = trim(filter_input(INPUT_POST, 'username'));
        if (is_null($r->data->password = filter_input(INPUT_POST, 'password'))) {
            $r->data->password = '';
        }
        if (is_null($r->data->password2 = filter_input(INPUT_POST, 'password2'))) {
            $r->data->password2 = '';
        }

        if (!wp_verify_nonce(trim(filter_input(INPUT_POST, 'front_desk_profile')), 'front_desk_profile')) {
            $r->message = 'Invalid request session!';
            return $r;
        }

        if ($r->data->firstname == '') {
            $r->message = 'Please enter your first name!';
            return $r;
        }
        if ($r->data->lastname == '') {
            $r->message = 'Please enter your last name!';
            return $r;
        }

        if ($r->data->email == '') {
            $r->message = 'Please enter your email address!';
            return $r;
        }
        if (is_email($r->data->email) === FALSE) {
            $r->message = 'Please enter a valid email address!';
            return $r;
        }
        if (($user = get_user_by('email', $r->data->email)) !== FALSE) {
            if ($user->ID != $r->data->user_ID) {
                $r->message = 'The email address you entered is already registered!';
                return $r;
            }
        }

        if ($r->data->username == '') {
            $r->message = 'Please enter your desired username!';
            return $r;
        }
        if (($user = get_user_by('login', $r->data->username)) !== FALSE) {
            if ($user->ID != $r->data->user_ID) {
                $r->message = 'The username you entered is already in use!';
                return $r;
            }
        }

        if (!empty($r->data->password)) {
            if (strlen($r->data->password) < 8) {
                $r->message = 'Your password must be at least eight characters.';
                return $r;
            }
            if ($r->data->password != $r->data->password2) {
                $r->message = 'The passwords you enter do not match. They must be the same.';
                return $r;
            }
        }

        $pd = [];
        $pd['ID'] = $r->data->user_ID;
        $pd['first_name'] = $r->data->firstname;
        $pd['last_name'] = $r->data->lastname;
        $pd['user_email'] = $r->data->email;
        $pd['user_login'] = $r->data->username;
        if (!empty($r->data->password)) {
            $pd['user_pass'] = $r->data->password;
        }
        if (is_wp_error(wp_update_user($pd))) {
            $r->message = 'An error occurred while saving your changes!';
            return $r;
        }

        $r->success = TRUE;
        $r->message = 'Your profile changes have been saved!';
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
