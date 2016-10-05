<html>
<head>
<title>Creating a user table</title>
</head>

<body>

<form action="save_user_db.php" method="post" name="user_details">

<b>username*</b><br>
<input type="text" name="user_name" maxlength="16"><br>

<b>password*</b><br>
<input type="password" name="pass_word" maxlength="16"><br>

<b>real name*</b><br>
<input type="text" name="real_name" maxlength="200"><br>

<b>email*</b><br>
<input type="text" name="email" maxlength="200"><br>

<input type="submit" value="Add User">

</form>

</body>
</html>
