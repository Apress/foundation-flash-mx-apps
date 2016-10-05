<?php

ini_set("always_populate_raw_post_data", true);

// Include the database details, and make a connection
include("../../fb_includes/database.php");

// Define the variables needed to parse the XML
$currentTag = "";
$password = "";
$username = "";
$usernameValue = "";
$passwordValue = "";

// The handler for the element opening tags
function startElementHandler($parser, $name, $attr) {

	global $currentTag;

	$currentTag = $name;

}

// The handler for the element closing tags
function endElementHandler($parser, $name) {
	
	global $password, $username, $usernameValue, $passwordValue;
  
  	if (strcmp($name, "USERNAME") == 0) {
	
		$username = $usernameValue;
		$usernameValue = "";
	
	} elseif (strcmp($name, "PASSWORD") == 0) {
	
		$password = $passwordValue;
		$passwordValue = "";
	
	}

}

// The handler for character data
function cdataHandler($parser, $data) {
	
	global $currentTag, $usernameValue, $passwordValue;
	
	if (strcmp($currentTag, "USERNAME") == 0) {
	
		$usernameValue .= $data;
	
	} elseif (strcmp($currentTag, "PASSWORD") == 0) {
	
		$passwordValue .= $data;
	
	}
}

// Create the XML parser
$parser = xml_parser_create();

// Register the start and end element handlers
xml_set_element_handler($parser, "startElementHandler", "endElementHandler");

// Register the character data parser
xml_set_character_data_handler($parser, "cdataHandler");

// Parse the XML, sending an error message to the SWF if failed
// and then exit the script
if (!xml_parse($parser, $HTTP_RAW_POST_DATA, true)) {
	
	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>XML packet was invalid. Please try again.</msg></FlashBlog>";
	exit;
	
}

// OK, finished parsing the XML
xml_parser_free($parser);

// Query the database for the username and password
$result = @mysql_query("SELECT username, password FROM fb_user WHERE username='$username';", $db);

// Check the query, send an error message if the query failed and then exit the script
if ($result == 0) {

	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>The username was incorrect. Please try again.</msg></FlashBlog>";
	exit;

}

// Check that at least one row was returned, if it wasn't, inform the SWF of an error
if (@mysql_num_rows($result) <= 0) {

	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>The username was incorrect. Please try again.</msg></FlashBlog>";
	exit;
	
}

// Retrieve the row from the database
$row = @mysql_fetch_array($result);

// Compare the username and password from the XML to the username and password from the database
if ($password == $row['password']) {

	// Generate the session id - with username, password and time in an MD5 hash
	$sessionID = md5($username . $password . microtime());
	
	// Update the session_id field with the new session id
	$result = @mysql_query("UPDATE fb_user SET session_id='$sessionID' WHERE username='$username';", $db);

	// Check the result
	if ($result == 0) {
	
		// The database failed while updating the session id
		echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>Database failed. Please try again.</msg></FlashBlog>";
	
	} else {
	
		// All is OK, the user has now logged in
		echo "<?xml version=\"1.0\"?><FlashBlog><status>true</status><msg>You've successfully logged in.</msg><session_id>$sessionID</session_id></FlashBlog>";
	
	}

} else {

	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>The password was incorrect. Please try again.</msg></FlashBlog>";

}


?>
