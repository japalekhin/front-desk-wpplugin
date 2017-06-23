<?php

class auth_twostep {

    const session_key_posted = 'cbm_auth_twostep_posted';
    const session_key_twostep_data = 'cbm_auth_twostep_data';

    static $ro = NULL;

    static function link_login_again() {
        if (system_pages::get_page_id(system_pages::page_auth_twostep) > 0) {
            $uo = new address(system_pages::get_page_url(system_pages::page_auth_twostep));
            $uo->query['login'] = 'start-over';
            return $uo->url();
        }
        return home_url();
    }

    static function link_resend_code() {
        if (system_pages::get_page_id(system_pages::page_auth_twostep) > 0) {
            $uo = new address(system_pages::get_page_url(system_pages::page_auth_twostep));
            $uo->query['get'] = 'new-code';
            return $uo->url();
        }
        return home_url();
    }

    static function generate_code() {
        $code_source = 'ABCDEFGHIKLNOPQRSTUVWXYZ01356789';
        $code = '';
        for ($counter = 0; $counter < 8; $counter++) {
            $code .= $code_source[rand(0, strlen($code_source) - 1)];
        }
        return $code;
    }

    static function set_twostep_data($user_id, $remember) {
        $so = new stdClass();
        $so->user_id = $user_id;
        $so->remember = $remember;
        $so->code = self::generate_code();
        $_SESSION[self::session_key_twostep_data] = $so;
        self::send_twostep_data();
    }

    static function get_twostep_data() {
        if (isset($_SESSION[self::session_key_twostep_data])) {
            return $_SESSION[self::session_key_twostep_data];
        }
        return NULL;
    }

    static function clear_twostep_data() {
        unset($_SESSION[self::session_key_twostep_data]);
    }

    static function set_new_code() {
        $ro = new return_object();

        if (is_null($_SESSION[self::session_key_twostep_data])) {
            $ro->message = 'You are not logged in! Please start over.';
            return $ro;
        }
        if (!isset($_SESSION[self::session_key_twostep_data]->code)) {
            $ro->message = 'You are not logged in! Please start over.';
            return $ro;
        }
        $_SESSION[self::session_key_twostep_data]->code = self::generate_code();
        self::send_twostep_data();

        $ro->message = 'We have created a new code and sent it to your email.';
        $ro->success = TRUE;
        return $ro;
    }

    static function validate_code($code) {
        if (is_null($_SESSION[self::session_key_twostep_data])) {
            return FALSE;
        }
        if (!isset($_SESSION[self::session_key_twostep_data]->code)) {
            $ro->message = 'You are not logged in! Please start over.';
            return FALSE;
        }
        return $_SESSION[self::session_key_twostep_data]->code == $code;
    }

    static function send_twostep_data() {
        if (is_null($_SESSION[self::session_key_twostep_data])) {
            return;
        }
        $so = $_SESSION[self::session_key_twostep_data];
        if (!isset($so->code) || !isset($so->user_id) || !isset($so->remember)) {
            return;
        }
        $user = get_user_by('id', $so->user_id);

        $html = '';
        $html .= '<p>Hi ' . $user->data->user_login . '! Because you opted in to 2-step authentication in ' . get_bloginfo() . ', you must verify that you own this account using the code below.</p>';
        $html .= '<p><strong><big>' . $so->code . '</big></strong></p>';
        $html .= '<p>If you did not log in to your account, please consider changing your password in order to secure your account.</p>';

        emailer::send($user->data->user_email, 'Login verification code', $html);
    }

    static function verify() {
        $ro = new return_object();
        $ro->data->code = filter_input(INPUT_POST, 'twostep_code');

        if (is_null($_SESSION[self::session_key_twostep_data])) {
            $ro->message = 'You are not logged in! Please start over.';
            return $ro;
        }
        $so = $_SESSION[self::session_key_twostep_data];
        if (!isset($so->code) || !isset($so->user_id) || !isset($so->remember)) {
            $ro->message = 'You are not logged in! Please start over.';
            return $ro;
        }
        if (empty($ro->data->code)) {
            $ro->message = 'Please enter the verification code we sent to your email!';
            return $ro;
        }

        if (!self::validate_code($ro->data->code)) {
            $ro->message = 'The code you entered is incorrect!';
            return $ro;
        }

        wp_clear_auth_cookie();
        wp_set_current_user($so->user_id);
        wp_set_auth_cookie($so->user_id, $so->remember, is_ssl());

        self::clear_twostep_data();

        $ro->success = TRUE;
        $ro->message = 'You are now logged in!';
        return $ro;
    }

    static function on_init() {
        if (isset($_SESSION[self::session_key_posted])) {
            self::$ro = $_SESSION[self::session_key_posted];
            unset($_SESSION[self::session_key_posted]);
        }
    }

    static function on_template_redirect() {
        if (get_the_ID() != system_pages::get_page_id(system_pages::page_auth_twostep)) {
            return;
        }

        if (is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }

        if (isset($_GET['login']) && trim($_GET['login']) == 'start-over') {
            self::clear_twostep_data();
            if (system_pages::get_page_id(system_pages::page_auth_login) > 0) {
                wp_redirect(system_pages::get_page_url(system_pages::page_auth_login));
            } else {
                wp_redirect(home_url());
            }
            exit;
        }

        if (isset($_GET['get']) && trim($_GET['get']) == 'new-code') {
            self::$ro = $_SESSION[self::session_key_posted] = self::set_new_code();
            if (system_pages::get_page_id(system_pages::page_auth_twostep) > 0) {
                wp_redirect(system_pages::get_page_url(system_pages::page_auth_twostep));
            } else {
                wp_redirect(home_url());
            }
            exit;
        }

        if (is_null($_SESSION[self::session_key_twostep_data])) {
            if (system_pages::get_page_id(system_pages::page_auth_login) > 0) {
                wp_redirect(system_pages::get_page_url(system_pages::page_auth_login));
            } else {
                wp_redirect(home_url());
            }
            exit;
        }
        $so = $_SESSION[self::session_key_twostep_data];
        if (!isset($so->code) || !isset($so->user_id) || !isset($so->remember)) {
            if (system_pages::get_page_id(system_pages::page_auth_login) > 0) {
                wp_redirect(system_pages::get_page_url(system_pages::page_auth_login));
            } else {
                wp_redirect(home_url());
            }
            exit;
        }

        if (isset($_POST['twostep_verify'])) {
            self::$ro = $_SESSION[self::session_key_posted] = self::verify();
            if (self::$ro->success) {
                wp_redirect(home_url());
            } else {
                wp_redirect(filter_input(INPUT_SERVER, 'REQUEST_URI'));
            }
            exit;
        }
    }

    static function filter_the_content($the_content) {
        if (get_the_ID() != system_pages::get_page_id(system_pages::page_auth_twostep)) {
            return $the_content;
        }

        ob_start();
        include cbm_dir . '/views/auth/verify.php';
        return ob_get_clean();
    }

}

add_action('init', ['auth_twostep', 'on_init',]);
add_action('template_redirect', ['auth_twostep', 'on_template_redirect',]);
add_filter('the_content', ['auth_twostep', 'filter_the_content',]);
