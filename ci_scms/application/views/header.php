<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="hu-HU">
  <head>
    <meta charset="UTF-8">
	<link rel="stylesheet" href="<?=base_url("css/base.css") ?>">
	<link rel="shortcut icon" type="image/png" href="<?=base_url("favico.png") ?>"/>
	<?php if ($type == "gallery"): ?>
	<script src="<?=base_url("js/jquery.js"); ?>"></script>
	<script src="<?=base_url("js/gallery.js"); ?>"></script>
	<?php endif; ?>
    <title></title>
  </head>
  <body>
  <div class="banner">
    <img src="<?=base_url("img/banner.jpg"); ?>"></img>
  </div>