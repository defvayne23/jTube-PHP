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

require_once("jTube.php");

class jTubeManageVideo extends jTube
{
	private $_videoID;
	
	private $_title;
	private $_description;
	private $_category;
	private $_keywords;
	private $_location;
	private $_private;
	
	function __construct($sId) {
		$this->_videoID = $sId;
		
		// Load video info
		// if not found throw error
		//http://gdata.youtube.com/feeds/api/users/defvayne23/uploads/S4pGCqtkJEE
	}
	
	function setInfo($sName, $sValue) {
		switch($sName) {
			case "title":
				$this->_title = $sValue;break;
			case "description":
				$this->_description = $sValue;break;
			case "category":
				$this->_category = $sValue;break;
			case "keywords":
				$this->_keywords = $sValue;break;
			case "location":
				$this->_location = $sValue;break;
			case "private":
				$this->_private = $sValue;break;
			default:
				throw new Exception("Info name passed is not valid.");
		}
	}
	function getValue() {
		// Either return current value from YouTube
		// or return new value set from setInfo()
		switch($sName) {
			case "title":
				break;
			case "description":
				break;
			case "category":
				break;
			case "keywords":
				break;
			case "location":
				break;
			case "private":
				break;
			default:
				throw new Exception("Info name passed is not valid.");
		}
	}
	
	function save() {
		$sYoutubeUrl = "http://gdata.youtube.com/feeds/api/users/default/uploads/".$this->_videoID;
		
		$aHeaders = array();
		$aHeaders[] = "Content-Type: application/atom+xml";
		$aHeaders[] = "GData-Version: 2";
		
		if(!empty($this->_devKey))
			$aHeaders[] = "X-GData-Key: key=".$this->_devKey;
		
		if(!empty($this->_authKey))
			$aHeaders[] = "Authorization: AuthSub token=\"".$this->_authKey."\"";
		
		$sData = "<?xml version=\"1.0\"?>\n";
		$sData .= "<entry xmlns=\"http://www.w3.org/2005/Atom\" xmlns:media=\"http://search.yahoo.com/mrss/\" xmlns:yt=\"http://gdata.youtube.com/schemas/2007\">\n";
		$sData .= "\t<media:group>\n";
		$sData .= "\t\t<media:title type=\"plain\"></media:title>\n";
		$sData .= "\t\t<media:description type=\"plain\"></media:description>\n";
		$sData .= "\t\t<media:category scheme=\"http://gdata.youtube.com/schemas/2007/categories.cat\">People</media:category>\n";
		$sData .= "\t\t<media:keywords></media:keywords>\n";
		$sData .= "\t</media:group>\n";
		$sData .= "\t<yt:accessControl action=\"comment\" permission=\"allowed\"/>\n";
		$sData .= "\t<yt:accessControl action=\"commentVote\" permission=\"allowed\"/>\n";
		$sData .= "\t<yt:accessControl action=\"videoRespond\" permission=\"allowed\"/>\n";
		$sData .= "\t<yt:accessControl action=\"rate\" permission=\"allowed\"/>\n";
		$sData .= "\t<yt:accessControl action=\"embed\" permission=\"allowed\"/>\n";
		$sData .= "\t<yt:accessControl action=\"syndicate\" permission=\"allowed\"/>\n";
		$sData .= "\t<georss:where>\n";
		$sData .= "</entry>";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $sYoutubeUrl);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeaders);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $sData);
		$sResults = curl_exec($ch);
		$aInfo = curl_getinfo($ch);
		curl_close($ch);
	}
	function delete() {
		$sYoutubeUrl = "http://gdata.youtube.com/feeds/api/users/default/uploads/".$this->_videoID;
		
		$aHeaders = array();
		$aHeaders[] = "Content-Type: application/atom+xml";
		$aHeaders[] = "GData-Version: 2";
		
		if(!empty($this->_devKey))
			$aHeaders[] = "X-GData-Key: key=".$this->_devKey;
		
		if(!empty($this->_authKey))
			$aHeaders[] = "Authorization: AuthSub token=\"".$this->_authKey."\"";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $sYoutubeUrl);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeaders);
		$sResults = curl_exec($ch);
		$aInfo = curl_getinfo($ch);
		curl_close($ch);
	}
}