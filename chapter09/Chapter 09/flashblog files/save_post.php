<?php

    ini_set("always_populate_raw_post_data", true);
    // Include the database details, and make a connection
    include("../../fb_includes/database.php");

    $currentTag = "";
    $username = "";
    $usernameValue = "";
    $sessionID = "";
    $sessionIDValue = "";
    $blogLimit = "";
    $blogLimitValue = "";
    $time = "";
    $timeValue = "";
    $subject = "";
    $subjectValue = "";
    $body = "";
    $bodyValue = "";
  
    // The handler for the element opening tags
    function startElementHandler($parser, $name, $attr) {

        global $currentTag;
        $currentTag = $name;
    }

    // The handler for character data
    function cdataHandler($parser, $data) {

        global $currentTag, $usernameValue, $blogLimitValue, $timeValue, $subjectValue, $bodyValue, $sessionIDValue;

    if (strcmp($currentTag, "USERNAME") == 0) {

        $usernameValue .= $data;

    } else if (strcmp($currentTag, "SESSION_ID") == 0) {

        $sessionIDValue .= $data;

    } else if (strcmp($currentTag, "BLOGLIMIT") == 0) {

        $blogLimitValue .= $data;

    } else if (strcmp($currentTag, "TIME") == 0) {

        $timeValue .= $data;

    } else if (strcmp($currentTag, "SUBJECT") == 0) {

        $subjectValue .= $data;

    } else if (strcmp($currentTag, "BODY") == 0) {

        $bodyValue .= $data;
    }
}

// The handler for the element closing tags
function endElementHandler($parser, $name) {

    global $username, $usernameValue, $blogLimit, $blogLimitValue, $time, $timeValue, $subject, $subjectValue, $body, $bodyValue, $sessionID, $sessionIDValue;

    if (strcmp($name, "USERNAME") == 0) {

        $username = $usernameValue;
        $usernameValue = "";

    } else if (strcmp($name, "SESSION_ID") == 0) {

        $sessionID = $sessionIDValue;
        $sessionIDValue = "";

    } else if (strcmp($name, "BLOGLIMIT") == 0) {

        $blogLimit = $blogLimitValue;
        $blogLimitValue = "";

    } else if (strcmp($name, "TIME") == 0) {

        $time = $timeValue;
        $timeValue = "";

    } else if (strcmp($name, "SUBJECT") == 0) {

        $subject = $subjectValue;
        $subjectValue = "";

    } else if (strcmp($name, "BODY") == 0) {

        $body = $bodyValue;
        $bodyValue = "";

    }
}

// Create the XML parser
$parser = xml_parser_create();

// Register the start and end element handlers
xml_set_element_handler($parser, "startElementHandler", "endElementHandler");

// Register the character data parser
xml_set_character_data_handler($parser, "cdataHandler");

if (!xml_parse($parser, $HTTP_RAW_POST_DATA, true)) {

    $err = "There was an error in the XML in line " . xml_get_current_line_number($configParser) . " at position " . xml_get_current_column_number($configParser);
    echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>$str</msg></FlashBlog>";
    exit;

}

// Ok, finished parsing the POST XML
xml_parser_free($parser);

// Now load and parse the config.xml file
$configParser = xml_parser_create();
xml_set_element_handler($configParser, "startElementHandler", "endElementHandler");
xml_set_character_data_handler($configParser, "cdataHandler");

if (!$fp = @fopen("XML/config.xml", "r")) {

    echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>Failed to open the configuration file.</msg></FlashBlog>";
    exit;

}

while ($data = fread($fp, 4096)) {

    if (!xml_parse($configParser, $data, feof($fp))) {

        $err = "There was an error in the XML in line " . xml_get_current_line_number($configParser) . " at position " . xml_get_current_column_number($configParser);

        echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>$err</msg></FlashBlog>";
        exit;

    }
}

if (!isset($username) || $username == "") {

    echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>The user is not logged in.</msg></FlashBlog>";
    exit;

} else if (!isset($time) || $time == "") {

    echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>The time was missing.</msg></FlashBlog>";
    exit;

} else if (!isset($subject) || $subject == "") {

    echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>The subject was missing.</msg></FlashBlog>";
    exit;

} else if (!isset($body) || $body == "") {

    echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>The body was missing.</msg></FlashBlog>";
    exit;

} else if (!isset($blogLimit) || $blogLimit == "") {

    echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>There was an error in the configuration directives.</msg></FlashBlog>";
    exit;

}

$stmt = "INSERT INTO fb_posts (user_id, subject, body, timestamp) VALUES(1, '" . urldecode($subject) . "', '" . urldecode($body) . "', '$time')";

$result = @mysql_query($stmt, $db);

if ($result == 0) {

    $str = echo "Error #" . mysql_errno($db) . ": " . mysql_error($db);
    echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>Database update failed: " . $str . "</msg></FlashBlog>";
    exit;

}

$stmt = "SELECT id FROM fb_posts ORDER BY id DESC LIMIT $blogLimit";
$result = @mysql_query($stmt, $db);

if ($result == 0) {

    $str = echo "Error #" . mysql_errno($db) . ": " . mysql_error($db);
    echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>Entry was added, but the database failed while deleting old posts.</msg></FlashBlog>";
    exit;

}

$numRows = @mysql_num_rows($result);

for ($i = 0; $i < $numRows; $i++) {

    $row = @mysql_fetch_array($result);
    $lastID = $row['id'];
			
}

$stmt = "DELETE FROM fb_posts WHERE id < $lastID";
$result = @mysql_query($stmt, $db);

if ($result == 0) {

    echo "<?xml version=\"1.0\"?><FlashBlog><status>false</status><msg>Entry was added, but the database failed while deleting old posts.</msg></FlashBlog>";
    exit;

}

echo "<?xml version=\"1.0\"?><FlashBlog><status>true</status><msg>Your post was saved to the database.</msg></FlashBlog>";


?>