<?

$currentTag = "";
$contacts = array();
$personName = "";
$personNameValue = "";
$phone = "";
$phoneValue = "";
$email = "";
$emailValue = "";

// The handler for the element opening tags
function startElementHandler($parser, $name, $attr) {

	global $currentTag;

	$currentTag = $name;
 
}

// The handler for character data

function cDataHandler($parser, $data) {
	
	global $currentTag, $contacts, $personNameValue, $phoneValue, $emailValue, $emailArray;		

switch ($currentTag) {
    case "NAME":
        $personNameValue .= $data;
        break;

    case "PHONE":
        $phoneValue .= $data;
        break;

        case "EMAIL":
        $emailValue .= $data;
        break;

	}
} 


// The handler for the element closing tags
function endElementHandler($parser, $name) {
	
	global $personName, $contacts, $personNameValue, $phone, $phoneValue, $email, $emailValue;
 
  	if (strcmp($name, "NAME") == 0) {
	
		$personName = $personNameValue;
		$personNameValue = "";
		
	} else if (strcmp($name, "PHONE") == 0) {

		$phone = $phoneValue;
		$phoneValue = "";

	} else if (strcmp($name, "EMAIL") == 0) {
	
		$email = $emailValue;		
		$emailValue = "";

	} else if (strcmp($name, "PERSON") == 0) {
	
		$contacts[] = array("name"=>$personName,"phone"=>$phone,"email"=>$email);
	
	}

}

?>


<html>
<head>
<title>Address Book</title>
</head>
<body>

<?

// Create the XML parser
$parser = xml_parser_create();

// Register the start and end element handlers
xml_set_element_handler($parser, "startElementHandler", "endElementHandler");

// Register the character data parser
xml_set_character_data_handler($parser, "cDataHandler");


// Open the XML file
$file = fopen("xml_document.xml", "r");

if (!$file) {

	echo "Could not open xml_document.xml.<br>\n";
	exit;

}



while ($data = fread($file, 4096)) {

	if (!xml_parse($parser, $data, feof($file))) {
	
		exit("There was an error in the XML in line " . xml_get_current_line_number($parser) . 		" at position " . xml_get_current_column_number($parser) . ".<br>&nbsp;<br>\n\n");
	
	}


}

// OK, finished parsing the XML
xml_parser_free($parser);

if (count($contacts) > 0) {

	echo "<hr size=\"1\">\n";

	for ($i = 0; $i < count($contacts); $i++) {
	
		echo "Name: " . $contacts[$i]["name"] . "<br>\n";
		echo "Phone: " . $contacts[$i]["phone"] . "<br>\n";
		echo "Email: <a href=\"mailto:" . $contacts[$i]["email"] . "\">" . $contacts[$i]["email"] . "</a><br>\n";
		echo "<hr size=\"1\">\n";
	
	}

} else {

	echo "There are no contacts in your contacts book.<br>\n";

}

echo "<a href=\"ab_index.php\">View Contacts</a> | <a href=\"ab_edit.php?numContacts=10\">Edit Contacts</a><br>\n";


?>

</body>
</html>
