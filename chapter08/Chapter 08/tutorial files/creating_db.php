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

   if (!mysql_create_db($database, $mysql_connection)) {

    echo "Failed to create the new database <i>$database</i>.<br>\n";
    echo "Error #" . mysql_errno($mysql_connection) . ": " . mysql_error($mysql_connection);

} else {

    echo "Successfully created the database <i>$database</i>.<br>\n";

} 

?>

</body>
</html>
