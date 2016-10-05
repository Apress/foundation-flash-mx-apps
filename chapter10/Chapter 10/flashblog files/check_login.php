<?
ini_set("always_populate_raw_post_data", true);

// Include the database details, and make a connection
include("../../fb_includes/database.php");

$currentTag = "";
$username = "";
$usernameValue = "";
$sessionID = "";
$sessionIDValue = "";
$level = "";
$levelValue = "";

// The handler for the element opening tags
function startElementHandler($parser, $name, $attr) {

	global $currentTag;

	$currentTag = $name;
   
}


// The handler for the character data
function cdataHandler($parser, $data) {
	
	global $currentTag, $usernameValue, $sessionIDValue, $levelValue;
	
	if (strcmp($currentTag, "USERNAME") == 0) {
	
		$usernameValue .= $data;
	
	} else if (strcmp($currentTag, "SESSION_ID") == 0) {
	
		$sessionIDValue .= $data;
	
	} else if (strcmp($currentTag, "USER_LEVEL") == 0) {
	
		$levelValue .= $data;
	
	}
	
}

// The handler for the element closing tags
function endElementHandler($parser, $name) {
	
	global $username, $usernameValue, $sessionID, $sessionIDValue, $level, $levelValue;
  
  	if (strcmp($name, "USERNAME") == 0) {
	
		$username = $usernameValue;
		$usernameValue = "";
	
	} else if (strcmp($name, "SESSION_ID") == 0) {
	
		$sessionID = $sessionIDValue;
		$sessionIDValue = "";
	
	} else if (strcmp($name, "USER_LEVEL") == 0) {
	
		$level = $levelValue;
		$levelValue = "";
	
	}
   
}

// Create the XML parser
$parser = xml_parser_create();

// Register the start and end element handlers
xml_set_element_handler($parser, "startElementHandler", "endElementHandler");

// Register the character data parser
xml_set_character_data_handler($parser, "cdataHandler");

if (!xml_parse($parser, $HTTP_RAW_POST_DATA, true)) {
	
	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>XML packet was invalid. Please try again.</msg></FlashBlog>";
	exit;
	
}

// OK, finished parsing the XML
xml_parser_free($parser);

// Query the database
$stmt = "SELECT session_id, user_type FROM fb_user WHERE username='$username'";
$result = @mysql_query($stmt, $db);

if ($result == 0) {

	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>This is a restricted area. You need to login before you can continue. Please enter your username and password.</msg></FlashBlog>";
	exit;

}

if (@mysql_num_rows($result) <= 0) {

	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>This is a restricted area. You need to login before you can continue. Please enter your username and password.</msg></FlashBlog>";
	return;
	
}

$row = @mysql_fetch_array($result);

if ($row['session_id'] == $sessionID) {

	if ($level == "user") {
	
		echo "<?xml version=\"1.0\"?><FlashBlog><status>true</status><msg>User is logged in.</msg></FlashBlog>";
		exit;
	
	}

	if ($row['user_type'] != "administrator") {
	
		echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>You must be an Administrator to edit FlashBlog's configuration.</msg></FlashBlog>";
		exit;
	
	}

	echo "<?xml version=\"1.0\"?><FlashBlog><status>true</status><msg>User is logged in.</msg></FlashBlog>";
	exit;

}

echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>This is a restricted area. You need to login before you can continue. Please enter your username and password.</msg></FlashBlog>";

?>
