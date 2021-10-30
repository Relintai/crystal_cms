<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$url = "";

if ($mode == "add") {
	$url = site_url("admin/addtext/" . $pageid);
}

if ($mode == "edit") {
	$url = site_url("admin/edittext/" . $contentid);
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" href="<?=base_url("css/admin.css") ?>">
	<?php if ($mode == "edit"): ?>
    <title>Szöveg Szerkesztése</title>
	<?php endif; ?>
	<?php if ($mode == "add"): ?>
    <title>Szöveg hozzáadása</title>
	<?php endif; ?>
</head>
<body>
  <div class="texteditbox">
	<form action="<?=$url; ?>" method="post">
<textarea name="message" rows="30" cols="120">
<?php if ($mode == "edit"): ?>
<?=$text; ?>
<?php endif; ?>
</textarea>
		<br><br>
		<input type="submit" value="Küld">
	</form>
	</div>
</body>
</html>