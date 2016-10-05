<html>
    <head>
         <title>PHP Access logger</title>
    </head>
    <body>
	<?php

	            $str = $_SERVER["REMOTE_ADDR"] . " - [" . date("d.M.Y H:i:s ") . "] - " . $_SERVER['REQUEST_METHOD'] . " - " . $_SERVER['SERVER_PROTOCOL'] . " - " . $_SERVER['HTTP_USER_AGENT'] . "<br>\n";
 
	if (!$fp = fopen("access.log", "a")) {
		exit("Failed to open access.log.<br>\n");
	}

	if (!fwrite($fp, $str)) {
                exit("Failed to write to file.<br>\n");
        }

        fclose($fp);
        echo "Accessed logged.<br>\n";


       ?>

    </body>
</html>
