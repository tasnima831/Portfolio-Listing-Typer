<?php
if (!defined('ABSPATH'))
    exit;

function plt_shortcode_handler($atts)
{
    $atts = shortcode_atts(array(
        'layout' => get_option('plt_default_layout', 'grid'),
        'items' => absint(get_option('plt_items_per_page', 12)),
        'category' => '',
        'filter' => get_option('plt_enable_filter', true) ? 'true' : 'false',
    ), $atts, 'portfolio_list');

    $layout = in_array($atts['layout'], array('grid', 'list')) ? $atts['layout'] : 'grid';
    $items = absint($atts['items']);
    $category = sanitize_text_field($atts['category']);
    $filter = filter_var($atts['filter'], FILTER_VALIDATE_BOOLEAN);

    $args = array(
        'post_type' => 'portfolio',
        'posts_per_page' => $items > 0 ? $items : 12,
    );

    if ($category) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'portfolio_category',
                'field' => 'slug',
                'terms' => $category,
            )
        );
    }

    $q = new WP_Query($args);

    ob_start();

    if ($filter) {
        $terms = get_terms(array('taxonomy' => 'portfolio_category', 'hide_empty' => true));
        if (!is_wp_error($terms) && !empty($terms)) {
            echo '<div class="plt-filter"><label>' . esc_html__('Filter:', 'portfolio-listing-typer') . ' </label>';
            echo '<select class="plt-filter-select">';
            echo '<option value="">' . esc_html__('All', 'portfolio-listing-typer') . '</option>';
            foreach ($terms as $t) {
                printf(
                    '<option value="%s">%s</option>',
                    esc_attr($t->slug),
                    esc_html($t->name)
                );
            }
            echo '</select></div>';
        }
    }

    $wrapper_class = $layout === 'list' ? 'plt-list' : 'plt-grid';
    echo '<div class="plt-wrapper ' . esc_attr($wrapper_class) . '" data-layout="' . esc_attr($layout) . '">';

    if ($q->have_posts()):
        while ($q->have_posts()):
            $q->the_post();
            $link = esc_url(get_post_meta(get_the_ID(), '_plt_project_link', true));
            $desc = esc_html(get_post_meta(get_the_ID(), '_plt_project_desc', true));
            $cats = get_the_terms(get_the_ID(), 'portfolio_category');
            $cat_slugs = array();
            if ($cats && !is_wp_error($cats)) {
                foreach ($cats as $c)
                    $cat_slugs[] = $c->slug;
            }
            $data_cat = esc_attr(implode(' ', $cat_slugs));

            echo '<article class="plt-item" data-cats="' . $data_cat . '">';
            if (has_post_thumbnail()) {
                echo '<div class="plt-thumb"><a href="' . get_permalink() . '">';
                the_post_thumbnail('medium_large', array('loading' => 'lazy'));
                echo '</a></div>';
            }
            echo '<div class="plt-content">';
            echo '<h3 class="plt-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            if ($desc) {
                echo '<p class="plt-desc">' . $desc . '</p>';
            } else {
                the_excerpt();
            }
            echo '<div class="plt-meta">';
            if ($link) {
                echo '<a class="plt-btn" href="' . $link . '" target="_blank" rel="noopener noreferrer">' . esc_html__('Visit Project', 'portfolio-listing-typer') . '</a>';
            }
            if (!empty($cats) && !is_wp_error($cats)) {
                echo '<span class="plt-cats">';
                $names = wp_list_pluck($cats, 'name');
                echo esc_html(implode(', ', $names));
                echo '</span>';
            }
            echo '</div>';
            echo '</div>';
            echo '</article>';
        endwhile;
        wp_reset_postdata();
    else:
        echo '<p>' . esc_html__('No portfolio items found.', 'portfolio-listing-typer') . '</p>';
    endif;

    echo '</div>';

    return ob_get_clean();
}
add_shortcode('portfolio_list', 'plt_shortcode_handler');
