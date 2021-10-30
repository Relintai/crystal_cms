<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="hu-HU">
<html>
  <head>
    <meta charset="UTF-8">
	<link rel="stylesheet" href="<?=base_url("css/admin.css") ?>">
    <title>Admin</title>
  </head>
  <body>
    <div class="topspacer"></div>
    <div class="spacer"></div>
    <div class="loginbox">
	Belépés:<br><br>
    <form action="<?=site_url("admin/dologin"); ?>" method="POST">
	  Felhasználónév:<br>
      <input type="text" name="user"><br>
	  Jelszó:<br>
      <input type="password" name="pass"><br><br>
	  <input type="submit" value="Küld"><br>
	</form>
	<?php if ($reg_enabled): ?>
	<a href="<?=site_url("admin/register"); ?>">Regisztráció</a>
	<?php endif; ?>
	</div>
	<div class="fc"></div>
  </body>
</html>