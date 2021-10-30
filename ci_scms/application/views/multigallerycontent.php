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
			<?php if ($data["folders"]): ?>
			<?php foreach ($data["folders"] as $d): ?>
			<div class="imgbox">
				<div class="imgboximgdiv">
					<a href="<?=site_url("mgallery/view/" . $data["main"]["link"] . "/" . $d["link"]); ?>">
					<?php if ($d["description"]): ?>
					<img src="<?=base_url("img/mgallery/folder/" . $d["thumb"]) ?>" alt="<?=$d["description"]; ?>" width="202" height="202">
					<?php else: ?>
					<img src="<?=base_url("img/mgallery/folder/" . $d["thumb"]) ?>" alt="" width="202" height="202">
					<?php endif; ?>
					</a>
				</div>
				<?php if ($d["name"] && !is_numeric($d["name"])): ?>
				<div class="imgboxtextdiv">
					<?=ellipsize($d["name"], 25); ?>
				</div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
			<?php endif; ?>
			<?php if ($is_admin): ?>
			<div class="imgbox">
				<div class="imgboximgdiv">
					<a href="<?=site_url("admin/addmultigalleryfolder/" . $link["content_id"]) ?>">
					<img src="<?=base_url("img/plus.png") ?>" alt="Mappa Hozzáadása" width="202" height="202">
					</a>
				</div>
				<div class="imgboxtextdiv">
					Mappa Hozzáadása
				</div>
			</div>
			<?php endif; ?>
		</div>