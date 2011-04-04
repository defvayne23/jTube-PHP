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
	
	private $_title = "";
	private $_description = "";
	private $_category = "";
	private $_keywords = "";
	private $_location = "";
	private $_position = "";
	private $_private = false;
	
	private $_accessComment = "allowed";
	private $_accessCommentVote = "allowed";
	private $_accessVideoRespond = "allowed";
	private $_accessRate = "allowed";
	private $_accessEmbed = "allowed";
	private $_accessSyndicate = "allowed";
	
	function setInfo($sName, $sValue) {
		switch($sName) {
			case "title":
				$this->_title = $sValue;break;
			case "description":
				$this->_description = str_replace(array(">", "<"), null, $sValue);break;
			case "category":
				$this->_category = $sValue;break;
			case "keywords":
				$this->_keywords = $sValue;break;
			case "location":
				$this->_location = $sValue;break;
			case "position":
				$this->_position = $sValue;break;
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
				return $this->_title;break;
			case "description":
				return $this->_description;break;
			case "category":
				return $this->_category;break;
			case "keywords":
				return $this->_keywords;break;
			case "location":
				return $this->_location;break;
			case "position":
				return $this->_position;break;
			case "private":
				return $this->_private;break;
			default:
				throw new Exception("Info name passed is not valid.");
		}
	}
	function loadVideo($sVideoId) {
		$this->_videoID = $sVideoId;
		
		$aHeaders = array();
		
		if(!empty($this->_devKey))
			$aHeaders[] = "X-GData-Key: key=".$this->_devKey;
		
		if(!empty($this->_authKey))
			$aHeaders[] = "Authorization: AuthSub token=\"".$this->_authKey."\"";
		
		// Load video info
		// if not found throw error
		try {
			$sResults = $this->request("http://gdata.youtube.com/feeds/api/users/default/uploads/".$sVideoId."?alt=json&v=2", $aHeaders);
		} catch(Exception $sError) {
			throw new Exception($sError);
		}
		
		$aResults = json_decode($sResults, true);
		
		// Get main video info
		$this->_title = $aResults["entry"]["media\$group"]["media\$title"]["\$t"];
		$this->_description = $aResults["entry"]["media\$group"]["media\$description"]["\$t"];
		$this->_category = $aResults["entry"]["media\$group"]["media\$category"][0]["\$t"];
		$this->_keywords = $aResults["entry"]["media\$group"]["media\$keywords"]["\$t"];
		
		// Get video location info
		if(isset($aResults["entry"]["yt\$location"]))
			$this->_location = $aResults["entry"]["yt\$location"]["\$t"];
		
		if(isset($aResults["entry"]["georss\$where"]))
			$this->_position = $aResults["entry"]["georss\$where"]["gml\$Point"]["gml\$pos"]["\$t"];
		
		// Get video view status
		if(isset($aResults["entry"]["media\$group"]["yt\$private"]))
			$this->_private = true;
		else
			$this->_private = false;
		
		// Loop video access
		foreach($aResults["entry"]["yt\$accessControl"] as $aAccess) {
			switch($aAccess["action"]) {
				case "comment":
					$this->_accessComment = $aAccess["permission"];break;
				case "commentVote":
					$this->_accessCommentVote = $aAccess["permission"];break;
				case "videoRespond":
					$this->_accessVideoRespond = $aAccess["permission"];break;
				case "rate":
					$this->_accessRate = $aAccess["permission"];break;
				case "embed":
					$this->_accessEmbed = $aAccess["permission"];break;
				case "syndicate":
					$this->_accessSyndicate = $aAccess["permission"];break;
			}
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
		$sData .= "<entry xmlns=\"http://www.w3.org/2005/Atom\"";
		$sData .= " xmlns:media=\"http://search.yahoo.com/mrss/\"";
		$sData .= " xmlns:yt=\"http://gdata.youtube.com/schemas/2007\"";
		$sData .= " xmlns:geo=\"http://www.w3.org/2003/01/geo/wgs84_pos#\"";
		$sData .= " xmlns:gd=\"http://schemas.google.com/g/2005\"";
		$sData .= " xmlns:georss=\"http://www.georss.org/georss\"";
		$sData .= " xmlns:gml=\"http://www.opengis.net/gml\"";
		$sData .= ">\n";
		
		$sData .= "\t<media:group>\n";
		$sData .= "\t\t<media:title type=\"plain\">".$this->_title."</media:title>\n";
		$sData .= "\t\t<media:description type=\"plain\">".$this->_description."</media:description>\n";
		$sData .= "\t\t<media:category scheme=\"http://gdata.youtube.com/schemas/2007/categories.cat\">".$this->_category."</media:category>\n";
		$sData .= "\t\t<media:keywords>".$this->_keywords."</media:keywords>\n";
		$sData .= "\t</media:group>\n";
		
		$sData .= "\t<yt:accessControl action=\"comment\" permission=\"".$this->_accessComment."\"/>\n";
		$sData .= "\t<yt:accessControl action=\"commentVote\" permission=\"".$this->_accessCommentVote."\"/>\n";
		$sData .= "\t<yt:accessControl action=\"videoRespond\" permission=\"".$this->_accessVideoRespond."\"/>\n";
		$sData .= "\t<yt:accessControl action=\"rate\" permission=\"".$this->_accessRate."\"/>\n";
		$sData .= "\t<yt:accessControl action=\"embed\" permission=\"".$this->_accessEmbed."\"/>\n";
		$sData .= "\t<yt:accessControl action=\"syndicate\" permission=\"".$this->_accessSyndicate."\"/>\n";
		
		if(!empty($this->_location))
			$sData .= "\t<yt:location>".$this->_location."</yt:location>\n";
		
		if(!empty($this->_position)) {
			$sData .= "\t<georss:where>\n";
			$sData .= "\t\t<gml:Point>\n";
			$sData .= "\t\t\t<gml:pos>".$this->_position."</gml:pos>\n";
			$sData .= "\t\t</gml:Point>\n";
			$sData .= "\t</georss:where>\n";
		}
		
		if($this->_private == true)
			$sData .= "\t<yt:private/>\n";
		
		$sData .= "</entry>";
		
		try {
			$this->request($sYoutubeUrl, $aHeaders, $sData, "put");
		} catch(Exception $sError) {
			throw new Exception($sError);
		}
		
		return true;
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
			
		try {
			$this->request($sYoutubeUrl, $aHeaders, array(), "delete");
		} catch(Exception $sError) {
			throw new Exception($sError);
		}
		
		return true;
	}
}