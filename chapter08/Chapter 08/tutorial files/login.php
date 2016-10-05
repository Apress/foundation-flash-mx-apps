<?

ini_set("always_populate_raw_post_data", true);
include("../includes/db_details.php");

$currentTag = "";
$user_username = "";
$usernameValue = "";
$user_password = "";
$passwordValue = "";

// The handler for the element opening tags
function startElementHandler($parser, $name, $attr) {

	global $currentTag, $user_username, $user_password;

	$currentTag = $name;
   
	if (strcmp($name, "LOGIN") == 0) {
	
		$user_username = $attr["USERNAME"];
		$user_password = $attr["PASSWORD"];
	
	}

}

function endElementHandler() {

	// do nothing

}

// Create the XML parser
$parser = xml_parser_create();

// Register the start and end element handlers
xml_set_element_handler($parser, "startElementHandler", "endElementHandler");

if (!xml_parse($parser, $HTTP_RAW_POST_DATA, true)) {
	
	echo "<?xml version=\"1.0\"?><login status=\"false\" msg=\"There was an error in the XML in line " . xml_get_current_line_number($parser) . " at position " . xml_get_current_column_number($parser) . "\" />";
	exit();
	
}

// Ok, finished parsing the XML
xml_parser_free($parser);

$mysql_connection = mysql_connect($hostname, $username, $password);

if (!$mysql_connection) {

	exit("<?xml version=\"1.0\"?><login status=\"false\" msg=\"A failure occured while connecting to the MySQL server.\" />");

} else {

	if (!mysql_select_db($database, $mysql_connection)) {
	
		$err = "An error occured while connecting to the database: " . mysql_errno($mysql_connection) . " " . mysql_error($mysql_connection);
	
		exit("<?xml version=\"1.0\"?><login status=\"false\" msg=\"$err\" />");
	
	} else {
	
		$add_user = "SELECT * FROM users";
		
		$result = mysql_query($add_user, $mysql_connection);
	
		if (!$result) {
			
			$err = "An error occured while querying the database: " . mysql_errno($mysql_connection) . " " . mysql_error($mysql_connection);
			exit("<?xml version=\"1.0\"?><login status=\"false\" msg=\"$err\" />");
		
		} else {
		
			$numRows = mysql_num_rows($result);
			
			for ($i = 0; $i < $numRows; $i++) {
			
				$row = mysql_fetch_array($result);
			
				if ($row["username"] == $user_username && $row["userpass"] == $user_password) {
				
					exit("<?xml version=\"1.0\"?><login status=\"success\" msg=\"You've successfully logged in.\" />");
				
				}
			
			}
			
			exit("<?xml version=\"1.0\"?><login status=\"false\" msg=\"Their username and/or password was incorrect.\" />");
		
		}
	
	}
	
}

?>