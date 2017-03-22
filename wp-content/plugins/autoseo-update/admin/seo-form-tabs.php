<?php

require_once( SEO_AUTOMATION . 'admin/seo-table-data-display.php' );
include_once( SEO_AUTOMATION . 'admin/xmlrpc.php' );
//require_once( SEO_AUTOMATION . 'admin/class-word.php' );
class post_tabs extends Customers_List
{
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="check_box" class="mycheckbox" value="%s" />', $item['id']
			);
	}
	function get_columns() {
		$columns = [
		'cb'      => '<input type="checkbox" class="mycheckbox" />',	
		'website_url' => __( 'Website URL', 'sp' )	
		];
		return $columns;
	}

}
class SEO_Formtab extends SEO_Tabledisplaydata {
	// class instance
	static $inst;

	// customer WP_List_Table object
	public $forms_obj;
	public $content;
	// class constructor
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
		// add_action('init', [ $this,'table_load_plugin_textdomain' ] );	
		// add_action('admin_menu', [ $this,'cltd_example_admin_menu' ] );	
		add_action( 'admin_enqueue_scripts', array( $this, 'strideup_enqueue_scripts' ) );
		
	}	
	public function strideup_enqueue_scripts()
	{


		if(is_admin()){
			wp_register_script( 'seoautomation_js',  plugins_url( '../js/seoautomation_js.js', __FILE__ ), array('jquery'),'1.1', true ); 
			wp_enqueue_script( 'seoautomation_js' );
		}    
	}
	public function form_tab_display_options() {
		echo '<h1>hiiiii testing</h1>';
	}
	public function plugin_menu() {
		$tab = add_submenu_page( 'wp_list_table_class', __('Post Content', 'edit_table_data'), __('Post Content', 'edit_table_data'), 'manage_options', 'seo_post_form', [ $this, 'tab_settings' ] );
		add_submenu_page( null, __('Post Content', 'edit_table_data'), __('Post Content', 'edit_table_data'), 'manage_options', 'seo_post_form_tab2', [ $this, 'tab2_forms' ] );

		add_action( "load-$tab", [ $this, 'tab_option' ] );
	}

	public function tab_option() {

		$option = 'per_page';
		$args   = [
		'label'   => 'Customers',
		'default' => 5,
		'option'  => 'customers_per_page'
		];

		add_screen_option( $option, $args );

		$this->forms_obj = new post_tabs();
	}
	public function tab2_forms() {

		?>
		<div class="wrap">
			<h2>THIS IS TAB 2</h2>

			<h2 class="nav-tab-wrapper">
				<a class="nav-tab" href="<?php echo admin_url() ?>/admin.php?page=seo_post_form">TAB1</a>
				<a class="nav-tab nav-tab-active" href="<?php echo admin_url() ?>/admin.php?page=seo_post_form_tab2">TAB2</a>
			</h2>

		</div>   
		<form method ="POST" enctype = "multipart/form-data">	
			<p>File to be Upload:</br> <input type="file" name="fileToUpload[]" id="fileToUpload" multiple="multiple" ></p><br /> 
			<input type="submit">
		</form>

		<div id="ss">
			<p>
				ss
			</p>
		</div>
		<?php 

		global $file_tmp;
		global $file_fullname;
		global $heading;
		global $docObj;
		if (isset($_FILES['fileToUpload']['tmp_name']))  {  

			$file_count = count($_FILES['fileToUpload']['name']);

			for($i=0; $i<$file_count; $i++) {
				$file_tmp = $_FILES['fileToUpload']['tmp_name'][$i];
				$file_fullname = $_FILES['fileToUpload']['name'][$i];
				$docObj = $this->convertToText($file_tmp);
							         // echo $docText= $docObj->convertToText();
							          //echo $docObj;
			}
		}

		?>
		<div class="meta-editor">

			<?php
					 			$content = $docObj;//get_post_meta('tab2_editor_content', true );
					 			$editor = 'tab2_editor_content';
					 			$settings = array(
					 				'textarea_rows' => 8,
					 				'media_buttons' => true,
					 				);
					 			wp_editor( $content, $editor, $settings);
					 			?>
					 		</div>
					 		<?php					
					 	}
					 	public function convertToText() {
					 		global $file_tmp;
					 		global $file_fullname;
					 		global $heading;


					 		if(isset($file_tmp) && !file_exists($file_tmp)) {
					 			return "<strong>".$heading.":  "."</strong>". "File Not exists"."<br />";
					 			return "<br />";
					 		}

					 		$info = pathinfo($file_fullname);						  
					 		$explode_data = explode('.', $file_fullname);
					 		$file_ext = end($explode_data);
							//echo "dshgfsd".$file_tmp;
					 		$this->filename = $file_tmp;
					 		if($file_ext == "doc" || $file_ext == "docx" || $file_ext == "xlsx" || $file_ext == "pptx" || $file_ext == "pdf" || $file_ext == "htm")
					 		{
					 			if($file_ext == "doc") {
					 				return $this->read_doc();
					 			} elseif($file_ext == "docx") {
					 				return $this->read_docx($file_tmp);
									  /*$rt = new WordPHP();

									$text = $rt->readDocument($file_tmp);
									return $text;*/
									$doc = new Docx_reader();
									$doc->setFile($file_ext);

									$html = $doc->to_html();
									$plain_text = $doc->to_plain_text();
								}
								elseif($file_ext == "pdf") {
								    //include ( 'pdf2text.php' ) ;
                                    //$pdf   =  new PdfToText();
                                    // return $this->pdf2text($file_tmp);


								}
								elseif($file_ext == "htm") {
									return $this->post_publish();
								}

							} else {
								return "<strong>".$heading.":  "."</strong>".$file_fullname." is invalid File Type"."<br />";
								return "<br />";
							}
						}
						public function read_doc() {

							$filename = $this->filename;   
							$nl = "";      
							if ( file_exists($filename) ) {        

								if ( ($fh = fopen($filename, 'r')) !== false ) {

									$headers = fread($fh, 0xA00);

									$n1 = ( ord($headers[0x21C]) - 1 );

									$n2 = ( ( ord($headers[0x21D]) - 8 ) * 256 );

									$n3 = ( ( ord($headers[0x21E]) * 256 ) * 256 );

									$n4 = ( ( ( ord($headers[0x21F]) * 256 ) * 256 ) * 256 );

									$textLength = ($n1 + $n2 + $n3 + $n4);

									$extracted_plaintext = fread($fh,$textLength);

									$text =  nl2br($extracted_plaintext);

									$word = wp_specialchars_decode( $text, ENT_NOQUOTES );
									$string =$word;
									$asciiString = "";
									for($i = 0; $i != strlen($string); $i++)
									{

										if($string[$i] == "<")
										{
											$i++;
											$i++;               
										}
										else if($string[$i] == "/")
										{

										}
										else if($string[$i] == ">")
										{
											$asciiString .= "\n";
										}
										else
										{
											$asciiString .= "&#".ord($string[$i]).";";
										}

									}

									return  $asciiString;

								}

							}

						}



						public function tab_settings() {
							global $content; 
							?>
							<div class="wrap">
								<h2>An Example Welcome Screen</h2>

								<h2 class="nav-tab-wrapper">
									<a class="nav-tab nav-tab-active" href="<?php echo admin_url() ?>/admin.php?page=seo_post_form">TAB1</a>
									<a class="nav-tab" href="<?php echo admin_url() ?>/admin.php?page=seo_post_form_tab2">TAB2</a>
								</h2>

								<div id='sections'>
									<section>
										<?php
										$item="";
										add_meta_box('customers_tab_meta_box', 'Post Data Details', [ $this, 'post_content_data' ], 'customers', 'normal', 'default');
										?>
										<form id="tab_form" method="post" >
											<?php do_meta_boxes('customers', 'normal', $item); ?>
											<input type="hidden" id="content_id" name="content" value="<?php echo $content ?>"/>
											<input type="submit" value="<?php _e('POST', 'post_content_data')?>" id="post" class="button-primary" name="post">
											<form>
											</section>
											<section>
												<?php
												if(isset($_POST['check_box']))
												{
													$req_check = $_REQUEST['check_box'];
													$req_title = $_REQUEST['post_title']; 
													$req_content = $_REQUEST['editor_content'];				
													global $wpdb;
													$sql = "SELECT * FROM {$wpdb->prefix}seoautomation where id='$req_check'";
													$result = $wpdb->get_results( $sql, 'ARRAY_A' );
													$result_arr = $result['0'];				
													$req_url = $result_arr['website_url'];
													$req_username = $result_arr['web_username'];
													$req_password = $result_arr['web_password'];
													$this->calltomain_page($req_title,$req_content,$req_url,$req_username,$req_password);				
												}

												?>
											</section>
										</div>
									</div>
									<?php
								}
				/*function wpPostXMLRPC($title,$body,$rpcurl,$username,$password,$categories=array(1)){
				    $categories = implode(",", $categories);
				    $XML = "<title>$title</title>".
				    "<category>$categories</category>".
				    $body;
				    $params = array('','',$username,$password,$XML,1);
				    $request = xmlrpc_encode_request('blogger.newPost',$params);
				    $ch = curl_init();
				    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
				    curl_setopt($ch, CURLOPT_URL, $rpcurl);
				    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
				    curl_exec($ch);
				    curl_close($ch);
				}*/

				
				function calltomain_page($req_title,$req_content,$req_url,$req_username,$req_password) {
     //require_once("IXR_Library.php"); 
					$title = $req_title;
					$body = $req_content;
					$rpcurl = $req_url.'/xmlrpc.php';
					$username = $req_username;
					$password = $req_password;
					$category = 'blog';
					$client = new IXR_Client($rpcurl);
					$content = array(
						'title'=>$title,
						'description'=>$body,
						'post_type'=>'post',
						'category'=>$category,
						);
					$params = array(1,$username,$password,$content,true); 
					if (!$client->query('metaWeblog.newPost', $params)) { 
						die('Something went wrong - '.$client->getErrorCode().' : '.$client->getErrorMessage()); 
					}
					else {

						$result = $client->query(
							'metaWeblog.getRecentPosts',$params
							);
						$data = $client->getResponse();
						print_r($data);
						echo '<pre>'.$data[0]['link'].'</pre>';
						echo $data[0]['postid'];
			             /*$c = dksort($data, 'year');
			             print_r($c);*/         $arr = array($data[0]);
			             echo '<pre>';
			             echo json_encode($arr);
			             echo '</pre>';
			             

// // $direct=$wpdb->get_results("SELECT * FROM $tablename_insert");
//  //print_r($redirect);
// // echo "<pre>";
// 			             foreach ($arr as $value_data) {
// 			             	$arrayss_auction[] = array($value_data->year);
// 			             }
// 			             foreach ($arrayss_auction as $attr => $infos) { 
// 			             	$getting_dbvalue = $infos;
// 			             	echo "<br/>";
// 			             }
// 			             $getting_db = $getting_dbvalue;
// 			             print_r($getting_db);



			             echo "<pre> Article Posted Successfully </pre>"; 
			         }
			     /*$test = wpPostXMLRPC($title,$body,$rpcurl,$username,$password,1);
			        print_r($test);
			     $response = $this->wpPostXMLRPC($title,$body,$rpcurl,$username,$password,$categories=array(1));
			     print_r($response);*/
			     //echo phpinfo();
			 }

			 public function post_content_data( $item){
			 	global $content;
			 	$this->forms_obj->prepare_items();
								//$this->forms_obj->search_box( 'search', 'search_id' );
			 	$this->forms_obj->display();
			 	?>
			 	<div>
			 		<div id="titlediv">
			 			<div id="titlewrap">

			 				<input id="title" name="post_title" size="30" placeholder="Enter title here" value="" spellcheck="true" autocomplete="off" type="text">		 				
			 			</div>
			 			<div class="inside">
			 				<div id="edit-slug-box" class="hide-if-no-js"> </div>
			 			</div>

			 		</div>
			 	</div>
			 	<div class="meta-editor">
			 		<?php
			 		$content = get_post_meta('editor_content', true );
			 		$editor = 'editor_content';
			 		$settings = array(
			 			'textarea_rows' => 8,
			 			'media_buttons' => true,
			 			);
			 		wp_editor( $content, $editor, $settings);
			 		?>
			 	</div>
			 	<div id="edit-slug-box" class="hide-if-no-js"> </div>


			 	<?php
			 }
			 public static function get_instances() {
			 	if ( ! isset( self::$inst ) ) {
			 		self::$inst = new self();
			 	}

			 	return self::$inst;
			 }
			}

			add_action( 'plugins_loaded', function () {
				SEO_Formtab::get_instances();
			} );



