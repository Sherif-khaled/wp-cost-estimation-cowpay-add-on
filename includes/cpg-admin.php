<?php

if (!defined('ABSPATH'))
    exit;

class CPG_admin
{

    /**
     * The single instance
     * @var    object
     * @access  private
     * @since    1.0.0
     */
    private static $_instance = null;

    /**
     * The main plugin object.
     * @var    object
     * @access  public
     * @since    1.0.0
     */
    public $parent = null;

    /**
     * Prefix for plugin settings.
     * @var     string
     * @access  publicexport
     * Delete
     * @since   1.0.0
     */
    public $base = '';

    /**
     * Available settings for plugin.
     * @var     array
     * @access  public
     * @since   1.0.0
     */
    public $settings = array();

    /**
     * Is WooCommerce activated ?
     * @var     array
     * @access  public
     * @since   1.5.0
     */

    public function __construct() {

        add_action('wp_ajax_nopriv_lfb_addForm', array($this, 'addForm'));
        add_action('wp_ajax_lfb_addForm', array($this, 'addForm'));


    }

    /*
    * Load admin scripts
    */

    function admin_scripts() {
        if (isset($_GET['page']) && strpos($_GET['page'], 'lfb_') === 0) {
            $settings = $this->getSettings();


            wp_register_script($this->parent->_token . '-bootstrap', esc_url($this->parent->assets_url) . 'js/bootstrap.min.js', array('jquery', "jquery-ui-core"), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-bootstrap');
            wp_register_script($this->parent->_token . '-bootstrap-select', esc_url($this->parent->assets_url) . 'js/bootstrap-select.min.js', array($this->parent->_token . '-bootstrap'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-bootstrap-select');
            wp_register_script($this->parent->_token . '-bootstrap-timepicker', esc_url($this->parent->assets_url) . 'js/bootstrap-datetimepicker.min.js', array($this->parent->_token . '-bootstrap'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-bootstrap-timepicker');
            wp_register_script($this->parent->_token . '-datatable', esc_url($this->parent->assets_url) . 'js/jquery.dataTables.min.js', array($this->parent->_token . '-bootstrap'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-datatable');
            wp_register_script($this->parent->_token . '-bootstrap-datatable', esc_url($this->parent->assets_url) . 'js/dataTables.bootstrap.min.js', array($this->parent->_token . '-datatable'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-bootstrap-datatable');
            wp_register_script($this->parent->_token . '-bootstrap-switch', esc_url($this->parent->assets_url) . 'js/bootstrap-switch.js', array('jquery', "jquery-ui-core"), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-bootstrap-switch');
            wp_register_script($this->parent->_token . '-colpick', esc_url($this->parent->assets_url) . 'js/colpick.js', array('jquery'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-colpick');
            wp_register_script($this->parent->_token . '-editor', esc_url($this->parent->assets_url) . 'js/summernote.min.js', array('jquery', "jquery-ui-core", $this->parent->_token . '-bootstrap'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-editor');
            wp_register_script($this->parent->_token . '-moment', esc_url($this->parent->assets_url) . 'js/moment.min.js', array(), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-moment');
            wp_register_script($this->parent->_token . '-mask', esc_url($this->parent->assets_url) . 'js/jquery.mask.min.js', array(), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-mask');
            wp_register_script($this->parent->_token . '-fullcalendar', esc_url($this->parent->assets_url) . 'js/fullcalendar.min.js', array($this->parent->_token . '-bootstrap', $this->parent->_token . '-moment'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-fullcalendar');
            wp_register_script($this->parent->_token . '-nicescroll', esc_url($this->parent->assets_url) . 'js/jquery.nicescroll.min.js', 'jquery', $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-nicescroll');
            wp_register_script($this->parent->_token . '-googleCharts', 'https://www.gstatic.com/charts/loader.js', array('jquery'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-googleCharts');
            wp_register_script($this->parent->_token . '-codemirror', esc_url($this->parent->assets_url) . 'js/codemirror.min.js', array(), $this->parent->_version, true);
            wp_enqueue_script($this->parent->_token . '-codemirror');
            wp_register_script($this->parent->_token . '-codemirrorJS', esc_url($this->parent->assets_url) . 'js/codemirror-javascript.min.js', array(), $this->parent->_version, true);
            wp_enqueue_script($this->parent->_token . '-codemirrorJS');
            wp_register_script($this->parent->_token . '-codemirrorCSS', esc_url($this->parent->assets_url) . 'js/codemirror-css.min.js', array(), $this->parent->_version, true);
            wp_enqueue_script($this->parent->_token . '-codemirrorCSS');



            $locale = get_locale();
            if (strpos($locale, '_') > -1) {
                $locale = substr($locale, 0, strpos($locale, '_'));
            }
            if (file_exists($this->parent->assets_dir . '/js/calendarLocale/' . $locale . '.js')) {
                wp_register_script($this->parent->_token . '-calendarLocale', esc_url($this->parent->assets_url) . 'js/calendarLocale/' . $locale . '.js', array('jquery'), $this->parent->_version);
                wp_enqueue_script($this->parent->_token . '-calendarLocale');
            } else {
                $locale = 'en';
            }
            wp_register_script($this->parent->_token . '-datetimepicker', esc_url($this->parent->assets_url) . 'js/bootstrap-datetimepicker.min.js', array('jquery'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-datetimepicker');

            if (file_exists($this->parent->assets_dir . '/js/datepickerLocale/bootstrap-datetimepicker.' . $locale . '.js')) {
                wp_register_script($this->parent->_token . '-datepickerLocale', esc_url($this->parent->assets_url) . 'js/datepickerLocale/bootstrap-datetimepicker.' . $locale . '.js', array('jquery'), $this->parent->_version);
                wp_enqueue_script($this->parent->_token . '-datepickerLocale');
            }

            wp_register_script($this->parent->_token . '-lfb-designer', esc_url($this->parent->assets_url) . 'js/lfb_formDesigner.min.js', array('jquery', "jquery-ui-slider", "jquery-ui-resizable"), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-lfb-designer');

            wp_register_script($this->parent->_token . '-lfb-admin', esc_url($this->parent->assets_url) . 'js/lfb_admin.min.js', array("jquery-ui-autocomplete", "jquery-ui-draggable", "jquery-ui-droppable", "jquery-ui-resizable", "jquery-ui-sortable", "jquery-ui-datepicker", "jquery-ui-slider", $this->parent->_token . '-bootstrap-switch', $this->parent->_token . '-editor'), $this->parent->_version, true);
            wp_enqueue_script($this->parent->_token . '-lfb-admin');

            $lscVerified = 0;
            if (strlen($settings->purchaseCode) > 8 || get_option('lfb_themeMode')) {
                $lscVerified = 1;
            }
            $designForm = 0;
            if (isset($_GET['lfb_formDesign'])) {
                $designForm = sanitize_text_field($_GET['lfb_formDesign']);
            }
            $showMeridian = 0;
            if (strpos(strtolower(get_option('time_format')), 'g') > -1) {
                $showMeridian = 1;
            }
            $js_data[] = array(
                'assetsUrl' => esc_url($this->parent->assets_url),
                'websiteUrl' => esc_url(get_site_url()),
                'exportUrl' => esc_url(trailingslashit(plugins_url('/export/', $this->parent->file))),
                'designForm' => $designForm,
                'dateFormat' => stripslashes($this->parent->dateFormatToDatePickerFormat(get_option('date_format'))),
                'timeFormat' => $this->parent->timeFormatToDatePickerFormat(get_option('time_format')),
                'timeFormatCal' => $this->parent->timeFormatToCalendarFormat(get_option('time_format')),
                'timeFormatMoment' => $this->parent->timeFormatToMomentFormat(get_option('time_format')),
                'dateMeridian' => $showMeridian,
                'lscV' => $lscVerified,
                'locale' => $locale,
                'texts' => array(
                    'tip_flagStep' => __('Click the flag icon to set this step at first step', 'lfb'),
                    'tip_linkStep' => __('Start a link to another step', 'lfb'),
                    'tip_delStep' => __('Remove this step', 'lfb'),
                    'tip_duplicateStep' => __('Duplicate this step', 'lfb'),
                    'tip_editStep' => __('Edit this step', 'lfb'),
                    'tip_editLink' => __('Edit a link', 'lfb'),
                    'isSelected' => __('Is selected', 'lfb'),
                    'isUnselected' => __('Is unselected', 'lfb'),
                    'isPriceSuperior' => __('Is price superior to', 'lfb'),
                    'isPriceInferior' => __('Is price inferior to', 'lfb'),
                    'isPriceEqual' => __('Is price equal to', 'lfb'),
                    'isntPriceEqual' => __("Is price different than", 'lfb'),
                    'isSuperior' => __('Is superior to', 'lfb'),
                    'isInferior' => __('Is inferior to', 'lfb'),
                    'isEqual' => __('Is equal to', 'lfb'),
                    'isntEqual' => __("Is different than", 'lfb'),
                    'isQuantitySuperior' => __('Quantity selected is superior to', 'lfb'),
                    'isQuantityInferior' => __('Quantity selected is inferior to', 'lfb'),
                    'isQuantityEqual' => __('Quantity is equal to', 'lfb'),
                    'isntQuantityEqual' => __("Quantity is different than", 'lfb'),
                    'totalPrice' => __('Total price', 'lfb'),
                    'totalQuantity' => __('Total quantity', 'lfb'),
                    'isFilled' => __('Is Filled', 'lfb'),
                    'errorExport' => __('An error occurred during the exportation. Please verify that your server supports the ZipArchive php library ', 'lfb'),
                    'errorImport' => __('An error occurred during the importation. Please verify that your server supports the ZipArchive php library ', 'lfb'),
                    'Yes' => __('Yes', 'lfb'),
                    'No' => __('No', 'lfb'),
                    'days' => __('Days', 'lfb'),
                    'hours' => __('Hours', 'lfb'),
                    'weeks' => __('Weeks', 'lfb'),
                    'months' => __('Months', 'lfb'),
                    'years' => __('Years', 'lfb'),
                    'amountOrders' => __('Amount of orders', 'lfb'),
                    'oneTimePayment' => __('One time payments or estimates', 'lfb'),
                    'subscriptions' => __('Subscriptions', 'lfb'),
                    'lastStep' => __('Last Step', 'lfb'),
                    'Nothing' => __('Nothing', 'lfb'),
                    'selectAnElement' => __('Select an element of your website', 'tld'),
                    'stopSelection' => __('Stop the selection', 'tld'),
                    'stylesApplied' => __('The styles are applied', 'tld'),
                    'modifsSaved' => __('Styles are now applied to the website', 'tld'),
                    'My step' => __('My step', 'lfb'),
                    'value' => __('Value', 'lfb'),
                    'quantity' => __('Quantity', 'lfb'),
                    'price' => __('Price', 'lfb'),
                    'myNewLayer' => __('My new Layer', 'lfb'),
                    'edit' => __('Edit', 'lfb'),
                    'editConditions' => __('Edit the visibility conditions', 'lfb'),
                    'duplicate' => __('Duplicate', 'lfb'),
                    'remove' => __('Remove', 'lfb'),
                    'display' => __('Display', 'lfb'),
                    'search' => __('Search', 'lfb'),
                    'showingPage' => sprintf(__('Showing page %1$s of %2$s', 'lfb'), '_PAGE_', '_PAGES_'),
                    'filteredFrom' => sprintf(__('- filtered from %1$s documents', 'lfb'), '_MAX_'),
                    'noRecords' => __('No documents to display', 'lfb'),
                    'minSizeTip' => __('Fill this field to limit the minimum number of characters', 'lfb'),
                    'maxSizeTip' => __('Fill this field to limit the maximum number of characters', 'lfb'),
                    'newEventContent' => __('An event will take place on [date], at [time] !', 'lfb'),
                    'newEventSubject' => __('New event !', 'lfb'),
                    'noReminders' => __('There is no reminders yet', 'lfb'),
                    'noCategories' => __('There is no categories yet', 'lfb'),
                    'newEvent' => __('New event', 'lfb'),
                    'userEmailTip' => __('If true, the user will receive a confirmation email', 'lfb'),
                    'userEmailTipDisabled' => __('You need to disable the GDPR option to be able to disable this option', 'lfb'),
                    'Currency' => __('Currency', 'lfb'),
                    'Integer' => __('Integer', 'lfb'),
                    'Float' => __('Float', 'lfb'),
                    'My Variable' => __('My Variable', 'lfb'),
                    'View this order' => __('View this order', 'lfb'),
                    'Download the order' => __('Download the order', 'lfb'),
                    'Delete this order' => __('Delete this order', 'lfb'),
                    'Customer information' => __('Customer information', 'lfb'),
                    'Total price of the form' => __('Total price of the form', 'lfb'),
                    'Total selected quantity in the form' => __('Total selected quantity in the form', 'lfb'),
                    'Price of the item [item]'=> __('Price of the item [item]', 'lfb'),
                    'Quantity of the item [item]'=> __('Quantity of the item [item]', 'lfb'),
                    'Value of the item [item]'=> __('Value of the item [item]', 'lfb'),
                    'Title of the item [item]'=> __('Title of the item [item]', 'lfb'),
                    'Total quantity of the step [step]'=> __('Total quantity of the step [step]', 'lfb'),
                    'Total price of the step [step]'=> __('Total price of the step [step]', 'lfb'),
                    'Title of the step [step]'=>__('Title of the step [step]', 'lfb'),
                    'Variable' => __('Variable', 'lfb')
                )
            );
            wp_localize_script($this->parent->_token . '-lfb-admin', 'lfb_data', $js_data);
        }
    }

    public function addForm(){
        if (current_user_can('manage_options')) {
            global $wpdb;

            $formID = $wpdb->insert_id;


            $table_name = $wpdb->prefix . "mycred_user_activities";


            $_data = array(
                'user_id' => $formID,
                'hook_name' => 'insert form',
                'activity_id' => 55,
                'score' => 55,
                'created_at' => date("Y-m-d H:i:s"));

            $format = array('%d', '%s', '%d', '%d', '%s');

            $wpdb->insert($table_name, $_data, $format);
            die();

        }
    }

}