<?php

namespace Alekhin\FrontEndUser;

use Alekhin\FrontEndUser\FrontEndUser;
use Alekhin\FrontEndUser\Admin\Theme as AdTheme;

class Theme {

    static function get_theme_url() {
        return admin_url('admin-ajax.php?action=feu_theme_stylesheet');
    }

    static function on_wp_enqueue_scripts() {
        wp_enqueue_style('feu-theme', self::get_theme_url(), [], FrontEndUser::get_ss_version() . '-' . AdTheme::get_style_version());
    }

    static function ajax_theme_stylesheet() {
        header('Content-type: text/css; charset: UTF-8');

        //include FrontEndUser::get_dir('/styles/main.css');
        readfile(FrontEndUser::get_dir('/styles/main.css'));
        echo AdTheme::get_custom_css();
        exit;
    }

    static function initialize() {
        add_action('wp_enqueue_scripts', [__CLASS__, 'on_wp_enqueue_scripts',]);

        add_action('wp_ajax_feu_theme_stylesheet', [__CLASS__, 'ajax_theme_stylesheet',]);
        add_action('wp_ajax_nopriv_feu_theme_stylesheet', [__CLASS__, 'ajax_theme_stylesheet',]);
    }

}
