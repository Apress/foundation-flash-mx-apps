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
    echo "Error information: " . mysql_errno($mysql_connection) . " " . 	
	mysql_error($mysql_connection) . "<br>\n";
    exit;

}

$add_user = "SELECT * FROM users";

if (!$result = mysql_query($add_user, $mysql_connection)) {

    echo "An error occurred while retrieving user information.<br>\n";
    echo "Error information: " . mysql_errno($mysql_connection) . " " .
	mysql_error($mysql_connection) . "<br>\n";
    exit;

}

$numRows = mysql_num_rows($result);

for ($i = 0; $i < $numRows; $i++) {

    $row = mysql_fetch_array($result);
    echo "username: " . $row["username"] . " (" . $row["realname"] . ")<br>\n";
    echo "email: " . $row["email"] . "<br>\n";
    echo "<hr>";

}

?>

   </body>
</html>
