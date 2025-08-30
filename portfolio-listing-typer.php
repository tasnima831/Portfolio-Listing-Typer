<?php

if (!defined('ABSPATH'))
    exit;

define('PLT_VERSION', '1.0.0');
define('PLT_PATH', plugin_dir_path(__FILE__));
define('PLT_URL', plugin_dir_url(__FILE__));

require_once PLT_PATH . 'includes/cpt.php';
require_once PLT_PATH . 'includes/meta-boxes.php';
require_once PLT_PATH . 'includes/shortcode.php';
require_once PLT_PATH . 'includes/settings.php';

function plt_enqueue_assets()
{
    wp_enqueue_style('plt-styles', PLT_URL . 'assets/css/portfolio.css', array(), PLT_VERSION);
    wp_enqueue_script('plt-scripts', PLT_URL . 'assets/js/portfolio.js', array('jquery'), PLT_VERSION, true);
    wp_localize_script('plt-scripts', 'PLT', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'plt_enqueue_assets');

function plt_activate()
{
    plt_register_cpt();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'plt_activate');

function plt_deactivate()
{
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'plt_deactivate');
