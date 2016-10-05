<?

// Define the details of the database

$db_user = "root";
$db_password = "";
$db_name = "flashblog";
$db_host = "localhost";

// Connect to the mysql server, if an error occurs, return an error message and exit the script
if(!($db = @mysql_connect($db_host, $db_user, $db_password))) {

	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>An error occured while connecting to the MySQL server.</msg><action>database_mysql_server</action></FlashBlog>";
	exit;

}

// Select the database, if an error occurs return an error message and exit the script
if(!@mysql_select_db($db_name, $db)) {

	echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>An error occurred while selecting the databse.</msg><action>database_connecting</action></FlashBlog>";
	exit;

}

?>