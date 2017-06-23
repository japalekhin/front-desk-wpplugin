<?php

namespace Alekhin\FrontDesk;

class FrontDesk {

    static function get_ss_version() {
        return version;
    }

    static function get_dir($to_what = '') {
        return dir . $to_what;
    }

    static function get_url($to_what = '') {
        return url . $to_what;
    }

    static function on_after_setup_theme() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    static function initialize() {
        add_action('after_setup_theme', [__CLASS__, 'on_after_setup_theme',]);
    }

}
