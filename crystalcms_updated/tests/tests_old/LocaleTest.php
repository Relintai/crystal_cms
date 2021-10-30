<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\GlobalSettings;
use App\Models\RBACGroups;
use App\Models\RBACPermissions;
use App\Models\RBACRanks;


class LocaleTest extends TestCase
{
	use DatabaseTransactions;

    public function testSwitching()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/menu_editor/create')
            ->type('test', 'name_key')
            ->type('asdddte', 'url')
            ->press('Save')
            ->see('Add or Edit successful!');

        $this->visit('/')
        	->see('Test');

        //doesn't matters if eists, because if it doesn't it will be menu.test
        $this->visit('/language/hu')
        	->dontSee('Test');

        $this->visit('/language/en')
        	->see('Test');
    }

    protected function setupGuestPermission()
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
    }
}
