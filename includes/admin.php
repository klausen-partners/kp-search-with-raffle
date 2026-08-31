<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/advanced-settings.php';

/**
 * Admin settings page for the KP Raffle Search plugin.
 *
 * Adds a "KP Raffle Search" submenu under Settings and provides fields
 * to store baseUrl and searchUid in the WordPress database.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Register the settings submenu page.
function raffle_search_add_settings_page() {
	add_submenu_page(
		'options-general.php',
		__( 'KP Raffle Search', 'kp-search-with-raffle' ),
		__( 'KP Raffle Search', 'kp-search-with-raffle' ),
		'manage_options',
		'kp-search-with-raffle-settings',
		'raffle_search_render_settings_page'
	);
}
add_action( 'admin_menu', 'raffle_search_add_settings_page' );

// Register settings, sections, and fields.
function raffle_search_register_settings() {

	// Option to enable tags for pages
	register_setting(
		'raffle_search_options',
		'raffle_search_enable_tags_on_pages',
		array(
		'type' => 'boolean',
		'sanitize_callback' => 'rest_sanitize_boolean',
		'default' => false,
		)
	);

	// Register option to enable meta tag output for post/page tags
	register_setting(
		'raffle_search_options',
		'raffle_search_enable_article_tag_meta',
		array(
			'type' => 'boolean',
			'sanitize_callback' => 'rest_sanitize_boolean',
			'default' => false,
		)
	);

	// Register option to enable raffle:type meta tag output
	register_setting(
		'raffle_search_options',
		'raffle_search_enable_raffle_type_meta',
		array(
			'type'              => 'boolean',
			'sanitize_callback' => 'rest_sanitize_boolean',
			'default'           => false,
		)
	);

	// Register default image URL option
	register_setting(
		'raffle_search_options',
		'raffle_search_default_image_url',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		)
	);
	register_setting(
		'raffle_search_options',
		'raffle_search_base_url',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'esc_url_raw',
			'default'           => 'https://api.raffle.ai/v2',
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_excerpt_trim_length',
		array(
			'type'              => 'integer',
			'sanitize_callback' => 'raffle_search_sanitize_trim_length',
			'default'           => null,
		)
	);

    register_setting(
        'raffle_search_options',
        'raffle_search_instant_answer_excerpt_trim_length',
        array(
            'type'              => 'integer',
            'sanitize_callback' => 'raffle_search_sanitize_trim_length',
            'default'           => null,
        )
    );

	register_setting(
		'raffle_search_options',
		'raffle_search_uid',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '',
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_show_references',
		array(
			'type'              => 'boolean',
			'sanitize_callback' => 'rest_sanitize_boolean',
			'default'           => true,
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_hide_summary_button',
		array(
			'type'              => 'boolean',
			'sanitize_callback' => 'rest_sanitize_boolean',
			'default'           => false,
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_hide_excerpt_types',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'raffle_search_sanitize_types',
			'default'           => 'pdf',
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_hidden_tags',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '',
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_tags_mode',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'raffle_search_sanitize_tags_mode',
			'default'           => 'exclude',
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_hidden_types',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '',
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_types_mode',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'raffle_search_sanitize_types_mode',
			'default'           => 'exclude',
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_color_type_bg',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => '',
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_color_type_text',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => '',
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_color_tag_bg',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => '',
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_color_tag_text',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => '',
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_image_width',
		array(
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'default'           => 250,
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_widget_icon_color',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => '',
		)
	);

	register_setting(
		'raffle_search_options',
		'raffle_search_widget_icon_color_mobile',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => '',
		)
	);

	// ── General tab ──────────────────────────────────────────────
	add_settings_section(
		'raffle_search_general_section',
		__( 'API Configuration', 'kp-search-with-raffle' ),
		'raffle_search_section_description',
		'kp-search-with-raffle-general'
	);

	add_settings_field(
		'raffle_search_base_url',
		__( 'Base URL', 'kp-search-with-raffle' ),
		'raffle_search_field_base_url',
		'kp-search-with-raffle-general',
		'raffle_search_general_section'
	);

	add_settings_field(
		'raffle_search_uid',
		__( 'Search UID', 'kp-search-with-raffle' ),
		'raffle_search_field_search_uid',
		'kp-search-with-raffle-general',
		'raffle_search_general_section'
	);

	// ── Metadata tab ─────────────────────────────────────────────
	add_settings_section(
		'raffle_search_metadata_section',
		'',
		'raffle_search_metadata_section_description',
		'kp-search-with-raffle-metadata'
	);

	add_settings_field(
		'raffle_search_enable_article_tag_meta',
		__( 'Add article:tag meta', 'kp-search-with-raffle' ),
		'raffle_search_field_enable_article_tag_meta',
		'kp-search-with-raffle-metadata',
		'raffle_search_metadata_section'
	);

	add_settings_field(
		'raffle_search_enable_raffle_type_meta',
		__( 'Add raffle:type meta', 'kp-search-with-raffle' ),
		'raffle_search_field_enable_raffle_type_meta',
		'kp-search-with-raffle-metadata',
		'raffle_search_metadata_section'
	);

	add_settings_field(
		'raffle_search_enable_tags_on_pages',
		__( 'Enable tags for pages', 'kp-search-with-raffle' ),
		'raffle_search_field_enable_tags_on_pages',
		'kp-search-with-raffle-metadata',
		'raffle_search_metadata_section'
	);

	// ── Settings tab ─────────────────────────────────────────────
	add_settings_section(
		'raffle_search_settings_section',
		'',
		'raffle_search_settings_section_description',
		'kp-search-with-raffle-vis-settings'
	);

	add_settings_field(
		'raffle_search_show_references',
		__( 'Show references', 'kp-search-with-raffle' ),
		'raffle_search_field_show_references',
		'kp-search-with-raffle-vis-settings',
		'raffle_search_settings_section'
	);

	add_settings_field(
		'raffle_search_hide_summary_button',
		__( 'Hide summary button', 'kp-search-with-raffle' ),
		'raffle_search_field_hide_summary_button',
		'kp-search-with-raffle-vis-settings',
		'raffle_search_settings_section'
	);

	add_settings_field(
		'raffle_search_excerpt_trim_length',
		__( 'Excerpt trim length', 'kp-search-with-raffle' ),
		'raffle_search_field_excerpt_trim_length',
		'kp-search-with-raffle-vis-settings',
		'raffle_search_settings_section'
	);

    add_settings_field(
        'raffle_search_instant_answer_excerpt_trim_length',
        __( 'Instant Answers excerpt trim length', 'kp-search-with-raffle' ),
        'raffle_search_field_instant_answer_excerpt_trim_length',
        'kp-search-with-raffle-vis-settings',
        'raffle_search_settings_section'
    );

	add_settings_field(
		'raffle_search_hide_excerpt_types',
		__( 'Hide excerpts for types', 'kp-search-with-raffle' ),
		'raffle_search_field_hide_excerpt_types',
		'kp-search-with-raffle-vis-settings',
		'raffle_search_settings_section'
	);

	add_settings_field(
		'raffle_search_hidden_types',
		__( 'Filter Types', 'kp-search-with-raffle' ),
		'raffle_search_field_hidden_types',
		'kp-search-with-raffle-vis-settings',
		'raffle_search_settings_section'
	);

	add_settings_field(
		'raffle_search_hidden_tags',
		__( 'Filter Tags', 'kp-search-with-raffle' ),
		'raffle_search_field_hidden_tags',
		'kp-search-with-raffle-vis-settings',
		'raffle_search_settings_section'
	);

	// ── Design tab ───────────────────────────────────────────────
	add_settings_section(
		'raffle_search_design_images_section',
		__( 'Images', 'kp-search-with-raffle' ),
		'__return_false',
		'kp-search-with-raffle-design'
	);

	add_settings_field(
		'raffle_search_default_image_url',
		__( 'Default result image', 'kp-search-with-raffle' ),
		'raffle_search_field_default_image_url',
		'kp-search-with-raffle-design',
		'raffle_search_design_images_section'
	);

	add_settings_field(
		'raffle_search_image_width',
		__( 'Result image width', 'kp-search-with-raffle' ),
		'raffle_search_field_image_width',
		'kp-search-with-raffle-design',
		'raffle_search_design_images_section'
	);

	add_settings_section(
		'raffle_search_badge_colors_section',
		__( 'Badge colors', 'kp-search-with-raffle' ),
		'raffle_search_badge_colors_section_description',
		'kp-search-with-raffle-design'
	);

	add_settings_field(
		'raffle_search_color_type',
		__( 'Type badge', 'kp-search-with-raffle' ),
		'raffle_search_field_type_badge_colors',
		'kp-search-with-raffle-design',
		'raffle_search_badge_colors_section'
	);

	add_settings_field(
		'raffle_search_color_tag',
		__( 'Tag badge', 'kp-search-with-raffle' ),
		'raffle_search_field_tag_badge_colors',
		'kp-search-with-raffle-design',
		'raffle_search_badge_colors_section'
	);

	// ── Design tab: Widget Icon section ─────────────────────────
	add_settings_section(
		'raffle_search_widget_icon_section',
		__( 'Widget icon', 'kp-search-with-raffle' ),
		'raffle_search_widget_icon_section_description',
		'kp-search-with-raffle-design'
	);

	add_settings_field(
		'raffle_search_widget_icon_color',
		__( 'Icon color', 'kp-search-with-raffle' ),
		'raffle_search_field_widget_icon_color',
		'kp-search-with-raffle-design',
		'raffle_search_widget_icon_section'
	);
// Field for enabling tags on pages
function raffle_search_field_enable_tags_on_pages() {
	$value = get_option( 'raffle_search_enable_tags_on_pages', false );
	?>
<label for="raffle_search_enable_tags_on_pages">
    <input type="checkbox" id="raffle_search_enable_tags_on_pages" name="raffle_search_enable_tags_on_pages" value="1"
        <?php checked( 1, $value ); ?> />
    <?php esc_html_e( 'Allow tags to be added to pages (not just posts).', 'kp-search-with-raffle' ); ?>
</label>
<?php
}

// Register post_tag for pages only if enabled in settings
add_action( 'init', function() {
	if ( get_option( 'raffle_search_enable_tags_on_pages', false ) ) {
		register_taxonomy_for_object_type( 'post_tag', 'page' );
	}
}, 0 );

// Fallback: ensure taxonomy is registered before saving page
add_action( 'save_post_page', function( $post_id ) {
	if ( get_option( 'raffle_search_enable_tags_on_pages', false ) ) {
		register_taxonomy_for_object_type( 'post_tag', 'page' );
	}
}, 1 );


// Field for enabling article:tag meta
	function raffle_search_field_enable_article_tag_meta() {
		$value = get_option( 'raffle_search_enable_article_tag_meta', false );
		?>
<label for="raffle_search_enable_article_tag_meta">
    <input type="checkbox" id="raffle_search_enable_article_tag_meta" name="raffle_search_enable_article_tag_meta"
        value="1" <?php checked( 1, $value ); ?> />
    <?php esc_html_e( 'Add a meta tag with all post/page tags as a string array (article:tag) in the page head.', 'kp-search-with-raffle' ); ?>
</label>
<?php
}

// Field for enabling raffle:type meta
function raffle_search_field_enable_raffle_type_meta() {
	$value = get_option( 'raffle_search_enable_raffle_type_meta', false );
	?>
<label for="raffle_search_enable_raffle_type_meta">
    <input type="checkbox" id="raffle_search_enable_raffle_type_meta" name="raffle_search_enable_raffle_type_meta"
        value="1" <?php checked( 1, $value ); ?> />
    <?php esc_html_e( 'Add a raffle:type meta tag identifying the content type (post, page, or custom post type singular name) in the page head.', 'kp-search-with-raffle' ); ?>
</label>
<?php
}
	
function raffle_search_field_hide_excerpt_types() {
	$value = get_option( 'raffle_search_hide_excerpt_types', 'pdf' );
	?>
<input type="text" id="raffle_search_hide_excerpt_types" name="raffle_search_hide_excerpt_types"
    value="<?php echo esc_attr( $value ); ?>" class="regular-text" placeholder="pdf,docx" />
<p class="description">
    <?php esc_html_e( 'Comma-separated list of result types (e.g. pdf,docx) for which excerpts/snippets should be hidden. You can add your own types.', 'kp-search-with-raffle' ); ?>
</p>
<?php
}

function raffle_search_sanitize_tags_mode( $value ) {
	$allowed = array( 'exclude', 'include' );
	return in_array( $value, $allowed, true ) ? $value : 'exclude';
}

function raffle_search_sanitize_types_mode( $value ) {
	$allowed = array( 'exclude', 'include' );
	return in_array( $value, $allowed, true ) ? $value : 'exclude';
}

function raffle_search_field_hidden_types() {
	$types_value = get_option( 'raffle_search_hidden_types', '' );
	$mode_value  = get_option( 'raffle_search_types_mode', 'exclude' );
	?>
<div style="display:flex;gap:8px;align-items:flex-start;flex-wrap:wrap;">
    <select id="raffle_search_types_mode" name="raffle_search_types_mode" style="height:30px;">
        <option value="exclude" <?php selected( $mode_value, 'exclude' ); ?>>
            <?php esc_html_e( 'Exclude', 'kp-search-with-raffle' ); ?></option>
        <option value="include" <?php selected( $mode_value, 'include' ); ?>>
            <?php esc_html_e( 'Include only', 'kp-search-with-raffle' ); ?></option>
    </select>
    <input type="text" id="raffle_search_hidden_types" name="raffle_search_hidden_types"
        value="<?php echo esc_attr( $types_value ); ?>" class="regular-text" placeholder="news,document,page" />
</div>
<p class="description">
    <?php esc_html_e( 'Comma-separated list of type names (news, document, page). "Exclude" hides these types from result cards and type filters; "Include only" shows only these types.', 'kp-search-with-raffle' ); ?>
</p>
<?php
}

function raffle_search_field_hidden_tags() {
	$tags_value = get_option( 'raffle_search_hidden_tags', '' );
	$mode_value = get_option( 'raffle_search_tags_mode', 'exclude' );
	?>
<div style="display:flex;gap:8px;align-items:flex-start;flex-wrap:wrap;">
    <select id="raffle_search_tags_mode" name="raffle_search_tags_mode" style="height:30px;">
        <option value="exclude" <?php selected( $mode_value, 'exclude' ); ?>>
            <?php esc_html_e( 'Exclude', 'kp-search-with-raffle' ); ?></option>
        <option value="include" <?php selected( $mode_value, 'include' ); ?>>
            <?php esc_html_e( 'Include only', 'kp-search-with-raffle' ); ?></option>
    </select>
    <input type="text" id="raffle_search_hidden_tags" name="raffle_search_hidden_tags"
        value="<?php echo esc_attr( $tags_value ); ?>" class="regular-text" placeholder="internal,draft" />
</div>
<p class="description">
    <?php esc_html_e( 'Comma-separated list of tag names. "Exclude" hides these tags; "Include only" shows only these tags on result cards and tag filters.', 'kp-search-with-raffle' ); ?>
</p>
<?php
}
function raffle_search_field_hide_summary_button() {
	$value = get_option( 'raffle_search_hide_summary_button', false );
	?>
<label for="raffle_search_hide_summary_button">
    <input type="checkbox" id="raffle_search_hide_summary_button" name="raffle_search_hide_summary_button" value="1"
        <?php checked( 1, $value ); ?> />
    <?php esc_html_e( 'Hide the "Learn More" button in the AI summary.', 'kp-search-with-raffle' ); ?>
</label>
<?php
}

function raffle_search_field_image_width() {
	$value = get_option( 'raffle_search_image_width', 250 );
	?>
<input type="number" id="raffle_search_image_width" name="raffle_search_image_width"
    value="<?php echo esc_attr( $value ); ?>" class="small-text" min="0" max="600" step="10" />
<p class="description">
    <?php esc_html_e( 'Width of the result thumbnail image in pixels. Set to 0 to hide images entirely. Default: 250.', 'kp-search-with-raffle' ); ?>
</p>
<?php
}

function raffle_search_field_default_image_url() {
	$value = get_option( 'raffle_search_default_image_url', '' );
	?>
<div id="kp-search-with-raffle-default-image-upload">
    <?php if ( $value ) : ?>
    <img src="<?php echo esc_url( $value ); ?>"
        style="max-width:100px;max-height:100px;display:block;margin-bottom:8px;" alt="" />
    <?php endif; ?>
    <input type="url" id="raffle_search_default_image_url" name="raffle_search_default_image_url"
        value="<?php echo esc_attr( $value ); ?>" class="regular-text" placeholder="https://..." />
    <button type="button" class="button"
        id="raffle_search_default_image_upload_btn"><?php esc_html_e( 'Upload or Select Image', 'kp-search-with-raffle' ); ?></button>
    <p class="description">
        <?php esc_html_e( 'Select or upload a default image to use when no image is found in search results.', 'kp-search-with-raffle' ); ?>
    </p>
</div>
<script>
(function($) {
    $(function() {
        var frame;
        $('#raffle_search_default_image_upload_btn').on('click', function(e) {
            e.preventDefault();
            if (frame) {
                frame.open();
                return;
            }
            frame = wp.media({
                title: '<?php echo esc_js( __( 'Select or Upload Default Image', 'kp-search-with-raffle' ) ); ?>',
                button: {
                    text: '<?php echo esc_js( __( 'Use this image', 'kp-search-with-raffle' ) ); ?>'
                },
                multiple: false
            });
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#raffle_search_default_image_url').val(attachment.url).trigger('change');
                $('#kp-search-with-raffle-default-image-upload img').remove();
                $('#kp-search-with-raffle-default-image-upload').prepend('<img src="' +
                    attachment
                    .url +
                    '" style="max-width:100px;max-height:100px;display:block;margin-bottom:8px;" />'
                );
            });
            frame.open();
        });
    });
})(jQuery);
</script>
<?php
}

function raffle_search_badge_colors_section_description() {
	echo '<p>' . esc_html__( 'Customise the background and text colours for type and tag badges on result cards. Leave blank to use the defaults.', 'kp-search-with-raffle' ) . '</p>';
}

function raffle_search_field_type_badge_colors() {
	raffle_search_render_color_pair(
		'raffle_search_color_type_bg',
		'raffle_search_color_type_text',
		get_option( 'raffle_search_color_type_bg', '' ),
		get_option( 'raffle_search_color_type_text', '' ),
		'#fef3c7',
		'#b45309',
		__( 'Type', 'kp-search-with-raffle' )
	);
}

function raffle_search_field_tag_badge_colors() {
	raffle_search_render_color_pair(
		'raffle_search_color_tag_bg',
		'raffle_search_color_tag_text',
		get_option( 'raffle_search_color_tag_bg', '' ),
		get_option( 'raffle_search_color_tag_text', '' ),
		'#d1fae5',
		'#047857',
		__( 'Tag', 'kp-search-with-raffle' )
	);
}

function raffle_search_widget_icon_section_description() {
	echo '<p>' . esc_html__( 'Customise the magnifier icon colour in the KP Raffle Search Widget. Set a mobile colour to override on small screens; leave it blank to inherit the desktop colour.', 'kp-search-with-raffle' ) . '</p>';
}

function raffle_search_field_widget_icon_color() {
	$desktop = get_option( 'raffle_search_widget_icon_color', '' );
	$mobile  = get_option( 'raffle_search_widget_icon_color_mobile', '' );
	$default = '#333333';
	$preview_desktop = $desktop ? $desktop : $default;
	$preview_mobile  = $mobile ? $mobile : $preview_desktop;
	?>
<div style="display:flex;gap:2rem;align-items:flex-start;flex-wrap:wrap;">
    <div style="display:flex;flex-direction:column;gap:4px;">
        <span
            style="font-size:.82rem;font-weight:600;color:#555;"><?php esc_html_e( 'Desktop', 'kp-search-with-raffle' ); ?></span>
        <button type="button" class="raffle-swatch-trigger" data-target="raffle_search_widget_icon_color"
            data-preview="raffle-widget-icon-preview" data-prop="color"
            data-default="<?php echo esc_attr( $default ); ?>"
            style="width:36px;height:36px;border-radius:6px;border:2px solid rgba(0,0,0,.2);background:<?php echo esc_attr( $preview_desktop ); ?>;cursor:pointer;padding:0;box-shadow:0 1px 3px rgba(0,0,0,.08);"></button>
        <input type="hidden" id="raffle_search_widget_icon_color" name="raffle_search_widget_icon_color"
            value="<?php echo esc_attr( $desktop ); ?>" />
    </div>
    <div style="display:flex;flex-direction:column;gap:4px;">
        <span
            style="font-size:.82rem;font-weight:600;color:#555;"><?php esc_html_e( 'Mobile', 'kp-search-with-raffle' ); ?></span>
        <button type="button" class="raffle-swatch-trigger" data-target="raffle_search_widget_icon_color_mobile"
            data-preview="raffle-widget-icon-preview-mobile" data-prop="color"
            data-default="<?php echo esc_attr( $default ); ?>"
            style="width:36px;height:36px;border-radius:6px;border:2px solid rgba(0,0,0,.2);background:<?php echo esc_attr( $preview_mobile ); ?>;cursor:pointer;padding:0;box-shadow:0 1px 3px rgba(0,0,0,.08);"></button>
        <input type="hidden" id="raffle_search_widget_icon_color_mobile" name="raffle_search_widget_icon_color_mobile"
            value="<?php echo esc_attr( $mobile ); ?>" />
    </div>
    <div style="display:flex;flex-direction:column;gap:4px;">
        <span
            style="font-size:.82rem;font-weight:600;color:#555;"><?php esc_html_e( 'Preview', 'kp-search-with-raffle' ); ?></span>
        <div style="display:flex;gap:12px;align-items:center;">
            <span id="raffle-widget-icon-preview" style="color:<?php echo esc_attr( $preview_desktop ); ?>;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2" fill="none" />
                    <line x1="16.5" y1="16.5" x2="22" y2="22" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" />
                </svg>
            </span>
            <span id="raffle-widget-icon-preview-mobile"
                style="color:<?php echo esc_attr( $preview_mobile ); ?>;font-size:.75rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2" fill="none" />
                    <line x1="16.5" y1="16.5" x2="22" y2="22" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" />
                </svg>
                <span
                    style="display:block;font-size:.7rem;color:#888;margin-top:2px;"><?php esc_html_e( '(mobile)', 'kp-search-with-raffle' ); ?></span>
            </span>
        </div>
    </div>
</div>
<p class="description" style="margin-top:8px;">
    <?php esc_html_e( 'Leave blank to use the default (#333). If no mobile colour is set, the desktop colour is used on all screen sizes.', 'kp-search-with-raffle' ); ?>
</p>
<?php
}

function raffle_search_render_color_pair( $bg_id, $text_id, $bg_val, $text_val, $default_bg, $default_text, $label ) {
	$preview_bg   = $bg_val   ? $bg_val   : $default_bg;
	$preview_text = $text_val ? $text_val : $default_text;
	?>
<div class="raffle-color-pair" style="display:flex;gap:1.5rem;align-items:center;flex-wrap:wrap;">
    <div style="display:flex;flex-direction:column;gap:4px;">
        <span
            style="font-size:.82rem;font-weight:600;color:#555;"><?php esc_html_e( 'Background', 'kp-search-with-raffle' ); ?></span>
        <button type="button" class="raffle-swatch-trigger" data-target="<?php echo esc_attr( $bg_id ); ?>"
            data-preview="preview-<?php echo esc_attr( $bg_id ); ?>" data-prop="background"
            data-default="<?php echo esc_attr( $default_bg ); ?>"
            style="width:36px;height:36px;border-radius:6px;border:2px solid rgba(0,0,0,.2);background:<?php echo esc_attr( $preview_bg ); ?>;cursor:pointer;padding:0;box-shadow:0 1px 3px rgba(0,0,0,.08);"></button>
        <input type="hidden" id="<?php echo esc_attr( $bg_id ); ?>" name="<?php echo esc_attr( $bg_id ); ?>"
            value="<?php echo esc_attr( $bg_val ); ?>" />
    </div>
    <div style="display:flex;flex-direction:column;gap:4px;">
        <span
            style="font-size:.82rem;font-weight:600;color:#555;"><?php esc_html_e( 'Text', 'kp-search-with-raffle' ); ?></span>
        <button type="button" class="raffle-swatch-trigger" data-target="<?php echo esc_attr( $text_id ); ?>"
            data-preview="preview-<?php echo esc_attr( $bg_id ); ?>" data-prop="color"
            data-default="<?php echo esc_attr( $default_text ); ?>"
            style="width:36px;height:36px;border-radius:6px;border:2px solid rgba(0,0,0,.2);background:<?php echo esc_attr( $preview_text ); ?>;cursor:pointer;padding:0;box-shadow:0 1px 3px rgba(0,0,0,.08);"></button>
        <input type="hidden" id="<?php echo esc_attr( $text_id ); ?>" name="<?php echo esc_attr( $text_id ); ?>"
            value="<?php echo esc_attr( $text_val ); ?>" />
    </div>
    <div style="display:flex;flex-direction:column;gap:4px;">
        <span
            style="font-size:.82rem;font-weight:600;color:#555;"><?php esc_html_e( 'Preview', 'kp-search-with-raffle' ); ?></span>
        <span id="preview-<?php echo esc_attr( $bg_id ); ?>"
            style="display:inline-flex;align-items:center;padding:.2em .85em;border-radius:999px;font-size:.85rem;font-weight:500;line-height:1.6;background:<?php echo esc_attr( $preview_bg ); ?>;color:<?php echo esc_attr( $preview_text ); ?>;">
            <?php echo esc_html( $label ); ?>
        </span>
    </div>
</div>
<?php
}
}
add_action( 'admin_init', 'raffle_search_register_settings' );

function raffle_search_enqueue_color_picker( $hook ) {
	if ( 'settings_page_kp-search-with-raffle-settings' !== $hook ) {
		return;
	}
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'raffle_search_enqueue_color_picker' );

function raffle_search_output_badge_color_styles() {
	// sanitize_hex_color() returns null for anything that is not a valid hex
	// colour, so the values interpolated below can never break out of the rule.
	$type_bg   = sanitize_hex_color( get_option( 'raffle_search_color_type_bg', '' ) );
	$type_text = sanitize_hex_color( get_option( 'raffle_search_color_type_text', '' ) );
	$tag_bg    = sanitize_hex_color( get_option( 'raffle_search_color_tag_bg', '' ) );
	$tag_text  = sanitize_hex_color( get_option( 'raffle_search_color_tag_text', '' ) );

	$css = '';

	if ( $type_bg || $type_text ) {
		$rule = '';
		if ( $type_bg )   $rule .= 'background:' . $type_bg . '!important;';
		if ( $type_text ) $rule .= 'color:' . $type_text . '!important;';
		$css .= '.raffle-meta-tag--type,.raffle-filter-type,.raffle-filter-type.is-active{' . $rule . '}';
		if ( $type_bg )   $css .= '.raffle-filter-type-count{background:' . $type_bg . '!important;}';
		if ( $type_text ) $css .= '.raffle-filter-type-count{color:' . $type_text . '!important;}';
	}

	if ( $tag_bg || $tag_text ) {
		$rule = '';
		if ( $tag_bg )   $rule .= 'background:' . $tag_bg . '!important;';
		if ( $tag_text ) $rule .= 'color:' . $tag_text . '!important;';
		$css .= '.raffle-meta-tag--tag,.raffle-filter-tag,.raffle-filter-tag.is-active{' . $rule . '}';
		if ( $tag_bg )   $css .= '.raffle-filter-tag-count{background:' . $tag_bg . '!important;}';
		if ( $tag_text ) $css .= '.raffle-filter-tag-count{color:' . $tag_text . '!important;}';
	}

	if ( $css ) {
		echo '<style id="raffle-badge-colors">' . wp_strip_all_tags( $css ) . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
	}

	// Widget icon colour.
	$icon_desktop = sanitize_hex_color( get_option( 'raffle_search_widget_icon_color', '' ) );
	$icon_mobile  = sanitize_hex_color( get_option( 'raffle_search_widget_icon_color_mobile', '' ) );

	if ( $icon_desktop || $icon_mobile ) {
		$icon_css = '';
		if ( $icon_desktop ) {
			$icon_css .= '.kp-search-with-raffle-widget__trigger{color:' . $icon_desktop . ';}';
		}
		if ( $icon_mobile ) {
			$icon_css .= '@media(max-width:990px){.kp-search-with-raffle-widget__trigger{color:' . $icon_mobile . ';}}';
		}
		// Attach to both the block style handle and the shortcode style handle
		// so the override prints right after whichever stylesheet is enqueued.
		$widget_block_handle = function_exists( 'generate_block_asset_handle' )
			? generate_block_asset_handle( 'kp-search-with-raffle/widget', 'style' )
			: '';
		if ( $widget_block_handle && wp_style_is( $widget_block_handle, 'registered' ) ) {
			wp_add_inline_style( $widget_block_handle, $icon_css );
		}
		if ( wp_style_is( 'kp-search-with-raffle-widget-shortcode-style', 'registered' ) ) {
			wp_add_inline_style( 'kp-search-with-raffle-widget-shortcode-style', $icon_css );
		}
	}
}
add_action( 'wp_head', 'raffle_search_output_badge_color_styles' );

function raffle_search_section_description() {
	echo '<p>' . esc_html__( 'Enter your Raffle AI credentials. Find these in the Raffle Web App under your API User Interface settings.', 'kp-search-with-raffle' ) . '</p>';
}

function raffle_search_metadata_section_description() {
	$img_url    = plugins_url( 'includes/assets/metadata-sample.png', dirname( __FILE__ ) );
	$ref_url    = 'https://docs.raffle.ai/api/guides/search-results-customization/metadata-selectors/';
	$adv_url    = 'https://app.raffle.ai';
	?>
<p><?php esc_html_e( 'Update WordPress meta-data that allows Raffle to improve the index. Note that changes will not affect the index until the next indexing schedule – usually within 1 day.', 'kp-search-with-raffle' ); ?>
</p>
<p>
    <?php esc_html_e( 'The following metadata attributes are supported:', 'kp-search-with-raffle' ); ?>
    <code>published_time</code>, <code>description</code>, <code>image</code>, <code>tag</code>
</p>
<p><?php esc_html_e( 'You need to add these items to your index under', 'kp-search-with-raffle' ); ?>
    <strong><?php esc_html_e( 'Advanced settings', 'kp-search-with-raffle' ); ?></strong>:
</p>
<p><img src="<?php echo esc_url( $img_url ); ?>" width="700" alt="
        <?php esc_attr_e( 'Metadata sample screenshot', 'kp-search-with-raffle' ); ?>"
        style="max-width:100%;height:auto;border:1px solid #ddd;border-radius:4px;" /></p>
<p><a href="<?php echo esc_url( $ref_url ); ?>" target="_blank"
        rel="noopener noreferrer"><?php esc_html_e( 'Reference: Metadata Selectors – Raffle Docs', 'kp-search-with-raffle' ); ?></a>
</p>
<?php
}

function raffle_search_settings_section_description() {
	echo '<p>' . esc_html__( 'Modify the visibility and data structure of search results.', 'kp-search-with-raffle' ) . '</p>';
}

function raffle_search_field_base_url() {
	$value = get_option( 'raffle_search_base_url', 'https://api.raffle.ai/v2' );
	?>
<input type="url" id="raffle_search_base_url" name="raffle_search_base_url" value="<?php echo esc_attr( $value ); ?>"
    class="regular-text" placeholder="https://api.raffle.ai/v2" />
<p class="description">
    <?php esc_html_e( 'The Raffle API base URL. Defaults to https://api.raffle.ai/v2.', 'kp-search-with-raffle' ); ?>
</p>
<?php
}

function raffle_search_field_search_uid() {
	$value = get_option( 'raffle_search_uid', '' );
	?>
<input type="password" id="raffle_search_uid" name="raffle_search_uid" value="<?php echo esc_attr( $value ); ?>"
    class="regular-text" placeholder="D2FF7152-8089-41A9-A65D-E82111A11E49" autocomplete="off" />
<button type="button" onclick="
		var f = document.getElementById('raffle_search_uid');
		if (f.type === 'password') { f.type = 'text'; this.textContent = 'Hide'; } else { f.type = 'password'; this.textContent = 'Show'; }
	" style="margin-left:8px;">Show</button>
<p class="description">
    <?php esc_html_e( 'The UID of your Raffle Search UI (Tool UID). Found in the Install modal of your tool in the Raffle Web App.', 'kp-search-with-raffle' ); ?>
</p>
<?php
}

function raffle_search_field_show_references() {
	$value = get_option( 'raffle_search_show_references', true );
	?>
<label for="raffle_search_show_references">
    <input type="checkbox" id="raffle_search_show_references" name="raffle_search_show_references" value="1"
        <?php checked( 1, $value ); ?> />
    <?php esc_html_e( 'Display the References list below the AI summary.', 'kp-search-with-raffle' ); ?>
</label>
<?php
}

// Render the settings page.
// Sanitize the trim length: allow null or positive integer
function raffle_search_sanitize_trim_length( $value ) {
	if ( $value === '' || is_null( $value ) ) {
		return null;
	}
	$int = intval( $value );
	return $int > 0 ? $int : null;
}

function raffle_search_field_excerpt_trim_length() {
	$value = get_option( 'raffle_search_excerpt_trim_length', null );
	?>
<input type="number" id="raffle_search_excerpt_trim_length" name="raffle_search_excerpt_trim_length"
    value="<?php echo esc_attr( $value ); ?>" class="small-text" min="1" placeholder="None" />
<p class="description">
    <?php esc_html_e( 'Maximum number of characters to show in each result excerpt/snippet, except for instant answers. Leave blank for no trimming.', 'kp-search-with-raffle' ); ?>
</p>
<?php
}

function raffle_search_field_instant_answer_excerpt_trim_length() {
	$value = get_option( 'raffle_search_instant_answer_excerpt_trim_length', null );
	?>
<input type="number" id="raffle_search_instant_answer_excerpt_trim_length"
    name="raffle_search_instant_answer_excerpt_trim_length" value="<?php echo esc_attr( $value ); ?>" class="small-text"
    min="1" placeholder="None" />
<p class="description">
    <?php esc_html_e( 'Maximum number of characters to show in instant answer excerpts/snippets (type: instant_answer). Leave blank for no trimming.', 'kp-search-with-raffle' ); ?>
</p>
<?php
}
function raffle_search_render_settings_page() {
	   if ( ! current_user_can( 'manage_options' ) ) {
		   return;
	   }
	   // Ensure media scripts are loaded for uploader
	   if ( function_exists( 'wp_enqueue_media' ) ) {
		   wp_enqueue_media();
	   }
	?>
<div class="wrap">
    <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 24px;">
        <div style="height: 64px; width: 64px; background: #12151f; border-radius: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" id="icon" viewBox="0 0 500 500">
                <defs>
                    <style>
                    .st1 {
                        fill: #e7f76e;
                        fill-rule: evenodd
                    }
                    </style>
                </defs>
                <path id="bg" d="M0 0h500v500H0z" style="fill:#12151f" />
                <path
                    d="M337.4 226.4c-4.9-2.8-10-4.9-15.3-6.5l-18 18c21.6 4.8 34.4 20.2 34.4 49.5s-21 53.8-47.1 53.8-12 0-25.3-1.8V276l-26.5 26.6v113.9h26.5v-56.7c13.9 1.8 21.3 2.2 27.2 2.2 15.2 0 30-3.2 45-12.2 19.7-12.2 28.7-36.5 28.7-62.2s-6.6-47.1-29.5-61M222.8 313.5l-35.3-36 70.6-59.7h-35.5l-63.7 58.7V158.1h-25.8v199h25.8v-73.2l45.4 48.1z"
                    class="st1" />
                <path
                    d="m301.1 199-29.5-29.5c-.2-.2-.5-.4-.8-.6 7-9 11.2-20.3 11.2-32.5 0-29.3-23.8-53.1-53.1-53.1s-53.1 23.8-53.1 53.1 23.8 53.1 53.1 53.1 23.5-4.2 32.5-11.2c.2.3.4.5.6.8l29.5 29.5c1.3 1.3 3 2 4.8 2s3.5-.7 4.8-2c2.6-2.6 2.6-6.9 0-9.5ZM229 176c-21.8 0-39.6-17.8-39.6-39.6s17.8-39.6 39.6-39.6 39.6 17.8 39.6 39.6S250.8 176 229 176"
                    style="fill:#fff" />
            </svg>
        </div>
        <h1 style="margin: 0; padding: 0;"><?php echo esc_html( get_admin_page_title() ); ?></h1>
    </div>

    <h2 class="nav-tab-wrapper" id="raffle-tab-nav">
        <a href="#tab-general" class="nav-tab nav-tab-active"
            data-tab="tab-general"><?php esc_html_e( 'General', 'kp-search-with-raffle' ); ?></a>
        <a href="#tab-metadata" class="nav-tab"
            data-tab="tab-metadata"><?php esc_html_e( 'Metadata', 'kp-search-with-raffle' ); ?></a>
        <a href="#tab-settings" class="nav-tab"
            data-tab="tab-settings"><?php esc_html_e( 'Settings', 'kp-search-with-raffle' ); ?></a>
        <a href="#tab-design" class="nav-tab"
            data-tab="tab-design"><?php esc_html_e( 'Design', 'kp-search-with-raffle' ); ?></a>
        <a href="#tab-shortcodes" class="nav-tab"
            data-tab="tab-shortcodes"><?php esc_html_e( 'Shortcodes', 'kp-search-with-raffle' ); ?></a>
        <a href="#tab-about" class="nav-tab"
            data-tab="tab-about"><?php esc_html_e( 'About', 'kp-search-with-raffle' ); ?></a>
    </h2>

    <form method="post" action="options.php">
        <?php settings_fields( 'raffle_search_options' ); ?>

        <div id="tab-general" class="raffle-tab-panel">
            <?php do_settings_sections( 'kp-search-with-raffle-general' ); ?>
        </div>

        <div id="tab-metadata" class="raffle-tab-panel" style="display:none;">
            <?php do_settings_sections( 'kp-search-with-raffle-metadata' ); ?>
        </div>

        <div id="tab-settings" class="raffle-tab-panel" style="display:none;">
            <?php do_settings_sections( 'kp-search-with-raffle-vis-settings' ); ?>
        </div>

        <div id="tab-design" class="raffle-tab-panel" style="display:none;">
            <?php do_settings_sections( 'kp-search-with-raffle-design' ); ?>
        </div>

        <div id="tab-shortcodes" class="raffle-tab-panel" style="display:none;">
            <h2><?php esc_html_e( 'Shortcodes', 'kp-search-with-raffle' ); ?></h2>
            <p><?php esc_html_e( 'You can use shortcodes to embed the Raffle Search blocks in templates, widgets, or any content area that does not support Gutenberg blocks.', 'kp-search-with-raffle' ); ?>
            </p>

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><?php esc_html_e( 'Raffle Search', 'kp-search-with-raffle' ); ?></th>
                    <td>
                        <p><?php esc_html_e( 'Renders the full Raffle Search experience.', 'kp-search-with-raffle' ); ?>
                        </p>
                        <code>[raffle_search]</code>
                        <p class="description" style="margin-top:8px;">
                            <?php esc_html_e( 'Optional attribute:', 'kp-search-with-raffle' ); ?>
                            <code>uid</code> &mdash;
                            <?php esc_html_e( 'Override the global Search UID for this instance.', 'kp-search-with-raffle' ); ?>
                        </p>
                        <p style="margin-top:4px;">
                            <?php esc_html_e( 'Example:', 'kp-search-with-raffle' ); ?>
                            <code>[raffle_search uid="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"]</code>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'KP Raffle Search Widget', 'kp-search-with-raffle' ); ?></th>
                    <td>
                        <p><?php esc_html_e( 'Renders a magnifier icon that opens the search overlay or links to a search page.', 'kp-search-with-raffle' ); ?>
                        </p>
                        <code>[raffle_search_widget]</code>
                        <p class="description" style="margin-top:8px;">
                            <?php esc_html_e( 'Optional attributes:', 'kp-search-with-raffle' ); ?>
                        </p>
                        <ul style="margin-top:4px;list-style:disc;padding-left:20px;">
                            <li>
                                <code>mode</code> &mdash;
                                <?php esc_html_e( '"overlay" (default) or "link".', 'kp-search-with-raffle' ); ?>
                            </li>
                            <li>
                                <code>url</code> &mdash;
                                <?php esc_html_e( 'The search page URL (used when mode="link").', 'kp-search-with-raffle' ); ?>
                            </li>
                        </ul>
                        <p style="margin-top:4px;">
                            <?php esc_html_e( 'Examples:', 'kp-search-with-raffle' ); ?><br>
                            <code>[raffle_search_widget]</code><br>
                            <code>[raffle_search_widget mode="link" url="https://example.com/search"]</code>
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <div id="tab-about" class="raffle-tab-panel" style="display:none;">
            <h2><?php esc_html_e( 'About this plugin', 'kp-search-with-raffle' ); ?></h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><?php esc_html_e( 'Version', 'kp-search-with-raffle' ); ?></th>
                    <td>
                        <p><?php echo esc_html( KP_SEARCH_WITH_RAFFLE_VERSION ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Author', 'kp-search-with-raffle' ); ?></th>
                    <td>
                        <p><?php esc_html_e( 'This plugin is built and maintained by', 'kp-search-with-raffle' ); ?>
                            <a href="https://klausenogpartners.dk/" target="_blank" rel="noopener noreferrer">Klausen og
                                Partners</a>.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Source code', 'kp-search-with-raffle' ); ?></th>
                    <td>
                        <p><a href="https://github.com/klausen-partners/kp-search-with-raffle"
                                target="_blank"
                                rel="noopener noreferrer">github.com/klausen-partners/kp-search-with-raffle</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Feature requests &amp; bugs', 'kp-search-with-raffle' ); ?></th>
                    <td>
                        <p><?php esc_html_e( 'Found a bug or have a feature request? Please open an issue on GitHub:', 'kp-search-with-raffle' ); ?><br>
                            <a href="https://github.com/klausen-partners/kp-search-with-raffle/issues"
                                target="_blank"
                                rel="noopener noreferrer">github.com/klausen-partners/kp-search-with-raffle/issues</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Powered by Raffle', 'kp-search-with-raffle' ); ?></th>
                    <td>
                        <p><?php esc_html_e( 'Thanks to', 'kp-search-with-raffle' ); ?>
                            <a href="https://business.raffle.ai/" target="_blank" rel="noopener noreferrer">Raffle</a>
                            <?php esc_html_e( 'for providing the', 'kp-search-with-raffle' ); ?>
                            <a href="https://docs.raffle.ai/api/" target="_blank"
                                rel="noopener noreferrer"><?php esc_html_e( 'API', 'kp-search-with-raffle' ); ?></a>
                            <?php esc_html_e( 'that was used to build this plugin.', 'kp-search-with-raffle' ); ?>
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <?php submit_button( __( 'Save Settings', 'kp-search-with-raffle' ) ); ?>
    </form>

    <hr style="margin: 32px 0;" />
</div>

<script>
jQuery(function($) {
    var storageKey = 'raffle_active_tab';

    function activateTab(tabId, save) {
        $('.raffle-tab-panel').hide();
        $('#raffle-tab-nav .nav-tab').removeClass('nav-tab-active');
        $('#' + tabId).show();
        $('#raffle-tab-nav [data-tab="' + tabId + '"]').addClass('nav-tab-active');
        $('#submit').closest('.submit').toggle(tabId !== 'tab-about');
        if (save) {
            try {
                localStorage.setItem(storageKey, tabId);
            } catch (e) {}
        }
    }

    var stored = '';
    try {
        stored = localStorage.getItem(storageKey) || '';
    } catch (e) {}
    if (stored && $('#' + stored).length) {
        activateTab(stored, false);
    }

    $('#raffle-tab-nav .nav-tab').on('click', function(e) {
        e.preventDefault();
        activateTab($(this).data('tab'), true);
    });
});
</script>

<div id="raffle-color-overlay" style="display:none;position:fixed;z-index:100000;background:#fff;border:1px solid #c3c4c7;
		    border-radius:8px;box-shadow:0 6px 24px rgba(0,0,0,.18);padding:16px 18px 14px;min-width:240px;">
    <button type="button" id="raffle-overlay-close" style="position:absolute;top:8px;right:10px;background:none;border:none;cursor:pointer;
				   font-size:18px;line-height:1;color:#888;padding:2px 4px;"
        aria-label="<?php esc_attr_e( 'Close', 'kp-search-with-raffle' ); ?>">&times;</button>

    <div id="raffle-overlay-swatches" style="display:none;margin-bottom:12px;">
        <p
            style="margin:0 0 6px;font-size:.75rem;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.05em;">
            <?php esc_html_e( 'Theme Colors', 'kp-search-with-raffle' ); ?>
        </p>
        <div id="raffle-overlay-swatches-list" style="display:flex;flex-wrap:wrap;gap:6px;"></div>
        <hr style="margin:10px 0;border:none;border-top:1px solid #f0f0f0;">
    </div>

    <div id="raffle-overlay-picker-wrap">
        <input type="text" id="raffle-overlay-picker-input" />
    </div>

    <div style="margin-top:8px;">
        <button type="button" id="raffle-overlay-reset"
            style="font-size:.8rem;color:#999;background:none;border:none;cursor:pointer;padding:0;text-decoration:underline;">
            <?php esc_html_e( 'Reset to default', 'kp-search-with-raffle' ); ?>
        </button>
    </div>
</div>

<script>
jQuery(function($) {
    var $overlay = $('#raffle-color-overlay');
    var $pickerInput = $('#raffle-overlay-picker-input');
    var currentTarget = null;
    var currentProp = null;
    var currentPreview = null;
    var currentDefault = null;
    var $activeTrigger = null;
    var pickerInited = false;

    <?php
	$palette      = get_theme_support( 'editor-color-palette' );
	$theme_colors = ( ! empty( $palette ) && is_array( $palette[0] ) ) ? $palette[0] : array();
	echo 'var raffleThemeColors = ' . wp_json_encode( array_values( $theme_colors ) ) . ';';
	?>

    if (raffleThemeColors.length) {
        var $list = $('#raffle-overlay-swatches-list');
        $.each(raffleThemeColors, function(i, c) {
            $list.append(
                $('<button>').attr({
                    type: 'button',
                    title: c.name || c.color,
                    'data-color': c.color
                }).css({
                    width: '26px',
                    height: '26px',
                    borderRadius: '50%',
                    background: c.color,
                    border: '2px solid rgba(0,0,0,.12)',
                    cursor: 'pointer',
                    padding: 0,
                    boxShadow: '0 1px 3px rgba(0,0,0,.1)',
                    flexShrink: 0
                })
            );
        });
        $('#raffle-overlay-swatches').show();
    }

    function initPicker() {
        if (pickerInited) {
            return;
        }
        $pickerInput.wpColorPicker({
            change: function(event, ui) {
                applyColor(ui.color.toString());
            },
            clear: function() {
                applyColor('');
            }
        });
        pickerInited = true;
    }

    function applyColor(color) {
        if (!currentTarget) {
            return;
        }
        var display = color || currentDefault;
        $('#' + currentTarget).val(color);
        if ($activeTrigger) {
            $activeTrigger.css('background', display);
        }
        if (currentPreview && currentProp) {
            $('#' + currentPreview).css(currentProp, display);
        }
    }

    function positionOverlay($trigger) {
        var rect = $trigger[0].getBoundingClientRect();
        var top = rect.bottom + 6;
        var left = rect.left;
        var overlayW = 260;
        var overlayH = 400;
        if (left + overlayW > window.innerWidth) {
            left = Math.max(4, window.innerWidth - overlayW - 8);
        }
        if (top + overlayH > window.innerHeight) {
            top = Math.max(4, rect.top - overlayH - 6);
        }
        $overlay.css({
            top: top + 'px',
            left: left + 'px'
        });
    }

    function openOverlay($trigger) {
        currentTarget = $trigger.data('target');
        currentProp = $trigger.data('prop');
        currentPreview = $trigger.data('preview');
        currentDefault = $trigger.data('default');
        $activeTrigger = $trigger;

        initPicker();

        var current = $('#' + currentTarget).val() || currentDefault;
        $pickerInput.wpColorPicker('color', current);

        positionOverlay($trigger);
        $overlay.show();
    }

    $(document).on('click', '.raffle-swatch-trigger', function(e) {
        e.stopPropagation();
        var $t = $(this);
        if ($overlay.is(':visible') && currentTarget === $t.data('target')) {
            $overlay.hide();
            return;
        }
        openOverlay($t);
    });

    $overlay.on('click', '#raffle-overlay-swatches-list button', function(e) {
        e.stopPropagation();
        var color = $(this).data('color');
        $pickerInput.wpColorPicker('color', color);
        applyColor(color);
    });

    $('#raffle-overlay-close').on('click', function() {
        $overlay.hide();
    });

    $('#raffle-overlay-reset').on('click', function() {
        $('#' + currentTarget).val('');
        $pickerInput.wpColorPicker('color', currentDefault || '');
        if ($activeTrigger) {
            $activeTrigger.css('background', currentDefault);
        }
        if (currentPreview && currentProp) {
            $('#' + currentPreview).css(currentProp, currentDefault);
        }
    });

    $overlay.on('click', function(e) {
        e.stopPropagation();
    });

    $(document).on('click', function() {
        if ($overlay.is(':visible')) {
            $overlay.hide();
        }
    });
});
</script>

<?php
}
