<?
ini_set("always_populate_raw_post_data", true);

// Include the database details, and make a connection
include("../../fb_includes/database.php");

$currentTag = "";
$username = "";
$usernameValue = "";

// The handler for the element opening tags
function startElementHandler($parser, $name, $attr) {

	global $currentTag;

	$currentTag = $name;
   
}

// The handler for character data
function cdataHandler($parser, $data) {
	
	global $currentTag, $usernameValue;
	
	if (strcmp($currentTag, "USERNAME") == 0) {
	
		$usernameValue .= $data;
	
	}
}

// The handler for the element closing tags
function endElementHandler($parser, $name) {
	
	global $username, $usernameValue;
  
  	if (strcmp($name, "USERNAME") == 0) {
	
		$username = $usernameValue;
		$usernameValue = "";
	
	}
   
}

// Create the XML parser
$parser = xml_parser_create();

// Register the start and end element handlers
xml_set_element_handler($parser, "startElementHandler", "endElementHandler");

// Register the character data parser
xml_set_character_data_handler($parser, "cdataHandler");

// Parse the XML document
if (!@xml_parse($parser, $HTTP_RAW_POST_DATA, true)) {
	
	// An error occured, info the Flash file, and exit the script
	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>XML packet was invalid. Please try again.</msg></FlashBlog>";
	exit;
	
}

// Ok, finished parsing the XML, free the parser
xml_parser_free($parser);

if ($username == "") {

	// The username didn't exist, but we need it to user in the query
	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>An error occured while removing you from the system. Please close the browser window.</msg></FlashBlog>";
	exit;

}

// Update the database
$stmt = "UPDATE fb_user SET session_id=0 WHERE username='$username'";
$result = @mysql_query($stmt, $db);

// Check the query was successful
if ($result == 0) {

	// Query failed, return a message
	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>An error occured while removing you from the system. Please close the browser window.</msg></FlashBlog>";
	exit;

}
	
// All is ok, the user has been logged out of the system
echo "<?xml version=\"1.0\"?><FlashBlog><status>true</status><msg>You've been logged out of the system.</msg></FlashBlog>";

?>