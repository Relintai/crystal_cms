<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->helper("text"); ?>
		<div class="gallerycontent">
			<a href="<?=site_url("main/index/" . $page); ?>">
			<div class="back">
			<-- Vissza
			</div>
			</a>
			<?php if ($gallery_info["name"] && !is_numeric($gallery_info["name"])): ?>
			<div class="galleryname">
			<?=$gallery_info["name"]; ?>
			</div>
			<?php endif; ?>
			<?php if ($gallery_info["description"]): ?>
			<div class="gallerydescription">
			<?=$gallery_info["description"]; ?>
			</div>
			<?php endif; ?>
		<div class="gallerymaindiv">
			<div class="galleryleft">
				<a href="<?=base_url("img/gallery/orig/" . $curr_pic["img"]); ?>" id="bigimageopen">
				<div class="gallerybigimage">
				<img src="<?=base_url("img/gallery/mid/" . $curr_pic["img"]); ?>" width=470>
				</div>
				</a>
				<?php if ($curr_pic["name"]): ?>
				<div class="galleryimagename">
				<?=$curr_pic["name"]; ?>
				</div>
				<?php endif; ?>
				<?php if ($curr_pic["description"]): ?>
				<div class="galleryimagedescription">
				<?=$curr_pic["description"]; ?>
				</div>
				<?php endif; ?>
				<?php if ($is_admin): ?>
				<div class="galleryimagename">
				<a href="<?=site_url("admin/delgalleryimage/" . $gallery_info["link"] . "/" . $curr_pic["id"]); ?>">Törlés</a>
				</div>
				<?php endif; ?>
			</div>
			<div class="galleryright">
				<?php if ($gallery_data): ?>
				<?php $i = 1; ?>
				<div class="gallerysrow">
				<?php foreach ($gallery_data as $gd): ?>
				<a href="<?=site_url("gallery/view/" . $gallery_info["link"] . "/" . $gd["link"]); ?>">
				<div class="galleryimagecontainer">
					<div class="galleryimage">
					<img src="<?=base_url("img/gallery/thumb/" . $gd["thumb"]); ?>" width=155 height=155>
					</div>
					<div class="gallerythumbdesc">
					<?php if ($gd["name"]): ?>
					<?=ellipsize($gd["name"], 20); ?>
					<?php endif; ?>
					</div>
					<div class="fc"></div>
				</div>
				</a>
				<?php if ($i % 3 == 0): ?>
				</div>
				<div class="gallerysrow">
				<?php endif; ?>
				<?php $i++; ?>
				<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<?php if ($is_admin): ?>
				<div class="galleryrow">
				<a href="<?=site_url("admin/addgalleryimage/" . $gallery_info["id"]) ?>">
				<div class="galleryimagecontainer">
					<div class="galleryimage">
						<img src="<?=base_url("img/plus.png") ?>" alt="Kép Hozzáadás" width="155" height="155">
					</div>
					<div class="gallerythumbdesc">
						Kép Hozzáadása
					</div>
					<div class="fc"></div>
				</div>
				</a>
				</div>
				<?php endif; ?>
				<div class="fc"></div>
			</div>
			<div class="fc"></div>
		</div>
		</div>