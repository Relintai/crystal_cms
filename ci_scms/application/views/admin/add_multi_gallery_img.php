<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$url = "";

if ($mode == "add") {
	$url = site_url("admin/addmultigalleryimage/" . $id . "/" . $folderid);
}

if ($mode == "edit") {
	$url = site_url("admin/editmultigalleryimage/" . $id . "/" . $folderid);
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" href="<?=base_url("css/admin.css") ?>">
	<?php if ($mode == "edit"): ?>
    <title>Kép hozzáadása</title>
	<?php endif; ?>
	<?php if ($mode == "add"): ?>
    <title>Kép hozzáadása</title>
	<?php endif; ?>
</head>
<body>
<div class="topspacer"></div>
<div class="imgaddspacer"></div>
<div class="imgaddbox">
	<?php echo form_open_multipart($url); ?>
		<?php if ($mode == "edit"): ?>
    Kép szerkesztése:
	<?php endif; ?>
	<?php if ($mode == "add"): ?>
    Kép hozzáadása:
	<?php endif; ?><br><br>
Kép neve (Nem kötelező):<br>
<input name="name">
<?php if ($mode == "edit"): ?>
<?php endif; ?>
		<br>
Leírás: (Nem kötelező):<br>
<textarea name="description" rows="10" cols="60">
<?php if ($mode == "edit"): ?>
<?php endif; ?>
</textarea><br><br>
<input type="file" name="img" id="img" accept="image/jpeg, image/png, image/gif"><br><br>
		<input type="submit" value="Küld">
	</form>
		</div>
<div class="fc"></div>
</body>
</html>