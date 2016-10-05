<?

// Open the XML file
$file = fopen("fake_message_data.xml", "r");

if (!$file) {

	echo "<?xml version=\"1.0\"?><FlashBlog><error>Couldn't open fake_message_data.xml.</error></FlashBlog>";
	exit;

}

$xmlDoc = "";

while ($data = fread($file, 4096)) {

	$xmlDoc .= $data;

}

if ($xmlDoc == "") {

	echo "<?xml version=\"1.0\"?><FlashBlog><error>There are currently no posts.</error></FlashBlog>";

} else {

	echo $xmlDoc;

}

?>