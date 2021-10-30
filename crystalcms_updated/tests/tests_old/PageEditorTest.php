<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\GlobalSettings;
use App\Models\RBACGroups;
use App\Models\RBACPermissions;
use App\Models\RBACRanks;

class PageEditorTest extends TestCase
{
    use DatabaseTransactions;

    public function testIfAdminPanelLinkCorrect()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/admin_panel')
         ->click('Page Editor')
         ->seePageIs('/admin/page_editor');
    }

    public function testIfPageEditorThere()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/page_editor')
            ->see('Page Editor');
    }

    public function testIfPageEditorNewActionThere()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/page_editor/create')
            ->see('Page Editor');
    }

    public function testIfNewPageWorks()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/page_editor/create')
            ->type('pagetest', 'name')
            ->type('pagetest', 'url')
            ->press('Save')
            ->see('Add or Edit successful!');
    }

    public function testIfNewPageUrlUniquenessWorks()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/page_editor/create')
            ->type('pagetest', 'name')
            ->type('pagetest', 'url')
            ->press('Save')
            ->see('Add or Edit successful!');

        $this->visit('/admin/page_editor/create')
            ->type('pagetest', 'name')
            ->type('pagetest', 'url')
            ->press('Save')
            ->dontSee('Add or Edit successful!');
    }

    public function testIfEditPageUrlUniquenessWorks()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/page_editor/create')
            ->type('pagetest', 'name')
            ->type('pagetest', 'url')
            ->press('Save')
            ->see('Add or Edit successful!');

        $this->visit('/admin/page_editor/create')
            ->type('pagetes', 'name')
            ->type('pagetes', 'url')
            ->press('Save')
            ->see('Add or Edit successful!');

        $this->visit('/admin/page_editor')
            ->click('pagetest')
            ->type('pagetes', 'name')
            ->type('pagetes', 'url')
            ->press('Save')
            ->dontSee('Add or Edit successful!');
    }

    public function testDelete()
    {
        $this->setupGuestPermission();
        
        $this->visit('/admin/page_editor/create')
            ->type('pagetest', 'name')
            ->type('pagetest', 'url')
            ->press('Save')
            ->see('Add or Edit successful!');

        $this->visit('/admin/page_editor')
            ->press('Delete')
            ->see('Page Editor');
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