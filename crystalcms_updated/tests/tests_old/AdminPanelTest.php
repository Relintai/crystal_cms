<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\GlobalSettings;
use App\Models\RBACGroups;
use App\Models\RBACPermissions;
use App\Models\RBACRanks;

/**
* This class should test the admin panel, probably the liunks too
*/
class AdminPanelTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIfLinkWorks()
    {
    	$this->create_admin_guest();
        $this->visit('admin/admin_panel')
        	->see('Menu Editor');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIsLinkThere()
    {
        $this->create_admin_guest();
        $this->visit('/')
            ->see('Admin Panel');
    }

    protected function create_admin_guest()
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
