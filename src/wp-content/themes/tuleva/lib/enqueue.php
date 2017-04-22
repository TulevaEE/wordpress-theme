<?php

	/*
		Wordpress Enqueue Class
	o/

	$about_this_class = array(
			"name" => "Wordpress Enqueue Class",
			"class" => "SimplyEnqueue",
			"version" => "0.0.2",
			"github" => "https://github.com/carlrannaberg/wp-enqueue-class"
	)

	// REQUIRED PARAMS
	"handle" => "handle",
	"url" => "url/to/file.css",
			|- CSS or JS

	// OPTIONAL PARAMS
	"deps" => "handle" || "url/to/file.js" || array("handle", "url/to/file.js"),

	"ver" => "version number",
			|- will default to 0.0.0 if unset

	"usecase" => "frontend" || "backend" || "template.php" || array("home-page.php", "author.php, "backend") || "login" || "all",
			|- will default to frontend if unset

	"js_in_header" => true || false
			|- only for JS files
			|- will default to false if unset
			|- will be overridden if a dependency specifies false for this property

	"media" => "all" || "print" || screen"
			|- only for CSS files
			|- will default to all if unset

	"enqueue" => true || false || function
			|- determines if file should be enqueued
			|- function must return true or false
			|- will default to false if unset
	);

	*/


	class SimplyEnqueue {

		private $items_to_register = array(),
				$items_to_enqueue = array(),
				$instance_params = array(),
				$process = array(),
				$enqueue_hook;

		// needs proper filtering for array of usecases
		private function _is_correct_enqueue_hook($data){

			// filter out bad usecases
			if( !isset($data) || !isset($data['usecase']) ){

				return false;
			}

			// normalizing $data['usecase'] content to lowercase chars
			// - string
			if( is_string($data['usecase']) ){

				$data['usecase'] = strtolower( $data['usecase'] );
			}

			// - array
			else if( is_array($data['usecase']) ){

				$data['usecase'] = array_map('strtolower', $data['usecase']);
			}

			// function
			if (is_callable($data['usecase'])){

				return $data['usecase']();
			}

			// handle 'all' as string or in array
			if( $data['usecase'] == "all" || is_array($data['usecase']) && in_array('all', $data['usecase']) ){

				return true;
			}

			// pair code with login hooks
			switch ($this->enqueue_hook){

				case 'wp_enqueue_scripts':

					if( is_string($data['usecase']) && $data['usecase'] == 'frontend'
					||	is_array($data['usecase']) && in_array('frontend', $data['usecase'])
					){

						return true;
					}

					return false;

				break;

				case 'admin_enqueue_scripts':

					if (  is_string($data['usecase']) && $data['usecase'] == 'backend'
					||	is_array($data['usecase']) && in_array('backend', $data['usecase'])
					){
						return true;
					}

					return false;

				break;

				case 'login_enqueue_scripts':

					if (  is_string($data['usecase']) && $data['usecase'] == 'login'
					||	is_array($data['usecase']) && in_array('login', $data['usecase'])
					){
						return true;
					}

					return false;

				break;
			}

			// at this point its safe to say it doesn't match the hook
			return false;
		}

		private function _is_register_data($data){

			if ( isset($data) && isset($data['handle']) && isset($data['src']) ) {

				return true;
			}

			return false;
		}

		private function _contains_register_data($wrapper) {

			if ( !is_array($wrapper) && !is_object($wrapper) ){

				return false;
			}

			foreach ($wrapper as $data){

				if ( $this->_is_register_data($data) ){

					return true;
				}
			}

			return false;
		}

		private function _is_enqueue_data($data){

			if ( isset($data) && ( is_array($data) || is_object($data) ) && isset($data['handle']) ){

				return true;
			}

			return false;
		}

		private function _contains_enqueue_data($wrapper){

			if ( !is_array($wrapper) && !is_object($wrapper) ){

				return false;
			}

			foreach ($wrapper as $data){

				if ( $this->_is_enqueue_data($data) ){

					return true;
				}
			}

			return false;
		}

		// CHECK IF URL IS STYLESHEET OR JAVASCRIPT
		// @return string - "css" || "js" || "unknown"
		private function _get_filetype($url){

			// set default filetype
			$filetype = 'unknown';

			// if url var is the right datatype, try to get filetype
			if ( is_string($url) ) {

				$fractalUrl = explode('/', $url);

				$filename = $fractalUrl[count($fractalUrl) - 1];
				$fractalFilename = explode('.', $filename);

				$fileExtension = strtolower($fractalFilename[count($fractalFilename) - 1]);

				if (strpos($fileExtension, "js") !== false) {

					$filetype = "js";
				}

				if (strpos($fileExtension, "css") !== false) {

					$filetype = "css";
				}
			}

			return $filetype;
		}

		private function _normalize_for_registration($file){

			// error handling
			if ( !$file['handle'] || !$file['src'] ) {

				if (!$file['handle'] && !$file['src']) {

					echo "Enqueuing a file without a handle AND url? GO HOME, DEVELOPER. YOU'RE DRUNK. <br />";
					return;
				}

				if (!$file['handle'] && $file['src']) {

					echo "No handle set for '" . $file['src'] . "' <br />";
					return;
				}

				if (!$file['src'] && $file['handle']) {

					echo "No url given for '" . $file['handle'] . "' <br />";
					return;
				}
			}

			$filetype = $this->_get_filetype($file['src']);

			if ( $filetype == 'unknown') {

				echo "'" . $file['handle'] . "' doesn't seem to be a stylesheet or javascript. Doublecheck the url. <br />";
				// letting it continue through, just in case its a valid file served up in a format _get_filetype() doesn't check.
				// an example is css served as a php file
			}

			$file["filetype"] = $filetype;

			// version number
			$file['ver'] = (isset($file['ver']) ? $file['ver'] : "0.0.0");

			// usecase
			$file['usecase'] = (isset($file['usecase']) ? $file['usecase'] : "all");

			// dependencies
			$file['deps'] = (isset($file['deps']) ? $file['deps'] : array());

			$file['enqueue'] = (isset($file['enqueue']) ? $file['enqueue'] : false);

			// javascript specific vetting
			if ($filetype == "js") {

				$file['js_in_header'] = (isset($file['js_in_header']) ? $file['js_in_header'] : false);
			}

			// CSS specific vetting
			if ($filetype = "css") {

				$file['media'] = (isset($file['media']) ? $file['media'] : "all");
			}

			return $file;
		}

		private function _normalize_for_enqueue($data){

			$item_to_enqueue = array();

			$item_to_enqueue['handle'] = $data['handle'];
			$item_to_enqueue['usecase'] = (isset($data['usecase']) ? $data['usecase'] : 'frontend' );
			$item_to_enqueue['filetype'] = (isset($data['filetype']) ? $data['filetype'] : (isset($data['src']) ? $this->_get_filetype($data['src']) : "unknown") );
			$item_to_enqueue['conditional'] = isset($data['conditional']) ? $data['conditional'] : '';

			return $item_to_enqueue;
		}

		private function _add_to_register_list($data){

			$this->items_to_register[] = $this->_normalize_for_registration($data);

			if (!isset($this->process['register']) || $this->process['register'] != true){

				$this->process['register'] = true;
			}
		}

		private function _add_to_enqueue_list($data){

			if ( !$this->_is_correct_enqueue_hook($data) ){

				return;
			}

			$this->items_to_enqueue[] = $this->_normalize_for_enqueue($data);

			if (!isset($this->process['enqueue']) || $this->process['enqueue'] != true){

				$this->process['enqueue'] = true;
			}
		}

		private function _register_items(){

			foreach ($this->items_to_register as $file){

				if ( $file['filetype'] == "js") {

					wp_register_script(	$file['handle'], $file['src'], $file['deps'], $file['ver'], !$file['js_in_header'] );
				}

				if ( $file['filetype'] == "css") {

					wp_register_style( $file['handle'], $file['src'], $file['deps'], $file['ver'], $file['media'] );
				}

				if ( (isset($file['enqueue'])) && (($file['enqueue'] === true) || (is_callable($file['enqueue']) && $file['enqueue']() === true)) ){

					$this->_add_to_enqueue_list($file);
				}
			}
		}

		private function _enqueue_items(){

			foreach ($this->items_to_enqueue as $file){
				$filetype = $file['filetype'];

				if (!$filetype) {
					if ($file['src']) {
						$filetype = $this->_get_filetype($file['src']);
					} else {
						$filetype = 'unknown';
					}
				}

				if ( $filetype == "js") {

					wp_enqueue_script(  $file['handle'] );

					if (!empty($file['conditional'])) {
						wp_script_add_data( $file['handle'], 'conditional', $file['conditional'] );
					}
				}

				if ( $filetype == "css") {

					wp_enqueue_style( $file['handle'] );

					if (!empty($file['conditional'])) {
						wp_style_add_data( $file['handle'], 'conditional', $file['conditional'] );
					}
				}

				if ( $filetype == "unknown") {

					// check if handle is a style or script
					// enqueue accordingly

					// for now, fire blindly
					wp_enqueue_style( $file['handle'] );
					wp_enqueue_script(  $file['handle'] );
				}
			}
		}

		private function _file_exists($url) {
			$file_exists = false;
			$site_url = get_site_url();
			$is_url = filter_var($url, FILTER_VALIDATE_URL);
			$is_current_site_url = strpos($url, $site_url);

			if ($is_current_site_url !== false) {
				$relative_path = str_ireplace($site_url, '', $url);
				$home_path = untrailingslashit(ABSPATH);
				$file_path = $home_path . $relative_path;
				$file_exists = file_exists($file_path);
			} else if ($is_url) {
				// Probably a remote URL
				$file_exists = true;
			}

			return $file_exists;
		}

		/*
			PUBLIC FUNCTIONS
			* Accessible from outside the Class

			|- Class Instance
				|-> get_enqueue_hook
				|-> register
				|-> init_instance
		*/


		// DETERMINE APPROPRIATE ENQUEUE HOOK AND RETURN IT
		// @returns: string
		public function get_enqueue_hook() {

			if ( isset($this->enqueue_hook) && in_array(strtolower($this->enqueue_hook), array( "wp_enqueue_scripts", "admin_enqueue_scripts", "login_enqueue_scripts") )  ){

				return $this->enqueue_hook;
			}

			else {

				// check if login / register page
				if ( in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) ) {

					$this->enqueue_hook = 'login_enqueue_scripts';

				} else {

					// check if backend page
					// if not, assume frontend
					$this->enqueue_hook = ( true == is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts' );
				}

				return $this->enqueue_hook;
			}
		}

		// SET FILE(S) FOR REGISTRATION
		public function register( $params = array() ){

			// improper $params
			if ( !$this->_is_register_data($params) && !$this->_contains_register_data($params) ){

				return $this;
			}

			// $params = single enqueue file
			if ( $this->_is_register_data($params) ){

				if( !$this->_file_exists($params['src']) ) {
					echo "COULDN'T FIND THIS FILE: " . $params['src'];
				}

				$this->_add_to_register_list($params);

				echo $this->_is_register_data($params);
			}

			// $params = multiple enqueue files
			if( $this->_contains_register_data($params) ){

				foreach ($params as $data){

					if ( $this->_is_register_data($data) ){

						if( !$this->_file_exists($data['src']) ) {
							echo "COULDN'T FIND THIS FILE: " . $data['src'];
						}

						$this->_add_to_register_list($data);
					}
				}
			}

			return $this;
		}

		// ENQUEUE FILE(S)
		public function enqueue( $params = array() ){

			// improper $params
			if ( !$this->_is_enqueue_data($params) && !$this->_contains_enqueue_data($params) ){

				return $this;
			}

			// $params = single enqueue file
			if ( $this->_is_enqueue_data($params) ){

				// already registered
				if( wp_script_is($params['handle'], 'registered') ){

					$this->_add_to_enqueue_list($params);
				}

				// needs to be registered
				else if( $this->_is_register_data($params) ){

					// flag for auto enqueue
					$params['enqueue'] = true;

					$this->_add_to_register_list($params);
				}
			}

			// $params = multiple enqueue files
			if( $this->_contains_enqueue_data($params) ){

				foreach ($params as $data){

					if ( $this->_is_enqueue_data($data) ){

						// already registered
						if( wp_script_is($data['handle'], 'registered') ){

							$this->_add_to_enqueue_list($data);
						}

						// needs to be registered
						else if( $this->_is_register_data($data) ){

							// flag for auto enqueue
							$data['enqueue'] = true;

							$this->_add_to_register_list($data);
						}
					}
				}
			}

			return $this;
		}

		// PROCESS INSTANCE
		public function init_instance(){

			if (isset($this->process['register']) && $this->process['register'] == true) {

				$this->_register_items();
			}

			if (isset($this->process['enqueue']) && $this->process['enqueue'] == true) {

				$this->_enqueue_items();
			}
		}

		function __construct( $params = NULL ){
			$this->instance_params = $params;
			$this->enqueue_hook = $this->get_enqueue_hook();


			if ( !has_action($this->enqueue_hook, array($this, 'init_instance')) ){

				add_action( $this->enqueue_hook, array($this, 'init_instance') );
			}
		}
	}
?>
