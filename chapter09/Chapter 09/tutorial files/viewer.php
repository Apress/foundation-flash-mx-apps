<html>
    <head>
         <title>PHP Access Log Reader</title>
    </head>
    <body>
	
	<?php
	
	if (!$fp = fopen("access.log", "r")) {
		exit("Failed to open access.log.<br>\n");
        }
	
	fpassthru($fp);
        
	?>

    </body>
</html>
