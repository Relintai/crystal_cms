<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\GlobalSettings;
use App\Models\RBACGroups;
use App\Models\RBACPermissions;
use App\Models\RBACRanks;

class MenuEditorTest extends TestCase
{
    use DatabaseTransactions;

    public function testIfAdminPanelLinkCorrect()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/admin_panel')
         ->click('Menu Editor')
         ->seePageIs('/admin/menu_editor');
    }

    public function testIfMenuEditorThere()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/menu_editor')
            ->see('Menu Editor');
    }

    public function testIfMenuEditorNewActionThere()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/menu_editor/create')
            ->see('Menu Editor');
    }
 
    public function testIfCreateWorks()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/menu_editor/create')
            ->type('menutest', 'name_key')
            ->type('menutest', 'url')
            ->press('Save')
            ->see('Add or Edit successful!');
    }

    public function testIfUrlNeedsToBeUnique()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/menu_editor/create')
            ->type('menutest', 'name_key')
            ->type('menutest', 'url')
            ->press('Save')
            ->see('Add or Edit successful!');

        $this->visit('/admin/menu_editor/create')
            ->type('menutest', 'name_key')
            ->type('menutest', 'url')
            ->press('Save')
            ->dontSee('Add or Edit successful!');
    }

    public function testUp()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/menu_editor/create')
            ->type('menutest0', 'name_key')
            ->type('menutest0', 'url')
            ->press('Save')
            ->see('Add or Edit successful!');

        $this->visit('/admin/menu_editor/create')
            ->type('menutest1', 'name_key')
            ->type('menutest1', 'url')
            ->press('Save')
            ->see('Add or Edit successful!');

        $this->visit('/admin/menu_editor/')
            ->press('Up')
            ->see('Menu Editor');
    }

    public function testDown()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/menu_editor/create')
            ->type('menutest0', 'name_key')
            ->type('menutest0', 'url')
            ->press('Save')
            ->see('Add or Edit successful!');

        $this->visit('/admin/menu_editor/create')
            ->type('menutest1', 'name_key')
            ->type('menutest1', 'url')
            ->press('Save')
            ->see('Add or Edit successful!');

        $this->visit('/admin/menu_editor/')
            ->press('Down')
            ->see('Menu Editor');
    }

    public function testDelete()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/menu_editor/create')
            ->type('menutest', 'name_key')
            ->type('menutest', 'url')
            ->press('Save')
            ->see('Add or Edit successful!');

        $this->visit('/admin/menu_editor/')
            ->press('Delete')
            ->see('Menu Editor');
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
