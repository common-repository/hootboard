<?php
/**
 * Plugin Name: HootBoard
 * Author: HootBoard
 * Author URI: https://about.hootboard.com
 * Version: 3.1.4
 * Description: HootBoard is a bulletin board for your community website. HootBoard works seamlessly across your website, mobile devices and screens in your building.
 * Text-Domain: hootboard
 */

if (!defined('ABSPATH')): exit();endif; // No direct script access allowed.

// Define all constants here
define('HB_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('HB_URL', trailingslashit(plugins_url('/', __FILE__)));

// Loading the scripts and elements needed for the plugin.
add_action('admin_enqueue_scripts', 'load_scripts');
function load_scripts()
{
    // The bundle.js file should contains the whole plugin functionality.
    wp_enqueue_script('hootboard', HB_URL . 'dist/index.js', ['wp-element'], wp_rand(), true);
    wp_localize_script('hootboard', 'appLocalizer', [
        'apiUrl' => home_url('/wp-json'),
        'nonce' => wp_create_nonce('wp_rest'),
    ]);
}

require_once HB_PATH . 'classes/class-create-admin-menu.php';
require_once HB_PATH . 'classes/class-create-rest-routes.php';
require_once HB_PATH . 'classes/class-create-shortcode.php';
