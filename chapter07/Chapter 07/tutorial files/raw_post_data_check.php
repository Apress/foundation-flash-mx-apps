<?

$value = ini_set("always_populate_raw_post_data", true);

if ($value == 0) {

	echo "The always_populate_raw_post_data directive is <b><i>off</i></b> by default.<br>\n";

} else if ($value == false) {

	echo "Failed to set the always_populate_raw_post_data directive.<br>\n Unable to determine default settings.<br>\n";
	echo "Please look beflow for more information on PHP configuration:<br>&nbsp;<br>\n\n";
	phpinfo();

} else {

	echo "The always_populate_raw_post_data directive is <b><i>on</i></b> by default.<br>\n";

}

?>