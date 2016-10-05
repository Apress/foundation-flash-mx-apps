<?

// Define the details of the database
$db_user = "root";
$db_password = "";
$db_name = "flashblog";
$db_host = "localhost";

// Connect to the mysql server, if an error occurs, return an error message and exit the script
if(!$db=@mysql_connect($db_host, $db_user, $db_password)) {
	exit("An error occured while connecting to the MySQL server.<br>\n");
}

// Select the database, if an error occurs return an error message and exit the script
if(!@mysql_select_db($db_name, $db)) {
	exit("Unable to select the database <i>$db_name</i>.<br>\nError #" . mysql_errno($db) . ": " . mysql_error($db) . "<br>\n");
}

$result = mysql_query("DROP DATABASE $db_name");

if (!$result) {

	exit("Unable to DROP <i>$db_name</i>.<br>\nError #" . mysql_errno($db) . ": " . mysql_error($db) . "<br>\n");

}

echo "The database <i>$db_name</i> has been dropped.<br>\n";

?>