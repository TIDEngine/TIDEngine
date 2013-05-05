<?
/*************************************************************
 * JCM - Javascript & CSS Minifier
 * JSminimizer.php version: 0.8b, 2012.10.11.
 * Copyright (c) 2012 Szentgyörgyi János <info@dynamicart.hu>
 * Web: http://dynamicart.hu/
 * Web JCM: http://dynamicart.hu/jcm/
 *
 * This software is licensed under the CC BY-NC-ND
 * http://creativecommons.org/licenses/by-nc-nd/3.0/legalcode
 *************************************************************/

// js token const
define ("JT_START",         0);         // start token..
define ("JT_DSTRING",       1);         // double quoted string " ... "
define ("JT_SSTRING",       2);         // single quoted string ' ... '
define ("JT_COMMENT",       3);         // comment single line, not used
define ("JT_COMMENT_MULTI", 4);         // comment multi line, not used
define ("JT_REGEX",         5);         // regexp / .... /mod
define ("JT_OPERATOR",      6);         // operators
define ("JT_LINE_TERMINATOR", 7);       // \r\n
define ("JT_WHITESPACE",    8);         // whitespace char
define ("JT_BRACKET",       9);         // bracket ()
define ("JT_BLOCK",         10);        // bracket {}
define ("JT_ENUM",          11);        // bracket []
define ("JT_NUMERIC_LITERAL",12);       // numeric
define ("JT_WORD",          13);        // word
define ("JT_JUNK",          14);        // junk
define ("JT_KEYWORD",       16);        // keyword
//define ("JT_VARIABLE",      17);        // variable

class JSminimizer extends System{
    private $text = "";                 // the javascript text, original
    private $jsmin_str = "";            // result
    private $pos = 0;                   // current position

	private $SCOPE_PREFIX = array(      // block, bracket, enum open chars
		"[", "{", "("
	);
	private $SCOPE_SUFFIX = array(      // block, bracket, enum close chars
		"]", "}", ")"
	);

    private $CONST_NOSPACEPLEASE = 	array(  // not need any whitespace arround this chars
		"+", "-", "*", "&", "%", "=", "<", ">", "!", "?", "|", "~", "^",
        "'", '"', "(", ")", "[", "]", "{", "}", ",", ".", ":"
	);

    private $NAMESEPARATOR_CHARS = 	array(  // separators
		"+", "-", "*", "&", "%", "<", ">", "!", "?", "|", "~", "^"
	);

    private $REGEXP_MODIFIERS = array(  // regexp modifiers at javascript
		"g", "m", "s", "i", "y"
	);

    private $REGEXP_MODIFIERS_COUNT = 5;    // regexp modifiers count

    private $REGEXP_BEFORE_EXPR = array ( // start regexp with this
        "=", "(", "[", ":", "!"
    );

	private $KEYWORDS = array(
		"break", "case", "catch", "const", "continue", "default", "delete",
		"do", "else", "finally", "for", "function", "if", "in", "instanceof",
		"new", "return", "switch", "throw", "try", "typeof", "var", "void",
		"while", "with"
	);

	private $RESERVED_WORDS = array(
		"abstract", "boolean", "byte", "char", "class", "debugger", "double",
		"enum", "export", "extends", "final", "float", "goto", "implements",
		"import", "int", "interface", "long", "native", "package", "private",
		"public", "public", "short", "static", "super", "synchronized",
		"throws", "transient", "volatile"
	);

	public static $KEYWORDS_BEFORE_EXPRESSION = array(
		"return", "new", "delete", "throw", "else", "case"
	);

	public static $KEYWORDS_ATOM = array(
		"false", "null", "true", "undefined"
	);

	private $KEYWORDS_ALL = array(
		"break", "case", "catch", "const", "continue", "default", "delete",
		"do", "else", "finally", "for", "function", "if", "in", "instanceof",
		"new", "return", "switch", "throw", "try", "typeof", "var", "void",
		"while", "with",
		"abstract", "boolean", "byte", "char", "class", "debugger", "double",
		"enum", "export", "extends", "final", "float", "goto", "implements",
		"import", "int", "interface", "long", "native", "package", "private",
		"public", "public", "short", "static", "super", "synchronized",
		"throws", "transient", "volatile",
        "return", "new", "delete", "throw", "else", "case",
        "false", "null", "true", "undefined"
	);

    private $r_var = "var([ ]?+([a-zA-Z0-9_]+)([^,^;]*))[;]";

    private $r_name = "/^[a-zA-Z_$][0-9a-zA-Z_$]*/";    // regexp pattern for variable names

    private $encoding = "";             // current encode level

    /**
     * pack JS class
     * @param string $src - JS source text
     * @param string $encode - encode method
     * @return JSminimizer
     */
    public static function pack($src,$encode = "normal"){  //
        $jsm = new JSminimizer($src,$encode);
        return $jsm->minimizer();
    }

    /**
     * pack constructor
     * @param string $src - JS source text
     * @param string $encode - encode method
     */
    protected function __construct($src,$encode){
        $this->text = stripslashes($src);
        $this->encoding = $encode;
    }

    /**
     * Minimizer
     * @return string compressed JS src
     */
    private function minimizer(){
        // method none: original text
        if ($this->encoding == "none"){
            $this->jsmin_str = ($this->text);
            return addslashes($this->jsmin_str);
        }

        // method normal:
        //  whitespace remove only
        if ($this->encoding == "normal"){
            $js_arrays = $this->arrayizer2();
            $this->jsmin_builder2($js_arrays);
            return $this->jsmin_str;
        }

        // method renamer:
        //  whitespace remove, more aggressive
        //  variable names rename to short names
        //      - new names by frequency
        if ($this->encoding == "renamer"){
            $js_arrays = $this->arrayizer2();
            $this->varRenamer($js_arrays);
            $this->jsmin_builder2($js_arrays);
            // sok szóköz -> 1-re
            $this->jsmin_str = preg_replace('/\s\s+/', ' ',$this->jsmin_str);
            return $this->jsmin_str;
        }
        return $this->jsmin_str;
    }

    /**
     * generate a new varname
     * @param array $js_arrays - arrayized stuff from arrayizer2
     */
    private function varRenamer($js_arrays){
        // build frequency table about varnames
        $this->jsmin_builder_stat($js_arrays);
        // sort freq. table
        arsort($this->varnames_stat);
        $this->varnames_table = array();
        $this->global_vars = 0;
        // rename the vars
        foreach($this->varnames_stat as $k => $v){
            $newname = $this->get_newvarname();
            $this->varnames_table[$k] = $newname;
        }
    }


    /**
     * get the next char from the stream...
     * this->pos ++ !!
     */
	private function next() {
		$ch = $this->text[$this->pos++];
		return $ch;
	}

	/**
     * get the next char. from the current pos.
     * this->pos untouched
    */
	private function next2() {
		$ch = $this->text[$this->pos];
		return $ch;
	}

	/**
     * get the prev. char. from the current pos.
     * this->pos untouched
    */
    private function prev($o = 0) {
        $i = $this->pos - 2 - $o;
        if ($i < 0)
            return "";
    	return $this->text[$i];
	}

    /**
     * get prev. non whitespace char
     */
    private function prev_nospace(){
        $i = $this->pos - 2;
        $tmp = "";

        while($ch = $this->text[$i--]){
            if (ord($ch) > 32) {
                $tmp = $ch;
                break;
            }
        }
        return $tmp;
    }

    /**
     * get next non whitespace char
     */
    private function next_nospace(){
        $i = $this->pos;
        $tmp = "";

        while($ch = $this->text[$i++]){
            //if ($signal_eof && ! $ch) {
            //    //throw new JS_EOF();
            //    return null;
            //}
            if (ord($ch) > 32) {
                $tmp = $ch;
                break;
            }
        }
        return $tmp;
    }

    // Last used keyword
    private $last_keyword = "";

    /**
     * Debug displayer
     * @param string $token - The Token name
     * @param string $buffer - buffer (msg)
     * @return void
     */
    private function debugthis($token,$buffer){
        if (!$this->debug) return;
        $tab = "";
        for($i=0;$i<$this->block_counter;$i++)
            $tab .= "-";

        echo "<div style='font-family: courier; font-size: 12px;'>{$tab}> T: $token, V: ".(htmlentities($buffer))."</div>";
    }

    /* ARRAYIZER 2 - TOKENIZER */

    private $block_counter = 0;         // { block counter
    private $bracket_counter = 0;       // ( bracket counter
    private $enum_counter = 0;          // [ enum counter
    private $token_stack = array();     // the TOKEN stack for arrayizer
    private $if_stack = array();        // IF stack for IF(?) block detection
    private $debug = false;             // debug switch

    /**
     * Arrayizer2 - tokenizer, more complex then arrayizer1
     * @param int $current_scope - predefined value
     * @return array - one scope in array
     */
    public function arrayizer2($current_scope = ""){
        $buffer = "";
        $bslash = false;
        $js_array_slice = array();
        while(null != ($char = $this->next())){
            //$char_ord = ord($char);
            /**
             * REGEXP scope == r
             */
            if ($current_scope === JT_REGEX){
                if (!$bslash && $char === "/"){
                    // collect modifiers
                    $regexp_mod = "";
                    for($i=0;$i<$this->REGEXP_MODIFIERS_COUNT;$i++){
                        $next = $this->next2();
                        if (in_array($next,$this->REGEXP_MODIFIERS,true)){
                            $regexp_mod .= $next;
                            $this->next();
                        }
                    }
                    $buffer = '/'. $buffer . $char . $regexp_mod;
                    $this->debugthis("JT_REGEX",$buffer);
                    return array("token"=>JT_REGEX,"value"=>$buffer);
                }else{
                    $buffer .= $char;
                    // backslash test
                    /*
                     	rnoContent = /^(?:GET|HEAD)$/,
                    	rprotocol = /^\/\//,
                       	rquery = /\?/,
                     */
                    if ($char === "\\"){
                        //$buffer .= "\\";
                        $bslash = !$bslash;
                    }else{
                        $bslash = false;
                    }
                    continue;
                }
            }

            /**
             * STRING scope == SINGLE QUOTE
             */
            if ($current_scope === JT_SSTRING){
                $do = true;
                do{
                    // if !\ + scope_char then end if string scope
                    if (!$bslash && $char === "'"){
                        // string over, return array
                        $this->debugthis("JT_SSTRING",$buffer);
                        return array("token"=>JT_SSTRING,"value"=>$buffer);
                    }
                    $buffer .= $char;
                    // backslash test
                    if ($char === "\\"){
                        //$buffer .= $char;
                        $bslash = !$bslash;
                    }else{
                        $bslash = false;
                    }
                    $char = $this->next();
                }while($do === true && $char != null);
            }

            /**
             * STRING scope == DOUBLE QUOTE
             */
            if ($current_scope === JT_DSTRING){
                $do = true;
                do{
                    // if !\ + scope_char then end if string scope
                    if (!$bslash && $char === '"'){
                        // string over, return array
                        $this->debugthis("JT_DSTRING",$buffer);
                        return array("token"=>JT_DSTRING,"value"=>$buffer);
                    }
                    $buffer .= $char;
                    // backslash test
                    if ($char === "\\"){
                        //$buffer .= $char;
                        $bslash = !$bslash;
                    }else{
                        $bslash = false;
                    }
                    $char = $this->next();
                }while($do === true && $char != null);

            }

            /**
             * BRACKET scope == ()
             */
            if ($current_scope === JT_BRACKET && $char === ")"){
                if ($buffer!==""){
                    $js_array_slice[] = $this->make_array($buffer);
                }
                $this->bracket_counter--;

                // ) char possibly end of FUNCTION HEADER token
                $token = $tmp = array_pop($this->token_stack);
                $token = explode("|",$token);
                if ($token[0] == "FUNCTION" && $this->get_counters() == $token[1]){
                    $this->debugthis("JT_KEYWORD", 'function, END OF '.$token[1]);
                }else{
                    array_push($this->token_stack,$tmp);
                }
                return array("token"=>JT_BRACKET,"value"=>$js_array_slice);
            }

            /**
             * BLOCK scope == {}
             */
            if ($current_scope === JT_BLOCK && $char === "}"){
                if ($buffer!==""){
                    $js_array_slice[] = $this->make_array($buffer);
                }
                // } char possibly end of VAR token (missing ;)
                $token = $tmp = array_pop($this->token_stack);
                $token = explode("|",$token);
                if ($token[0] == "VAR" && $this->get_counters() == $token[1]){
                    $this->debugthis("JT_KEYWORD", 'var, END OF '.$token[1]);
                }else{
                    array_push($this->token_stack,$tmp);
                }

                $this->block_counter--;
                return array("token"=>JT_BLOCK,"value"=>$js_array_slice);
            }

            /**
             * ENUM scope == []
             */
            if ($current_scope === JT_ENUM && $char === "]"){
                if ($buffer!==""){
                    $js_array_slice[] = $this->make_array($buffer);
                }
                $this->enum_counter--;
                return array("token"=>JT_ENUM,"value"=>$js_array_slice);
            }

            /**
             * COMMENT SINGLE LINE scope == //
             */
            if ($current_scope === JT_COMMENT){
                $do = true;
                do{
                    if (ord($this->next()) !== 10){
                        // amíg nincs új sor addig comment és eldobás van,
                    }else{
                        return;
                        $do = false;
                    }
                }while($do === true);
                return;
            }

            /**
             * COMMENT MULTI LINE scope == /* ...
             */
            if ($current_scope === JT_COMMENT_MULTI){
                // amíg nincs */ lezárás addig loop
                $do = true;
                do{
                    if ($char === '*' && $this->next2() === '/'){
                        $this->next();
                        return;
                        $do = false;
                        // ha komment vége van
                    }
                    $char = $this->next();
                }while($do === true && $char != null);
                return;
            }

            /*******************
             * END OF SCOPE VIEW
             *******************/

            /**
             * STRING BEGIN with '
             */
            if ($char === "'"){
                if ($buffer!==""){
                    $js_array_slice[] = $this->make_array($buffer);
                    $buffer = "";
                }
                $js_array_slice[] = $this->arrayizer2(JT_SSTRING);
                continue;
            }

            /**
             * STRING BEGIN with "
             */
            if ($char === '"'){
                if ($buffer!==""){
                    $js_array_slice[] = $this->make_array($buffer);
                    $buffer = "";
                }
                $js_array_slice[] = $this->arrayizer2(JT_DSTRING);
                continue;
            }

            /**
             * BRACKET begin with (
             */
            if ($char === "("){
                if ($buffer!==""){
                    $js_array_slice[] = $this->make_array($buffer);
                    $buffer = "";
                }

                // ( char possibly end of FUNCTION NAME token
                $token = $tmp = array_pop($this->token_stack);
                $token = explode("|",$token);
                if ($token[0] == "FUNCTION" && $this->get_counters() == $token[1]){
                    if (isset($token[2]) && $token[2] == 'COLLECT_NAME'){
                        $this->debugthis("HINT", 'FUNCTION, cont. function ?('.$token[1]);
                        unset($token[2]);
                        $tmp = implode("|",$token);
                    }
                }
                array_push($this->token_stack,$tmp);

                $this->bracket_counter++;
                $js_array_slice[] = $this->arrayizer2(JT_BRACKET);
                continue;
            }

            /**
             * BLOCK begin with {
             */
            if ($char === "{"){
                if ($buffer!==""){
                    $js_array_slice[] = $this->make_array($buffer);
                    $buffer = "";
                }
                $this->block_counter++;
                $js_array_slice[] = $this->arrayizer2(JT_BLOCK);
                continue;
            }

            /**
             * ENUM begin with [
             */
            if ($char === "["){
                if ($buffer!==""){
                    $js_array_slice[] = $this->make_array($buffer);
                    $buffer = "";
                }
                $this->enum_counter++;
                $js_array_slice[] = $this->arrayizer2(JT_ENUM);
                continue;
            }

            /**
             * CMD_END char ;
             * line end & VAR token end
             */

            if ($char === ";"){
                if ($buffer!==""){
                    $js_array_slice[] = $this->make_array($buffer);
                    $buffer = "";
                }
                // ; char possibly end of VAR token
                $token = $tmp = array_pop($this->token_stack);
                $token = explode("|",$token);
                if ($token[0] == "VAR" && $this->get_counters() == $token[1]){
                    $this->debugthis("JT_KEYWORD", 'var, END OF '.$token[1]);
                }else{
                    array_push($this->token_stack,$tmp);
                }

                if ($this->next_nospace() !== "}")
                    $js_array_slice[] = array("token"=>JT_OPERATOR,"value"=>$char);
                continue;
            }

            /**
             * CMD_SELECTOR chars . and nameselector chars
             */
            if ($char === "." || in_array($char, $this->NAMESEPARATOR_CHARS,true)){
                if ($char === '?'){
                    // IF statement BEGIN
                    $counters = $this->get_counters();
                    array_push($this->if_stack,"IF?|".$counters);
                    $this->debugthis("JT_KEYWORD", 'IF? STATEMENT BEGIN | '.$counters);
                }

                if ($buffer!==""){
                    $this->debugthis("NAMESEPARATOR FOUND, BUFFER:", $buffer);
                    $js_array_slice[] = $this->make_array($buffer);
                    $buffer = "";
                }
                $js_array_slice[] = array("token"=>JT_OPERATOR,"value"=>$char);
                continue;
            }

            /**
             * CMD_SELECTOR chars :
             * ha név van előtte akkor azt nem szabad rövidíteni
             */
            if ($char === ":"){
                if ($buffer!==""){
                    // : char possibly end of IF? token
                    $token = $tmp = array_pop($this->if_stack);
                    $token = explode("|",$token);
                    $need_rename = true;
                    if ($token[0] == "IF?" && $this->get_counters() == $token[1]){
                        // IF ? : end
                        $this->debugthis("JT_KEYWORD", 'IF?, END OF '.$token[1]);
                    }else{
                        // name :
                        array_push($this->if_stack,$tmp);
                        $this->debugthis("COLON FOUND (NO RENAME!), BUFFER:", $buffer);
                        $need_rename = false;
                    }

                    $js_array_slice[] = $this->make_array($buffer, false, $need_rename);
                    $buffer = "";
                }
                $js_array_slice[] = array("token"=>JT_OPERATOR,"value"=>$char);
                continue;
            }

            /**
             * CMD_SELECTOR char ,
             */
            if ($char === ","){
                if ($buffer!==""){
                    $js_array_slice[] = $this->make_array($buffer);
                    $buffer = "";
                }

                // , char keep in VAR name collect
                $token = $tmp = array_pop($this->token_stack);
                $token = explode("|",$token);
                if ($token[0] == "VAR" && $this->get_counters() == $token[1]){
                    $this->debugthis("JT_KEYWORD", 'name collection waiting for , or ;');
                    $tmp = $token[0] . "|" . $token[1];
                }
                array_push($this->token_stack,$tmp);

                $js_array_slice[] = array("token"=>JT_OPERATOR,"value"=>$char);
                continue;
            }

            /**
             * WHITESPACE TRIMMING
             */
            if (ord($char) <= 32){
                $buffer = trim($buffer);
                $prev_nospace = $this->prev_nospace();
                $next_nospace = $this->next_nospace();
                if ($buffer!==""){
                    //$this->debugthis("SPACE FOUND, BUFFER:", $buffer);
                    // before, after no space req.
                    if (in_array($prev_nospace,$this->CONST_NOSPACEPLEASE,true) || in_array($next_nospace,$this->CONST_NOSPACEPLEASE,true)){
                        $need_space = false;
                        // if both of chars equivalent near space, then need space
                        if ($prev_nospace === $next_nospace){
                            $need_space = true;
                        }
                    }else
                        $need_space = true;

                    // find : sign
                    $need_rename = true;
                    if ($next_nospace === ':'){
                        // : char possibly end of IF? token
                        $token = $tmp = array_pop($this->if_stack);
                        $token = explode("|",$token);
                        if ($token[0] == "IF?" && $this->get_counters() == $token[1]){
                            // IF ? : end
                            $this->debugthis("JT_KEYWORD", 'IF?, END OF (space) | '.$token[1]);
                        }else{
                            // name :
                            $this->debugthis("COLON FOUND (NO RENAME!), BUFFER:", $buffer);
                            $need_rename = false;
                            array_push($this->if_stack,$tmp);
                        }
                    }

                    $js_array_slice[] = $this->make_array($buffer,$need_space);
                    $buffer = "";
                }else{
                    // ha nincs buffer akkor is nézzük a : jelet
                    if ($next_nospace === ':'){
                        // : char possibly end of IF? token
                        $token = $tmp = array_pop($this->if_stack);
                        $token = explode("|",$token);
                        if ($token[0] == "IF?" && $this->get_counters() == $token[1]){
                            // IF ? : end
                            $this->debugthis("JT_KEYWORD", 'IF?, END OF (space) | '.$token[1]);
                        }else{
                            // name :
                            $this->debugthis("COLON FOUND (NO RENAME!), BUFFER:", $buffer);
                            array_push($this->if_stack,$tmp);
                        }
                    }

                    // ha nincs buffer, de kell szóköz pl i=i+ ++i; -> nem lehet i+++i;
                    if ($prev_nospace === $next_nospace &&
                            $prev_nospace !== ')' && $prev_nospace !== '}' && $prev_nospace !== ']' &&
                            $prev_nospace !== '(' && $prev_nospace !== '{' && $prev_nospace !== '['){
                        $js_array_slice[] = $this->make_array(" ", true);
                    }
                }
                continue;
            }

            /**
             * OPERATOR (EQ) char = , not eq. with == or ===
             */
            if ($this->prev() !== "=" && $char === "=" && $this->next2() !== "="){
                $buffer = trim($buffer);
                if ($buffer!==""){
                    $js_array_slice[] = $this->make_array($buffer);
                    $buffer = "";
                }

                // = char possibly end of VAR name collect
                $token = $tmp = array_pop($this->token_stack);
                $token = explode("|",$token);
                if ($token[0] == "VAR" && $this->get_counters() == $token[1]){
                    $this->debugthis("JT_KEYWORD", 'name collection waiting for , or ;');
                    $tmp .= "|WAIT";
                }
                array_push($this->token_stack,$tmp);

                $js_array_slice[] = array("token"=>JT_OPERATOR,"value"=>$char);
                continue;
            }

            /**
             * REGEXP BEGIN with /
             */
            if (in_array($this->prev_nospace(),$this->REGEXP_BEFORE_EXPR,true) && $char === "/" && $this->next2() !== "/"){
                if ($buffer!==""){
                    $js_array_slice[] = $this->make_array($buffer);
                    $buffer = "";
                }
                $js_array_slice[] = $this->arrayizer2(JT_REGEX);
                continue;
            }

            /**
             * COMMENT SINGLE LINE BEGIN with //
             */
            if ($char === "/" && $this->next2() === "/"){
                if ($buffer!==""){
                    $js_array_slice[] = $this->make_array($buffer);
                    $buffer = "";
                }
                $this->arrayizer2(JT_COMMENT);
                // opt
                if ($this->next_nospace() === "}"){
                    $tmp = array_pop($js_array_slice);
                    if (isset($tmp['value']) && $tmp['value'] === ';')
                        ;
                        else
                        $js_array_slice[] = $tmp;
                }
                continue;
            }

            /**
             * COMMENT MULTI LINE BEGIN with /*
             */
            if ($char === "/" && $this->next2() === "*"){
                if ($buffer!==""){
                    $js_array_slice[] = $this->make_array($buffer);
                    $buffer = "";
                }
//                $js_array_slice[] = $this->arrayizer2(JT_COMMENT_MULTI);
                $this->arrayizer2(JT_COMMENT_MULTI);
                continue;
            }

            /**
             * ANY CHAR to buffer
             */
            $buffer .= $char;
        }

        // reset counters
        $this->block_counter = 0;
        $this->bracket_counter = 0;
        $this->enum_counter = 0;
        return $js_array_slice;
    }

    /**
     * get values of counter, in order: block_bracket_enum
     * @return string
     */
    private function get_counters(){
        return $this->block_counter."_".$this->bracket_counter."_".$this->enum_counter;
    }

    /**
     * UNUSED - check the actual state of counter
     * @param int $block - checked block counter
     * @param int $bracket - checked bracket counter
     * @param itn $enum - checked enum counter
     * @return boolean - true if equal
     */
    private function check_counter($block, $bracket, $enum){
        if ($block == $this->block_counter && $bracket == $this->bracket_counter && $enum == $this->enum_counter)
            return true;
        else
            return false;
    }

    private $global_vars = 0;               // new varname counter
    private $global_var_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; // char for varnames
    private $global_names = array();        // global varnames
    private $varnames_table = array();      // varname table for new varnames
    private $varnames_stat = array();       // varname stat table
    private $varnames_counter = array();    // varname counter - frequency table

    /**
     * create a new short varname
     * @return string - new short varname
     */
    private function get_newvarname(){
        $v = floor($this->global_vars / 52);
        $vv = 0;
        if ($v > 0)
            $vv = $this->global_vars % 52;

        $n = "";
        if ($v == 0)
            $n = $this->global_var_chars[$this->global_vars];
        else{
            $n .= $this->global_var_chars[$v-1];
            $n .= $this->global_var_chars[$vv];
        }

        if ($n == 'do' || $n == 'if' || $n == 'in'){
            $n = '_'.$n;
        }

        $this->global_vars++;
        return $n;
    }

    /**
     * UNUSED - Search a varname in global_names
     * @param type $word
     * @return boolean
     */
    private function search_name($word){
        foreach($this->global_names as $k => $v){
            if (array_search($word,$v) !== false)
                    return $k;
        }
        return false;
    }

    /**
     * Make array for a word
     * @param string $word - word
     * @param boolean $need_space - if need a space : word." "
     * @param boolean $need_rename - if need rename the word (if it's a VARNAME)
     * @return array - word in array with other parameters:
     * 'token' => the token name (keyword, word...)
     * 'value' => original name of word
     * 'type' => type of token (VAR||FUNCTION)
     * 'need_space' => if need some space on the right
     * 'need_rename' => if need the short name for word
     */
    private function make_array($word,$need_space = false, $need_rename = true){
        $word = trim($word);
        $type = NULL;
        // classify
        $T = $this->classify_word2($word);
        // if var name
        $var_name = preg_match($this->r_name, $word);
        // var name build
        if ($T === JT_WORD && $word !== '' && $var_name){
            $token = $tmp = array_pop($this->token_stack);
            $token = explode("|",$token);
            if ($token[0] == 'VAR' && !isset($token[2])){
                $type = "var";
                $this->varnames_counter[$word]++;
            }
            if ($token[0] == 'FUNCTION'){
                $type = "function";
                if (isset($token[2]) && $token[2] == 'COLLECT_NAME'){
                    // function NAME begyüjtésre várás van,
                }else{
                    $this->varnames_counter[$word]++;
                }
            }

            array_push($this->token_stack,$tmp);
        }

        if ($word == 'in') $word = " ".$word;

        return array("token"=>$T,"value"=>$word,"type"=>$type, "need_space" => $need_space, "need_rename" => $need_rename);
    }

    /**
     * Classify the word (second edition)
     * @param string $word - the word
     * @return int - token
     */
    private function classify_word2($word){
        $msg = "";
        if (in_array($word,$this->KEYWORDS_ALL,true)){
            $this->last_keyword = $word;
            if ($word == "in"){
                // IN keyword possibly end of VAR name collect
                $token = $tmp = array_pop($this->token_stack);
                $token = explode("|",$token);
                if ($token[2] != "WAIT" && $token[0] == "VAR" && $this->get_counters() == $token[1]){
                    $this->debugthis("JT_KEYWORD", 'name collection waiting for , or ;');
                }else
                    array_push($this->token_stack,$tmp);
            }
            if ($word == "var"){
                $counters = $this->get_counters();
                $msg = ", START VAR|".$counters;
                array_push($this->token_stack,"VAR|".$counters);
            }

            if ($word == "function"){
                $counters = $this->get_counters();
                $msg = ", START FUNCTION|".$counters."|COLLECT_NAME";
                array_push($this->token_stack,"FUNCTION|".$counters."|COLLECT_NAME");
            }

            $this->debugthis("JT_KEYWORD",$word.$msg);
            return JT_KEYWORD;
        }
        if (ctype_digit($word)){
            $this->debugthis("JT_NUMERIC_LITERAL",$word);
            return JT_NUMERIC_LITERAL;
        }

        $this->debugthis("JT_WORD",$word);
        return JT_WORD;
    }


private $last_operator = "";    // the last operator
private $last_token = "";       // the last token

    /**
     * JS min source builder (second edition)
     * @param array $js_array - JS stuff from arrayizer2
     * @return
     */
    private function jsmin_builder2($js_array){
        foreach($js_array as $item){
            if($item['token'] === JT_SSTRING){
                $this->jsmin_add("'".$item['value']."'", $item);
            }
            if($item['token'] === JT_DSTRING){
                $this->jsmin_add('"'.$item['value'].'"', $item);
            }
            if($item['token'] === JT_NUMERIC_LITERAL){
                $this->jsmin_add($item['value'], $item);
            }

            if($item['token'] === JT_WORD){
                // ha szó előtt . van akkor nem kérjük le a rövid nevet
                if ($this->last_token === '.' || ($this->bracket_counter === 1 && $this->block_counter === 0 && $this->last_keyword != "function")){
                    $this->jsmin_add($item['value'], $item);
                    continue;
                }

                // rövidnév lekérése
                if($item['need_rename']){
                    if (isset($this->varnames_table[$item['value']])){
                        $this->jsmin_add($this->varnames_table[$item['value']], $item);
                        continue;
                    }
                }
                $this->jsmin_add($item['value'], $item);
            }
            if($item['token'] === JT_KEYWORD){
                // false to !1 converter
                if (strtolower($item['value']) == 'false')
                    $item['value'] = '!1';
                // true to 1 converter
                if (strtolower($item['value']) == 'true')
                    $item['value'] = '!0';
                $this->jsmin_add($item['value'], $item);
                $this->last_keyword = $item['value'];
            }
            if($item['token'] === JT_REGEX){
                $this->jsmin_add($item['value'], $item);
            }
            if($item['token'] === JT_OPERATOR){
                $this->jsmin_add($item['value'], $item);
                $this->last_operator = $item['value'];
            }
            if($item['token'] === JT_BRACKET){
                $this->bracket_counter++;
                $this->jsmin_str .= "(";
                if (gettype($item['value']) == 'string'){
                    $this->jsmin_add($item['value'], $item);
                }else{
                    $this->jsmin_builder2($item['value']);
                }
                $this->jsmin_add(")", $item);

                $this->bracket_counter--;
            }
            if($item['token'] === JT_BLOCK){
                $this->block_counter++;
                $this->jsmin_str .= "{";
                if (gettype($item['value']) == 'string'){
                    $this->jsmin_add($item['value'], $item);
                }else
                    $this->jsmin_builder2($item['value']);

                $this->jsmin_add("}", $item);

                $this->block_counter--;
            }
            if($item['token'] === JT_ENUM){
               $this->jsmin_str .= "[";
               if (gettype($item['value']) == 'string'){
                   $this->jsmin_add($item['value'], $item);
               }else{
                    $this->jsmin_builder2($item['value']);
               }
               $this->jsmin_add("]", $item);
            }

        }
        return;
    }

    /**
     * JS varname stat creator
     * @param array $js_array - JS stuff from arrayizer2
     */
    private function jsmin_builder_stat($js_array){
        foreach($js_array as $item){
            if($item['token'] === JT_WORD){
                if ($this->last_token === '.' || ($this->bracket_counter === 1) && $this->block_counter === 0){
                    continue;
                }
                // rövidnév lekérése
                if($item['need_rename']){
                    if (isset($this->varnames_counter[$item['value']])){
                        $this->varnames_stat[$item['value']]++;
                        continue;
                    }
                }
            }
            if($item['token'] === JT_BRACKET){
                $this->bracket_counter++;
                if (gettype($item['value']) == 'string'){
                }else{
                    $this->jsmin_builder_stat($item['value']);
                }
                $this->bracket_counter--;
            }
            if($item['token'] === JT_BLOCK){
                $this->block_counter++;
                if (gettype($item['value']) == 'string'){
                }else
                    $this->jsmin_builder_stat($item['value']);
                $this->block_counter--;
            }
            if($item['token'] === JT_ENUM){
               if (gettype($item['value']) == 'string'){
               }else{
                    $this->jsmin_builder_stat($item['value']);
               }
            }
        }
    }

    /**
     * JS text add to final source
     * @param string $str - JS element
     * @param array $item - token array
     */
    private function jsmin_add($str, $item){
        $this->jsmin_str .= $str;
        if ($item['need_space'])
            $this->jsmin_str .= " ";
        $this->last_token = $item['value'];
    }
}
?>