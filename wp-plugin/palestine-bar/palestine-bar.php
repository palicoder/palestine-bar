<?php
/*
Plugin Name: PalestineBar
Description: Inserts the PalestineBar javascript file into wordpress
*/

// Add settings page to the WordPress dashboard
add_action('admin_menu', 'palestine_bar_menu');

function palestine_bar_menu() {
    add_options_page('PalestineBar Settings', 'PalestineBar', 'manage_options', 'palestine-bar-settings', 'palestine_bar_settings_page');
}

// Settings page content
function palestine_bar_settings_page() {
    ?>
    <div class="wrap">
        <h2>PalestineBar Settings</h2>
        <form method="post" action="options.php">
            <?php
                settings_fields('palestine-bar-settings-group');
                do_settings_sections('palestine-bar-settings-group');
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

function is_hex_color($color) {
    // Check if the color starts with a hash (#) and is followed by either 3 or 6 valid hex characters
    return preg_match('/^#([A-Fa-f0-9]{3}){1,2}$/', $color);
}

// Register settings
add_action('admin_init', 'palestine_bar_settings');

function palestine_bar_settings() {
    $defaults = array(
        'data_text' => 'Save Palestinian children',
        'data_button_text' => 'Donate now',
        'data_bar_mode' => 'header',
        'data_bg_color' => '#000',
        'data_button_color' => '#ED2E38',
        'data_text_color' => '#fff',
        'data_donation_link' => 'https://irusa.org/middle-east/palestine/',
        'data_image_link' => plugins_url( 'images/gaza.jpg', __FILE__ )
    );

    add_option('data_text', $defaults['data_text']);
    add_option('data_button_text', $defaults['data_button_text']);
    add_option('data_bar_mode', $defaults['data_bar_mode']);
    add_option('data_bg_color', $defaults['data_bg_color']);
    add_option('data_button_color', $defaults['data_button_color']);
    add_option('data_text_color', $defaults['data_text_color']);
    add_option('data_donation_link', $defaults['data_donation_link']);
    add_option('data_image_link', $defaults['data_image_link']);

    register_setting('palestine-bar-settings-group', 'data_text');
    register_setting('palestine-bar-settings-group', 'data_button_text');
    register_setting('palestine-bar-settings-group', 'data_bar_mode');
    register_setting('palestine-bar-settings-group', 'data_bg_color', array('sanitize_callback' => 'validate_and_sanitize_data_bg_color'));
    register_setting('palestine-bar-settings-group', 'data_button_color', array('sanitize_callback' => 'validate_and_sanitize_data_button_color'));
    register_setting('palestine-bar-settings-group', 'data_text_color', array('sanitize_callback' => 'validate_and_sanitize_data_text_color'));
    register_setting('palestine-bar-settings-group', 'data_donation_link');
    register_setting('palestine-bar-settings-group', 'data_image_link');

    add_settings_section('palestine-bar-settings-section', 'Settings', 'palestine_bar_section_callback', 'palestine-bar-settings-group');

    add_settings_field('palestine-bar-data-text', 'Text message', 'palestine_bar_data_text', 'palestine-bar-settings-group', 'palestine-bar-settings-section');
    add_settings_field('palestine-bar-data-button-text', 'Donation button text', 'palestine_bar_data_button_text', 'palestine-bar-settings-group', 'palestine-bar-settings-section');
    add_settings_field('palestine-bar-data-bar-mode', 'Display mode', 'palestine_bar_data_bar_mode', 'palestine-bar-settings-group', 'palestine-bar-settings-section');
    add_settings_field('palestine-bar-data-bg-color', 'Background color', 'palestine_bar_data_bg_color', 'palestine-bar-settings-group', 'palestine-bar-settings-section');
    add_settings_field('palestine-bar-data-button-color', 'Button color', 'palestine_bar_data_button_color', 'palestine-bar-settings-group', 'palestine-bar-settings-section');
    add_settings_field('palestine-bar-data-text-color', 'Text color', 'palestine_bar_data_text_color', 'palestine-bar-settings-group', 'palestine-bar-settings-section');
    add_settings_field('palestine-bar-data-donation-link', 'Donation Link', 'palestine_bar_data_donation_link', 'palestine-bar-settings-group', 'palestine-bar-settings-section');
    add_settings_field('palestine-bar-data_image_link', 'Image Link', 'palestine_bar_data_image_link', 'palestine-bar-settings-group', 'palestine-bar-settings-section');

    if (isset($_POST['data_image_link'])) {
        update_option('data_image_link', $_POST['data_image_link']);
    }
}

// Section callback
function palestine_bar_section_callback() {
    echo '<p>Modify the content of the PalestineBar</p>';
}

// Field callback
function palestine_bar_data_text() {
    $data_text = esc_attr(get_option('data_text'));
    echo "<input type='text' id='data_text' name='data_text' value='$data_text' size='100' />";
}

function palestine_bar_data_button_text() {
    $data_button_text = esc_attr(get_option('data_button_text'));
    echo "<input type='text' id='data_button_text' name='data_button_text' value='$data_button_text' size='50' />";
}

function palestine_bar_data_bar_mode() {
    $data_bar_mode = esc_attr(get_option('data_bar_mode'));
    echo "<select id='data_bar_mode' name='data_bar_mode'>
    <option value='header'".($data_bar_mode == 'header' ? ' selected' : '').">Header</option>
    <option value='fixed-header'".($data_bar_mode == 'fixed-header' ? ' selected' : '').">Fixed header</option>
    <option value='fixed-footer'".($data_bar_mode == 'fixed-footer' ? ' selected' : '').">Fixed footer</option>
    <option value='popup-left'".($data_bar_mode == 'popup-left' ? ' selected' : '').">Popup left</option>
    <option value='popup-right'".($data_bar_mode == 'popup-right' ? ' selected' : '').">Popup right</option>
    </select>";
}

function palestine_bar_data_bg_color() {
    $data_bg_color = esc_attr(get_option('data_bg_color'));
    echo "<input type='text' id='data_bg_color' name='data_bg_color' value='$data_bg_color' size='7' />";
    echo "<p class='description'>" . (is_hex_color($data_bg_color) ? 'Valid color' : 'Invalid color') . "</p>";
}

function palestine_bar_data_button_color() {
    $data_button_color = esc_attr(get_option('data_button_color'));
    echo "<input type='text' id='data_button_color' name='data_button_color' value='$data_button_color' size='7' />";
    echo "<p class='description'>" . (is_hex_color($data_button_color) ? 'Valid color' : 'Invalid color') . "</p>";
}

function palestine_bar_data_text_color() {
    $data_text_color = esc_attr(get_option('data_text_color'));
    echo "<input type='text' id='data_text_color' name='data_text_color' value='$data_text_color' size='7' />";
    echo "<p class='description'>" . (is_hex_color($data_text_color) ? 'Valid color' : 'Invalid color') . "</p>";
}

function palestine_bar_data_donation_link() {
    $data_donation_link = get_option('data_donation_link');
    echo "<input type='text' id='data_donation_link' name='data_donation_link' value='$data_donation_link' size='255' style='width : 80%' />";
}

function palestine_bar_data_image_link() {
    $image_url = get_option('data_image_link');
    echo "<label for='data_image_link'>Select Image:</label><br>";
    echo "<input type='text' id='data_image_link' name='data_image_link' value='$image_url' readonly>";
    echo "<input type='button' id='palestine_bar_upload_image_button' class='button-secondary' value='Upload Image'>";
}

function validate_and_sanitize_hex_color($color, $field) {
    // Check if the color is a valid hex color
    if (is_hex_color($color)) {
        return $color;
    } else {
        // If not valid, display an error message and return the previous value
        add_settings_error($field, 'invalid_color', 'Invalid color. Please enter a valid hex color value.', 'error');
        return get_option($field);
    }
}

function validate_and_sanitize_data_bg_color($color) {
    return validate_and_sanitize_hex_color($color,'data_bg_color');
}

function validate_and_sanitize_data_button_color($color) {
    return validate_and_sanitize_hex_color($color,'data_button_color');
}

function validate_and_sanitize_data_text_color($color) {
    return validate_and_sanitize_hex_color($color,'data_text_color');
}

// Enqueue custom JavaScript file
function enqueue_palestine_bar_js_file() {
    $plugin_url = plugin_dir_url(__FILE__);

    // Pass settings to JavaScript
    $data_text = get_option('data_text');
    $data_button_text = get_option('data_button_text');
    $data_bar_mode = get_option('data_bar_mode');
    $data_bg_color = get_option('data_bg_color');
    $data_button_color = get_option('data_button_color');
    $data_text_color = get_option('data_text_color');
    $data_donation_link = get_option('data_donation_link');
    $data_image_link = get_option('data_image_link');

    wp_enqueue_script('palestine-bar', $plugin_url . 'js/palestine-bar.js', array(), '1.0', true);

    wp_localize_script('palestine-bar', 'wpSettings', array(
        'textContent' => $data_text,
        'buttonText' => $data_button_text,
        'barMode' => $data_bar_mode,
        'bgColor' => $data_bg_color,
        'buttonColor' => $data_button_color,
        'textColor' => $data_text_color,
        'donationLink' => $data_donation_link,
        'imageLink' => $data_image_link
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_palestine_bar_js_file');

function palestine_bar_admin_scripts() {
    wp_enqueue_media();
    wp_enqueue_script('palestine-bar-admin-script', plugin_dir_url(__FILE__) . 'js/admin-script.js', array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'palestine_bar_admin_scripts');