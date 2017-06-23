<?php

namespace Alekhin\FrontDesk;

use Alekhin\WebsiteHelpers\ReturnObject;
use Alekhin\FrontDesk\Admin\Pages;
use Alekhin\FrontDesk\Admin\Settings;

class Register {

    const session_key_posted = __CLASS__ . '_posted';

    static $p = NULL;

    static function raw_register($data, $bypass = FALSE) {
        $r = new ReturnObject();
        $r->data = $data;
        $r->data->user = FALSE;

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
            $r->message = 'The email address you entered is already registered!';
            return $r;
        }

        if ($r->data->username == '') {
            $r->message = 'Please enter your desired username!';
            return $r;
        }
        if (($user = get_user_by('login', $r->data->username)) !== FALSE) {
            $r->message = 'The username you entered is already in use!';
            return $r;
        }

        if (!$bypass) {
            if (empty($r->data->password)) {
                $r->message = 'Please enter your new desired password.';
                return $r;
            }
            if (strlen($r->data->password) < 8) {
                $r->message = 'Your password must be at least eight characters.';
                return $r;
            }
            if ($r->data->password != $r->data->password2) {
                $r->message = 'The passwords you enter do not match. They must be the same.';
                return $r;
            }

            if (!$r->data->terms) {
                $r->message = 'You must read, understand, and agree to the terms of use and privacy policy!';
                return $r;
            }
        }

        $reg_data = [];
        $reg_data['first_name'] = $r->data->firstname;
        $reg_data['last_name'] = $r->data->lastname;
        $reg_data['user_email'] = $r->data->email;
        $reg_data['user_login'] = $r->data->username;
        $reg_data['user_pass'] = $r->data->password;
        if (is_wp_error($r->data->user_ID = wp_insert_user($reg_data))) {
            $r->message = 'An error occurred while creating the new user.';
            return $r;
        }
        // login by social account, reserved functionality for later
        //if (isset($r->data->social_type) && isset($r->data->social_id)) {
        //    users::set_social_id($r->data->user_ID, $r->data->social_type, $r->data->social_id);
        //}
        $r->data->user = get_user_by('id', $r->data->user_ID);

        $r->success = TRUE;
        $r->message = 'You are now registered!';
        $r->redirect = (Pages::get_pages('login') > 0) ? Pages::get_page_url('login') : $r->redirect;
        return $r;
    }

    static function form_register() {
        $r = new ReturnObject();
        $r->data->user_ID = 0;
        $r->data->firstname = trim(filter_input(INPUT_POST, 'register_firstname'));
        $r->data->lastname = trim(filter_input(INPUT_POST, 'register_lastname'));
        $r->data->email = trim(filter_input(INPUT_POST, 'register_email'));
        $r->data->username = trim(filter_input(INPUT_POST, 'register_username'));
        if (is_null($r->data->password = filter_input(INPUT_POST, 'register_password'))) {
            $r->data->password = '';
        }
        if (is_null($r->data->password2 = filter_input(INPUT_POST, 'register_password2'))) {
            $r->data->password2 = '';
        }
        $r->data->terms = filter_input(INPUT_POST, 'register_terms') == 'agree';

        if (!wp_verify_nonce(trim(filter_input(INPUT_POST, 'front_desk_register')), 'front_desk_register')) {
            $r->message = 'Invalid request session!';
            return $r;
        }

        if (!Settings::users_can_register()) {
            $r->message = 'Sorry user registration is not allowed right now!';
            return $r;
        }

        return self::raw_register($r->data);
    }

    static function on_init() {
        if (isset($_SESSION[self::session_key_posted])) {
            self::$p = $_SESSION[self::session_key_posted];
            unset($_SESSION[self::session_key_posted]);
        }
    }

    static function on_template_redirect() {
        if (get_the_ID() !== Pages::get_pages('register')) {
            return;
        }

        if (is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }

        if (!Settings::users_can_register()) {
            wp_redirect(Pages::get_pages('login') > 0 ? Pages::get_page_url('login') : home_url());
            exit;
        }

        if (isset($_POST['register'])) {
            self::$p = $_SESSION[self::session_key_posted] = self::form_register();
            wp_redirect(self::$p->redirect);
            exit;
        }
    }

    static function filter_the_content($the_content) {
        if (get_the_ID() !== Pages::get_pages('register')) {
            return $the_content;
        }

        ob_start();
        include dir . '/views/register.php';
        return ob_get_clean();
    }

    static function initialize() {
        add_action('init', [__CLASS__, 'on_init',]);
        add_action('template_redirect', [__CLASS__, 'on_template_redirect',]);
        add_filter('the_content', [__CLASS__, 'filter_the_content',]);
    }

}
