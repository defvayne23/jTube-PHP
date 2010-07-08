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
}