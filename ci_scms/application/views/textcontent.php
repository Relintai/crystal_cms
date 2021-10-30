<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<?php if ($is_admin): ?>
		<div class="info">
		<?php if ($i != 0): ?>
		<a href="<?=site_url("admin/contentup/" . $link["pageid"] . "/" . $link["id"]); ?>">Fel</a> 
		<?php else: ?>
		Fel 
		<?php endif; ?>
		<?php if (!($i == $contsize - 1)): ?>
		<a href="<?=site_url("admin/contentdown/" . $link["pageid"] . "/" . $link["id"]); ?>">Le</a> 
		<?php else: ?>
		Le  
		<?php endif; ?>
		<a href="<?=site_url("admin/editcontent/" . $link["pageid"] . "/" . $link["content_type"] . "/" . $link["content_id"]); ?>">Szerkeszt</a> 
		<a href="<?=site_url("admin/deletecontent/" . $link["pageid"] . "/" . $link["id"]); ?>">Töröl</a><br>
		</div>
		<?php endif; ?>
		<div class="info">
		<?=$data["text"]; ?>
		<?php if ($is_admin): ?>
		<br><br>
		<?php endif; ?>
		</div>