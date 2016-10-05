<html>
   <head> 
      <title>General PHP Exercise - Foundation Flash MX Applications</title>
   </head>
   <body>

      	<?php
      	// echo Hello World to the browser
      	echo "Hello World!<br />\n";
      
	// If statements
	if (isset($name)) {
		echo "My name is $name.<br />\n";

		// Comparison operator
		if ($name == "Scott") {
			echo "Would you like to search @ <a href=\http://www.google.com\target=\"_blank\">google</a ?<br/>\n";		
		}
		
		// The for loop in PHP
         	if (isset($age) && $age > 18) {
            		echo "My age is $age.<br />\n";
            		echo "<br />\n";
            		for ($count = 0; $count < $age; $count++) {
               		echo "Hello $name!<br />\n";
            	}

         } else {
           	
		echo "<br />\n";
		echo "$name, you are not old enough to visit this page. Please come back in " . (18-$age) . " years.";
         } 

     }
     ?>

</body/>
</html>