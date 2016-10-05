<?
	extract($_GET);
	extract($_POST);

   include("ab_common.php");
?>

<html>
   <head>
      <title>Address Book - Edit Contact</title>
   </head>
   <body>
      <h2>Edit Contact</h2>
      <?
         loadContacts();


         if ($contact_id < count($contacts)) {
        
            echo "<form action=\"ab_save.php\" method=\"POST\">\n";


            echo "<input type=\"hidden\" name=\"contact_id\" value=\"$contact_id\">\n";
            echo "Name: <input type=\"text\" name=\"newName\" value=\"" . $contacts[$contact_id]['name'] . "\"><br>\n";

            echo "Phone: <input type=\"text\" name=\"newPhone\" value=\"" . $contacts[$contact_id]['phone'] . "\"><br>\n";
            echo "Email: <input type=\"text\" name=\"newEmail\" value=\"" . $contacts[$contact_id]['email'] . "\"><br><br>\n";

            echo "<input type=\"submit\" value=\"Save Changes\"><br>\n";

            echo "</form>\n";

         } else {

            echo "This is not a valid contact ID.<br>\n";        

         }

      ?>

      <a href="ab_index.php">View Contacts</a>

   </body>
</html>
