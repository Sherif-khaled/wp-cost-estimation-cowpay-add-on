<?php
/*
 * Plugin Name: WP Cost Estimation CowPay Add-on
 * Version: 1.1
 *
 * Plugin URI: https://github.com/Sherif-khaled/wp-cost-estimation-cowpay-add-on
 * Description: This plugin allows you to use cowpay gateway with wp cost estimation plugin
 * Author: Sherif Khaled
 * Author URI: https://github.com/Sherif-khaled
 *
 * @package WP Cost Estimation & Payment Forms Builder
 * @author Sherif Khaled
 * @since 1.0.0
 * Text Domain: cpg
 */

if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');}

add_action('init','cpg_plugins_required');

define('CPG_PLUGIN_SLUG', 'wp-cost-estimation-cowpay-add-on/cowpay-add-on.php');
define('CPG_PLUGIN_NAME', 'WP Cost Estimation CowPay Add-on');
define('CPG_PATH', plugin_dir_path(__FILE__));
define('CPG_EDITION', CPG_PLUGIN_NAME);

 $cpg_plugins_required = [
        'name' => 'WP Cost Estimation CowPay Add-on',
        'path' => 'WP_Estimation_Form/estimation-form.php',
        'url'  => 'http://codecanyon.net/item/wp-cost-estimation-payment-forms-builder/7818230'
    ];


function cpg_plugins_required(){
    global $cpg_plugins_required;
    $active_plugins = get_option( 'active_plugins' );
    if(!in_array( $cpg_plugins_required['path'], apply_filters( 'active_plugins',$active_plugins ) ) && !(function_exists('is_plugin_active_for_network') && is_plugin_active_for_network( $cpg_plugins_required['path']))){
        add_action('admin_notices','cpg_admin_message_notice');
        deactivate_plugins( plugin_basename( __FILE__ ) );
    }

}
function cpg_admin_message_notice(){
    global $cpg_plugins_required;
    $class = 'notice notice-error is-dismissible';
    wp_create_nonce('cpg_message_notice');
    $message = sprintf(__( '%s needs %s plugin to be activated.', 'cpg' ),'<strong>'.CPG_PLUGIN_NAME.'</strong>','<a href="'.$cpg_plugins_required['url'].'">'.$cpg_plugins_required['name'].'</a>');

    printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
}

if(class_exists('LFB_admin')){
    define('CPG_ADMIN_PATH', CPG_PATH . '/includes/');
    define('CPG_Template_PATH', CPG_PATH . '/templates/');
    define('CPG_JS_PATH', plugin_dir_url(__FILE__) . 'assets/js/');
    define('CPG_CSS_PATH', plugin_dir_url(__FILE__) . 'assets/css/');

    $cpg_url_protocol = (is_ssl()) ? 'https' : 'http';
    define('CPG_URL', preg_replace('/^https?:/', "{$cpg_url_protocol}:", plugins_url('/' . CPG_PLUGIN_NAME)));

    require_once('includes/cpg-admin.php');
    $rr = new CPG_admin();

}

function cpg_table_column_exists( $table_name, $column_name ) {

    global $wpdb;

    $column = $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",
        DB_NAME, $table_name, $column_name
    ) );

    if ( ! empty( $column ) ) {
        return true;
    }

    return false;
}
function cpg_update_table(){
    global $wpdb;

    $table_name = $wpdb->prefix . "wpefc_forms";
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {

        if(!cpg_table_column_exists($table_name,'use_cowpay')){
            $sql = "ALTER TABLE " . $table_name . " ADD use_cowpay BOOL DEFAULT 0;";
            $wpdb->query($sql);
        }

        if(!cpg_table_column_exists($table_name,'cowpay_useSandbox')){
            $sql = "ALTER TABLE " . $table_name . " ADD cowpay_useSandbox BOOL DEFAULT 0;";
            $wpdb->query($sql);
        }

        if(!cpg_table_column_exists($table_name,'cowpay_secretKey')){
            $sql = "ALTER TABLE " . $table_name . " ADD cowpay_secretKey VARCHAR(250) NOT NULL;";
            $wpdb->query($sql);
        }

        if(!cpg_table_column_exists($table_name,'cowpay_publishKey')){
            $sql = "ALTER TABLE " . $table_name . " ADD cowpay_publishKey VARCHAR(250) NOT NULL;";
            $wpdb->query($sql);
        }

        if(!cpg_table_column_exists($table_name,'cowpay_currency')){
            $sql = "ALTER TABLE " . $table_name . " ADD cowpay_currency VARCHAR(6) NOT NULL DEFAULT 'INR';";
            $wpdb->query($sql);
        }

        if(!cpg_table_column_exists($table_name,'cowpay_subsFrequency')){
            $sql = "ALTER TABLE " . $table_name . " ADD cowpay_subsFrequency SMALLINT(5) NOT NULL DEFAULT 1;";
            $wpdb->query($sql);
        }

        if(!cpg_table_column_exists($table_name,'cowpay_subsFrequencyType')){
            $sql = "ALTER TABLE " . $table_name . " ADD cowpay_subsFrequencyType VARCHAR(16) NOT NULL DEFAULT 'monthly';";
            $wpdb->query($sql);
        }

        if(!cpg_table_column_exists($table_name,'cowpay_fixedToPay')){
            $sql = "ALTER TABLE " . $table_name . " ADD cowpay_fixedToPay FLOAT DEFAULT 100;";
            $wpdb->query($sql);
        }

        if(!cpg_table_column_exists($table_name,'cowpay_payMode')){
            $sql = "ALTER TABLE " . $table_name . " ADD cowpay_payMode VARCHAR(64) NOT NULL DEFAULT '';";
            $wpdb->query($sql);
        }

        if(!cpg_table_column_exists($table_name,'cowpay_percentToPay')){
            $sql = "ALTER TABLE " . $table_name . " ADD cowpay_percentToPay FLOAT DEFAULT 100;";
            $wpdb->query($sql);
        }

        if(!cpg_table_column_exists($table_name,'txt_btnCowpay')){
            $sql = "ALTER TABLE " . $table_name . " ADD txt_btnCowpay TEXT NOT NULL;";
            $wpdb->query($sql);
        }


    }
}
function cpg_activate(){
    cpg_update_table();
}
function cpg_deactivate(){

}
function cpg_uninstall(){

    global $wpdb;

    $table_name = $wpdb->prefix . "wpefc_forms";
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {

        if (cpg_table_column_exists($table_name, 'use_cowpay')) {
            $sql = "ALTER TABLE " . $table_name .
                " DROP use_cowpay, ".
                " DROP cowpay_useSandbox, ".
                " DROP cowpay_secretKey, ".
                " DROP cowpay_publishKey, ".
                " DROP cowpay_currency, ".
                " DROP cowpay_subsFrequency, ".
                " DROP cowpay_subsFrequencyType, ".
                " DROP cowpay_fixedToPay, ".
                " DROP cowpay_payMode, ".
                " DROP cowpay_percentToPay, ".
                " DROP txt_btnCowpay;";

            $wpdb->query($sql);
        }
    }
}
register_activation_hook(__FILE__, 'cpg_activate');
register_deactivation_hook(__FILE__,'cpg_deactivate');
register_uninstall_hook(__FILE__, 'cpg_uninstall');