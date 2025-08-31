<?php
if (!defined('ABSPATH'))
    exit;

function plt_register_cpt()
{

    $labels = array(
        'name' => __('Portfolios', 'portfolio-listing-typer'),
        'singular_name' => __('Portfolio', 'portfolio-listing-typer'),
        'add_new' => __('Add New', 'portfolio-listing-typer'),
        'add_new_item' => __('Add New Portfolio', 'portfolio-listing-typer'),
        'edit_item' => __('Edit Portfolio', 'portfolio-listing-typer'),
        'new_item' => __('New Portfolio', 'portfolio-listing-typer'),
        'view_item' => __('View Portfolio', 'portfolio-listing-typer'),
        'search_items' => __('Search Portfolios', 'portfolio-listing-typer'),
        'not_found' => __('No Portfolios found', 'portfolio-listing-typer'),
        'not_found_in_trash' => __('No Portfolios found in Trash', 'portfolio-listing-typer'),
        'menu_name' => __('Portfolios', 'portfolio-listing-typer'),
    );

    $supports = array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'revisions');

    register_post_type('portfolio', array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'portfolio'),
        'menu_icon' => 'dashicons-portfolio',
        'supports' => $supports,
        'show_in_rest' => true,
    ));

    register_taxonomy('portfolio_category', 'portfolio', array(
        'labels' => array(
            'name' => __('Portfolio Categories', 'portfolio-listing-typer'),
            'singular_name' => __('Portfolio Category', 'portfolio-listing-typer'),
        ),
        'hierarchical' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'portfolio-category'),
        'show_in_rest' => true,
    ));

    register_taxonomy('portfolio_tag', 'portfolio', array(
        'labels' => array(
            'name' => __('Portfolio Tags', 'portfolio-listing-typer'),
            'singular_name' => __('Portfolio Tag', 'portfolio-listing-typer'),
        ),
        'hierarchical' => false,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'portfolio-tag'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'plt_register_cpt');
