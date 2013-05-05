<?php
include_once 'Sqlite3_Wrapper.php';

$oSql = new Sqlite3_Wrapper('database.db',true);
$oSql->openDB();
$oSql->executeQuery("SELECT name FROM sqlite_master WHERE type='table'");
while($aRess=$oSql->fetch())
{
	print_r($aRess);
}

