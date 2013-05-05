<?php 

class DBMysql extends DBDrivers {
	
	/** Mysql protected commands*/
	protected $mysql_db_protect = array(
											"mysql.user", 
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
										
	public function __construct(){
		
	//	parent::__construct();
		
	}
	
	public function datas() {
		echo 'testTTTTTTTTTTTTTTTTTTT';

	}
}