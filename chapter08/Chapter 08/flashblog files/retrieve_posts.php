<?php

ini_set("always_populate_raw_post_data", true);

// Include the database details, and make a connection
include("../../fb_includes/database.php");

$stmt = "SELECT subject, body, timestamp, first_name, last_name FROM fb_posts, fb_user WHERE fb_user.id = fb_posts.user_id ORDER BY fb_posts.id ASC";

// Get all the posts and necessary information
$result = @mysql_query($stmt, $db);

// Did we get a result?
if (!$result) {

	echo "<?xml version=\"1.0\"?><FlashBlog><error>SQL query failed. Please try again.</error></FlashBlog>";
	exit;

}

// Find the number of rows returned (i.e. the number of posts)
$numRows = @mysql_num_rows($result);

// Were there any rows?
if ($numRows <= 0) {

	// No rows were returned, there mustn't be any entries
	echo "<?xml version=\"1.0\"?><FlashBlog><error>There are currently no entries in the database.</error></FlashBlog>";
	exit;

}
	
// Open the XML document
$XMLString = "<?xml version=\"1.0\"?><FlashBlog>";

// Loop through each row and create the <message> node with all the correct information
for ($i = 0; $i < $numRows; $i++) {

	$row = @mysql_fetch_array($result);

	$XMLString .= "<message id=\"" . ($i+1) . "\"><author><firstName>" . $row['first_name'] . "</firstName><lastName>" . $row['last_name'] . "</lastName></author><time>" . $row['timestamp'] . "</time><subject>" . urlencode($row['subject']) . "</subject><body>" . urlencode($row['body']) . "</body></message>";

}

// Finish the XML document
$XMLString .= "</FlashBlog>";

// Return the XML to Flash
echo $XMLString;

?>