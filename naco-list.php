<?php
/*
Plugin Name: NACO List
Description: Display a marquee of named chicks with customizable speed and message via shortcode.
Version: 1.1
Author: Mando Works
*/

function naco_list_create_menu() {
    add_menu_page(
        'NACO List', 
        'NACO List', 
        'manage_options', 
        'naco-list-settings', 
        'naco_list_settings_page', 
        'dashicons-list-view'
    );
}
add_action('admin_menu', 'naco_list_create_menu');

function naco_list_settings_page() {
    ?>
    <div class="wrap">
        <h1>NACO List Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('naco-list-settings-group'); ?>
            <?php do_settings_sections('naco-list-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Chick Names (Comma-separated)</th>
                    <td><textarea name="naco_list_names" style="width: 100%;" rows="5"><?php echo esc_attr(get_option('naco_list_names')); ?></textarea></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Speed (in milliseconds)</th>
                    <td><input type="number" name="naco_list_speed" value="<?php echo esc_attr(get_option('naco_list_speed', 50)); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Link to NACO Page</th>
                    <td><input type="text" name="naco_list_link" value="<?php echo esc_attr(get_option('naco_list_link', '#')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Custom Message (after every 3 names)</th>
                    <td><input type="text" name="naco_list_message" value="<?php echo esc_attr(get_option('naco_list_message', 'Name a chick online now!')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function naco_list_register_settings() {
    register_setting('naco-list-settings-group', 'naco_list_names');
    register_setting('naco-list-settings-group', 'naco_list_speed');
    register_setting('naco-list-settings-group', 'naco_list_link');
    register_setting('naco-list-settings-group', 'naco_list_message');
}
add_action('admin_init', 'naco_list_register_settings');

function naco_list_shortcode() {
    $names = explode(',', get_option('naco_list_names', ''));
    $speed = esc_attr(get_option('naco_list_speed', 50));
    $message = esc_attr(get_option('naco_list_message', 'Name a chick online now!'));
    $link = esc_url(get_option('naco_list_link', '#'));

    $output = '<div class="naco-list-wrapper"><marquee behavior="scroll" direction="left" scrollamount="' . $speed . '">Chicks named so far: ';

    $count = 0;
    foreach ($names as $name) {
        $output .= esc_html(trim($name)) . ' ';
        $count++;
        if ($count % 3 == 0) {
            $output .= '<a href="' . $link . '" target="_blank">' . $message . '</a> ';
        }
    }

    $output .= '</marquee></div>';
    return $output;
}
add_shortcode('naco_list', 'naco_list_shortcode');

function naco_list_enqueue_styles() {
    wp_enqueue_style('naco-list-style', plugins_url('naco-list.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'naco_list_enqueue_styles');
