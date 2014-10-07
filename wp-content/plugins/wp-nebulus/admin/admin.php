<?php
/**
 * @package     WP Nebulus Coming Soon
 * @author      decodigo <decodigo@decodigoinc.com>
 */

class WP_Nebulus_Admin{

    private $general_settings_key       = 'nebulus_general_settings';
    private $social_settings_key        = 'nebulus_social_settings';
    private $mailchimp_settings_key     = 'nebulus_mailchimp_settings';
    private $access_settings_key        = 'nebulus_access_settings';
    private $plugin_options_key         = 'nebulus_plugin_options';
    private $plugin_settings_tabs       = array();

    public function __construct(){
        add_action( 'init', array( &$this, 'load_settings' ) );
        add_action( 'admin_init', array( &$this, 'register_general_settings' ) );
        add_action( 'admin_init', array( &$this, 'register_social_settings' ) );
        add_action( 'admin_init', array( &$this, 'register_mailchimp_settings' ) );
        add_action( 'admin_init', array( &$this, 'register_access_settings' ) );
        add_action( 'admin_menu', array( &$this, 'add_admin_menus' ) );
        add_action('admin_enqueue_scripts', array( &$this, 'scripts') );
    }

    function load_settings() {
        $this->general_settings     = (array) get_option( $this->general_settings_key );
        $this->social_settings      = (array) get_option( $this->social_settings_key );
        $this->mailchimp_settings   = (array) get_option( $this->mailchimp_settings_key );
        $this->access_settings      = (array) get_option( $this->access_settings_key );
    }

    /***********************************
    General Settings
    ***********************************/
    function section_desc($args) {
        switch ($args['id']) {
            case 'section_twitter_feed':
                echo '<p>'. __('The following fields are required for the Twitter Feed to work', 'wp_nebulus') .'</p>'; break;
            case 'section_social':
                echo '<p>'. __('Used for links to social media sites. If you leave one blank it will not show up.', 'wp_nebulus') .'</p>'; break;
            case 'section_mailchimp':
                echo '<p>'. __('Below are the fields required for the mailchimp integration to work. <br>If the fields are blank the user email form will not be shown.', 'wp_nebulus') .'</p>'; break;
            case 'section_access_ip':
                echo '<p>'. __('List the IP addresses below that you would like to grant access to.', 'wp_nebulus') .'</p>'; break;
            default:
                echo '<p>'. __('Please fill in the fields below.', 'wp_nebulus') .'</p>'; break;
        }
    }

    /***********************************
    General Settings
    ***********************************/
    function register_general_settings() {
        $this->plugin_settings_tabs[$this->general_settings_key] = 'General';

        register_setting( $this->general_settings_key, $this->general_settings_key );
        add_settings_section( 'section_general', __('General Plugin Settings', 'wp_nebulus'), array( &$this, 'section_desc' ), $this->general_settings_key );

        $fields = array(
            array( 'id' => 'status', 'type' => 'status', 'section' => 'section_general'),
            array( 'id' => 'launchdate', 'label' => 'Launch Date', 'description' => __('It is important to that you set a timezone. <a href="'. get_admin_url() .'options-general.php#timezone_string">Click here</a> to set it.', 'wp_nebulus'),'section' => 'section_general', 'type' => 'date'),
            array( 'id' => 'hidetimer', 'label' => 'Hide Timer', 'section' => 'section_general', 'type' => 'checkbox'),
            array( 'id' => 'hidefooter', 'label' => 'Hide Footer', 'section' => 'section_general', 'type' => 'checkbox'),
            array( 'id' => 'return503', 'label' => 'Return 503 Header', 'description'=>__('SEO - Check this box to tell Search Engines that you are temporarily in maintenance mode <br>and should come back later to crawl for content.<br><span class="description">( Recomended )</span> ','wp-nebulus'), 'section' => 'section_general', 'type' => 'checkbox'),
            array( 'id' => 'logo', 'type' => 'media', 'section' => 'section_general'),
            array( 'id' => 'footerlogo', 'label' => 'Footer Logo', 'section' => 'section_general', 'type' => 'media'),
            array( 'id' => 'heading', 'type' => 'text', 'section' => 'section_general'),
            array( 'id' => 'sub_heading', 'label' => 'Sub Heading', 'section' => 'section_general', 'type' => 'text'),
            array( 'id' => 'theme', 'label' => 'Theme', 'description' => __('Selecting Blue or Green overrides custom color and image below</p>','wp-nebulus'), 'type' => 'radio', 'options' => array('default', 'green', 'blue'), 'section' => 'section_general'),
            array( 'id' => 'backgroundimage', 'label' => 'Custom Background Image', 'section' => 'section_general', 'type' => 'media', 'with_css_options' => true),
            array( 'id' => 'backgroundcolor', 'label' => 'Background Color', 'section' => 'section_general', 'type' => 'color'),
            array( 'id' => 'textcolor', 'label' => 'Text Color', 'section' => 'section_general', 'type' => 'color'),
            array( 'id' => 'buttoncolor', 'label' => 'Button Color', 'section' => 'section_general', 'type' => 'color'),
            array( 'id' => 'content1', 'label' => 'Content Column 1', 'section' => 'section_general', 'type' => 'editor'),
            array( 'id' => 'content2', 'label' => 'Content Column 2', 'section' => 'section_general', 'type' => 'editor'),
            array( 'id' => 'content3', 'label' => 'Content Column 3', 'description' => __('NOTE! This content will only show if you disable the Twitter feed <a href="?page=nebulus_plugin_options&tab=nebulus_social_settings#input-hidetwitterfeed">here</a>','wp_nebulus'), 'section' => 'section_general', 'type' => 'editor'),
            array( 'id' => 'customcss', 'label' => 'Custom CSS','description' => __('Add any custom styles you would like added to the template.', 'wp_nebulus'), 'section' => 'section_general', 'type' => 'textarea'),
            array( 'id' => 'google_analytics', 'label' => 'Google Analytics','description' => __('Insert your tracking code here.', 'wp_nebulus'), 'section' => 'section_general', 'type' => 'textarea')
        );

        $this->add_fields($fields, $this->general_settings_key);
    }

    /***********************************
    Social Settings
    ***********************************/
    function register_social_settings() {
        $this->plugin_settings_tabs[$this->social_settings_key] = 'Social';

        register_setting( $this->social_settings_key, $this->social_settings_key );
        add_settings_section( 'section_social', __('Social Media Links', 'wp_nebulus'), array( &$this, 'section_desc' ), $this->social_settings_key );
        add_settings_section( 'section_twitter_feed', __('Twitter Feed', 'wp_nebulus'), array( &$this, 'section_desc' ), $this->social_settings_key);

        $fields = array(
            array('id' => 'facebook', 'type' => 'text','section' => 'section_social','placeholder' => 'http://'),
            array('id' => 'twitter', 'type' => 'text','section' => 'section_social','placeholder' => 'http://'),
            array('id' => 'instagram', 'type' => 'text','section' => 'section_social','placeholder' => 'http://'),
            array('id' => 'linkedin', 'type' => 'text','section' => 'section_social','placeholder' => 'http://'),
            array('id' => 'pinterest', 'type' => 'text','section' => 'section_social','placeholder' => 'http://'),
            array('id' => 'google', 'type' => 'text','section' => 'section_social','placeholder' => 'http://'),
            array('id' => 'flickr', 'type' => 'text','section' => 'section_social','placeholder' => 'http://'),
            array('id' => 'tumblr', 'type' => 'text','section' => 'section_social','placeholder' => 'http://'),
            array('id' => 'youtube', 'type' => 'text','section' => 'section_social','placeholder' => 'http://'),
            array('id' => 'foursquare', 'type' => 'text','section' => 'section_social','placeholder' => 'http://'),
            array('id' => 'dribbble', 'type' => 'text','section' => 'section_social','placeholder' => 'http://'),
            array('id' => 'hidetwitterfeed','label' => 'Hide Twitter Feed', 'type' => 'checkbox', 'description' => __('Check this box to hide the Twitter feed.', 'wp_nebulus'), 'section' => 'section_twitter_feed'),
            array('id' => 'tweet_user','label' => 'Twitter Username', 'type' => 'text','section' => 'section_twitter_feed'),
            array('id' => 'tweet_count','label' => 'Tweet Count', 'type' => 'text','section' => 'section_twitter_feed'),
            array('id' => 'tweet_consumerkey','label' => 'Consumer Key', 'type' => 'text','section' => 'section_twitter_feed'),
            array('id' => 'tweet_consumersecret','label' => 'Consumer Secret', 'type' => 'text','section' => 'section_twitter_feed'),
            array('id' => 'tweet_accesstoken','label' => 'Access Token', 'type' => 'text','section' => 'section_twitter_feed'),
            array('id' => 'tweet_accesssecret','label' => 'Access Secret', 'type' => 'text','section' => 'section_twitter_feed')
        );

        $this->add_fields($fields, $this->social_settings_key);
    }

    /***********************************
    Mailchimp
    ***********************************/
    function register_mailchimp_settings(){
        $this->plugin_settings_tabs[$this->mailchimp_settings_key] = 'Mailchimp';

        register_setting( $this->mailchimp_settings_key, $this->mailchimp_settings_key );
        add_settings_section( 'section_mailchimp', __('Mailchimp Plugin Settings', 'wp_nebulus'), array( &$this, 'section_desc' ), $this->mailchimp_settings_key );

        $fields = array(
            array('id' => 'hide_email_form','label'=>'Hide Email Forms', 'type' => 'checkbox','section' => 'section_mailchimp'),
            array('id' => 'apikey','type' => 'text','section' => 'section_mailchimp'),
            array('id' => 'listid','type' => 'text','section' => 'section_mailchimp'),
            array('id' => 'button_text','label' => 'Button Text','type' => 'text','section' => 'section_mailchimp'),
            array('id' => 'text_below','label' => 'Text Below Form','type' => 'text','section' => 'section_mailchimp'),
            array('id' => 'welcome_email','label' => 'Welcome Email','type' => 'checkbox','section' => 'section_mailchimp', 'description' => 'Send an email to the user when they subscribe to your list.')
        );

        $this->add_fields($fields, $this->mailchimp_settings_key);
    }

    /***********************************
    Access
    ***********************************/
    function register_access_settings(){
        $this->plugin_settings_tabs[$this->access_settings_key] = 'Access';

        global $wp_roles;
        $roles = $wp_roles->get_names();

        register_setting( $this->access_settings_key, $this->access_settings_key );
        add_settings_section( 'section_access_ip', __('IP Whitelist', 'wp_nebulus'), array( &$this, 'section_desc' ), $this->access_settings_key );

        $fields = array(
            array('id' => 'ip_whitelist','label'=>'IP Whitelist', 'description'=>__('Separate multiple IP addresses with commas. For example<code>127.0.0.1, 127.0.0.2</code><br>Your IP is<code>'. $_SERVER['REMOTE_ADDR'].'</code>', 'wp_nebulus'), 'type' => 'textarea','section' => 'section_access_ip'),
            array('id' => 'roles_whitelist','label'=>'Allowed Roles', 'type' => 'checkbox', 'options'=> $roles ,'section' => 'section_access_ip')
        );

        $this->add_fields($fields, $this->access_settings_key);
    }

    /***********************************
    Creates the Settings Fields
    ***********************************/
    /**
     * Adds the settings fields to the corresponding section
     * @param array $fields
     * @param string $page what page to display the field in
     */
    function add_fields($fields, $page){
        foreach ($fields as $field) {
            $args = array(
                'id' => $field['id'],
                'type' => $field['type'],
                'label' => isset($field['label']) ? $field['label'] : ucwords($field['id']),
                'page' => $page
            );

            if( isset( $field['placeholder'] ) )
                $args['placeholder'] = $field['placeholder'];

            if( isset( $field['description'] ) )
                $args['description'] = $field['description'];

            if( isset( $field['options'] ) )
                $args['options'] = $field['options'];

            if( isset( $field['with_css_options'] ) )
                $args['with_css_options'] = $field['with_css_options'];

            add_settings_field( $args['id'], __( $args['label'], 'wp_nebulus' ) , array( &$this, "fields" ), $page, $field['section'], $args );
        }
    }

    /**
     * Generates indivual fields based on the type
     * @param  array $args field informatuon
     */
    function fields($args){
        $settings_callback = str_replace('nebulus_', '', $args['page'] );
        $option_value = isset( $this->{ $settings_callback }[ $args[ 'id' ] ] ) ? $this->{ $settings_callback }[ $args[ 'id' ] ] : false;

        switch ( $args['type'] ) {

            /***********************************
            Sttatus
            ***********************************/
            case 'status':
                ?>
                <label class="toggle android status-toggle" onclick="">
                    <input id="status" type="checkbox" name="<?php echo $args['page'] ?>[<?php echo $args['id'] ?>]" value="on" <?php checked( esc_attr( $option_value ), 'on' ); ?>/>
                    <p>
                        <span><?php _e('On', 'wp_nebulus'); ?></span>
                        <span><?php _e('Off', 'wp_nebulus'); ?></span>
                    </p>
                    <a class="slide-button"></a>
                </label>

                <a href="<?php echo home_url('?nebulus_admin'); ?>" target="_blank" class="preview_url"><?php _e('Preview Site', 'wp_nebulus'); ?></a>
                <?php
                break;

            /***********************************
            Date
            ***********************************/
            case 'date':
                ?>
                <input name="<?php echo $args['page'] ?>[<?php echo $args['id'] ?>]" type="text" id="launch_date" value="<?php echo esc_attr($option_value ); ?>" class="datepicker">
                <?php
                break;

            /***********************************
            Date
            ***********************************/
            case 'radio':
                $opts = $args['options'];
                foreach ($opts as $key => $opt) {
                    ?>
                    <label for="<?php echo $args['id'] ?><?php echo $key; ?>">
                    <input type="radio" id="<?php echo $args['id'] ?><?php echo $key; ?>" name="<?php echo $args['page'] ?>[<?php echo $args['id'] ?>]" value="<?php echo strtolower($opt); ?>" <?php checked( esc_attr( $option_value ), $opt ); ?> >
                    <?php echo ucwords($opt) ?>
                    </label>
                    <?php
                }
                break;

            /***********************************
            Media Upload
            ***********************************/
            case 'media':
                ?>
                <input type="text" id="<?php echo $args['id'] ?>" class="image-url-input regular-text" name="<?php echo $args['page'] ?>[<?php echo $args['id'] ?>]" value="<?php echo esc_url($option_value ); ?>" size="20"/>
                <input id="logo_button" class="custom_upload_image_button button " type="button" value="<?php _e('Choose Image', 'wp_nebulus'); ?>" name="<?php echo $args['id'] ?>_upload_btn"/>
                <input id="clear_button" class="button clear-btn" type="button" value="<?php _e('Clear', 'wp_nebulus'); ?>"/>
                <?php $imageclass = $option_value  == '' ? 'hidden' : '' ; ?>
                <br>
                <img src="<?php echo esc_url($option_value ); ?>" class="custom_preview_image <?php echo $imageclass; ?>" alt="" />
                <?php
                if(isset($args['with_css_options'])):

                    ?>
                    <div class="image-css-options <?php echo $imageclass; ?>" >
                        <p>
                            <label>Size</label>
                            <select name="<?php echo $args['page'] ?>[<?php echo $args['id'] ?>_options]" id="">
                                <?php

                                foreach( array(
                                    'tiles' => 'Tiles',
                                    'centered' => 'Centered',
                                    'best_fit' => 'Best Fit',
                                    'responsive' => 'Responsive' ) as $key => $val):?>
                                <option value="<?php echo $key; ?>" <?php if($key == $this->general_settings[$args['id']. '_options'] ) echo ' selected="selected"';?>><?php echo $val; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </p>

                    </div>

                <?php
                endif;
                break;

            /***********************************
            Text Box
            ***********************************/
            case 'text':
                $placeholder = isset( $args['placeholder'] ) ? 'placeholder="'. $args['placeholder'] .'"' : '' ;
                ?>
                <input name="<?php echo $args['page'] ?>[<?php echo $args['id'] ?>]" type="text" id="<?php echo $args['id'] ?>" value="<?php echo esc_html( $option_value ); ?>" class="regular-text" <?php echo $placeholder; ?> />
                <?php
                break;

            /***********************************
            Color Picker
            ***********************************/
            case 'color':
                ?>
                <input name="<?php echo $args['page'] ?>[<?php echo $args['id'] ?>]" value="<?php echo esc_url($option_value ); ?>" type="text" value="#ffffff" class="codigo-color-field" />
                <?php
                break;

            /***********************************
            Checkbox
            ***********************************/
            case 'checkbox':
                if(isset($args['options']) && is_array($args['options'])):
                    foreach ($args['options'] as $key => $value) :
                        $opt = isset($option_value[$key]) ? $option_value[$key] : ''; ?>
                        <label for="input-<?php echo $args['id'] ?>-<?php echo $key ?>">
                            <input id="input-<?php echo $args['id'] ?>-<?php echo $key ?>" name="<?php echo $args['page'] ?>[<?php echo $args['id'] ?>][<?php echo $key ?>]" value="true" type="checkbox" <?php checked( esc_attr( $opt ), 'true' ); ?>/>
                            <?php echo $value ?>
                        </label><br>
                    <?php endforeach;
                else:?>
                <input id="input-<?php echo $args['id'] ?>" name="<?php echo $args['page'] ?>[<?php echo $args['id'] ?>]" value="true" type="checkbox" <?php checked( esc_attr( $option_value ), 'true' ); ?>/>
                <?php

                endif;
                break;

            /***********************************
            Content Editor
            ***********************************/
            case 'editor':
                $settings = array(
                    'textarea_rows' => 15,
                    'tabindex' => 1
                );

                $option_name = $args['page'].'['.$args['id'] .']';

                wp_editor( $option_value , $option_name, $settings );
                break;

            /***********************************
            Textarea
            ***********************************/
            case 'textarea':
                ?>
                <textarea id="input-<?php echo $args['id'] ?>" name="<?php echo $args['page'] ?>[<?php echo $args['id'] ?>]" cols="70" rows="10"><?php echo $option_value; ?></textarea>
                <?php
                break;

        }

        // Add a description field to all
        if( isset($args['description']) ) echo '<p class="description">'.$args['description'].'</p>';
    }

    /***********************************
    Admin Menu
    ***********************************/
    function add_admin_menus() {
        add_options_page( __('WP Nebulus Settings', 'wp_nebulus'), __('WP Nebulus Settings', 'wp_nebulus'), 'manage_options', $this->plugin_options_key, array( &$this, 'plugin_options_page' ) );
    }

    function plugin_options_page() {
        $tab = isset( $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : $this->general_settings_key;
        ?>
        <div class="wrap wp-nebulus-wrap <?php echo $tab ?>">
            <?php $this->plugin_options_tabs(); ?>

            <div class="decodigo-support">
                <a target="_blank" href="http://decodigothemes.com" class="brand-link">
                    <img src="<?php echo NEBULUS_ADMIN_URI . '/img/decodigo.png' ?>" alt="">
                    <span>Decodigo Themes</span>
                </a>
                <p>
                    WP Nebulus v<?php echo NEBULUS_VERSION; ?><br>
                    Need support? <a target="_blank" href="http://themeforest.net/user/decodigo#contact">Click here</a><br>
                    Twitter: <a target="_blank" href="https://twitter.com/Dec0dig0">@dec0dig0</a><br>
                    Facebook: <a target="_blank" href="https://www.facebook.com/dec0dig0">dec0dig0</a><br>

                </p>
            </div>

            <form method="post" action="options.php">
                <?php settings_fields( $tab ); ?>
                <?php do_settings_sections( $tab ); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    function plugin_options_tabs() {
        $current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->general_settings_key;

        screen_icon();
        echo '<h2 class="nav-tab-wrapper">';
        foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
            $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
            echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
        }
        echo '</h2>';
    }

    function scripts($hook){
        if( $hook == "settings_page_$this->plugin_options_key"){
            wp_enqueue_style( 'jquery-datepicker-styles', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
            wp_enqueue_style( 'nebulus-styles', NEBULUS_ADMIN_URI . '/css/admin-css.css', false, NEBULUS_VERSION);
            wp_enqueue_style( 'thickbox' );
            wp_enqueue_style( 'wp-color-picker' );

            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-datepicker', 'http://code.jquery.com/ui/1.10.3/jquery-ui.js', array('jquery', 'jquery-ui-core' ) );
            wp_enqueue_script( 'media-upload' );
            wp_enqueue_script( 'thickbox' );
            wp_enqueue_script( 'jquery-ui-timepicker', NEBULUS_ADMIN_URI . '/js/jquery-ui-timepicker.js', array('jquery', 'jquery-datepicker') );
            wp_enqueue_script( 'nebulus-scripts', NEBULUS_ADMIN_URI . '/js/admin.js', array('jquery', 'wp-color-picker'), NEBULUS_VERSION );
        }
    }
}
