<?

	extract($_GET);
	extract($_POST);

	include("ab_common.php");
?>

<html>
   <head>
      <title>Address Book - Save Contact</title>
   </head>
   <body>
      <h2>Save Contact</h2>
      <?
         loadContacts();

         if ($contact_id < count($contacts)) {

            $contacts[$contact_id]['name'] = $newName;
            $contacts[$contact_id]['phone'] = $newPhone;
            $contacts[$contact_id]['email'] = $newEmail;

            saveContacts();

         } else {

            echo "This is not a valid contact ID.<br>\n";

         }


      ?>
      <a href="ab_index.php">Continue</a>
   </body>
</html>
