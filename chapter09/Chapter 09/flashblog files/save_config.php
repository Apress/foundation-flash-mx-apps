<?

ini_set("always_populate_raw_post_data", true);

// Include the database details, and make a connection
include("../../fb_includes/database.php");

// Include the checkLogin function
include("functions.php");

$currentTag = "";
$username = "";
$usernameValue = "";
$blogLimit = "";
$blogLimitValue = "";
$templatePath = "";
$templatePathValue = "";
$templateFilename = "";
$templateFilenameValue = "";
$sessionID = "";
$sessionIDValue = "";

// The handler for the element opening tags
function startElementHandler($parser, $name, $attr) {

	global $currentTag;

	$currentTag = $name;
   
}

// The handler for the element closing tags
function endElementHandler($parser, $name) {
	
	global $username, $usernameValue, $blogLimit, $blogLimitValue, $templatePath, $templatePathValue, $templateFilename, $templateFilenameValue, $sessionID, $sessionIDValue;
  
  	if (strcmp($name, "USERNAME") == 0) {
	
		$username = $usernameValue;
		$usernameValue = "";
		
	} else if (strcmp($name, "BLOGLIMIT") == 0) {

		$blogLimit = $blogLimitValue;
		$blogLimitValue = "";
		
	} else if (strcmp($name, "SESSION_ID") == 0) {

		$sessionID = $sessionIDValue;
		$sessionIDValue = "";

	} else if (strcmp($name, "TEMPLATEPATH") == 0) {
	
		$templatePath = $templatePathValue;		
		$templatePathValue = "";
		
	} else if (strcmp($name, "TEMPLATEFILENAME") == 0) {
	
		$templateFilename = $templateFilenameValue;
		$templateFilenameValue = "";
	
	}
   
}

// The handler for character data
function cdataHandler($parser, $data) {
	
	global $currentTag, $usernameValue, $blogLimitValue, $templatePathValue, $templateFilenameValue, $sessionIDValue;
	
	if (strcmp($currentTag, "USERNAME") == 0) {
	
		$usernameValue .= $data;

	} else if (strcmp($currentTag, "BLOGLIMIT") == 0) {

		$blogLimitValue .= $data;
		
	} else if (strcmp($currentTag, "SESSION_ID") == 0) {
	
		$sessionIDValue .= $data;
	
	} else if (strcmp($currentTag, "TEMPLATEPATH") == 0) {
	
		$templatePathValue .= $data;
	
	} else if (strcmp($currentTag, "TEMPLATEFILENAME") == 0) {
	
		$templateFilenameValue .= $data;
	
	}
	
}

// Create the XML parser
$parser = xml_parser_create();

// Register the start and end element handlers
xml_set_element_handler($parser, "startElementHandler", "endElementHandler");

// Register the character data parser
xml_set_character_data_handler($parser, "cdataHandler");

if (!xml_parse($parser, $HTTP_RAW_POST_DATA, true)) {

	$str = sprintf("XML error %d %d", xml_get_current_line_number($parser), xml_get_current_column_number($parser));
	
	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>$str</msg></FlashBlog>";
	exit;
	
}

// Ok, finished parsing the XML
xml_parser_free($parser);

if (!isset($blogLimit) || $blogLimit == "") {

	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>XML packet failed, the blog limit was null.</msg></FlashBlog>";
	exit;
	
} else if (!isset($sessionID) || $sessionID == "") {

	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>XML packet failed, there was no session id. Are you logged in?</msg></FlashBlog>";
	exit;

} else if (!isset($templatePath) || $templatePath == "") {

	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>XML packet failed, the template path was undefined.</msg></FlashBlog>";
	exit;

} else if (!isset($templateFilename) || $templateFilename == "") {

	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>XML packet failed, the template filename was undefined.</msg></FlashBlog>";
	exit;

}

// Check to see if the user is logged in
if (!checkLogin($username, $sessionID)) {

	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>You are not logged in.</msg></FlashBlog>";
	exit;

}

// Write the information to config.xml
$content = "<?xml version=\"1.0\"?><FlashBlog><blogLimit>$blogLimit</blogLimit><templatePath>" . urldecode($templatePath) . "</templatePath><templateFilename>" . urldecode($templateFilename) . "</templateFilename></FlashBlog>";

$path = "/www/s/smebberson/htdocs/flashblog/XML";
$filename = "/config.xml";

$fp = @fopen($path . $filename, "w");
$write = @fwrite($fp, $content);

if ($write == -1) {

	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>Failed while writing config.xml.</msg></FlashBlog>";
	exit;

} else {

	echo "<?xml version=\"1.0\"?><FlashBlog><status>true</status><msg>The configuration file was successfully updated.</msg></FlashBlog>";

}

?>