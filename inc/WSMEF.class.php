<?php

class WSMEF
{
    // Define config var
    private $config = null;

    // Define required shipping methods must be affected
    private $shipping_methods = array('flat_rate', 'free_shipping');

    /**
     * Constraction of the class
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = new stdClass();
        $this->config->plugin_url = plugin_dir_url(__FILE__);
        $this->config->is_admin = is_admin();
    }

    /**
     * Enable and active the plugin
     *
     * @return void
     */
    public function activeWSMEF()
    {

    }

    /**
     * Deactive and uninstall the plugin
     *
     * @return void
     */
    public function deactiveWSMEF()
    {

    }

    /**
     * Check if woocommerce is enabled
     *
     * @return boolean
     */
    public function checkStatusWC()
    {
        if ($this->_exist_woocommerce()) {
            return true;
        }

        return false;

    }

    /**
     * Add custom field to settings
     *
     * @param string $shipping_method
     * @return void
     */

    public function shipping_instance_form_add_extra_fields($settings)
    {
        $settings['carrier_id'] = [
            'title' => 'Carrier ID',
            'type' => 'text',
            'placeholder' => 'Carrier ID',
            'description' => '',
        ];

        return $settings;
    }

    /**
     * Add fields to shipping method settings in woocommerce settings panel
     *
     * @return void
     */
    public function add_carrierid_id_field()
    {
        // for each supported shipping method
        foreach ($this->shipping_methods as $shipping_method) {
            add_filter('woocommerce_shipping_instance_form_fields_' . $shipping_method, array($this, 'shipping_instance_form_add_extra_fields'));
        }

    }

    /**
     * Add carrier ID to orders are under processing
     *
     * @param string $order_id
     * @param string $old_status
     * @param string $new_status
     *
     * @return void
     */
    public function add_carrier_id_to_processing_order($order_id, $old_status, $new_status)
    {
        if ($new_status == 'processing') {
            
            //Get the order object
            $the_order = wc_get_order($order_id);

            //Get order shiping methods
            $order_shipping_methods = array_values($the_order->get_shipping_methods());

            if (count($order_shipping_methods)) {
                //Get shipping method of the order
                $the_shipping = $order_shipping_methods[0];

                if (in_array($the_shipping->get_method_id(), $this->shipping_methods) === true) {

                    
                    $option_id = 'woocommerce_' . $the_shipping->get_method_id() . '_' . $the_shipping->get_instance_id() . '_settings';

                    //Get all options of shipping method
                    $option_list = get_option($option_id);

                    // Check if this method has carrier_id or not
                    if (isset($option_list['carrier_id'])) {

                        // update order meta with _carrier_id
                        update_post_meta($order_id, '_carrier_id', $option_list['carrier_id']);
                        
                    }

                }
            }

        }
    }

    /**
     * Initial the plugin if it is active
     *
     * @return void
     */
    public function init()
    {
        $this->add_carrierid_id_field();

        // monitor action on order status change
        add_action('woocommerce_order_status_changed', array($this, 'add_carrier_id_to_processing_order'), 10, 3);
    }
}
