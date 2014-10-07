<?php
/**
 * Plugin Name: WP Nebulus Coming Soon
 * Plugin URI: http://nebulussoon.decodigothemes.com
 * Description: Coming Soon plugin using Nebulus Template
 * Version: 1.2.3
 * Author: DeCodigo
 * Author URI: http://decodigothemes.com
 * Contributors: Sonny T.
 */

// Some constants
define('NEBULUS_DIR', plugin_dir_path( __FILE__ ));
define('NEBULUS_ADMIN_URI', plugins_url('admin', __FILE__));
define('NEBULUS_VERSION', '1.2.3');

// Require the admin class
require_once NEBULUS_DIR . '/admin/admin.php';

class WP_Nebulus{

    public function __construct(){
        $plugin = plugin_basename(__FILE__);

        register_activation_hook( __FILE__, array( &$this, 'activation') );
        add_action('plugins_loaded', array( &$this, 'loadSite' ) );
        add_filter("plugin_action_links_$plugin", array( &$this, 'settings_link' ) );
        add_action('wp_ajax_nebulus_mailchimp', array( &$this, 'nebulus_mailchimp' ) );
        add_action('wp_ajax_nopriv_nebulus_mailchimp', array( &$this, 'nebulus_mailchimp' ) );
    }

    /**
     * Settings Panel Link
     */
    public function settings_link( $links ){
        $settings_link = '<a href="'. admin_url( 'options-general.php?page=nebulus_plugin_options') .'">'.__('Settings').'</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * Activation hook
     */
    function activation(){

delete_option('nebulus_general_settings');
delete_option('nebulus_social_settings');
delete_option('nebulus_mailchimp_settings');
delete_option('nebulus_access_settings');

        // Set some defaults if they don't exist
        if( !get_option('nebulus_general_settings')){
            $general = array(
                'status'      => 'on',
                'theme'       => 'default',
                'heading'     => __("We're building a new experience.", "wp_nebulus"),
                'sub_heading' => __("Our awesome site, coming soon.", "wp_nebulus"),
                'content1'    => __("<h3>Retina Ready</h3><p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</p><h3>MailChimp Integration</h3><p>Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. Aenean dignissim pellentesque felis.</p>", "wp_nebulus"),
                'content2'    => __("<h3>Responsive Layout</h3><p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</p><h3>Countdown Timer</h3><p>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.</p>", "wp_nebulus"),
                'content3'    => __("<h3>Optional Column</h3><p><p>This column is visible when the twitter feed is either turned off or Twitter has not been set up properly. Visit the settings page to setup the twitter feed.</p>", "wp_nebulus"),
                'return503'    => 'true'
            );
            add_option('nebulus_general_settings', $general, '', 'no');
        }

        if( !get_option('nebulus_social_settings')){
            $social = array(
                'facebook'    => 'https://www.facebook.com/dec0dig0',
                'twitter'     => 'https://twitter.com/dec0dig0',
                'google'      => 'https://plus.google.com/u/0/106725100237653625676/posts'
            );
            add_option('nebulus_social_settings', $social, '', 'no');
        }

        if( !get_option('nebulus_mailchimp_settings')){
            $social = array(
                'button_text'    => 'Notify Me!',
                'text_below'    => 'Want to get notified the second we launch? Enter your email address above and we’ll send you a note.'
            );
            add_option('nebulus_mailchimp_settings', $social, '', 'no');
        }

        if( !get_option('nebulus_access_settings')){
            $access = array(
                'ip_whitelist'    => '',
                'roles_whitelist' => array( 'administrator' => 'true')
            );
            add_option('nebulus_access_settings', $access, '', 'no');
        }
    }

    /**
     * Loads the Plugin Front end
     */
    public function loadSite(){
        $admin                  = new WP_Nebulus_Admin;
        $options                = array_merge( (array)get_option('nebulus_general_settings'), (array)get_option('nebulus_access_settings'));
        $launchdate             = ( isset( $options['launchdate'] ) && !empty( $options['launchdate'] ) ) ? strtotime( $options['launchdate'] ) : false;
        $today                  = current_time('timestamp');
        $ip                     = $_SERVER['REMOTE_ADDR'];
        $roles_whitelist        = isset($options['roles_whitelist']) ? $options['roles_whitelist'] : false;
        $user_is_whitelisted    = false;

        // If the user's role has been whitelisted, whitelist the user
        if( is_user_logged_in() && $roles_whitelist){
            global $current_user;
            get_currentuserinfo();

            foreach ($current_user->roles as $role) {
                if( isset($roles_whitelist[$role]) )
                    $user_is_whitelisted = true;
            }
        }

        // If the user's IP has been whitelisted, whitelist the user
        if( in_array($ip, explode( ",", $options['ip_whitelist']) ) )
            $user_is_whitelisted = true;

        // If the launch date is before today
        if ( $launchdate && $launchdate < $today)
            return;

        // Current user can manage_options and Preview string is not set
        if ( current_user_can('manage_options') && !isset($_GET['nebulus_admin']) )
            return;

        // if the user is whitelisted and Preview string is not set
        if( $user_is_whitelisted && !isset($_GET['nebulus_admin']) )
            return;

        add_action('wp_ajax_ml_mailchimp', array( &$this, 'mailchimp' ) );
        add_action('wp_ajax_ml_twitter', array( &$this, 'twitter' ) );
        add_action('template_redirect', array( &$this, 'overlay' ) );
    }

    /**
     * Calls the template to be displayed
     */
    public function overlay($template){
        $options = get_option('nebulus_general_settings');

        if ($options['status'] === 'on') {
            // Go home first
            if ( !is_front_page() && ! is_home() ) {
                wp_safe_redirect( get_home_url(), 302 );
            }


            if(isset($options['return503']) && $options['return503'] === 'true'){
                $protocol = $_SERVER["SERVER_PROTOCOL"];
                if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol )
                        $protocol = 'HTTP/1.0';
                header( "$protocol 503 Service Unavailable", true, 503 );
                header( 'Content-Type: text/html; charset=utf-8' );
                header( 'Retry-After: 3600' );
            }

            require_once plugin_dir_path( __FILE__ ) . 'template/template.php';
        }
    }

    /**
     * Subscriber callback for the mailchimp integration
     * @return dies with a JSON response that includes the status
     */
    public function nebulus_mailchimp(){
        // some validation
        $email = is_email($_REQUEST['email']);

        // default to an error
        $output = array('type' => 'error', 'message' => 'Sorry we could not subscribe that email.');

        // continue if we have an email
        if($email){
            $options        = get_option('nebulus_mailchimp_settings');
            $apikey         = isset($options['apikey'] ) && !empty($options['apikey'] ) ? esc_attr( $options['apikey']  ) : false;
            $listid         = isset($options['listid']) && !empty($options['listid']) ? esc_attr( $options['listid'] ) : false;
            $welcome        = isset($options['welcome_email']) && !empty($options['welcome_email']) ? esc_attr( $options['welcome_email'] ) : false;
            $name           = isset( $_REQUEST['name'] ) ? sanitize_text_field( $_POST['name'] ) : null;
            $apiParts       = explode('-', $apikey);
            $datacenter     = array_pop($apiParts);
            $submit_url     = "http://$datacenter.api.mailchimp.com/1.3/?method=listSubscribe";

            $data = array(
                'email_address' => $email,
                'apikey'        => $apikey,
                'id'            => $listid,
                'double_optin'  => false,
                'send_welcome'  => $welcome,
                'email_type'    => 'html',
                'merge_vars'    => array(
                    'EMAIL'     => $name
                )
            );

            $response = wp_remote_post( $submit_url, array( 'body' => json_encode( $data ) ) );

            if ( is_wp_error( $response ) ) {
               $error_string = $response->get_error_message();
               $output['message'] = $error_string;
            }else{
                $data = json_decode( $response['body'] );

                if (isset($data->error)){
                    $output['message'] = $data->error;
                } else {
                    $output =  array('type' => "success", 'message' => "Got it, you've been added to our email list.");
                }
            }

        }else{
            $output['message'] = 'That is not a valid email address';
        }
        header("Content-Type: application/json; charset={" . get_bloginfo('charset') . "}");
        die( json_encode( $output ) );
    }

    /**
     * Get Tweets, uses caching and refreshes every 15 mins
     * @return Object List of tweets
     */
    public function get_tweets(){

        if( !class_exists('TwitterOAuth') ){
            // Uncomment the line below for debugging.
            // delete_transient('wp_nebulus_tweets');

            // check for caching
            if ( false === ( $tweets = unserialize( base64_decode( get_transient( 'wp_nebulus_tweets' ) ) ) ) ) {
                $options = get_option( 'nebulus_social_settings' );

                require_once( 'libs/twitter/twitteroauth.php' );

                $connection = new TwitterOAuth( $options['tweet_consumerkey'], $options['tweet_consumersecret'], $options['tweet_accesstoken'], $options['tweet_accesssecret'] );

                $tweets = $connection->get( 'https://api.twitter.com/1.1/statuses/user_timeline.json?count='.$options['tweet_count'] );

                // If it's not an error message from Twitter, proceed to fix the links
                if( !isset($tweets->errors) ){

                    foreach ( $tweets as $tweet ) {
                        if( $tweet->text ){
                            $parts = explode( ' ', $tweet->text );
                            $tweet_text = '';

                            foreach ( $parts as $part ) {
                                $text = $part;
                                $link = 'https://twitter.com/#!/';

                                // #hashtag fix
                                if ( false !== strrpos( $part, '#' ) ) {
                                    $tag = preg_replace('/[^A-Za-z0-9]/', '', str_replace('#23', '%23', $part));

                                    $text = '<a href="' . $link . 'search/' . $tag . '" target="_blank">' . $part . '</a>';
                                }

                                // @username fix
                                if ( false !== strrpos( $part, '@' ) ) {
                                    $username = preg_replace('/[^A-Za-z0-9]/', '', str_replace('@', '', $part));

                                    $text = '<a href="' . $link . $username . '" target="_blank">' . $text . '</a>';
                                }

                                // http:// link fix
                                if ( false !== strrpos( $part, 'http://' ) ) {
                                    $text = '<a href="' . $text . '" target="_blank">' . $text . '</a>';
                                }

                                $tweet_text .= $text . ' ';
                            }

                            $tweet->text = $tweet_text;
                        }
                    }

                    // set caching for 15 mins
                    set_transient( 'wp_nebulus_tweets', base64_encode( serialize( $tweets ) ), 60 * 15 );
                }else{

                    // Return the error message
                    return $tweets->errors[0]->message;
                }
            }

            return $tweets;
        }
    }

    /**
     * Method to Adjust the color of a given hex color value
     * http://stackoverflow.com/a/11951022
     *
     * @param  string $hex   hex color value to be
     * @param  int    $steps how dark or light
     * @return string        the new lighter or darker hex color value
     */
    public function adjustBrightness($hex, $steps) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Format the hex color string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
        }

        // Get decimal values
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));

        // Adjust number of steps and keep it inside 0 to 255
        $r = max(0,min(255,$r + $steps));
        $g = max(0,min(255,$g + $steps));
        $b = max(0,min(255,$b + $steps));

        $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
        $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
        $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

        return '#'.$r_hex.$g_hex.$b_hex;
    }
}

/**
 * OK GO!
 */
$wpnebulus = new WP_Nebulus();