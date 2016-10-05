<?
	extract($_GET);
	extract($_POST);


   include("ab_common.php");
?>


<html>
   <head>
      <title>Address Book - View Contacts</title>
   </head>
   <body>
      <h2>View Contacts</h2>

      <?
         loadContacts();
         if (count($contacts) > 0) {


            for ($i = 0; $i < count($contacts); $i++) {

               echo "Name: " . $contacts[$i]["name"] . "<br>\n";
               echo "Phone: " . $contacts[$i]["phone"] . "<br>\n";

               echo "Email: <a href=\"mailto:" . $contacts[$i]["email"] . "\">" . $contacts[$i]["email"] . "</a><br>\n";

            echo "<a href=\"ab_edit.php?contact_id=$i\">Edit</a><br>\n";


               echo "<hr size=\"1\">\n";



            }



         } else {
            echo "There are no contacts in the contacts book.<br>\n";
         }
      ?>


      <a href="ab_add.php">Add New Contact</a>


   </body>
</html>
