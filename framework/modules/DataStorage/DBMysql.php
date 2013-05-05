<?php 

class DBMysql extends DBDrivers {
	
	protected $connection;
	protected $db_data;
	protected $query_data;
	protected $free_result;
	public $server_info = array();	
	
	/** Mysql protected commands*/
	protected $mysql_db_protect = array(
											"MYSQL.USER", 
											"SET PASSWORD FOR", 
											"FLUSH", 
											"PRIVILEGES", 
											"GRANT", 
											"USEAGE", 
											"USER()", 
											"DATABASE()", 
											"SHOW DATABASES", 
											"SHOW TABLES", 
											"CREATE", 
											"DESCRIBE", 
											"LOAD", 
											"INFINE", 
											"LINES", 
											"LOCAL", 
											"DROP",
											"UNLOCK"
										);	
										
	public function __construct($db_data){
		
		$this->db_data = $db_data;	
		$this->db_connect();
		$this->check_connection();	
			
	}
	
	protected function db_connect() {
		
		if($this->db_data['p_connection']){

			$this->connection = @mysql_pconnect($this->db_data['host'] , $this->db_data['user'], $this->db_data['pass']);

		}else{

			$this->connection = @mysql_connect($this->db_data['host'] , $this->db_data['user'], $this->db_data['pass']);
		}
	}

	protected function check_connection() {	
		
		if(!$this->connection){
			
			$this->System_Module_Error_TIDErrors->show_error_info('308', array('::SERVER::'=>$this->db_data['host']));
			
		}
	}
	
	public function check_database () {
		
		if(!@mysql_select_db($this->db_data['db_name'], $this->connection)) {
			
			$this->System_Module_Error_TIDErrors->show_error_info('309', array('::DATABASE::'=>$this->db_data['db_name']));

		}
	}
	 
	public function ping_server () {
		
		if (!@mysql_ping($this->connection)) {
			
			$this->System_Module_Error_TIDErrors->show_error_info('310', array('::SERVER::'=>$this->db_data['host']));	

		}		
	}
	
	protected function connection_close () {
		
		if(!$this->db_data['p_connection']){

			if(!@mysql_close($this->connection)){
				
				$this->System_Module_Error_TIDErrors->show_error_info('311', array('::SERVER::'=>$this->db_data['host']));	
					
			}

		}else{
			
			if(!register_shutdown_function(array('this'=>'shutdown'))){
				
				$this->System_Module_Error_TIDErrors->show_error_info('312', array('::SERVER::'=>$this->db_data['host']));	
					
			}
		}
	}
	
	protected function sql_query($query_string, $db_operation=false, $commands_unlock_key=false) {
		
		if( $this->db_data['admin_mode'] || ($commands_unlock_key !== $this->db_data['master_key']) ){
			
			$upper_query_string = strtoupper($query_string);
			
			foreach($this->mysql_db_protect as $key => $command){
				
				$find_command = strpos($query_string, $command);
	
				if ($find_command) {
					
					$this->System_Module_Error_TIDErrors->show_error_info('313');
					
				}			
			}
		}
		
		$this->query_data['id'] =  @mysql_query($query_string , $this->connection);
		
		if (!$this->query_data['id']) {
			
			$this->System_Module_Error_TIDErrors->show_error_info('314', array('::QUERY::'=>$db_operation, '::DATABASE::'=>$this->db_data['db_name']));	
			exit();
		}
		
		if(preg_match('/SELECT|SHOW/', $query_string)){

			$this->query_data['num_rows'] = @mysql_num_rows($this->query_data['id']);

		}else{

			$this->query_data['affected_rows'] = @mysql_affected_rows($this->query_data['id']);

		}
		
		if($this->db_data['debug']){

			$this->debug($query_string, $operation);
				
		}

		return $this->query_data;	

	}
	
	protected function free_results(){
		/*  database free_results SELECT, SHOW, EXPLAIN, and DESCRIBE query.*/
		
		if($this->query_data['id'] !== 0 && !@mysql_free_result($this->query_data['id'])) {
			
			$this->System_Module_Error_TIDErrors->show_error_info('315', array('::QUERY_ID::'=>$this->query_data['id']));	
			
			$this->free_result = true;
			
		}else{
			
			$this->free_result = false;
		}
	}
	
	public function db_create($db_name, $db_settings='', $drop_old=false, $commands_unlock_key=false){
		
		if(!isset($db_settings['char_set'])){
			
			$db_settings['char_set'] = $this->db_data['char_set'];
			
		}
		
		if(!isset($db_settings['collate'])){
			
			$db_settings['collate'] = $this->db_data['collate'];
			
		}

		if($drop_old){
			
			$this->db_drop($db_name, 'DATABASE', $commands_unlock_key);
				
		}
		
		$query_construct = "CREATE DATABASE IF NOT EXISTS " . $db_name . 
				 "DEFAULT CHARACTER SET " . $db_settings['char_set'] . 
				 "COLLATE " . $db_settings['collate'];
		
		
		$this->sql_query($query_construct, 'CREATE DATABASE', $commands_unlock_key);		
		
	}
	
	// $table_data =  array( 
	//						 'field_name'=>array('field_type', 'field_data'), 
	//						 'keys'=>array('primary'=>('pre_hook'=>'index_all', 'key_names'=>'primary_key_field_name'), 'forein'=>'forein_key_field_name),
	//						 'autoincrement'=>('true', 'start_field_number'),
	//						 'drop_if_exist'=>'true'
	//						);
	public function db_table_create($table_name, $table_data, $commands_unlock_key=false) { 
		
		if($table_data['drop_if_exist']){
			
			$this->db_drop($table_name, 'TABLE');
			
		}
	
		$query_construct = 'CREATE TABLE IF NOT EXISTS ' . $table_name . '(';
		
		foreach ($table_data['field_name'] as $field => $field_data){
			
			$query_construct .=  $this->quotation($table_data['field_type']) . ' ' . $table_data['field_data'] . ', ';
			
		}
		
		foreach ($table_data['keys'] as $key => $field_name){
			
			$query_construct .= strtoupper($key) . ' ';
			
			if(isset($field_name['pre_hook'])) {

				$query_construct .= $this->quotation($field_name['pre_hook']) . ' ';

				unset($field_name['pre_hook']);
				
			}
			
			if(is_array($field_name)){
				
				$query_construct .= '(';
				
				foreach($field_name as $filed_keys => $fields){
					
					if(end($field_name) == $fields){
						
						$query_construct .= $this->quotation($fields);
					
					}else{
						
						$query_construct .= $this->quotation($fields) . ', ';
						
					}
					
				}
				
				if(end($table_data['keys']) !== $key ){
				
						$query_construct .= '), ';
					
				}else{
					
					$query_construct .= ')';
				
				}
			}
		}		
		
		$ai = '';
		
		if(	$table_data['autoincrement']['0']){
			
			$ai = ' AUTO_INCREMENT=' . $table_data['autoincrement']['1']; 
			
		}
		
		$query_construct .= ')  ENGINE=' . $this->db_data['engine'] . ' DEFAULT CHARSET=' . $this->db_data['char_set'] . $ai;
		
		$this->sql_query($query_construct, 'CREATE TABLE', $commands_unlock_key);		
	}
	

	
	public function db_drop($db_or_table_name, $db_or_table, $commands_unlock_key=false) { /** DATABASE|TABLE */
	
		$query_construct = "DROP " . strtoupper($db_or_table). " IF EXISTS " . $db_or_table_name . "";
	
		$this->sql_query($query_construct, 'DROP DATABASE', $commands_unlock_key);

	}

/**
 * 
 * Enter description here ...
 * @param unknown_type $set_command tables|columns
 * @param unknown_type $list_columns
 * @param unknown_type $commands_unlock_key
 */
	public function db_tables_show($list_columns=false, $commands_unlock_key=false ){
			
		$columns_list = array();
		$columns_data = array();
		
		$query_construct = "SHOW TABLES FROM " . $this->db_data['db_name'] . "";
		
		$db_tables = $this->sql_query($query_construct, 'SHOW TABLES', $commands_unlock_key);	
			
		$columns_list = $this->db_fetch($db_tables, 'row');
		
		if ($list_columns) {
			
			$columns_data = $this->db_columns_show($columns_list);

			
		}else{
			
			$columns_data = $columns_list;
			
		}
		
		return $columns_data;
	}
		
	public function db_columns_show($tables){
		
		if(is_array($tables)){
			
			foreach($tables as $key=>$db_table){

				$query_construct = "SHOW COLUMNS FROM ".$db_table."";
				
				$result = $this->sql_query($query_construct, 'LISTING TABLE COLUMNS');
				
				
				if ($this->query_data['affected_rows'] > 0) {

					$data = $this->db_fetch($result);

				}
				
			}		
			
		}else{
			
			$query_construct = "SHOW COLUMNS FROM ".$tables."";
				
			$result = $this->sql_query($query_construct, 'LISTING TABLE COLUMNS');	
				
			if ($this->query_data['affected_rows'] > 0) {

					$data = $this->db_fetch($result);

			}
			
		}
		
		return $data;
	}
	
	private function db_fetch($db_data, $fetch_type = 'assoc'){
		
		$db_records = array();
		
		switch ($fetch_type) {
			case 'array':
				while($row = mysql_fetch_array($db_data, MYSQL_BOTH)){
					$db_records[]= $row;
				}
			break;
			case 'row':
				while($row = mysql_fetch_row($db_data)){
					$db_records[]= $row;
				}
			break;
			case 'object':
				while($row = mysql_fetch_object($db_data)){
					$db_records[]= $row;
				}
			break;	
			case 'field':
				while($row = mysql_fetch_field($db_data)){
					$db_records[]= $row;
				}
			break;
			case 'lengths':
				while($row = mysql_fetch_lengths($db_data)){
					$db_records[]= $row;
				}
			break;						
			default:
				while($row = mysql_fetch_assoc($data)){
					$db_records[]= $row;
				}	
			break;
		}
		return $db_records;
	}
	
	public function db_table_prefix($action=false, $new_prefix=false, $old_prefix=false, $prefix_separator=false, $commands_unlock_key=false){ // change|remove|add
		
		$db_tables_new = array();
		$db_tables_old = array();
//				  a) change prefix: $action = "change", $new_prefix = "new prefix", $old_prefix = "old prefix", 
//				  b) remove prefix: $action = "add",    $new_prefix = "new prefix", $old_prefix = "false", 
//				  c) add prefix:	$action = "remove", $new_prefix = "false",      $old_prefix = "old_prefix", 		

		if( !$new_prefix || !$action ){
			
			$this->System_Module_Error_TIDErrors->show_error_info('317');	
			exit();
		}
		
		$db_tables_old = $this->db_tables_show(false, $commands_unlock_key);
		
		if($prefix_separator == false){
			
			if( $this->db['prefix_separator'] !== ''){
				
				$prefix_separator = $this->db['prefix_separator'];
				
			}else{
				
				$this->System_Module_Error_TIDErrors->show_error_info('321');	
				exit();
				
			}
			
		}
		
		if($action == "change" || $action == "remove"){
			
			if(!$old_prefix){
				
				$table_prefix = $this->db_get_table_prefix($db_tables_old, $prefix_separator);
				
			}else{
				
				$table_prefix = $old_prefix;
				
			}
			
		
		}elseif($action == "add"){
			
			$table_prefix = false;
		
		}else{
			
			$this->System_Module_Error_TIDErrors->show_error_info('320');	
			exit();
			
		}
		
		foreach($db_tables_old as $key => $db_table_name){
			
			if ($table_prefix == false) {
				
				$db_tables_new[$key] =  $new_prefix . $prefix_separator . $db_table_name ;
				
			}else{
				
				$db_tables_new[$key] = str_replace($table_prefix, $new_prefix, $db_table_name);
				
			}
			
			$query_construct =  'RENAME TABLE ' . $db_table_name . ' TO ' . $db_tables_new[$key] . '';
			
			$result = $this->sql_query($query_construct, 'CHANGING TABLE PREFIX', $commands_unlock_key);
			
		}
			
		$this->connection_close();
		
	}

	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $table_names - multidimensional array eg: $table_names = array('old table name'=>'new table name')
	 */
	public function db_rename_table($table_names){

		foreach ($table_names as $old_name => $new_name) {
			
			$query_construct =  'RENAME TABLE ' . $old_name . ' TO ' . $new_name . '';
			
			$result = $this->sql_query($query_construct, 'RENAMING TABLES');
			
			if (!$result) {
				
				$this->System_Module_Error_TIDErrors->show_error_info('320',  array('::DB_TABLE::', $old_name));	
				continue;
			}
			
		}
		
		$this->connection_close();
		return true;
		
	}
	
	private function db_get_table_prefix($table_names, $prefix_separator){
		
		$get_prefix = array();
		
		foreach ($table_names as $key => $value) {
			
			$prefix_position = strpos($value, $prefix_separator);
			
			if(!empty($current_prefix)){
				
				$get_prefix[$key] = substr($value, 0, $prefix_position + 1);
				
			}else{
				
				$this->System_Module_Error_TIDErrors->show_error_info('318');	
				exit();				
				
			}
			
		}
		
		$check_diff = array_unique($get_prefix);

		if(count($check_diff) == 1){
			
			return $get_prefix;
		
		}else{
			
			$this->System_Module_Error_TIDErrors->show_error_info('319');	
			exit();			
		}
	}
	

	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $table_fields - string|array, multidimension array for multiple tables 
	 * 
	 * eg. array('table name'=>array('table_fileds' => array('filed_1, filed_2...'),
	 * 								  'condition'	=> '')
	 * 
	 * @param unknown_type $db_table - bool|table name - false if we use multiple tables query even if not and we want to define data as array
	 * @param unknown_type $condition - where etc, etc...
	 * @param unknown_type $fetch_type - look at function db_fetch() default associate + 
	 * if you wat to get value of specific row - you must define var as array 
	 * EG. array('specific_row'=>'row number')
	 * 
	 */
	public function db_select($table_fields, $db_table, $condition, $fetch_type) { 
		
		$data = array();
		
		if(!$db_table){
			
			
			foreach ( $table_fields as $table_name => $table_data ){
				
				$fileds = implode(",", $table_data['table_fileds']);
				
				$query_construct =  "SELECT " . $fileds . " FROM " . $table_name . " " . $table_data['condition'] . "";
				
				$result = $this->sql_query($query_construct, 'SELECT DATA');	

				if( !is_array($fetch_type) ){
					
					$data[$table_name] = mysql_result($result,  $fetch_type['specific_row']);
					
				}else{
					
					$data[$table_name] = $this->db_fetch($result);
					
				}
	
			}
			
		}else{
			
			$query_construct =  "SELECT " . $table_fields . " FROM " . $db_table . " " . $condition . "";	
					
			$result = $this->sql_query($query_construct, 'SELECT DATA');	
			
			$data[$db_table] = $this->db_fetch($result);
		}
		
		mysql_free_result($result);
		
		$this->connection_close();
		
		return $data;
	
	}
	
	
	
	
	
	
	/**
	 * Display Mysql/ Database informations functions
	 */	
	
	public function get_sever_data(){
		
		$this->server_info['server_info'] = mysql_get_server_info(); 
		$this->server_info['host_info'] = mysql_get_host_info();
		$this->server_info['client_info'] = mysql_get_client_info();
		$this->server_info['protocol_info'] = mysql_get_proto_info();	
		
	}	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * Helper Funkcije, samo za manipulaciju podacima
	 * 
	 */
	private function quotation($string){

		return '`' . $string .  '`';
	
	}
	
	private function slashes($string){
		
		return "'$string'";	
		
	}
	
	protected function convert_data_type($query_data){
		//----------------------------------------------------------------
		// $query_data['fields'] = '1, 2, 3, 4';
		// $query_data['values'] = 'data_1, data_2, data_3, data_4';
		//----------------------------------------------------------------
		// $query_data['fields'] = array('1, 2, 3, 4');
		// $query_data['values'] = array('data_1, data_2, data_3, data_4);		
		//----------------------------------------------------------------
		// $query_data = array('1'=>'data_1', '2'=>'data_2', '3'=>'data_3', '4'=>'data_4');		
		//----------------------------------------------------------------
		$data = array();
		
		if(isset($query_data['fileds']) && isset($query_data['values'])){
			
			if(!is_array($query_data['fileds'])){
				
					$data['fileds'] = explode(',', $query_data['fields']);
			}
			
			if(!is_array($query_data['values'])){
				
					$data['values'] = explode(',', $query_data['values']);
			}			
			
		}else{
			
			$data['fileds'] = array_keys( $query_data );
			$data['values'] = array_values( $query_data );		
			
		}
		
		$fields_number = count($data['fileds']);
		$values_number = count($data['values']);
				
		if($fields_number !== $values_number){
			
			$this->System_Module_Error_TIDErrors->show_error_info('316', array('::FILEDS::'=>$fields_number, '::VALUES::'=>$values_number));	
			exit();
			
		}else{
			
			return array_combine($data['fileds'], $data['values']);
			
		}
		
	}
	
		
}