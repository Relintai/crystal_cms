<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <div class="footer">
	   <?php if (!$is_admin): ?>
	   <a href="<?=site_url('/admin/index/'); ?>">Admin</a> 
	   <?php else: ?>
	   <a href="<?=site_url('/admin/logout/'); ?>">Kilépés</a> <a href="<?=site_url('/admin/logoutall/'); ?>">Kilépés minden gépről</a>
	   <?php endif; ?>
	   <div>
	</div>
  </body>
</html>