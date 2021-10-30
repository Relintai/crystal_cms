<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\GlobalSettings;
use App\Models\RBACGroups;
use App\Models\RBACPermissions;
use App\Models\RBACRanks;
use App\Models\Menu;

class RBACMiddlewareTest extends TestCase
{
	use DatabaseTransactions;

    public function testAllPermission()
    {
    	$rank = new RBACRanks();
    	$rank->name = "test";
    	$rank->name_internal = "test";
        $rank->settings = "showadminpanellink";
    	$rank->save();

    	$permissions = new RBACPermissions();
    	$permissions->permissions = "all";
    	$permissions->save();

    	$group = new RBACGroups();
    	$group->rank_id = $rank->id;
    	$group->permission_id = $permissions->id;
    	$group->name = "test";
    	$group->url = "/";
    	$group->revoke = false;
    	$group->sort_order = 0;
    	$group->save();

    	$s = GlobalSettings::find(1);
    	$s->value = $rank->id;
    	$s->save();


    	$this->visit('/')
         ->see('Admin panel');
    }

    public function testNonePermission()
    {
    	$rank = new RBACRanks();
    	$rank->name = "test";
    	$rank->name_internal = "test";
    	$rank->save();

    	$permissions = new RBACPermissions();
    	$permissions->permissions = "none";
    	$permissions->save();

    	$group = new RBACGroups();
    	$group->rank_id = $rank->id;
    	$group->permission_id = $permissions->id;
    	$group->name = "test";
    	$group->url = "/";
    	$group->revoke = false;
    	$group->sort_order = 0;
    	$group->save();

    	$s = GlobalSettings::find(1);
    	$s->value = $rank->id;
    	$s->save();

    	$response = $this->call('GET', '/');

    	$this->assertEquals(404, $response->status());
    }

    public function testViewPermission()
    {
        $this->addTestMenu();

    	$rank = new RBACRanks();
    	$rank->name = "test";
    	$rank->name_internal = "test";
    	$rank->save();

    	$permissions = new RBACPermissions();
    	$permissions->permissions = "view";
    	$permissions->save();

    	$group = new RBACGroups();
    	$group->rank_id = $rank->id;
    	$group->permission_id = $permissions->id;
    	$group->name = "test";
    	$group->url = "/";
    	$group->revoke = false;
    	$group->sort_order = 0;
    	$group->save();

    	$s = GlobalSettings::find(1);
    	$s->value = $rank->id;
    	$s->save();


    	$this->visit('/')
         ->see('Test');

    	$response = $this->call('GET', '/admin/admin_panel');

    	$this->assertEquals(200, $response->status());
    }

    public function testRevokeViewPermission()
    {
        $this->addTestMenu();
        
    	$rank = new RBACRanks();
    	$rank->name = "test";
    	$rank->name_internal = "test";
    	$rank->save();

    	$permissions = new RBACPermissions();
    	$permissions->permissions = "all";
    	$permissions->save();

    	$group = new RBACGroups();
    	$group->rank_id = $rank->id;
    	$group->permission_id = $permissions->id;
    	$group->name = "test";
    	$group->url = "/";
    	$group->revoke = false;
    	$group->sort_order = 0;
    	$group->save();

    	$permissionsr = new RBACPermissions();
    	$permissionsr->permissions = "view,redirect";
    	$permissionsr->save();

    	$groupr = new RBACGroups();
    	$groupr->rank_id = $rank->id;
    	$groupr->permission_id = $permissionsr->id;
    	$groupr->name = "test2";
    	$groupr->url = "admin/admin_panel";
    	$groupr->revoke = true;
    	$groupr->sort_order = 1;
    	$groupr->save();

    	$s = GlobalSettings::find(1);
    	$s->value = $rank->id;
    	$s->save();

    	$this->visit('/')
         ->see('Test');

    	$response = $this->call('GET', '/admin/admin_panel');

    	$this->assertEquals(404, $response->status());
    }

    public function testViewRedirectPermission()
    {
        $this->addTestMenu();
        
    	$rank = new RBACRanks();
    	$rank->name = "test";
    	$rank->name_internal = "test";
    	$rank->save();

    	$permissions = new RBACPermissions();
    	$permissions->permissions = "all";
    	$permissions->save();

    	$group = new RBACGroups();
    	$group->rank_id = $rank->id;
    	$group->permission_id = $permissions->id;
    	$group->name = "test";
    	$group->url = "/";
    	$group->revoke = false;
    	$group->sort_order = 0;
    	$group->save();

    	$permissionsr = new RBACPermissions();
    	$permissionsr->permissions = "view";
    	$permissionsr->save();

    	$groupr = new RBACGroups();
    	$groupr->rank_id = $rank->id;
    	$groupr->permission_id = $permissionsr->id;
    	$groupr->name = "test2";
    	$groupr->url = "admin/admin_panel";
    	$groupr->revoke = true;
    	$groupr->sort_order = 1;
    	$groupr->save();

    	$s = GlobalSettings::find(1);
    	$s->value = $rank->id;
    	$s->save();

    	$this->visit('/')
         ->see('Test');

    	$response = $this->call('GET', '/admin/admin_panel');

    	$this->assertEquals(302, $response->status());
    }

    protected function addTestMenu()
    {
        $m = new Menu();
        $m->name_key = 'test';
        $m->url = 'test';
        $m->sort_order = 0;
        $m->save();
    }
}
