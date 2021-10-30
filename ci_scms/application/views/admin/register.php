<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html lang="hu-HU">
<html>
  <head>
    <meta charset="UTF-8">
		<link rel="stylesheet" href="<?=base_url("css/admin.css") ?>">
    <title>Admin</title>
  </head>
  <body>
    <div class="topspacer"></div>
    <div class="regspacer"></div>
	<div class="registerbox">
	Regisztráció: <br><br>
    <form action="<?=site_url("admin/doregister"); ?>" method="POST">
	  Felhasználónév (legalább 4 karakter):<br>
      <input type="text" name="user"><br>
	  Jelszó (legalább 5 karakter):<br>
      <input type="password" name="pass"><br>
	  Jelszó megint:<br>
      <input type="password" name="pass2"><br>
	  e-mail cím:<br>
	  <input type="text" name="email"><br><br>
	  <input type="submit" value="Küld">
	</form>
	</div>
	<div class="fc"></div>
  </body>
</html>