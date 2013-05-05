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
 * @uses Simple Sqlite PHP wrapper 
 * @see https://github.com/vaan3713/databaseswrappers
 * 
 * */
 
 Class Sqlite3_Wrapper
 {
	 
	 private $sFileName, $oCon=null, $iDebug, $oResult;
	 private $sDefaultFile = 'default.db';
	 
	 public function __construct($sFileName, $iDebug=false)
	 {
		 $this->setFileName($sFileName);
	 }
	 
	 private function showMessage($sMsg)
	 {
		if($this->iDebug != false)
		{
			$sMessage = (php_sapi_name() == 'cli')?$sMsg."\n":$sMsg.'<br />' ;
			echo $sMessage;
		}
	 }
	 
	 public function setFileName($sFileName)
	 {
		 if(is_string($sFileName) && strlen($sFileName)>0)
		 {
			 $this->sFileName = $sFileName;
		 }
		 else
		 {
			 $this->sFileName = $this->sDefaultFile;
		 }
	 }
	 
	 public function openDB($sFileName=false)
	 {
		 if($sFileName!==false)
		 {
			 $this->setFileName($sFileName);
		 }
		 try
		 {
			 $this->oCon = new SQLite3($this->sFileName);
		 }catch(Exception $oEx)
		 {
			 $this->showMessage($ex->getMessage());
		 }
		 
	 }
	 
	 public function closeDB()
	 {
		 if($this->oCon!=null)
		 {
			 $this->oCon->close();
			 $this->oCon = null;
		 }
	 }
	 
	 public function executeCUDQuery($sQuery)
	 {
		 if($this->oCon==null)
		 {
			 $this->showMessage('Not connected to database');
		 }
		 else
		 {
			 try
			 {
				 $this->oCon->exec($sQuery);
			 }catch(Exception $ex)
			 {
				 $this->showMessage($ex->getMessage());
			 }
		 }
	 }
	 
	 public function executeQuery($sQuery)
	 {
		 $this->oResult = null;
		 if($this->oCon==null)
		 { 
			 $this->showMessage('Not connected to database');
		 }
		 else{
			 try
			 {
				 $this->oResult 	= $this->oCon->query($sQuery);
			 }catch(Exception $oEx)
			 {
				 $this->showMessage($oEx->getMessage());
			 }
		 }
		 return $this->oResult;
	 }
	 
	 public function fetch($iMode=SQLITE3_NUM)
	 {
		 $aData = array();
		 try
		 {
			 $aData = $this->oResult->fetchArray($iMode);
		 }
		 catch(Exception $oEx)
		 {
			 $this->showMessage($oEx->getMessage());
		 }
		 return $aData;
	 }
 }
