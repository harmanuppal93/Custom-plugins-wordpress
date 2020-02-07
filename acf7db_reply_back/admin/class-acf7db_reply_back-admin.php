<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.vsourz.com
 * @since      1.0.0
 *
 * @package    Acf7db_reply_back
 * @subpackage Acf7db_reply_back/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Acf7db_reply_back
 * @subpackage Acf7db_reply_back/admin
 * @author     Vsourz Development Team <support@vsourz.com>
 */
class Acf7db_reply_back_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Acf7db_reply_back_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Acf7db_reply_back_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_style( "acf7_db_rb_css", plugin_dir_url( __FILE__ ) . 'css/acf7db_reply_back-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Acf7db_reply_back_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Acf7db_reply_back_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( "acf7_db_rb_js", plugin_dir_url( __FILE__ ) . 'js/acf7db_reply_back-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	//Display table header in reply back column here
	function vsz_cf7_rb_admin_after_heading_field_callback(){
		?><th style="width: 32px;" class="manage-column"><?php _e(''); ?></th><?php
	}
	
	//Display reply back link with each entry in table
	function vsz_cf7_rb_admin_after_body_edit_field_func($form_id, $row_id){
		//Define thickbox popup function
		add_thickbox();
		$row_id = (int)$row_id;
		?><td>
			<a data-rid="<?php echo $row_id; ?>" href="#TB_inline?width=600&height=550&inlineId=cf7-rb-modal-edit-value" id="cf7-rb-edit-form" class="thickbox cf7-rb-value button button-primary" name="Reply Back">
				<?php _e('Reply',VSZ_ACF7DB_RB_TEXT_DOMAIN); ?>
			</a>
		</td>
		
		<?php
	}
	
	//Display reply back popup related content for this function
	function vsz_cf7_rb_after_admin_edit_values_form_callback($form_id){
		
		wp_enqueue_style("acf7_db_rb_css");
		wp_enqueue_script("acf7_db_rb_js");
		
		wp_enqueue_style('wp-pointer');
		wp_enqueue_script('wp-pointer');
		
		$form_id = intval($form_id);
		//Get form id related contact form object
		$obj_form = vsz_cf7_get_the_form_list($form_id);
		//get pre define fields information
		$arr_form_tag = $obj_form[0]->scan_form_tags();
		
		$arr_field_type = array();
		//Define option field type array
		$arr_option_type = array('checkbox','radio','select');
		//Check field exist with form or not
		if(!empty($arr_form_tag)){
			
			//Get all fields related information
			foreach($arr_form_tag as $key => $arr_type){
				//Check if tag type is submit then ignore tag info
				if($arr_type['basetype'] == 'submit') continue;
				//Check if field type match with option values or not
				if(isset($arr_type['basetype']) && in_array($arr_type['basetype'],$arr_option_type)){
					continue;
				}
				else{
					//get field type information
					$arr_field_type[$arr_type['name']]['basetype'] = $arr_type['basetype'];
				}
			}//Close for for each
		}//Close if for field check
		
		//Get form id related database fields information
		$fields = vsz_cf7_get_db_fields($form_id);
		//Define nonce value which is validate on save time
		$nonce = wp_create_nonce( 'vsz-cf7-edit-nonce-'.$form_id );
		//Get not editable fields list
		$not_editable_field = apply_filters('vsz_cf7_not_editable_fields',array());
		//Setup edit form design here
		?>
		
		<div class="cf7d-rb-modal" id="cf7-rb-modal-edit-value" style="display:none;">
			<div class="vsz-reply-error"></div>
			<form action="" class="cf7-rb-modal-form loading" id="cf7-rb-modal-form-edit-value" method="POST">
				<input type="hidden" name="fid" value="<?php echo $form_id; ?>" />
				<input type="hidden" name="rid" value="" />
				<input type="hidden" name="vsz_cf7_rb_edit_nonce"  value="<?php echo $nonce; ?>" />
				<ul id="cf7d-list-field-for-edit" class="edit-popup">
					<li class="clearfix">
						<span class="label"><?php _e('To',VSZ_ACF7DB_RB_TEXT_DOMAIN); ?>*</span>
						<input type="text" name="acf7_db_rb_to_email" id="acf7_db_rb_to_email" value="" class="field-your-name" placeholder="<?php _e('To Email',VSZ_ACF7DB_RB_TEXT_DOMAIN); ?>" style="width:38%;margin-right:10px;" />
						<select id="cf7-rb-to-email-select" class="rb-popup" style="width:40%;height:27px;line-height:27px;vertical-align:top;">
							<option value=""><?php _e('Select Field',VSZ_ACF7DB_RB_TEXT_DOMAIN); ?></option><?php
							//Get form id related header settings value
							$field_settings = get_option('vsz_cf7_settings_field_' . $form_id, array());
							
							if(count($field_settings) == 0) { //no settings found
							
								foreach ($fields as $k => $v) {
									//Display field type related fields here
									if(isset($arr_field_type[$k])){
										//Define all text field here
										$label = esc_html($v);
										$k = esc_html($k);
										//Display Text box design here
										echo "<option class='field-".$k."'>".$label."</option>";
									}//Close else
								}//Close foreach
							}//Close if for  check fields settings
							//If field setting not defined 
							else{
								
								//Display form fields with value
								foreach($field_settings as $k => $v) {
									//Check field set in array or not
									if (isset($fields[$k]) && isset($arr_field_type[$k])) {
										//Get label name values which is define on Setting screen
										$show = (int)$field_settings[$k]['show'];
										$label = esc_html($field_settings[$k]['label']);
										//Display Text box design here
										echo "<option class='field-".$k."'>".$label."</option>";
										unset($fields[$k]);
									}//Close If for check field name set in field array or not
								}//close for each
								
								
								//Call when new field is added in existing form
								//Check any field remaining in field array or not
								if (count($fields) > 0) {
									//Get all remaining fields information
									foreach ($fields as $k => $v) {
										$label = esc_html($v);
										$k = esc_html($k);
										//Display Text box design here
										echo "<option class='field-".$k."'>".$label."</option>";
									}//close foreach
								}//Close if
							}//close else 
						?></select>
					</li>
					<li class="clearfix">
						<span class="label"><?php _e('From',VSZ_ACF7DB_RB_TEXT_DOMAIN); ?>*</span>
						<input type="text" name="acf7_db_rb_from_name" id="acf7_db_rb_from_name" class="field-your-name" value="<?php echo get_bloginfo(); ?>" placeholder="<?php _e('From Name',VSZ_ACF7DB_RB_TEXT_DOMAIN); ?>" style="width:38%;margin-right:10px;"/>
						<input type="text" name="acf7_db_rb_from_email" id="acf7_db_rb_from_email" class="field-your-name" value="<?php echo get_option("admin_email"); ?>" placeholder="<?php _e('From Email',VSZ_ACF7DB_RB_TEXT_DOMAIN); ?>" style="width:40%;"/ />
				
					</li>
					<li class="clearfix">
						<span class="label"><?php _e('Subject',VSZ_ACF7DB_RB_TEXT_DOMAIN); ?>*</span>
						<input type="text" name="acf7_db_rb_subject" id="acf7_db_rb_subject" class="field-your-name" placeholder="<?php _e('Subject',VSZ_ACF7DB_RB_TEXT_DOMAIN); ?>" />					
					</li>
					<li class="clearfix" style="position:relative;">
						<span class="label"><?php _e('HTML Content',VSZ_ACF7DB_RB_TEXT_DOMAIN); ?></span>
						 <span class="tooltip"><i class="fa fa-question-circle"></i><span class="tooltiptext"><?php _e('If checked then message will be passed as html content, otherwise message will be passed as plain text.',VSZ_ACF7DB_RB_TEXT_DOMAIN); ?></span></span>
						<input type="checkbox" name="acf7_db_rb_is_html" id="acf7_db_rb_is_html" value="yes" />			
					</li>
					<li class="clearfix">
						<span class="label"><?php _e('Message Body',VSZ_ACF7DB_RB_TEXT_DOMAIN); ?>*</span>
						<textarea name="acf7_db_rb_msg_body" id="acf7_db_rb_msg_body" placeholder="<?php _e('Message Body',VSZ_ACF7DB_RB_TEXT_DOMAIN); ?>" style="height:260px;"></textarea>
					</li>
				</ul>	
				
				
				<div class="cf7d-modal-footer">
					<input type="hidden" name="arr_field_type" value="<?php print esc_html(json_encode($arr_field_type));?>">
					<input type="button" id="acf7_db_rb_send_reply_email" name="acf7_db_rb_send_reply_email" value="<?php _e('Send Reply',VSZ_ACF7DB_RB_TEXT_DOMAIN); ?>" class="button button-primary button-large" />
				</div>
			</form>
			<!------------------------------------ Ajax loader ----------------------------------------->
			<table style="display:none;" class="custom-overlay" id="rb_overlayLoader">
				<tbody>
					<tr>
						<td><img alt="Loading..." src="<?php echo plugin_dir_url(dirname( __FILE__)).'images/716.gif'; ?>"height="50" width="100"></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	
		<?php
	}//Close Reply Back POPUP function here
	
	//Reply Back form AJAX call handle By this function 
	public function acf7_db_rb_send_mail_callback(){
		
		global $wpdb;
		$toEmail = (isset($_POST['acf7_db_rb_to_email']) && !empty($_POST['acf7_db_rb_to_email'])) ? $_POST['acf7_db_rb_to_email'] : '';
		$fromName = (isset($_POST['acf7_db_rb_from_name']) && !empty($_POST['acf7_db_rb_from_name'])) ? $_POST['acf7_db_rb_from_name'] : '';
		$fromEmail = (isset($_POST['acf7_db_rb_from_email']) && !empty($_POST['acf7_db_rb_from_email'])) ? $_POST['acf7_db_rb_from_email'] : '';
		$subjectEmail = (isset($_POST['acf7_db_rb_subject']) && !empty($_POST['acf7_db_rb_subject'])) ? $_POST['acf7_db_rb_subject'] : '';
		$contentTypeCheckbox = (isset($_POST['acf7_db_rb_is_html']) && !empty($_POST['acf7_db_rb_is_html'])) ? $_POST['acf7_db_rb_is_html'] : '';
		$messageBody = (isset($_POST['acf7_db_rb_msg_body']) && !empty($_POST['acf7_db_rb_msg_body'])) ? $_POST['acf7_db_rb_msg_body'] : '';
		
		if($contentTypeCheckbox == "yes"){
			add_filter( 'wp_mail_content_type', function( $content_type ) {
				return 'text/html';
			});
		}
		
		if(empty($fromName)){
			$fromName = get_bloginfo();
		}
		if(empty($fromEmail)){
			$fromEmail = get_option('admin_email');
		}
		
		$headers = 'From:'.$fromName.' <'.$fromEmail.'>'."\r\n";
		
		$return = wp_mail( $toEmail, $subjectEmail, $messageBody, $headers );
		// Reset content-type to avoid conflicts
		if($contentTypeCheckbox == "yes"){
			remove_filter( 'wp_mail_content_type', function( $content_type ) {
				return 'text/html';
			});
		}
		remove_filter( 'wp_mail_content_type', function( $content_type ) {
			return 'text/html';
		});
		
		if($return){
			echo "y";
		}
		else{
			echo "n";
		}
		
		wp_die();
		
	}//Close Reply Back form AJAX callback
	
	//Display plugin active or inactive error message
	function acf7_db_rb_plugin_display_message(){
		if(defined('VSZ_ACF7DB_ACTIVE') && VSZ_ACF7DB_ACTIVE === false && is_admin())
		{
			echo '<div class="notice error"><p>';
			_e('If you want to use Advanced CF 7 DB Reply Back plugin then, you must install <a href="https://wordpress.org/plugins/advanced-cf7-db/" target="_blank">Advanced CF7 DB</a> plugin first.',VSZ_ACF7DB_RB_TEXT_DOMAIN);
			echo '</p></div>';
		}
	}
}
