<?php 

namespace EmailKit\Promotional\Onboard\Classes;

defined( 'ABSPATH' ) || exit;

class Utils{

    public static $instance = null;
    private static $key = 'emailkit_onboard_options';

    public static function get_dir(){
        return EMAILKIT_DIR . 'Promotional/Onboard/';
    }

    public static function get_url(){
        return EMAILKIT_URL . 'Promotional/Onboard/';
    }

    public function get_option($key, $default = ''){
        $data_all = get_option(self::$key);
        return (isset($data_all[$key]) && $data_all[$key] != '') ? $data_all[$key] : $default;
    }

    public function save_option($key, $value = ''){
        $data_all = get_option(self::$key);
        $data_all[$key] = $value;
        update_option(self::$key, $data_all);
    }

    public function get_settings($key, $default = ''){
        $data_all = $this->get_option('settings', []);
        return (isset($data_all[$key]) && $data_all[$key] != '') ? $data_all[$key] : $default;
    }

    public function save_settings($new_data = ''){
        $data_old = $this->get_option('settings', []);
        $data = array_merge($data_old, $new_data);
        $this->save_option('settings', $data);
    }

    /*
        -> this method used to check weather the widget active/deactive
        -> this method takes two paramitter 1. widget name 2. Active/deactive hook
     */ 
    public function is_widget_active_class( $widget_name, $pro_active ){
        if($pro_active){
            return 'label-'.esc_attr($widget_name).' attr-panel-heading';
        }else{
            return 'label-'.esc_attr($widget_name).' attr-panel-heading pro-disabled';
        }
    }

    public function input($input_options){
        $defaults = [
            'type' => null,
            'name' => '',
            'value' => '',
            'class' => '',
            'label' => '',
            'info' => '',
            'disabled' => '',
            'options' => [],
        ];
        $input_options = array_merge($defaults, $input_options);

        if(file_exists(self::get_dir() . 'controls/settings/' . $input_options['type'] . '.php')){
            extract($input_options);
            include self::get_dir() . 'controls/settings/' . $input_options['type'] . '.php';
        }
    }

    public static function strify($str){
        return strtolower(preg_replace("/[^A-Za-z0-9]/", "__", $str));
    }

    public static function instance() {
        if ( is_null( self::$instance ) ) {

            // Fire the class instance
            self::$instance = new self();
        }

        return self::$instance;
    }
}