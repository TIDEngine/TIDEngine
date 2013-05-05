<?php

/**
 *
 * TIDEngine core class
 * @author bogyvet
 *
 */
class Mod_TIDEngine extends System{

	/**
	 * @defgroup paths_settings TIDEngine Paths Settings
	 * @{
	 * TIDEngine different paths setting for source and cached files.
	 *
	 * If you do not change TIDEngine structure you can use default paths if not you must define your own.
	 *
	 */

	/**
	 *
	 * You can define root domain if for some reason you have problems with Javascript and/or CSS Cache Files Paths.\n
	 * If you have no problems you can leave it empty.\n
	 * @param string $domain
	 */
	public  $domain  			= 'http://localhost/TIDengine/';

	/**
	 *
	 *  Cache directory root path. If you do not change orginal TIDEngine structure,  Default : 'cache/' \n
	 *  If you have your own structure set path depending of your needs.
	 *  @param string $root_cache
	 */

	public  $root_cache  			= 'cache/';

	/**
	 *
	 * Cached Templates/Pages cache directory name. Default : 'pages/'
	 * @param string $pages_cache
	 */
	public  $pages_cache 			= 'pages/';

	/**
	 *
	 * Css cache directory name. Default :  'css/'
	 * @param string $css_cache
	 */
	public  $css_cache   			= 'css/';

	/**
	 *
	 * Javascript cache directory name. Default : 'javascript/'
	 * @param string $js_cache
	 */
	public  $js_cache    			= 'javascript/';

	/**
	 *
	 * Javascript source files directory path. Default : 'javascript/native/source/'
	 * @param string $javascript_source
	 */
	public  $javascript_source		= 'javascript/native/source/';

	/**
	 *
	 * Javascript minified files directory path. Default : 'javascript/native/minified/'
	 * @param string $javascript_minified
	 */
	public  $javascript_minified		= 'javascript/native/minified/';

	/**
	 *
	 * Javascript Frameworks source files directory path. Default : 'javascript/frameworks/'
	 * @param string $frameworks_source
	 */
	public  $frameworks_source		= 'javascript/frameworks/';

	/**
	 *
	 * CSS source files directory path. Default : 'css/'
	 * @param string $css_source
	 */
	public  $css_source 			= 'css/';

	/**
	 * @}
	 */

	/**
	 * @defgroup general_cache_settings TIDEngine General Cache Settings
	 * @{
	 * TIDEngine different General Cache Settings.
	 *
	 * Your Pages performance depends from this settings. We recomend to use Default settings for best results.\n\n
	 * But anyway you could make experiments and adjust by your needs. This settings are applied to all files.
	 *
	 */

	/**
	 *  Templates/Pages cache files unique naming type.
	 *
	 * @param string $cache_naming - you can use one of the following name possibilities:
	 * 	 - md5 - Page/Template cache name is md5 value of template name.
	 * 	 - sha1 - Page/Template cache name is sha1 value of template name.
	 * 	 - native - use native templatre names
	 */
	public  $cache_naming 			= 'md5';

	/**
	 * Cache name prefix implemented for easier data changes check unique value inside brackets.
	 *
	 * @param string:
	 * 	- Default  '(' eg. ()- full Cache separators
	 */
	public	$cache_name_pre 		= '(';

	/**
	 * Cache name sufix implemented for easier data changes check unique value inside brackets.
	 *
	 * @param string:
	 * 	- Default  '(' eg. ()- full Cache separators
	 */
	public	$cache_name_post 		= ')-';

	/**
	 * Pages cache extension in use, you can use any type of extension, because Template/Page Cache File just store XHTML code,\n
	 * there are no direct access, they are final data storag, render by TIDEngine.
	 *
	 * @param string $cache_extension
	 */
	public  $cache_extension 		= '.tpl.php';

	/**
	 * Use Server Caching. If this is enabled all Page Components will be processed depending of Settings and Cached  on Server Side.\n
	 * Until Page Components do not expire or some of Data are changed TIDEngine will serve Cached.
	 * Files for every Page Request.\n
	 *
	 * @param bool $server_caching
	 */
	public  $server_caching			= true;

	/**
	 * Use Client/Browser Caching. If this is enabled on every page request will be checked Cache existance and validity.\n\n
	 * If Cache files pass checking for individual Cache file will be set 304 Redirect - Not Modified.\n\n
	 * EG. If you have unique Javascript files for all pages on different pages access will be used same
	 * Javascript, CSS files from Browser cache if is valid if not TIDEngine will serve Server Side Cached Files.\n
	 *
	 * @param bool $client_caching
	 */
	public  $client_caching			= true;

	/**
	 * Use gzip compression on Page/Template Compress Server Side Cache Files.
	 *
	 * @param bool $gzip
	 */
	public  $gzip				= true;

	/**
	 * Set general compression level for all Cache Files. There are 1-9 - gzip compression levels.
	 * 		- fast:   1-4,
	 * 		- slower: 5-6 (but better compression),
	 *		- slow:   7-9 (up to 2x faster, best compression)
	 * On files under 300K level 4 is good enought.
	 *
	 * @param bool $compression_level
	 */
	public	$compression_level		= 4;

	/**
	 * Set Cache Life Time in seconds EG. 60 seconds x 60 minutes x 24 hours = one day cache life time.\n
	 * You can set cache lifetime in miliseconds or as string 'permanent' means.\n\n
	 * EG. 1.minute 1.hour 1.day 1.month 1.year \n
	 * @code
	  	$this->cache_lifetime = array(
						'page'=>'1.minute',
						'css'=>'1.hour',
						'javascript'=>'1.day',
						'frameworks'=>'1.month'
						);
	  @endcode\n
	 * 	Other possibility is to set one lifetime for all cache files:\n\n
	 * @code
	  	$this->cache_lifetime = '1.month';
	  @endcode\n
	 * 	or\n
	 * @code
	  	$this->cache_lifetime = 'permanent';
	  @endcode
	 *
	 * @param array $cache_lifetime
	 */
	public  $cache_lifetime  		=  array('page'=>'1.month',
											 'css'=>'6.month',
											 'javascript'=>'6.month',
											 'frameworks'=>'6.month');

	/**
	 * @}
	 */

	/**
	 * @defgroup xhtml_cache_settings TIDEngine XHTML Cache Settings
	 * @{
	 * TIDEngine different XHTML Cache Settings.
	 *
	 * Your Pages performance depends from this settings. We recomend to use Default settings for best results.\n\n
	 * But anyway you could make experiments and adjust by your needs. This settings are applied just to XHTML
	 * Page/Template Cache files code.
	 *
	 */

	/**
	 * You can use two types of XHTML Optimization. Indent XHTML code or clean whitespace and combine XHTML code in one lien.
	 *
	 * @param string $xhtml_code_operations:
	 * 		- compact - optimize XHTML code and remove whitespace complete XHTML in one line
	 * 		- indent - set XHTML code indentation,
	 * 		- false - any value no XHTML manipulation
	 */
	public  $xhtml_code_operations		= 'compact';

	/**
	 * Set default indentation space only. EG. '    '
	 *
	 * @param string $xhtml_code_indentation
	 */
	public  $xhtml_code_indentation		= '   ';

	/**
	 * @}
	 */

	/**
	 * @defgroup css_cache_settings TIDEngine CSS Cache Settings
	 * @{
	 * TIDEngine different CSS Cache Settings.
	 *
	 * Your Pages performance depends from this settings. We recomend to use Default settings for best results.\n\n
	 * But anyway you could make experiments and adjust by your needs. This settings are applied just to CSS
	 *  Cache files.
	 *
	 */

	/**
	 * Use CSS optimization. Clean CSS files and put all code in one line - size reduction.\n\n
	 * @todo add CSStidy support
	 *
	 * @param bool $optimize_css
	 */
	public $optimize_css			= true;

	/**
	 * Use gzip compression on CSS Cache files. File size reduction.
	 *
	 * @var bool $optimize_css_gz
	 */
	public $optimize_css_gz 		= true;

	/**
	 * Combine all CSS files in one unique file. CoReduces the number of HTTP requests.
	 *
	 * @param bool $compact_css
	 */
	public $compact_css 			= true;

	/**
	 * Set file name for combined file. If we combine all CSS files combined CSS file name.
	 *
	 * @todo - CSS Files versioning if we need it.
	 * @param bool $css_file_name
	 */
	public $css_file_name 			= 'master';

	/**
	 * Set default CSS extension. Look sily but we need that for Safari gzip bug.
	 * Safari acceprt default extension for gzipped files in format  .css.gz or .css.gz.css*
	 *
	 * @param string $css_ext
	 */
	public  $css_ext			= '.css';

	/**
	 * @}
	 */


	/**
	 * @defgroup js_cache_settings TIDEngine Javascript Cache Settings
	 * @{
	 * TIDEngine different Javascript Cache Settings.
	 *
	 * Your Pages performance depends from this settings. We recomend to use Default settings for best results.\n\n
	 * But anyway you could make experiments and adjust by your needs. This settings are applied just to Javascript
	 * Cache files.
	 *
	 */

	/**
	 * Use Javascript optimization. Clean Javascript files and put all code in one line - size reduction.
	 * This means we will use some of external Javascript Packers.
	 *
	 * @param bool $optimize_javascript
	 */
	public  $optimize_javascript		= true;

	/**
	 * Combine all Javascript and Javscript Frameworks files in one file. If we use this option
	 * all Javascript files will be treated as native Javascript files.\n
	 * Compress Javascript files - Reduces the number of HTTP requests.
	 *
	 * @param bool $combine_all_javascript
	 */
	public  $combine_all_javascript		= true;

	/**
	 * Combine all Javascript files in two files, one for native Javascript source files and other for Javscript Frameworks Files.\n
	 * Compress Javascript files - Reduces the number of HTTP requests.\n\n
	 * If  $combine_all_javascript are enabled this setting will have no effects.
	 *
	 * @param bool $compact_javascript
	 */
	public  $compact_javascript		= true;

	/**
	 * Use gzip compression on Javascript Cache files . Compress Javascript files - size reduction.
	 *
	 * @var bool $optimize_javascript_gz
	 */
	public  $optimize_javascript_gz 	= true;

	/**
	 * Optimize Javascript files, by using some of build in Javascript Packers. \n
	 * You must realize that every time you use Javascript Optimization you will have high server load, and long response time.\n\n
	 * Workarround for this is to create minified versions of Javascript Frameworks files. Or to create them on Localhost.\n\n
	 * By default TIDEngine checks if there are minified version of Javascript files during first time load and if they \n
	 * do not exist create them, so you will have high Server load just once during creation of minified version of \n
	 * Javascript files. When you have  minified version of Javascript files there are no high Server load.\n
	 * If you do not have Javascript changes you could just:
	 * 	- set $cache_lifetime = 'permanent'; and
	 * 	- change .httacces file lines 1 month to 1 year:\n\n
	 * 		@code
	  		  ExpiresByType application/javascript "access plus 1 month"
	 	  	  ExpiresByType application/x-javascript "access plus 1 month"
	  		@endcode
	 *
	 * @param string $packer:
	 * 	- packer - PHP 5 ported version of Dean Edwards Packer
	 * 	- jsmin  - JSmin packer PHP ported version of Dean Edwards JSmin
	 * 	- jshrink - JShrink-0.2.class.php hosted on Google Code
	 * 	- jsminplus - one more port of Dean Edwards JSmin
	 * 	- native - TIDEngine native compressor based on regular Expressions - Not finished.
	 */
	public  $packer				= 'jsmin';

	/**
	 * Set default Javascript extension. Look sily but we need that for Safari gzip bug.
	 * Safari acceprt default extension for gzipped files in format  .js.gz.js or .css.gz.css*
	 *
	 * @param string $js_ext
	 */
	public	$js_ext 			= '.js';

	/**
	 * New combined javascript file name. When we use combinig all Javascript files into one.
	 *
	 * @param string $javascript_combined
	 */
	public  $javascript_combined    	= 'combined';

	/**
	 * New combined javascript file name. When we combine native Javascript files ad one Cache file and Frameworks as second..
	 *
	 * @param string $javascript_filename
	 */
	public  $javascript_filename		= 'javascript';

	/**
	 * New combined frameworks file name. When we combine native Javascript files ad one Cache file and Frameworks as second..
	 *

	 * @param string $framework_filename
	 */
	public  $framework_filename		= 'framework';

	/**
	 * If Framework type in use.
	 *
	 * @param string $frameworks_type:
	 * 		- source	- If we use source - no minified versions of Javascript will be created.
	 * 		- minified	- If we use minified - minified versions of all Javascript will be created.
	 */
	public	$frameworks_type		= 'minified';

	/**
	 * @}
	 */

	/**
	 * @defgroup priv_settings TIDEngine Class Settings
	 * @{
	 * TIDEngine different private class parameters.
	 *
	 * No need to change anything.
	 */

	/**
	 * Shortcodes array defined for specific template
	 *
	 * @var array $shortcodes
	 */
	private $shortcodes = array();

	/**
	 * Data send to TempEngine md5 for checking for changes.
	 *
	 * @var num $data_changes_check
	 */
	private $data_changes_check;

	/**
	 * Cache file modification time.
	 *
	 * @var num $modification_time
	 */
	private $modification_time;

	/**
	 * Template file name/id.
	 *
	 * @var num $template_id
	 */
	private $template_id;

	/**
	 * Include external cofiguration file.\n
	 * If you leave empty this path, you must set settings when you init Class.
	 * If there are no defined settings default settings will be used.\n\n
	 * default path:  'TIDEngine/configuration/config.php'
	 *
	 * @param string $configuration_file
	 */
	private $configuration_file 		= '';

	/**
	 * For meta tags spec.
	 *
	 * @param array $meta_spec
	 */
	private $meta_spec 			= array('Content-Language', 'Expires', 'Pragma', 'Cache-Control', 'imagetoolbar');

	/**
	 * Use SEO Optimization Adviser/Debugger.
	 *
	 * @param bool $debug_meta
	 */
	private $debug_meta			= false;

	//	/**
	//	 * Possiblity to use Browscap native PHP get_browser() function. You must uncomment few line in display() function
	//	 * and comment TIDEngine simple Browser Detection function call.
	//	 *
	//	 * parms string $Browscap_cache
	//	 */
	//private $Browscap_cache 		= 'cache/Browscap';
	/**
	 * @}
	 */




	/**
	 * Class constructor. \n\n
	 * Detailed instructions inside Class inline comments
	 *
	 */
	public function __construct() {

		if($this->configuration_file !==''){		// Check for configuration file existance. If exist include it.

			include ($this->configuration_file);

		}

		$this->caculate_lifetime();					// Convert lifetime to seconds.

		$this->check_browser_set_extension();		// Set extension - Safari gzip bug FIX.

		$this->include_packer();					// Include external packer Class

		// If we set Template/Page cache files to be gzipped - > set extension to extension.gz.
		if($this->gzip == true){

			$this->cache_extension =  $this->cache_extension . '.gz';

		}
//		$this->include_debugger();					// Include TIDEbugger class @todo
	}

	/**
	 * Checks for Cache existance, Cache file validity and Cache expiration time.\n\n
	 * Detailed instructions inside Class inline comments
	 *
	 * @param array $_data - Data to be phrased to template shortcodes array key = SHORTCODE, array value = DATA.
	 * @param string $template_path - Path to template file
	 * @param string $template_id - Template name
	 * @param array $elements - Extra elements we need to include, default header, body and footer.
	 * @param bool $caching - Do we use caching for specific template
	 */
	public function check_cache($_data, $template_path, $template_id, $elements, $caching) {

		// Check if we set for specific template not to be cached or use default.
		if($caching !== ''){

			$this->server_caching = $caching;

		}

		// Set template file path.
		$template_file = $template_path  . $template_id . '.tpl';

		// Set md5 value of shortcodes, and all $this. We need this to check if template shortcodes or settings changes.
		// When we create cache file this value will be part of file name, eg (md5 value)-filename.tpl.php
		// So every time we check if shortcodes md5 from file name and md5 of actual shortcodes and settings are same.
		// If not cache file will be overwritten with new one.

		$this->data_changes_check = md5(serialize($_data)).md5(serialize($this));

		// We can use orginal template name for cache file name or md5, sha1 values of template name.
		if($this->cache_naming == 'md5'){

			$this->template_id = md5($template_id);

		}else if($this->cache_naming == 'sha1'){

			$this->template_id = sha1($template_id);

		}

		// Cache file path. We set global and local scope one for checking Client/Browser Cache other
		// for checking Server Cache (if cache file exist).
		$this->page_cache_path = $cache_file['page']['cache'][0] = $this->root_cache . $this->pages_cache . $this->cache_name_pre . $this->data_changes_check . $this->cache_name_post . $this->template_id . $this->cache_extension;

		// If we use Client Side Caching we must check page cache file time and header Last Modified time.
		// If-Modified-Since - determine if there are some changes. If there are create new cache file if not
		// we will use Browser cache and 304 redirect.
		if($this->client_caching){

			// Check if Page Cache file exist. If exist get modification time.
			// If not - (first time cache creation) get current time() because we will create cache file in next few steps.
			if(file_exists($this->page_cache_path)){

				$template_time = filemtime($this->page_cache_path);

			}else{

				$template_time = time();
			}

			// Get headers data to be able to check 'If-Modified-Since' header and Browser current cache.
			$headers = $this->getRequestHeaders();

			// If headers and cache file have same modification time - > Client cache is current.
			if (isset($headers['If-Modified-Since'])){

				$get_mod = strtotime($headers['If-Modified-Since']);

				// Because Server load we must calculate Cache time +/- 10 seconds.
				if($get_mod < $template_time+10 && $get_mod > $template_time-10){


					// We will just respond '304 Not Modified'and use Client/Browser Cache.
					header('Last-Modified: '.gmdate('D, d M Y H:i:s', $template_time).' GMT', true, 304);
					exit;
				}
			}

		}



			// Cache files paths for CSS, Javascript and Javascript Frameworks files.

			// Just in case that CSS is not defined.
			if(isset($_data['css'])){

				// Count number of files defined.
				$css_files_num = count($_data['css']);

				// Loop over defined files.
				for ($i = 0; $i < $css_files_num; $i++) {

					// Set cache path.
					$cache_file['css']['cache'][] 	= $this->domain .  $this->root_cache .  $this->css_cache . $_data['css'][$i] . $this->css_ext;
					$cache_file['css']['source'][] 	= $this->domain .  $this->css_source .  $_data['css'][$i] .'.css';

				}

				// If we combine CSS files in one we need one cache path.
				if($this->compact_css){
					$cache_file['css']['cache'] = '';
					$cache_file['css']['cache'][0]= $this->root_cache .  $this->css_cache . $this->css_file_name . $this->css_ext;

				}
				unset($_data['css']);
			}

			// In some cases we do not use Javascript.
			if(isset($_data['javascript'])){

				// Count number of files defined.
				$js_files_num = count($_data['javascript']);

				for ($i = 0; $i < $js_files_num; $i++) {

					// Set cache path.
					$cache_file['javascript']['cache'][]   	= $this->domain . $this->root_cache . $this->js_cache . $_data['javascript'][$i] . $this->js_ext;

					// If we use minified version of Frameworks files check if exist minified versions of native Javascript Files,
					// if not create them and use minified versions for creating Cache.
					if($this->frameworks_type == 'minified'){

						if(!file_exists( $this->domain . $this->javascript_minified . $_data['javascript'][$i] . '.js')){

							$this->create_minified($_data['javascript'][$i]);
						}

						$cache_file['javascript']['source'][]   = $this->domain . $this->javascript_minified . $_data['javascript'][$i] . '.js';

					// Use source files.
					}else{

						$cache_file['javascript']['source'][]   = $this->domain . $this->javascript_minified . $_data['javascript'][$i] . '.js';

					}

				}

				// If we combine native javascript files in one we need one cache path.
				if($this->compact_javascript){

					$cache_file['javascript']['cache'] = '';
					$cache_file['javascript']['cache'][0]  = $this->root_cache . $this->js_cache . $this->javascript_filename . $this->js_ext;

				}
				// Unset array because we do not need it.
				unset($_data['javascript']);
			}

			// In some cases we do not use Javascript Frameworks.
			if(isset($_data['frameworks'])){

				// Count number of files defined.
				$frameworks_files_num = count($_data['frameworks']);

				// Set while loop counter
				$i = 0;

				// Loop over Framework files and set source and cache files paths.
				while ($i < $frameworks_files_num) {

					// If we have multiple files in one Framework  eg. array('0'=>('scriptaculous', 'builder', 'effects'))
					// $_data['frameworks'][$i][0] first array member is also and Framework folder name,
					// so we must construct paths in that matter.
					if(is_array($_data['frameworks'][$i])){

						$j = 0;
						$fw_num = count($_data['frameworks'][$i]);

						while ($j < $fw_num) {

							$cache_file['frameworks']['source'][]  =  $this->domain . $this->frameworks_source . $this->frameworks_type . '/'. $_data['frameworks'][$i][0] . '/' . $_data['frameworks'][$i][$j] . '.js';
							$cache_file['frameworks']['cache'][]   =  $this->domain . $this->root_cache . $this->js_cache . $_data['frameworks'][$i][$j] . $this->js_ext;

							$j++;

						}

					}else{

						$cache_file['frameworks']['source'][]  =  $this->domain . $this->frameworks_source . $this->frameworks_type . '/'. $_data['frameworks'][$i] . '/' . $_data['frameworks'][$i] . '.js';
						$cache_file['frameworks']['cache'][]   =  $this->root_cache . $this->js_cache . $_data['frameworks'][$i] . $this->js_ext;

					}

					$i++;

				}

				// If we combine javscript Frameworks files in one we need one cache path.
				if($this->compact_javascript){

					$cache_file['frameworks']['cache'] = '';
					$cache_file['frameworks']['cache'][0] = $this->root_cache . $this->js_cache . $this->framework_filename .  $this->js_ext;

				}
				// Unset array because we do not need it.
				unset($_data['frameworks']);
			}

			// If we combine all Javascript files into one native Javascript and Frameworks Files.
			if($this->combine_all_javascript){

				// We must set that we will compact Javascript files, just for reason if Settings are not proper.
				// Merge native Javascript files paths with Frameworks paths and set unique Cache file path.
				$this->compact_javascript		= true;

				$cache_file['javascript']['source'] = array_merge($cache_file['frameworks']['source'], $cache_file['javascript']['source'] );

				$cache_file['javascript']['cache'][0]  = $this->root_cache . $this->js_cache . $this->javascript_combined  . $this->js_ext;
				unset($cache_file['frameworks']);


			}

			// Control Cache counter just to check if we need new cache file or not.
			$ex_count = 0;

			// Loop over cache files paths.
			foreach($cache_file as $type => $paths){

				// Count number of defined Cache Files. We need that for loop that checks for Cache Files existance.
				$number_cache = count($paths['cache']);

				for ($i = 0; $i < $number_cache; $i++) {


					// Check for cache file existance - if do not exist increase counter and set modification time to current.
					if(!file_exists($paths['cache'][$i])){

						// Set Cache File modification to current time.
						$this->modification_time[$type] = time();

						// Increacse cotrol counter.
						$ex_count++;

						// Set Control file exist value for specific Cache File to false.
						$cache_file[$type]['exist'][$i]  = false;

					// Cache File exist.
					}else{

						// Cache file exist so if we do not use permanent cache,
						// check cache expiration for every cache file. If cache file expire just increase counter.
						if($this->cache_lifetime[$type] !== 'permanent'){

							// Get file modification time.
							$this->modification_time[$type] = filemtime($paths['cache'][$i]);

							// Check if Cache expire.
							if(($this->modification_time[$type] + $this->cache_lifetime[$type]) < time()){

								// Increacse cotrol counter.
								$ex_count++;

								// Set Control file exist value for specific Cache File to false.
								$cache_file[$type]['exist'][$i]  = false;

							}else{

								// Set control type to shortcode array - we do not want to create new cache if already exist and is valid
								//  just for cache that do not exist
								$cache_file[$type]['exist'][$i]  = true;


							}
						}
					}
				}
			}

		// Merge data native and Cache.
		$cache_file = array_merge($_data, $cache_file);

		// If server caching is true. By default Template Engine always cache pages on server side $this->caching = true;
		//  You can define in function call $caching false if  you do not want to use cache for specific page.
		if($this->server_caching == true){

			// Check counter all cache files exist and valid -> show cached template.
			if($ex_count == 0){

				$this->template_id = false;
				$this->display($this->page_cache_path);

			// One or all cache files do not exists or not valid ->create new cache file.
			}else{

				$this->shortcodes($cache_file);
				$this->display($template_file, true, $elements);

			}

		// We do not use caching.
		}else{

			$this->shortcodes($cache_file);
			$this->display($template_file, false, $elements);
		}


	}

	/**
	 * Create minified version of source Javascript files
	 *
	 * @param string $file
	 */
	public function create_minified($file){

		// Create minified Javascript Files.
		$optimized = $this->js_packers(file_get_contents($this->javascript_source . $file . '.js'));
		file_put_contents($this->javascript_minified . $file . '.js', $optimized);
	}

	/**
	 * Display page and|or Create Cache file.
	 *
	 * @param string $file_path - path to template or cache file.
	 * @param bool $cache - we need to create cache file controll variable
	 * @param bool $elements - page elements.
	 */
	public function display($file_path, $cache=false, $elements=false) {

		// Set empty output data variable.
		$output_data  = '';

		// We use cached template file so just get cache file content.
		if($this->template_id == false){

			$output_data .= file_get_contents($file_path);

		}else{

			// If we have defined page elements.
			if($elements !== false){

				// Loop ver page elements array and get template data for every template file.
				foreach($elements as $file=>$path){

					// If body is not defined body template file path use $file_path.
					if($file == 'body' && empty($path)){

						$output_data .= file_get_contents($file_path);

					// If body is defined you can use it without header and footer.
					}else{

						$output_data .= file_get_contents($path . '/' . $file.'.tpl');

					}

				}

			// Get template file.
			}else{

				$output_data .= file_get_contents($file_path);

			}

			// Match shortcodes in template.
			$output_data = preg_replace(array_keys($this->shortcodes), array_values($this->shortcodes), $output_data);

			// If we have shortcodes in template that are not defined just replace them with empty space.
			$output_data = preg_replace('/{.*}/', '', $output_data);

			// Remove white space and new lines - compact XHTML output.
			if($this->xhtml_code_operations == 'compact'){

				$output_data = $this->clean_file($output_data);

			// Indent XHTML output.
			}else if($this->xhtml_code_operations == 'indent'){

				$output_data = $this->clean_html_code($output_data);

			}

		}

		// Buffering start. Check if Zlib is enabled use ob_start("ob_gzhandler"), if not use ob_start()
		if(!ob_start("ob_gzhandler")){
			ob_start();
		}
		// If we ouput gzipped Page/Template Cache files.
		if($this->gzip == true){

			header("Content-Encoding: gzip");
			header("Last-Modified: ".gmdate("D, d M Y H:i:s \G\M\T", $this->modification_time['page']), true, 200);
			header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + $this->cache_lifetime['page']));
			header("Cache-Control: public");
			header("Pragma: public");
			header("Expect:"); // Fix IE6 Content-Disposition
			header("Content-Description: Steam Inline");
			header("Connection: Keep-Alive");
			header("Content-Disposition: inline;");
			header('ETag: "'. $this->data_changes_check .'"');

		}else{

			header("Last-Modified: ".gmdate("D, d M Y H:i:s \G\M\T", $this->modification_time['page']), true, 200);
			header("Content-Encoding: x-gzip");
			header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + $this->cache_lifetime['page']));
			header("Cache-Control: public");
			header("Pragma: public");
			header("Expect:"); // Fix IE6 Content-Disposition
			header("Connection: Keep-Alive");
			header('ETag: "'. $this->data_changes_check .'"');

		}

		// Output template file content.
		echo  $output_data;

		// If we want to create cache.
		if($this->server_caching == true && $this->template_id !== false){

			file_put_contents($this->page_cache_path , $output_data);

		}
		// Buffering end, clean. Takes to much time.
		//ob_end_flush();

	}

	/**
	 * shortcodes($data) - Set shortcodes for template
	 *
	 * @param array $data - defined shortcodes that must be phrased to template
	 */
	public function shortcodes($data) {

		// Set uppercase all shortcodes -  we use hardcoded.
		$data = array_change_key_case($data, CASE_UPPER);

		// Loop over $data array.
		foreach($data as $shortcode=>$content){

			// Check for specific types of shortcodes depending of their array key.
			switch($shortcode){
				// CSS files.
				case 'CSS':
					$this->css_data($content);
					break;

				// Javascript files.
				case 'JAVASCRIPT':
					$this->javascript_data($content, 'JAVASCRIPT');
					break;

				// Same Javascript file. For further development separated from regular JS files.
				case 'FRAMEWORKS':
					$this->javascript_data($content, 'FRAMEWORKS');
					break;

				// Meta data .
				case 'META':
					$this->meta_data($content);
					break;

				// In all other cases. Template Shortcodes.
				default:

					// One level array shortcodes.
					if(!is_array($content)){

						$this->shortcodes["'{{$shortcode}}'"] = $content;

					// Multi level array shortcodes.
					}else{

						// Loop because we have more child elements.
						foreach($content as $child_shortcode=>$child_content){

							// One level array shortcodes.
							if(!is_array($child_content)){

								$this->shortcodes["'{{$shortcode}.{$child_shortcode}}'"] = $child_content;

							// Multi level array - Only three levels supported.
							}else{

								foreach($child_content as $c_shortcode=>$c_content){

									$this->shortcodes["'{{$shortcode}.{$child_shortcode}.{$c_shortcode}}'"] = $c_content;

								}
							}
						}
					}

			}
		}

	}

	/**
	 * Set CSS head data.
	 * CSS files paths
	 *
	 * @param array $data - CSS files paths
	 */
	public function css_data($data) {

		// Empty data holder.
		$css = '';

		// Count number of CSS cache files.
		$css_files = count($data['cache']);

		// Loop over Cache files array.
		foreach ($data['cache'] as $key => $value) {

			// If Server Side CSS cache file already exist.
			if($data['exist'][$key]){

				// Set only link and unset data array for specific cache file.
				$css .= '<link href="'. $value.'" rel="stylesheet" type="text/css" />'."\n";

				// Unset data we do not need anymore.
				unset($data['source'][$key]);
				unset($data['cache'][$key]);

				// Control var decrease number of cache files because one exist.
				$css_files--;

			}
		}

		// Reset array index and unset exist - we do not need it anymore.
		unset($data['exist']);
		$data['source'] = array_values($data['source']);
		$data['cache'] = array_values($data['cache']);

		// If some of Server Side CSS cache files do not exist create them, set link and concat it with files that exists.
		if($css_files > 0){

			$css .= $this->optimize_css($data);

		}

		// Set CSS shortcode.
		$this->shortcodes["'{CSS}'"]  = $css;


	}

	/**
	 * Optimize css files
	 *
	 * @param string $files - CSS file content
	 */
	public function optimize_css($files) {

		// get number of CSS files
		$files_num = count($files['source']);

		// Empty data holder.
		$css = '';

		// Check if we use CSS FIles combine setting.
		if($this->compact_css == true){

			$combined_file = '';


			// Loop over css files paths -> get files data and combine.
			/** @todo Add CSSTidy support and some other Libaries */
			for ($i = 0; $i < $files_num; $i++) {

				$combined_file .= file_get_contents($files['source'][$i]);

			}

			// Oprimize CSS Code.
			if($this->optimize_css == true){

				$combined_file = $this->clean_file($combined_file);

			}

			// Gzip CSS Code.
			if($this->optimize_css_gz == true){

				$combined_file = $this->gzip_data($combined_file);
			}

			// Create CSS Cache File.
			file_put_contents($files['cache'][0], $combined_file);

			// Set Link.
			$css .= '<link href="'. $files['cache'][0].'" rel="stylesheet" type="text/css" />'."\n";

		}else{

			// Empty cache paths array.
			$cache_path = array();

			// Loop over css files paths.
			for ($i = 0; $i < $files_num; $i++) {

				// get separate files content.
				$combined_file = file_get_contents($files['source'][$i]);

				// Oprimize CSS Code.
				if($this->optimize_css == true){

					$combined_file = $this->clean_file($combined_file);

				}

				// Gzip CSS Code.
				if($this->optimize_css_gz == true){

					$combined_file = $this->gzip_data($combined_file);

				}

				// Create CSS Cache File.
				file_put_contents($files['cache'][$i], $combined_file);

				// Set Link.
				$css .= '<link href="'. $files['cache'][$i].'" rel="stylesheet" type="text/css" />'."\n";
			}

		}

		return $css;
	}

	/**
	 * Set Javascript/Javascript Frameworks
	 * @param array $data - Javascript files
	 * @param string $type - frameworks or regular Javascript files
	 */
	public function javascript_data($data, $type) {

		// Empty data holder.
		$js = '';

		// Count number of Javascript cache files.
		$js_files = count($data['cache']);

		// Loop over Cache files array.
		foreach ($data['cache'] as $key => $value) {

			// If Server Side Javascript cache file already exist.
			if($data['exist'][$key]){

				// Set only link and unset data array for specific cache file.
				$js .= '<script type="text/javascript" src="' . $value . '"></script>'."\n";

				unset($data['source'][$key]);
				unset($data['cache'][$key]);

				// Control var decrease holds number of cache files.
				$js_files--;

			}
		}
		// Reset array index and unset exist - we do not need it anymore.
		unset($data['exist']);
		$data['source'] = array_values($data['source']);
		$data['cache'] = array_values($data['cache']);

		// If some of Server Side Javascript cache files do not exist create them, set link and concat it with files that exists.
		if($js_files > 0){

			$js .= $this->optimize_javascript($data, $type);

		}

		// Set Javascript shortcode.
		$this->shortcodes["'{{$type}}'"]  = $js;
	}



	/**
	 *
	 * Optimize Javascript files
	 * @param array $files
	 * @param string $type
	 * @return string $js - Links
	 */
	public function optimize_javascript($files, $type) {

		// Count number of Javascript cache files.
		$files_num = count($files['source']);

		// Empty data holder.
		$js = '';

		// Compact Javascript enabled.
		if($this->compact_javascript == true){

			$combined_file = '';


			// Loop over css files paths -> optimize and combine files data.
			for ($i = 0; $i < $files_num; $i++) {

				$combined_file .= file_get_contents($files['source'][$i]);

			}

			// We have multiple condition here not best solution but temporary
			if($this->optimize_javascript == true && $this->packer !== 'native' && $type !== 'FRAMEWORKS' && $this->frameworks_type !== 'minified'){

				$combined_file = $this->js_packers($combined_file);

			}

			// Gzip Javascript enabled.
			if($this->optimize_javascript_gz == true){

				$combined_file = $this->gzip_data($combined_file);
			}

			file_put_contents($files['cache'][0], $combined_file);
			$js .=  '<script type="text/javascript" src="'. $files['cache'][0].'"></script>'."\n";

		// Compact Javascript disabled.
		}else{

			$cache_path = array();

			// Loop over css files paths
			for ($i = 0; $i < $files_num; $i++) {

				// Get file data.
				$combined_file = file_get_contents($files['source'][$i]);

				// Optimize File Content.
				if($this->optimize_javascript == true && $this->packer !== 'native'){

					$combined_file = $this->js_packers($combined_file);

				}

				// Gzip file content.
				if($this->optimize_javascript_gz == true){

					$combined_file = $this->gzip_data($combined_file);
				}

				file_put_contents($files['cache'][$i], $combined_file);
				$js .=  '<script type="text/javascript" src="'. $files['cache'][$i].'"></script>'."\n";
			}

		}
		return $js;
	}

	/**
	 * Optimize Javascript data with different Packers.
	 *
	 * @param string $file_content
	 * @return string $optimized_file
	 */
	public function js_packers($file_content){

		// Set timeout
		set_time_limit(2500);

		switch($this->packer){
			case 'packer':

				$optimized_file = $this->Packer($file_content);

				break;
			case 'jsmin':

				$optimized_file = $this->JSmin($file_content);

				break;
			case 'jshrink':

				$optimized_file = $this->JShrink($file_content);

				break;

			case 'jsminplus':

				$optimized_file = $this->JSminplus($file_content);

				break;

//		   case 'native':
//
//				$optimized_file = file_get_contents($files[$i]);
//
//			break;

		}


		return $optimized_file;

	}
	/**
	 * Include  SEO Optimization Adviser/Debugger Class.
	 * @todo Use SEO Optimization Adviser/Debugger.
	 *
	 */
	public function include_debugger(){
		if($this->debug_meta){

			require ('TIDEngine/core/TIDEbugger.php');
			$this->TIDEbugger = new TIDEbugger;
		}

	}

	/**
	 * Include defined packer Class in use.
	 *
	 * @todo Native Class packer.
	 */
	public function include_packer(){
		switch($this->packer){
			case 'packer':

				require ('TIDEngine/vendor/JavaScriptPacker/JavaScriptPacker.php');

				break;
			case 'jsmin':

				require ('TIDEngine/vendor/JSMin/JSMin.php');

				break;
			case 'jshrink':

				require ('TIDEngine/vendor/JShrink/JShrink.php');

				break;

			case 'jsminplus':

				require ('TIDEngine/vendor/JSMinPlus/JSMinPlus.php');

				break;
		}

	}

	/**
	 *
	 * Packer - PHP 5 ported version of Dean Edwards Packer
	 *
	 * @param string $js_file
	 */
	public function Packer($js_file){

		$packer = new JavaScriptPacker($js_file, 'Normal', true, false);
		return $packer->pack();

	}

	/**
	 *
	 * JSmin -  JSmin packer PHP ported version of Dean Edwards JS
	 * @param string $js_file
	 */
	public function JSmin($js_file){


		return JSMin::minify($js_file);


	}

	/**
	 *
	 * JShrink -   JShrink-0.2.class.php hosted on Google Code
	 *
	 * @param string $js_file
	 */
	public function JShrink($js_file){


		return JShrink::minify($js_file);

	}

	/**
	 *
	 * JSminPlus -  one more port of Dean Edwards JSmin
	 *
	 * @param string $js_file
	 */
	public function JSminplus($js_file){


		return JSMinPlus::minify($js_file);

	}

	/**
	 * If enabled gzip cache files
	 *
	 * @param string $data
	 */
	public function gzip_data($data) {

		// Check if zlib extension is loaded.
		if(extension_loaded('zlib')) {

			// Encode combined and optimized css files. gzdeflate
			$gzipped_file = gzencode($data, $this->compression_level);

		}

		return $gzipped_file;

	}

	/**
	 * Save cache files
	 *
	 * @param string $data 		- file data
	 * @param string $path		- Cache File Path
	 * @param bool $modifiers	- overwrite, append data to file.
	 */
	public function save_data($data, $path, $modifiers=false) {

		if(!$modifiers){

			file_put_contents($path, $data);

		}else{

			file_put_contents($path, $data,  FILE_APPEND | LOCK_EX);

		}
	}

	/**
	 * Set head Page Title and Meta Tags.
	 *
	 * @param array $data
	 */
	public function meta_data($data) {

		$meta_tags = '';

		// Loop and construct array that will be delivered to construct Smarty Constants.
		foreach ($data as $element=>$meta){

			// If element is title assign Smarty because that is single element.
			if($element == 'title'){

				$this->shortcodes["'{PAGE_TITLE}'"] = $meta;

			// Check for special http-equiv meta elements if exist in array.
			}else if ( in_array($element, $this->meta_spec)){

				$meta_tags .= '<meta http-equiv="'.$element.'" content="'.$meta.'" />';

			// All other meta tags.
			}else{

				$meta_tags .= '<meta name="'.$element.'" content="'.$meta.'" />';

			}

		}

		// Set Meta shortcodes.
		$this->shortcodes["'{META}'"]  = $meta_tags;

		// Use Meta Data Debugger
		/** @todo $this->debug_meta will be part of TIDEbugger Class*/
//		if($this->debug_meta){
//
//			$this->shortcodes["'{DEBUG_META}'"] = $this->TIDEbugger->debug_meta_data($data);
//
//		}

	}

	/**
	 * Function to seperate multiple tags one line. Credits to orginal author.
	 *
	 * @param string $fixthistext
	 */
	public function fix_newlines_for_clean_html($fixthistext){

		// Explode data to array on every new line.
		$fixthistext_array = explode("\n", $fixthistext);

		// Loop and remove empty lines.
		foreach ($fixthistext_array as $unfixedtextkey => $unfixedtextvalue){

			if (!preg_match("/^(\s)*$/", $unfixedtextvalue)){

				$fixedtextvalue = preg_replace("/>(\s|\t)*</U", ">\n<", $unfixedtextvalue);
				$fixedtext_array[$unfixedtextkey] = $fixedtextvalue;

			}
		}

		return implode("\n", $fixedtext_array);

	}

	/**
	 * Indent XHTML output code  $this->xhtml_code_indentation. Credits to orginal author.
	 *
	 * @param string $uncleanhtml
	 * @return string implode("\n", $cleanhtml_array)
	 */
	public function clean_html_code($uncleanhtml){

		// Set indentation.
		$indent = $this->xhtml_code_indentation;

		// Uses previous function to seperate tags.
		$fixed_uncleanhtml = $this->fix_newlines_for_clean_html($uncleanhtml);

		$uncleanhtml_array = explode("\n", $fixed_uncleanhtml);

		// Sets no indentation.
		$indentlevel = 0;

		foreach ($uncleanhtml_array as $uncleanhtml_key => $currentuncleanhtml){

			// Removes all indentation.
			$currentuncleanhtml = preg_replace("/\t+/", "", $currentuncleanhtml);
			$currentuncleanhtml = preg_replace("/^\s+/", "", $currentuncleanhtml);

			$replaceindent = "";

			// Sets the indentation from current indentlevel.
			for ($o = 0; $o < $indentlevel; $o++){

				$replaceindent .= $this->xhtml_code_indentation;
			}

			// If self-closing tag, simply apply indent.
			if (preg_match("/<(.+)\/>/", $currentuncleanhtml)){

				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;

			// If doctype declaration, simply apply indent.
			}else if (preg_match("/<!(.*)>/", $currentuncleanhtml)){

				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;

			// If opening AND closing tag on same line, simply apply indent.
			}else if (preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && preg_match("/<\/(.*)>/", $currentuncleanhtml)){

				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;

			// If closing HTML tag or closing JavaScript clams, decrease indentation and then apply the new level.
			}else if (preg_match("/<\/(.*)>/", $currentuncleanhtml) || preg_match("/^(\s|\t)*\}{1}(\s|\t)*$/", $currentuncleanhtml)){

				$indentlevel--;
				$replaceindent = "";

				for ($o = 0; $o < $indentlevel; $o++){
					$replaceindent .= $this->xhtml_code_indentation;
				}

				//  Fix for textarea whitespace and in my opinion nicer looking script tags.
				if($currentuncleanhtml == '</textarea>' || $currentuncleanhtml == '</script>'){

					$cleanhtml_array[$uncleanhtml_key] = $cleanhtml_array[($uncleanhtml_key - 1)] . $currentuncleanhtml;
					unset($cleanhtml_array[($uncleanhtml_key - 1)]);

				}else{

					$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;

				}

			// If opening HTML tag AND not a stand-alone tag, or opening JavaScript clams, increase indentation and then apply new level.
			}else if ((preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && !preg_match("/<(link|meta|base|br|img|hr)(.*)>/", $currentuncleanhtml)) || preg_match("/^(\s|\t)*\{{1}(\s|\t)*$/", $currentuncleanhtml)){

				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;

				$indentlevel++;
				$replaceindent = "";

				for ($o = 0; $o < $indentlevel; $o++){
					$replaceindent .= $this->xhtml_code_indentation;
				}

			}else{

				// Else, only apply indentation.
				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;}
		}

		// Return single string seperated by newline.
		return implode("\n", $cleanhtml_array);
	}

	/**
	 * Manualy clear cache.
	 *
	 * @param string $template_id
	 */
	public function clear_cache($template_id=false){


		// When we send $template_id value false - > clear all cache.
		if($template_id == false){

			array_map( "unlink", glob(  $this->domain . $this->root_cache . $this->pages_cache . '*' . $this->cache_extension ) );

		// Clear specific cache file.
		}else{

			// We can use orginal template name for cache file name, md5 or sha1 value of template name.
			if($this->cache_naming == 'md5'){

				$template_id = md5($template_id);

			}else if($this->cache_naming == 'sha1'){

				$template_id = sha1($template_id);

			}

			// Delete cache file.
			array_map( "unlink", glob(   $this->domain . $this->root_cache . $this->pages_cache . '*-' . $template_id . $this->cache_extension ) );

		}

	}

	/** Remove white space, comments etc.*/
	public function clean_file($content) {

		$content = preg_replace('/^\s+|\n|\r|\s+$/m', '', $content);
		$content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
		$content = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $content);
		$content = preg_replace(array('(( )+{)','({( )+)'), '{', $content);
		$content = preg_replace(array('(( )+})','(}( )+)','(;( )*})'), '}', $content);
		$content = preg_replace(array('(;( )+)','(( )+;)'), ';', $content);
		$content = preg_replace(array('(:( )+)','(( )+:)'), ':', $content);
		$content = preg_replace('/(\s|)\,(\s|)/', ',', $content);

		return $content;

	}

	/**
	 *
	 * Code taken php.org and slitly adapted. Lightweight alternative for Browscap.
	 * @param string|null $agent - string to check if we have access by defined agent
	 */
	public function browser_info($agent=null) {

		// Declare known browsers to look for.
		$known = array('msie', 'firefox', 'safari', 'webkit', 'opera', 'netscape','konqueror', 'gecko', 'chrome');

		// Clean up agent and build regex that matches phrases for known browsers
		// (e.g. "Firefox/2.0" or "MSIE 6.0" (This only matches the major and minor
		// version numbers.  E.g. "2.0.0.6" is parsed as simply "2.0"
		$agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);
		$pattern = '#(?<browser>' . join('|', $known) .')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';

		// Find all phrases (or return empty array if none found).
		if (!preg_match_all($pattern, $agent, $matches)) return array();

		// Since some UAs have more than one phrase (e.g Firefox has a Gecko phrase,
		// Opera 7,8 have a MSIE phrase), use the last one found (the right-most one
		// in the UA).  That's usually the most correct.
		// Fix for Chrome has 3 parts

		$i = count($matches['browser'])-1;

		if($i == 2){

			$i=1;

		}

		return array($matches['browser'][$i], $matches['version'][$i]);

	}

	/** Get Client Browser headers data. */
	public function getRequestHeaders() {

		// If Apache function is enabled.
		if (function_exists("apache_request_headers")) {

			// And if we get data from functiuon.
			if($headers = apache_request_headers()) {

				// Return data.
				return $headers;

			}
		}

		// Set empty header array.
		$headers = array();

		// Get the IF_MODIFIED_SINCE header as Server variable.
		if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {

			$headers['If-Modified-Since'] = $_SERVER['HTTP_IF_MODIFIED_SINCE'];

		}

		return $headers;

	}

	/**
	 *
	 * Calculate Cache Life Time in miliseconds
	 */
	public function caculate_lifetime(){

		$d_time = $this->cache_lifetime;

		// Unset.
		unset($this->cache_lifetime);

		// Cache files types.
		$lifetime = array('page', 'css', 'javascript', 'frameworks');

		// Time values.
		$time_def = array('second'=>'1',
						  'minute'=>'60',
						  'hour'=>'3600',
						  'day'=>'86400',
						  'month'=>'2592000',
						  'year'=>'946080000');

		// Defined just one life time value for all cache files.
		if(!is_array($d_time)){

			// If we match format EG. 1.month.
			if(preg_match('/([\d]+)\.([\w]+)/', $d_time, $def_time)){

				$calc_lifetime = ($def_time[1]*$time_def[$def_time[2]]);

			// Cache life time is set to permanent.
			}else{

				$calc_lifetime = $d_time;

			}

			// Loop and set life time for all cache files types.
			foreach($lifetime as $key => $value){

				$this->cache_lifetime[$value] = $calc_lifetime;


			}

		// If we have defined cache life time values just convert them to miliseconds.
		}else{

			foreach($d_time as $type =>$life){

				preg_match('/([\d]+)\.([\w]+)/', $life, $def_time);

				$this->cache_lifetime[$type] = ($def_time[1]*$time_def[$def_time[2]]) ;

			}

		}
	}

	/**  Workarround for Safari problem with gzipped content we need other type of compressed cache files extensions.
	 *  As some hosting companies do not support get_browser function I was thinking that best solution is native
	 *  Browscap class - first time load this class will update all data in cache file.
	 *  eg. Other browsers .css.gz and .js.gz
	 *      Safari css.gz.css and js.gz.js
	 *      - infact this is not strict rule you can set any kind of extension just not to be .gz
	 *        eg. js.gz.xyz and css.gz.xyz - this will work without problem.
	 *
	 *      Uncomment this line :
	 *
	 *      $check_browser = new Browscap($this->Browscap_cache);
	 *      $browser = $check_browser->getBrowser(null, false);
	 *      $this->browser = $browser->Browser;
	 *
	 *      and
	 *
	 *      $this->Browscap_cache =  $this->root_cache . 'Browscap';
	 *
	 *      also comment this two lines of code:
	 *
	 *      $browser = $this->browser_info();
	 *      $this->browser = ucfirst($browser[0]);
	 */
	public function check_browser_set_extension(){

		// Get browser name.
		$browser = $this->browser_info();
		$this->browser = $browser[0];

		// Set extensions for compressed cache.
		if($this->optimize_javascript_gz == true ){

			// Safari specific extensions.
			if($this->browser == 'safari'){

				$this->js_ext = $this->js_ext . '.gz.js';

			// Other browser extensions.
			}else{

				$this->js_ext =  $this->js_ext . '.gz';

			}
		}

		// CSS cached file extension.
		if(	$this->optimize_css_gz == true){

			// Safari specific extensions.
			if($this->browser == 'safari'){

				$this->css_ext = $this->css_ext . '.gz.css';

			// Other browser extensions.
			}else{

				$this->css_ext = $this->css_ext . '.gz';

			}
		}
	}

}
