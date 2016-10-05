<?php

include("../includes/db_details.php");

?>

<html>
   <head>
      <title>Creating a MySQL Database</title>
   </head>
   <body>

<?php

$mysql_connection = @mysql_connect($hostname, $username, $password) 
	or exit("Failed to connect to the MySQL server.<br>\n");

if (!mysql_select_db($database, $mysql_connection)) {

    echo "Failed to connect to database: <i>$database</i>.<br>\n";
    echo "Error information: " . mysql_errno($mysql_connection) . " " . mysql_error($mysql_connection) . "<br>\n";
    exit;
}

$create_table = "CREATE TABLE users (
      uid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(16),
            userpass VARCHAR(16),
            realname VARCHAR(200),
            email VARCHAR(200))";

if (!$result=mysql_query($create_table, $mysql_connection)) {

    echo "An error occured while creating the <i>users</i> table.<br>\n";
    echo "Error information: " . mysql_errno($mysql_connection) . " " . 
		mysql_error($mysql_connection) . "<br>\n";
    exit;
}

echo "The <i>users</i> table on <i>$database</i> has successfully been created.<br>\n";

?>

   </body>
</html>
