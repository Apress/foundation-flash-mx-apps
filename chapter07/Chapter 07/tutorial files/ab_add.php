<?
	extract($_GET);
	extract($_POST);

   include("ab_common.php");

?>

<html>
   <head>
      <title>Address Book - Add Contact</title>
   </head>
   <body>
      <h2>Add Contact</h2>
      <?

         if(isset($newName)) {

           loadContacts();
           $contacts[] = array("name"=>$newName, "phone"=>$newPhone, "email"=>$newEmail);
           saveContacts();

         } else {

            echo "<form action=\"ab_add.php\" method=\"POST\">\n";
            echo "Name: <input type=\"text\" name=\"newName\"><br>\n";
            echo "Phone: <input type=\"text\" name=\"newPhone\"><br>\n";
            echo "Email: <input type=\"text\" name=\"newEmail\"><br><br>\n";
            echo "<input type=\"submit\" value=\"Add Contact\"><br>\n";
            echo "</form>\n";

         }

      ?>

      <a href="ab_index.php">View Contacts</a>

      <a href="ab_add.php">Add New Contact</a>

   </body>
</html>
