<?php 
/*
Plugin Name: Optin Forms
Plugin URI: http://www.codeleon.com/wordpress/plugins/optin-forms
Description: Create beautiful optin forms with ease. Choose a form design, customize it, and add your form to your blog with a simple mouse-click.
Author: Codeleon
Version: 1.1.3
Author URI: http://www.codeleon.com
Text Domain: optinforms
Domain Path:   /languages/
License:
  Copyright 2014 codeleon.com
 
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.
 
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
 
  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Add translation to plugin
function optinforms_init() {
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain( 'optinforms', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'optinforms_init');

// Allow for translation of plugin description
$plugin_header_translate = array(
	__('Create beautiful optin forms with ease. Choose a form design, customize it, and add your form to your blog with a simple mouse-click.', 'optinforms'),
);

// Add our menu to WordPress
add_action( 'admin_menu', 'optinforms_menu' );

function optinforms_menu() 
{
	// @since 1.1.2 added a menu position decimal fix to prevent conflict with other themes using 31, such as Thesis Theme
	// @http://gabrielharper.com/blog/2012/08/wordpress-admin-menu-positioning-conflicts/
	$submenu = add_menu_page(__('Optin Forms','menu-test'), __('Optin Forms','menu-test'), 'manage_options', 'optinforms', 'optinforms_main_page', plugins_url('optin-forms/images/icon.png'), '30.1');
	
	// * We want our JS and CSS loaded on our admin pages only, so let's just load them for now
	add_action( 'load-' . $submenu, 'load_optinforms_admin_scripts' );
}

// Enqueue our CSS and JS on WP Video Coach admin pages only
function load_optinforms_admin_scripts() {
	add_action( 'admin_enqueue_scripts', 'optinforms_admin_scripts' );
}

// Include our registration settings
include( plugin_dir_path( __FILE__ ) . 'includes/register-settings.php');
// Include our regular functions
include( plugin_dir_path( __FILE__ ) . 'includes/functions.php');
// Include our form functions
include( plugin_dir_path( __FILE__ ) . 'includes/functions-form-1.php');
include( plugin_dir_path( __FILE__ ) . 'includes/functions-form-2.php');
include( plugin_dir_path( __FILE__ ) . 'includes/functions-form-3.php');
include( plugin_dir_path( __FILE__ ) . 'includes/functions-form-4.php');
include( plugin_dir_path( __FILE__ ) . 'includes/functions-form-5.php');
include( plugin_dir_path( __FILE__ ) . 'includes/functions-forms.php');


// Add our CSS and JS to admin head, but just for our pages (see load_optinforms_admin_scripts above!)
function optinforms_admin_scripts()
{
	wp_enqueue_style('optinforms-admin-stylesheet', plugins_url('/css/optinforms-admin.css', __FILE__ ), array('googleFont'));
	wp_enqueue_script('tabcontent', plugins_url('/js/tabcontent.js', __FILE__ ));
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('optinforms-color', plugins_url('/js/optinforms-color.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	wp_enqueue_script('placeholder', plugins_url('/js/placeholder.js', __FILE__ ));
	wp_enqueue_script('toggle', plugins_url('/js/custom.js', __FILE__ ));
	wp_register_style('googleFont', 'http://fonts.googleapis.com/css?family=Share+Tech|Droid+Sans|Lobster|Fenix|Unkempt|Flavors|Viga|Damion|Oleo+Script|Racing+Sans+One|Nixie+One|Fredoka+One|Open+Sans|Overlock+SC|Bubbler+One|Contrail+One|Gochi+Hand|Roboto+Condensed|Russo+One|Cinzel+Decorative|News+Cycle|Marcellus+SC|Chewy|Quicksand|Sanchez|Signika+Negative|Gloria+Hallelujah|Grand+Hotel|Droid+Serif|Englebert|Oswald|Pacifico|Titan+One|Shadows+Into+Light|Dancing+Script|Luckiest+Guy|Parisienne|Coming+Soon|Baumans|Belgrano');
}

// Enqueue our form CSS on front end
	add_action( 'wp_enqueue_scripts', 'optinforms_scripts' );


// Add our CSS and JS to admin head, but just for our pages (see load_optinforms_admin_scripts above!)
function optinforms_scripts()
{
	global $optinforms_form_design;
	wp_enqueue_script('jquery');
	wp_enqueue_style('optinforms-stylesheet', plugins_url('/css/optinforms.css', __FILE__ ), array('googleFont'));
	wp_enqueue_script('placeholder', plugins_url('/js/placeholder.js', __FILE__ ));
	wp_register_style('googleFont', optinforms_used_fonts());
}

// Make sure user can manage options
function optinforms_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>

<?php }
function optinforms_main_page() {
	
	{ ?>
    <div class="wrap">
    	<div id="icon-optinforms" class="icon32">
        </div><!--icon-32-->
		<h2 class="title"><?php echo optinforms_menu_tabs(); ?></h2>
    </div><!--wrap-->
    
		<?php echo optinforms_configuration(); ?>

        <?php if( isset($_GET['settings-updated']) ) { ?>
    		<div id="message" class="updated">
        		<p><strong><?php _e('Settings updated') ?></strong> - <?php echo __('If you enjoy our plugin, why not tweet about it?', 'optinforms'); ?> <a href="https://twitter.com/intent/tweet?original_referer=http://www.codeleon.com/wordpress/plugins/optin-forms/&related=codeleoncom&text=Create%20Beautiful%20Optin%20Forms%20in%20WordPress%20with%20This%20FREE%20Plugin:&url=http://www.codeleon.com/wordpress/plugins/optin-forms" target="_blank"><?php echo __('Share on Twitter', 'optinforms'); ?></a></p>
    		</div>
		<?php } ?>
    
    <div id="optinforms">
      	<form method="post" action="options.php" id="frm1">
        <?php settings_fields( 'optinforms-settings-group' ); ?>
    	
        <div id="optinforms-email-solution-tab" class="tabcontent">
        	<div class="optinforms-container-left">
                <?php include( plugin_dir_path( __FILE__ ) . 'includes/options-email-solution.php'); ?>
            </div><!--optinforms-container-left-->
            <div class="optinforms-container-right">
            	<?php include( plugin_dir_path( __FILE__ ) . 'includes/sidebar.php'); ?>
            </div><!--optinforms-container-right-->
            <div class="clear"></div>
    	</div><!--optinforms-email-solution-tab-->
        
    	<div id="optinforms-posts-tab" class="tabcontent">
        	<div class="optinforms-container-left">
                <div class="optiongroup">
                    <p><?php echo __('Add a beautiful optin form to your posts, custom post types and pages. Include the form on your website with a simple mouse-click, or use the shortcode to add it to specific posts and pages.', 'optinforms'); ?></p>
                    <div class="optionleft">
                        <label for="optinforms_form_design" class="nopointer"><?php echo __('Form design', 'optinforms'); ?></label>
                    </div><!--optionleft-->
                    <div class="optionmiddle">
                        <select name="optinforms_form_design" id="optinforms_form_design">
                            <option value="optinforms_form_design_option1" <?php if (get_option('optinforms_form_design')== 'optinforms_form_design_option1') echo 'selected="selected"'; ?>>01</option>
                            <option value="optinforms_form_design_option2" <?php if (get_option('optinforms_form_design')== 'optinforms_form_design_option2') echo 'selected="selected"'; ?>>02</option>
                            <option value="optinforms_form_design_option3" <?php if (get_option('optinforms_form_design')== 'optinforms_form_design_option3') echo 'selected="selected"'; ?>>03</option>
                            <option value="optinforms_form_design_option4" <?php if (get_option('optinforms_form_design')== 'optinforms_form_design_option4') echo 'selected="selected"'; ?>>04</option>
                            <option value="optinforms_form_design_option5" <?php if (get_option('optinforms_form_design')== 'optinforms_form_design_option5') echo 'selected="selected"'; ?>>05</option>
                        </select>
                        <script type="text/javascript">
                            document.getElementById('optinforms_form_design').onchange = function() {
                                var i = 1;
                                var myDiv = document.getElementById("optinforms_form_design_option" + i);
                                while(myDiv) {
                                    myDiv.style.display = 'none';
                                    myDiv = document.getElementById("optinforms_form_design_option" + ++i);
                                }
                            document.getElementById(this.value).style.display = 'block';
                        };
                        </script>
                    </div><!--optionmiddle-->
                    <div class="optionlast">
                                    
                    </div><!--optionlast-->
                    <div class="clear"></div>
        
                </div><!--optiongroup-->
                
                <div class="optiongroup">
                    <div id="optinforms_form_design_option1" <?php if (get_option('optinforms_form_design')== '' || get_option('optinforms_form_design')== 'optinforms_form_design_option1') echo 'style="display:block;"'; ?>>
                        
						<?php include( plugin_dir_path( __FILE__ ) . 'includes/preview-form-1.php'); ?>
                       	<?php include( plugin_dir_path( __FILE__ ) . 'includes/options-form-1.php'); ?>
                    
                    </div><!--optinforms_form_design_option1-->
                    <div id="optinforms_form_design_option2" <?php if (get_option('optinforms_form_design')== 'optinforms_form_design_option2') echo 'style="display:block;"'; ?>>
                        
                        <?php include( plugin_dir_path( __FILE__ ) . 'includes/preview-form-2.php'); ?>
                        <?php include( plugin_dir_path( __FILE__ ) . 'includes/options-form-2.php'); ?>
                        
                    </div><!--optinforms_form_design_option2-->
                    <div id="optinforms_form_design_option3" <?php if (get_option('optinforms_form_design')== 'optinforms_form_design_option3') echo 'style="display:block;"'; ?>>
                        
                        <?php include( plugin_dir_path( __FILE__ ) . 'includes/preview-form-3.php'); ?>
                        <?php include( plugin_dir_path( __FILE__ ) . 'includes/options-form-3.php'); ?>
                        
                    </div><!--optinforms_form_design_option3-->
                    <div id="optinforms_form_design_option4" <?php if (get_option('optinforms_form_design')== 'optinforms_form_design_option4') echo 'style="display:block;"'; ?>>
                    
                        <?php include( plugin_dir_path( __FILE__ ) . 'includes/preview-form-4.php'); ?>
                        <?php include( plugin_dir_path( __FILE__ ) . 'includes/options-form-4.php'); ?>
                        
                    </div><!--optinforms_form_design_option4-->
                    <div id="optinforms_form_design_option5" <?php if (get_option('optinforms_form_design')== 'optinforms_form_design_option5') echo 'style="display:block;"'; ?>>
                        
                        <?php include( plugin_dir_path( __FILE__ ) . 'includes/preview-form-5.php'); ?>
                        <?php include( plugin_dir_path( __FILE__ ) . 'includes/options-form-5.php'); ?>
                        
                    </div><!--optinforms_form_design_option5-->
                </div><!--optiongroup-->
                
                <?php include( plugin_dir_path( __FILE__ ) . 'includes/options-form-functionality.php'); ?>
                <?php include( plugin_dir_path( __FILE__ ) . 'includes/options-form-placement.php'); ?>
                
        	</div><!--optinforms-container-left-->
            <div class="optinforms-container-right">
            	<?php include( plugin_dir_path( __FILE__ ) . 'includes/sidebar.php'); ?>
            </div><!--optinforms-container-right-->
            <div class="clear"></div>
        </div><!--optinforms-posts-tab-->
        
        <script type="text/javascript">
			var wpthumbs=new ddtabcontent("optinforms-menu-tabs") //enter ID of Tab Container
			wpthumbs.setpersist(true) //toogle persistence of the tabs' state
			wpthumbs.setselectedClassTarget("link") //"link" or "linkparent"
			wpthumbs.init()
        </script>
        
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </form>
        
        <div id="emailbox">
        	<form method="post" action="http://www.codeleon.com/send/form.php?form=5" id="frmSS5">
        	<h3><?php echo __('Sign up to receive news about the plugin and other WordPress news', 'optinforms'); ?></h3>
    		<span><?php echo __('My email is', 'optinforms'); ?></span>
            <input type="text" name="email" value="<?php global $current_user; get_currentuserinfo(); echo $current_user->user_email; ?>" />
            <span><?php echo __('and my name is', 'optinforms'); ?></span>
            <input type="text" name="CustomFields[1]" id="CustomFields_1_5" value="<?php global $current_user; get_currentuserinfo(); echo $current_user->display_name; ?>">
            <input type="hidden" name="format" value="h" />
            <input type="submit" class="emailbox-subscribe" value="<?php echo __('Subscribe', 'optinforms'); ?>" />
            </form>
    	</div><!--emailbox-->
	</div><!--optinforms-->

<?php }


} ?>