<?php

class DataStorage extends Model {

	protected $db = array();
	public $database_load;
	public $debug;

	public function __construct() {

		if(APP_DB_CONFIG == false && MASTER_DB_CONFIG == false){

			$this->System_Module_Error_TIDErrors->show_error_info('300');

		}

		if(!file_exists(APP_DB_CONFIG) && !file_exists(MASTER_DB_CONFIG)){

			$this->System_Module_Error_TIDErrors->show_error_info('301');

		}

		if(file_exists(APP_DB_CONFIG)){

			include(APP_DB_CONFIG);

		}else{

			include(MASTER_DB_CONFIG);

		}




		if(!$_DB_DATABASE){

			$this->System_Module_Error_TIDErrors->show_error_info('302');

		}else{

			$this->db['type'] = $_DB_DATABASE;

		}

		if(!$_DB_HOST){

			$this->System_Module_Error_TIDErrors->show_error_info('303');

		}else{

			$this->db['host'] = $_DB_HOST;

		}

		if(!$_DB_NAME){

			$this->System_Module_Error_TIDErrors->show_error_info('304');

		}else{

			$this->db['db_name'] = $_DB_NAME;

		}
			if(!$_DB_USER){

			$this->System_Module_Error_TIDErrors->show_error_info('305');

		}else{

			$this->db['user'] = $_DB_USER;

		}

		if(!$_DB_PASS){

			$this->System_Module_Error_TIDErrors->show_error_info('306');

		}else{

			$this->db['pass'] = $_DB_PASS;

		}

		$this->db['p_connection'] = $_DB_CONN_TYPE;
		$this->db['table_prefix'] = $_DB_TABLE_PREFIX;
		$this->db['debug'] = $_DB_DEBUGG;
		$this->db['errors'] = $_DB_ERRORS;
		$this->db['admin_mode'] = $_DB_PROTECTION;
		$this->db['master_key'] = $_DB_MASTER_KEY;

		$this->db['engine'] = $_DB_ENGINE;
		$this->db['char_set'] = $_DB_CHARACTER_SET;
		$this->db['collate'] = $_DB_COLLATE;
		$this->db['prefix_separator'] = $_DB_PREFIX_SEPARATOR;
		$this->load_driver();

	}

	public function load_driver() {

		switch ($this->db['type']) {

			case 'MYSQL':
			include('DBMysql.php');
			$this->database_load = new DBMysql($this->db);
			break;

			case 'MYSQLI':
			include('DBMysqli.php');
			$this->database_load = new DBMysqli($this->db);
			break;

			case 'MSSQL':
			include('DBMSsql.php');
			$this->database_load = new DBMSsql($this->db);
			break;

			case 'POSTGRESQL':
			include('DBPostgresql.php');
			$this->database_load = new DBPostgresql($this->db);
			break;

			case 'SQLITE':
			include('DBSqlite.php');
			$this->database_load = new DBSqlite($this->db);
			break;

			case 'XML':
			include('DTXML.php');
			$this->database_load = new DTXML($this->db);
			break;


			case 'ARRAY':
			include('DTArray.php');
			$this->database_load = new DTArray($this->db);
			break;

			case 'JSON':
			include('DTJSON.php');
			$this->database_load = new DTJSON($this->db);
			break;

			default:
				$this->System_Module_Error_TIDErrors->show_error_info('307');
			break;

		}
	}

	public function escape($query_string) {

		// check magic_quotes_runtime active configuration settings
		if(get_magic_quotes_runtime()){

			$string = stripslashes($query_string);

		}

		return mysql_real_escape_string($query_string);

	}



	public function connection(){

		$this->database_load->datas();
	}

}