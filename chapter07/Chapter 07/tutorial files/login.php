<?

   ini_set("always_populate_raw_post_data", true);

   $currentTag = "";
   $user_username = "";
   $usernameValue = "";
   $user_password = "";
   $passwordValue = "";

   // The handler for the element opening tags
   function startElementHandler($parser, $name, $attr) {

      global $currentTag, $user_username, $user_password;
      $currentTag = $name;

      if (strcmp($name, "LOGIN") == 0) {

         $user_username = $attr["USERNAME"];
         $user_password = $attr["PASSWORD"];
      }
   }

   function endElementHandler() {
      // do nothing
   }

   // Define the arrays
   $username = array();
   $password = array();

   // Populate the arrays
   $username[] = "scott";
   $password[] = "testpassword123";

   $username[] = "user";
   $password[] = "pass";

   // Create the XML parser
   $parser = xml_parser_create();

   // Register the start and end element handlers
   xml_set_element_handler($parser, "startElementHandler", "endElementHandler");

   if (!xml_parse($parser, $HTTP_RAW_POST_DATA, true)) {

      exit ("There was an error in the XML in line " . xml_get_current_line_number($parser) . " at position " . xml_get_current_column_number($parser));

   }

   // OK, finished parsing the XML
   xml_parser_free($parser);

   $found = false; 
   for ($i = 0; $i < count($username); $i++) {
      if ($username[$i] == $user_username && $password[$i] == $user_password) {
         $found = true ;
      }
   }

   if ($found == true) {

      echo "<?xml version=\"1.0\"?><login status=\"success\" msg=\"You've successfully logged in.\" />";

   } else {
      echo "<?xml version=\"1.0\"?><login status=\"failed\" msg=\"The supplied username or password was wrong.\" />";

   }

?>
