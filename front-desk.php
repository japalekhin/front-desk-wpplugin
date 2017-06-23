<?php

/*
  Plugin Name: Front Desk
  Plugin URI:  https://alekhin.llemos.com/front-desk
  Description: A WP plugin that enables front-end functionality including login, register, password recovery and profile editor.
  Version:     1.0.0
  Author:      Alekhin
  Author URI:  https://alekhin.llemos.com
  License:     GPLv3
  License URI: https://www.gnu.org/licenses/gpl-3.0.html
  Text Domain: front-desk
  Domain Path: /languages

  Front Desk is free software: you can redistribute it and/or modify it under
  the terms of the GNU General Public License as published by the Free Software
  Foundation, either version 3 of the License, or any later version.

  Front Desk is distributed in the hope that it will be useful, but WITHOUT ANY
  WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
  A PARTICULAR PURPOSE. See the GNU General Public License for more details.

  You should have received a copy of the GNU General Public License along with
  Front Desk.

  If not, see https://www.gnu.org/licenses/gpl-3.0.html.
 */

namespace Alekhin\FrontDesk;

if (!defined('ABSPATH')) {
    echo 'really?';
    exit;
}

define(__NAMESPACE__ . '\version', '1.0.0' . (WP_DEBUG ? '.' . time() : ''));
define(__NAMESPACE__ . '\dir', plugin_dir_path(__FILE__));
define(__NAMESPACE__ . '\url', plugin_dir_url(__FILE__));

require_once dir . 'classes/vendor/autoload.php';

$symfony_loader = new \Symfony\Component\ClassLoader\Psr4ClassLoader();
$symfony_loader->addPrefix('Alekhin\FrontDesk\\', dir . str_replace('/', DIRECTORY_SEPARATOR, 'classes/Alekhin/FrontDesk'));
$symfony_loader->register();

FrontDesk::initialize();

Admin\Admin::initialize();
Admin\Theme::initialize();
Admin\Pages::initialize();
Admin\Menu::initialize();
Admin\Settings::initialize();

Menu::initialize();
Theme::initialize();
Login::initialize();
Register::initialize();
Reset::initialize();
Recover::initialize();
//TwoStep::initialize();
Profile::initialize();

// TODO: additional fields in profile and registration (filter)
// TODO: action to save additional fields
// TODO: add auto page setup for pages (single and multi)
// TODO: delete account in profile page
// TODO: add default user role on admin/settings
// TODO: add 2-step authentication
