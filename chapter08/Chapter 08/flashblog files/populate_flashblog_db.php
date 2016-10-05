<?

// Define the details of the database
$db_user = "root";		// username
$db_password = "";		// password
$db_name = "flashblog";		// database name
$db_host = "localhost";		// host name

$users = array();

$users[] = array("username" => "admin",
				"password" => "admin",
				"first_name" => "default",
				"last_name" => "default",
				"user_type" => "administrator");
				
$users[] = array("username" => "user1",
				"password" => "user1",
				"first_name" => "default1",
				"last_name" => "user1",
				"user_type" => "user");

$users[] = array("username" => "user2",
				"password" => "user2",
				"first_name" => "default2",
				"last_name" => "user2",
				"user_type" => "user");

$posts = array();

// Enter the fake posts here
$posts[] = array("user_id" => "1",
				"subject" => "Subject here",
				"body" => "Body here",
				"timestamp" => "2002,11,21,0,00");

// Connect to the mysql server, if an error occurs, return an error message and exit the script
if(!$db=@mysql_connect($db_host, $db_user, $db_password)) {
	exit("An error occured while connecting to the MySQL server.<br>\n");
}

// Select the database, if an error occurs return an error message and exit the script
if(!mysql_select_db($db_name, $db)) {
	exit("Unable to select the database <i>$db_name</i>.<br>\n");
}

echo "Now populating the database with users:<br>&nbsp;<br>\n\n";

for ($i = 0; $i < count($users); $i++) {

	$stmt = "INSERT INTO fb_user (username, password, first_name, last_name, user_type) VALUES ('" . $users[$i]['username'] . "', '" . $users[$i]['password'] . "', '" . $users[$i]['first_name'] . "', '" . $users[$i]['last_name'] . "', '" . $users[$i]['user_type'] . "')";

	$result = mysql_query($stmt, $db);
	
	echo "$stmt<br>\n";

}

echo "<br>Now populating the database with posts:<br>&nbsp;<br>\n\n";

for ($i = 0; $i < count($posts); $i++) {

	$stmt = "INSERT INTO fb_posts (user_id, subject, body, timestamp) VALUES ('" . $posts[$i]['user_id'] . "', '" . $posts[$i]['subject'] . "', '" . $posts[$i]['body'] . "', '" . $posts[$i]['timestamp'] . "')";

	$result = mysql_query($stmt, $db);
	
	echo "$stmt<br>\n";

}

?>