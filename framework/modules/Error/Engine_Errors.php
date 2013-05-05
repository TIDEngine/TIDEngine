<?php

	$this->error_list = array(
		'0'=>'The URL contains invalid characters, the application cannot continue. By default URL can contain lowercase letters numbers and special characters : _\-.
			  You can chage default settings but for security reasons we do not recommed.',
		'100'=>'Page do not exists.',
                '200'=>'Source Template do not exist!',
                '201'=>'Problem creating cache file.',
                '202'=>'PHP extension zlib is disabled, please enable it or set CACHE_GZIP to false in configuration.php.',
                '203'=>'Default template head.tpl do not exist',
                '204'=>'Default template  footer.tpl do not exist',
                '205'=>'PHP function <i><u> ::FUNCT::</u></i> is not supported!',
                '206'=>'Data for shortcode <i><u> ::SHORTCODE::</u></i> is not defined. Can not procced!'
//		'220'=>'Please Define DEFAULT_SELECTION in your configuration file.',
//		'201'=>'Please Define DEFAULT_CONTROLLER in your configuration file.',
//		'202'=>'Please Define DEFAULT_FUNCTION in your configuration file.',
//
//		'300'=>'Database configuration file path is not defined. You can set it as Application configuration "APP_DB_CONFIG" or you can use TIDEngine configuration constant "MASTER_DB_CONFIG"',
//		'301'=>'Database configuration files do not exists. Please check paths for "APP_DB_CONFIG" and/or "MASTER_DB_CONFIG" depending of configuration file you use.',
//		'302'=>'Database Type is not defined. Avaible types MYSQL|MYSQLI|MSSQL|POSTGRESQL|SQLITE and altenative storage XML|ARRAY|JSON|ARRAY.',
//		'303'=>'Database Server is not defined.',
//		'304'=>'Database Name is not defined.',
//		'305'=>'Database User is not defined.',
//		'306'=>'Database Password is not defined.',
//		'307'=>'Database Storage Type at this time is not supported by TIDEngine.',
//		'308'=>'Could not connect to Database Server on ::SERVER::.',
//		'309'=>'Could not open ::DATABASE:: Database.',
//		'310'=>'Lost connection to Server on ::SERVER::.',
//		'311'=>'Failed to close connection to Server on ::SERVER::.',
//		'312'=>'Persistant connection to Server can not be closed.',
//		'313'=>'This Command is not alowed if you want to use it enable it in your configuration file.',
//		'314'=>'Current query ::QUERY:: to Database ::DATABASE:: failed.',
//		'315'=>'It is not possible to free query with id - ::QUERY_ID::',
//		'316'=>'Number of fields and values must match in Database query: in current query there are ::FILEDS:: and ::VALUES::.',
//		'317'=>'You must define db_table_prefix() function variables to be able to process database tables prefix changes:
//				  a) change prefix: $new_prefix = "new prefix",  $old_prefix = "old prefix", or
//									$new_prefix = "new prefix",  $old_prefix = "change",
//				  b) remove prefix: $new_prefix = "new_prefix", $old_prefix = "remove",
//				  c) add prefix:	$new_prefix = "new prefix",  $old_prefix = "add"',
//		'318'=>'Unable to determine Database tables prefix. Database tables prefixes do not share same patern eg. prefix_tablename, or same separator default "_".
//				Please define old Database tables prefix and separator in function call db_table_prefix().',
//		'319'=>'Different prefixes exists, please change Database tables prefixes maually.',
//	    '320'=>'This action do not exist for Database Tables prefix changes.',
//	    '321'=>'Database prefix separator is not defined please define it in your configuration file or during function call.',
//		'322'=>'Database table with name ::DB_TABLE:: do not exists. Please check for typing errors.',

	);