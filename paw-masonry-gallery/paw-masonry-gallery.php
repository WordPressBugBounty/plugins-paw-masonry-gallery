<?php

/**
 * Plugin Name: Divi Masonry Gallery
 * Plugin URI: https://diviextensions.com/divi-pro-gallery/
 * Author: DiviExtensions
 * Author URI: https://diviextensions.com/
 * Description: The fastest and easiest way to create a responsive masonry gallery.
 * Version: 1.0.5
 * Text Domain: divi-masonry-gallery
 * License: GPL2
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

define('DMG_VERSION',         '1.0.5');
define('DMG_BASENAME',        plugin_basename(__FILE__));
define('DMG_PLUGIN_DIR',      plugin_dir_path(__FILE__));
define('DMG_PLUGIN_DIR_URL',  plugin_dir_url(__FILE__));

/**
 * Main plugin class
 */
class Divi_Masonry_Gallery
{
    const DOCS_LINK     = 'https://diviextensions.com/docs/';
    const PRICING_LINK  = 'https://diviextensions.com/divi-pro-gallery/';

    private static $instance;

    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof Divi_Masonry_Gallery)) {
            self::$instance = new Divi_Masonry_Gallery;
            self::$instance->includes();
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_action('et_builder_ready', [$this, 'load_modules'], 11);
        add_action('wp_enqueue_scripts', [$this, 'register_frontend_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'register_builder_scripts'], 11);
    }

    private function includes()
    {
        require DMG_PLUGIN_DIR . '/includes/helper.php';
    }

    public function load_modules()
    {
        if (!class_exists('ET_Builder_Module')) {
            return;
        }

        require_once DMG_PLUGIN_DIR . 'includes/modules/Divi4/Gallery/Gallery.php';
    }

    public function register_frontend_scripts()
    {
        wp_register_script(
            'dmg-fancybox',
            DMG_PLUGIN_DIR_URL . 'assets/libs/fancybox/fancybox.js',
            ['jquery'],
            DMG_VERSION,
            true
        );

        wp_register_style(
            'dmg-fancybox',
            DMG_PLUGIN_DIR_URL . 'assets/libs/fancybox/fancybox.css',
            [],
            DMG_VERSION,
        );

        wp_register_script(
            'dmg-masonry',
            DMG_PLUGIN_DIR_URL . 'assets/libs/masonry/masonry.min.js',
            ['jquery'],
            DMG_VERSION,
            true
        );

        wp_register_script(
            'dmg-frontend',
            DMG_PLUGIN_DIR_URL . 'dist/js/frontend.js',
            ['jquery', 'dmg-masonry', 'dmg-fancybox'],
            DMG_VERSION,
            true
        );

        wp_register_style(
            'dmg-frontend',
            DMG_PLUGIN_DIR_URL . 'dist/css/frontend.css',
            ['dmg-fancybox'],
            DMG_VERSION,
            false
        );
    }

    public function register_builder_scripts()
    {
        // Check if we're in the Divi Builder frontend editor
        if (!function_exists('et_fb_is_enabled') || !et_fb_is_enabled()) {
            return;
        }

        wp_enqueue_script(
            'dmg-bundle',
            DMG_PLUGIN_DIR_URL . 'dist/js/bundle.js',
            ['jquery', 'react', 'react-dom', 'dmg-frontend'],
            DMG_VERSION . time(),
            true
        );

        wp_enqueue_style(
            'dmg-bundle',
            DMG_PLUGIN_DIR_URL . 'dist/css/bundle.css',
            [],
            DMG_VERSION . time()
        );
    }
}

function diviextensions_masonry_gallery()
{
    return Divi_Masonry_Gallery::instance();
}

diviextensions_masonry_gallery();
