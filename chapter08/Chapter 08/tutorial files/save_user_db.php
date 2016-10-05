<html>

<?php

include("../includes/db_details.php");

?>
   <head>

      <title>Populating the database</title>
   </head>
   <body>

<?php

$error = false;
$errorMsg = "";

if (!isset($user_name) && $user_name == "") {

    $error = true;
    $errorMsg .= "No username was supplied<br>\n";

} else if (!isset($pass_word) && $pass_word == "") {

    $error = true;
    $errorMsg .= "No password was supplied<br>\n";

} else if (!isset($real_name) && $real_name == "") {

    $error = true;
    $errorMsg .= "A real name was not supplied<br>\n";

} else if (!isset($email) && $email == "") {

    $error = true;
    $errorMsg .= "An email address was not supplied<br>\n";

}

if ($error == true) {

    echo "An error has occurred:<br>\n $errorMsg";
    exit;

}

$mysql_connection = @mysql_connect($hostname, $username, $password)
           or exit("Failed to connect to the MySQL server.<br>\n");

if (!mysql_select_db($database, $mysql_connection)) {

    echo "Failed to connect to database: <i>$database</i>.<br>\n";
    echo "Error information: " . mysql_errno($mysql_connection) . " " . 
	mysql_error($mysql_connection) . "<br>\n";
    exit;
}

$add_user = "INSERT INTO users (username, userpass, realname, email)
	VALUES ('$user_name', '$pass_word', '$real_name', '$email')";

if (!$result=mysql_query($add_user, $mysql_connection)) {
    echo "An error occurred while adding the user: <i>$user_name</i>.<br>\n";
    echo "Error information: " . mysql_errno($mysql_connection) . " " .
        mysql_error($mysql_connection) . "<br>\n";
    exit;
}

echo "The user <i>$user_name</i> was successfully added to the database.<br>\n";

?>

   </body>
</html>
