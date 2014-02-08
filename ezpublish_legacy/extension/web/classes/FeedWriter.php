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
 * The main FeedWriter class file for Php FeedWriter
 * 
 * This is the main class file for the Php FeedWriter solution.  
 * @author		Daniel Soutter
 * @version		3.2
 * @copyright	Copyright (c) Daniel Soutter, in original or modified state
 * @link		http://phpfeedwriter.webmasterhub.net/docs Php FeedWriter Documentation
 * @license		http://phpfeedwriter.webmasterhub.net/terms Conditions of Use
 * @package 	PhpFeedWriter 
 */
/**
 * include file to register globals / constants
 */
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'FeedWriter'.DIRECTORY_SEPARATOR.'constants.php');
/**
 * The main FeedWriter class containing functionality to build and output a feed in various formats.
 * 
 * This class contains functions required to create, populate the feed and feed items with data and 
 * output the feed.   
 * The FeedConstruct class is used by this FeedWriter class to build a logical representation
 * of a feed format for use when validating and outputing the feed.
 *
 * @link 		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/   FeedWriter Class Documentation
 * @uses		FeedConstruct
 * @package 	PhpFeedWriter 
 * @author		Daniel Soutter
 * @version		3.2
 * @copyright	Copyright (c) Daniel Soutter, in original or modified state
 */
class FeedWriter
{
	//variables 
	/**
	 * Used to store the feed xml.  getXML() builds, then returns the feed XML from this variable.
	 * @see		getXML()
	 * @access 	private
	 * @var 	string 
	 */
	private $xml;
	/**
	 * Sets the indent for the XML output.  Set as $indent parameter of class constructor.
	 * @see		__construct()
	 * @access 	private
	 * @var 	integer 
	 */
	private $indent;
	/**
	 * Stores the feed/channel data.
	 * @access 	private
	 * @var 	array 
	 */
	private $feedData;
	/**
	 * Stores information to help control output of the feed.  Array values are set from constructor.
	 * @see		__construct()
	 * @access 	private
	 * @var 	array 
	 */
	private $feedSpecs;
	/**
	 * Array to store the Feed Items added to the feed
	 * @see		addItem()
	 * @access 	private
	 * @var 	array 
	 */
	private $itemsArray = Array();
	/**
	 * Array to store each FeedConstruct class objects for each format.  Allows runtime ammendments to a
	 * specific schema, such as changing an element type from "text" to "html".  Use {@link set_feedConstruct()} 
	 * to instantiate and set a specific format as the current, then use {@link FeedWriter::$feed_construct} 
	 * to access the construct.
	 * @see		set_feedConstruct()
	 * @access 	private
	 * @var 	array 
	 */
	private $constructArray = Array();
	/**
	 * Array to store the constructs that fail the validation process.  Used to help when creating a feed.
	 * @access 	private
	 * @var 	array 
	 */
	private $error_details = null;
	/**
	 * Flag to indicate if the footer item has been included.  This is not in use.
	 * @access 	private
	 * @var 	boolean 
	 */
	private $hasCredit;
	
	/**
	 * The current feed construct for an output format.  Use {@link set_feedConstruct()} to instantiate and 
	 * set this variable as the FeedConstruct class object for the specified format.
	 * @see		set_feedConstruct()
	 * @access 	public
	 * @var 	boolean 
	 */
	public $feed_construct = null;
	/**
	 * Used to toggle debug.  When enabled, additional information is displayed when a feed is not valid.
	 * @access 	public
	 * @var 	boolean 
	 */
	public $debug = true;
	/**
	 * An array that maps an string used to represent a feed format to the corresponding constant. These are set as
	 * the same as the name of each constant by default, but can be altered as required.
	 * @access 	public
	 * @var 	array 
	 */
	public $feed_Formats;
	
	
	/*********************************************************************************
	* Class Constructor
	*
	* Description:	Creates an instance of the XMLWriter and starts an xml 1.0 
	*				document.  Starts required RSS 2.0 elements (rss, channel)
	*	
	* Details:		
	**********************************************************************************/
	/**
	 * Class Constructor - Creates the main (empty) feed.  Assigns null/empty array to feed/channel data elements
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/__construct/  FeedWriter Constructor Documentation
	 * @param 	string $title the feed format.
	 * @param 	string $description the feed format.
	 * @param 	string $link the feed format.
	 * @param 	integer $indent the feed format.
	 * @param 	boolean $useCDATA the feed format.
	 * @param 	string $encode_as the feed format.
	 * @param 	boolean $enable_validation the feed format.
	 * @return 	void
	 */
	function __construct($title, $description, $link, $indent = 6, $useCDATA = false, $encode_as = null, $enable_validation = true)	//Constructor
	{
		/**
		 * Add available feed format constants to array.  Used to generate a list of strings representing each feed format.
		 */
		$this->feed_Formats = Array(
			Array('RSS_2_0', RSS_2_0),
			Array('RSS_1_0', RSS_1_0),
			Array('Atom_1', Atom_1),
			Array('RSS_0_91', RSS_0_91),
			Array('RSS_0_92', RSS_0_92)
		);
	
		/**
		 * Instantiate the feed data array with empty values (null) / empty array if a repeating element
		 */
		$this->feedData = Array(
			"feedTitle" => $title, 
			"feedDescription" => $description, 
			"feedLink" => $link,
			"feedId" => $link, //(Atom only)
			"feedLanguage" => null,
			"feedCopyright" => null,
			"feedAuthor" => null,
			"feedWebmaster" => null,
			"feedEditor" => null,
			"feedRating" => null,
			"feedPubDate" => null,
			"feedDateUpdated" => null,
			"feedDocs" => null,
			"feedSkipDays" => null,
			"skipDay" => Array(),
			"feedSkipHours" => null,
			"skipHour" => Array(),
			"feedImage" => null,
			"feedInput" => null,
			"feedGenerator" => null,
			"feedRefreshInterval" => null,
			"feedIcon" => null,
			"feedSelfLink" => null,
			"feedLinks" => Array(),
			"feedContributor" => Array(),
			"feedCategory" => Array(),
			"feedCloud" => null,
			"image_toc" => null,
			"input_toc" => null,
			"items_toc" => null,
			"items_toc_li" => Array(),
			"optionalElements" => Array()
			);
		
		/**
		 * Set the indent for the XMLWriter class to use when building the XML output. Default = 6.
		 */
		$this->indent = $indent;
		
		/**
		 * Set the indent for the XMLWriter class to use when building the XML output.
		 */
		$this->feedSpecs = Array(
			'useCDATA' => $useCDATA,
			'enableValidation' => $enable_validation,
			'xmlEncodeAs' => $encode_as,
			"feedXMLNameSpace" => Array(),
			"feedStylesheet" => Array(),
			);
	}
	
	/**
	 * Add an item to the feed.  
	 * Sets blank values for optional item data that can be included using other functions of the FeedWriter class.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_item/   add_item() Documentation
	 * @param 	string $title name to declare
	 * @param 	string $description value of the name
	 * @param 	string $link name to declare
	 * @todo 	Feed Items to be implemented as a separate FeedItem class.
	 * @return 	void 
	 */
	function add_item($title = null, $description = null, $link = null)
	{
		$this->itemsArray[] = Array(
			'itemTitle' => $title, 
			'itemContent' => $description, 
			'itemLink' => $link, 
			'itemSummary' => null, 
			'itemSource' => null, 
			'itemCategory' => Array(), 
			'itemAuthor' => null, 
			'itemContributor' => Array(), 
			'itemMedia' => Array(),
			'itemId' => $link, //Set link as default value for id
			'itemPubDate' => null,
			'itemUpdated' => null,
			'itemCopyright' => null,
			'itemComments' => null,
			'itemSelfLink' => null,
			'itemLinks' => Array(),
			'optionalElements' => Array()
			); 
		
		/**
		 * initiate the items toc container if required (RSS 1.0 / RDF output)
		 */
		if(!isset($this->feedData['items_toc']) || $this->feedData['items_toc'] == null)
			$this->feedData['items_toc'] = Array('items_toc_seq' => 'items_toc_seq');
		
		/**
		 * Add the new item url to the item_toc_li array (RSS 1.0 / RDF output)
		 */
		$this->feedData['items_toc_li'][] = $link;
	}
	
	/**
	 * Returns the Feed Construct object for the specified format.
	 * 
	 * Instantiates from the FeedConstruct class if not previously created
	 * if the specific format is different to an instance set previously for a particular feed format.
	 * This function can also be called to instantiate and modify the
	 * construct of a specified format prior to outputting a feed 
	 * (eg. to change the feed item content element type to "html").
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_feedconstruct/   set_feedconstruct() Documentation
	 * @param 	string $format The feed format used to find/instantiate the corresponding FeedConstruct object.
	 * @return 	void 
	 */
	function set_feedConstruct($format = RSS_2_0)
	{
		/**
		* Include the FeedConstruct class file
		*/
		require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'FeedWriter'.DIRECTORY_SEPARATOR.'FeedConstruct.php');
		$found = false;

		foreach($this->constructArray as $curConstruct)
		{
			if($curConstruct->format == $format)
			{
				$found = true;
				if($curConstruct->format != $this->feed_construct->format)
				{
					//Format has changed
					
					$tmpConstruct = $this->feed_construct;
					$this->feed_construct = $curConstruct;
					
					//Add temp back to construct array
					foreach($this->constructArray as $curConstruct2)
					{
						if($curConstruct2->format == $tmpConstruct->format)
						{
							$curConstruct2 = $tmpConstruct;
							break;
						}
					}
					break;
				}
			}
		}
		
		if(!$found)
		{
			$this->constructArray[] = new FeedConstruct($format);
			$this->feed_construct = $this->constructArray[count($this->constructArray) -1];
		}
	}
	
	/**
	 * Add stylesheet details, included when rendering the feed. 
	 * 
	 * More than one stylesheet can be associated with a single feed.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_feedstylesheet/   add_feedstylesheet() Documentation
	 * @param 	string $address The URI to the stylesheet
	 * @param 	string $type A string representing the type of stylesheet (default is "text/css")
	 * @return 	void 
	 */
	function add_feedStylesheet($address, $type = null)
	{
		if($type == null)
			$type = 'text/css';
			
		$this->feedSpecs['feedStylesheet'][] = Array("type" => $type, "address" => $address);
	}

	/**
	 * Include custom name space referrences.  
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_feedxmlnamespace/   add_feedstylesheet() Documentation
	 * @param 	string $prefix The Name Space Prefix
	 * @param 	string $url The Name Space URL
	 * @return 	void 
	 */
	function add_feedXMLNameSpace($prefix, $url)
	{
		$this->feedSpecs['feedXMLNameSpace'][] = Array('prefix' => $prefix, 'url' => $url);	
	}
	
	//=============feed only functions===================================================
	/**
	 * Set the language for the feed.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_language/   set_language() Documentation
	 * @param 	string $lang A string representing the language for the feed.
	 * @return 	void 
	 */
	function set_language($lang){
		$this->feedData['feedLanguage'] = $lang;
	}

	/**
	 * Set the webmaster or contact for technical issues with the feed. (Optional)
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_webmaster/   set_webmaster() Documentation
	 * @param 	string $webmaster A string representing the webmaster for the feed, usually in "email@domain.com (name)", or "email@domain.com" format.
	 * @return 	void 
	 */
	function set_webmaster($webmaster){
		$this->feedData['feedWebmaster'] = $webmaster;
	}

	/**
	 * Set the PICS rating for the feed. (Optional)
	 *
	 * Used for RSS Output.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_rating/   set_rating() Documentation
	 * @param 	string $rating The PICS rating for the feed.
	 * @return 	void 
	 */
	function set_rating($rating){
		$this->feedData['feedRating'] = $rating;
	}

	/**
	 * Specify days of the week to skip checking for updates to the feed. (Optional)
	 *
	 * Used for RSS Output.  You can supply either a single string (eg. "Monday"), 
	 * or a single dimensional array of strings representing each day of the week 
	 * that should be skipped: Array('Saturday', 'Sunday')
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_skipdays/   set_skipdays() Documentation
	 * @param 	string $skipday A single string value, or array of strings representing the days of week to skip.
	 * @return 	void 
	 */
	function set_skipDays($skipday){
	
		if($this->feedData['feedSkipDays'] == null)
			$this->feedData['feedSkipDays'] = true;
		
		if(!isset($this->feedData['skipDay']) || $this->feedData['skipDay'] == null)
			$this->feedData['skipDay'] = Array();
		
		if(!is_array($skipday))
			$skipday = Array($skipday);
		
		foreach($skipday as $curDay)
		{
			$found = false;
			foreach($this->feedData['skipDay'] as $curDay_Feed)
			{
				if($curDay_Feed == $curDay)
					$found = true;
			}
			if(!$found)
				$this->feedData['skipDay'][] = $curDay;
		}
	}

	/**
	 * Specify hours of the day to skip checking for updates to the feed. (Optional)
	 *
	 * Used for RSS Output.  You can supply either a single integer (eg. 7), 
	 * or a single dimensional array of integers representing each hour of the day 
	 * that should be skipped: Array(11, 22)
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_skiphours/   set_skiphours() Documentation
	 * @param 	integer $hour A single integer value (0-23) representing the hours of the day to skip, or array of integers.
	 * @return 	void 
	 */
	function set_skipHours($hour){
		if($this->feedData['feedSkipHours'] == null)
			$this->feedData['feedSkipHours'] = true;
		
		if(!isset($this->feedData['skipHour']) || $this->feedData['skipHour'] == null)
			$this->feedData['skipHour'] = Array();		
		
		if(!is_array($hour))
			$hour = Array($hour);
		
		foreach($hour as $curHour)
		{
			$found = false;
			foreach($this->feedData['skipHour'] as $curHour_Feed)
			{
				if($curHour_Feed == $curHour)
					$found = true;
			}
			if(!$found)
				$this->feedData['skipHour'][] = $curHour;
		}
	}

	/**
	 * Associate an input field and results url with the feed. (Optional)
	 *
	 * Used for RSS Output.  
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_input/   set_input() Documentation
	 * @param 	string $inputTitle A string containing the title of the input. Eg. "Search".
	 * @param 	string $inputDescription A string consisting of a description of what the input field is for.
	 * @param 	string $inputName A string representing the name of the input field once submitted.
	 * @param 	string $inputLink A string consisting of the url or web address that the value in the input field will be submitted to.
	 * @return 	void 
	 */
	function set_input($inputTitle,$inputDescription,$inputName,$inputLink){
		$this->feedData['feedInput'] = Array(
			'inputTitle' => $inputTitle, 
			'inputDescription' => $inputDescription, 
			'inputName' => $inputName, 
			'inputLink' => $inputLink
			);
			
			$this->feedData['input_toc'] = $inputLink;
	}

	/**
	 * Specify the minimum amount of time in minutes to wait before checking again for updates to the feed. (Optional)
	 *
	 * Used for RSS 2.0 Output.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_refreshinterval/   set_refreshinterval() Documentation
	 * @param 	integer $interval An integer value that represents the time in minutes to wait.
	 * @return 	void 
	 */
	function set_refreshInterval($interval){
		$this->feedData['feedRefreshInterval'] = $interval;
	}

	/**
	 * Associate an icon image with the feed. (Optional)
	 *
	 * Used for Atom output - associate a small image/icon with the feed.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_icon/   set_icon() Documentation
	 * @param 	string $icon_uri A string consisting of the URL to the icon image for the feed.
	 * @return 	void 
	 */
	function set_icon($icon_uri){
		$this->feedData['feedIcon'] = $icon_uri;
	}

	/**
	 * Associates the feed with a cloud. Not applicable for all feed output formats. (Optional)
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_cloud/   set_cloud() Documentation
	 * @param 	string $domain A string consisting of the cloud domain or server.
	 * @param 	string $port A string consisting of the cloud port. Default is '80'
	 * @param 	string $path A string consisting of the URL / path to the feed data on the web.
	 * @param 	string $regProcedure A string containing the name of the procedural call to make for the cloud connection. Default is 'pingMe'.
	 * @param 	string $protocol A string containing the protocol to use when making the connection. Default is 'soap'
	 * @return 	void 
	 */
	function set_cloud($domain, $port = '80', $path, $regProcedure = 'pingMe', $protocol = 'soap'){
		$this->feedData['feedCloud'] = Array(
			'cloudDomain' => $domain, 
			'cloudPort' => $port, 
			'cloudPath' => $path, 
			'cloudRegProcedure' => $regProcedure, 
			'cloudProtocol' => $protocol
			);
	}

	/**
	 * Associate an image with the feed.  Required for RSS 1 Output.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_image/   set_image() Documentation
	 * @param 	string $title A string containing a title for the image
	 * @param 	string $link A string containing the address to send the use to when the image is clicked.
	 * @param 	string $url A string containing the address / URI of the image.
	 * @param 	integer $width An integer value that represents the width of the image.
	 * @param 	integer $height An integer value that represents the height of the image.
	 * @param 	string $description A string containing a description of the image.
	 * @return 	void 
	 */
	function set_image($title, $link, $url, $width = null, $height = null, $description = null){
		$this->feedData['feedImage'] = Array(
			'feedImage' => $url,
			'imageUrl' => $url, 
			'imageTitle' => $title, 
			'imageLink' => $link, 
			'imageDescription' => $description, 
			'imageWidth' => $width, 
			'imageHeight' => $height
			);
			
		$this->feedData['image_toc'] = $url;
	}


	//=============Feed/Item Functions===================================================
	/**
	 * Include a date value in the feed, or items within the feed.
	 *
	 * Call the function before adding items to the feed to associate the date with the feed.  
	 * Call after adding items to associate with to the most recently added item.
	 * Date Type Options: {@link http://phpfeedwriter.webmasterhub.net/docs/constants/ Constants}
	 * DATE_UPDATED (1) - Date Feed/Item Updated (Required for Atom output)
	 * DATE_PUBLISHED (2) - Item Publish Date
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_date/   set_date() Documentation
	 * @param 	string $date_value A string containing a date in ISO-8601 format.
	 * @param 	integer $date_type An integer representing the type of date. Expects either DATE_UPDATED or DATE_PUBLISHED
	 * @return 	void 
	 */
	function set_date($date_value, $date_type){
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			if($date_type == DATE_UPDATED)
				$this->itemsArray[count($this->itemsArray)-1]['itemUpdated'] = $date_value;
			elseif($date_type == DATE_PUBLISHED)
			{
				$this->itemsArray[count($this->itemsArray)-1]['itemPubDate'] = $date_value;
				
				//Set date updated if not yet supplied
				if($this->itemsArray[count($this->itemsArray)-1]['itemUpdated'] == null)
					$this->itemsArray[count($this->itemsArray)-1]['itemUpdated'] = $date_value;
			}
		}
		else
		{
			//Add to the feed
			if($date_type == DATE_UPDATED)
				$this->feedData['feedDateUpdated'] = $date_value;
			elseif($date_type == DATE_PUBLISHED)
			{
				$this->feedData['feedPubDate'] = $date_value;
				
				//Set date updated if not yet supplied
				if($this->feedData['feedDateUpdated'] == null)
					$this->feedData['feedDateUpdated'] = $date_value;
			}
		}
	}

	/**
	 * Associate an ID with the feed or the most recently added feed item.
	 *
	 * If this function is not called, the 'link' value passed to the class 
	 * constructor, or the addItem() function will be used for the unique id.
	 * Calling this function will override the default (link) value with the id supplied.
	 * The following elements will be populated with the unique identifier:
	 * RSS Feed item id: <guid>
	 * Atom Feed/Entry id: <id>
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_id/   set_id() Documentation
	 * @param 	string $id A string containing the ID for the feed or feed item.
	 * @return 	void 
	 */
	function set_id($id) {
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['itemId'] = $id;
		}
		else
		{
			//Add to feed (Atom only)
			$this->feedData['feedId'] = $id;
		}
	}

	/**
	 * Add a copyright notice to the feed or feed item. (Optional)
	 *
	 * Used for Atom output - associate a small image/icon with the feed.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_copyright/   set_copyright() Documentation
	 * @param 	string $copyright A string containing the copyright notice for the feed or feed item.
	 * @return 	void 
	 */
	function set_copyright($copyright){
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['itemCopyright'] = $copyright;
		}
		else
		{
			$this->feedData['feedCopyright'] = $copyright;
		}
	}

	/**
	 * Add author details to the feed or the most recent item added to the feed. (Required for Atom Feed Output)
	 *
	 * For Atom output, the author is required in either the feed, or in each item within the feed.  
	 * Both is fine. For RSS output, these details if provided will be added to a <managingEditor>
	 * element within the feed channel, or an <author> element in feed items.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_author/   set_author() Documentation
	 * @param 	string $authorEmail A string containing the email address of the author.
	 * @param 	string $authorName A string containing the name of the author.
	 * @param 	string $authorUri A string containing a URL to the author's website or profile page.
	 * @return 	void 
	 */
	function set_author($authorEmail = null, $authorName = null,$authorUri = null){
		
		//Exit if no name/email provided
		if($authorName == null &&
		$authorEmail == null)
			return false;
		elseif($authorName == null)
			$authorString = $authorEmail;
		elseif($authorEmail == null)
			$authorString = $authorName;
		else
			$authorString = $authorEmail . " (" . $authorName . ")";

		
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['itemAuthor'] = Array(
				'itemAuthor' => $authorString,
				'itemAuthorEmail' => $authorEmail, 
				'itemAuthorName' => $authorName, 
				'itemAuthorUri' => $authorUri);
		}
		else
		{
			$this->feedData['feedAuthor'] = Array(
				'feedAuthor' => $authorString,
				'feedAuthorName' => $authorName, 
				'feedAuthorUri' => $authorUri, 
				'feedAuthorEmail' => $authorEmail
				);
		}
	}

	/**
	 * Set the self link (rel="self") for the feed or feed item. (Required for Atom Feed Data)
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_selflink/   set_selflink() Documentation
	 * @param 	string $uri A string consisting of the URI of the "self" content (feed) on the internet.
	 * @return 	void 
	 */
	function set_selfLink($uri){
		if (count($this->itemsArray) > 0)
		{
			$this->itemsArray[count($this->itemsArray)-1]['itemSelfLink'] = $uri;
		}
		else
		{
			$this->feedData['feedSelfLink'] = $uri;
		}
	}

	/**
	 * Add details of one or more contributors to the feed or the item most recent added to the feed.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_contributor/   add_contributor() Documentation
	 * @param 	string $email A string containing the email address of the contributor.
	 * @param 	string $name A string containing the name of the contributor.
	 * @param 	string $uri A string containing a URL to the contributor's website or profile page.
	 * @return 	void 
	 */
	function add_contributor($email = null, $name = null,$uri = null){
		
		if($name == null && $email != null)
			$authorString = $email;
		elseif($email == null && $name != null)
			$authorString = $name;
		elseif($email != null && $name != null)
			$authorString = $email . " (" . $name . ")";
	
		if (count($this->itemsArray) > 0)
		{
			$this->itemsArray[count($this->itemsArray)-1]['itemContributor'][] = Array(
				'itemContributor' => $authorString, 
				'itemContributorName' => $name, 
				'itemContributorUri' => $uri, 
				'itemContributorEmail' => $email);
		}
		else
		{
			$this->feedData['feedContributor'][] = Array(
				'feedContributor' => $authorString, 
				'feedContributorName' => $name, 
				'feedContributorUri' => $uri, 
				'feedContributorEmail' => $email);
		}
	}

	/**
	 * Add a link to the feed or feed item. Returns false if rel = self,alternate,enclosure (use class functions instead). 
	 *
	 * The {@link set_selfLink()} function should be used if intending to include a link with rel="self".
	 * The {@link add_media()} function should be used if intending to include a link with rel="enclosure".
	 * The FeedWriter Constructor or {@link add_item()} function set the "alternate" link by default for some 
	 * feed output formats, as this is generally the link that the user is taken to if the  title of the feed 
	 * or item is clicked, or to view up-to-date version if the feed has not been updated in a reader or aggregator.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_link/   add_link() Documentation
	 * @param 	string $uri A string containing the URI of the link
	 * @param 	string $rel A string containing the value to be included in the "rel" attribute for the link.
	 * @param 	string $type A string containing the value for the type attribute
	 * @return 	void 
	 */
	function add_link($uri, $rel, $type){

		//Return false if controlled type
		//Instead use set_selfLink(), or add_media()
		if(	strtolower($rel) == 'self' || 
			strtolower($rel) == 'alternate' || 
			strtolower($rel) == 'enclosure'
			)
			return false;

		if (count($this->itemsArray) > 0)
		{
			$this->itemsArray[count($this->itemsArray)-1]['itemLinks'][] = Array(
				'linkUri' => $uri, 
				'linkRelType' => $rel, 
				'linkType' => $type);
		}
		else
		{
			$this->feedData['feedLinks'][] = Array(
				'linkUri' => $uri, 
				'linkRelType' => $rel, 
				'linkType' => $type);
		}
	}

	/**
	 * Add one or more categories to the feed or feed item
	 *
	 * Generic function to add categories to the channel and/or items.
	 * Call this function before adding items to the feed to add categories to the channel. 
	 * Call after adding an item to assign categories/tags to individual feed items.
	 * You can add multiple categories to the feed or an item. This function will need 
	 * to be called separately to add each category.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_category/   add_category() Documentation
	 * @param 	string $categoryName A string containing the catgory
	 * @param 	string $domain A string containing the domain or website that the category is associated with
	 * @param 	string $categoryScheme A string containing the scheme for the category if applicable
	 * @param 	string $categoryLabel A string containing the label for the category if applicable
	 * @return 	void 
	 */
	function add_category($categoryName, $domain = null, $categoryScheme = null, $categoryLabel = null){
		if($categoryLabel == null)
			$categoryLabel = $categoryName;
			
		if (count($this->itemsArray) > 0)
		{
			//Add category to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['itemCategory'][] = Array(
				'itemCategory' => $categoryName,
				'itemCategoryTerm' => $categoryName, 
				'itemCategoryScheme' => $categoryScheme, 
				'itemCategoryLabel' => $categoryLabel,
				'itemCategoryDomain' => $domain
				);
		}
		else
		{
			//Add category to feed
			$this->feedData['feedCategory'][] = Array(
				'feedCategory' => $categoryName,
				'feedCategoryTerm' => $categoryName, 
				'feedCategoryScheme' => $categoryScheme, 
				'feedCategoryLabel' => $categoryLabel,
				'feedCategoryDomain' => $domain
				);
		}
	}

	//=============item only functions===================================================
	/**
	 * Include information from the source feed with a feed item. (Optional)
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_source/   set_source() Documentation
	 * @param 	string $source_title A string containing the title of the source feed.
	 * @param 	string $source_url A string containing the URL of the source feed
	 * @param 	string $source_updated A string containing the date that the source feed was updated.
	 * @return 	boolean 
	 */
	function set_source($source_title, $source_url, $source_updated){
		//Continue if items have been added to the feed.  
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['itemSource'] = Array(
				'itemSource' => $source_title,
				'sourceTitle' => $source_title,
				'sourceUrl' => $source_url,
				'sourceUpdated' => $source_updated
				);
				
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Include a summary of the feed item content (applicable to Atom Output only)
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_summary/   set_summary() Documentation
	 * @param 	string $summary A string containing the summary of the feed item content.
	 * @return 	boolean 
	 */
	function set_summary($summary){
		//Continue if items have been added to the feed.  
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['itemSummary'] = $summary;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Add a media file enclosure to a feed item
	 *
	 * Associate media files to items in the feed.  
	 * This function must be called after adding an item to the feed.  The media file(s)
	 * will be attached to the item most recently added to the feed using the <enclosure> 
	 * or <link> element depending on the output format.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_media/   add_media() Documentation
	 * @param 	string $url A string containing the URL to the media file.
	 * @param 	string $type A string indicating the type of file. Eg. "application/x-zip-compressed", "image/jpeg"
	 * @param 	integer $fileSize An integer value representing the size of the file in bytes.
	 * @return 	boolean 
	 */
	function add_media($url, $type, $fileSize){
		//Continue if items have been added to the feed.  
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['itemMedia'][] = Array(
				'mediaUrl' => $url, 
				'mediaType' => $type, 
				'mediaLength' => $fileSize);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Specify a URL to a comments page.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_comments/   set_comments() Documentation
	 * @param 	string $uri A string containing the URI to the comments page for a feed item.
	 * @return 	boolean 
	 */
	function set_comments($uri){
		//Continue if items have been added to the feed.
		if (count($this->itemsArray) > 0)
		{
			$this->itemsArray[count($this->itemsArray)-1]['itemComments'] = $uri;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Private function: Sets the URL for the feed schema documentation. The values are set 
	 * from information from the FeedConstruct class for each format
	 * @access 	private
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_docs/   set_docs() Documentation
	 * @param 	string $docs_uri A string containing the URL to the feed schema documentation page.
	 * @return 	boolean 
	 */
	private function set_docs($docs_uri){
		$this->feedData['feedDocs'] = $docs_uri;
	}
	
	/**
	 * This function has been replaced by the range of functions available to populate the feed and items.
	 * @access 	public
	 * @deprecated since (Php FeedWriter) version 3.0
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_element/   add_element() Documentation
	 * @param 	string $elementName A string containing the name of the element to add to the feed.
	 * @param 	string $val A string containing the value of the element.
	 * @param 	array $attributes A two dimensional array containing the set of attributes and corresponding values in pairs.
	 * @return 	boolean 
	 */
	function add_element($elementName, $val = null, $attributes = Array()){
		//Override default copyright notice if provided
		if(strtolower($elementName) == 'copyright')
			$this->overrideDefaultCopyright = true;
		
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['optionalElements'][] = Array("elementName" => $elementName, "value" => $val, "attributes" => $attributes);
		}
		else
		{
			//Add to channel
			$this->feedData['optionalElements'][] = Array("elementName" => $elementName, "value" => $val, "attributes" => $attributes);
		}
	}

	
	/**
	 * Validates the feed to determine if required information has been included for a specific format
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/validate/   validate() Documentation
	 * @param 	string $elementName A string containing the feed format to validate against. See constants for details.
	 * @return 	boolean 
	 */
	function validate($format = RSS_2_0)
	{
		$this->set_feedConstruct($format);
		$valid = true;
		
		$rootConstruct = $this->feed_construct->getConstruct(ROOT_CONSTRUCT);
		
		//Get root construct children
		$feedConstruct = $this->feed_construct->getChildren($rootConstruct['commonName']);
		
		//Check if has feed channel (sub) element
		if(count($feedConstruct) > 0 && $feedConstruct[0]['commonName'] == CHANNEL_DATA_CONSTRUCT)
		{
			//Get get feed channel data constructs
			$feedConstruct = $this->feed_construct->getChildren(CHANNEL_DATA_CONSTRUCT);
		}

		$itemConstruct = $this->feed_construct->getChildren(ITEM_CONSTRUCT);
		
		//Reset Error Details Array
		$this->error_details = Array();
		
		//Loop through Feed Data Elements (stop if reached item construct)
		foreach($feedConstruct as $curConstruct)
		{
			if($curConstruct['commonName'] == ITEM_CONSTRUCT) //Item Data
			{	
				break;
			}
			else //Feed Data
			{	
				if(!isset($this->feedData[$curConstruct['commonName']]))
				{
					if($curConstruct['min'] > 0)
					{
						$this->error_details[] = Array('construct' => $curConstruct, 'data' => null);
						$valid = false;
					}
				}
				elseif(!$this->validateConstruct($this->feedData[$curConstruct['commonName']], $curConstruct, $curConstruct['commonName'],ITEM_CONSTRUCT))
					$valid = false;
			}
		}
		
		//Loop through Feed Items
		foreach($itemConstruct as $curConstruct)
		{
			foreach($this->itemsArray as $curItem)
			{
				if(!$this->validateConstruct($curItem[$curConstruct['commonName']], $curConstruct, $curConstruct['commonName'], null, $curItem))
					$valid = false;
			}
		}
		
		
		
		if($valid)
		{
			$this->error_details = null;
			return true;
		}
		else
			return false;
		
	}
	
	/**
	 * Validates the specified feed construct against the corresponding feed data if available
	 * @access 	private
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/validateconstruct/   validateconstruct() Documentation
	 * @param 	string $feedData A string containing the feed data corresponding to the current (or parent) construct, or an associative array
	 * @param 	string $construct An associative array containing the construct details
	 * @param 	string $feedCommonName A string containing the "commonName" value of the feed data being validated. 
	 * @param 	string $break_at A string containing the "commonName" of a specific construct to break the validation process at.
	 * @param 	string $item A string containing the feed format to validate against. See constants for details.
	 * @return 	boolean 
	 */
	private function validateConstruct($feedData, $construct, $feedCommonName, $break_at = null, $item = null)
	{	
		$valid = true;
	
		if($construct['min'] > 0) //Current is required.  Check if data exists
		{
			$valid = false;
			
			if($construct['commonName'] == $break_at)
				break;
			
			//Check that current construct has feed data 
		
			foreach($construct['attributes'] as $curAttribute){
				if(is_array($feedData) && isset($feedData[$curAttribute[0]]) && $feedData[$curAttribute[0]] != null){
					$valid = true;
				}
				elseif($curAttribute[0] == $feedCommonName){
					$valid = true;
				}
			}
		
			if(is_array($feedData) && isset($feedData[$construct['commonName']]) && $feedData[$construct['commonName']] != null){
				$valid = true;
			}
			elseif(!is_array($feedData) && $feedData != null){
				$valid = true;
			}
		
		
			//Get children
			$tmpChildren = $this->feed_construct->getChildren($construct['commonName']);
			
			if($tmpChildren !== false) { //Has Children
				//Call validateConstruct for each child construct (nested calls)
				$validChildren = true;
				foreach($tmpChildren as $curChild)
				{
					if(!$this->validateConstruct($feedData, $curChild, $feedCommonName))
						$validChildren = false;
				}
				$valid = $validChildren;
			}
			
			$parent = $this->feed_construct->getParent($construct['commonName']);
			
			//If child, check if parent element is required.
			if($construct['commonName'] != $feedCommonName)//is child
			{
				if($parent['min'] == 0) //parent not required
				{
					if(!is_array($feedData) && $feedData == null) //no value
						$valid = true;
					elseif(is_array($feedData) && count($feedData) > 0)//is array with values
						$valid = true;
				}
			}
				
			if($valid)
				return true;
			else
			{
				//Not valid.  
				if($item != null)
					$feedData = $item;
				
				//Add construct to error array
				$this->error_details[] = Array('construct' => $construct, 'data' => $feedData);
				return false;
			}
		}
		else
			return true;
	}
	
	/**
	 * Internal function used to display an invalid feed message with troubleshooting information if debug and validation are enabled in the FeedWriter class. 
	 * @access 	private
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/invalidfeed/   invalidfeed() Documentation
	 * @param 	string $feed_format A string containing a predefined constant that represents the feed format, such as RSS_2_0 (RSS 2.0).
	 * @return 	void 
	 */
	private function invalidFeed($feed_format)
	{
		/* //Debug
		echo "<b>Invalid feed:</b><br/>";
		foreach($this->error_details as $curError)
			echo $curError['construct']['commonName'] . "<br/>";
		exit;
		*/
		
		//Disable validation to prevent endless loop
		$this->feedSpecs['enableValidation'] = false;
		
		//Reset Feed to allow populating with error details
		$this->itemsArray = Array();
		$this->feedData = Array(
			"feedTitle" => 'A valid feed could not be generated in the ' . $feed_format . ' format', 
			"feedDescription" => 'A valid feed could not be generated in the ' . $feed_format . ' format', 
			"feedLink" => 'http://phpfeedwriter.webmasterhub.net/',
			"feedId" => 'http://phpfeedwriter.webmasterhub.net/', 
			"feedLanguage" => null,
			"feedCopyright" => null,
			"feedAuthor" => null,
			"feedWebmaster" => null,
			"feedRating" => null,
			"feedPubDate" => null,
			"feedDateUpdated" => null,
			"feedDocs" => null,
			"feedSkipDays" => null,
			"feedSkipHours" => null,
			"feedImage" => null,
			"feedInput" => null,
			"feedGenerator" => null,
			"feedRefreshInterval" => null,
			"feedIcon" => null,
			"feedSelfLink" => null,
			"feedLinks" => Array(),
			"feedContributor" => Array(),
			"feedCategory" => Array(),
			"feedCloud" => null,
			"optionalElements" => Array()
			);
		
		$this->set_date('2011-04-23T00:00:00Z',DATE_UPDATED);
		$this->set_date('2011-04-23T00:00:00Z',DATE_PUBLISHED);
		$this->set_id('feed_error');
		$this->set_selfLink('http://phpfeedwriter.webmasterhub.net/');
		$this->set_image('WebmasterHub.net', 'http://www.webmasterhub.net/img/logo.jpg','http://www.webmasterhub.net/');
		$this->set_copyright('(c) Daniel Soutter.');
		$this->set_language('EN-US');
		$this->set_webmaster('Daniel Soutter');
		$this->set_author(null, 'Daniel Soutter','http://phpfeedwriter.webmasterhub.net/');
		
		$this->add_item(
			'Troubleshoot Feed Error Details (Php FeedWriter)', 
			'<p>You are seeing this page because there was not enough information available to generate a feed using the ' . $feed_format . ' format, or an error occurred when generating the feed.</p>'. 
			'<p>If the problem persists, please notify the owner of the website.</p>
			<hr/>',
			'http://phpfeedwriter.webmasterhub.net/docs/'
			);
		$this->set_date(date('c'),DATE_PUBLISHED);
		$this->set_id('validation_details');
		
		if($this->debug && $this->error_details != null)
		{
			//Disable debug to allow error feed to be generated properly.
			$this->debug = false;
		
			$constructTableHTML = $this->listConstructs($feed_format, true);
		
			$error_details = '<p>Validation errors are generally caused when 
				data that is required by a feed format was not available to add to this feed.  If an error occurred while generating the feed XML, the details will be provided below in the Input Data column.
				</p> 
				
				<p>For help with, including enabling/disabling validation when outputting a feed 
				see the <a href="http://phpfeedwriter.webmasterhub.net/docs/">Php FeedWriter Documentation</a>.</p>
				
				<p>The validator or XML generation process failed at the following construct(s) due to invalid or missing feed data:</p>  

					' . $constructTableHTML . '

				<p><strong>Additional Help for:</strong><br/>
				<ul>
				<li><a href="http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/" target="_blank"><b>FeedConstruct</b> class members and functions</a></li>
				<li><a href="http://phpfeedwriter.webmasterhub.net/docs/feedwriter/" target="_blank"><b>FeedWriter</b> class members and functions</a> used to populate data in each construct</li>
				<li><a href="' . $this->feed_construct->docsUrl . '" target="_blank">XML Scema Definition for <b>' . $feed_format . '</b> feeds</a></li>
				</ul></p>
				<hr/>Note: This "debug" item is displayed because debug mode is currently enabled, to assist with configuration of the feed for output in varous formats. It is recommended that debug mode be 
				disabled prior to making the feed available on the internet.
				';
			
			$this->add_item(
				'Debug: Invalid feed construct ' . $this->error_details[0]['construct']['commonName'] . ' (' . $this->error_details[0]['construct']['elementName'] . ')', 
				$error_details, 
				'http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/'
				);
			$this->set_date(date('c'),DATE_PUBLISHED);
			$this->set_id('validation_details');
		}
		
		//update construct data to allow display of debug data table
		$this->feed_construct->construct['itemSummary']['type'] = 'html';
		$this->feed_construct->construct['itemContent']['type'] = 'html';
		$this->feed_construct->construct['itemContent']['limit'] = null;
		echo $this->getXML($feed_format);	
		exit;
	}
	
	/**
	 * Returns an array of all available feed output formats.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/getfeedformats/   getFeedFormats() Documentation
	 * @return 	array 
	 */
	function getFeedFormats()
	{
		//Return an array of all available feed formats.
		return $this->feed_Formats;
	}
	
	/**
	 * Generate and return the feed xml in the specified output format after populating the feed with data and items.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/getxml/   getXML() Documentation
	 * @param 	string $feed_format A string constant representing the format to output the feed. 
	 * @param 	string $category_filter A string containing a category to filter feed items by. This feature is not currently implemented.
	 * @return 	string 
	 */
	function getXML($feed_format = RSS_2_0, $category_filter = null)
	{	
		$elementNestLevel = 0;
		
		//Error flag
		$has_error = false;
		
		//Set the default timezone
		@date_default_timezone_set("GMT"); 
		
		//Create the xml write object
		$writer = new XMLWriter(); 
		
		//XMLWriter Output method:
		//------------------------------------------------------------------------------------------
		$writer->openMemory(); 							//	Xml stored in memory (allows set as variable, output 
														//  to file, print/echo	to user, etc.
		//$writer->openURI('php://output');  	//	Send xml to directly to browser/user (not implemented in this version)
		//-----------------------------------------------------------------------------------------
		
			//XML Version.  Include Charachter Encoding if supplied
			if($this->feedSpecs['xmlEncodeAs'] != null)
			{
				set_error_handler('outputError');
				try {
					$writer->startDocument('1.0', $this->feedSpecs['xmlEncodeAs']); 	
				} catch (Exception $e) {
					//Start XML Document without specifying encoding (common error if unsupported encoding provided).
					$writer->startDocument('1.0'); 	
				}
				restore_error_handler();
			}
			else
				$writer->startDocument('1.0'); 			
			
			//Add stylesheet details if provided
			foreach($this->feedSpecs['feedStylesheet'] as $curStylesheet)
				$writer->writePI("xml-stylesheet", 'type="' . $curStylesheet['type'] . '" href="' . $curStylesheet['address'] . '"');
		
		//------------------------------------------
		
		
		//Indent level
		$writer->setIndent($this->indent);
		
		//Validate and display notice if not valid
		if($this->feedSpecs['enableValidation'] && !$this->validate($feed_format) )
		{
			//Validation Enabled.  Feed is not valid for the specified output format.
			//echo $err_message;
			//exit;
			$writer->flush();
			$this->invalidFeed($feed_format);
		}	
		
		//Instantiate the FeedConstruct class
		$this->set_feedConstruct($feed_format);
		
		$this->set_docs($this->feed_construct->docsUrl);
		
		//Set content type for specified output format
		set_error_handler('outputError');
		try {
			$this->feed_construct->setHeaderContentType($this->feed_construct->format);
		} catch (Exception $e) {
			//Ignore if error setting content type.
		}
		restore_error_handler();
		
		//Get root, set current as root construct
		$current = $this->feed_construct->getConstruct(ROOT_CONSTRUCT);
		
		//Start the root element
		$writer->startElement($current['elementName']);
		$elementNestLevel++;
		
		//add attributes if available
		foreach($current['attributes'] as $curAttribute)
			$writer->writeAttribute($curAttribute[1], $curAttribute[3]); 
			
		//add custom namespaces if available
		foreach($this->feedSpecs['feedXMLNameSpace'] as $curNS)
			$writer->writeAttribute('xmlns:' . $curNS['prefix'], $curNS['url']); 
	
		//Get root construct children
		$children = $this->feed_construct->getChildren($current['commonName']);
		
		//Check if has feed channel (sub) element (Channel)
		if(count($children) > 0 && $children[0]['commonName'] == CHANNEL_DATA_CONSTRUCT)
		{
			//Move to CHANNEL_DATA_CONSTRUCT
			$current = $children[0];
			
			//Start the element
			$writer->startElement($current['elementName']);
			$elementNestLevel++;
			
			//add attributes if available
			if($current['attributes'] !== null)
			{
				foreach($current['attributes'] as $curAttribute){
				
					if($curAttribute[0] != 'default' && isset($this->feedData[$curAttribute[0]])){	
						//populated with feed data
						$writer->writeAttribute($curAttribute[1], $this->feedData[$curAttribute[0]]);
					}
					elseif($curAttribute[0] == 'default'){
						//Populated with constant value
						$writer->writeAttribute($curAttribute[1], $curAttribute[3]);
					}
				}
			}
			
			//Get get feed channel data constructs
			$children = $this->feed_construct->getChildren($current['commonName']);
		}
		
		$atItemConstruct = false;
		
		//Loop through Feed Data Elements (stop if reached item construct)
		foreach($children as $curConstruct)
		{
			if($curConstruct['commonName'] == ITEM_CONSTRUCT) //Item Data
			{	
				$atItemConstruct = true;
				break;
			}
			else //Feed Data
			{	
				//Test if feed has data for the current construct.  Skip if not.
				if(isset($this->feedData[$curConstruct['commonName']]) && $this->feedData[$curConstruct['commonName']] != null)
				{
					$iterator = 0;
					$mult = true;
					
					//Proceed single node in feedData array, or loop through set if multiple.
					do
					{
						if($curConstruct['max'] > 1 && $iterator >= $curConstruct['max'])
						{
							//Allws multiple, but has reached limit
							$mult = false;
						}
						elseif($curConstruct['max'] != 1) //Allows multiple instances of current construct
						{
							if(isset($this->feedData[$curConstruct['commonName']][$iterator]))
							{
								if(	!$this->writeConstruct(
										$writer, 
										$this->feedData[$curConstruct['commonName']][$iterator], 
										$curConstruct, 
										$curConstruct['commonName'] )
									) {
									$has_error = true;
								}
								$iterator++;
							}
							else
							{
								//Reached end of feed data array.
								$mult = false;
							}
						}
						else //Single instance of current construct
						{	
							if(	!$this->writeConstruct(
								$writer, 
								$this->feedData[$curConstruct['commonName']], 
								$curConstruct, 
								$curConstruct['commonName'])
							) {
								$has_error = true;
							}
							$mult = false;
						}
					}while($mult);
				}
			}
		}
		
		//Close channel element if required
		if($atItemConstruct)
		{
			//Item construct reached when processing feed channel data
			//Items will be added to the channel element
		}
		else
		{
			//Reached end of feed data (channel sub elements), but havent reached items
			//Items are outside of the channel element
			$writer->endElement(); //Close the channel element
			$elementNestLevel--;
			
			//Add non channel elements to feed if available, 
			//exluding items (eg. image, input - RSS 1.0), as they are added 
			//separately
			
			//Move back to parent node if applicable:
			if($current['parentConstruct'] != null){
				$current = $this->feed_construct->getConstruct($current['parentConstruct']);
				
				//Get children.   
				$children = $this->feed_construct->getChildren($current['commonName']);
				
				//Loop through and write element if construct is not item or channel construct (required for RSS 1.0 output)
				foreach($children as $curConstruct){
					if($curConstruct['commonName'] != ITEM_CONSTRUCT && 
						$curConstruct['commonName'] != CHANNEL_DATA_CONSTRUCT){
						
						//Write element if feed data exists for current
						if(isset($this->feedData[$curConstruct['commonName']]) && $this->feedData[$curConstruct['commonName']] != null){
							if( !$this->writeConstruct(
								$writer, 
								$this->feedData[$curConstruct['commonName']], 
								$curConstruct, 
								$curConstruct['commonName'])
							) {
								$has_error = true;
							}
						}
					}
				}
			}
		}
		
		//Add Items to feed xml
		$item_construct = $this->feed_construct->getConstruct(ITEM_CONSTRUCT);
		$item_construct_children = $this->feed_construct->getChildren(ITEM_CONSTRUCT);
		$creditsIncluded = false;
		$itemNumber = 0;
		
		//Loop through items in feed
		foreach($this->itemsArray as $currentItem)
		{
			$itemNumber ++;
			if($item_construct['max'] > 1 && $itemNumber >= $item_construct['max'])
			{
				//has reached limit
				break;
			}
			
			//Start the element
			$writer->startElement($item_construct['elementName']);
			
			//add attributes if available
			if($item_construct['attributes'] !== null)	{
				foreach($item_construct['attributes'] as $curAttribute){
				
					if($curAttribute[0] != 'default' && isset($currentItem[$curAttribute[0]])){	
						//populated with feed data
						$writer->writeAttribute($curAttribute[1], $currentItem[$curAttribute[0]]);
					}
					elseif($curAttribute[0] == 'default'){
						//Populated with constant value
						$writer->writeAttribute($curAttribute[1], $curAttribute[3]);
					}
				}
			}
									
			foreach($item_construct_children as $curItemConstruct)
			{
				//Test if current feed item has data for the current construct.  Skip if not.
				if(isset($currentItem[$curItemConstruct['commonName']]) && $currentItem[$curItemConstruct['commonName']] != null)
				{
					$iterator = 0;
					$mult = true;
					$currentFeedData;
					do
					{
						if($curItemConstruct['max'] > 1 && $iterator >= $curItemConstruct['max'])
						{
							//Allws multiple, but has reached limit
							$mult = false;
						}
						elseif($curItemConstruct['max'] != 1)
						{
							if(isset($currentItem[$curItemConstruct['commonName']][$iterator]))
							{
								if( !$this->writeConstruct(
									$writer, 
									$currentItem[$curItemConstruct['commonName']][$iterator], 
									$curItemConstruct, 
									$curItemConstruct['commonName'] )
								) {
									$has_error = true;
								}
								
								$iterator++;
							}
							else
							{
								$mult = false;
							}
						}
						else
						{	
							if( !$this->writeConstruct(
								$writer, 
								$currentItem[$curItemConstruct['commonName']], 
								$curItemConstruct, 
								$curItemConstruct['commonName'])
							) {
									$has_error = true;
							}
							$mult = false;
						}
					}while($mult);
				}
			}
			//Close the current ITEM_CONSTRUCT element
			$writer->endElement();
		}
		
		/**
		 * It is a breach of the terms of use to disable, modify or remove the Php FeedWriter footer item if you have not purchased Php FeedWriter.
		 * Please see the Terms of Use for details:  http://phpfeedwriter.webmasterhub.net/terms/  
		 */		
		//-Start add footer----------------------
		$this->feed_construct->construct['itemSummary']['type'] = 'html';
		$this->feed_construct->construct['itemContent']['type'] = 'html';
		$item_construct_children = $this->feed_construct->getChildren(ITEM_CONSTRUCT);
		$this->add_credit();
		$writer->startElement($item_construct['elementName']);
		foreach($item_construct['attributes'] as $curAttribute)
			$writer->writeAttribute($curAttribute[1], $curAttribute[3]); 
		$currentItem = $this->itemsArray[count($this->itemsArray)-1];
		$creditsIncluded = true;
		foreach($item_construct_children as $curItemConstruct){
			if(isset($currentItem[$curItemConstruct['commonName']]) && $currentItem[$curItemConstruct['commonName']] != null)
				$this->writeConstruct(
					$writer, 
					$currentItem[$curItemConstruct['commonName']], 
					$curItemConstruct, 
					$curItemConstruct['commonName']);}
					//End add footer-------------

					
		//Close remaining elements
		for($i=$elementNestLevel; $i>0; $i--)
			$writer->endElement();
			
		//End Xml Document
		$writer->endDocument();
		
		//Output memory if no error.  Display error if debug enabled and error
		if($has_error) {
			$writer->flush();
			$this->invalidFeed($feed_format);
			return false;
		}
		else {
			//(!defined(cr))?exit:null;
			$this->xml = $writer->outputMemory(true);

			//Output the Feed XML if footer included
			//($creditsIncluded)?null:$this->xml = null;
			/*
			if($this->hasCredit)		
				return $this->xml;
			else
				return false;
			*/
			return $this->xml;
		}
	}
	
	/**
	 * Internal function used to output an XML element based on a single construct from the FeedConstruct class.
	 * @access 	private
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/writeconstruct/   writeConstruct() Documentation
	 * @param 	string $writer The XMLWriter object being used to build the feed XML
	 * @param 	string $feedData An array of strings, or a single string variable containing data from the feed that corresponds to the current construct.
	 * @param 	string $construct An associating array of strings representing a single construct from the FeedConstruct class.
	 * @param 	string $feedCommonName A string containing the value of the "commonName" for the current construct, which should match a key in the array of feed data or feed item data.
	 * @return 	boolean 
	 */
	private function writeConstruct($writer, $feedData, $construct, $feedCommonName)
	{
		/**
		 * Set has_error to false.  This variable is set to true if there is an error 
		 * while outputting the current construct and debug is enabled.
		 */
		$has_error = false;

		//Check that current construct has feed data 
		//if current construct is a child (not yet checked for input)
		if($construct['commonName'] != $feedCommonName)
		{
			//Current construct is a child
			$found = false;
			foreach($construct['attributes'] as $curAttribute){
				if(is_array($feedData) && isset($feedData[$curAttribute[0]]) && $feedData[$curAttribute[0]] != null){
					$found = true;
				}
				elseif($curAttribute[0] == $feedCommonName){
					$found = true;
				}
			}
			if(is_array($feedData) && isset($feedData[$construct['commonName']]) && $feedData[$construct['commonName']] != null){
				$found = true;
			}
			elseif(!is_array($feedData) && $feedData != null){
				$found = true;
			}
			elseif(isset($this->feedData[$construct['commonName']]) && $this->feedData[$construct['commonName']] != null)
				$found = true;
			
			if(!$found)
				return true;
			
			//Check if current is a child, and repeating. (image_toc_li)
			if($construct['max'] == -1 && isset($this->feedData[$construct['commonName']]))
			{
				foreach($this->feedData[$construct['commonName']] as $tmpFeedData)
				{
					$this->writeConstruct(
						$writer, 
						$tmpFeedData, 
						$construct, 
						$construct['commonName']
						);
				}
				return true;
			}
		}

		
		$setAsAttribute = false;
		//Start the element
		$writer->startElement($construct['elementName']);
		
		//add attributes if available
		if($construct['attributes'] !== null)
		{
			set_error_handler('outputError');
			try {
				foreach($construct['attributes'] as $curAttribute){
					if($curAttribute[0] != 'default'){	
						//populated with feed data
						if(is_array($feedData) && isset($feedData[$curAttribute[0]])){
							$writer->writeAttribute($curAttribute[1], $feedData[$curAttribute[0]]);
							$setAsAttribute = true;
						}
						elseif($curAttribute[0] == $feedCommonName){
							$writer->writeAttribute($curAttribute[1], $feedData);
							$setAsAttribute = true;
						}
					}
					elseif($curAttribute[0] == 'default'){
						//Populated with constant value
						$writer->writeAttribute($curAttribute[1], $curAttribute[3]);
					}
				}
			} catch (Exception $e) {
				if($this->debug) {
					$has_error = true;
					$this->error_details[] = Array('construct' => $construct, 'data' => 'Error ' . $e->getCode() . ': ' . $e->getMessage());
				}
				else {
					//ignore error
				}
			}
			restore_error_handler();
			
		}
		
		//Get children
		$tmpChildren = $this->feed_construct->getChildren($construct['commonName']);
		if($tmpChildren !== false) { //Has Children
			//Call writeConstruct for each child construct (nested calls)
			foreach($tmpChildren as $curChild)
				$this->writeConstruct($writer, $feedData, $curChild, $feedCommonName);
		}
		else{ 
		
			set_error_handler('outputError');
			try {
				//No child constructs.  Write value.
				if(is_array($feedData) && isset($feedData[$construct['commonName']]) && !$setAsAttribute){
				
					if($construct['limit'] != null && $construct['limit'] > 0)
						$data = substr($feedData[$construct['commonName']], 0 , $construct['limit']);
					else
						$data = $feedData[$construct['commonName']];
				}
				elseif(!is_array($feedData) && $construct['commonName'] == $feedCommonName && !$setAsAttribute){
					
					if($construct['limit'] != null && $construct['limit'] > 0)
						$data = substr($feedData, 0 , $construct['limit']);
					else
						$data = $feedData;
				}
				else
					$data = null;
				
				if($data != null)
				{
					//Write value for current element and data type
					switch ($construct['type'])
					{
						case $construct['type'] == 'string' && $this->feedSpecs['useCDATA']:
							if($this->feedSpecs['xmlEncodeAs'] != null)
								$writer->writeCData(htmlentities($data, ENT_COMPAT, $this->feedSpecs['xmlEncodeAs']));
							else
								$writer->writeCData(htmlentities($data));
							break;
						case $construct['type'] == 'string' && !$this->feedSpecs['useCDATA']:
							if($this->feedSpecs['xmlEncodeAs'] != null)
								$writer->writeRaw(htmlentities($data, ENT_COMPAT, $this->feedSpecs['xmlEncodeAs']));
							else
								$writer->writeRaw(htmlentities($data));
							break;
						case $construct['type'] == 'uri':
							$writer->writeRaw($data);
							break;
						case $construct['type'] == 'email' && $this->feedSpecs['useCDATA']:
							$writer->writeCData($data);
							break;
						case $construct['type'] == 'email' && !$this->feedSpecs['useCDATA']:
							$writer->writeRaw($data);
							break;
						case $construct['type'] == 'xml':
							$writer->writeRaw($data);
							break;
						case $construct['type'] == 'html':
							$writer->writeCData($data);
							break;
						case $construct['type'] == 'date_iso':
							$writer->text( date('c',strtotime($data))); 
							break;
						case $construct['type'] == 'date_rfc':
							$writer->text( date('r',strtotime($data))); 
							break;
						default:
							$writer->text((string)$feedData); 
					}
				}
			} catch (Exception $e) {
				if($this->debug) {
					$has_error = true;
					$this->error_details[] = Array('construct' => $construct, 'data' => 'Error ' . $e->getCode() . ': ' . $e->getMessage());
				}
				else {
					//ignore error
				}
			}
			restore_error_handler();
		}
		//Close the element
		$writer->endElement();
		
		//return true if success or no error, false if debug enabled and error
		if($has_error)
			return false;
		else
			return true;
	}
	//function rc(){!defined('cr')?define('cr','cr'):null;}
	
	/**
	 * Outputs the feedXML to a file in the specified format. 
	 * @access 	public
	 * @uses	getXML()
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/writetofile/   writeToFile() Documentation
	 * @param 	string $fileName A string containing the path and filename of the file to create.
	 * @param 	string $feed_format A string constant indicating the format of feed to output.
	 * @param 	string $categories A single string, or array of strings containing the item categories to filter by. This functionality is not currently implemented.
	 * @return 	void 
	 */
	function writeToFile($fileName, $feed_format = RSS_2_0, $categories = null)
	{
		//$this->closeDocument();
		
		$fh = fopen($fileName, 'w') or die("can't open file");
		
		if(!$categories == null)
			fwrite($fh, $this->getXML());
		else
		{
			fwrite($fh, $this->getXML());
			//fwrite($fh, $this->getXMLFiltered($categories));
		}
		
		fclose($fh);
	}
	
	/**
	 * Help support Php FeedWriter.
	 *
	 * It is a breach of the terms of use to disable, modify or remove the Php FeedWriter footer item if you have not purchased Php FeedWriter.
	 * Please see the Terms of Use for details:  http://phpfeedwriter.webmasterhub.net/terms/  
	 *
	 * @access 	private
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_credit/   add_credit() Documentation
	 * @return 	void 
	 */
	private function add_credit()
	{
		$this->add_item(
				'Php Feedwriter', 
				'<font size=1>Powered by <a href=http://phpfeedwriter.webmasterhub.net/><b>Php Feedwriter</b></a>',
				'http://phpfeedwriter.webmasterhub.net/');//$this->rc();
				$this->set_date('2011-06-26T00:00:00Z',DATE_UPDATED); 
				//$this->hasCredit = true;
				$this->set_author(null, 'Daniel Soutter','http://phpfeedwriter.webmasterhub.net/');
	}
	
	/**
	 * Not currently implemented.  See online docuemntation for details of intended functionality.
	 * @access 	private
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/updatefeeddata/   updateFeedData() Documentation
	 * @param 	string $element_name The commonName for the element being updated in teh feed.
	 * @return 	void 
	 */
	private function updateFeedData($element_name)
	{
		
	}
	
	/**
	 * Returns a HTML table containing details of the constructs in an instance of the FeedConstuct class for a specific output format.
	 *
	 * Displays a table of data for all constructs for a particular format. Includes limits, required elements, attributes and links
	 * to the online documentation related to each element.
	 *
	 * If customisations have been made to the construct of a feed format during runtime, the 
	 * changes will be visible in this table, which may be useful for debugging.
	 *
	 * This function is also called internally when a feed fails the validation process and debug is enabled.  
	 * Only details of the invalid constructs are listed in the table if the second parameter ($error) is true.
	 * @access 	public
	 * @link 	http://phpfeedwriter.webmasterhub.net/docs/feedwriter/listconstructs/   listConstructs() Documentation
	 * @param 	string $format A string constant representing the feed format to list the details of.
	 * @param 	string $error True/False indicating if a full list of constructs should be outputted, or only constructs with errors during the validation process.
	 * @return 	void 
	 */
	public function listConstructs($format, $error = false)
	{
		$this->set_feedConstruct($format);
		$constructTableHTML = '';
		
		if($error)
		{
			$constructs = Array();
			if(is_array($this->error_details) && count($this->error_details) > 0)
			{
				foreach($this->error_details as $curError)
				{
					$curError['construct']['data'] = $curError['data'];
					$constructs[] = $curError['construct'];
				}
			}
			else
				return 'No Error';
		}
		else
			$constructs = $this->feed_construct->construct;
		
		foreach($constructs as $currConstruct)
		{
			$attributesTableHTML = '';
			foreach($currConstruct['attributes'] as $curAttribute){
				$attributesTableHTML .= 
				'<tr>' . 
					'<td>' . $curAttribute[0] . '&nbsp;</td>' . 
					'<td>' . $curAttribute[1] . '&nbsp;</td>' . 
					'<td>' . $curAttribute[2] . '&nbsp;</td>' . 
					'<td>' . $curAttribute[3] . '&nbsp;</td>' . 
				'</tr>';
			}
			
			if(count($currConstruct['attributes']) > 0){
				$attributesTableHTML = '
							<table style="border:1px solid #CCCCCC; text-align:left" cellpadding=2 width=100%>
								<tr><th>Common name</th><th>Attribute name</th><th>Required?</th><th>Default Value</th></tr>
								' . $attributesTableHTML . '
							</table>';
			}
			
			if($currConstruct['min'] == 1)
				$min = '<u>' . $currConstruct['min'] . ' (required)</u>';
			else
				$min = $currConstruct['min'] . ' (optional)';
			
			if($currConstruct['max'] == -1)
				$max = 'Unlimited';
			else
				$max = $currConstruct['max'];
			
			$data = '';
			if($error && isset($currConstruct['data']))
			{
				if(is_array($currConstruct['data']))
				{	
					if(isset($currConstruct['data']['itemTitle'])) //Item
					{
						$data = 'Item (Title): "' . $currConstruct['data']['itemTitle'] . "\"<br/>\n";
						if(isset($currConstruct['data'][$currConstruct['commonName']]) && $currConstruct['data'][$currConstruct['commonName']] != null)
							$data .= $currConstruct['commonName'] . ': "' . $currConstruct['data'][$currConstruct['commonName']] . "\"<br/>\n";
						else
							$data .= $currConstruct['commonName'] . ": none<br/>\n";
					}
					else //feed data
					{
						while( $element = each( $currConstruct['data'] ) )
						{
														 $data .= $element[ 'key' ];
								 $data .=  ': ';
								 if($element[ 'value' ] == null)
									$data .= "none";
								 else
									$data .=  $element[ 'value' ];
								 $data .=  "<br />\n";
							
						}
					}
				}
				elseif($currConstruct['data'] != null)
					$data = $currConstruct['data'];
				else
					$data = "none";
			}
				
			$constructTableHTML .= '<tr>';
			
			if($currConstruct['function'] != null)
				$constructTableHTML .= '<td><a href="http://phpfeedwriter.webmasterhub.net/docs/' . $this->feed_construct->functions[$currConstruct['function']] . '" target="_blank"><b>' . $currConstruct['function'] . '()</b></a></td>';
			else
				$constructTableHTML .= '<td>&nbsp;</td>';
				
			$constructTableHTML .= '<td>' . $currConstruct['commonName'] . '</td>
			<td>' . $currConstruct['elementName'] . '&nbsp;</td>
			<td>' . $currConstruct['parentConstruct'] . '&nbsp;</td>
			<td>' . $min . '&nbsp;</td>
			<td>' . $max . '&nbsp;</td>
			<td>' . $currConstruct['limit'] . '&nbsp;</td>
			<td>' . $currConstruct['type'] . '&nbsp;</td>';
			
			if($currConstruct['example'] != null)
				$constructTableHTML .= '<td>' . $currConstruct['example'] . '&nbsp;</td>';
			elseif($currConstruct['example'] == null && $currConstruct['function'] != null)
				$constructTableHTML .= '<td>See <a href="http://phpfeedwriter.webmasterhub.net/docs/' . $this->feed_construct->functions[$currConstruct['function']] . '" target="_blank">' . $currConstruct['function'] . '()</a> documentation.</td>';
			else
				$constructTableHTML .= '<td>&nbsp;</td>';
			
			$constructTableHTML .= '<td>' . $attributesTableHTML . '&nbsp;</td>';

			if($error)
				$constructTableHTML .= '<td>' . $data . '&nbsp;</td>';
			
			$constructTableHTML .= '</tr>';
			
		}
		
		if(!$error)
			$html_output = '<h3><a href="http://phpfeedwriter.webmasterhub.net/">Php FeedWriter</a></h3><hr/>';
		else
			$html_output = '';
		
		$html_output .= '<h3>Output format: ' . $format . '</h3>
				<p>
					<a href="' . $this->feed_construct->classDocsUrl . '">View Online Version of the ' . $format . ' Construct Documentation</a>
					for more information about this construct and feed format.
				</p>
				<br/>
				<table border=1 cellpadding=2>
					<tr>
					<th>Documenation</th>
					<th>Common name</th>
					<th>Element name</th>
					<th>Parent construct</th>
					<th>Min occurances</th>
					<th>Max occurances</th>
					<th>Character limit</th>
					<th>Data type</th>
					<th>Example</th>
					<th>Attributes</th>';
					
					if($error)
						$html_output .= '<th>Input Data</th>';
					
					$html_output .= '</tr>
					' . $constructTableHTML . '
				</table>';
				
		return $html_output;
	}
	
	/**  
	 * Depreciated Functions:
	 * 
	 * The following functions are no longer in use.  They remain in the class for 
	 * cases where they may be still in use, but will no longer be supported.  
	 * 
	 * Future versions of the FeedWriter class may not include the functions below.
	 */
    /**
	 * @deprecated
	 */
	function addImage(){}
	/**
	 * @deprecated
	 */
	function channelCloud(){}
	/**
	 * @deprecated
	 */
	function addCategory(){}
	/**
	 * @deprecated
	 */
	function addItem(){}
	/**
	 * @deprecated
	 */
	function addElement(){}
	
	/**
	 * getXMLFiltered() was never implemented. Future versions of Php FeedWriter may implement similar functionality
	 * using the $category_filter parameter passed to the {@link getXML()} function.
	 * @deprecated
	 */
	function getXMLFiltered($categories)
	{	
		//  Use getXML() instead, passing the catgory filter value as the $category_filter parameter.
	}
	
	/**
	 * Closes an item element if left open to include additional data.
	 * @deprecated
	 */
	function closeItem()
	{
		if($this->itemOpen)
		{
			$this->xml .= '</item>
';//end item tag with new line
			$this->itemOpen = false;
		}
	}
	
	/**
	 * Closes the Channel and rss elements as well as the document
	 * @deprecated
	 */
	private function closeDocument()
	{
		//Create the xml write object
		$writer = new XMLWriter(); 
		$writer->openMemory(); 
		$writer->setIndent(4); 
		
		// Start the xml elements which requiring closing (allow endElement() function to work)
		$writer->startElement('rss');
		$writer->startElement('channel'); 
		$writer->text('.');
		
		//Flush the current xml to remove start tags, but allow correct elements to be closed.
		$writer->flush(); 
		
		$writer->endElement(); 
		//End channel -------------------------------------------------------------------------
			
		// End rss 
		$writer->endElement(); 
		//-----------------------------------------------------------------------------------------
		//*****************************************************************************************

		//End Xml Document
		$writer->endDocument(); 

		//$writer->flush(); 
		$this->xml .= $writer->outputMemory(true);
	}
}

function outputError($error_no, $error_str, $error_file, $error_line, array $error_context)
{
	$error_code = 0;
	throw new ErrorException($error_str, $error_code, $error_no, $error_file, $error_line);
}
?>