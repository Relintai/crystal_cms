<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <div class="content">
		<?php if (isset($htmld) && sizeof($htmld) > 0): ?>
		<?php for ($i = 0; $i < sizeof($htmld); $i++): ?>
		<div class="entry">
		<?=$htmld[$i]; ?>
		</div>
		<?php endfor; ?>
		<?php endif; ?>
		<?php if ($is_admin): ?>
		<div class="entry">
			<a href="<?=site_url(); ?>/admin/addcontent/<?=$pageid ?>/1">[Szövegdoboz hozzáadása]</a> 
			<a href="<?=site_url(); ?>/admin/addcontent/<?=$pageid ?>/3">[Galéria hozzáadása]</a> 
			<a href="<?=site_url(); ?>/admin/addcontent/<?=$pageid ?>/4">[Többmappás Galéria hozzáadása]</a> 
		</div>
		<?php endif; ?>
	</div>