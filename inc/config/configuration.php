<?php

/**
 *
 * Define default TIDEngine paths
 *
 *
 * @package SYS - 				Framework Default Directory Path.
 * @package CONFIG  - 			Framework Configuration files Directory Path.
 * @package CORE - 				Framework Core Libaries Directory Path.
 * @package MODULES - 			Framework Modules Directory Path.
 * @package HELPERS - 			Framework Helpers Directory Path.
 * @package DOCS - 				Framework Documentation Directory Path.
 * @package API - 				Framework API Documentation Directory Path.
 * @package HELP - 				Framework Help Documentation Directory Path.
 * @package LANGUAGE -  		Framework Documentation Language in use.
 */
define('APP', 'access/');
define('ADMIN_DIR', 'admin');
define('PUBLIC_DIR', 'public');
define('ADM', APP . ADMIN_DIR . '/');
define('ADM_CONTROLLER', APP . ADMIN_DIR . '/controller/');
define('ADM_MODEL', APP . ADMIN_DIR . '/model/');
define('ADM_VIEW', APP . ADMIN_DIR . '/view/');
define('PUB', APP . PUBLIC_DIR . '/');
define('PUB_CONTROLLER', APP . PUBLIC_DIR . '/controller/');
define('PUB_MODEL', APP . PUBLIC_DIR . '/model/');
define('PUB_VIEW', APP . PUBLIC_DIR . '/view/');

define('INC', 'inc/');
define('CONFIG', INC . 'config/');


define('SYS', 'framework/');
define('CORE', SYS . 'core/');
define('MODULES', SYS . 'modules/');
define('HELPERS', SYS . 'helpers/');
define('VENDOR', SYS . 'vendor/');
define('PLUGIN', INC . 'plugins/');
define('STRUCTURE', INC . 'structure/');


define('SCRIPT_VENDOR', INC . 'vendor/');
define('LANG', INC . 'language/');
define('LANG_ACTIVE', 'en');
define('DOCS', SYS . 'documents/');
define('API', DOCS . 'api/');
define('HELP', DOCS . 'help/');




/**
 * Define all other settings
 *
 * @package PHP_MIN_VERSION -   Framework Minimal Version in use.
 * @package USE_GET -  			Framework use get action in forms.
 * @package ALLOWED_URL_CHARS - Framework alowed chars in address.
 * @package URL_SEPARATOR -		Framework default url separator.
 * @package NICE_URL -			Framework use frienly URL's use with Mod_rewrite.
 */
define('PHP_MIN', '5.2.0');
define('USE_GET', false);
define('ALLOWED_URL_CHARS', 'a-z0-9_\-');
define('URL_SEPARATOR', '/');
define('NICE_URL', false);
define('DEBUG', true);
define('CMS', 'version 1.0');


define('HOME_REDIRECT', 'home');/** home|error - Redirect error pages to home page */

define('CACHING_CLIENT', false);
define('CACHING_SERVER', false);
define('CACHE', 'cache/');/** Default cache folsder */
define('CACHE_BROWSCAP', CACHE . 'browscap');
define('CACHE_PAGES', '86400');/** In seconds 1 month = 60 seconds * 60 minutes * 24 hours *30 days */
define('CACHE_SCRIPTS', '155552000');

define('CACHE_GZIP', false);
define('CACHE_GZIP_LEVEL', '4');
define('CACHE_FILENAME', 'adler32');/** md5|sha1|adler32 - cache file name */
define('CACHE_EXT', 'cache');/** Any type of extension */
define('PACKERS', true);
define('COMBINE_CSS', true);

define('COMBINED_CSS_NAME', 'combined');
define('COMBINE_JS', true);
define('COMBINED_JS_NAME', 'combined');
define('CSS_OPTIMIZER', 'CSSminyui');/** @todo - add compressor check new cache, baseclean|CSSCompressor|CSSTidy|CSSCrush|CSSmin|CSSminyui|YUICompressor */
define('JS_OPTIMIZER', 'JSMinPlus');/** JavaScriptPacker|JShrink|JSMinPlus|JSMin|ClosureCompiler|JSminimizer|YUICompressor */
define('HTML_CODE', 'indent');   /* * compact|indent */

//define('SITE_THEME', 'default');
define('DEFAULT_THEME', INC . 'core/theme');
define('THEME_PATH', INC . 'themes');
define('DEFAULT_HEAD', SITE_ROOT . 'inc/core/theme/elements/head.tpl');
define('DEFAULT_FOOTER', SITE_ROOT . 'inc/core/theme/elements/footer.tpl');
define('CORE_CSS', INC . 'core/css_core/');
define('CORE_JS', INC . 'core/js_core/');
define('CORE_IMAGES', INC . 'core/images/');

/** DATABASE PARAMETERS */
define('DB_CONFIG', 'defined');         /** constants|php array|json|xml */
define('DB_CONFIG_PATH', '');    /** define|php array|xml|json */



