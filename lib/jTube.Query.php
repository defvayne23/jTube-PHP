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

class jTubeQuery extends jTube
{
	// Queries
	private $_queryType; // user, search, feed, playlist, videos
	private $_queryValue;
	private $_queryOption; // # user: profile, uploads, playlists, subscriptions, contacts ## videos: info, related, responses, comments ##
	
	// Query Options
	private $_format = "flash";
	private $_order = "published";
	private $_time = "all_time";
	private $_limit = 5;
	private $_page = 1;
	private $_formated = true;
	
	private $_results;
	
	function setQuery($sType, $sValue, $sTypeOption = "uploads") {
		if(in_array($sType, array("user","search","feed","playlist","videos"))) {
			$this->_queryType = $sType;
			$this->_queryValue = $sValue;
			$this->_queryOption = $sTypeOption;
		} else {
			throw new Exception("Query type given is not valid.");
		}
	}
	function getQuery() {
		return $this->_queryType;
	}
	
	function setOption($sOption, $sValue) {
		switch($sOption) {
			case "format":
				$this->_format = $sValue;break;
			case "order":
				$this->_order = $sValue;break;
			case "time":
				$this->_time = $sValue;break;
			case "limit":
				$this->_limit = $sValue;break;
			case "page":
				$this->_page = $sValue;break;
			case "formated":
				$this->_formated = $sValue;break;
			default:
				throw new Exception("Option passed is not a valid option.");
		}
		
		return true;
	}
	function getOption($sOption) {
		switch($sOption) {
			case "format":
				return $this->_format;break;
			case "order":
				return $this->_order;break;
			case "time":
				return $this->_time;break;
			case "limit":
				return $this->_limit;break;
			case "page":
				return $this->_page;break;
			case "formated":
				return $this->_formated;break;
			default:
				throw new Exception("Option passed is not a valid option.");
		}
	}
	
	function runQuery() {
		$sYoutubeUrl = "http://gdata.youtube.com/feeds/api/";
		
		// Set desired query for returned results
		switch($this->_queryType) {
			case "user":
				if($this->_queryOption == "profile")
					$sYoutubeUrl .= "users/".urlencode($this->_queryValue)."?";
				else
					$sYoutubeUrl .= "users/".urlencode($this->_queryValue)."/".urlencode($this->_queryOption)."?";
				break;
			case "search":
				$sYoutubeUrl .= "videos?q=".urlencode($this->_queryValue)."&";break;
			case "feed":
				$sYoutubeUrl .= "standardfeeds/".urlencode($this->_queryValue)."?";break;
			case "playlist":
				$sYoutubeUrl .= "playlists/".urlencode($this->_queryValue)."?";break;
			case "videos":
				if($this->_queryOption == "info")
					$sYoutubeUrl .= "videos/".urlencode($this->_queryValue)."?";
				else
					$sYoutubeUrl .= "videos/".urlencode($this->_queryValue)."/".urlencode($this->_queryOption)."?";
				break;
			default:
				throw new Exception("No query has been set.");
		}
		
		// Set options
		$sYoutubeUrl .= "alt=json&v=2";
		
		if(($this->_queryType == "user" && $this->_queryOption == "profile")
			|| ($this->_queryType == "videos" && $this->_queryOption == "info")
		) {
			// Avoid setting options
		} else {
			$sYoutubeUrl .= "&max-results=".$this->_limit;
			$sYoutubeUrl .= "&start-index=".((($this->_page * $this->_limit) - $this->_limit) + 1);
			$sYoutubeUrl .= "&orderby=".urlencode($this->_order);
			$sYoutubeUrl .= "&time=".urlencode($this->_time);
			
			// Set desired format
			switch($this->_format) {
				case "mpeg":
					$sYoutubeUrl .= "&format=6";break;
				case "h263":
					$sYoutubeUrl .= "&format=1";break;
				default:
					$sYoutubeUrl .= "&format=5";
			}
		}
		
		// Set Headers
		$aHeaders = array();
		
		if(!empty($this->_devKey))
			$aHeaders[] = "X-GData-Key: key=".$this->_devKey;
		
		if(!empty($this->_authKey))
			$aHeaders[] = "Authorization: AuthSub token=\"".$this->_authKey."\"";
		
		// Send query to YouTube
		try {
			$sResults = $this->request($sYoutubeUrl, $aHeaders);
		} catch(Exception $sError) {
			throw new Exception($sError);
		}
		
		// Decode results
		$this->_results = json_decode($sResults, true);
		
		// Parse results
		if($this->_formated == true) {
			switch($this->_queryType) {
				case "search":
				case "feed":
				case "playlist":
					return $this->queryVideos((isset($this->_results["feed"]["entry"]))?$this->_results["feed"]["entry"]:array());
				break;
				case "user":
					if($this->_queryOption == "playlists") {
						return $this->queryPlaylists((isset($this->_results["feed"]["entry"]))?$this->_results["feed"]["entry"]:array());
					} elseif($this->_queryOption == "subscriptions") {
						return $this->querySubscriptions((isset($this->_results["feed"]["entry"]))?$this->_results["feed"]["entry"]:array());
					} elseif($this->_queryOption == "contacts") {
						return $this->queryContacts((isset($this->_results["feed"]["entry"]))?$this->_results["feed"]["entry"]:array());
					} elseif($this->_queryOption == "profile") {
						return $this->queryProfile((isset($this->_results["entry"]))?$this->_results["entry"]:array());
					} else {
						return $this->queryVideos((isset($this->_results["feed"]["entry"]))?$this->_results["feed"]["entry"]:array());
					}
				break;
				case "videos":
					if($this->_queryOption == "info") {
						$aVideo = $this->queryVideos(array($this->_results["entry"]));
						return $aVideo[0];
					} elseif($this->_queryOption == "comments") {
						return $this->queryComments((isset($this->_results["feed"]["entry"]))?$this->_results["feed"]["entry"]:array());
					} else {
						return $this->queryVideos((isset($this->_results["feed"]["entry"]))?$this->_results["feed"]["entry"]:array());
					}
			}
		} else {
			return $this->_results;
		}
	}
	
	private function queryPlaylists($aData) {
		$aPlaylists = array();
		
		foreach($aData as $aPlaylist) {			
			$aPlaylists[] = array(
				"title" => $aPlaylist["title"]["\$t"],
				"description" => $aPlaylist["summary"]["\$t"],
				"id" => $aPlaylist["yt\$playlistId"]["\$t"],
				"link" => $aPlaylist["link"][1]["href"],
				"published" => strtotime($aPlaylist["published"]["\$t"])
			);
		}
		
		return $aPlaylists;
	}
	private function querySubscriptions($aData) {
		$aSubscriptions = array();
		
		foreach($aData as $aSubscription) {
			$aSubscriptions[] = array(
				"title" => $aSubscription["title"]["\$t"],
				"username" => $aSubscription["yt\$username"]["\$t"],
				"link" => $aSubscription["link"][1]["href"],
				"published" => strtotime($aSubscription["published"]["\$t"]),
				"updated" => strtotime($aSubscription["updated"]["\$t"])
			);
		}
		
		return $aSubscriptions;
	}
	private function queryContacts($aData) {
		$aContacts = array();
		
		foreach($aData as $aContact) {
			$aContacts[] = array(
				"username" => $aContact["yt\$username"]["\$t"],
				"status" => $aContact["yt\$status"]["\$t"],
				"link" => $aContact["link"][1]["href"]
			);
		}
		
		return $aContacts;
	}
	private function queryProfile($aData) {
		$aProfile = array(
			"username" => $aData["yt\$username"]["\$t"],
			"thumbnail" => $aData["media\$thumbnail"]["url"],
			"views" => $aData["yt\$statistics"]["viewCount"],
			"uploadViews" => $aData["yt\$statistics"]["videoWatchCount"],
			"subscribers" => $aData["yt\$statistics"]["subscriberCount"],
			"lastLogin" => strtotime($aData["yt\$statistics"]["lastWebAccess"]),
			"location" => $aData["yt\$location"]["\$t"],
			"gender" => $aData["yt\$gender"]["\$t"],
			"age" => $aData["yt\$age"]["\$t"],
			"link" => $aData["link"][1]["href"],
			"title" => $aData["title"]["\$t"]
		);
		
		return $aProfile;
	}
	private function queryComments($aData) {
		$aComments = array();
		
		foreach($aData as $aComment) {
			$aComments[] = array(
				"author" => $aComment["author"][0]["name"]["\$t"],
				"comment" => $aComment["content"]["\$t"],
				"title" => $aComment["title"]["\$t"],
				"posted" => strtotime($aComment["published"]["\$t"]),
				"updated" => strtotime($aComment["updated"]["\$t"])
			);
		}
		
		return $aComments;
	}
	private function queryVideos($aData) {
		$aVideos = array();
		
		foreach($aData as $aVideo) {
			$aVideoInfo = array(
				"title" => $aVideo["title"]["\$t"],
				"link" => $aVideo["link"][0]["href"],
				"author" => array(
					"name" => $aVideo["author"][0]["name"]["\$t"],
					"link" => $aVideo["author"][0]["uri"]["\$t"]
				)
			);
			
			// Category label
			if(isset($aVideo["media\$group"]["media\$category"])) {
				$aVideoInfo["category"] = $aVideo["media\$group"]["media\$category"][0]["label"];
			}
			
			// Keywords
			if(isset($aVideo["media\$group"]["media\$keywords"])) {
				$aVideoInfo["category"] = $aVideo["media\$group"]["media\$keywords"]["\$t"];
			}
			
			// Video ID
			if(isset($aVideo["media\$group"]["yt\$videoid"])) {
				$aVideoInfo["id"] = $aVideo["media\$group"]["yt\$videoid"]["\$t"];
			}
			
			// Description
			if(isset($aVideo["media\$group"]["media\$description"])) {
				$aVideoInfo["description"] = $aVideo["media\$group"]["media\$description"]["\$t"];
			}
			
			// Video Thumbnail
			if(isset($aVideo["media\$group"]["media\$thumbnail"])) {
				$aVideoInfo["thumbnail"] = $aVideo["media\$group"]["media\$thumbnail"][3]["url"];
			}
			
			// Create array of available formats
			$aVideoFormats = array();
			foreach($aVideo["media\$group"]["media\$content"] as $aFormat) {
				$aVideoFormats[$aFormat["yt\$format"]] = $aFormat["url"];
			}
		
			// Get video url based on requested video type
			if($this->_format == "mpeg") {
				$aVideoInfo["video"] = $aVideoFormats[6];
			} else if($this->_format == "h263") {
				$aVideoInfo["video"] = $aVideoFormats[1];
			} else {
				$aVideoInfo["video"] = $aVideoFormats[5];
			}
			
			// Video published date/time
			if(isset($aVideo["published"]))
				$aVideoInfo["published"] = strtotime($aVideo["published"]["\$t"]);
			
			// Video formated duration
			if(isset($aVideo["media\$group"]["yt\$duration"])) {
				$duration = $aVideo["media\$group"]["yt\$duration"]["seconds"];
				$hours = 0;
				$minutes = 0;
				$seconds = 0;
				
				// Hours
				while($duration >= 3600) {
					$hours = $hours + 1;
					$duration = $duration - 3600;
				}
				
				// Minutes
				while($duration >= 60) {
					$minutes = $minutes + 1;
					$duration = $duration - 60;
				}
				
				// Seconds is remainder
				$seconds = $duration;
				
				// Add leading 0
				if($seconds < 10)
					$seconds = "0".$seconds;
				
				// Put minutes and seconds together
				$aVideoInfo["length"] = $minutes.":".$seconds;
				
				// If video is an hour or more, add to video length
				if($hours > 0)
					$aVideoInfo["length"] = $hours.":".$aVideoInfo["length"];
			}
			
			// View count
			if(isset($aVideo["yt\$statistics"]))
				$aVideoInfo["views"] = $aVideo["yt\$statistics"]["viewCount"];
			
			// Add video to array to pass back
			$aVideos[] = $aVideoInfo;
		}
		
		return $aVideos;
	}
}