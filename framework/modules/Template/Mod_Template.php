<?php

/**
 *
 */
class Mod_Template extends System{

    public $doctype = array(
        '1' => '<!DOCTYPE html>',
        '2' => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
        '3' => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
        '4' => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">',
        '5' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
        '6' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
        '7' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
        '8' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'
    );


    /**
     * Class Constructor
     */
    public function __construct() {
        $this->extension = array('css' => 'css', 'js' => 'js', 'page' => CACHE_EXT);
        $this->set_extension();     /*!< Set extension depending browser access Safari gzip bug */
    }

    /**
     * generate_page - Template Class entery method
     *
     * @param array $meta_data
     * @param array $page_data
     * @param string $template_path
     * @param string $template_name
     * @param array $elements
     * @param array $css_paths
     * @param array $js_paths
     */
    public function generate_page($meta_data, $page_data, $theme, $template_name, $elements = false, $css_paths = false, $js_paths = false) {

        $shortcodes_scripts = array();          /*!< Empty array to hold shortcodes */

        /** If is not defined head and footer use default elements */
        if (!$elements || count($elements['head']) == 0) {

            if (count($elemets['head']) == 0) {  /*!<If head element template is not defined use default head template. */
                $elements['head'] = DEFAULT_HEAD;
            }

            if (count($elemets['footer']) == 0) { /*!<If footer element template is not defined use default footer template. */
                $elements['footer'] = DEFAULT_FOOTER;
            }
        }

        /** If we use default site theme eg. build in we have build in templates  If not we check installed theme and use this template. */
        if ($theme == 'default') {

            $template_path = DEFAULT_THEME . '/' . str_replace('-', '/', $template_name) . '.tpl';

        } else {

            $template_path = THEME_PATH . '/' . str_replace('-', '/', $template_name) . '.tpl';
        }

        /** We can or not use cache defined in configuration.php */
        if (CACHING_SERVER) {

             /** If we have CSS files we will process them */
            if ($css_paths) {
                /** Create CSS cache files pathe and check for existance, validity and timestamp */
                $css = $this->scripts_files($css_paths, 'css');
            }

            /**  If we have JS files we will process them.  */
            if ($js_paths) {
                /** Check if paths are defined
                  * We can use javascript files in head and footer selection. Depending of that we must split cache links in
                  * two selections head and footer
                 */
                if (isset($js_paths['head']) && isset($js_paths['footer'])) {
                    /** Create JS head selection cache files paths and check for existance, validity and timestamp */
                    $js_data['head'] = $this->scripts_files($js_paths['head'], 'js', 'head');
                    /** Create JS footer selection cache files paths and check for existance, validity and timestamp */
                    $js_data['footer'] = $this->scripts_files($js_paths['footer'], 'js', 'footer');

                    $js['head'] = $js_data['head'][0];
                    $js['footer'] = $js_data['footer'][0];
                    /** Create specific mark for Files. Used to check page cache validity.
                     * Page cache path is created based on multiple elements and for CSS and JS files are timestamp.
                     */
                    $time_js = $js_data['head'][1] . $js_data['footer'][1];

                } else {

                    $js['head'] = $this->scripts_files($js_paths, 'js');

                    $time_js = $js['head'][1];
                }
            }

            /** Check cache files existance and if exist check their time validity */
            $page = $this->page_files($meta_data, $page_data, $template_path, $template_name, $elements, $css['cache_path'], $time_js);

            /** If client caching is enabled in configuration.php check headers
             * and if is valid cache send header 301 and use client cache */
            if (CACHING_CLIENT) {
                /** Check Browser headers */
                $headers = $this->request_headers();
                /** We need just one header If-Modified-Since and extract time from that value. */
                if (isset($headers['If-Modified-Since'])) {

                    $get_time = strtotime($headers['If-Modified-Since']);

                    /** Because Server load we must calculate Cache time +/- 10 seconds. */
                    if ($get_time < $page['cache_time'] + 10 && $get_time > $page['cache_time'] - 10) {

                        /** We will just respond '304 Not Modified'and use Client/Browser Cache. */
                        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $page['cache_time']) . ' GMT', true, 304);
                        exit;
                    }
                }
            }

            /** If we have CSS files we will process them */
            if ($css_paths) {
                /** If we use cache (css and js) we will create optimized cache files and return html link to cache files
                 * If not we will just get link to native script files. */
                $shortcodes_scripts["{css}"] = $this->css_optimize($css[0], $css_paths);
            }

            /** If we have JAVASCRIPT files we will process them */
            if ($js_paths) {

                /** Check if paths are defined
                  * We can use javascript files in head and footer selection. Depending of that we must split cache links in
                  * two selections head and footer
                 */
                if (isset($js_paths['head'])) {

                /** If we use cache (css and js) we will create optimized cache files and return html link to cache files
                 * If not we will just get link to native script files. */
                    $shortcodes_scripts["{head.js}"] = $this->js_optimize($js['head'], $js_paths['head']);

                }
                 if (isset($js_paths['footer'])) {

                /** If we use cache (css and js) we will create optimized cache files and return html link to cache files
                 * If not we will just get link to native script files. */
                    $shortcodes_scripts["{footer.js}"] = $this->js_optimize($js['footer'], $js_paths['footer']);

                }

            }

            /** If page cache exist and is valid we will output cache file. */
            if ($page["cache_exist"] && $page["cache_validity"]) {

                $this->process_cache($page);

            } else {
                /** Page cache do not exist so we will create one. */
                $this->process_template($page, $meta_data, $page_data, $template_path, $elements, $shortcodes_scripts);
            }
        } else {

            /** We do not use cache system so nothing to cache, so we just use template engine  */
            $this->process_template_no_cache($meta_data, $page_data, $template_path, $template_name, $elements, $css_paths, $js_paths);
        }
    }

    /**
     * process_cache - Get cache filecontent and display it
     * @param string $cache - Cache path
     */
    private function process_cache($cache) {

        $file = file_get_contents($cache['cache_path']);
        $this->display($file, $cache);

    }

    private function display($template, $page) {

        if (!ob_start("ob_gzhandler")) {
            ob_start();
        }
        // Buffering start. Check if Zlib is enabled use ob_start("ob_gzhandler"), if not use ob_start()
        // If we ouput gzipped Page/Template Cache files.
        if (CACHE_GZIP) {



            header("Content-Encoding: gzip");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s \G\M\T", $page['cache_time']), true, 200);
            header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + CACHE_PAGES));
            header("Cache-Control: public");
            header("Pragma: public");
            header("Expect:"); // Fix IE6 Content-Disposition
            header("Content-Description: Steam Inline");
            header("Connection: Keep-Alive");
            header("Content-Disposition: inline;");
            header('ETag: "' . $page['cache_unique'] . '"');
        } else {

            header("Last-Modified: " . gmdate("D, d M Y H:i:s \G\M\T", $page['cache_time']), true, 200);
            header("Content-Encoding: x-gzip");
            header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + CACHE_PAGES));
            header("Cache-Control: public");
            header("Pragma: public");
            header("Expect:"); // Fix IE6 Content-Disposition
            header("Connection: Keep-Alive");
            header('ETag: "' . $page['cache_unique'] . '"');
        }

        // Output template file content.
        echo $template;

        ob_end_flush();
    }

    /**
     *  process_template - Process all shortcodes, templates, display page and create cache
     *
     * @param array $page
     * @param array $meta_data
     * @param array $page_data
     * @param string $template_path
     * @param array $elements
     * @param array $shortcodes_scripts
     */
    private function process_template($page, $meta_data, $page_data, $template_path, $elements, $shortcodes_scripts) {

        /** Create page content. */
        $output = $this->create_output($shortcode, $elements, $template_path, $page_data);

        /** Disp[lay page. */
        $this->display($output, $page);
        /** gzip cache file. */
        if (CACHE_GZIP && extension_loaded('zlib')) {
            $output = gzencode($output, CACHE_GZIP_LEVEL);
        }
        /** Create cache file. */
        file_put_contents($page['cache_path'], $output);
    }

    /**
     * process_template_no_cache - Process template without creating cache
     * @param array $page
     * @param array $meta_data
     * @param array $page_data
     * @param string $template_path
     * @param string $template_name
     * @param array $elements
     * @param array $css_paths
     * @param array $js_paths
     */
    private function process_template_no_cache($meta_data, $page_data, $template_path, $template_name, $elements, $css_paths, $js_paths) {

        /** Check if we using client caching. If we do check for client cache. */
        if (CACHING_CLIENT) {

            $headers = $this->request_headers();

            if (isset($headers['If-Modified-Since'])) {

                $get_time = strtotime($headers['If-Modified-Since']);

                if ($get_time + 600 > time()) {

                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $get_time) . ' GMT', true, 304);
                    exit;
                }
            }

        }

            $shortcodes_scripts = array();

            $shortcodes_scripts["{css}"] = $this->scripts_paths($css_paths, 'css');

            if (isset($js_paths['head'])) {

                $shortcodes_scripts["{head.js}"] = $this->scripts_paths($js_paths['head'], 'js');

            }

            if (isset($js_paths['footer'])) {

                $shortcodes_scripts["{footer.js}"] = $this->scripts_paths($js_paths['footer'], 'js');

            }

            $output = $this->create_output($shortcodes_scripts, $elements, $template_path, $page_data, $meta_data);

            $page['cache_time'] = time();

            $page['cache_unique'] = $this->cache_hash($output);

            $this->display($output, $page);

    }

    /**
     * page_shortcodes - Build in shortcodes creates some html elements on fly - call them when you prepare data
     *
     * @param array $data - Page shortcodes
     * @param string $source
     * @return string
     */
//    private function page_shortcodes($data, $source) {
//
//        foreach ($data as $key => $value) {
//            /** Create unorder list */
//            if (preg_match('/{ul_list./', $key)) {
//                $data[$key] = $this->lists($value, 'ul');
//            }
//            /** Create order list. */
//            if (preg_match('/{ol_list./', $key)) {
//                $data[$key] = $this->lists($value, 'ol');
//            }
//            /** Create headings elements*/
//            /** @todo fix headings and add more html code processing eg. forms and other fast elements */
//            if (preg_match('/h[(0-9)]/', $key, $matches)) {
//                $data[$key] = $this->headings($value, $matches);
//            }
//
//
//        }
//
//
//        return $data;
//    }

    private function h($value, $data) {

        return '<h' . $value[0] . '>' . $data . '</h' . $value[0] . '>';
    }

    private function video($value, $data) {

        return '<video width="'.$data['width'].'" height="'.$data['height'].'" '.$data['settings'].'>
  <source src="'.$data['src'].'" >
  Your browser does not support the video tag.
</video>
';

    }

    private function lists(&$data, $processing_data) {

        $list = '<' . $data[0] . '>';
        foreach ($processing_data as $key => $value) {
            if (!is_array($value)) {

                if(!array($key)){
                    $v = '<a href="'.$value.'">'.$key.'</a>';
                }else{
                    $v = $value;
                }
                $list .= '<li>' . $v . '</li>';

            } else {

                $n = array($key);
                $list .= $this->lists($n, $value);
            }
        }
        $list .= '</' . $data[1] . '>';
        return $list;
    }


    private function template_shortcodes($output, $shortcode, $page_data){
        preg_match_all('/\{\:\:([^}]*)\.([^}]*)\}/', $output, $m);
        $p = array();
        $num = count($m[1]);

        for($i=0;$i<$num;$i++){

            $data = explode('_', $m[1][$i]);

                if(array_key_exists($m[0][$i], $page_data)){

                   $processing_data = $page_data[$m[0][$i]];
                   unset($page_data[$m[0][$i]]);

               }elseif(array_key_exists($m[2][$i], $page_data)){

                   $processing_data = $page_data[$m[2][$i]];
                   unset($page_data[$m[2][$i]]);
               }

               if($processing_data){
                    $function = $data[0];
                    array_shift($data);
                    $p[$m[0][$i]] = $this->{$function}($data, $processing_data);
               }

        }
        foreach ($page_data as $key => $value) {
            $p["{" . $key . "}"] = $value;
        }

        return $p;
    }
    /**
     * create_output - Process shortcodes in templates
     * @param array $shortcode
     * @param array $elements
     * @param string $template_path
     * @param array $page_data
     * @return string
     */
    private function create_output($shortcode, $elements, $template_path, $page_data, $meta_data) {

        $output = '';
        /** Get template elements content and comcat them. */
        $output .= file_get_contents($elements['head']);
        $output .= file_get_contents($template_path);
        $output .= file_get_contents($elements['footer']);

        $page_data = $this->template_shortcodes($output, $shortcode, $page_data);

        /** Process head selection shortcodes and merge all shortcodees in one array. */
        $shortcode = array_merge($shortcode, $this->head_shortcodes($meta_data), $page_data);


        /** Find and process php code tags */
        $output = $this->php_code($output, $shortcode);

       // $output = $this->template_shortcodes($output, $shortcode);

        /** Find and process php functions */
        $shortcode = $this->process_functions($output, $shortcode);

        /** Process values of arrays, {array-key} outputs value of specific key. */
        $shortcode = $this->array_elements($output, $shortcode);

        /** main Template processing replace shortcodes with values. */
        $output = str_replace(array_keys($shortcode), array_values($shortcode), $output);

        /** Remove unused shortcodes. */
        $output = preg_replace('/{.*}/', '', $output);

        /** Process html code make indentatiton or put all in one line. */
        if (HTML_CODE) {
            if (HTML_CODE == 'compact') {
                $output = $this->baseclean($output);
            } else {
                $output = $this->clean_html_code($output);
            }
        }

        return $output;
    }

    /**
     * array_elements - Evaluate array element value eg. {array-element} outputs value of that key
     * @param string $output
     * @param array $shortcode
     * @return array
     */
    private function array_elements($output, $shortcode) {


        preg_match_all('/{(\w+(?:-\w+)+)}/im', $output, $m);


        for ($i = 0; $i < count($m[0]); $i++) {
            $segments = explode("-", $m[1][$i]);

            $segments_num = count($segments) - 1;
            $ra = $shortcode["{" . $segments[0] . "}"];

            for ($w = 1; $w < $segments_num + 1; $w++) {

                $ra = $ra[$segments[$w]];
            }

            $shortcode["{$m[0][$i]}"] = $ra;

        }

        return $shortcode;
    }

    /** @todo array functions if needed if else etc*/
     private function array_functions($output, $shortcode){

         preg_match_all('/{arr-([a-zA-Z0-9]+)::(.*)(|{(.*)})\|([a-zA-Z0-9]+)}/Usm', $output, $m);

          if ($m) {
            for ($i = 0; $i < count($m[1]); $i++) {

                if (strlen($m[3][$i]) > 0) {


                    if (!$shortcode["{".$m[1][$i]."}"] = @call_user_func($m[2][$i], $shortcode["{" . $m[5][$i] . "}"], $m[3][$i])) {
                        $this->Module_Error->show_error_info('205', array('::FUNCT::' => $m[1][$i]));
                    }
                } else {

                    if (!$shortcode["{".$m[1][$i]."}"] = @call_user_func($m[2][$i], $shortcode["{" . $m[5][$i] . "}"])) {
                        $this->Module_Error->show_error_info('205', array('::FUNCT::' => $m[1][$i]));
                    }
                }
            }
          }

          return $shortcode;
     }


    /**
     * process_functions - process php functions more or less String Functions can be used
     *
     * @param string $output
     * @param type $shortcode
     */
    private function process_functions($output, $shortcode) {
        /** Match shortcode */
        preg_match_all('/{comm::(.*)(|{(.*)})(|\|([a-zA-Z0-9]+))}/Usm', $output, $m);

        if ($m) {
            /** Loop over matched. */
            for ($i = 0; $i < count($m[1]); $i++) {

                if (strlen($m[3][$i]) > 0) {

                    if (!$shortcode["{$m[0][$i]}"] = call_user_func($m[1][$i], $shortcode["{" . $m[4][$i] . "}"], $m[3][$i])) {
                        $this->Module_Error->show_error_info('205', array('::FUNCT::' => $m[1][$i]));
                    }

                } else {

                    if (!$shortcode["{$m[0][$i]}"] = call_user_func($m[1][$i], $shortcode["{" . $m[4][$i] . "}"])) {
                        $this->Module_Error->show_error_info('205', array('::FUNCT::' => $m[1][$i]));
                    }
                }
            }

            return $shortcode;
        }
    }

    /**
     * php_code - Process php code, do we need this or not????
     *
     * @param type $output
     * @param type $shortcode
     * @return type
     */
    private function php_code($output, $shortcode) {

        /** Match tags. */
        preg_match_all('/^{php}(.*){\/php}$/Usm', $output, $matches);
        $clean_array = array_filter($matches);

        if ( !empty($clean_array)) {
            /** Loop all matched. */
            for ($i = 0; $i < count($matches); $i++) {
                /** Match all variable shortcodes and replace it with values*/
                preg_match('/{([a-zA-Z0-9]+)}/', $matches[$i][1], $matchess);

                ${$matchess[1]} = $shortcode[$matchess[0]];
                /** Execute php code. */
                ob_start();
                eval($matches[$i][1]);
                $dada = ob_get_contents();
                ob_end_clean();
            }

            return str_replace($matches[0], $dada, $output);
        }else{
            return $output;
        }
    }

    private function scripts_paths($paths, $type) {

        $link = '';
        foreach ($paths as $key => $value) {

            if ($type == 'css') {
                $link .= '<link href="' . $value . '" rel="stylesheet" type="text/css" />' . "\n";
            } else {
                $link .= '<script type="text/javascript" src="' . $value . '"></script>' . "\n";
            }
        }
        return $link;
    }

    /**
     * head_template - Construct shortcodes for head selection: doctype, title, meta, http_equiv, link
     *
     * @param array $meta_data
     * @return array
     */
    private function head_shortcodes($meta_data) {

        $shortcodes = array();

        $shortcodes["{base}"] = '';
        $shortcodes['{meta}'] = '';
        $shortcodes['{http_equiv}'] = '';
        $shortcodes['{link_rel}'] = '';

        /** Not HTML5 */
        if ($meta_data['doctype'] !== '1') {

            $shortcodes["{doctype}"] = $this->doctype[$meta_data['doctype']] . "\n";
            $shortcodes["{doctype}"] .= '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";


        } else {
            /** HTML5 */
            $shortcodes["{doctype}"] = $this->doctype[$meta_data['doctype']] . "\n";
            $shortcodes["{doctype}"] .= '<html lang="' . $meta_data['lang'] . '">' . "\n";
             $shortcodes["{doctype}"] .= '<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" ></ <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" /> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" /> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" </html>  <!--<![endif]-->';
            $shortcodes['{meta}'] .= ' <meta charset="' . $meta_data['charset'] . '" />';
            $shortcodes['{html5}'] = '<!--[if lt IE 9]>
            <script src="core/js`_core/verndor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <![endif]-->' . "\n";

        }

        $shortcodes["{title}"] = '<title>' . $meta_data['title'] . '</title>';

        /** Check if shortcode is set and not empty, and create shortcode or just unset it.*/
        if (isset($meta_data['base']) && !empty($meta_data['base'])) {

            $shortcodes["{base}"] = $meta_data['base'];

        }else{

            unset($shortcodes["{base}"] );
        }

        /** Process all meta values and create shortcodes*/
        if (isset($meta_data['meta']) && count($meta_data['meta']) !== 0) {

            foreach ($meta_data['meta'] as $key => $value) {
                if(!empty($value)){
                    $shortcodes['{meta}'] .= '<meta name="' . $key . '" content="' . $value . '" />' . "\n";
                }
            }
        }

        if (isset($meta_data['http_equiv']) && count($meta_data['http_equiv']) !== 0) {

            foreach ($meta_data['http_equiv'] as $key => $value) {
                if(!empty($value)){
                    $shortcodes['{http_equiv}'] .= '<meta http-equiv="' . $key . '" content="' . $value . '" />' . "\n";
                }
            }
        }

        if (isset($meta_data['link_rel']) && count($meta_data['link_rel']) !== 0) {

            foreach ($meta_data['link_rel'] as $key => $value) {

                $shortcodes['{link_rel}'] .= '<link rel="' . $value['rel'] . '" ';

                if (isset($value['type'])) {

                    $shortcodes['{link_rel}'] .= 'type="' . $value['type'] . '"';

                } else {

                    $shortcodes['{link_rel}'] .= 'sizes="' . $value['sizes'] . '"';
                }

                $shortcodes['{link_rel}'] .= 'href="' . $value['href'] . '">' . "\n";
            }
        }

        /** Add support for HTML5 on browsers that do not support fully*/
//        if ($meta_data['doctype'] == 1) {
//            $shortcodes['{html5}'] .= '<!--[if IE]>
//        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
//        <![endif]-->' . "\n";
//        }
        return $shortcodes;
    }

    /**
     * request_headers - Get browser headers
     *
     * @return array - headers array
     */
    private function request_headers() {

        /** Set empty header array. */
        $headers = array();

         /** If Apache function is enabled. */
        if (function_exists("apache_request_headers")) {

            $headers = apache_request_headers();

        } else if (function_exists("get_headers")) {

            $headers = getallheaders();

        } else {

            $headers['If-Modified-Since'] = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
        }

        return $headers;
    }

    /**     * ******************* */

    /**
     * css_optimize - Optimize CSS code using some of the optimizers/Compressors
     * base_clean|CSSCompressor|CSSTidy|CSSCrush|CSSmin|YUICompressor
     *
     * @param array $data - consist of
     * [cache_path] => cache/css/main(aea90b4b).css.gz - cache path
      [cache_exist] => 1 - true|false
      [cache_validity] => 1 - true|false
      [cache_time] => 1365443934 - cache file time
     * @param array $paths - source files path
     */
    public function css_optimize($data, $paths) {

        $optimized = '';                    // empty variable to hold conc code from different css files if we combine them into one
        $count_files = count($paths);       // count number of css files
        $link = '';                         // empty variable that will hold <link to optimized css files one or more

        /** Loop over css file we want toprocess */
        for ($i = 0; $i < $count_files; $i++) {
            /** We already check if cache existance and cache validity if one of this values == false we must create new cache file */
            if (!$data[$i]["cache_exist"] || !$data[$i]["cache_validity"]) {
                /** Get css file content */
                $file_content = file_get_contents($paths[$i]);
                /** If we set option to combine multiple css files into one */
                if (COMBINE_CSS) {
                    /** Optimize css code with one class included check configuration.php */
                    $optimized .= $this->process_css($file_content);
                    /** Last file creare cache file and compose link to file */
                    if ($count_files - 1 == $i) {

                        if ($this->cache_create($optimized, $data[0]["cache_path"])) {

                            $link .= '<link href="' . $data[0]["cache_path"] . '" rel="stylesheet" type="text/css" />' . "\n";
                        }
                    }
                } else {
                    /** We optimize and cache individual files so process them one by one */
                    if ($this->cache_create($this->process_css($file_content), $data[$i]["cache_path"])) {

                        $link .= '<link href="' . $data[$i]["cache_path"] . '" rel="stylesheet" type="text/css" />' . "\n";
                    }
                }
            } else {
                /** Cache is valid we do not need to recreate it just commpose <link */
                $link .= '<link href="' . $data[$i]["cache_path"] . '" rel="stylesheet" type="text/css" />' . "\n";
            }
        }

        return $link;
    }

    /**
     * process_css - Optimize/Compress CSS code depending of optimizer used
     * @param string $data
     * @return bool
     */
    private function process_css($data) {

        if (CSS_OPTIMIZER == 'baseclean') {
            /** Simple optimizer base processing */
            return call_user_func(array($this, CSS_OPTIMIZER), $data);
        } else if (CSS_OPTIMIZER == 'CSSCompressor') {

            return $this->Vendor_CSSpackers_CSSCompressor->process($data);
        } else if (CSS_OPTIMIZER == 'CSSTidy') {
            /*             * @todo csstidy have more complex options so implement it over controll panell */
            $this->Vendor_CSSpackers_CSSTidy->parse($data);
            return $this->Vendor_CSSpackers_CSSTidy->print->formatted();
        } else if (CSS_OPTIMIZER == 'CSSCrush') {
            /** @todo - Implement singleton on CssCrush class */
            require_once SITE_ROOT . 'framework/vendor/CSSpackers/CSScrush/CssCrush.php';
            return csscrush::string($data);
        } else if (CSS_OPTIMIZER == 'CSSminyui') {

            return $this->Vendor_CSSpackers_CSSminyui->run($data);

            /** @todo Implement this later
              } else if (CSS_OPTIMIZER == 'CssMin') {
              require_once SITE_ROOT . 'framework/vendor/CSSpackers/CssMin.php';
              $minifier = new CssMinifier($data);
              return $minifier->getMinified();
             */
        } else if (CSS_OPTIMIZER == 'YUICompressor') {

            /** @todo add chmod script for temp directory */
            $yui = $this->Vendor_YUI_YUICompressor;
            $yui::$jarFile = SITE_ROOT . 'framework/vendor/YUI/java/yuicompressor-2.4.7.jar';
            $yui::$tempDir = SITE_ROOT . 'framework/vendor/YUI/temp/';
            return $yui::minifyCss($data, array('nomunge' => true, 'line-break' => 1000));
        }
    }

    /**
     * js_optimize - Predprocessing js code
     * @param type $data
     * @param type $paths
     * @return string
     */
    public function js_optimize($data, $paths) {

        $optimized = '';
        $count_files = count($paths);
        $script = '';

        for ($i = 0; $i < $count_files; $i++) {

            if (!$data[$i]["cache_exist"] || !$data[$i]["cache_validity"]) {

                $file_content = file_get_contents($paths[$i]);

                if (COMBINE_JS) {

                    //  $optimized .= $this->process_js($file_content);
                    $optimized .= $file_content;

                    if ($count_files - 1 == $i) {

                        $optimized = $this->process_js($optimized);

                        if ($this->cache_create($optimized, $data[0]["cache_path"])) {

                            $script .= '<script type="text/javascript" src="' . $data[0]["cache_path"] . '"></script>' . "\n";
                        }
                    }
                } else {

                    if ($this->cache_create($this->process_js($file_content), $data[$i]["cache_path"])) {

                        $script .= '<script type="text/javascript" src="' . $data[$i]["cache_path"] . '"></script>' . "\n";
                    }
                }
            } else {

                $script .= '<script type="text/javascript" src="' . $data[$i]["cache_path"] . '"></script>' . "\n";
            }
        }

        return $script;
    }

    /**
     * process_js - Optimize/Compress JS code depending of optimizer used
     * @param type $data
     * @return bool
     */
    public function process_js($data) {

        if (JS_OPTIMIZER == 'JavaScriptPacker') {

            return $this->Vendor_JSpackers_JavaScriptPacker->data($data);
        } else if (JS_OPTIMIZER == 'JShrink') {

            return $this->Vendor_JSpackers_Minifier->minify($data);
        } else if (JS_OPTIMIZER == 'JSMinPlus') {

            return $this->Vendor_JSpackers_JSMinPlus->minify($data);
        } else if (JS_OPTIMIZER == 'JSMin') {
            /*             * @todo adjust source class code to sigleton call */
            return @$this->Vendor_JSpackers_JSMin->minify($data);
        } else if (JS_OPTIMIZER == 'ClosureCompiler') {

            return $this->Vendor_JSpackers_ClosureCompiler->minify($data);
        } else if (JS_OPTIMIZER == 'JSminimizer') {
            /** @todo - Implement singleton on JSminimizer class */
            return @$this->Vendor_JSpackers_JSminimizer->pack($data);
        } else if (JS_OPTIMIZER == 'YUICompressor') {

            /** @todo add chmod script for temp directory */
            $yui = $this->Vendor_YUI_YUICompressor;
            $yui::$jarFile = SITE_ROOT . 'framework/vendor/YUI/java/yuicompressor-2.4.7.jar';
            $yui::$tempDir = SITE_ROOT . 'framework/vendor/YUI/temp/';
            return $yui::minifyJs($data, array('nomunge' => true, 'line-break' => 1000));
        }

        /**
          @todo add chmod script for temp directory
          $ClosureCompilerLocal = $this->Vendor_JSpackers_ClosureCompilerLocal;
          $ClosureCompilerLocal::$jarFile = SITE_ROOT . 'framework/vendor/JSpackers/ClosureCompiler/compiler.jar';
          $ClosureCompilerLocal::$CACHE_FOLDER = SITE_ROOT . 'framework/vendor/JSpackers/ClosureCompiler/temp';
          return $ClosureCompilerLocal::minify($data, 'temp');
         */
    }

    /**
     * baseclean - Basic white space clean function
     * @param string $content
     * @return string
     */
    public function baseclean($content) {

        $content = preg_replace('/^\s+|\n|\r|\s+$/m', '', $content);
        $content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
        $content = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $content);
        $content = preg_replace(array('(( )+{)', '({( )+)'), '{', $content);
        $content = preg_replace(array('(( )+})', '(}( )+)', '(;( )*})'), '}', $content);
        $content = preg_replace(array('(;( )+)', '(( )+;)'), ';', $content);
        $content = preg_replace(array('(:( )+)', '(( )+:)'), ':', $content);
        $content = preg_replace('/(\s|)\,(\s|)/', ',', $content);

        return $content;
    }

    /**     * ********* */

    /**
     * page_files - Create unique page cache path and check for existance.
     * If any of elements are changed cache is not valid. Unique page cache path include all elements head, template, footer,
     *  all shortcodes, css and js timestamps.
     *
     * @param array $meta_data  - head shortcodes
     * @param array $page_data  - template shortcodes
     * @param string $template_path - path to current template
     * @param string $template_name - unique template name
     * @param array $elements - head and footer template
     * @param array $css_paths  - css files timestamp
     * @param array $js_paths - js files timestamp
     * @return array - cache_unique - Cache unique name
     *               - cache_path - Cache path
     *               - cache_exist - true|false - Cache existance
     *               - cache_time   - Cache timestamp
     *               - cache_validity - Calculate if cache is valid if no elements is changed based on CACHE_PAGES constant in configuration.php
     */
    public function page_files($meta_data, $page_data, $template_path, $template_name, $elements, $css_paths, $js_paths) {

        $page_cache = array();
        /** Get md5 values of template elements head, template and footer*/
        if (!$get_template = md5_file($template_path)) {

            $this->Module_Error->show_error_info('200');
        }

        if (!$get_template .= md5_file($elements['head'])) {

            $this->Module_Error->show_error_info('203');
        }

        if (!$get_template .= md5_file($elements['footer'])) {

            $this->Module_Error->show_error_info('204');
        }

        /** Create unique stamp for cache */
        $check_data = md5(json_encode($meta_data)) . md5(json_encode($page_data)) . $get_template . $css_paths . $js_paths;

        /** Create cache file name. */
        $page_cache['cache_unique'] = $template_name . '(' . $this->cache_hash($check_data) . ')';

        /** Create cache path. Extension is added this way because we can use gzipped files.
         * In that case Safari can not process .css.gs, so must be created extension .css.gs.css */
        $page_cache['cache_path'] = CACHE . 'pages/' . $page_cache['cache_unique'] . '.' . $this->extension['page'];

        /** Simply check file existance. */
        $page_cache['cache_exist'] = $this->check_file($page_cache['cache_path']);

        /** If cache exist check cache validity.  */
        if ($page_cache['cache_exist']) {

            $page_cache_validity = $this->cache_validity($page_cache['cache_path'], 'page');

        } else {

            $page_cache_validity = array('cache_validity' => false, 'cache_time' => time());
        }

        return array_merge($page_cache, $page_cache_validity);
    }

    /**
     * scripts_files - Check CSS and JS cache files for existance and validity
     *
     * @param array $files
     * @param string $type
     */
    public function scripts_files($files, $type, $js_location = false) {

        $script_cache = array();
        $script_cache_validity = array();
        $scripts_type = false;
        $all_paths = '';

        if ($type == 'css') {

            $scripts_type = COMBINE_CSS;
            $scripts_master_name = COMBINED_CSS_NAME;
        } else if ($type == 'js') {

            $scripts_type = COMBINE_JS;

            if ($js_location) {

                $scripts_master_name = $js_location;
            } else {

                $scripts_master_name = COMBINED_JS_NAME;
            }
        }

        /** If we define that all scripts will be combined into one with same name $scripts_master_name
         * check existance and validity bc we have just one cache script file
         */
        if ($scripts_type) {

            $all_paths .= $hash = $this->cache_hash($files);

            $cache_unique = $scripts_master_name . '(' . $hash . ')';

            $script_cache[0]['cache_path'] = CACHE . $type . '/' . $cache_unique . '.' . $this->extension[$type];

            $script_cache[0]['cache_exist'] = $this->check_file($script_cache[0]['cache_path']);


            if ($script_cache[0]['cache_exist']) {

                $script_cache_validity[0] = $this->cache_validity($script_cache[0]['cache_path'], $type);
            } else {

                $script_cache_validity[0] = array('cache_validity' => false, 'cache_time' => time());
            }

            $combined[0] = array_merge($script_cache[0], $script_cache_validity[0]);
        } else {

            /** If we do not combine all scripts into one shech them one by one */
            foreach ($files as $key => $path) {

                $segments = explode('/', $path);

                $script_name = strtolower(substr(end($segments), 0, -4));

                $all_paths .= $hash = $this->cache_hash($path);

                $cache_unique = $script_name . '(' . $hash . ')';

                $script_cache[$key]['cache_path'] = CACHE . $type . '/' . $cache_unique . '.' . $this->extension[$type];

                $script_cache[$key]['cache_exist'] = $this->check_file($script_cache[$key]['cache_path']);

                if ($script_cache[$key]['cache_exist']) {

                    $script_cache_validity[$key] = $this->cache_validity($script_cache[$key]['cache_path'], $type);
                } else {

                    $script_cache_validity[$key] = array('cache_validity' => false, 'cache_time' => time());
                }

                $combined[$key] = array_merge($script_cache[$key], $script_cache_validity[$key]);
            }
        }

        return array($combined, $all_paths);
    }

    /**
     * check_file - Check file existance
     * @param string $path - check cache file existance
     * @return boolean
     */
    public function check_file($path) {

        if (file_exists($path)) {

            return true;
        } else {

            return false;
        }
    }

    public function cache_create($optimized_data, $path) {

        if (CACHE_GZIP && extension_loaded('zlib')) {

            // Encode combined and optimized css files. gzdeflate
            $optimized_data = gzdeflate($optimized_data, CACHE_GZIP_LEVEL);
        }

        if (file_put_contents($path, $optimized_data)) {

            return true;
        } else {

            $this->Module_Error->show_error_info('201');
        }
    }

    /**
     * cache_hash - Create unique hash based on defined algo in CACHE_FILENAME configuration.php
     * @param string $data
     * @return string
     */
    public function cache_hash($data) {

        $cache_unique = '';
        /** Check if is array - means array of file paths, used for css and js files combining in single file. */
        if (is_array($data)) {
            $count = count($data);

            for ($i = 0; $i < $count; $i++) {
                /** Combime files hashes into one unique */
                $cache_unique .= hash(CACHE_FILENAME, filemtime($data[$i]));

            }
        } else {
            /** If $data is just plain text create hash.*/
            $cache_unique .= hash(CACHE_FILENAME, $data);
        }

        return $cache_unique;
    }

    /**
     * cache_validity - Checking if cache file is still valid
     * @param string $cache_path - Cache file path
     * @param string $cache_type - page|css|js type of cache files
     * @param bool $cache_exist - already checked cache existance
     */
    public function cache_validity($cache_path, $cache_type) {

        /** Check type of cache file and set lifetine defined in inc/config/configuration.php
         * We have css, js and pages cache lifetime defined*/
        if ($cache_type == 'css' || $cache_type == 'js') {

            $cache_lifetine = CACHE_SCRIPTS;

        } else {

            $cache_lifetine = CACHE_PAGES;
        }

        /** Get last modification of cache file */
        $last_modified = filemtime($cache_path);

        /** Depending if cache live expire or is still valid return validity */
        if (($last_modified + $cache_lifetine) < time()) {

            $valid_cache = false;

        } else {

            $valid_cache = true;
        }

        return array('cache_validity' => $valid_cache, 'cache_time' => $last_modified);
    }

    /**
     * set_extension()
     * Workarround for Safari problem with gzipped content we need other type of compressed cache files extensions.
     */
    private function set_extension() {

        if (CACHE_GZIP) {

            $this->extension['css'] = $this->extension['css'] . '.gz';
            $this->extension['js'] = $this->extension['js'] . '.gz';

            if ($this->Module_Browscap->getBrowser()->Browser == 'Safari') {

                $this->extension['css'] = $this->extension['css'] . '.gz.css';
                $this->extension['js'] = $this->extension['js'] . '.gz.js';
            }

            $this->extension['page'] = $this->extension['page'] . '.gz';
        }
    }

    /**
     * Indent XHTML output code  $this->xhtml_code_indentation. Credits to orginal author.
     *
     * @param string $uncleanhtml
     * @return string implode("\n", $cleanhtml_array)
     */
    private function clean_html_code($uncleanhtml) {

        // Set indentation.
        $indent = '   ';

        // Uses previous function to seperate tags.
        $fixed_uncleanhtml = $this->fix_newlines_for_clean_html($uncleanhtml);

        $uncleanhtml_array = explode("\n", $fixed_uncleanhtml);

        // Sets no indentation.
        $indentlevel = 0;

        foreach ($uncleanhtml_array as $uncleanhtml_key => $currentuncleanhtml) {

            // Removes all indentation.
            $currentuncleanhtml = preg_replace("/\t+/", "", $currentuncleanhtml);
            $currentuncleanhtml = preg_replace("/^\s+/", "", $currentuncleanhtml);

            $replaceindent = "";

            // Sets the indentation from current indentlevel.
            for ($o = 0; $o < $indentlevel; $o++) {

                $replaceindent .= $indent;
            }

            // If self-closing tag, simply apply indent.
            if (preg_match("/<(.+)\/>/", $currentuncleanhtml)) {

                $cleanhtml_array[$uncleanhtml_key] = $replaceindent . $currentuncleanhtml;

                // If doctype declaration, simply apply indent.
            } else if (preg_match("/<!(.*)>/", $currentuncleanhtml)) {

                $cleanhtml_array[$uncleanhtml_key] = $replaceindent . $currentuncleanhtml;

                // If opening AND closing tag on same line, simply apply indent.
            } else if (preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && preg_match("/<\/(.*)>/", $currentuncleanhtml)) {

                $cleanhtml_array[$uncleanhtml_key] = $replaceindent . $currentuncleanhtml;

                // If closing HTML tag or closing JavaScript clams, decrease indentation and then apply the new level.
            } else if (preg_match("/<\/(.*)>/", $currentuncleanhtml) || preg_match("/^(\s|\t)*\}{1}(\s|\t)*$/", $currentuncleanhtml)) {

                $indentlevel--;
                $replaceindent = "";

                for ($o = 0; $o < $indentlevel; $o++) {
                    $replaceindent .= $indent;
                }

                //  Fix for textarea whitespace and in my opinion nicer looking script tags.
                if ($currentuncleanhtml == '</textarea>' || $currentuncleanhtml == '</script>') {

                    $cleanhtml_array[$uncleanhtml_key] = $cleanhtml_array[($uncleanhtml_key - 1)] . $currentuncleanhtml;
                    unset($cleanhtml_array[($uncleanhtml_key - 1)]);
                } else {

                    $cleanhtml_array[$uncleanhtml_key] = $replaceindent . $currentuncleanhtml;
                }

                // If opening HTML tag AND not a stand-alone tag, or opening JavaScript clams, increase indentation and then apply new level.
            } else if ((preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && !preg_match("/<(link|meta|base|br|img|hr)(.*)>/", $currentuncleanhtml)) || preg_match("/^(\s|\t)*\{{1}(\s|\t)*$/", $currentuncleanhtml)) {

                $cleanhtml_array[$uncleanhtml_key] = $replaceindent . $currentuncleanhtml;

                $indentlevel++;
                $replaceindent = "";

                for ($o = 0; $o < $indentlevel; $o++) {
                    $replaceindent .= $indent;
                }
            } else {

                // Else, only apply indentation.
                $cleanhtml_array[$uncleanhtml_key] = $replaceindent . $currentuncleanhtml;
            }
        }

        // Return single string seperated by newline.
        return implode("\n", $cleanhtml_array);
    }

    /**
     * Function to seperate multiple tags one line. Credits to orginal author.
     *
     * @param string $fixthistext
     */
    private function fix_newlines_for_clean_html($fixthistext) {

        // Explode data to array on every new line.
        $fixthistext_array = explode("\n", $fixthistext);

        // Loop and remove empty lines.
        foreach ($fixthistext_array as $unfixedtextkey => $unfixedtextvalue) {

            if (!preg_match("/^(\s)*$/", $unfixedtextvalue)) {

                $fixedtextvalue = preg_replace("/>(\s|\t)*</U", ">\n<", $unfixedtextvalue);
                $fixedtext_array[$unfixedtextkey] = $fixedtextvalue;
            }
        }

        return implode("\n", $fixedtext_array);
    }

}
