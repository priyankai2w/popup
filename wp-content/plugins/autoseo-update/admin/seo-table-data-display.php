<?php
require_once( SEO_AUTOMATION . 'admin/seo-authentication-table.php' );
class SEO_Tabledisplaydata {

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $customers_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
		add_action('admin_init', [ $this,'table_load_plugin_textdomain' ] );	
		// add_action('admin_menu', [ $this,'cltd_example_admin_menu' ] );	
	}

	public function table_load_plugin_textdomain()
	{
	    load_plugin_textdomain('edit_table_data', false, dirname(plugin_basename(__FILE__)));
	}

	public static function set_screen( $status, $option, $value ) {
		return $value;
	}
	// public abstract function plugin_menu();
	public function plugin_menu() {
		$hook = add_menu_page(
			__('Seoautomation', 'edit_table_data'),
			__('Seoautomation','edit_table_data'),
			'manage_options',
			'wp_list_table_class',
			[ $this, 'plugin_settings_page' ]
		);

		add_action( "load-$hook", [ $this, 'screen_option' ] );

		// add_submenu_page( 'wp_list_table_class', 'Authentication', 'Authentication', 'manage_options', 'wp_list_table_class', [ $this, 'plugin_settings_page' ] );
		if(isset($_GET['id']) != null)
		 {
		 	$var = '<span style="color: rgba(240,245, 250, 0.7);font-weight: 100;">Add new</span>';	
		 }
		 else
		 {
		 	$var = 'Add new';
		 }
    	add_submenu_page( 'wp_list_table_class', __('Add new', 'edit_table_data'), __($var, 'edit_table_data'), 'manage_options', 'seo_automation_form', [ $this, 'edit_table_data_customers_form_page_handler' ] );
		// add_submenu_page( 'wp_list_table_class', __('Add new', 'edit_table_data'), __('Add new', 'edit_table_data'), 'manage_options', 'customers_form', [ $this, 'edit_table_data_custom_table_data_form_display' ] );

	}

	function edit_table_data_customers_form_page_handler() {
		global $wpdb;
	    $table_name = $wpdb->prefix . 'seoautomation'; // do not forget about tables prefix
	    $message = '';
	    $notice = '';
	    $default = array(
	        'id' => 0,
	        'website_url' => '',
	        'web_username' => '',
	        'web_password' => '',
	    );
	    if(isset($_REQUEST['nonce']) == NULL)
	    {
	        $empty_nonce = " ";
	    }
	    else
	    {
	        $empty_nonce = $_REQUEST['nonce']; 
	    }
	    if (wp_verify_nonce($empty_nonce, basename(__FILE__))) {
	        $item = shortcode_atts($default, $_REQUEST);
	        $item_valid = $this->edit_table_data_validate_person($item);
	        if ($item_valid === true) {
	            if ($item['id'] == 0) {
	                $result = $wpdb->insert($table_name, $item);
	                $item['id'] = $wpdb->insert_id;
	                if ($result) {
	                    $message = __('Item was successfully saved', 'edit_table_data');
	                } else {
	                    $notice = __('There was an error while saving item', 'edit_table_data');
	                }
	            } else {
	                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
	                if ($result) {
	                    $message = __('Item was successfully updated', 'edit_table_data');
	                } else {
	                    $notice = __('There was an error while updating item', 'edit_table_data');
	                }
	            }
	        } else {
	            // if $item_valid not true it contains error message(s)
	            $notice = $item_valid;
	        }
	    }
	    else {
	        // if this is not post back we load item to edit or give new one to create
	        $item = $default;
	        if (isset($_REQUEST['id'])) {
	            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
	            if (!$item) {
	                $item = $default;
	                $notice = __('Item not found', 'edit_table_data');
	            }
	        }
	    }

	    // here we adding our custom meta box
	    add_meta_box('customers_form_meta_box', 'Website Details', [ $this, 'edit_table_data_customers_form_meta_box_handler' ], 'customers', 'normal', 'default');
	    ?>
		<div class="wrap">
		    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
		    <h2><?php _e('Website Authentication', 'edit_table_data')?> <a class="add-new-h2"
		                                href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=wp_list_table_class');?>"><?php _e('back to list', 'edit_table_data')?></a>
		    </h2>
		    <?php if (!empty($notice)): ?>
		    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
		    <?php endif;?>
		    <?php if (!empty($message)): ?>
		    <div id="message" class="updated"><p><?php echo $message ?></p></div>
		    <?php endif;?>

		    <form id="form" method="POST">
		        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
		        <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
		        <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

		        <div class="metabox-holder" id="poststuff">
		            <div id="post-body">
		                <div id="post-body-content">
		                    <?php /* And here we call our custom meta box */ ?>
		                    <?php do_meta_boxes('customers', 'normal', $item); ?>
		                    <input type="submit" value="<?php _e('Save', 'edit_table_data')?>" id="submit" class="button-primary" name="submit">
		                </div>
		            </div>
		        </div>
		    </form>
		</div>
		<?php
	}

	/**
	 * Simple function that validates data and retrieve bool on success
	 * and error message(s) on error
	 *
	 * @param $item
	 * @return bool|string
	 */
	public function edit_table_data_validate_person($item)
	{
	    $messages = array();

	    if (empty($item['website_url'])) $messages[] = __('Website URL is required', 'edit_table_data');
	    if (empty($item['web_username']) ) $messages[] = __('Website Username is in wrong format', 'edit_table_data');
	    if (empty($item['web_password'])) $messages[] = __('Website Password in wrong format', 'edit_table_data');
	    //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
	    //if(!empty($item['age']) && !preg_match('/[0-9]+/', $item['age'])) $messages[] = __('Age must be number');
	    //...

	    if (empty($messages)) return true;
	    return implode('<br />', $messages);
	}
	public function edit_table_data_languages()
	{
	    load_plugin_textdomain('edit_table_data', false, dirname(plugin_basename(__FILE__)));
	}
	/**
	 * Plugin Add new page
	 */
	function edit_table_data_customers_form_meta_box_handler($item)
	{
	    ?>
		<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
		    <tbody>
			    <tr class="form-field">
			        <th valign="top" scope="row">
			            <label for="website_url"><?php _e('Website URL', 'edit_table_data')?></label>
			        </th>
			        <td>
			            <input id="website_url" name="website_url" type="url" style="width: 95%" value="<?php echo esc_attr($item['website_url'])?>" size="50" class="code" placeholder="<?php _e('Your Website URL', 'edit_table_data')?>" required>
			        </td>
			    </tr>
			    <tr class="form-field">
			        <th valign="top" scope="row">
			            <label for="web_username"><?php _e('Username', 'edit_table_data')?></label>
			        </th>
			        <td>
			            <input id="web_username" name="web_username" type="text" style="width: 95%" value="<?php echo esc_attr($item['web_username'])?>"
			                   size="50" class="code" placeholder="<?php _e('Your Website Username', 'edit_table_data')?>" required>
			        </td>
			    </tr>
			    <tr class="form-field">
			        <th valign="top" scope="row">
			            <label for="web_password"><?php _e('Password', 'edit_table_data')?></label>
			        </th>
			        <td>
			            <input id="web_password" name="web_password" type="text" style="width: 95%" value="<?php echo esc_attr($item['web_password'])?>"
			                   size="50" class="code" placeholder="<?php _e('Your Website Password', 'edit_table_data')?>" required>
			        </td>
			    </tr>
		    </tbody>
		</table>
	<?php
	}
	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page() {
		?>
		<div class="wrap">
			<h2>Website Authentication list</h2>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->customers_obj->prepare_items();
								$this->customers_obj->search_box( 'search', 'search_id' );
								$this->customers_obj->display(); ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
	<?php
	}

	/**
	 * Screen options
	 */
	public function screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => 'Customers',
			'default' => 5,
			'option'  => 'customers_per_page'
		];

		add_screen_option( $option, $args );

		$this->customers_obj = new Customers_List();
	}


	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}


add_action( 'plugins_loaded', function () {
	SEO_Tabledisplaydata::get_instance();
} );