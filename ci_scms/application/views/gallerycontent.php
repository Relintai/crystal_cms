<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->helper("text"); ?>
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
		<?php if ($data["main"]["name"] && !is_numeric($data["main"]["name"])): ?>
		<?=$data["main"]["name"]; ?><br>
		<?php endif; ?>
		<?php if ($data["main"]["description"]): ?>
		<?=$data["main"]["description"]; ?><br>
		<?php endif; ?>
		<?php if ($is_admin): ?>
		<br><br>
		<?php endif; ?>
		</div>
		<div class="imgboxes">
			<?php if ($data["data"]): ?>
			<?php $i = 1; ?>
			<div class="galleryrow">
			<?php foreach ($data["data"] as $d): ?>
			<div class="imgbox">
				<div class="imgboximgdiv">
					<a href="<?=site_url("gallery/view/" . $data["main"]["link"] . "/" . $d["link"]); ?>">
					<img src="<?=base_url("img/gallery/thumb/" . $d["thumb"]); ?>" alt="<?php if ($d["name"]) echo $d["name"]; ?>" width="155" height="155">
					</a>
				</div>
				<div class="imgboxtextdiv">
				<?php if ($d["name"] && !is_numeric($d["name"])): ?>
					<?=ellipsize($d["name"], 20); ?>
				<?php endif; ?>
				</div>
			</div>
			<?php if ($i % 5 == 0): ?>
			</div>
			<div class="galleryrow">
			<?php endif; ?>
			<?php $i++; ?>
			<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<?php if ($is_admin): ?>
			<div class="galleryrow">
			<div class="imgbox">
				<div class="imgboximgdiv">
					<a href="<?=site_url("admin/addgalleryimage/" . $link["content_id"]) ?>">
					<img src="<?=base_url("img/plus.png") ?>" alt="Kép Hozzáadás" width="155" height="155">
					</a>
				</div>
				<div class="imgboxtextdiv">
					Kép Hozzáadása
				</div>
			</div>
			</div>
			<?php endif; ?>
		</div>