<?php
/*
Plugin Name: Showcase Shipping Options (icons)
License: GPLv2 https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Description: Show your webshops shipping options (icons) via shortcode.
Version: 1.0.0
Author: KNEET
Author URI: https://kneet.be/
*/

if (!defined('ABSPATH')) {
    exit;
}

// Register the admin menu for the plugin
function ssoi_shipping_icons_plugin_menu() {
    add_menu_page(
        esc_html__('Shipping Icons Settings', 'showcase-shipping-options-icons'),
        esc_html__('Shipping Icons', 'showcase-shipping-options-icons'),
        'manage_options',
        'ssoi-shipping-icons-plugin',
        'ssoi_shipping_icons_plugin_settings_page',
        'dashicons-layout'
    );
}
add_action('admin_menu', 'ssoi_shipping_icons_plugin_menu');

// Render the settings page
function ssoi_shipping_icons_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h2><?php esc_html_e('Shipping Icons Settings', 'showcase-shipping-options-icons'); ?></h2>
        <form action="options.php" method="post">
            <?php
            settings_fields('ssoi-shipping-icons-plugin');
            do_settings_sections('ssoi-shipping-icons-plugin');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Initialize plugin settings
function ssoi_shipping_icons_plugin_settings_init() {
    register_setting('ssoi-shipping-icons-plugin', 'ssoi_shipping_icons_plugin_settings');

    add_settings_section(
        'ssoi_shipping_icons_plugin_main_section',
        esc_html__('General Settings', 'showcase-shipping-options-icons'),
        'ssoi_shipping_icons_plugin_section_cb',
        'ssoi-shipping-icons-plugin'
    );

    add_settings_section(
        'ssoi_shipping_icons_plugin_icon_size_section',
        esc_html__('Icon Size Settings', 'showcase-shipping-options-icons'),
        'ssoi_shipping_icons_plugin_icon_size_section_cb',
        'ssoi-shipping-icons-plugin'
    );

    add_settings_field(
        'ssoi_shipping_icons_icon_size',
        esc_html__('Icons size', 'showcase-shipping-options-icons'),
        'ssoi_shipping_icons_plugin_field_icon_size_cb',
        'ssoi-shipping-icons-plugin',
        'ssoi_shipping_icons_plugin_icon_size_section',
        [
            'label_for' => 'ssoi_shipping_icons_icon_size',
            'type' => 'number',
            'name' => 'ssoi_shipping_icons_icon_size',
            'value' => '65',
            'min' => '10',
            'max' => '512',
            'description' => esc_html__('Set the size of the shipping icons in pixels.', 'showcase-shipping-options-icons')
        ]
    );

    add_settings_field(
        'ssoi_shipping_icons_icon_spacing',
        esc_html__('Icon spacing', 'showcase-shipping-options-icons'),
        'ssoi_shipping_icons_plugin_field_icon_size_cb',
        'ssoi-shipping-icons-plugin',
        'ssoi_shipping_icons_plugin_icon_size_section',
        [
            'label_for' => 'ssoi_shipping_icons_icon_spacing',
            'type' => 'number',
            'name' => 'ssoi_shipping_icons_icon_spacing',
            'value' => '5',
            'min' => '0',
            'max' => '25',
            'description' => esc_html__('Set the horizontal space between the icons in pixels.', 'showcase-shipping-options-icons')
        ]
    );

    $shipping_methods_column1 = [
        'australiapost' => esc_html__('Australia Post', 'showcase-shipping-options-icons'),
        'bpost' => esc_html__('Bpost', 'showcase-shipping-options-icons'),
        'bring' => esc_html__('Bring', 'showcase-shipping-options-icons'),
        'budbee' => esc_html__('Budbee', 'showcase-shipping-options-icons'),
        'canadapost' => esc_html__('Canada Post', 'showcase-shipping-options-icons'),
        'chinapost' => esc_html__('China Post', 'showcase-shipping-options-icons'),
        'colisprive' => esc_html__('Colis Privé', 'showcase-shipping-options-icons'),
        'colissimo' => esc_html__('Colissimo', 'showcase-shipping-options-icons'),
        'correos' => esc_html__('Correos', 'showcase-shipping-options-icons'),
        'correosexpress' => esc_html__('Correos Express', 'showcase-shipping-options-icons'),
        'deutschepost' => esc_html__('Deutsche Post', 'showcase-shipping-options-icons'),
        'dhl' => esc_html__('DHL', 'showcase-shipping-options-icons'),
        'dpd' => esc_html__('DPD', 'showcase-shipping-options-icons'),
        'dsv' => esc_html__('DSV', 'showcase-shipping-options-icons'),
        'fedex' => esc_html__('FedEx', 'showcase-shipping-options-icons'),
        'gls' => esc_html__('GLS', 'showcase-shipping-options-icons'),
        'hellenicpost' => esc_html__('Hellenic Post', 'showcase-shipping-options-icons'),
        'itella' => esc_html__('Itella', 'showcase-shipping-options-icons'),
        'japanpost' => esc_html__('Japan Post', 'showcase-shipping-options-icons'),
        'laposte' => esc_html__('La Poste', 'showcase-shipping-options-icons'),
        'mondialrelay' => esc_html__('Mondial Relay', 'showcase-shipping-options-icons'),
        'nacex' => esc_html__('Nacex', 'showcase-shipping-options-icons'),
        'osterreichischepost' => esc_html__('Österreichische Post', 'showcase-shipping-options-icons'),
        'posteitaliane' => esc_html__('Poste Italiane', 'showcase-shipping-options-icons'),
        'postnl' => esc_html__('PostNL', 'showcase-shipping-options-icons'),
        'postnord' => esc_html__('PostNord', 'showcase-shipping-options-icons'),
        'postnordsverige' => esc_html__('PostNord Sverige', 'showcase-shipping-options-icons'),
        'royalmail' => esc_html__('Royal Mail', 'showcase-shipping-options-icons'),
        'seur' => esc_html__('Seur', 'showcase-shipping-options-icons'),
        'singaporepost' => esc_html__('Singapore Post', 'showcase-shipping-options-icons'),
        'tnt' => esc_html__('TNT', 'showcase-shipping-options-icons'),
        'ups' => esc_html__('UPS', 'showcase-shipping-options-icons'),
        'usps' => esc_html__('USPS', 'showcase-shipping-options-icons'),
        'yodel' => esc_html__('Yodel', 'showcase-shipping-options-icons'),
    ];

    add_settings_section(
        'ssoi_shipping_icons_plugin_section_column1',
        esc_html__('Rectangle Icons', 'showcase-shipping-options-icons'),
        'ssoi_shipping_icons_plugin_section_column1_cb',
        'ssoi-shipping-icons-plugin'
    );

    foreach ($shipping_methods_column1 as $id => $label) {
        add_settings_field(
            "ssoi_shipping_icons_{$id}_enabled",
            $label,
            'ssoi_shipping_icons_plugin_field_cb',
            'ssoi-shipping-icons-plugin',
            'ssoi_shipping_icons_plugin_section_column1',
            [
                'label_for' => "ssoi_shipping_icons_{$id}_enabled",
                'type' => 'checkbox',
                'name' => "ssoi_shipping_icons_{$id}_enabled",
                'value' => '1',
                'description' => esc_html($label),
            ]
        );
    }

    $shipping_methods_column2 = [
        'anpost2' => esc_html__('An Post', 'showcase-shipping-options-icons'),
        'asendia2' => esc_html__('Asendia', 'showcase-shipping-options-icons'),
        'australiapost2' => esc_html__('Australia Post', 'showcase-shipping-options-icons'),
        'austrianpost2' => esc_html__('Austrian Post', 'showcase-shipping-options-icons'),
        'bgpost2' => esc_html__('BG Post', 'showcase-shipping-options-icons'),
        'bpost2' => esc_html__('Bpost', 'showcase-shipping-options-icons'),
        'brazilcorreios2' => esc_html__('Brazil Correios', 'showcase-shipping-options-icons'),
        'bring2' => esc_html__('Bring', 'showcase-shipping-options-icons'),
        'canadapost2' => esc_html__('Canada Post', 'showcase-shipping-options-icons'),
        'chinapost2' => esc_html__('China Post', 'showcase-shipping-options-icons'),
        'colisprive2' => esc_html__('Colis Privé', 'showcase-shipping-options-icons'),
        'correos2' => esc_html__('Correos', 'showcase-shipping-options-icons'),
        'correosexpress2' => esc_html__('Correos Express', 'showcase-shipping-options-icons'),
        'cypruspost2' => esc_html__('Cyprus Post', 'showcase-shipping-options-icons'),
        'deutschepost2' => esc_html__('Deutsche Post', 'showcase-shipping-options-icons'),
        'dhl2' => esc_html__('DHL', 'showcase-shipping-options-icons'),
        'dpd2' => esc_html__('DPD', 'showcase-shipping-options-icons'),
        'dsv2' => esc_html__('DSV', 'showcase-shipping-options-icons'),
        'estoniapost2' => esc_html__('Estonia Post', 'showcase-shipping-options-icons'),
        'fedex2' => esc_html__('FedEx', 'showcase-shipping-options-icons'),
        'gls2' => esc_html__('GLS', 'showcase-shipping-options-icons'),
        'greekpost2' => esc_html__('Greek Post', 'showcase-shipping-options-icons'),
        'hermes2' => esc_html__('Hermes', 'showcase-shipping-options-icons'),
        'hkpost2' => esc_html__('HK Post', 'showcase-shipping-options-icons'),
        'hongkongpost2' => esc_html__('Hong Kong Post', 'showcase-shipping-options-icons'),
        'india2' => esc_html__('India Post', 'showcase-shipping-options-icons'),
        'itella2' => esc_html__('Itella', 'showcase-shipping-options-icons'),
        'japanpost2' => esc_html__('Japan Post', 'showcase-shipping-options-icons'),
        'laposte2' => esc_html__('La Poste', 'showcase-shipping-options-icons'),
        'lettermail2' => esc_html__('Letter Mail', 'showcase-shipping-options-icons'),
        'lithuanianpost2' => esc_html__('Lithuanian Post', 'showcase-shipping-options-icons'),
        'luxembourgpost2' => esc_html__('Luxembourg Post', 'showcase-shipping-options-icons'),
        'malaysiapost2' => esc_html__('Malaysia Post', 'showcase-shipping-options-icons'),
        'malta2' => esc_html__('Malta', 'showcase-shipping-options-icons'),
        'newzealandpost2' => esc_html__('New Zealand Post', 'showcase-shipping-options-icons'),
        'nigeriapost2' => esc_html__('Nigeria Post', 'showcase-shipping-options-icons'),
        'norwegianpost2' => esc_html__('Norwegian Post', 'showcase-shipping-options-icons'),
        'p4d2' => esc_html__('P4D', 'showcase-shipping-options-icons'),
        'parcelforce2' => esc_html__('ParcelForce', 'showcase-shipping-options-icons'),
        'posteitaliane2' => esc_html__('Poste Italiane', 'showcase-shipping-options-icons'),
        'posten2' => esc_html__('Posten', 'showcase-shipping-options-icons'),
        'postnl2' => esc_html__('PostNL', 'showcase-shipping-options-icons'),
        'postnord2' => esc_html__('PostNord', 'showcase-shipping-options-icons'),
        'portugalpost2' => esc_html__('Portugal Post', 'showcase-shipping-options-icons'),
        'purolator2' => esc_html__('Purolator', 'showcase-shipping-options-icons'),
        'royalmail2' => esc_html__('Royal Mail', 'showcase-shipping-options-icons'),
        'russianpost2' => esc_html__('Russian Post', 'showcase-shipping-options-icons'),
        'saudiarabia2' => esc_html__('Saudi Arabia', 'showcase-shipping-options-icons'),
        'seur2' => esc_html__('Seur', 'showcase-shipping-options-icons'),
        'singaporepost2' => esc_html__('Singapore Post', 'showcase-shipping-options-icons'),
        'skynet2' => esc_html__('SkyNet', 'showcase-shipping-options-icons'),
        'swedenpost2' => esc_html__('Sweden Post', 'showcase-shipping-options-icons'),
        'swisspost2' => esc_html__('Swiss Post', 'showcase-shipping-options-icons'),
        'taqbin2' => esc_html__('Taqbin', 'showcase-shipping-options-icons'),
        'tnt2' => esc_html__('TNT', 'showcase-shipping-options-icons'),
        'ukmail2' => esc_html__('UK Mail', 'showcase-shipping-options-icons'),
        'ups2' => esc_html__('UPS', 'showcase-shipping-options-icons'),
        'usps2' => esc_html__('USPS', 'showcase-shipping-options-icons'),
        'venezuela2' => esc_html__('Venezuela', 'showcase-shipping-options-icons'),
        'yodel2' => esc_html__('Yodel', 'showcase-shipping-options-icons'),
    ];

    add_settings_section(
        'ssoi_shipping_icons_plugin_section_column2',
        esc_html__('Round Icons', 'showcase-shipping-options-icons'),
        'ssoi_shipping_icons_plugin_section_column2_cb',
        'ssoi-shipping-icons-plugin'
    );

    foreach ($shipping_methods_column2 as $id => $label) {
        add_settings_field(
            "ssoi_shipping_icons_{$id}_enabled",
            $label,
            'ssoi_shipping_icons_plugin_field_cb',
            'ssoi-shipping-icons-plugin',
            'ssoi_shipping_icons_plugin_section_column2',
            [
                'label_for' => "ssoi_shipping_icons_{$id}_enabled",
                'type' => 'checkbox',
                'name' => "ssoi_shipping_icons_{$id}_enabled",
                'value' => '1',
                'description' => esc_html($label),
            ]
        );
    }
}
add_action('admin_init', 'ssoi_shipping_icons_plugin_settings_init');

// Callback for the general settings section
function ssoi_shipping_icons_plugin_section_cb() {
    echo '<p>' . esc_html__('Configure the general settings for the Shipping Icons plugin.', 'showcase-shipping-options-icons') . '</p>';
}

// Callback for the icon size settings section
function ssoi_shipping_icons_plugin_icon_size_section_cb() {
    echo '<p>' . esc_html__('Configure the size and spacing of the icons.', 'showcase-shipping-options-icons') . '</p>';
}

// Callback for the Rectangle Icons section
function ssoi_shipping_icons_plugin_section_column1_cb() {
    echo '<p>' . esc_html__('Select the Rectangle icons to display.', 'showcase-shipping-options-icons') . '</p>';
}

// Callback for the Round Icons section
function ssoi_shipping_icons_plugin_section_column2_cb() {
    echo '<p>' . esc_html__('Select the Round icons to display.', 'showcase-shipping-options-icons') . '</p>';
}

// Callback for rendering fields
function ssoi_shipping_icons_plugin_field_cb($args) {
    $options = get_option('ssoi_shipping_icons_plugin_settings');
    $name = esc_attr($args['name']);
    $value = isset($options[$name]) ? esc_attr($options[$name]) : ''; // Check if the option is set
    $type = esc_attr($args['type']);
    $label = esc_attr($args['label_for']);
    $description = esc_html($args['description']); // Use esc_html for descriptions

    if ($type === 'checkbox') {
        ?>
        <input type="checkbox" id="<?php echo $label; ?>" name="ssoi_shipping_icons_plugin_settings[<?php echo $name; ?>]" value="1" <?php checked($value, '1'); ?>>
        <?php echo $description; ?>
        <?php
    } elseif ($type === 'number') {
        $min = esc_attr($args['min']);
        $max = esc_attr($args['max']);
        $default_value = esc_attr($args['value']); // Use a default value from args if set
        ?>
        <input type="number" id="<?php echo $label; ?>" name="ssoi_shipping_icons_plugin_settings[<?php echo $name; ?>]" value="<?php echo $value ? $value : $default_value; ?>" min="<?php echo $min; ?>" max="<?php echo $max; ?>">
        <p class="description"><?php echo $description; ?></p>
        <?php
    }
}

// Enqueue custom styles for the settings page
function ssoi_shipping_icons_plugin_admin_styles($hook) {
    if ($hook !== 'toplevel_page_ssoi-shipping-icons-plugin') {
        return;
    }
    ?>
    <style>
        .form-table th {
            width: 25%;
        }
        .form-table td {
            width: 75%;
        }
    </style>
    <?php
}
add_action('admin_head', 'ssoi_shipping_icons_plugin_admin_styles');