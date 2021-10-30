<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\GlobalSettings;
use App\Models\RBACGroups;
use App\Models\RBACPermissions;
use App\Models\RBACRanks;

class ThemeSwitcherTest extends TestCase
{
    use DatabaseTransactions;

    public function testIfAdminPanelLinkThere()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/admin_panel')
            ->see('Theme Editor');
    }

    public function testIfAdminPanelLinkWorks()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/admin_panel')
            ->click('Theme Editor')
            ->see('Theme Editor');
    }

    public function testIfSaveWorks()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/admin_panel')
            ->click('Theme Editor')
            ->press('Save')
            ->see('Theme Editor');
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
