<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$url = "";

if ($mode == "add") {
	$url = site_url("admin/addmultigallery/" . $pageid);
}

if ($mode == "edit") {
	$url = site_url("admin/editmultigallery/" . $contentid);
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" href="<?=base_url("css/admin.css") ?>">
	<?php if ($mode == "edit"): ?>
    <title>Galéria Szerkesztése</title>
	<?php endif; ?>
	<?php if ($mode == "add"): ?>
    <title>Galéria hozzáadása</title>
	<?php endif; ?>
</head>
<body>
    <div class="topspacer"></div>
    <div class="galleryspacer"></div>
<div class="addgallerybox">	<?php if ($mode == "edit"): ?>
    Galéria Szerkesztése
	<?php endif; ?>
	<?php if ($mode == "add"): ?>
    Galéria hozzáadása
	<?php endif; ?><br><br>
	<form action="<?=$url; ?>" method="post">
Galéria neve (Nem kötelező):<br>
<input name="name">
<?php if ($mode == "edit"): ?>
<?php endif; ?>
		<br>
Leírás: (Nem kötelező):<br>
<textarea name="description" rows="10" cols="60">
<?php if ($mode == "edit"): ?>
<?php endif; ?>
</textarea><br><br>
		<input type="submit" value="Küld">
	</form>
	</div>
<div class="fc"></div>
</body>
</html>