<?php 
//--------------------------------------------------------------------------------
//	Php FeedWriter 3.2
//
//	(c) Copyright Daniel Soutter
//	
//	Website: http://phpFeedWriter.WebmasterHub.net/
//	
//  Php FeedWriter and this sample script is provided with no warranty and may be 
//  used or developed at your own risk, providing that you have read and aggree to
//	the Terms of Use http://phpfeedwriter.webmasterhub.net/terms .
//
//	Please post any comments, bugs or suggestions for improvement to the website.
//
//	For usage instructions or technical information about Php FeedWriter, see the 
//  online documentation: http://phpFeedWriter.WebmasterHub.net/docs
//--------------------------------------------------------------------------------
/** 
 * All constants used by classes in the PhpFeedWriter package/solution are declared in this file.
 * @author		Daniel Soutter
 * @version		1.0.1
 * @link		http://phpfeedwriter.webmasterhub.net/docs/constants/   Php FeedWriter Constants
 * @copyright	Copyright (c) Daniel Soutter, in original or modified state
 * @license		http://phpfeedwriter.webmasterhub.net/terms Conditions of Use
 * @package 	PhpFeedWriter 
 */ 
//Recommended Feed Output Formats
/**
 * String representation of RSS 2.0 Feed format
 * @constant RSS_2_0 RSS 2.0 Feed format
 */
define("RSS_2_0",'RSS 2.0');	//Default: RSS 2.0 (http://cyber.law.harvard.edu/rss/rss.html)
/**
 * String representation of RSS 1.0 Feed format
 * @constant RSS_1_0 RSS 1.0 Feed format
 */
define("RSS_1_0",'RSS 1.0');	//RSS 1.0 (http://web.resource.org/rss/1.0/spec)
/**
 * String representation of Atom 1.0 Feed format
 * @constant Atom_1 Atom 1.0 Feed format
 */
define("Atom_1",'Atom 1.0');		//Atom 1.0

//Other Supported Output Formats
/**
 * String representation of RSS 0.91 Feed format
 * @constant RSS_0_91 RSS 0.91 Feed format
 */
define("RSS_0_91",'RSS 0.91'); 	//RSS 0.91 (http://www.rssboard.org/rss-0-9-1 | http://backend.userland.com/stories/rss091)
/**
 * String representation of RSS 0.92 Feed format
 * @constant RSS_0_92 RSS 0.92 Feed format
 */
define("RSS_0_92",'RSS 0.92'); 	//RSS 0.92 (http://backend.userland.com/rss092)

/**
 * String containing the feed generator details (Php FeedWriter)
 * @constant GENERATOR Details of the feed generator
 */
define("GENERATOR",'Php FeedWriter v3.0.2 ( http://phpFeedWriter.WebmasterHub.net/ )');
/**
 * A constant used to differentiate between updated and publish dates. 
 * @constant DATE_UPDATED Indicates the date a feed or item was last updated
 */
define("DATE_UPDATED",1); //Date type indicator
/**
 * A constant used to differentiate between updated and publish dates. 
 * @constant DATE_PUBLISHED Indicates the date a feed or item was published
 */
define("DATE_PUBLISHED",2); //Date type indicator

/**
 * A constant used to indicate that a construct is a known "ITEM_CONSTRUCT" (container for feed item elements)
 * @constant ITEM_CONSTRUCT Used to set commonName value for the feed item construct
 */
define("ITEM_CONSTRUCT",'item'); //Common Name for Item Container
/**
 * A constant used to indicate that a construct is the "ROOT_CONSTRUCT" (outter most element of feed data)
 * @constant ITEM_CONSTRUCT Used to set commonName value for the topmost (root) construct - feed
 */
define("ROOT_CONSTRUCT",'feed'); //Common Name for Root Element
/**
 * A constant used to indicate that a construct is the known "CHANNEL_DATA_CONSTRUCT" (container for feed/channel data)
 * @constant ITEM_CONSTRUCT Used to set commonName value for the construct containing feed data
 */
define("CHANNEL_DATA_CONSTRUCT",'feedChannel'); //Common Name for Root Element
?>