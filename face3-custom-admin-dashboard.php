<?php 
/*
Plugin Name: Custom Admin Dashboard 
Plugin URI: http://face3media.com
Description: Clean your admin dashboard and create custom boxes with custom content (images, links, text, lists).  Access and modify your boxes on the settings menu under 'Custom Admin Dashboard'.  Created by Face3Media.  V. 1.0.1
Version: 1.0.3
Author: Face3Media
Author URI: http://face3media.com
License: GPLv3 
*/


/* Define Plugin Version 
------------------------------------------ */
if (!defined('CUSTOM_DASHBOARD_VERSION_KEY'))
    define('CUSTOM_DASHBOARD_VERSION_KEY', 'custom_dasboard_version');

if (!defined('CUSTOM_DASHBOARD_VERSION_NUM'))
    define('CUSTOM_DASHBOARD_VERSION_NUM', '1.0.1');

add_option(CUSTOM_DASHBOARD_VERSION_KEY, CUSTOM_DASHBOARD_VERSION_NUM);


/* Add Settings Link on Plugins page
------------------------------------------ */
function face3_custom_dashboard_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=face3_custom_admin_dashboard">Settings</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}
add_filter('plugin_action_links', 'face3_custom_dashboard_action_links', 10, 2);

/* Add Admin Styles
------------------------------------------ */

function face3_custom_admin_dashboard_add_stylesheet() {
	
	global $wp_styles;
	wp_enqueue_style( 'face3_custom_admin_dashboard', plugins_url( basename( dirname( __FILE__ ) ) . '/inc/face3.css' ), false );
	$wp_styles->add_data( 'face3_custom_admin_dashboard', 'rtl', true );
	
	
}
add_action( 'admin_print_styles', 'face3_custom_admin_dashboard_add_stylesheet'  );


/* Create menu user options under 'Settings'
------------------------------------------ */

function face3_custom_admin_dashboard_menu() {
	add_options_page( 'Custom Admin Dashboard Options', 'Custom Admin Dashboard', 'manage_options', 'face3_custom_admin_dashboard', 'face3_custom_admin_dashboard_options' );

	//call register settings function
	add_action( 'admin_init', 'face3_custom_admin_dashboard_register_settings' );
}
add_action( 'admin_menu', 'face3_custom_admin_dashboard_menu' );

function face3_custom_admin_dashboard_register_settings() {
	register_setting( 'face3_custom_admin_dashboard_group', 'Box01Title'); 
	register_setting( 'face3_custom_admin_dashboard_group', 'Box01Content'); 
	register_setting( 'face3_custom_admin_dashboard_group', 'Box02Title'); 
	register_setting( 'face3_custom_admin_dashboard_group', 'Box02Content'); 
	register_setting( 'face3_custom_admin_dashboard_group', 'show_to_user_role'); 
	
} 

function face3_custom_admin_dashboard_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}	
	
	/* Save Content data */
	if (!empty($_POST)){
		if (isset($_POST["F3SP_input_1"])) { 
			update_option('Box01Title', $_POST["F3SP_input_1"]);
			update_option('Box01Content', $_POST["F3SP_input_2"]);
			update_option('Box02Title', $_POST["F3SP_input_3"]);
			update_option('Box02Content', $_POST["F3SP_input_4"]);
		}
	} 
	/* Save Checkbox data */
	if (!empty($_POST)){
		if (isset($_POST["show_to_user_role"])) update_option('show_to_user_role', $_POST['show_to_user_role']);
		update_option('remove_default_metaboxes', $_POST['remove_default_metaboxes']);
		
	} 
	/* Display Options Page */ 
	?>   

	<div class="wrap">
        <div id="face3-header">
            <div id="face3-background">
                <h2><img src="<?php echo plugins_url( basename( dirname( __FILE__ ) ) . '/inc/face3.png' ); ?>"> Custom Admin Dashboard</h2>
                <div class="face3-logo"><img src="<?php echo plugins_url( basename( dirname( __FILE__ ) ) . '/inc/logo.png' ); ?>"></div>
            </div>
        </div>
        
        <div id="face3-content">
        
            <p>Create your new dashboard boxes, you can insert images, links, html code.</p>
            
            <form method="post" action="">
                <?php settings_fields( 'face3_custom_admin_dashboard_group' ); ?>
                
              	<div class="option box">      
                    Remove other default wordpress metaboxes?
                    <?php $remove_metaboxes = get_option('remove_default_metaboxes'); ?>
                    <input type="checkbox" name="remove_default_metaboxes" value="1"<?php checked( $remove_metaboxes,1 ); ?> />
              	</div>         
                   
                <div class="row">                
                    <table class="form-table">
                        <tr valign="top">
                        <th scope="row">First Box Title</th>
                        <td><input type="text" name="F3SP_input_1" value="<?php echo get_option('Box01Title'); ?>"/></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row">First Box Content</th>
                        <td><?php wp_editor( html_entity_decode(stripslashes(get_option('Box01Content'))), 'F3SP_input_2' ); ?></td>
                        </tr>
                        
                    </table>
                </div> 
                
                <div class="row">                
                    <table class="form-table">
                        <tr valign="top">
                        <th scope="row">Second Box Title</th>
                        <td><input type="text" name="F3SP_input_3" value="<?php echo get_option('Box02Title'); ?>"/></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row">Second Box Content</th>
                        <td><?php wp_editor( html_entity_decode(stripslashes(get_option('Box02Content'))), 'F3SP_input_4' ); ?></td>
                        </tr>
                    </table>
                </div> 
                
                <div class="option box">     
            		Who should see these boxes?
					<?php $options = get_option( 'show_to_user_role' ); ?>
                    
                    <br><input type="checkbox" name="show_to_user_role[Administrator]" value="1"<?php checked( isset( $options['Administrator'] ) ); ?> />Administrator
                    <br><input type="checkbox" name="show_to_user_role[Editor]" value="1"<?php checked( isset( $options['Editor'] ) ); ?> />Editor        
                    <br><input type="checkbox" name="show_to_user_role[Author]" value="1"<?php checked( isset( $options['Author'] ) ); ?> />Author          
                    <br><input type="checkbox" name="show_to_user_role[Follower]" value="1"<?php checked( isset( $options['Follower'] ) ); ?> />Follower
            	</div>
                <?php submit_button(); ?>
            </form>            
             
      	</div>
	</div>
    
	<?php
}
/* ------------------------------------------ */




/* Define Variables
----------------------------------------------- */

$first_widget_title = 'Edit this title';
$first_widget_text = '<p style="text-align: justify;"><a href="#"><img class="alignleft" style="padding:15px;" title="www.blogacademyoflearning" src="http://placehold.it/80x80" alt="Custom Image"  style="max-width:200px;" /></a><p>Hello!</p><p style="text-align:justify;">I\'m your custom widget.   </p><p style="text-align:justify;">I\'m here to show important information and you can customize me by going to the settings menu and select "Custom Admin Dashboard".</p><p>You can use my to display your logo, or to place any image or embed any video you want.</p>';

$second_widget_title = 'Edit this title';
$second_widget_text = '
<p style="text-align: justify;"><strong><strong>You can embed videos on this widget, just paste the HTML code you want to display.  Follow these simple instructions:</strong></strong></p>
<ol style="text-align: justify;">
	<li>Go to the Youtube Video.</li>
	<li>Click the Share button located under the video.</li>
	<li>Click the Embed button.</li>
	<li>Copy the code provided in the expanded box.</li>
	<li>Paste the code into this box.</li>
</ol>
<p style="text-align: justify;" dir="ltr">You may also customize your own embeddable player by clicking on the embed code. When you click on the embed code the space below it will expand and reveal customization options.</p>

<p>Now go and edit me on the settings menu, under "Custom Admin Dashboard" option</p>
';

$option01 			= get_option('Box01Title');
$option02 			= html_entity_decode(stripslashes(get_option('Box01Content')));
$option03 			= get_option('Box02Title');
$option04			= html_entity_decode(stripslashes(get_option('Box02Content')));
$showwidget 		= false;
$remove_metaboxes 	= get_option('remove_default_metaboxes');

if(!empty($option01)) 	$first_widget_title 	= get_option('Box01Title');
if(!empty($option02)) 	$first_widget_text 		= html_entity_decode(stripslashes(get_option('Box01Content')));
if(!empty($option03)) 	$second_widget_title 	= get_option('Box02Title');
if(!empty($option04)) 	$second_widget_text		= html_entity_decode(stripslashes(get_option('Box02Content')));


/* ------------------------------------------ */



/* Add Dashboard Widget
----------------------------------------------- */
function face3_custom_dashboard_first_widget_function() {
	global $first_widget_text;
	// Display whatever it is you want to show
	echo $first_widget_text;
} 
// Create the function use in the action hook
function face3_custom_dashboard_add_first_widget() {
	global $first_widget_title;
	global $showwidget; 
	
	// Show depending on selected user role
	$user_roles		= get_option('show_to_user_role');
	$current_user 		= wp_get_current_user();
	$current_user_role = ucfirst($current_user->roles[0]);
	
	if (isset($user_roles[$current_user_role]) && $user_roles[$current_user_role] == 1) 	$showwidget = true;
		
	if ($showwidget == true){
		wp_add_dashboard_widget('dashboard_first_widget', $first_widget_title, 'face3_custom_dashboard_first_widget_function');	
		add_meta_box( 'dashboard_first_widget', $first_widget_title, 'face3_custom_dashboard_first_widget_function', 'dashboard', 'normal', 'high' );
	}
} 
// Hook into the 'wp_dashboard_setup' action to register our other functions
add_action('wp_dashboard_setup', 'face3_custom_dashboard_add_first_widget' ); 


/* Add Dashboard Widget
----------------------------------------------- */
function face3_custom_dashboard_second_widget_function() {
	// Display whatever it is you want to show
	global $second_widget_text;
	echo $second_widget_text;
} 
// Create the function use in the action hook
function face3_custom_dashboard_add_second_widget() {
	global $second_widget_title;
	global $showwidget; 
	
	// Show depending on selected user role
	$user_roles		= get_option('show_to_user_role');
	$current_user 		= wp_get_current_user();
	$current_user_role = ucfirst($current_user->roles[0]);
	
	if (isset($user_roles[$current_user_role]) && $user_roles[$current_user_role] == 1) 	$showwidget = true;
	
	if ($showwidget == true){
		wp_add_dashboard_widget('dashboard_second_widget', $second_widget_title, 'face3_custom_dashboard_second_widget_function');	
		add_meta_box( 'dashboard_second_widget', $second_widget_title, 'face3_custom_dashboard_second_widget_function', 'dashboard', 'side', 'high' );
	}
} 
// Hook into the 'wp_dashboard_setup' action to register our other functions
add_action('wp_dashboard_setup', 'face3_custom_dashboard_add_second_widget' ); 


/* Remove Wordpress Dashboard Boxes
----------------------------------------------- */
function face3_custom_dashboard_remove_dashboard_meta_boxes(){
	global $remove_metaboxes;
	if($remove_metaboxes) {
		global $wp_meta_boxes;
		// Dashboard core widgets :: Left Column
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		// Additional dashboard core widgets :: Right Column
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
		// Remove the welcome panel
		update_user_meta(get_current_user_id(), 'show_welcome_panel', false);
		// Remove wordpress 3.8 activity meta box:
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
	}
}
add_action('wp_dashboard_setup', 'face3_custom_dashboard_remove_dashboard_meta_boxes');
	

?>