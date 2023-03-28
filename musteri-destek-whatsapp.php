<?php
/**
 * Plugin Name: Müşteri Destek
 * Description: WhatsApp iletişim ikonu ve özelleştirme özelliklerine sahip WordPress eklentisi.
 * Version: 1.6
 * Author: 212 MEDYA | Dijital Pazarlama Ajansı
 * Author URI: https://212medya.com.tr
 * Text Domain: musteri-destek-whatsapp
 * Requires at least: 6.0
 * Tested up to: 6.1.1
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Admin panelinde eklenti ayarları sayfasını oluştur
function wp_whatsapp_contact_menu() {
    add_options_page(
        'WP WhatsApp Contact',
        'WP WhatsApp Contact',
        'manage_options',
        'wp-whatsapp-contact',
        'wp_whatsapp_contact_settings_page'
    );
}
add_action('admin_menu', 'wp_whatsapp_contact_menu');

// Eklenti ayarları sayfasının içeriğini oluştur
function wp_whatsapp_contact_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('WP WhatsApp Contact Settings', 'wp-whatsapp-contact'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wp_whatsapp_contact_options_group');
            do_settings_sections('wp_whatsapp_contact_options');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Eklenti ayarlarını kayıt et
function wp_whatsapp_contact_register_settings() {
    register_setting('wp_whatsapp_contact_options_group', 'wp_whatsapp_contact_options');

    add_settings_section(
        'wp_whatsapp_contact_section',
        __('General Settings', 'wp-whatsapp-contact'),
        null,
        'wp_whatsapp_contact_options'
    );

    add_settings_field(
        'whatsapp_number',
        __('WhatsApp Number', 'wp-whatsapp-contact'),
        'wp_whatsapp_contact_whatsapp_number_callback',
        'wp_whatsapp_contact_options',
        'wp_whatsapp_contact_section'
    );

    add_settings_field(
        'position',
        __('Icon Position', 'wp-whatsapp-contact'),
        'wp_whatsapp_contact_position_callback',
        'wp_whatsapp_contact_options',
        'wp_whatsapp_contact_section'
    );

    add_settings_field(
        'icon',
        __('Icon URL', 'wp-whatsapp-contact'),
        'wp_whatsapp_contact_icon_callback',
        'wp_whatsapp_contact_options',
        'wp_whatsapp_contact_section'
    );
}
add_action('admin_init', 'wp_whatsapp_contact_register_settings');

// Ayarlar için geri çağırma işlevlerini oluştur
function wp_whatsapp_contact_whatsapp_number_callback() {
    $options = get_option('wp_whatsapp_contact_options');
    echo '<input type="text" name="wp_whatsapp_contact_options[whatsapp_number]" value="' . esc_attr($options['whatsapp_number'] ?? '908508850955') . '" />';
}

function wp_whatsapp_contact_position_callback() {
    $options = get_option('wp_whatsapp_contact_options');
    $position = $options['position'] ?? 'bottom-right';
    ?>
    <select name="wp_whatsapp_contact_options[position]">
        <option value="bottom-right" <?php selected($position, 'bottom-right'); ?>><?php _e('Bottom Right', 'wp-whatsapp-contact'); ?></option>
        <option value="bottom-left" <?php selected($position, 'bottom-left'); ?>><?php _e('Bottom Left', 'wp-whatsapp-contact'); ?></option>
        <option value="middle-right" <?php selected($position, 'middle-right'); ?>><?php _e('Middle Right', 'wp-whatsapp-contact'); ?></option>
        <option value="middle-left" <?php selected($position, 'middle-left'); ?>><?php _e('Middle Left', 'wp-whatsapp-contact'); ?></option>
    </select>
    <?php
}

function wp_whatsapp_contact_icon_callback() {
    $options = get_option('wp_whatsapp_contact_options');
    echo '<input type="text" name="wp_whatsapp_contact_options[icon]" value="' . esc_attr($options['icon'] ?? 'https://img.icons8.com/color/48/null/whatsapp--v1.png') . '" />';
}

// WhatsApp butonunu siteye ekleyin
function wp_whatsapp_contact_add_button() {
    $options = get_option('wp_whatsapp_contact_options', array());

    $whatsapp_number = esc_attr($options['whatsapp_number'] ?? '908508850955');
    $position = esc_attr($options['position'] ?? 'bottom-right');
    $icon = esc_attr($options['icon'] ?? 'https://img.icons8.com/color/48/null/whatsapp--v1.png');

    ?>
    <style>
        #wp-whatsapp-contact {
            position: fixed;
            z-index: 1000;
        }
        #wp-whatsapp-contact img {
            width: 48px;
        }
        <?php if ($position === 'bottom-right') : ?>
        #wp-whatsapp-contact {
            bottom: 20px;
            right: 20px;
        }
        <?php elseif ($position === 'bottom-left') : ?>
        #wp-whatsapp-contact {
            bottom: 20px;
            left: 20px;
        }
        <?php elseif ($position === 'middle-right') : ?>
        #wp-whatsapp-contact {
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
        }
        <?php elseif ($position === 'middle-left') : ?>
        #wp-whatsapp-contact {
            top: 50%;
            left: 20px;
            transform: translateY(-50%);
        }
        <?php endif; ?>
    </style>
    <a href="https://wa.me/<?php echo $whatsapp_number; ?>" id="wp-whatsapp-contact" target="_blank">
        <img src="<?php echo $icon; ?>" alt="WhatsApp">
    </a>
    <?php
}
add_action('wp_footer', 'wp_whatsapp_contact_add_button');

// Eklenti için dil dosyalarını yükle
function wp_whatsapp_contact_load_textdomain() {
    load_plugin_textdomain('wp-whatsapp-contact', false, basename(dirname(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'wp_whatsapp_contact_load_textdomain');

