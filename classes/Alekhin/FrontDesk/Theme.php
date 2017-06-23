<?php

namespace Alekhin\FrontDesk;

use Alekhin\FrontDesk\FrontDesk;
use Alekhin\FrontDesk\Admin\Theme as AdTheme;

class Theme {

    static function get_theme_url() {
        return admin_url('admin-ajax.php?action=frd_theme_stylesheet');
    }

    static function on_wp_enqueue_scripts() {
        wp_enqueue_style('frd-theme', self::get_theme_url(), [], FrontDesk::get_ss_version() . '-' . AdTheme::get_style_version());
    }

    static function ajax_theme_stylesheet() {
        header('Content-type: text/css; charset: UTF-8');

        //include FrontDesk::get_dir('/styles/main.css');
        readfile(FrontDesk::get_dir('/styles/main.css'));
        echo AdTheme::get_custom_css();
        exit;
    }

    static function initialize() {
        add_action('wp_enqueue_scripts', [__CLASS__, 'on_wp_enqueue_scripts',]);

        add_action('wp_ajax_frd_theme_stylesheet', [__CLASS__, 'ajax_theme_stylesheet',]);
        add_action('wp_ajax_nopriv_frd_theme_stylesheet', [__CLASS__, 'ajax_theme_stylesheet',]);
    }

}
