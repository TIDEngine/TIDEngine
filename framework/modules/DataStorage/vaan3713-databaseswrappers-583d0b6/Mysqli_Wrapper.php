<?php

/*
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * @author vaan3713 <vaan3713[AT]wired-dev[DOT]com>
 * @uses Simple Mysql PHP wrapper (Mysqli module)
 * @see https://github.com/vaan3713/databaseswrappers
 * 
 * */
 class Mysqli_Wrapper
{
	private $aDBConfiguration 	= array();
	private $oCon, $oQuery;
	private $iDebug, $iAffectedRows,$iLastInsertedID;
	private $aErrorMessages		= array('CFG_NOT_FOUND'=>'Configuration \'[##CONFIG_NAME##]\' not found!',
										'SERVER_NOT_FOUND'=>'Could not connect to server: \'[##SERVER_NAME##]\'',
										'DB_NOT_FOUND'=>'Could not open database: \'[##DB_NAME##]\'',
										'CONNECT_CLOSE_FAIL'=>'Attempt to close connection failed!',
										'INVALID_QUERY'=>'Record couldn\'t be fetched!');
	
	/**
	 * constructor
	 * @param boolean $iDebug if true it will show error messages
	 * @param string $sServer database hostname
	 * @param string $sUser database login
	 * @param string $sPass database password
	 * @param string $sDatabase default database to select
	 * 
	 * */
	public function __construct($iDebug=false, $sServer=null, $sUser=null, $sPass=null, $sDatabase=null)
	{
		//set the default database configuration
		$this->addNewConfiguration('default', $sServer, $sUser, $sPass, $sDatabase);
		$this->iDebug 				= $iDebug;
		$this->iAffectedRows		= 0;
		$this->oQuery				= null;
	}
	
	/**
	 * @uses add new database configuration
	 * @param string $sConfigLabel the configuration label (used as a key for the database configuration array)
	 * 
	 * */
	public function addNewConfiguration($sConfigLabel, $sServer, $sUser, $sPass, $sDatabase)
	{
		$this->aDBConfiguration[$sConfigLabel] = array('SERVER'=>$sServer, 'USER'=>$sUser, 'PASS'=>$sPass, 'DATABASE'=>$sDatabase);
	}
	
	/**
	 * @uses print messages
	 * 
	 * */
	private function showMessage($sMessage)
	{
		if($this->iDebug != false)
		{
			$sMessage = (php_sapi_name() == 'cli')?$sMessage."\n":$sMessage.'<br />';
			echo $sMessage;
		}
	}
	
	/**
	 * @uses return the database configuration using its label
	 * @param $sConfigLabel the configuration label
	 * @return mixed[] return null if the configuration label dosn't exists
	 * */
	public function getDBConfiguration($sConfigLabel)
	{
		if(array_key_exists($sConfigLabel, $this->aDBConfiguration))
		{
			return $this->aDBConfiguration[$sConfigLabel];
		}
		else{
			$this->showMessage(str_replace('[##CONFIG_NAME##]',$sConfigLabel,$this->aErrorMessages['CFG_NOT_FOUND']));
			return null;
		}
	}
	
	/**
	 * @uses connect to database
	 * @param string $sConfigLabel configuration label
	 * 
	 * */
	public function connect($sConfigLabel='default')
	{
		$aParams		= $this->getDBConfiguration($sConfigLabel);
		if($aParams 	!= null)
		{
			$this->oCon	= mysqli_connect($aParams['SERVER'], $aParams['USER'], $aParams['PASS']);
			if(!$this->oCon)
			{
				$this->showMessage(str_replace('[##SERVER_NAME##]',$aParams['SERVER'],$this->aErrorMessages['SERVER_NOT_FOUND']));
			}
			elseif(!mysqli_select_db($this->oCon, $aParams['DATABASE']))
			{
				$this->showMessage(str_replace('[##DB_NAME##]',$aParams['DATABASE'],$this->aErrorMessages['DB_NOT_FOUND']));
			}
		}
		return $this->oCon;
	}
	
	/**
	 * @uses close connection
	 * 
	 * */
	public function close()
	{
		if($this->oCon!=false && $this->oCon!=null && !mysqli_close($this->oCon))
		{
			$this->showMessage($this->aErrorMessages['CONNECT_CLOSE_FAIL']);
		}
	}
	
	/**
	 * @see http://www.php.net/manual/en/function.mysql-real-escape-string.php
	 * 
	 * */
	public function clean($sString)
	{
		return mysqli_real_escape_string($this->oCon,$sString);
	}
	
	
	/**
	 * @uses excute query
	 * @return int query ID
	 * */
	public function executeQuery($sQuery)
	{
		
		$this->oQuery = mysqli_query($this->oCon,$sQuery) or $this->showMessage(mysqli_error($this->oCon));
		$this->iAffectedRows = mysqli_affected_rows($this->oCon);
		return $this->oQuery;
	}
	
	/**
	 * @uses execute insert query (for tables using auto_increment only)
	 * @return mixed last inserted id
	 * */
	public function executeInsert($sQuery)
	{
		$this->executeQuery($sQuery);
		$this->iLastInsertedID = mysqli_insert_id($this->oCon);
		return $this->iLastInsertedID;
	}
	
	/**
	 * @uses get affacted rows by the last executed query
	 * 
	 * */
	public function getAffectedRows()
	{
		return $this->iAffectedRows;
	}
	
	/**
	 * @uses fetch and return one ligne
	 * @param $oQuery a query id
	 * @return mixed[] row
	 * */
	public function fetch($oQuery=false)
	{
		$this->oQuery 	= ($oQuery===false)?$this->oQuery:$oQuery;
		$aRow			= array();
		if($this->oQuery!=null)
		{
			$aRow		= mysqli_fetch_array($this->oQuery, MYSQLI_ASSOC);;
		}
		else{
			$this->showMessage($this->aErrorMessages['INVALID_QUERY']);
		}
		return $aRow;
	}
	
	/**
	 * @uses return the connection link
	 * 
	 * */
	public function getConnectionLink()
	{
		return $this->oCon;
	}
	
	/**
	 * @uses return the last query id
	 * 
	 * */
	public function getLastQueryID()
	{
		return $this->oQuery;
	}
	
	/**
	 * @uses return the last id inserted of the last query
	 * 
	 * */
	public function getLastInsertedID()
	{
		return $this->iLastInsertedID;
	}
	
}
