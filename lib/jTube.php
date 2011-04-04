<?php
/*
 * jTubePHP
 * http://jtubephp.monkeecreate.com
 * 
 * jQuery Youtube API Feed Class
 * 
 * Developed by John Hoover <john@defvayne23.com>
 * Another project from monkeeCreate <http://monkeecreate.com>
 *
 * Version 1.0.0 - Last updated: June 10, 2010
*/
class jTube
{
	// Authentication
	protected $_devKey;
	protected $_authKey;
	
	function setDev($sDevKey) {
		$this->_devKey = $sDevKey;
	}
	
	function setAuth($sAuthKey) {
		$this->_authKey = $sAuthKey;
	}
	
	function request($sUrl, $aHeaders = array(), $aData = array(), $sRequestType = "") {
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $sUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		switch($sRequestType) {
			case "put": curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");break;
			case "delete": curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");break;
		}
		
		if(!empty($aHeaders))
			curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeaders);
		
		if(!empty($aData))
			curl_setopt($ch, CURLOPT_POSTFIELDS, $sData);
		
		$sResults = curl_exec($ch);
		$aInfo = curl_getinfo($ch);
		
		curl_close($ch);
		
		// Throw error
		if($aInfo["http_code"] != 200) {
			throw new Exception("Error getting results.<br>".$sResults);
		}
		
		return $sResults;
	}
}