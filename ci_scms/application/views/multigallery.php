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
				<?php if ($curr_pic): ?>
				<a href="<?=base_url("img/mgallery/big/" . $curr_pic["big"]); ?>" id="bigimageopen">
				<div class="gallerybigimage">
				<img src="<?=base_url("img/mgallery/mid/" . $curr_pic["mid"]); ?>" alt="<?=$curr_pic["name"]; ?>" width=470>
				</div>
				</a>
				<?php else: ?>
				<div class="gallerybigimage">
				<br><br>
				Ebben a mappában jelenleg nincs kép!
				<br><br>
				</div>
				<?php endif; ?>
				<?php if ($curr_pic["name"] && !is_numeric($curr_pic["name"])): ?>
				<div class="galleryimagename">
				<?=$curr_pic["name"]; ?>
				</div>
				<?php endif; ?>
				<?php if ($curr_pic["description"]): ?>
				<div class="galleryimagedescription">
				<?=$curr_pic["description"]; ?>
				</div>
				<?php endif; ?>
				<?php if ($is_admin && $curr_pic): ?>
				<div class="galleryimagename">
				<a href="<?=site_url("admin/delmultigalleryimage/" . $gallery_info["galleryid"] . "/" . $gallery_info["name"] . "/" . $curr_pic["id"]); ?>">Törlés</a>
				</div>
				<?php endif; ?>
			</div>
			<div class="galleryright">
				<?php if ($gallery_data): ?>
				<div class="mgallerysrow">
				<?php $i = 1; ?>
				<?php foreach ($gallery_data as $gd): ?>
				<a href="<?=site_url("mgallery/view/" . $galleryname . "/" . $gallery_info["link"] . "/" . $gd["link"]); ?>">
				<div class="galleryimagecontainer">
					<div class="galleryimage">
					<img src="<?=base_url("img/mgallery/thumb/" . $gd["thumb"]); ?>" alt="<?=$gd["name"]; ?>" width=155 height=155>
					</div>
					<div class="gallerythumbdesc">
					<?php if ($gd["name"] && !is_numeric($gd["name"])): ?>
					<?=ellipsize($gd["name"], 18); ?>
					<?php endif; ?>
					</div>
				</div>
				</a>
				<?php if ($i % 3 == 0): ?>
				</div>
				<div class="mgallerysrow">
				<?php endif; ?>
				<?php $i++; ?>
				<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<?php if ($is_admin): ?>
				<div class="mgallerysrow">
				<a href="<?=site_url("admin/addmultigalleryimage/" . $gallery_info["galleryid"] . "/" . $gallery_info["id"]) ?>">
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