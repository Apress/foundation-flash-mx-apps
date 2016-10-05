
<html>
<head>
<title>Address Book</title>
</head>
<body>

<?


// Open the XML file
$file = fopen("xml_document.xml", "r");

if (!$file) {

	echo "Could not open xml_document.xml.<br>\n";
	exit;

}


$xmlDoc = "";

while ($data = fread($file, 4096)) {

	$xmlDoc .= $data;

}



if ($xmlDoc == "") {

	echo "Failed to load the XML doc.";

} else {

	echo "The XML document has completely loaded:<br>&nbsp;<br>\n\n <pre>" . htmlentities($xmlDoc) . "</pre>";

}

?>

</body>
</html>
