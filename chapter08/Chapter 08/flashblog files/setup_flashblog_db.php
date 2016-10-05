<?

// Define the details of the database
$db_user = "root";		// username
$db_password = "";		// password
$db_name = "flashblog";		// database name
$db_host = "localhost";		// host name

// Connect to the mysql server, if an error occurs, return an error message and exit the script
if(!$db=@mysql_connect($db_host, $db_user, $db_password)) {
	exit("An error occured while connecting to the MySQL server.<br>\n");
}

// Select the database, if an error occurs return an error message and exit the script
if(!mysql_select_db($db_name, $db)) {
	echo "Unable to select the database <i>$db_name</i>.<br>\n I will attempt to create it.<br>&nbsp;<br>\n\n";
}

// Create the database
if (!$result=@mysql_query("CREATE DATABASE $db_name", $db)) {

	exit("Unable to create database <i>$db_name</i>.<br>\nError #" . mysql_errno($db) . ": " . mysql_error($db) . "<br>\n");

}

echo "The database <i>$db_name</i> has been created.<br>\n I will attempt to create the necessary tables.<br>&nbsp;<br>\n\n";

// Select the database, if an error occurs return an error message and exit the script
if(!mysql_select_db($db_name, $db)) {
	exit("Unable to select the database <i>$db_name</i><br>\n.");
}

echo "Selected database <i>$db_name</i>.<br>&nbsp;<br>\n\n";

// The SQL statement to create the fb_posts table
$create_fbposts = "CREATE TABLE fb_posts (
					  id INT(4) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
					  user_id INT(4),
					  subject VARCHAR(200),
					  body TEXT,
					  timestamp VARCHAR(24))";

if (!$result=@mysql_query($create_fbposts, $db)) {

	echo "Unable to create the table <i>fb_posts</i><br>\nError #" . mysql_errno($db) . ": " . mysql_error($db) . "<br>\nAttempting to create the next table<br>&nbsp;<br>\n\n";

} else {

	echo "Successfully created the <i>fb_posts</i> table.<br>\n";
	
}

$create_fbuser = "CREATE TABLE fb_user (
					  id int(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					  username varchar(12),
					  password varchar(12),
					  first_name varchar(12) NOT NULL default 'Firstname',
					  last_name varchar(26) NOT NULL default 'Lastname',
					  user_type varchar(13) NOT NULL default 'user',
					  session_id varchar(32) NOT NULL default '0')";
					  
if (!$result=@mysql_query($create_fbuser, $db)) {

	echo "Unable to create the table <i>fb_user</i><br>\nError #" . mysql_errno($db) . ": " . mysql_error($db) . "<br>\n";

} else {

	echo "Successfully created the <i>fb_user</i> table.<br>\n";
	
}

?>