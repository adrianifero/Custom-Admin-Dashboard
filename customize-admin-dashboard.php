<?php 
/*
Plugin Name: Customize Admin Dashboard 
Plugin URI: http://adriantoro.infoeplus.com
Description: Clean your admin dashboard and create custom boxes with custom content (images, links, text, lists).  Access and modify your boxes on the settings menu under 'Custom Admin Dashboard'.
Version: 1.0.2a
Author: Adrian Toro
Author URI: http://adriantoro.infoeplus.com
License: GPLv3 
*/

/* Add Settings Link on Plugins page
------------------------------------------ */
function cadashboard_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . 'options-general.php?page=cadashboard">Settings</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}
add_filter('plugin_action_links', 'cadashboard_action_links', 10, 2);

/* Add Admin Styles
------------------------------------------ */

function cadashboard_add_stylesheet() {
	
	global $wp_styles;
	wp_enqueue_style( 'cadashboard', plugins_url( basename( dirname( __FILE__ ) ) . '/css/customize-admin-dashboard.css' ), false );
	$wp_styles->add_data( 'cadashboard', 'rtl', true );
	
	
}
add_action( 'admin_print_styles', 'cadashboard_add_stylesheet'  );


/* Create menu user options under 'Settings'
------------------------------------------ */

function cadashboard_menu() {
	add_options_page( 'Custom Admin Dashboard Options', 'Custom Admin Dashboard', 'manage_options', 'cadashboard', 'cadashboard_options' );

	//call register settings function
	add_action( 'admin_init', 'cadashboard_register_settings' );
}
add_action( 'admin_menu', 'cadashboard_menu' );

function cadashboard_register_settings() {
	register_setting( 'cadashboard_group', 'Box01Title'); 
	register_setting( 'cadashboard_group', 'Box01Content'); 
	register_setting( 'cadashboard_group', 'Box02Title'); 
	register_setting( 'cadashboard_group', 'Box02Content'); 
	register_setting( 'cadashboard_group', 'show_to_user_role'); 
	
} 

function cadashboard_options() {
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
        <div id="cadashboard-header">
            <div id="cadashboard-background">
                <h2><img src="<?php echo plugins_url( basename( dirname( __FILE__ ) ) . '/images/logo.png' ); ?>" width="40" height="auto"><?php _e('Customize Admin Dashboard','cadashboard');?></h2>
            </div>
        </div>
        
        <div id="cadashboard-content">
        
            <p><?php _e('Use the two boxes below to create a personalized experience for your Dashboard. You can insert images, links, html code, etc.','cadashboard');?></p>
            
            <form method="post" action="">
                <?php settings_fields( 'cadashboard_group' ); ?>
                
              	<div class="option box">      
                    
                    <?php $remove_metaboxes = get_option('remove_default_metaboxes'); ?>
                    <input type="checkbox" name="remove_default_metaboxes" value="1"<?php checked( $remove_metaboxes,1 ); ?> /><?php _e('Remove ALL default wordpress metaboxes.','cadashboard');?>
              	</div>         
                   
                <div class="row cadashboard">             
                	<h2>First Box</h2>   
                    <table class="form-table">
                        <tr valign="top">
                        <th scope="row"><?php _e('Title','cadashboard');?>:</th>
                        <td><input type="text" name="F3SP_input_1" value="<?php echo get_option('Box01Title'); ?>"/></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><?php _e('Content','cadashboard');?>:</th>
                        <td><?php wp_editor( html_entity_decode(stripslashes(get_option('Box01Content'))), 'F3SP_input_2' ); ?></td>
                        </tr>
                        
                    </table>
                
					<div class="option box">     
						<?php _e('Who should see the first box','cadashboard');?>?
						<?php $options = get_option( 'show_to_user_role' ); ?>
					
						<ul>
						<?php foreach (get_editable_roles() as $role_name => $role_info): ?>
							<li><?php echo $role_info['name']; ?> <input type="checkbox" name="show_to_user_role[0][<?php echo $role_info['name']; ?>]" value="1"<?php checked( isset( $options[0][$role_info['name']] ) ); ?> /></li>
						<?php endforeach; ?>
						</ul>
					
	                	<?php submit_button(); ?>
					</div>
            	
            	</div>
            	
            	<hr/>
                
                <div class="row cadashboard">                
                	<h2><?php _e('Second Box','cadashboard');?></h2>        
                    <table class="form-table">
                        <tr valign="top">
                        <th scope="row"><?php _e('Title','cadashboard');?>:</th>
                        <td><input type="text" name="F3SP_input_3" value="<?php echo get_option('Box02Title'); ?>"/></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><?php _e('Content','cadashboard');?>:</th>
                        <td><?php wp_editor( html_entity_decode(stripslashes(get_option('Box02Content'))), 'F3SP_input_4' ); ?></td>
                        </tr>
                    </table>
			
			
					<div class="option box">     
						<?php _e('Who should see the second box','cadashboard');?>?
						<?php $options = get_option( 'show_to_user_role' ); ?>
				
						<ul>
						<?php foreach (get_editable_roles() as $role_name => $role_info): ?>
							<li><?php echo $role_info['name']; ?> <input type="checkbox" name="show_to_user_role[1][<?php echo $role_info['name']; ?>]" value="1"<?php checked( isset( $options[1][$role_info['name']] ) ); ?> /></li>
						<?php endforeach; ?>
						</ul>
						
	                	<?php submit_button(); ?>
					</div>
	            </div>
   
            </form>            
             
      	</div>
      	
      	<style>
      	div.row.cadashboard {
      		padding: 6px 12px;
			margin:44px;
			border: 5px solid white;
      	}
      	div.row.cadashboard div.option.box,
      	div.row.cadashboard div.option.box p.submit {
      		text-align:right;
      	}
      	</style>
	</div>
    
	<?php
}
/* ------------------------------------------ */

function cadashboard_activation_redirect( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_redirect( admin_url( 'options-general.php?page=cadashboard' ) ) );
    }
}
add_action( 'activated_plugin', 'cadashboard_activation_redirect' );



/* Define Variables
----------------------------------------------- */

$first_widget_title = __('Edit this title','cadashboard');
$first_widget_text = __('<p style="text-align: justify;"><a href="#"><img class="alignleft" style="padding:15px;" title="www.blogacademyoflearning" src="http://placehold.it/80x80" alt="Custom Image"  style="max-width:200px;" /></a><p>Hello!</p><p style="text-align:justify;">I\'m your custom widget.   </p><p style="text-align:justify;">I\'m here to show important information and you can customize me by going to the settings menu and select "Custom Admin Dashboard".</p><p>You can use my to display your logo, or to place any image or embed any video you want.</p>','cadashboard');

$second_widget_title = __('Edit this title','cadashboard');
$second_widget_text = __('
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
','cadashboard');

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
function cadashboard_first_widget_function() {
	global $first_widget_text;
	// Display whatever it is you want to show
	echo apply_filters('the_content',$first_widget_text); 
	
} 
// Create the function use in the action hook
function cadashboard_add_first_widget() {
	global $first_widget_title;
	global $showwidget; 
	$showwidget = false;
	
	// Show depending on selected user role
	$user_roles		= get_option('show_to_user_role');
	$current_user 		= wp_get_current_user();
	$current_user_role = ucfirst($current_user->roles[0]);
	
	
	if (isset($user_roles[0][$current_user_role]) && $user_roles[0][$current_user_role] == 1) 	$showwidget = true;
		
	if ($showwidget == true){
		wp_add_dashboard_widget('dashboard_first_widget', $first_widget_title, 'cadashboard_first_widget_function');	
		add_meta_box( 'dashboard_first_widget', $first_widget_title, 'cadashboard_first_widget_function', 'dashboard', 'normal', 'high' );
	}
} 
// Hook into the 'wp_dashboard_setup' action to register our other functions
add_action('wp_dashboard_setup', 'cadashboard_add_first_widget' ); 


/* Add Dashboard Widget
----------------------------------------------- */
function cadashboard_second_widget_function() {
	// Display whatever it is you want to show
	global $second_widget_text;
	echo apply_filters('the_content',$second_widget_text); 
	
} 
// Create the function use in the action hook
function cadashboard_add_second_widget() {
	global $second_widget_title;
	global $showwidget; 
	$showwidget = false;
	
	// Show depending on selected user role
	$user_roles		= get_option('show_to_user_role');
	$current_user 		= wp_get_current_user();
	$current_user_role = ucfirst($current_user->roles[0]);

	if (isset($user_roles[1][$current_user_role]) && $user_roles[1][$current_user_role] == 1) 	$showwidget = true;
	
	if ($showwidget == true){
		wp_add_dashboard_widget('dashboard_second_widget', $second_widget_title, 'cadashboard_second_widget_function');	
		add_meta_box( 'dashboard_second_widget', $second_widget_title, 'cadashboard_second_widget_function', 'dashboard', 'side', 'high' );
	}
} 
// Hook into the 'wp_dashboard_setup' action to register our other functions
add_action('wp_dashboard_setup', 'cadashboard_add_second_widget' ); 


/* Remove Wordpress Dashboard Boxes
----------------------------------------------- */
function cadashboard_remove_dashboard_meta_boxes(){
	global $remove_metaboxes;
	if($remove_metaboxes) {
		global $wp_meta_boxes;
    
    	//print_r( $wp_meta_boxes['dashboard'] );
    	
		foreach ($wp_meta_boxes['dashboard']['normal']['core'] as $id => $meta_box){
			unset($wp_meta_boxes['dashboard']['normal']['core'][$id]);
		}
		foreach ($wp_meta_boxes['dashboard']['side']['core'] as $id => $meta_box){
			unset($wp_meta_boxes['dashboard']['side']['core'][$id]);
		}
    		
		

		// Remove Other undesired Menu Boxes:
		function jp_rm_menu() {
			if( class_exists( 'Jetpack' )  ) {
				// This removes the page from the menu in the dashboard
				remove_menu_page( 'jetpack' );
				remove_menu_page( 'wpcr3_view_reviews' );
				remove_menu_page( 'tools.php' );
			}
		}
		add_action( 'admin_init', 'jp_rm_menu' ); 

		function jp_rm_icon() {
			if( class_exists( 'Jetpack' ) && !current_user_can( 'manage_options' ) ) {

				// This removes the small icon in the admin bar
				echo "\n" . '<style type="text/css" media="screen">#wp-admin-bar-notes { display: none; }</style>' . "\n";
			}
		}
		add_action( 'admin_head', 'jp_rm_icon' );
		
		remove_meta_box('jetpack_summary_widget', 'dashboard', 'normal');
		remove_meta_box('wpe_dify_news_feed', 'dashboard', 'normal');
	
		// Remove the welcome panel
		update_user_meta(get_current_user_id(), 'show_welcome_panel', false);
		
	}
}
add_action('wp_dashboard_setup', 'cadashboard_remove_dashboard_meta_boxes');

?>