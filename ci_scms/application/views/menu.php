<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  	<div class="menu">
	<?php foreach ($menu as $row): ?>
      <?php if ($row["link"] != $pageid) echo '<a href="' . site_url('/main/index/' . $row["link"]) . '">'; ?><div class="menuentry<?php if ($row["double"]) echo " menuentrydouble "; ?><?php if ($pageid == $row["link"]) echo " menuentryselected"; ?>"><?=$row["name"]; ?></div><?php if ($row["order"] != $pageid) echo "</a>"; ?>
	<?php endforeach; ?>  
	</div>
	
		 