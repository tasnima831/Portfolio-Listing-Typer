<?php
if (!defined('ABSPATH'))
    exit;

function plt_add_meta_boxes()
{
    add_meta_box('plt_portfolio_details', __('Project Details', 'portfolio-listing-typer'), 'plt_render_meta_box', 'portfolio', 'normal', 'default');
}
add_action('add_meta_boxes', 'plt_add_meta_boxes');

function plt_render_meta_box($post)
{
    wp_nonce_field('plt_save_meta', 'plt_meta_nonce');
    $project_link = get_post_meta($post->ID, '_plt_project_link', true);
    $project_desc = get_post_meta($post->ID, '_plt_project_desc', true);
    ?>
    <p>
        <label
            for="plt_project_link"><strong><?php _e('Project Link (URL)', 'portfolio-listing-typer'); ?></strong></label><br>
        <input type="url" name="plt_project_link" id="plt_project_link" value="<?php echo esc_attr($project_link); ?>"
            style="width:100%;" placeholder="https://example.com">
    </p>
    <p>
        <label
            for="plt_project_desc"><strong><?php _e('Short Description', 'portfolio-listing-typer'); ?></strong></label><br>
        <textarea name="plt_project_desc" id="plt_project_desc" rows="4" style="width:100%;"
            placeholder="<?php esc_attr_e('Brief project summary...', 'portfolio-listing-typer'); ?>"><?php echo esc_textarea($project_desc); ?></textarea>
    </p>
    <?php
}

function plt_save_meta($post_id)
{
    if (!isset($_POST['plt_meta_nonce']) || !wp_verify_nonce($_POST['plt_meta_nonce'], 'plt_save_meta'))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (isset($_POST['post_type']) && 'portfolio' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id))
            return;
    }

    if (isset($_POST['plt_project_link'])) {
        update_post_meta($post_id, '_plt_project_link', esc_url_raw($_POST['plt_project_link']));
    }
    if (isset($_POST['plt_project_desc'])) {
        update_post_meta($post_id, '_plt_project_desc', sanitize_textarea_field($_POST['plt_project_desc']));
    }
}
add_action('save_post', 'plt_save_meta');
