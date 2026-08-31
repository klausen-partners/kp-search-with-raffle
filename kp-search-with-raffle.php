<?php

/**
 * Plugin Name:       KP Search Integration with Raffle AI
 * Plugin URI:        https://kogp.dk
 * Description:       A Gutenberg block that integrates Raffle AI search (top questions, autocomplete, summary, and search results).
 * Version:           1.0.8
 * Requires at least: 6.1
 * Requires PHP:      8.1
 * Author:            Klausen and Partners
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       kp-search-with-raffle
 * Icon:              includes/assets/logo.svg
 */

if (! defined('ABSPATH')) {
    exit;
}

define('KP_SEARCH_WITH_RAFFLE_VERSION', '1.0.8');
define('KP_SEARCH_WITH_RAFFLE_DIR', plugin_dir_path(__FILE__));
define('KP_SEARCH_WITH_RAFFLE_URL', plugins_url('/', __FILE__));

// Admin settings page.
require_once __DIR__ . '/includes/admin.php';

/**
 * Register the Gutenberg blocks.
 */
function raffle_search_register_block()
{
    register_block_type(KP_SEARCH_WITH_RAFFLE_DIR . 'build/block.json');
    register_block_type(KP_SEARCH_WITH_RAFFLE_DIR . 'build/widget/block.json');

    // The widget overlay renders the full RaffleSearch component, so its
    // frontend styles must include the main search block's styles.
    $widget_style_handle = generate_block_asset_handle('kp-search-with-raffle/widget', 'style');
    $search_style_handle = generate_block_asset_handle('kp-search-with-raffle/search', 'style');
    $deps = $GLOBALS['wp_styles']->registered[$widget_style_handle]->deps ?? array();
    $deps[] = $search_style_handle;
    $GLOBALS['wp_styles']->registered[$widget_style_handle]->deps = $deps;
}
add_action('init', 'raffle_search_register_block');

// Enqueue frontend script variable for default image
add_action('wp_enqueue_scripts', function () {
    if (! is_admin()) {
        $default_image_url = function_exists('raffle_search_get_default_image_url') ? raffle_search_get_default_image_url() : '';
        if (empty($default_image_url)) {
            $default_image_url = plugins_url('includes/assets/logo.svg', __FILE__);
        }
        // Enqueue the view script if not already enqueued
        $handle = 'kp-search-with-raffle-view';
        if (! wp_script_is($handle, 'enqueued')) {
            $asset = include __DIR__ . '/build/view.asset.php';
            wp_enqueue_script(
                $handle,
                KP_SEARCH_WITH_RAFFLE_URL . 'build/view.js',
                $asset['dependencies'],
                $asset['version'],
                true
            );
        }
        wp_localize_script(
            $handle,
            'raffleSearchDefaultImageUrl',
            $default_image_url
        );
    }
});

/**
 * Output meta tag with article:tag for post/page tags if enabled.
 */
function raffle_search_output_article_tag_meta()
{
    if (! is_singular()) {
        return;
    }
    if (! get_option('raffle_search_enable_article_tag_meta', false)) {
        return;
    }
    $tags = get_the_terms(get_the_ID(), 'post_tag');
    if ($tags && is_array($tags)) {
        $tag_names = array();
        foreach ($tags as $tag) {
            $tag_names[] = $tag->name;
        }
        if (! empty($tag_names)) {
            echo '<meta property="article:tag" content="' . esc_attr(implode(',', $tag_names)) . '" />' . "\n";
        }
    }
}
add_action('wp_head', 'raffle_search_output_article_tag_meta');

/**
 * Output raffle:type meta tag for posts/pages/CPTs if enabled.
 */
function raffle_search_output_raffle_type_meta()
{
    if (! is_singular()) {
        return;
    }
    if (! get_option('raffle_search_enable_raffle_type_meta', false)) {
        return;
    }
    $post_type = get_post_type(get_the_ID());
    if ('post' === $post_type) {
        $type_value = 'post';
    } elseif ('page' === $post_type) {
        $type_value = 'page';
    } else {
        $obj        = get_post_type_object($post_type);
        $type_value = $obj ? strtolower($obj->labels->singular_name) : $post_type;
    }
    echo '<meta property="raffle:type" content="' . esc_attr($type_value) . '" />' . "\n";
}
add_action('wp_head', 'raffle_search_output_raffle_type_meta');

/**
 * Output CSS custom property for the result image width.
 */
function raffle_search_output_image_size_styles()
{
    $width = (int) get_option('raffle_search_image_width', 250);
    if ($width > 0) {
        echo '<style id="raffle-image-size">.raffle-result-meta-row{--raffle-image-width:' . esc_attr($width) . 'px}</style>' . "\n";
    }
}
add_action('wp_head', 'raffle_search_output_image_size_styles');

/**
 * Return the common raffleSettings array used by both blocks.
 */
function raffle_search_get_settings_array()
{
    return array(
        'baseUrl'           => get_option('raffle_search_base_url', 'https://api.raffle.ai/v2'),
        'searchUid'         => get_option('raffle_search_uid', ''),
        'showReferences'    => (bool) get_option('raffle_search_show_references', true),
        'hideSummaryButton' => (bool) get_option('raffle_search_hide_summary_button', false),
        'hideExcerptTypes'  => get_option('raffle_search_hide_excerpt_types', ''),
        'excerptTrimLength' => get_option('raffle_search_excerpt_trim_length', null),
        'instantAnswerExcerptTrimLength' => get_option('raffle_search_instant_answer_excerpt_trim_length', null),
        'imageWidth'        => (int) get_option('raffle_search_image_width', 250),
        'hiddenTags'        => get_option('raffle_search_hidden_tags', ''),
        'tagsMode'          => get_option('raffle_search_tags_mode', 'exclude'),
        'hiddenTypes'       => get_option('raffle_search_hidden_types', ''),
        'typesMode'         => get_option('raffle_search_types_mode', 'exclude'),
    );
}

/**
 * Pre-register shortcode scripts and styles on init so they are available
 * even when a shortcode is placed in a header template (before wp_head).
 * WordPress will print late-enqueued styles in the footer automatically.
 */
function raffle_search_register_shortcode_assets()
{
    if (is_admin()) {
        return;
    }

    // Raffle Search (full block) shortcode assets.
    $asset = include __DIR__ . '/build/view.asset.php';
    wp_register_script('kp-search-with-raffle-shortcode-view', KP_SEARCH_WITH_RAFFLE_URL . 'build/view.js', $asset['dependencies'], $asset['version'], true);
    wp_localize_script('kp-search-with-raffle-shortcode-view', 'raffleSettings', raffle_search_get_settings_array());
    wp_register_style('kp-search-with-raffle-shortcode-style', KP_SEARCH_WITH_RAFFLE_URL . 'build/style-view.css', array(), KP_SEARCH_WITH_RAFFLE_VERSION);

    // Raffle Search Widget shortcode assets.
    $widget_asset = include __DIR__ . '/build/widget/view.asset.php';
    wp_register_script('kp-search-with-raffle-widget-shortcode-view', KP_SEARCH_WITH_RAFFLE_URL . 'build/widget/view.js', $widget_asset['dependencies'], $widget_asset['version'], true);
    wp_localize_script('kp-search-with-raffle-widget-shortcode-view', 'raffleSettings', raffle_search_get_settings_array());
    wp_register_style('kp-search-with-raffle-widget-shortcode-style', KP_SEARCH_WITH_RAFFLE_URL . 'build/widget/style-view.css', array('kp-search-with-raffle-shortcode-style'), KP_SEARCH_WITH_RAFFLE_VERSION);
}
add_action('init', 'raffle_search_register_shortcode_assets');

/**
 * Shortcode: [raffle_search]
 *
 * Renders the full Raffle Search block.
 * Accepts an optional "uid" attribute to override the global Search UID.
 *
 * Usage: [raffle_search] or [raffle_search uid="xxx-xxx"]
 */
function raffle_search_shortcode($atts)
{
    $atts = shortcode_atts(array('uid' => ''), $atts, 'raffle_search');

    wp_enqueue_script('kp-search-with-raffle-shortcode-view');
    wp_enqueue_style('kp-search-with-raffle-shortcode-style');

    $uid_attr = $atts['uid'] ? ' data-search-uid="' . esc_attr($atts['uid']) . '"' : '';
    return '<div class="raffle-search-block wp-block-kp-search-with-raffle-search"' . $uid_attr . '></div>';
}
add_shortcode('raffle_search', 'raffle_search_shortcode');

/**
 * Shortcode: [raffle_search_widget]
 *
 * Renders the Raffle Search Widget (magnifier icon).
 * Accepts optional attributes:
 *   - mode: "overlay" (default) or "link"
 *   - url:  The search page URL (used when mode="link")
 *
 * Usage: [raffle_search_widget]
 *        [raffle_search_widget mode="link" url="https://example.com/search"]
 */
function raffle_search_widget_shortcode($atts)
{
    $atts = shortcode_atts(
        array(
            'mode' => 'overlay',
            'url'  => '',
        ),
        $atts,
        'raffle_search_widget'
    );

    $mode = in_array($atts['mode'], array('overlay', 'link'), true) ? $atts['mode'] : 'overlay';

    wp_enqueue_script('kp-search-with-raffle-widget-shortcode-view');
    wp_enqueue_style('kp-search-with-raffle-widget-shortcode-style');

    $url_attr = ('link' === $mode && $atts['url']) ? ' data-search-url="' . esc_url($atts['url']) . '"' : '';

    return '<div class="raffle-search-widget" data-mode="' . esc_attr($mode) . '"' . $url_attr . '>'
        . '<button class="raffle-search-widget__trigger" aria-label="Search" type="button">'
        . '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Search">'
        . '<circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2" fill="none"></circle>'
        . '<line x1="16.5" y1="16.5" x2="22" y2="22" stroke="currentColor" stroke-width="2" stroke-linecap="round"></line>'
        . '</svg>'
        . '</button>'
        . '</div>';
}
add_shortcode('raffle_search_widget', 'raffle_search_widget_shortcode');
