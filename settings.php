<?php
if (!defined('ABSPATH'))
  exit;

function plt_register_settings()
{
  register_setting('plt_settings_group', 'plt_default_layout', array(
    'type' => 'string',
    'sanitize_callback' => function ($v) {
      return in_array($v, array('grid', 'list')) ? $v : 'grid'; },
    'default' => 'grid',
  ));
  register_setting('plt_settings_group', 'plt_items_per_page', array(
    'type' => 'integer',
    'sanitize_callback' => 'absint',
    'default' => 12,
  ));
  register_setting('plt_settings_group', 'plt_enable_filter', array(
    'type' => 'boolean',
    'sanitize_callback' => function ($v) {
      return (bool) $v; },
    'default' => true,
  ));

  add_options_page(
    __('Portfolio Typer Settings', 'portfolio-listing-typer'),
    __('Portfolio Typer', 'portfolio-listing-typer'),
    'manage_options',
    'plt-settings',
    'plt_render_settings_page'
  );
}
add_action('admin_menu', 'plt_register_settings');

function plt_render_settings_page()
{
  ?>
  <div class="wrap">
    <h1><?php _e('Portfolio Listing Typer - Settings', 'portfolio-listing-typer'); ?></h1>
    <form method="post" action="options.php">
      <?php settings_fields('plt_settings_group'); ?>
      <table class="form-table" role="presentation">
        <tr>
          <th scope="row"><label
              for="plt_default_layout"><?php _e('Default Layout', 'portfolio-listing-typer'); ?></label></th>
          <td>
            <select id="plt_default_layout" name="plt_default_layout">
              <?php $layout = get_option('plt_default_layout', 'grid'); ?>
              <option value="grid" <?php selected($layout, 'grid'); ?>><?php _e('Grid', 'portfolio-listing-typer'); ?>
              </option>
              <option value="list" <?php selected($layout, 'list'); ?>><?php _e('List', 'portfolio-listing-typer'); ?>
              </option>
            </select>
          </td>
        </tr>
        <tr>
          <th scope="row"><label
              for="plt_items_per_page"><?php _e('Items per Page', 'portfolio-listing-typer'); ?></label></th>
          <td>
            <?php $items = absint(get_option('plt_items_per_page', 12)); ?>
            <input type="number" min="1" max="100" id="plt_items_per_page" name="plt_items_per_page"
              value="<?php echo esc_attr($items); ?>">
            <p class="description">
              <?php _e('Used as default when shortcode does not specify "items".', 'portfolio-listing-typer'); ?></p>
          </td>
        </tr>
        <tr>
          <th scope="row"><?php _e('Enable Category Filter UI', 'portfolio-listing-typer'); ?></th>
          <td>
            <?php $enabled = get_option('plt_enable_filter', true); ?>
            <label>
              <input type="checkbox" name="plt_enable_filter" value="1" <?php checked($enabled, true); ?>>
              <?php _e('Show a category dropdown above the portfolio list', 'portfolio-listing-typer'); ?>
            </label>
          </td>
        </tr>
      </table>
      <?php submit_button(); ?>
    </form>
    <p><strong><?php _e('Shortcode:', 'portfolio-listing-typer'); ?></strong>
      <code>[portfolio_list layout="grid" items="12" category="slug" filter="true"]</code></p>
  </div>
  <?php
}
