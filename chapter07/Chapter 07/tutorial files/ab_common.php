<?
	extract($_GET);
	extract($_POST);


   $currentTag = "";
   $contacts = array();
   $personName = "";
   $personNameValue = "";
   $phone = "";
   $phoneValue = "";
   $email = "";
   $emailValue = "";

   function startElementHandler($parser, $name, $attr) {

      global $currentTag;
      $currentTag = $name;

   }

   function charDataHandler($parser, $data) {

      global $currentTag, $personNameValue, $phoneValue, $emailValue;

      switch($currentTag) {
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


   function endElementHandler($parser, $name) {
      global $currentTag, $contacts, $personNameValue, $phoneValue, $emailValue, $personName, $phone, $email;


      switch($name) {
         case "NAME":
         $personName = $personNameValue;
         $personNameValue = "";
         break;

         case "PHONE":
         $phone = $phoneValue;
         $phoneValue = "";
         break;

         case "EMAIL":
         $email = $emailValue;
         $emailValue = "";
         break;

         case "PERSON":
         $contacts[] = array("name"=>$personName, "phone"=>$phone, "email"=>$email);
         break;

      }


   }


   function loadContacts() {
      // Create the XML parser
      $parser = xml_parser_create();


      // Register the start and end element handlers
      xml_set_element_handler($parser, "startElementHandler", "endElementHandler");

      // Register the character data parser
      xml_set_character_data_handler($parser, "charDataHandler");


      // Open the XML file
      $file = @fopen("xml_document.xml", "r");

      if (!$file) {
         echo "Could not open xml_document.xml.<br>\n";
         exit;
      }

      while ($data = fread($file, 4096)) {
         if (!xml_parse($parser, $data, feof($file))) {    	
            echo "There was an error in the XML document<br>\n";
            echo " line: " . xml_get_current_line_number($parser) . "<br>\n";
            echo " position: " . xml_get_current_column_number($parser) . "<br>\n";
            exit;
         }
      }

      // OK, free the XML parser
      xml_parser_free($parser);

   }

   function saveContacts() {
      global $contacts;

      $xmlStr = "<?xml version=\"1.0\"?>\n<addressBook>\n";

      for ($i = 0; $i < count($contacts); $i++) {

         $xmlStr .= "\t<person>\n";

         $xmlStr .= "\t\t<name>" . $contacts[$i]['name'] . "</name>\n";
         $xmlStr .= "\t\t<phone>" . $contacts[$i]['phone'] . "</phone>\n";
         $xmlStr .= "\t\t<email>" . $contacts[$i]['email'] . "</email>\n";

         $xmlStr .= "\t</person>\n";

      }

      $xmlStr .= "</addressBook>";

      $file = @fopen("xml_document.xml", "w");

      if(!$file) {

         print "Unable to open xml_document.xml for writing<br>\n";

      } else {

         fwrite($file, $xmlStr);
         fclose($file);
         echo "Contact details successfully saved.<br>\n";

      }

   }

?>
